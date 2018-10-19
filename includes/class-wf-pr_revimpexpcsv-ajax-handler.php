<?php
if ( ! defined( 'WPINC' ) ) {
	exit;
}

class WF_ProdReviewImpExpCsv_AJAX_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_product_reviews_csv_import_request', array( $this, 'csv_import_request' ) );
		add_action( 'wp_ajax_product_reviews_csv_import_regenerate_thumbnail', array( $this, 'regenerate_thumbnail' ) );
		add_action( 'wp_ajax_product_reviews_test_ftp_connection', array( $this, 'test_ftp_credentials' ) );
	}
	
	/**
	 * Ajax event for importing a CSV
	 */
	public function csv_import_request() {
		define( 'WP_LOAD_IMPORTERS', true );
        WF_PrRevImpExpCsv_Importer::product_importer();
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

		@set_time_limit( 900 ); // 5 minutes per image should be PLENTY

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
     * Ajax event to test FTP details
     */
    public function test_ftp_credentials(){
		$wf_prod_rev_ftp_details		= array();
		$wf_prod_rev_ftp_details['host']	= ! empty($_POST['ftp_host']) ? $_POST['ftp_host'] : '';
		$wf_prod_rev_ftp_details['port']	= ! empty($_POST['ftp_port']) ? $_POST['ftp_port'] : 21;
		$wf_prod_rev_ftp_details['userid']	= ! empty($_POST['ftp_userid']) ? $_POST['ftp_userid'] : '';
		$wf_prod_rev_ftp_details['password']	= ! empty($_POST['ftp_password']) ? $_POST['ftp_password'] : '';
		$wf_prod_rev_ftp_details['use_ftps']	= ! empty($_POST['use_ftps']) ? $_POST['use_ftps'] : 0;
		$ftp_conn = (!empty($wf_prod_rev_ftp_details['use_ftps'])) ? @ftp_ssl_connect($wf_prod_rev_ftp_details['host'], $wf_prod_rev_ftp_details['port']) : @ftp_connect($wf_prod_rev_ftp_details['host'], $wf_prod_rev_ftp_details['port']);
		if($ftp_conn == false)
		{
			die("<div id= 'prod_rev_ftp_test_msg' style = 'color : red'>Could not connect to Host. Server host / IP or Port may be wrong.</div>");
		}
		if( @ftp_login($ftp_conn,$wf_prod_rev_ftp_details['userid'],$wf_prod_rev_ftp_details['password']) )
		{
			if ($ftp_conn) {
				ftp_close($ftp_conn);
			}
			die("<div id= 'prod_rev_ftp_test_msg' style = 'color : green'>Successfully logged in.</div");
		}
		else
		{
			if ($ftp_conn) {
				ftp_close($ftp_conn);
			}
			die("<div id= 'prod_rev_ftp_test_msg' style = 'color : blue'>Connected to host but could not login. Server UserID or Password may be wrong or Try with / without FTPS ..</div>");
		}
    }
}

new WF_ProdReviewImpExpCsv_AJAX_Handler();