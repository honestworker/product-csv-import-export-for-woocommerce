<?php
if ( ! defined( 'WPINC' ) ) {
	exit;
}

class WF_ProdImpExpCsv_AJAX_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_woocommerce_csv_import_request', array( $this, 'csv_import_request' ) );
		add_action( 'wp_ajax_woocommerce_csv_import_regenerate_thumbnail', array( $this, 'regenerate_thumbnail' ) );
        add_action( 'wp_ajax_product_csv_export_mapping_change', array( $this, 'export_mapping_change_columns' ) );
		add_action( 'wp_ajax_product_test_ftp_connection', array( $this, 'test_ftp_credentials' ) );
		
		add_action( 'wp_ajax_advanced-save-settings-upload-file', array( $this, 'advanced_save_settings_upload_file' ) );
		add_action( 'wp_ajax_advanced-import-file', array( $this, 'advanced_import_file' ) );
	}
	
	/**
	 * Ajax event for importing a CSV
	 */
	public function csv_import_request() {
		define( 'WP_LOAD_IMPORTERS', true );
                WF_ProdImpExpCsv_Importer::product_importer();
	}

	/**
	 * From regenerate thumbnails plugin
	 */
	public function regenerate_thumbnail() {
		@error_reporting( 0 ); // Don't break the JSON result

		header( 'Content-type: application/json' );

		$id    = (int) $_REQUEST['id'];
		$image = get_post( $id );

		if ( ! $image || 'attachment' != $image->post_type || 'image/' != substr( $image->post_mime_type, 0, 6 ) )
			die( json_encode( array( 'error' => sprintf( __( 'Failed resize: %s is an invalid image ID.', 'wf_csv_import_export' ), esc_html( $_REQUEST['id'] ) ) ) ) );

		if ( ! current_user_can( 'manage_woocommerce' ) )
			$this->die_json_error_msg( $image->ID, __( "Your user account doesn't have permission to resize images", 'wf_csv_import_export' ) );

		$fullsizepath = get_attached_file( $image->ID );

		if ( false === $fullsizepath || ! file_exists( $fullsizepath ) )
			$this->die_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', 'wf_csv_import_export' ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );

		@set_time_limit( 120 ); // 2 minutes per image should be PLENTY

		$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );
 
		if ( is_wp_error( $metadata ) )
			$this->die_json_error_msg( $image->ID, $metadata->get_error_message() );
		if ( empty( $metadata ) )
			$this->die_json_error_msg( $image->ID, __( 'Unknown failure reason.', 'wf_csv_import_export' ) );

		// If this fails, then it just means that nothing was changed (old value == new value)
		wp_update_attachment_metadata( $image->ID, $metadata );

		die( json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) was successfully resized in %3$s seconds.', 'wf_csv_import_export' ), esc_html( get_the_title( $image->ID ) ), $image->ID, timer_stop() ) ) ) );
	}	

	/**
	 * Die with a JSON formatted error message
	 */
	public function die_json_error_msg( $id, $message ) {
        die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) failed to resize. The error message was: %3$s', 'regenerate-thumbnails' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
    }
    
                    
    /**
     * Ajax event for changing mapping of export CSV
     */
    public function export_mapping_change_columns() {

        $selected_profile = !empty($_POST['v_new_profile']) ? $_POST['v_new_profile'] : '';
        
        $post_columns = array();
        if (!$selected_profile) {
            $post_columns = include( 'exporter/data/data-wf-post-columns.php' );

            $post_columns['images'] = 'Images (featured and gallery)';
            $post_columns['file_paths'] = 'Downloadable file paths';
            $post_columns['taxonomies'] = 'Taxonomies (cat/tags/shipping-class)';
            $post_columns['attributes'] = 'Attributes';
            $post_columns['meta'] = 'Meta (custom fields)';
            $post_columns['product_page_url'] = 'Product Page URL';
            if (function_exists('woocommerce_gpf_install'))
                $post_columns['gpf'] = 'Google Product Feed fields';
        }

        $export_profile_array = get_option('xa_prod_csv_export_mapping');

        if (!empty($export_profile_array[$selected_profile])) {
            $post_columns = $export_profile_array[$selected_profile];
        }


        $res = "<tr>
                      <td style='padding: 10px;'>
                          <a href='#' id='pselectall' onclick='return false;' >Select all</a> &nbsp;/&nbsp;
                          <a href='#' id='punselectall' onclick='return false;'>Unselect all</a>
                      </td>
                  </tr>
                  
                <th style='text-align: left;'>
                    <label for='v_columns'>Column</label>
                </th>
                <th style='text-align: left;'>
                    <label for='v_columns_name'>Column Name</label>
                </th>";


        foreach ($post_columns as $pkey => $pcolumn) {

            $res.="<tr>
                <td>
                    <input name= 'columns[$pkey]' type='checkbox' value='$pkey' checked>
                    <label for='columns[$pkey]'>$pkey</label>
                </td>
                <td>";

            $res.="<input type='text' name='columns_name[$pkey]'  value='$pcolumn' class='input-text' />
                </td>
            </tr>";
        }

        echo $res;
        exit;
    }
    
    /**
     * Ajax event to test FTP details
     */
    public function test_ftp_credentials(){
		$wf_prod_ftp_details			= array();
		$wf_prod_ftp_details['host']		= ! empty($_POST['ftp_host']) ? $_POST['ftp_host'] : '';
		$wf_prod_ftp_details['port']		= ! empty($_POST['ftp_port']) ? $_POST['ftp_port'] : 21;
		$wf_prod_ftp_details['userid']		= ! empty($_POST['ftp_userid']) ? $_POST['ftp_userid'] : '';
		$wf_prod_ftp_details['password']	= ! empty($_POST['ftp_password']) ? $_POST['ftp_password'] : '';
		$wf_prod_ftp_details['use_ftps']	= ! empty($_POST['use_ftps']) ? $_POST['use_ftps'] : 0;
		$ftp_conn = (!empty($wf_prod_ftp_details['use_ftps'])) ? @ftp_ssl_connect($wf_prod_ftp_details['host'], $wf_prod_ftp_details['port']) : @ftp_connect($wf_prod_ftp_details['host'], $wf_prod_ftp_details['port']);
		if($ftp_conn == false)
		{
			die("<div id= 'prod_ftp_test_msg' style = 'color : red'>Could not connect to Host. Server host / IP or Port may be wrong.</div>");
		}
		if( @ftp_login($ftp_conn,$wf_prod_ftp_details['userid'],$wf_prod_ftp_details['password']) )
		{
            if ($ftp_conn) {
                ftp_close($ftp_conn);
            }
			die("<div id= 'prod_ftp_test_msg' style = 'color : green'>Successfully logged in.</div>");
		}
		else
		{
            if ($ftp_conn) {
                ftp_close($ftp_conn);
            }
			die("<div id= 'prod_ftp_test_msg' style = 'color : blue'>Connected to host but could not login. Server UserID or Password may be wrong or Try with / without FTPS .</div>");
		}
    }

	
    /**
     * Ajax event for saving the setting and upload file
     */
    public function advanced_save_settings_upload_file() {
        include_once( 'advanced/class-wf-advanced-prodimpexpcsv-settings.php' );
		$wf_prod_advanced_settings= WF_Advanced_ProdImpExpCsv_Settings::save_settings();
		
        include_once( 'importer/class-wf-csv-parser.php' );
        die(json_encode(WF_CSV_Parser::advanced_upload_file($wf_prod_advanced_settings)));
	}
	
    /**
     * Ajax event for saving the setting and upload file
     */
    public function advanced_import_file() {
        include_once( 'advanced/class-wf-advanced-prodimpexpcsv-settings.php' );
		WF_Advanced_ProdImpExpCsv_Settings::set_maaping_settings();
        $wf_prod_advanced_settings = WF_Advanced_ProdImpExpCsv_Settings::get_settings();
        
        $advanced_auto = get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto');
        if (!$advanced_auto) {
            $advanced_auto = 0;
        }
        
        update_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto', 1);
        include_once( 'importer/class-wf-csv-parser.php' );
        include_once( 'importer/class-wf-prodimpexpcsv-product-import.php' );
        $GLOBALS['WF_CSV_Product_Import'] = new WF_ProdImpExpCsv_Product_Import();
        $GLOBALS['WF_CSV_Product_Import']->import_start('', '', 0, 0, 0);
        $GLOBALS['WF_CSV_Product_Import']->import( );
        $GLOBALS['WF_CSV_Product_Import']->import_end();
        update_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto', $advanced_auto);
		die(json_encode($ttpme_result));
	}
}

new WF_ProdImpExpCsv_AJAX_Handler();