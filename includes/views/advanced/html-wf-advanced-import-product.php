<?php
$settings = get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_settings', null);

$pro_ftp_server = isset($settings['pro_ftp_server']) ? $settings['pro_ftp_server'] : '';
$pro_ftp_user = isset($settings['pro_ftp_user']) ? $settings['pro_ftp_user'] : '';
$pro_ftp_password = isset($settings['pro_ftp_password']) ? $settings['pro_ftp_password'] : '';
$pro_ftp_port = isset($settings['pro_ftp_port']) ? $settings['pro_ftp_port'] : 21;
$pro_use_ftps = isset($settings['pro_use_ftps']) ? $settings['pro_use_ftps'] : '';

$pro_server_path = isset($settings['pro_server_path']) ? $settings['pro_server_path'] : null;

$pro_delimiter = isset($settings['pro_delimiter']) ? $settings['pro_delimiter'] : ',';

$pro_images_path = isset($settings['pro_images_path']) ? $settings['pro_images_path'] : '/';

$pro_main_category = isset($settings['pro_main_category']) ? $settings['pro_main_category'] : '';
$pro_sub_category = isset($settings['pro_sub_category']) ? $settings['pro_sub_category'] : '';

// For Product and Review Test FTP 
wp_enqueue_script('woocommerce-prod-all-piep-test-ftp', plugins_url(basename(plugin_dir_path(WF_ProdImpExpCsv_FILE)) . '/js/piep_test_ftp_connection.js', basename(__FILE__)));
// For Product Test FTP 
$xa_prod_all_piep_ftp = array('admin_ajax_url' => admin_url('admin-ajax.php'));
wp_localize_script('woocommerce-prod-all-piep-test-ftp', 'xa_prod_piep_test_ftp', $xa_prod_all_piep_ftp);
// For Setting the column information
$xa_prod_all_piep_ftp = array('admin_ajax_url' => admin_url('admin-ajax.php'));
wp_enqueue_script('woocommerce-prod-advanced-impexp-settings', plugins_url(basename(plugin_dir_path(WF_ProdImpExpCsv_FILE)) . '/js/advanced_impexp_settings.js', basename(__FILE__)));
$xa_prod_advanced_impexp_settings = array('admin_ajax_url' => admin_url('admin-ajax.php'));
wp_localize_script('woocommerce-prod-advanced-impexp-settings', 'xa_prod_advanced_impexp_settings', $xa_prod_advanced_impexp_settings);

if ($pro_scheduled_timestamp = wp_next_scheduled('wf_woocommerce_csv_im_ex_auto_export_products')) {
    $pro_scheduled_desc = sprintf(__('The next export is scheduled on <code>%s</code>', 'wf_csv_import_export'), get_date_from_gmt(date('Y-m-d H:i:s', $pro_scheduled_timestamp), wc_date_format() . ' ' . wc_time_format()));
} else {
    $pro_scheduled_desc = __('There is no export scheduled.', 'wf_csv_import_export');
}
if ($pro_scheduled_import_timestamp = wp_next_scheduled('wf_woocommerce_csv_im_ex_auto_import_products')) {
    $pro_scheduled_import_desc = sprintf(__('The next import is scheduled on <code>%s</code>', 'wf_csv_import_export'), get_date_from_gmt(date('Y-m-d H:i:s', $pro_scheduled_import_timestamp), wc_date_format() . ' ' . wc_time_format()));
} else {
    $pro_scheduled_import_desc = __('There is no import scheduled.', 'wf_csv_import_export');
}

?>

<div class="tool-box">
    <form action="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&action=advanced'); ?>" method="post">
        <div class="tool-box bg-white p-20p">
            <h3 class="title aw-title"><?php _e('FTP Settings for Import Products', 'wf_csv_import_export'); ?></h3>
            <table class="form-table">
                <tr style="">
                    <td colspan="2">
                        <div style=" ">
                            <table class="form-table">
                                <tr>
                                    <th >
                                        <label for="pro_ftp_server"><?php _e('FTP Server Host/IP', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_ftp_server" id="pro_ftp_server" placeholder="XXX.XXX.XXX.XXX" value="<?php echo $pro_ftp_server; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter your FTP server hostname', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_ftp_user"><?php _e('FTP User Name', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_ftp_user" id="pro_ftp_user"  value="<?php echo $pro_ftp_user; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter your FTP username', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_ftp_password"><?php _e('FTP Password', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="password" name="pro_ftp_password" id="pro_ftp_password"  value="<?php echo $pro_ftp_password; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter your FTP password', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_ftp_port"><?php _e('FTP Port', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_ftp_port" id="pro_ftp_port"  value="<?php echo $pro_ftp_port; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter your port number', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_use_ftps"><?php _e('Use FTPS', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="pro_use_ftps" id="pro_use_ftps" class="checkbox" <?php checked($pro_use_ftps, 1); ?> />
                                        <p style="font-size: 12px"><?php _e('Enable this send data over a network with SSL encryption', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <input type="button" id="prod_test_ftp_connection" class="button button-primary" value="<?php _e('Test FTP', 'wf_csv_import_export'); ?>" />
                                        <span class ="spinner " ></span>
                                    </th>
                                    <td id="prod_ftp_test_notice"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_server_path"><?php _e('Import URL', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_server_path" id="pro_server_path"  value="<?php echo $pro_server_path; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Complete CSV path including name.', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_delimiter"><?php _e('Delimiter', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" id="pro_delimiter" name="pro_delimiter" placeholder="," size="2" value="<?php echo $pro_delimiter; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_images_path"><?php _e('Images URL', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_images_path" id="pro_images_path"  value="<?php echo $pro_images_path; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Complete images path.', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_main_category"><?php _e('Main Category Column', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_main_category" id="pro_main_category"  value="<?php echo $pro_main_category; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter main category column', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_sub_category"><?php _e('Sub Category Column', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_sub_category" id="pro_sub_category"  value="<?php echo $pro_sub_category; ?>" class="input-text" />
                                        <p style="font-size: 12px"><?php _e('Enter sub category column', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tr></tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <input type="button" id="prod-advanced-save-settings-upload-file" class="button button-primary" value="<?php _e('Save Settings And Upload File', 'wf_csv_import_export'); ?>" />
    </form>
</div>
<div class="wf-import-greeting tool-box bg-white p-20p hidden" id="prod-advaced-import-result">
</div>
