<?php

if (!defined('WPINC')) {
    exit;
}

class WF_ProdImpExpCsv_Settings {

    /**
     * Product Exporter Tool
     */
    public static function save_settings() {
        global $wpdb;
        $pro_ftp_server = !empty($_POST['pro_ftp_server']) ? $_POST['pro_ftp_server'] : '';
        $pro_ftp_user = !empty($_POST['pro_ftp_user']) ? $_POST['pro_ftp_user'] : '';
        $pro_ftp_password = !empty($_POST['pro_ftp_password']) ? $_POST['pro_ftp_password'] : '';
        $pro_ftp_port = !empty($_POST['pro_ftp_port']) ? $_POST['pro_ftp_port'] : 21;
        $pro_use_ftps = !empty($_POST['pro_use_ftps']) ? true : false;
        $pro_enable_ftp_ie = !empty($_POST['pro_enable_ftp_ie']) ? true : false;
        
        $rev_ftp_server = !empty($_POST['rev_ftp_server']) ? $_POST['rev_ftp_server'] : '';
        $rev_ftp_user = !empty($_POST['rev_ftp_user']) ? $_POST['rev_ftp_user'] : '';
        $rev_ftp_password = !empty($_POST['rev_ftp_password']) ? $_POST['rev_ftp_password'] : '';
        $rev_ftp_port = !empty($_POST['rev_ftp_port']) ? $_POST['rev_ftp_port'] : 21;
        $rev_use_ftps = !empty($_POST['rev_use_ftps']) ? true : false;
        $rev_enable_ftp_ie = !empty($_POST['rev_enable_ftp_ie']) ? true : false;
        
        $pro_auto_export = !empty($_POST['pro_auto_export']) ? $_POST['pro_auto_export'] : 'Disabled';
        $pro_auto_export_ftp_path = !empty($_POST['pro_auto_export_ftp_path']) ? $_POST['pro_auto_export_ftp_path'] : '/';
        $pro_auto_export_do_shortcode = !empty($_POST['pro_auto_export_do_shortcode']) ? $_POST['pro_auto_export_do_shortcode'] : null;
        $pro_auto_export_ftp_file_name = !empty($_POST['pro_auto_export_ftp_file_name']) ? $_POST['pro_auto_export_ftp_file_name'] : null;
        $pro_auto_export_start_time = !empty($_POST['pro_auto_export_start_time']) ? $_POST['pro_auto_export_start_time'] : '';
        $pro_auto_export_interval = !empty($_POST['pro_auto_export_interval']) ? $_POST['pro_auto_export_interval'] : '';
        $pro_auto_export_profile = !empty($_POST['pro_auto_export_profile']) ? $_POST['pro_auto_export_profile'] : '';
        $pro_auto_export_categories = !empty($_POST['pro_auto_export_categories']) ? $_POST['pro_auto_export_categories'] : null;
        $pro_auto_export_include_hidden_meta = !empty($_POST['pro_auto_export_include_hidden_meta']) ? $_POST['pro_auto_export_include_hidden_meta'] : null;
        
        $pro_auto_import = !empty($_POST['pro_auto_import']) ? $_POST['pro_auto_import'] : 'Disabled';        
        $pro_auto_advanced_import = !empty($_POST['pro_auto_advanced_import']) ? true : false;
        $pro_auto_import_file = !empty($_POST['pro_auto_import_file']) ? $_POST['pro_auto_import_file'] : null;
        $pro_auto_import_delimiter = !empty($_POST['pro_auto_import_delimiter']) ? $_POST['pro_auto_import_delimiter'] : ',';
        $pro_auto_import_start_time = !empty($_POST['pro_auto_import_start_time']) ? $_POST['pro_auto_import_start_time'] : '';
        $pro_auto_import_interval = !empty($_POST['pro_auto_import_interval']) ? $_POST['pro_auto_import_interval'] : '';
        $pro_auto_import_profile = !empty($_POST['pro_auto_import_profile']) ? $_POST['pro_auto_import_profile'] : '';
        $pro_auto_import_merge = !empty($_POST['pro_auto_import_merge']) ? true : false;
        $pro_auto_import_skip = !empty($_POST['pro_auto_import_skip']) ? true : false;
        $pro_auto_delete_products = !empty($_POST['pro_auto_delete_products']) ? true : false;
        $pro_auto_new_prod_status = !empty($_POST['pro_auto_new_prod_status']) ? $_POST['pro_auto_new_prod_status'] : '';
        $prod_auto_use_chidren_sku = !empty($_POST['prod_auto_use_chidren_sku']) ? true : false;
        $pro_auto_use_sku_upsell_crosssell = isset($_POST['pro_auto_use_sku_upsell_crosssell']) ? true : false;
        
        
        $rev_auto_export = !empty($_POST['rev_auto_export']) ? $_POST['rev_auto_export'] : 'Disabled';
        $rev_auto_export_start_time = !empty($_POST['rev_auto_export_start_time']) ? $_POST['rev_auto_export_start_time'] : '';
        $rev_auto_export_interval = !empty($_POST['rev_auto_export_interval']) ? $_POST['rev_auto_export_interval'] : '';
        $rev_auto_export_ftp_path = !empty($_POST['rev_auto_export_ftp_path']) ? $_POST['rev_auto_export_ftp_path'] : '/';
        $rev_auto_export_ftp_file_name = !empty($_POST['rev_auto_export_ftp_file_name']) ? $_POST['rev_auto_export_ftp_file_name'] : null;
        
        
        $rev_auto_import = !empty($_POST['rev_auto_import']) ? $_POST['rev_auto_import'] : 'Disabled';
        $rev_auto_import_start_time = !empty($_POST['rev_auto_import_start_time']) ? $_POST['rev_auto_import_start_time'] : '';
        $rev_auto_import_interval = !empty($_POST['rev_auto_import_interval']) ? $_POST['rev_auto_import_interval'] : '';
        $rev_auto_import_profile = !empty($_POST['rev_auto_import_profile']) ? $_POST['rev_auto_import_profile'] : '';
        $rev_auto_import_merge = !empty($_POST['rev_auto_import_merge']) ? true : false;
        
        
        // Product ipmort from URL 
        $pro_enable_url_ie = isset($_POST['pro_enable_url_ie']) ? true : false;
        $pro_auto_import_url = isset($_POST['pro_auto_import_url']) ? $_POST['pro_auto_import_url'] : null;
        $pro_auto_import_url_delimiter = !empty($_POST['pro_auto_import_url_delimiter']) ? $_POST['pro_auto_import_url_delimiter'] : ',';
        $pro_auto_import_url_start_time = isset($_POST['pro_auto_import_url_start_time']) ? $_POST['pro_auto_import_url_start_time'] : '';
        $pro_auto_import_url_interval = isset($_POST['pro_auto_import_url_interval']) ? $_POST['pro_auto_import_url_interval'] : '';
        $pro_auto_import_url_profile = isset($_POST['pro_auto_import_url_profile']) ? $_POST['pro_auto_import_url_profile'] : '';
        $pro_auto_import_url_merge = isset($_POST['pro_auto_import_url_merge']) ? true : false;
        $pro_auto_import_url_skip = isset($_POST['pro_auto_import_url_skip']) ? true : false;
        $pro_auto_import_url_delete_products = !empty($_POST['pro_auto_import_url_delete_products']) ? true : false;
        
        
        $settings = array();
        $settings['pro_ftp_server'] = $pro_ftp_server;
        $settings['pro_ftp_user'] = $pro_ftp_user;
        $settings['pro_ftp_password'] = $pro_ftp_password;
        $settings['pro_ftp_port'] = $pro_ftp_port;
        $settings['pro_use_ftps'] = $pro_use_ftps;
        $settings['pro_enable_ftp_ie'] = $pro_enable_ftp_ie;
        
        
        $settings['pro_auto_export'] = $pro_auto_export;
        $settings['pro_auto_export_ftp_path'] = $pro_auto_export_ftp_path;
        $settings['pro_auto_export_ftp_file_name'] = $pro_auto_export_ftp_file_name;
        $settings['pro_auto_export_do_shortcode'] = $pro_auto_export_do_shortcode;
        $settings['pro_auto_export_start_time'] = $pro_auto_export_start_time;
        $settings['pro_auto_export_interval'] = $pro_auto_export_interval;
        $settings['pro_auto_export_profile'] = $pro_auto_export_profile;
        $settings['pro_auto_export_categories'] = $pro_auto_export_categories;
        $settings['pro_auto_export_include_hidden_meta'] = $pro_auto_export_include_hidden_meta;
        
        $settings['pro_auto_import'] = $pro_auto_import;
        $settings['pro_auto_import_file'] = $pro_auto_import_file;
        $settings['pro_auto_import_delimiter'] = $pro_auto_import_delimiter;
        $settings['pro_auto_import_start_time'] = $pro_auto_import_start_time;
        $settings['pro_auto_import_interval'] = $pro_auto_import_interval;
        $settings['pro_auto_import_profile'] = $pro_auto_import_profile;
        $settings['pro_auto_import_merge'] = $pro_auto_import_merge;
        $settings['pro_auto_import_skip'] = $pro_auto_import_skip;
        $settings['pro_auto_delete_products'] = $pro_auto_delete_products;
        $settings['pro_auto_new_prod_status'] = $pro_auto_new_prod_status;
        $settings['prod_auto_use_chidren_sku'] = $prod_auto_use_chidren_sku;
        $settings['pro_auto_use_sku_upsell_crosssell'] = $pro_auto_use_sku_upsell_crosssell;
        
        
        $settings['rev_ftp_server'] = $rev_ftp_server;
        $settings['rev_ftp_user'] = $rev_ftp_user;
        $settings['rev_ftp_password'] = $rev_ftp_password;
        $settings['rev_ftp_port'] = $rev_ftp_port;
        $settings['rev_use_ftps'] = $rev_use_ftps;
        $settings['rev_enable_ftp_ie'] = $rev_enable_ftp_ie;
        
        $settings['rev_auto_export'] = $rev_auto_export;
        $settings['rev_auto_export_start_time'] = $rev_auto_export_start_time;
        $settings['rev_auto_export_interval'] = $rev_auto_export_interval;
        $settings['rev_auto_export_ftp_path'] = $rev_auto_export_ftp_path;
        $settings['rev_auto_export_ftp_file_name'] = $rev_auto_export_ftp_file_name;
        
        $settings['rev_auto_import'] = $rev_auto_import;
        $settings['rev_auto_import_start_time'] = $rev_auto_import_start_time;
        $settings['rev_auto_import_interval'] = $rev_auto_import_interval;
        $settings['rev_auto_import_profile'] = $rev_auto_import_profile;
        $settings['rev_auto_import_merge'] = $rev_auto_import_merge;
        
        // Product ipmort from URL 
        $settings['pro_enable_url_ie'] = $pro_enable_url_ie;
        $settings['pro_auto_import_url'] = $pro_auto_import_url;
        $settings['pro_auto_import_url_delimiter'] = $pro_auto_import_url_delimiter;
        $settings['pro_auto_import_url_start_time'] = $pro_auto_import_url_start_time;
        $settings['pro_auto_import_url_interval'] = $pro_auto_import_url_interval;
        $settings['pro_auto_import_url_profile'] = $pro_auto_import_url_profile;
        $settings['pro_auto_import_url_merge'] = $pro_auto_import_url_merge;
        $settings['pro_auto_import_url_skip'] = $pro_auto_import_url_skip;
        $settings['pro_auto_import_url_delete_products'] = $pro_auto_import_url_delete_products;
        
        $settings_db = get_option('woocommerce_' . WF_PROD_IMP_EXP_ID . '_settings', null);
        
        $pro_orig_export_start_inverval = '';
        if (isset($settings_db['pro_auto_export_start_time']) && isset($settings_db['pro_auto_export_interval'])) {
            $pro_orig_export_start_inverval = $settings_db['pro_auto_export_start_time'] . $settings_db['pro_auto_export_interval'];
        }
        
        $rev_orig_export_start_inverval = '';
        if (isset($settings_db['rev_auto_export_start_time']) && isset($settings_db['rev_auto_export_interval'])) {
            $rev_orig_export_start_inverval = $settings_db['rev_auto_export_start_time'] . $settings_db['rev_auto_export_interval'];
        }
        
        $pro_orig_import_start_inverval = '';
        if (isset($settings_db['pro_auto_import_start_time']) && isset($settings_db['pro_auto_import_interval'])) {
            $pro_orig_import_start_inverval = $settings_db['pro_auto_import_start_time'] . $settings_db['pro_auto_import_interval'];
        }
        
        $rev_orig_import_start_inverval = '';
        if (isset($settings_db['rev_auto_import_start_time']) && isset($settings_db['rev_auto_import_interval'])) {
            $rev_orig_import_start_inverval = $settings_db['rev_auto_import_start_time'] . $settings_db['rev_auto_import_interval'];
        }
        
        // Product ipmort from URL 
        $pro_orig_import_url_start_inverval = '';
        if (isset($settings_db['pro_auto_import_url_start_time']) && isset($settings_db['pro_auto_import_url_interval'])) {
            $pro_orig_import_url_start_inverval = $settings_db['pro_auto_import_url_start_time'] . $settings_db['pro_auto_import_url_interval'];
        }
        
        update_option('woocommerce_' . WF_PROD_IMP_EXP_ID . '_settings', $settings);
        
        update_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto', $pro_auto_advanced_import);
        
        // clear scheduled export event in case export interval was changed
        
        if (($pro_orig_export_start_inverval !== $settings['pro_auto_export_start_time'] . $settings['pro_auto_export_interval']) || (!$pro_enable_ftp_ie) || ($pro_auto_export === 'Disabled')) {
            // note this resets the next scheduled execution time to the time options were saved + the interval
            wp_clear_scheduled_hook('wf_woocommerce_csv_im_ex_auto_export_products');
        }
        
        if (($rev_orig_export_start_inverval !== $settings['rev_auto_export_start_time'] . $settings['rev_auto_export_interval']) || (!$rev_enable_ftp_ie) || ($rev_auto_export === 'Disabled')) {
            // note this resets the next scheduled execution time to the time options were saved + the interval
            wp_clear_scheduled_hook('wf_pr_rev_csv_im_ex_auto_export_products');
        }
        
        // clear scheduled import event in case import interval was changed
        if (($pro_orig_import_start_inverval !== $settings['pro_auto_import_start_time'] . $settings['pro_auto_import_interval']) || (!$pro_enable_ftp_ie) || ($pro_auto_import === 'Disabled')) {
            // note this resets the next scheduled execution time to the time options were saved + the interval
            wp_clear_scheduled_hook('wf_woocommerce_csv_im_ex_auto_import_products');
        }
        if (($rev_orig_import_start_inverval !== $settings['rev_auto_import_start_time'] . $settings['rev_auto_import_interval']) || (!$rev_enable_ftp_ie) || ($rev_auto_import === 'Disabled')) {
            // note this resets the next scheduled execution time to the time options were saved + the interval
            wp_clear_scheduled_hook('wf_pr_rev_csv_im_ex_auto_import_products');
        }
        
        // Product ipmort from URL 
        // clear scheduled url import event in case import interval was changed
        if ($pro_orig_import_url_start_inverval !== $settings['pro_auto_import_url_start_time'] . $settings['pro_auto_import_url_interval']) {
            // note this resets the next scheduled execution time to the time options were saved + the interval
            wp_clear_scheduled_hook('wf_woocommerce_csv_im_ex_auto_import_products_from_url');
        }
        
        wp_redirect(admin_url('/admin.php?page=' . WF_WOOCOMMERCE_CSV_IM_EX . '&tab=settings'));
        exit;
    }

}