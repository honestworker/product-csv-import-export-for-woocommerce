<?php

if (!defined('WPINC')) {
    exit;
}

class WF_Advanced_ProdImpExpCsv_Settings {

    /**
     * Product Exporter Tool
     */
    public static function save_settings() {
        $wf_prod_advanced_settings = array();
        
        $wf_prod_advanced_origin_settings = get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', null);

		$wf_prod_advanced_settings['pro_ftp_server']			= ! empty($_POST['ftp_host']) ? $_POST['ftp_host'] : '';
		$wf_prod_advanced_settings['pro_ftp_port']				= ! empty($_POST['ftp_port']) ? $_POST['ftp_port'] : 21;
		$wf_prod_advanced_settings['pro_ftp_user']				= ! empty($_POST['ftp_userid']) ? $_POST['ftp_userid'] : '';
		$wf_prod_advanced_settings['pro_ftp_password']			= ! empty($_POST['ftp_password']) ? $_POST['ftp_password'] : '';
		$wf_prod_advanced_settings['pro_use_ftps']				= ! empty($_POST['use_ftps']) ? $_POST['use_ftps'] : 0;
        $wf_prod_advanced_settings['pro_delimiter']				= !empty($_POST['delimiter']) ? $_POST['delimiter'] : ',';
        $wf_prod_advanced_settings['pro_images_path']			= !empty($_POST['images_path']) ? $_POST['images_path'] : ',';
        $wf_prod_advanced_settings['pro_server_path']			= isset($_POST['server_path']) ? $_POST['server_path'] : null;
		$wf_prod_advanced_settings['pro_main_category']			= ! empty($_POST['main_category']) ? $_POST['main_category'] : '';
        $wf_prod_advanced_settings['pro_sub_category']			= ! empty($_POST['sub_category']) ? $_POST['sub_category'] : '';
        
		$wf_prod_advanced_settings['pro_mapping_category']		= isset($wf_prod_advanced_origin_settings['pro_mapping_category']) ? $wf_prod_advanced_origin_settings['pro_mapping_category'] : array();

        update_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', $wf_prod_advanced_settings);

        return $wf_prod_advanced_settings;
    }

    /**
     * Product 
     */
    public static function set_maaping_settings() {
        $wf_prod_advanced_origin_settings = get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', null);
        $mapping_category = ! empty($_POST['mapping_category']) ? $_POST['mapping_category'] : array();
        $main_category = ! empty($_POST['main_category']) ? $_POST['main_category'] : array();
        $sub_category = ! empty($_POST['sub_category']) ? $_POST['sub_category'] : array();
        
        $save_mapping_category = array();
        foreach ($mapping_category as $key => $category) {
            if ($sub_category[$key]) {
                $save_mapping_category[$main_category[$key]][$sub_category[$key]] = $category;
            } else {
                $save_mapping_category[$main_category[$key]]['_default_'] = $category;
            }
        }
        $wf_prod_advanced_origin_settings['pro_mapping_category'] = $save_mapping_category;
        update_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', $wf_prod_advanced_origin_settings);
    }

    /**
     * Product 
     */
    public static function get_settings() {
        return get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', null);
    }
}