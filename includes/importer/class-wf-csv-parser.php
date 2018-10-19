<?php
/**
 * WooCommerce CSV Importer class for managing parsing of CSV files.
 */
class WF_CSV_Parser {

	var $row;
	var $post_type;
	var $reserved_fields;		// Fields we map/handle (not custom fields)
	var $post_defaults;			// Default post data
	var $postmeta_defaults;		// default post meta
	var $postmeta_allowed;		// post meta validation
	var $allowed_product_types;	// Allowed product types

	/**
	 * Constructor
	 */
	public function __construct( $post_type = 'product' ) {
		$this->post_type         = $post_type;
		$this->decentFgetcsv	 = (version_compare(PHP_VERSION, '5.3.0') >= 0);
		$this->reserved_fields   = include( 'data/data-wf-reserved-fields.php' );
		$this->post_defaults     = include( 'data/data-wf-post-defaults.php' );
		$this->postmeta_defaults = include( 'data/data-wf-postmeta-defaults.php' );
		$this->postmeta_allowed  = include( 'data/data-wf-postmeta-allowed.php' );
		
		require_once(dirname(__DIR__).'/class-wf-piep-helper.php');

		$simple_term 	= get_term_by( 'slug', 'simple', 'product_type' );
		$variable_term 	= get_term_by( 'slug', 'variable', 'product_type' );
		$grouped_term 	= get_term_by( 'slug', 'grouped', 'product_type' );
		$external_term 	= get_term_by( 'slug', 'external', 'product_type' );

		$this->allowed_product_types = array(
			'simple' 	=> $simple_term->term_id,
			'variable'	=> $variable_term->term_id,
			'grouped'	=> $grouped_term->term_id,
			'external'	=> $external_term->term_id
			);

		// Subscription product types
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$subscription_term                                    = get_term_by( 'slug', 'subscription', 'product_type' );
			$variable_subscription_term                           = get_term_by( 'slug', 'variable-subscription', 'product_type' );

			$this->allowed_product_types['subscription']          = $subscription_term->term_id;
			$this->allowed_product_types['variable-subscription'] = $variable_subscription_term->term_id;
		}

		// Composite product type
		if ( class_exists( 'WC_Composite_Products' ) ) {
			$composite_term = get_term_by( 'name', 'composite', 'product_type' );

			if ( $composite_term ) {
				$this->allowed_product_types['composite'] = $composite_term->term_id;
			}
		}
		// Simple Auction product type
		if ( class_exists( 'WooCommerce_simple_auction' ) ) {
			$auction_term = get_term_by( 'name', 'auction', 'product_type' );

			if ( $auction_term ) {
				$this->allowed_product_types['auction'] = $auction_term->term_id;
			}
		}

		// Bundle product type
		if ( class_exists( 'WC_Bundles' ) ) {
			$bundle_term = get_term_by( 'name', 'bundle', 'product_type' );

			if ( $bundle_term ) {
				$this->allowed_product_types['bundle'] = $bundle_term->term_id;
			}
		}

                // Wcpb Product Bundle
		if ( class_exists( 'WC_Product_Wcpb' ) ) {
			$wcbundle_term = get_term_by( 'name', 'wcpb', 'product_type' );

			if ( $wcbundle_term ) {
				$this->allowed_product_types['wcpb'] = $wcbundle_term->term_id;
			}
		}
		
		// Booking product types
		if ( class_exists( 'WC_Booking' ) ) {
			$booking_term                           = get_term_by( 'slug', 'booking', 'product_type' );

			if ( $booking_term ) {
				$this->allowed_product_types['booking'] = $booking_term->term_id;
			}
		}

		// Photography product types
		if ( class_exists( 'WC_Photography' ) ) {
			$photography_term			= get_term_by( 'slug', 'photography', 'product_type' );

			if ( $photography_term ) {
				$this->allowed_product_types['photography'] = $photography_term->term_id;
			}
		}
	}

	/**
	 * Format data from the csv file
	 * @param  string $data
	 * @param  string $enc
	 * @return string
	 */
	public function format_data_from_csv( $data, $enc ) {
		return ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
	}

	/**
	 * Parse the data
	 * @param  string  $file      [description]
	 * @param  string  $delimiter [description]
	 * @param  array  $mapping   [description]
	 * @param  integer $start_pos [description]
	 * @param  integer  $end_pos   [description]
	 * @return array
	 */
	public function parse_data( $file, $delimiter, $mapping, $start_pos = 0, $end_pos = null, $eval_field ) {
	    if (get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto')) {
			return $this->advanced_parse_data();
		}
		
        // Set locale
		$enc = mb_detect_encoding( $file, 'UTF-8, ISO-8859-1', true );
		if ( $enc )
			setlocale( LC_ALL, 'en_US.' . $enc );
		@ini_set( 'auto_detect_line_endings', true );

		$parsed_data = array();
		$raw_headers = array();

		// Put all CSV data into an associative array
		if ( ( $handle = @fopen( $file, "r" ) ) !== FALSE ) {

			$header   = ($this->decentFgetcsv)? fgetcsv( $handle, 0, $delimiter , '"', '"' ) : fgetcsv( $handle, 0, $delimiter , '"' );
			if ( $start_pos != 0 )
				fseek( $handle, $start_pos );

			while ( ( $postmeta = ($this->decentFgetcsv)? fgetcsv( $handle, 0, $delimiter , '"', '"' ) : fgetcsv( $handle, 0, $delimiter , '"' ) ) !== FALSE ) {
				$row = array();
				
				foreach ( $header as $key => $heading ) {
					$s_heading = $heading;

	            	// Check if this heading is being mapped to a different field
					if ( isset( $mapping[$s_heading] ) ) {
						if ( $mapping[$s_heading] == 'import_as_meta' ) {

							$s_heading = 'meta:' . $s_heading;

						} elseif ( $mapping[$s_heading] == 'import_as_images' ) {

							$s_heading = 'images';

						} else {
							$s_heading = esc_attr( $mapping[$s_heading] );
						}
					}
					if( !empty($mapping) )
					{
						foreach ($mapping as $mkey => $mvalue) {
							if(trim($mvalue) === trim($heading)){
								$s_heading =  $mkey;
							}
						}
					}

					if ( $s_heading == '' )
						continue;

					if($s_heading=='Images')// added for prevent 'if' condition going to false on function parse_product 
                    $s_heading='images';

	            	// Add the heading to the parsed data
					$row[$s_heading] = ( isset( $postmeta[$key] ) ) ? $this->format_data_from_csv( $postmeta[$key], $enc ) : '';
					
					if( isset($eval_field[$s_heading]) )
					{
						$row[$s_heading] = $this->evaluate_field($row[$s_heading], $eval_field[$s_heading]);
					}
	               	// Raw Headers stores the actual column name in the CSV
					$raw_headers[ $s_heading ] = $heading;
				}
				$parsed_data[] = $row;

				unset( $postmeta, $row );

				$position = ftell( $handle );

				if ( $end_pos && $position >= $end_pos )
					break;
			}
			fclose( $handle );
		}
		return array( $parsed_data, $raw_headers, $position );
	}
	
	private function evaluate_field($value, $evaluation_field){
		$processed_value = $value;
		if(!empty($evaluation_field)){
			$operator = substr($evaluation_field, 0, 1);
			if(in_array($operator, array('=', '+', '-', '*', '/', '&','@'))){
				if( strpos( $evaluation_field, '%', -0) )	//Execute when % is given in evaluation field, format +10% or -10%
				{
					$eval_val = substr($evaluation_field, 1,-1);
					switch($operator)
					{
						case '+':
							$processed_value = $value + ( ( $value * $eval_val) / 100 ); 
							break;
						case '-': 
							$processed_value = $value - ( ( $value * $eval_val ) / 100 ); 
							break;
					}
				}
				else
				{
					$eval_val = substr($evaluation_field, 1);
					switch($operator){
						case '=':
						$processed_value = trim($eval_val); 
						break;
						case '+':
						$processed_value = $this->hf_currency_formatter($value) + $eval_val; 
						break;
						case '-': 
						$processed_value = $value - $eval_val; 
						break;
						case '*': 
						$processed_value = $value * $eval_val; 
						break;
						case '/': 
						$processed_value = $value / $eval_val; 
						break;
						case '@': 
							if(!empty($value)){
								if (!(bool) strtotime($value)) {
									$value = str_replace("/", "-", $value);
									$eval_val = str_replace("/", "-", $eval_val);
								}
								if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
									$date = DateTime::createFromFormat($eval_val, $value);
									$processed_value = $date->format('Y-m-d H:i:s');
								} else {
									$processed_value = date("d-m-Y H:i:s", strtotime($value));
								}
							}

							break;
						case '&': 
						if (strpos($eval_val, '[VAL]') !== false) {
							$processed_value = str_replace('[VAL]',$value,$eval_val);								 
						}
						else{
							$processed_value = $value . $eval_val;
						}
						break;					
					}
				}
			}	
		}
		return $processed_value;	
	}

	public function advanced_upload_file($wf_prod_advanced_settings) {

        $multi_csv_import_enabled 				= false;
		
		$local_file = 'wp-content/plugins/product-csv-import-export-for-woocommerce/temp-advanced-import.csv';
		$server_file = $wf_prod_advanced_settings['pro_server_path'];
		
		$ftp_conn = (!empty($wf_prod_advanced_settings['pro_use_ftps'])) ? @ftp_ssl_connect($wf_prod_advanced_settings['pro_ftp_server'], $wf_prod_advanced_settings['pro_ftp_port']) : @ftp_connect($wf_prod_advanced_settings['pro_ftp_server'], $wf_prod_advanced_settings['pro_ftp_port']);
		
		$error_message = "";
		
		if($ftp_conn == false)
		{
			$error_message = __("Could not connect to Host. Server host / IP or Port may be wrong.");
		} else {
			if( @ftp_login($ftp_conn, $wf_prod_advanced_settings['pro_ftp_user'], $wf_prod_advanced_settings['pro_ftp_password']) )
			{
				if ($wf_prod_advanced_settings['pro_use_ftps']) {
					ftp_pasv($ftp_conn, TRUE);
				}
				
				if ($multi_csv_import_enabled) {
					$server_csv_files = ftp_nlist($ftp_conn, $ftp_server_path . "/*.csv");
					if ($server_csv_files) {
						foreach ($server_csv_files as $key => $server_file1) {
							if (@ftp_get($ftp_conn, ABSPATH . "wp-content/plugins/product-csv-import-export-for-woocommerce/temp-import_$key.csv", $server_file1, FTP_BINARY)) {
								
							} else {
								$error_message = __("Failed to Download Specified file in FTP Server File Path.<br/><br/><b>Possible Reasons</b><br/><b>1.</b> File path may be invalid.<br/><b>2.</b> Maybe File / Folder Permission missing for specified file or folder in path.<br/><b>3.</b> Write permission may be missing for file <b>plugins/product-csv-import-export-for-woocommerce/temp-import.csv</b> .\n");
								break;
							}
						}
					}
				} else {
					if (@ftp_get($ftp_conn, ABSPATH . $local_file, $server_file, FTP_BINARY)) {
						$error_message = "";
					} else {
						$error_message = __("Failed to Download Specified file in FTP Server File Path.<br/><br/><b>Possible Reasons</b><br/><b>1.</b> File path may be invalid.<br/><b>2.</b> Maybe File / Folder Permission missing for specified file or folder in path.<br/><b>3.</b> Write permission may be missing for file <b>plugins/product-csv-import-export-for-woocommerce/temp-import.csv</b> .\n");
					}
				}
			}
			else
			{
				$error_message = __("Connected to host but could not login. Server UserID or Password may be wrong or Try with / without FTPS .\n");
			}
		}
		
		if ($ftp_conn) {
			ftp_close($ftp_conn);
		}
		if ($error_message) {
			return ("<div id='prod_advanced_save_settgins_upload_file' style = 'color : blue'>" . $error_message . "</div>");
		} else {
			// Set locale
			$enc = mb_detect_encoding($local_file, 'UTF-8, ISO-8859-1', true);
			if ($enc)
				setlocale(LC_ALL, 'en_US.' . $enc);
			@ini_set('auto_detect_line_endings', true);
			
			$file = ABSPATH . $local_file;
			$file = str_replace("\\", "/", $file);
			
			$categories = array();
			// Get headers
			if (( $handle = @fopen($file, "r") ) !== FALSE) {
				$headers = fgetcsv($handle, 0, $wf_prod_advanced_settings['pro_delimiter'], '"', '"');
				while (( $postmeta = fgetcsv($handle, 0, $wf_prod_advanced_settings['pro_delimiter'], '"', '"') ) !== FALSE) {
					$main_category = $sub_category = "";
					foreach ($headers as $key => $heading) {
						if (!$heading)
							continue;
						$row_value = ( isset($postmeta[$key]) ) ? WF_CSV_Parser::format_data_from_csv($postmeta[$key], $enc) : '';
						
						if ($heading == $wf_prod_advanced_settings['pro_main_category']) {
							$main_category = $row_value;
						}
						if ($heading == $wf_prod_advanced_settings['pro_sub_category']) {
							$sub_category = $row_value;
						}
					}
					if (isset($categories[$main_category])) {
						if (!in_array($sub_category, $categories[$main_category])) {
							array_push($categories[$main_category], $sub_category);
						}
					} else {
						$categories[$main_category] = array($sub_category);
					}
				}
				fclose($handle);
				
				require_once( dirname(__DIR__) . '/advanced/class-wf-advanced-prodimpexpcsv-importer.php' );
				$messages = WF_Advanced_ProdImpExpCsv_Importer::get_categories_html($headers, $categories, $wf_prod_advanced_settings['pro_mapping_category']);
				
				return $messages;
			} else {
				return ("<div id='prod_advanced_save_settgins_upload_file' style = 'color : blue'>Failed to Open the downloaded file .\n</div>");
			}
		}
	}
	
	/**
	 * Advanced Parse the data
	 * @return array
	 */
	private function advanced_parse_data() {
	    global $wpdb;
        include_once( dirname(__DIR__). '/advanced/class-wf-advanced-prodimpexpcsv-settings.php' );
		$wf_prod_advanced_settings = WF_Advanced_ProdImpExpCsv_Settings::get_settings();

		$mapping  = include_once( dirname(__DIR__).'/advanced/data/data-wf-advanced-mapping-defaults.php' );
		// Set locale
		$enc = mb_detect_encoding( $file, 'UTF-8, ISO-8859-1', true );
		if ( $enc )
			setlocale( LC_ALL, 'en_US.' . $enc );
		@ini_set( 'auto_detect_line_endings', true );
		
		$parsed_data = array();
		$raw_headers = array();
		
		$local_file = 'wp-content/plugins/product-csv-import-export-for-woocommerce/temp-advanced-import.csv';
		$server_file = $wf_prod_advanced_settings['pro_server_path'];
		
		$ftp_conn = (!empty($wf_prod_advanced_settings['pro_use_ftps'])) ? @ftp_ssl_connect($wf_prod_advanced_settings['pro_ftp_server'], $wf_prod_advanced_settings['pro_ftp_port']) : @ftp_connect($wf_prod_advanced_settings['pro_ftp_server'], $wf_prod_advanced_settings['pro_ftp_port']);
		
		$error_message = "";
		
		if($ftp_conn == false)
		{
			$error_message = __("Could not connect to Host. Server host / IP or Port may be wrong.");
		} else {
			if( @ftp_login($ftp_conn, $wf_prod_advanced_settings['pro_ftp_user'], $wf_prod_advanced_settings['pro_ftp_password']) )
			{
				if ($wf_prod_advanced_settings['pro_use_ftps']) {
					ftp_pasv($ftp_conn, TRUE);
				}
				
				if ($multi_csv_import_enabled) {
					$server_csv_files = ftp_nlist($ftp_conn, $ftp_server_path . "/*.csv");
					if ($server_csv_files) {
						foreach ($server_csv_files as $key => $server_file1) {
							if (@ftp_get($ftp_conn, ABSPATH . "wp-content/plugins/product-csv-import-export-for-woocommerce/temp-import_$key.csv", $server_file1, FTP_BINARY)) {
								
							} else {
								$error_message = __("Failed to Download Specified file in FTP Server File Path.<br/><br/><b>Possible Reasons</b><br/><b>1.</b> File path may be invalid.<br/><b>2.</b> Maybe File / Folder Permission missing for specified file or folder in path.<br/><b>3.</b> Write permission may be missing for file <b>plugins/product-csv-import-export-for-woocommerce/temp-import.csv</b> .\n");
								break;
							}
						}
					}
				} else {
					if (@ftp_get($ftp_conn, ABSPATH . $local_file, $server_file, FTP_BINARY)) {
						$error_message = "";
					} else {
						$error_message = __("Failed to Download Specified file in FTP Server File Path.<br/><br/><b>Possible Reasons</b><br/><b>1.</b> File path may be invalid.<br/><b>2.</b> Maybe File / Folder Permission missing for specified file or folder in path.<br/><b>3.</b> Write permission may be missing for file <b>plugins/product-csv-import-export-for-woocommerce/temp-import.csv</b> .\n");
					}
				}
			}
			else
			{
				$error_message = __("Connected to host but could not login. Server UserID or Password may be wrong or Try with / without FTPS .\n");
			}
		}
		
		$image_upload_dir = '/wp-content/uploads/wp-wf-advanced-importer/';
		if (!$error_message) {
			$file = ABSPATH . $local_file;
			$file = str_replace("\\", "/", $file);
			
			// Put all CSV data into an associative array
			if ( ( $handle = @fopen( $file, "r" ) ) !== FALSE ) {
				$header   = ($this->decentFgetcsv)? fgetcsv( $handle, 0, $wf_prod_advanced_settings['pro_delimiter'] , '"', '"' ) : fgetcsv( $handle, 0, $wf_prod_advanced_settings['pro_delimiter'] , '"' );
				$skus = array();
				
				$tmp_product_count = 0;
				$upload_image_count = 0;
				while ( ( $postmeta = ($this->decentFgetcsv)? fgetcsv( $handle, 0, $wf_prod_advanced_settings['pro_delimiter'] , '"', '"' ) : fgetcsv( $handle, 0, $wf_prod_advanced_settings['pro_delimiter'] , '"' ) ) !== FALSE ) {
					$row = array();
					foreach ( $header as $key => $heading ) {
						$s_heading = $heading;
						// Check if this heading is being mapped to a different field
						if ( isset( $mapping[$s_heading] ) ) {
							if ( $mapping[$s_heading] == 'import_as_meta' ) {
								$s_heading = 'meta:' . $s_heading;
							} else if ( $mapping[$s_heading] == 'import_as_attr' ) {
								$s_heading = 'attribute:' . $s_heading;
							} elseif ( $mapping[$s_heading] == 'import_as_images' ) {								
								$s_heading = 'images';
							} else {
								$s_heading = esc_attr( $mapping[$s_heading] );
							}
						}
						if ( !empty($mapping) )
						{
							foreach ($mapping as $mkey => $mvalue) {
								if(trim($mvalue) === trim($heading)){
									$s_heading =  $mkey;
								}
							}
						}
						
						if ( $s_heading == '' )
							continue;
							
						if($s_heading=='Images') // added for prevent 'if' condition going to false on function parse_product 
							$s_heading='images';
							
						// Add the heading to the parsed data
						$row[$s_heading] = ( isset( $postmeta[$key] ) ) ? $this->format_data_from_csv( $postmeta[$key], $enc ) : '';
						
						if( isset($eval_field[$s_heading]) )
						{
							$row[$s_heading] = $this->evaluate_field($row[$s_heading], $eval_field[$s_heading]);
						}
						if ($s_heading == 'post_content') {
							$row['post_excerpt'] = $row['post_content'];
						} else if ($s_heading == 'price') {
							$row['regular_price'] = $row['price'];
							//$row['sale_price'] = $row['price'];
						}
						// Raw Headers stores the actual column name in the CSV
						$raw_headers[ $s_heading ] = $heading;
					}
					
					if (isset($skus[$row['sku']])) {
						$skus[$row['sku']]['index'] += 1;
						
						$row['parent_sku'] = $row['sku']['sku'];
						if (isset($row['attributes'])) {
							$attributes = explode(';', $row['attributes']);
							if ($attributes) {
								foreach ($attributes as $attribute) {
									$attr_key_val = explode('=', $attribute);
									$attr_key = trim(strtolower($attr_key_val[0]));
									$parsed_data[$row['parent_sku']]['tax:product_type'] = 'variable';
									if (isset($parsed_data[$row['parent_sku']]['attribute:pa_' . $attr_key])) {
										$attr_data = $parsed_data[$row['parent_sku']]['attribute_data:pa_' . $attr_key];
										$attr_data_array = explode('|', $attr_data);
										if ($attr_data_array) {
											if (!in_array($attr_key_val[1], $attr_data_array)) {
												$parsed_data[$row['parent_sku']]['attribute:pa_' . $attr_key] = $parsed_data[$row['parent_sku']]['attribute:pa_' . $attr_key] . '|' . trim($attr_key_val[1]);
											}
										} else {
											$parsed_data[$row['parent_sku']]['attribute:pa_' . $attr_key] = trim($attr_key_val[1]);
											$parsed_data[$row['parent_sku']]['attribute_default:pa_' . $attr_key] = trim($attr_key_val[1]);
										}
									} else {
										$parsed_data[$row['parent_sku']]['attribute:pa_' . $attr_key] = trim($attr_key_val[1]);
										$parsed_data[$row['parent_sku']]['attribute_default:pa_' . $attr_key] = trim($attr_key_val[1]);
									}
									$row['meta:attribute_pa_' . $attr_key] = trim($attr_key_val[1]);
									$row['attribute:' . $attr_key] = trim($attr_key_val[1]);
								}
							}
						}
					} else {
						$row['parent_sku'] = '';
						
						$row['tax:product_type'] = 'simple';
						if (isset($row['attributes'])) {
							if ($row['attributes']) {
								$attributes = explode(';', $row['attributes']);
								if ($attributes) {
									foreach ($attributes as $attribute) {
										$attr_key_val = explode('=', $attribute);
										$attr_key = trim(strtolower($attr_key_val[0]));
										//$row['attribute:pa_' . $attr_key] = trim($attr_key_val[0]);
										//$row['attribute_data:pa_' . $attr_key] = trim($attr_key_val[1]);
										//$row['attribute_default:pa_' . $attr_key] = trim($attr_key_val[1]);
										//$row['meta:attribute_pa_' . $attr_key] = trim($attr_key_val[1]);
										$row['attribute:' . $attr_key] = trim($attr_key_val[1]);
										$raw_headers[ 'attribute:' . $attr_key ] = $attr_key_val[0];
									}
								}
							}
						}
						$skus[$row['sku']] = array('index' => 1, 'sku' => $row['sku']);
					}

					if ($row['STAT'] == 'A') {
						$row['manage_stock'] = 'yes';
					}
					
					if ($row['subCategory']) {
						$row['tax:product_cat'] = $wf_prod_advanced_settings['pro_mapping_category'][$row['mainCategory']][$row['subCategory']];
					} else {
						$row['tax:product_cat'] = $wf_prod_advanced_settings['pro_mapping_category'][$row['mainCategory']]['_default_'];
					}
					unset($row['attributes'], $row['mainCategory'], $row['subCategory']);
					
					if ($row['tax:product_cat']) {
						if ($upload_image_count < 300) {
							$image_name = $row['sku'] . '_' . $skus[$row['sku']]['index'] . '.jpg';
							if (isset($parsed_data[$row['parent_sku']])) {							
								$post_pass_type = '"'. 'product' .'"';
							} else {
								$post_pass_type = '"'. 'product_variation' .'"';
							}
							$image_upload_flag = 1;
							$db_query = $wpdb->prepare("
								SELECT $wpdb->posts.ID
								FROM $wpdb->posts
								LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
								WHERE $wpdb->posts.post_type = $post_pass_type
								AND $wpdb->posts.post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )
								AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
								", $row['sku']);
							$found_product_id = $wpdb->get_var($db_query);
							$attachments = get_posts('post_parent=' . $found_product_id . '&post_type=attachment&fields=ids&post_mime_type=image&numberposts=-1');
							foreach ($attachments as $attachment_key => $attachment) {		
								$attachment_url = wp_get_attachment_url($attachment);							
								$attachment_basename = basename($attachment_url);
								// Don't import existing images
								if ($image_name == $attachment_basename) {
									$image_upload_flag = 0;
									break;
								}
							}
							
							if ($image_upload_flag) {							
								@ftp_get($ftp_conn, ABSPATH . $image_upload_dir . $image_name, $wf_prod_advanced_settings['pro_images_path'] . '/' . $image_name, FTP_BINARY);
								$row['images'] = site_url() . $image_upload_dir . $image_name;
								$upload_image_count = $upload_image_count + 1;
							}
						}
						
						$parsed_data[$row['sku']] = $row;
						
						unset( $postmeta, $row );
					}
					
					// $tmp_product_count += 1;
					// if ($tmp_product_count > 3) { break; }
				}
				fclose( $handle );
			}
		}
		
		if ($ftp_conn) {
			ftp_close($ftp_conn);
		}

		return array( $parsed_data, $raw_headers );
	}

	/**
	 * Parse product
	 * @param  array  $item
	 * @param  integer $merge_empty_cells
	 * @return array
	 */
	public function parse_product( $item, $merge_empty_cells = 0, $use_sku_upsell_crosssell = 0 ) {
		global $WF_CSV_Product_Import, $wpdb;
		require_once(dirname(__DIR__).'/class-wf-piep-helper.php');
		$piep_helper_object = new wf_piep_helper();
		$this->row++;

		$terms_array = $postmeta = $product = array();
		$attributes = $default_attributes = $gpf_data = null;
		// Merging
		$merging = ( ! empty( $_GET['merge'] ) && $_GET['merge'] ) ? true : false;
		
		$skip_new = ( ! empty( $_GET['skip_new'] ) && $_GET['skip_new'] ) ? true : false;
		
		if (get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto')) {
			$merging = true;
		}
		if($skip_new){
			$product['skip_new'] = TRUE;
		}
		
        $delete_products = ( ! empty( $_GET['delete_products'] ) && $_GET['delete_products'] ) ? true : false;
                
        if($delete_products){
			$product['delete_products'] = TRUE;
		}
		$this->post_defaults['post_type'] = 'product';
		if(!empty($item['parent_sku'])){
			$prod_id = $piep_helper_object->xa_wc_get_product_id_by_sku( $item['parent_sku'] );
			$prod    = wc_get_product( $prod_id );
			if(WC()->version < '2.7.0')
			{
				$temp_product_type = ($prod) ? $prod->product_type : '';
			}
			else
			{
				$temp_product_type = ($prod) ? $prod->get_type() : '';
			}
			if($temp_product_type === 'grouped'){
				$this->post_defaults['post_type'] = 'product';   
			}else{
				$this->post_defaults['post_type'] = 'product_variation';
			}
		}
		if(isset($item['post_parent']) && $item['post_parent'] !== '' && $item['post_parent'] !== null){
			$prod    = wc_get_product( $item['post_parent'] );
			if(WC()->version < '2.7.0')
			{
				$temp_product_type1 = ($prod) ? $prod->product_type : '';
			}
			else
			{
				$temp_product_type1 = ($prod) ? $prod->get_type() : '';
			}
			if($temp_product_type1 === 'grouped'){
				$this->post_defaults['post_type'] = 'product';   
			}else{
				$this->post_defaults['post_type'] = 'product_variation';
			}
		}
		// Post ID field mapping
		$post_id = ( ! empty( $item['id'] ) ) ? $item['id'] : 0;
		$post_id = ( ! empty( $item['post_id'] ) ) ? $item['post_id'] : $post_id;
		if ( $merging ) {

			$product['merging'] = true;

			$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> Row %s - preparing for merge.', 'wf_csv_import_export'), $this->row ) );

			// Required fields
			if ( ! $post_id && empty( $item['sku'] ) ) {

				$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', __( '> > Cannot merge without id or sku. Importing instead.', 'wf_csv_import_export') );

				$merging = false;
			} else {

				// Check product exists
				if ( ! $post_id ) {
					$post_db_type = $this->post_defaults['post_type'];
					$post_pass_type = '"'.$post_db_type.'"';
					// Check product to merge exists
					$db_query = $wpdb->prepare("
						SELECT $wpdb->posts.ID
						FROM $wpdb->posts
						LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
						WHERE $wpdb->posts.post_type = $post_pass_type
						AND $wpdb->posts.post_status IN ( 'publish', 'private', 'draft', 'pending', 'future' )
						AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
						", $item['sku']);
					$found_product_id = $wpdb->get_var($db_query);
					if ( ! $found_product_id ) {
						$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf(__( '> > Skipped. Cannot find product with sku %s. Importing instead.', 'wf_csv_import_export'), $item['sku']) );
						$merging = false;
					} else {
						$post_id = $found_product_id;
						$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf(__( '> > Found product with ID %s.', 'wf_csv_import_export'), $post_id) );
					}
				}
				$product['merging'] = true;
			}
		}

		if ( ! $merging ) {

			$product['merging'] = false;
			$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> Row %s - preparing for import.', 'wf_csv_import_export'), $this->row ) );

			// Required fields
			if ( isset($item['post_parent']) && $item['post_parent']=== '' &&  $item['post_title']=== '') {
				$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', __( '> > Skipped. No post_title set for new product.', 'wf_csv_import_export') );
				return new WP_Error( 'parse-error', __( 'No post_title set for new product.', 'wf_csv_import_export' ) );
			}
			if ( isset($item['post_parent']) && $item['post_parent']!== '' && $item['post_parent']!== null &&  $item['parent_sku'] === '' ) {
				$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', __( '> > Skipped. No parent set for new variation product.', 'wf_csv_import_export') );
				//return new WP_Error( 'parse-error', __( 'No post_title set for new product.', 'wf_csv_import_export' ) );
				return new WP_Error( 'parse-error', __( 'No parent set for new variation product.', 'wf_csv_import_export' ) );
			}

		}

		$product['post_id'] = $post_id;


		// Get post fields
		foreach ( $this->post_defaults as $column => $default ) {
			if ( isset( $item[ $column ] ) ) $product[ $column ] = $item[ $column ];
		}
		
		// Get custom fields
		foreach ( $this->postmeta_defaults as $column => $default ) {
			if ( isset( $item[$column] ) )
			{
				//Handle stock status if it is given like (Instock, In stock , out of stock, Out of  Stock), make them as (instock, outofstock)
				if( 'stock_status' == $column )
				{
					$item[$column] = strtolower( preg_replace('/\s+/', '', $item[$column]) );
				}
                            
				if( 'children' == $column ) {
					$postmeta[$column] = explode ( '|', (string) $item[$column] );
				}
				else {
					$postmeta[$column] = (string) $item[$column];
				}
			}
			elseif ( isset( $item['_' . $column] ) )
			{
				$postmeta[$column] = (string) $item['_' . $column];
			}
                        
			// Check custom fields are valid
			if ( isset( $postmeta[$column] ) && isset( $this->postmeta_allowed[$column] ) && ! in_array( $postmeta[$column], $this->postmeta_allowed[$column] ) ) {
				$postmeta[$column] = $this->postmeta_defaults[$column];
			}
		}
		
		if ( ! $merging ) {
			// Merge post meta with defaults
			$product  = wp_parse_args( $product, $this->post_defaults );
			$postmeta = wp_parse_args( $postmeta, $this->postmeta_defaults );
		}

		// Handle special meta fields
		if ( isset($item['post_parent']) ) {

			// price
			if ( $merging ) {
				if ( ! isset( $postmeta['regular_price'] ) )
					$postmeta['regular_price'] = get_post_meta( $post_id, '_regular_price', true );
				$postmeta['regular_price'] = $this->hf_currency_formatter($postmeta['regular_price']);
				if ( ! isset( $postmeta['sale_price'] ) )
					$postmeta['sale_price'] = get_post_meta( $post_id, '_sale_price', true );
				$postmeta['sale_price'] = $this->hf_currency_formatter($postmeta['sale_price']);
			}

			if ( isset( $postmeta['regular_price'] ) && isset( $postmeta['sale_price'] ) && $postmeta['sale_price'] !== '' ) {
				$postmeta['sale_price'] = $this->hf_currency_formatter($postmeta['sale_price']);
				$postmeta['regular_price'] = $this->hf_currency_formatter($postmeta['regular_price']);
				$price = min( $postmeta['sale_price'], $postmeta['regular_price']);
				$postmeta['price'] = $price;
			} elseif ( isset( $postmeta['regular_price'] ) ) {
				$postmeta['price'] = $this->hf_currency_formatter($postmeta['regular_price']);
			}

		} else {
			// price
			if ( $merging ) {
				if ( ! isset( $postmeta['regular_price'] ) )
					$postmeta['regular_price'] = get_post_meta( $post_id, '_regular_price', true );
				$postmeta['regular_price'] =  $this->hf_currency_formatter($postmeta['regular_price']);
				if ( ! isset( $postmeta['sale_price'] ) )
					$postmeta['sale_price'] = get_post_meta( $post_id, '_sale_price', true );
				$postmeta['sale_price'] = $this->hf_currency_formatter($postmeta['sale_price']);
			}

			if ( isset( $postmeta['regular_price'] ) && isset( $postmeta['sale_price'] ) && $postmeta['sale_price'] !== '' ) {
				$postmeta['sale_price'] = $this->hf_currency_formatter($postmeta['sale_price']);
				$postmeta['regular_price'] = $this->hf_currency_formatter($postmeta['regular_price']);
				$price = min( $postmeta['sale_price'], $postmeta['regular_price']);
				$postmeta['price'] = $price;
			} elseif ( isset( $postmeta['regular_price'] ) ) {
				$postmeta['price'] = $this->hf_currency_formatter($postmeta['regular_price']);
			}

			// Reset dynamically generated meta
			$postmeta['min_variation_price'] = $postmeta['max_variation_price']	= $postmeta['min_variation_regular_price'] =$postmeta['max_variation_regular_price'] = $postmeta['min_variation_sale_price'] = $postmeta['max_variation_sale_price'] = '';
		}

		// upsells
		if ( isset( $postmeta['upsell_ids'] ) && ! is_array( $postmeta['upsell_ids'] ) ) {
			$ids = array_filter( array_map( 'trim', explode( '|', $postmeta['upsell_ids'] ) ) );
			$postmeta['upsell_ids'] = $ids;
		}

		// crosssells
		if ( isset( $postmeta['crosssell_ids'] ) && ! is_array( $postmeta['crosssell_ids'] ) ) {
			$ids = array_filter( array_map( 'trim', explode( '|', $postmeta['crosssell_ids'] ) ) );
			$postmeta['crosssell_ids'] = $ids;
		}

		//Get upsells and crosssells product id from upsells and crosssells product sku
		if( $use_sku_upsell_crosssell )
		{
			//Get upsells product id from upsells product sku
			foreach($postmeta['upsell_ids'] as $key => $upsell_id) {
				$postmeta['upsell_ids'][$key] = $piep_helper_object->xa_wc_get_product_id_by_sku($upsell_id);
			}

			//Get crosssells product id from crosssells product sku
			foreach( $postmeta['crosssell_ids'] as $key => $crosssell_id ) {
				$postmeta['crosssell_ids'][$key] = $piep_helper_object->xa_wc_get_product_id_by_sku( $crosssell_id );
			}
		}

		// Sale dates
		if ( isset( $postmeta['sale_price_dates_from'] ) ) {
			$postmeta['sale_price_dates_from'] = empty( $postmeta['sale_price_dates_from'] ) ? '' : strtotime( $postmeta['sale_price_dates_from'] );
		}

		if ( isset( $postmeta['sale_price_dates_to'] ) ) {
			$postmeta['sale_price_dates_to'] = empty( $postmeta['sale_price_dates_to'] ) ? '' : strtotime( $postmeta['sale_price_dates_to'] );
		}

		// Relative stock updates
		if ( $merging ) {
			if ( isset( $postmeta['stock'] ) ) {
				$postmeta['stock'] = trim( $postmeta['stock'] );
				$mode = substr( $postmeta['stock'], 0, 3 );
				if ( $mode == '(+)' ) {
					$old_stock 	= absint( get_post_meta( $post_id, '_stock', true ) );
					$amount 	= absint( substr( $postmeta['stock'], 3 ) );
					$new_stock 	= $old_stock + $amount;
					$postmeta['stock'] = $new_stock;
				}

				if ( $mode == '(-)' ) {
					$old_stock 	= absint( get_post_meta( $post_id, '_stock', true ) );
					$amount 	= absint( substr( $postmeta['stock'], 3 ) );
					$new_stock 	= $old_stock - $amount;
					$postmeta['stock'] = $new_stock;
				}
			}
		}

		// Format post status
		if ( ! empty( $product['post_status'] ) ) {
			$product['post_status'] = strtolower( $product['post_status'] );
			if ( empty($item['post_parent']) ) {
				if ( ! in_array( $product['post_status'], array( 'publish', 'private', 'draft', 'pending', 'future', 'inherit', 'trash' ) ) ) {
					$product['post_status'] = 'publish';
				}
			} else {
				if ( ! in_array( $product['post_status'], array( 'private', 'publish' ) ) ) {
					$product['post_status'] = 'publish';
				}
			}
		}
		
		//Automatically Update the Product Inventory stock status based on out of stock threshold value
		//For both Merging or Inserting
		if( XA_INVENTORY_STOCK_STATUS == 'yes' )
		{
			if( $merging && empty( $postmeta['stock_status'] ))
			{
				$temp_product = wc_get_product($post_id);
				if( (! empty($temp_product)) && $temp_product->managing_stock() )
				{
					if( (! empty($postmeta['stock'])) && XA_INVENTORY_STOCK_THRESHOLD < $postmeta['stock'] )
					{
						$postmeta['stock_status'] = 'instock';
					}
					elseif( (! empty($postmeta['stock'])) && XA_INVENTORY_STOCK_THRESHOLD >= $postmeta['stock'] )
					{
						$postmeta['stock_status'] = 'outofstock';
					}
				}
				elseif( (! empty($temp_product)) && (! empty($postmeta['manage_stock'])) && $postmeta['manage_stock'] == 'yes' )
				{
					$temp_product_quantity = (! empty($postmeta['stock'])) ? $postmeta['stock'] : $temp_product->get_stock_quantity();
					if( XA_INVENTORY_STOCK_THRESHOLD < $temp_product_quantity )
					{
						$postmeta['stock_status'] = 'instock';
					}
					elseif( XA_INVENTORY_STOCK_THRESHOLD >= $temp_product_quantity )
					{
						$postmeta['stock_status'] = 'outofstock';
					}
				}
			}
			elseif( ! $merging && empty($postmeta['stock_status']) )
			{
				if( empty($postmeta['manage_stock']) || ( (! empty($postmeta['manage_stock']) ) && $postmeta['manage_stock'] == 'no') )
				{
					$postmeta['stock_status'] = 'instock';
				}
				elseif( ( ! empty($postmeta['manage_stock']) ) &&  $postmeta['manage_stock'] == 'yes' )
				{
					if( ( !empty($postmeta['stock']) ) && XA_INVENTORY_STOCK_THRESHOLD < $postmeta['stock'] )
					{
						$postmeta['stock_status'] = 'instock';
					}
					else
					{
						$postmeta['stock_status'] = 'outofstock';
					}
				}
			}
		}
		elseif( ( ! $merging ) && empty( $postmeta['stock_status'] ) )
		{
			$postmeta['stock_status'] = 'instock';
		}

		// Put set core product postmeta into product array
		foreach ( $postmeta as $key => $value ) {
			$product['postmeta'][] = array( 'key' 	=> '_' . esc_attr($key), 'value' => $value );
		}

		/**
		 * Handle other columns
		 */
		foreach ( $item as $key => $value ) {
			if ( empty($item['post_parent']) && ! $merge_empty_cells && $value == "" )
				continue;

			/**
			 * File path handling
			 */
			if ( $key == 'file_paths' || $key == 'downloadable_files' ) {
				$file_paths  = explode( '|', $value );
				$_file_paths = array();
				foreach ( $file_paths as $file_path ) {
					// 2.1
					if ( function_exists( 'wc_get_filename_from_url' ) ) {
						$file_path = array_map( 'trim', explode( '::', $file_path ) );
						if ( sizeof( $file_path ) === 2 ) {
							$file_name = $file_path[0];
							$file_path = $file_path[1];
						} else {
							$file_name = $piep_helper_object->xa_wc_get_filename_from_url( $file_path[0] );
							$file_path = $file_path[0];
						}
						$_file_paths[ md5( $file_path ) ] = array(
							'name' => $file_name,
							'file' => $file_path
							);
					} else {
						$file_path = trim( $file_path );
						$_file_paths[ md5( $file_path ) ] = $file_path;
					}
				}
				$value = $_file_paths;

				$product['postmeta'][] = array( 'key' 	=> '_' . esc_attr( $key ), 'value' => $value );
			}

			/**
			 * Handle meta: columns for variation attributes
			 */
			elseif ( strstr( $key, 'meta:attribute_pa_' ) ) {
				// Get meta key name
				$meta_key = ( isset( $WF_CSV_Product_Import->raw_headers[$key] ) ) ? $WF_CSV_Product_Import->raw_headers[$key] : $key;
				$meta_key = trim( str_replace( 'meta:', '', $meta_key ) );
				// Convert to slug
				$value = sanitize_title( $value );
				// Add to postmeta array
				$product['postmeta'][] = array(
					'key' 	=> esc_attr( $meta_key ),
					'value' => $value
					);
			}

			/**
			 * Handle meta: columns - import as custom fields
			 */
			elseif ( strstr( $key, 'meta:' ) ) {
				// Get meta key name
				$meta_key = ( isset( $WF_CSV_Product_Import->raw_headers[$key] ) ) ? $WF_CSV_Product_Import->raw_headers[$key] : $key;
                                //$meta_key = trim( str_replace( 'meta:', '', $meta_key ) ); 
				$meta_key = sanitize_title(trim( str_replace( 'meta:', '', $meta_key ) )); // add sanitize_title for encode chinese charectors in attributes
				if($meta_key !== 'wcpb_bundle_products'){
				// Decode JSON
					$json = json_decode( $value, true );
					if ( is_array( $json ) || is_object( $json ) )
						$value = (array) $json;					
				}
				// Add to postmeta array
				$product['postmeta'][] = array(
					'key' 	=> esc_attr( $meta_key ),
					'value' => $value
					);
			}

			/**
			 * Handle meta: columns - import as custom fields
			 */
			elseif ( strstr( $key, 'tax:' ) ) {

				// Get taxonomy
				$taxonomy = trim( str_replace( 'tax:', '', $key ) );

				// Exists?
				if ( ! taxonomy_exists( $taxonomy ) ) {
					$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> > Skipping taxonomy "%s" - it does not exist.', 'wf_csv_import_export'), $taxonomy ) );
					continue;
				}
                                
                // Product type check  
                // commented for updating featured taxonomy
//				if ( $taxonomy == 'product_visibility'  ) {					
//					continue;
//				}
				// Product type check
				if ( $taxonomy == 'product_type' ) {
					$term = strtolower( $value );

					if ( ! array_key_exists( $term, $this->allowed_product_types ) ) {
						$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> > > Product type "%s" not allowed - using simple.', 'wf_csv_import_export'), $term ) );
						$term_id = $this->allowed_product_types['simple'];
					} else {
						$term_id = $this->allowed_product_types[ $term ];
					}

					// Add to array
					$terms_array[] = array(
						'taxonomy' 	=> $taxonomy,
						'terms'		=> array( $term_id )
						);

					continue;
				}

				// Get terms - ID => parent
				$terms 			= array();
				$raw_terms 		= explode( '|', $value );
				$raw_terms 		= array_map( 'trim', $raw_terms );

				// Handle term hierachy (>)
				foreach ( $raw_terms as $raw_term ) {

					if ( strstr( $raw_term, '>' ) ) {

						$raw_term = explode( '>', $raw_term );
						global $wp_version;
						$raw_term = array_map( 'trim', $raw_term );
						if($wp_version < '2.8.0'){
							$raw_term = array_map( 'wp_specialchars', $raw_term );
						}
						else
						{
							$raw_term = array_map( 'esc_html', $raw_term );

						}
						$raw_term = array_filter( $raw_term );

						$parent = 0;
						$loop   = 0;

						foreach ( $raw_term as $term ) {
							$loop ++;
							$term_id = '';

							if ( isset( $this->inserted_terms[ $taxonomy ][ $parent ][ $term ] ) ) {
								$term_id = $this->inserted_terms[ $taxonomy ][ $parent ][ $term ];
							} elseif ( $term ) {

								/**
								 * Check term existance
								 */
								$term_may_exist = term_exists( $term, $taxonomy, absint( $parent ) );

								$WF_CSV_Product_Import->hf_log_data_change( 'CSV-Import', sprintf( __( '> > (' . __LINE__ . ') Term %s (%s) exists? %s', 'wf_csv_import_export' ), sanitize_text_field( $term ), esc_html( $taxonomy ), $term_may_exist ? print_r( $term_may_exist, true ) : '-' ) );

								if ( is_array( $term_may_exist ) ) {
									$possible_term = get_term( $term_may_exist['term_id'], 'product_cat' );

									if ( $possible_term->parent == $parent ) {
										$term_id = $term_may_exist['term_id'];
									}
								}

								if ( ! $term_id ) {
									//Create appropriate slug for the category
									$slug = sanitize_title( $raw_term[$loop - 1] );

									$t = wp_insert_term( $term, $taxonomy, array( 'parent' => $parent, 'slug' => $slug ) );

									if ( ! is_wp_error( $t ) ) {
										$term_id = $t['term_id'];
									} else {
										$WF_CSV_Product_Import->hf_log_data_change( 'CSV-Import', sprintf( __( '> > (' . __LINE__ . ') Failed to import term %s, parent %s - %s', 'wf_csv_import_export' ), sanitize_text_field( $term ), sanitize_text_field( $parent ), sanitize_text_field( $taxonomy ) ) );
										break;
									}
								}

								$this->inserted_terms[$taxonomy][$parent][$term] = $term_id;

							}

							if ( ! $term_id )
								break;

							// Add to product terms, ready to set if this is the final term
							if ( sizeof( $raw_term ) == $loop )
								$terms[] = $term_id;

							$parent = $term_id;
						}

					} else {

						$term_id  = '';
						global $wp_version;
						$raw_term = ( $wp_version < '2.8.0') ? wp_specialchars( $raw_term ) : esc_html( $raw_term );

						if ( isset( $this->inserted_terms[$taxonomy][0][$raw_term] ) ) {

							$term_id = $this->inserted_terms[$taxonomy][0][$raw_term];

						} elseif ( $raw_term ) {

							// Check term existance
							$term_exists 	= term_exists( $raw_term, $taxonomy, 0 );
							$term_id 		= is_array( $term_exists ) ? $term_exists['term_id'] : 0;

							if ( ! $term_id ) {
								$t = wp_insert_term( trim( $raw_term ), $taxonomy, array( 'parent' => 0 ) );

								if ( ! is_wp_error( $t ) ) {
									$term_id = $t['term_id'];
								} else {
									$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __( '> > Failed to import term %s %s', 'wf_csv_import_export' ), esc_html($raw_term), esc_html($taxonomy) ) );
									break;
								}
							}

							$this->inserted_terms[$taxonomy][0][$raw_term] = $term_id;

						}

						// Store terms for later insertion
						if ( $term_id )
							$terms[] = $term_id;

					}

				}

				// Any defined?
				if ( sizeof( $terms ) == 0 )
					continue;

				// Add to array
				$terms_array[] = array(
					'taxonomy' 	=> $taxonomy,
					'terms'		=> $terms
					);
			}

			/**
			 * Handle Attributes
			 */
			elseif ( strstr( $key, 'attribute:' ) ) {

				$attribute_key 	= sanitize_title( trim( str_replace( 'attribute:', '', $key ) ) ); 
				$attribute_name = str_replace( 'attribute:', '', $WF_CSV_Product_Import->raw_headers[ $key ] );

				if ( ! $attribute_key )
					continue;

				// Taxonomy
				if ( substr( $attribute_key, 0, 3 ) == 'pa_' ) {
					$taxonomy = $attribute_key;
					// Exists?
					if ( ! taxonomy_exists( $taxonomy ) ) {
						$nicename = sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
						$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> > Attribute taxonomy "%s" does not exist. Adding it. Nicename: %s', 'wf_csv_import_export'), $taxonomy, $nicename ) );
						$exists_in_db = $wpdb->get_var( "SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '" . $nicename . "';" );
						if ( ! $exists_in_db ) {
							// Create the taxonomy
							$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename, 'attribute_label' => $nicename, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order' ) );
						} else {
							$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __('> > Attribute taxonomy %s already exists in DB.', 'wf_csv_import_export'), $taxonomy ) );
						}

						// Register the taxonomy now so that the import works!
						register_taxonomy( $taxonomy,
							array( 'product', 'product_variation' ),
							array(
								'hierarchical' => true,
								'show_ui'      => false,
								'query_var'    => true,
								'rewrite'      => false,
								)
							);
					}

					// Get terms
					$terms     = array();
					$raw_terms = explode( '|', $value );
					global $wp_version;
					if($wp_version < '2.8.0'){
						$raw_terms = array_map( 'wp_specialchars', $raw_terms );
					}
					else
					{
						$raw_terms = array_map( 'esc_html', $raw_terms );
						
					}
					$raw_terms = array_map( 'trim', $raw_terms );
					if ( sizeof( $raw_terms ) > 0 ) {
						foreach ( $raw_terms as $raw_term ) {
							if ( empty( $raw_term ) && 0 != $raw_term ) {
								continue;
							}
							// Check term existance
							$term_exists 	= term_exists( $raw_term, $taxonomy, 0 );
							$term_id 		= is_array( $term_exists ) ? $term_exists['term_id'] : 0;
							if ( ! $term_id ) {
								$t = wp_insert_term( trim( $raw_term ), $taxonomy );
								if ( ! is_wp_error( $t ) ) {
									$term_id = $t['term_id'];
									$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __( '> > Inserted Raw Term %s ID = %s', 'wf_csv_import_export' ), esc_html( $raw_term ), $term_id ) );
								} else {
									$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __( '> > Failed to import term %s %s', 'wf_csv_import_export' ), esc_html($raw_term), esc_html($taxonomy) ) );
									break;
								}
							} else {
								$WF_CSV_Product_Import->hf_log_data_change( 'csv-import', sprintf( __( '> > Raw Term %s ID = %s', 'wf_csv_import_export' ), esc_html( $raw_term ), $term_id ) );
							}

							if ( $term_id ) {
								$terms[] = $term_id;
							}
						}
					}

					// Add to array
					$terms_array[] = array(
						'taxonomy' 	=> $taxonomy,
						'terms'		=> $terms
						);
					// Ensure we have original attributes
					if ( is_null( $attributes ) && $merging ) {
						$attributes = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) ) );
					} elseif ( is_null( $attributes ) ) {
						$attributes = array();
					}
					// Set attribute
					if ( ! isset( $attributes[$taxonomy] ) )
						$attributes[$taxonomy] = array();
					$attributes[$taxonomy]['name']        = $taxonomy;
					$attributes[$taxonomy]['value']       = null;
					$attributes[$taxonomy]['is_taxonomy'] = 1;

					if ( ! isset( $attributes[$taxonomy]['position'] ) )
						$attributes[$taxonomy]['position'] = 0;
					if ( ! isset( $attributes[$taxonomy]['is_visible'] ) )
						$attributes[$taxonomy]['is_visible'] = 1;
					if ( ! isset( $attributes[$taxonomy]['is_variation'] ) )
						$attributes[$taxonomy]['is_variation'] = 0;

				} else {
					if ( ! $value || ! $attribute_key ) continue;

					// Set attribute
					if ( ! isset( $attributes[$attribute_key] ) )
						$attributes[$attribute_key] = array();

					$attributes[$attribute_key]['name']        = $attribute_name;
					$attributes[$attribute_key]['value']       = $value;
					$attributes[$attribute_key]['is_taxonomy'] = 0;

					if ( ! isset( $attributes[$attribute_key]['position'] ) )
						$attributes[$attribute_key]['position'] = 0;
					if ( ! isset( $attributes[$attribute_key]['is_visible'] ) )
						$attributes[$attribute_key]['is_visible'] = 1;
					if ( ! isset( $attributes[$attribute_key]['is_variation'] ) )
						$attributes[$attribute_key]['is_variation'] = 0;
				}
			}

			/**
			 * Handle Attributes Data - position|is_visible|is_variation
			 */
			elseif ( strstr( $key, 'attribute_data:' ) ) {
                            
				$attribute_key = sanitize_title( trim( str_replace( 'attribute_data:', '', $key ) ) );

				if ( ! $attribute_key ) {
					continue;
				}

				$values 	= explode( '|', $value );
				$position 	= isset( $values[0] ) ? (int) $values[0] : 0;
				$visible 	= isset( $values[1] ) ? (int) $values[1] : 1;
				$variation 	= isset( $values[2] ) ? (int) $values[2] : 0;

				// Ensure we have original attributes
				if ( ! isset( $attributes[ $attribute_key ] ) ) {
					if ( $merging ) {
						$existing_attributes = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) ) );
						$attributes[ $attribute_key ] = isset( $existing_attributes[ $attribute_key ] ) ? $existing_attributes[ $attribute_key ] : array();
					} else {
						$attributes[ $attribute_key ] = array();
					}
				}

				$attributes[ $attribute_key ]['position']     = $position;
				$attributes[ $attribute_key ]['is_visible']   = $visible;
				$attributes[ $attribute_key ]['is_variation'] = $variation;
			}

			/**
			 * Handle Attributes Default Values
			 */
			elseif ( strstr( $key, 'attribute_default:' ) ) {

				$attribute_key = sanitize_title( trim( str_replace( 'attribute_default:', '', $key ) ) );

				if ( ! $attribute_key ) continue;

				// Ensure we have original attributes
				if ( is_null( $default_attributes ) && $merging ) {
					$default_attributes = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_default_attributes', true ) ) );
				} elseif ( is_null( $default_attributes ) ) {
					$default_attributes = array();
				}

				$default_attributes[ $attribute_key ] = $value;
			}

			/**
			 * Handle gpf: google product feed columns
			 */
			elseif ( strstr( $key, 'gpf:' ) ) {

				$gpf_key = trim( str_replace( 'gpf:', '', $key ) );

				// Get original values
				if ( is_null( $gpf_data ) && $merging ) {
					$gpf_data = array_filter( (array) maybe_unserialize( get_post_meta( $post_id, '_woocommerce_gpf_data', true ) ) );
				} elseif ( is_null( $gpf_data ) ) {
					$gpf_data = array(
						'availability'            => '',
						'condition'               => '',
						'brand'                   => '',
						'product_type'            => '',
						'google_product_category' => '',
						'gtin'                    => '',
						'mpn'                     => '',
						'gender'                  => '',
						'age_group'               => '',
						'color'                   => '',
						'size'                    => ''
						);
				}
				$gpf_data[$gpf_key] = $value;
			}

			/**
			 * Handle parent_sku column for variations
			 */
			elseif ( strstr( $key, 'parent_sku' ) ) {
				if ( $value ) {
					$dbQuery = $wpdb->prepare("
						SELECT $wpdb->posts.ID
						FROM $wpdb->posts
						LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
						WHERE $wpdb->posts.post_type = 'product'
						AND $wpdb->posts.post_status IN ( 'publish', 'private', 'draft' )
						AND $wpdb->postmeta.meta_key = '_sku' AND $wpdb->postmeta.meta_value = '%s'
						", $value );
					$found_product_id = $wpdb->get_var($dbQuery);
					if ( $found_product_id )
						$product['post_parent'] = $found_product_id;
				}
			}

			/**
			 * Handle upsell SKUs which we cannot assign until we get IDs later on
			 */
			elseif ( strstr( $key, 'upsell_skus' ) ) {
				if ( $value ) {
					$skus                   = array_filter( array_map( 'trim', explode( '|', $value ) ) );
					$product['upsell_skus'] = $skus;
				}
			}

			/**
			 * Handle crosssells SKUs which we cannot assign until we get IDs later on
			 */
			elseif ( strstr( $key, 'crosssell_skus' ) ) {
				if ( $value ) {
					$skus                      = array_filter( array_map( 'trim', explode( '|', $value ) ) );
					$product['crosssell_skus'] = $skus;
				}
			}
		}

		// Remove empty attribues
		if(!empty($attributes))
			foreach ( $attributes as $key => $value ) {
				if ( ! isset($value['name']) ) unset( $attributes[$key] );
			}

		/**
		 * Handle images
		 */
		if ( ! empty( $item['images'] ) ) {
			$images = array_map( 'trim', explode( '|', $item['images'] ) );
		} else {
			$images = '';
		}
		
		$product['postmeta'][] = array( 'key' 	=> '_default_attributes', 'value' => $default_attributes );
		$product['attributes'] = $attributes;
		$product['gpf_data']   = $gpf_data;
		$product['images']     = $images;
		$product['terms']      = $terms_array;
		$product['sku']        = ( ! empty( $item['sku'] ) ) ? $item['sku'] : '';
		$product['post_title'] = ( ! empty( $item['post_title'] ) ) ? $item['post_title'] : '';
		                
		$product = apply_filters('woocommerce_csv_parsed_product',$product, $item );
		unset( $item, $terms_array, $postmeta, $attributes, $gpf_data, $images );
		return $product;
	}
	
	function hf_currency_formatter($price){
            if(WC()->version < '2.3')
            {
                $separator = get_option( 'woocommerce_price_decimal_sep' ) ;
                $decimal_seperator = $separator ? stripslashes( $separator ) : '.';
            }else
            {
                $decimal_seperator = wc_get_price_decimal_separator();
            }
            return preg_replace("[^0-9\\'.$decimal_seperator.']", "", $price);
	}
}