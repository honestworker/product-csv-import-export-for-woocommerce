<?php
$settings = get_option('woocommerce_' . WF_PROD_IMP_EXP_ID . '_settings', null);

$advanced_settings = get_option('woocommerce_' . WF_PROD_IMP_EXP_ADVANCED_ID . '_auto', null);

$pro_ftp_server = isset($settings['pro_ftp_server']) ? $settings['pro_ftp_server'] : '';
$pro_ftp_user = isset($settings['pro_ftp_user']) ? $settings['pro_ftp_user'] : '';
$pro_ftp_password = isset($settings['pro_ftp_password']) ? $settings['pro_ftp_password'] : '';
$pro_ftp_port = isset($settings['pro_ftp_port']) ? $settings['pro_ftp_port'] : 21;
$pro_use_ftps = isset($settings['pro_use_ftps']) ? $settings['pro_use_ftps'] : '';
$pro_enable_ftp_ie = isset($settings['pro_enable_ftp_ie']) ? $settings['pro_enable_ftp_ie'] : '';

$pro_auto_export = isset($settings['pro_auto_export']) ? $settings['pro_auto_export'] : 'Disabled';
$pro_auto_export_ftp_path = isset($settings['pro_auto_export_ftp_path']) ? $settings['pro_auto_export_ftp_path'] : '/';
$pro_auto_export_ftp_file_name = isset($settings['pro_auto_export_ftp_file_name']) ? $settings['pro_auto_export_ftp_file_name'] : null;
$pro_auto_export_do_shortcode = isset($settings['pro_auto_export_do_shortcode']) ? $settings['pro_auto_export_do_shortcode'] : '';
$pro_auto_export_start_time = isset($settings['pro_auto_export_start_time']) ? $settings['pro_auto_export_start_time'] : '';
$pro_auto_export_interval = isset($settings['pro_auto_export_interval']) ? $settings['pro_auto_export_interval'] : '';
$pro_auto_export_profile = isset($settings['pro_auto_export_profile']) ? $settings['pro_auto_export_profile'] : '';
$pro_auto_export_categories = isset($settings['pro_auto_export_categories']) ? $settings['pro_auto_export_categories'] : '';
$pro_auto_export_include_hidden_meta = isset($settings['pro_auto_export_include_hidden_meta']) ? $settings['pro_auto_export_include_hidden_meta'] : '';

$pro_auto_import = isset($settings['pro_auto_import']) ? $settings['pro_auto_import'] : 'Disabled';
$pro_auto_advanced_import = isset($advanced_settings) ? $advanced_settings : '';
$pro_auto_import_file = isset($settings['pro_auto_import_file']) ? $settings['pro_auto_import_file'] : null;
$pro_auto_import_delimiter = !empty($settings['pro_auto_import_delimiter']) ? $settings['pro_auto_import_delimiter'] : ',';
$pro_auto_import_start_time = isset($settings['pro_auto_import_start_time']) ? $settings['pro_auto_import_start_time'] : '';
$pro_auto_import_interval = isset($settings['pro_auto_import_interval']) ? $settings['pro_auto_import_interval'] : '';
$pro_auto_import_profile = isset($settings['pro_auto_import_profile']) ? $settings['pro_auto_import_profile'] : '';
$pro_auto_import_merge = isset($settings['pro_auto_import_merge']) ? $settings['pro_auto_import_merge'] : 0;
$pro_auto_import_skip = isset($settings['pro_auto_import_skip']) ? $settings['pro_auto_import_skip'] : 0;
$pro_auto_delete_products = isset($settings['pro_auto_delete_products']) ? $settings['pro_auto_delete_products'] : 0;
$pro_auto_new_prod_status = isset($settings['pro_auto_new_prod_status']) ? $settings['pro_auto_new_prod_status'] : '';
$prod_auto_use_chidren_sku = isset($settings['prod_auto_use_chidren_sku']) ? $settings['prod_auto_use_chidren_sku'] : 0;
$pro_auto_use_sku_upsell_crosssell = isset($settings['pro_auto_use_sku_upsell_crosssell']) ? $settings['pro_auto_use_sku_upsell_crosssell'] : 0;


//review

$rev_ftp_server = isset($settings['rev_ftp_server']) ? $settings['rev_ftp_server'] : '';
$rev_ftp_user = isset($settings['rev_ftp_user']) ? $settings['rev_ftp_user'] : '';
$rev_ftp_password = isset($settings['rev_ftp_password']) ? $settings['rev_ftp_password'] : '';
$rev_ftp_port = isset($settings['rev_ftp_port']) ? $settings['rev_ftp_port'] : 21;
$rev_use_ftps = isset($settings['rev_use_ftps']) ? $settings['rev_use_ftps'] : '';
$rev_enable_ftp_ie = isset($settings['rev_enable_ftp_ie']) ? $settings['rev_enable_ftp_ie'] : '';

$rev_auto_export = isset($settings['rev_auto_export']) ? $settings['rev_auto_export'] : 'Disabled';
$rev_auto_export_start_time = isset($settings['rev_auto_export_start_time']) ? $settings['rev_auto_export_start_time'] : '';
$rev_auto_export_interval = isset($settings['rev_auto_export_interval']) ? $settings['rev_auto_export_interval'] : '';
$rev_auto_export_ftp_path = isset($settings['rev_auto_export_ftp_path']) ? $settings['rev_auto_export_ftp_path'] : '/';
$rev_auto_export_ftp_file_name = isset($settings['rev_auto_export_ftp_file_name']) ? $settings['rev_auto_export_ftp_file_name'] : null;

$rev_auto_import = isset($settings['rev_auto_import']) ? $settings['rev_auto_import'] : 'Disabled';
$rev_auto_import_start_time = isset($settings['rev_auto_import_start_time']) ? $settings['rev_auto_import_start_time'] : '';
$rev_auto_import_interval = isset($settings['rev_auto_import_interval']) ? $settings['rev_auto_import_interval'] : '';
$rev_auto_import_profile = isset($settings['rev_auto_import_profile']) ? $settings['rev_auto_import_profile'] : '';
$rev_auto_import_merge = isset($settings['rev_auto_import_merge']) ? $settings['rev_auto_import_merge'] : 0;


// Product ipmort from URL 
$pro_enable_url_ie = isset($settings['pro_enable_url_ie']) ? $settings['pro_enable_url_ie'] : '';
$pro_auto_import_url = isset($settings['pro_auto_import_url']) ? $settings['pro_auto_import_url'] : null;
$pro_auto_import_url_delimiter = !empty($settings['pro_auto_import_url_delimiter']) ? $settings['pro_auto_import_url_delimiter'] : ',';
$pro_auto_import_url_start_time = isset($settings['pro_auto_import_url_start_time']) ? $settings['pro_auto_import_url_start_time'] : '';
$pro_auto_import_url_interval = isset($settings['pro_auto_import_url_interval']) ? $settings['pro_auto_import_url_interval'] : '';
$pro_auto_import_url_profile = isset($settings['pro_auto_import_url_profile']) ? $settings['pro_auto_import_url_profile'] : '';
$pro_auto_import_url_merge = isset($settings['pro_auto_import_url_merge']) ? $settings['pro_auto_import_url_merge'] : '';
$pro_auto_import_url_skip = isset($settings['pro_auto_import_url_skip']) ? $settings['pro_auto_import_url_skip'] : '';
$pro_auto_import_url_delete_products = isset($settings['pro_auto_import_url_delete_products']) ? $settings['pro_auto_import_url_delete_products'] : '';


//For Product and Review Test FTP 
wp_enqueue_script('woocommerce-prod-all-piep-test-ftp', plugins_url(basename(plugin_dir_path(WF_ProdImpExpCsv_FILE)) . '/js/piep_test_ftp_connection.js', basename(__FILE__)));
$xa_prod_all_piep_ftp = array('admin_ajax_url' => admin_url('admin-ajax.php'));
//For Product Test FTP 
wp_localize_script('woocommerce-prod-all-piep-test-ftp', 'xa_prod_piep_test_ftp', $xa_prod_all_piep_ftp);
//For Review Test FTP
wp_localize_script('woocommerce-prod-all-piep-test-ftp', 'xa_prod_review_test_ftp', $xa_prod_all_piep_ftp);

wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_product_csv_cron_params', array('pro_enable_ftp_ie' => $pro_enable_ftp_ie, 'pro_auto_export' => $pro_auto_export, 'pro_auto_import' => $pro_auto_import));

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



wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_cron_params', array('rev_enable_ftp_ie' => $rev_enable_ftp_ie, 'rev_auto_export' => $rev_auto_export, 'rev_auto_import' => $rev_auto_import));
if ($rev_scheduled_timestamp = wp_next_scheduled('wf_pr_rev_csv_im_ex_auto_export_products')) {
    $rev_scheduled_desc = sprintf(__('The next export is scheduled on <code>%s</code>', 'wf_csv_import_export'), get_date_from_gmt(date('Y-m-d H:i:s', $rev_scheduled_timestamp), wc_date_format() . ' ' . wc_time_format()));
} else {
    $rev_scheduled_desc = __('There is no export scheduled.', 'wf_csv_import_export');
}
if ($rev_scheduled_import_timestamp = wp_next_scheduled('wf_pr_rev_csv_im_ex_auto_import_products')) {
    $rev_scheduled_import_desc = sprintf(__('The next import is scheduled on <code>%s</code>', 'wf_csv_import_export'), get_date_from_gmt(date('Y-m-d H:i:s', $rev_scheduled_import_timestamp), wc_date_format() . ' ' . wc_time_format()));
} else {
    $rev_scheduled_import_desc = __('There is no import scheduled.', 'wf_csv_import_export');
}



// Product ipmort from URL 
//wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_product_csv_cron_params_url', array('pro_enable_url_ie' => $pro_enable_url_ie));
if ($pro_scheduled_import_url_timestamp = wp_next_scheduled('wf_woocommerce_csv_im_ex_auto_import_products_from_url')) {
    $pro_scheduled_import_url_desc = sprintf(__('The next import is scheduled on <code>%s</code>', 'wf_csv_import_export'), get_date_from_gmt(date('Y-m-d H:i:s', $pro_scheduled_import_url_timestamp), wc_date_format() . ' ' . wc_time_format()));
} else {
    $pro_scheduled_import_url_desc = __('There is no import scheduled.', 'wf_csv_import_export');
}
?>


<div class="tool-box">

    <form action="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&action=settings'); ?>" method="post">
        <div class="tool-box bg-white p-20p">
            <h3 class="title aw-title"><?php _e('FTP Settings for Import/Export Products', 'wf_csv_import_export'); ?></h3>
            <table class="form-table">
                <tr style="">
                    <th style="">
                        <label for="pro_enable_ftp_ie"><?php _e('Enable FTP', 'wf_csv_import_export'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="pro_enable_ftp_ie" id="pro_enable_ftp_ie" class="checkbox" <?php checked($pro_enable_ftp_ie, 1); ?> />
                        <p style="font-size: 12px"><?php _e('Check to enable FTP', 'wf_csv_import_export'); ?></p> 
                    </td>
                </tr>
                <tr style="">
                    <td colspan="2">
                        <div style=" ">
                            <table class="form-table" id="pro_export_section_all">

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
                                <tr></tr>

                                <tr>
                                    <th>
                                        <label for="pro_auto_export_ftp_path"><?php _e('Export Path', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_auto_export_ftp_path" id="pro_auto_export_ftp_path"  value="<?php echo $pro_auto_export_ftp_path; ?>"/>
                                        <p style="font-size: 12px"><?php _e('Specify the path in the server to which the file will be exported', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>

                                <tr style="border-bottom: 1px solid #f1f1f1">
                                    <th>
                                        <label for="pro_auto_export_ftp_file_name"><?php _e('Export Filename', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_auto_export_ftp_file_name" id="pro_auto_export_ftp_file_name"  value="<?php echo $pro_auto_export_ftp_file_name; ?>" placeholder="For example sample.csv"/>
                                        <p style="font-size: 12px"><?php _e('Specify the name of the CSV file exported', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>

                                <tr style="border-bottom: 1px dotted #f1f1f1">
                                    <th>
                                        <label for="pro_auto_export"><?php _e('Automatically Export Products', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <select class="" style="" id="pro_auto_export" name="pro_auto_export">
                                            <option <?php if ($pro_auto_export === 'Disabled') echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
                                            <option <?php if ($pro_auto_export === 'Enabled') echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
                                        </select>
                                        <p style="font-size: 12px"><?php _e('Select to enable exporting products automatically', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>

                                <tbody class="pro_export_section">
                                    <tr>
                                        <th>
                                            <label for="pro_auto_export_do_shortcode"><?php _e('Convert shortcodes to html', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_export_do_shortcode" id="pro_auto_export_do_shortcode" <?php
                                            if ($pro_auto_export_do_shortcode == 'on')
                                                echo 'checked';
                                            ?> class="checkbox" />
                                            <p style="font-size: 12px"><?php _e('Check this to convert the shortcode to HTML in the exported CSV file', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="v_prod_categories"><?php _e('Product Categories', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <select id="v_prod_categories" name="pro_auto_export_categories[]" data-placeholder="<?php _e('Any Category', 'wf_csv_import_export'); ?>" class="wc-enhanced-select" multiple="multiple">
                                                <?php
                                                $product_categories = get_terms('product_cat', array('fields' => 'id=>name'));
                                                foreach ($product_categories as $category_id => $category_name) {
                                                    if (!empty($pro_auto_export_categories) && in_array($category_id, $pro_auto_export_categories))
                                                        echo '<option value="' . $category_id . '"selected>' . ( ( get_bloginfo('version') < '4.8') ? $category_name : get_term_parents_list($category_id, 'product_cat', array('separator' => ' -> ')) ) . '</option>';
                                                    else
                                                        echo '<option value="' . $category_id . '">' . ( ( get_bloginfo('version') < '4.8') ? $category_name : get_term_parents_list($category_id, 'product_cat', array('separator' => ' -> ')) ) . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <p style="font-size: 12px"><?php _e('The products under only these categories will be exported / Filter the products to be exported by category', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_export_start_time"><?php _e('Export Start Time', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_export_start_time" id="pro_auto_export_start_time"  value="<?php echo $pro_auto_export_start_time; ?>"/>
                                            <span class="description"><?php echo sprintf(__('Local time is <code>%s</code>.', 'wf_csv_import_export'), date_i18n(wc_time_format())) . ' ' . $pro_scheduled_desc; ?></span>
                                            <br/>
                                            <p style="font-size: 12px"><?php _e('Enter the time at which the export will start in the format  6:18pm or 12:27am', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_export_interval"><?php _e('Export Interval [ Minutes ]', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_export_interval" id="pro_auto_export_interval"  value="<?php echo $pro_auto_export_interval; ?>"  />
                                            <p style="font-size: 12px"><?php _e('Enter the interval at which the export will take place, in minutes', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <?php
                                    $pro_exp_mapping_from_db = get_option('xa_prod_csv_export_mapping');
                                    if (!empty($pro_exp_mapping_from_db)) {
                                        ?>
                                        <tr>
                                            <th>
                                                <label for="pro_auto_export_profile"><?php _e('Select an export mapping file.'); ?></label>
                                            </th>
                                            <td>
                                                <select name="pro_auto_export_profile">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($pro_exp_mapping_from_db as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>" <?php selected($key, $pro_auto_export_profile); ?>><?php echo $key; ?></option>

                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr style="border-bottom: 1px solid #f1f1f1">
                                        <th>
                                            <label for="pro_auto_export_include_hidden_meta"><?php _e('Include hidden meta data', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_export_include_hidden_meta" id="pro_auto_export_include_hidden_meta" <?php
                                            if ($pro_auto_export_include_hidden_meta == 'on')
                                                echo 'checked';
                                            ?> class="checkbox" />
                                            <p style="font-size: 12px"><?php _e('Check if you also want to include hidden metadata in the exported CSV', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                </tbody>

                                <tr style="border-bottom: 1px dotted #f1f1f1">
                                    <th>
                                        <label for="pro_auto_import"><?php _e('Automatically Import Products', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <select class="" style="" id="pro_auto_import" name="pro_auto_import">
                                            <option <?php if ($pro_auto_import === 'Disabled') echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
                                            <option <?php if ($pro_auto_import === 'Enabled') echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
                                        </select>
                                        <p style="font-size: 12px"><?php _e('Select to enable importing products automatically', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>
                                <tbody class="pro_import_section">
                                    <tr>
                                        <th>
                                            <label for="pro_auto_advanced_import"><?php _e('Use Advanced Import', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_advanced_import" id="pro_auto_advanced_import" class="checkbox" <?php checked($pro_auto_advanced_import, 1); ?> />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_file"><?php _e('Import File', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_import_file" id="pro_auto_import_file" value="<?php echo $pro_auto_import_file; ?>" placeholder="For example /root/temp/a.csv"/>
                                            <p style="font-size: 12px"><?php _e('Enter the complete path of the CSV file to be imported', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_delimiter"><?php _e('Delimiter', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" id="pro_auto_import_delimiter" name="pro_auto_import_delimiter" placeholder="," size="2" value="<?php echo $pro_auto_import_delimiter; ?>"/>
                                            <p style="font-size: 12px"><?php _e('Enter the type of delimiter the importing file uses', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_start_time"><?php _e('Import Start Time', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_import_start_time" id="pro_auto_import_start_time"  value="<?php echo $pro_auto_import_start_time; ?>"/>
                                            <span class="description"><?php echo sprintf(__('Local time is <code>%s</code>.', 'wf_csv_import_export'), date_i18n(wc_time_format())) . ' ' . $pro_scheduled_import_desc; ?></span>
                                            <br/>
                                            <p style="font-size: 12px"><?php _e('Enter the time at which the import will start in the format  6:18pm or 12:27am', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_interval"><?php _e('Import Interval [ Minutes ]', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_import_interval" id="pro_auto_import_interval"  value="<?php echo $pro_auto_import_interval; ?>"  />
                                            <p style="font-size: 12px"><?php _e('Enter the interval at which the import will take place, in minutes', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_merge"><?php _e('Merge Products if exist', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_import_merge" id="pro_auto_import_merge"  class="checkbox" <?php checked($pro_auto_import_merge, 1); ?> />
                                            <p style="font-size: 12px"><?php _e('Enable to update the existing products in the store', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_skip"><?php _e('Skip new product', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_import_skip" id="pro_auto_import_skip"  class="checkbox" <?php checked($pro_auto_import_skip, 1); ?> />
                                            <p style="font-size: 12px"><?php _e('Enable to skip the new products in CSV while importing', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            <label for="pro_auto_delete_products"><?php _e('Delete existing products', 'wf_csv_import_export'); ?></label>                                            
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_delete_products" id="pro_auto_delete_products" class="checkbox" <?php checked($pro_auto_delete_products, 1); ?>  />
                                            <p style="font-size: 12px"><?php _e('check to <b style="color:red"> delete existing products </b>  that are not present in the CSV', 'wf_csv_import_export'); ?></p>                                            
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                    <th>
                                        <label><?php _e('Use SKU to link up-sells and cross-sells', 'wf_csv_import_export'); ?></label><br/>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="pro_auto_use_sku_upsell_crosssell" id="pro_auto_use_sku_upsell_crosssell" class="checkbox" <?php checked($pro_auto_use_sku_upsell_crosssell, 1); ?> />
                                        <span style="font-size: 12px"><?php _e('check to import up-sells and cross-sells using the product SKU', 'wf_csv_import_export'); ?></span>
                                    </td>
                                </tr>
                                    
                                    <tr>
                                        <th>
                                            <label><?php _e('New product status', 'wf_csv_import_export'); ?></label><br/>
                                        </th>
                                        <td>
                                            <input type="text" name="pro_auto_new_prod_status" id="pro_auto_new_prod_status" placeholder="draft / pending / publish " value="<?php echo $pro_auto_new_prod_status ?>"/>
                                            <span style="font-size: 12px"><?php _e('<br /> Change the product status (draft/pending/published) of all the new products imported overriding the existing status', 'wf_csv_import_export'); ?></span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            <label for="prod_auto_use_chidren_sku"><?php _e('Use SKU to link grouped products', 'wf_csv_import_export'); ?></label><br/>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="prod_auto_use_chidren_sku" id="prod_auto_use_chidren_sku"  class="checkbox" <?php checked($prod_auto_use_chidren_sku, 1); ?> />
                                            <p style="font-size: 12px"><?php _e(' Check this to link grouped products using product SKUs instead of product IDs', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <?php
                                    $pro_mapping_from_db = get_option('wf_prod_csv_imp_exp_mapping');
                                    if (!empty($pro_mapping_from_db)) {
                                        ?>
                                        <tr>
                                            <th>
                                                <label for="pro_auto_import_profile"><?php _e('Select a mapping file.'); ?></label>
                                            </th>
                                            <td>
                                                <select name="pro_auto_import_profile">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($pro_mapping_from_db as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>" <?php selected($key, $pro_auto_import_profile); ?>><?php echo $key; ?></option>

                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>        

                            </table> 
                        </div>
                    </td>
                </tr>

            </table>
        </div>



        <div class="tool-box bg-white p-20p">
            <h3 class="title aw-title"><?php _e('FTP Settings for Import/Export Reviews', 'wf_csv_import_export'); ?></h3>
            <table class="form-table">

                <tr>
                    <th>
                        <label for="rev_enable_ftp_ie"><?php _e('Enable FTP', 'wf_csv_import_export'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="rev_enable_ftp_ie" id="rev_enable_ftp_ie" class="checkbox" <?php checked($rev_enable_ftp_ie, 1); ?> />

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="form-table" id="rev_export_section_all">

                            <tr>
                                <th>
                                    <label for="rev_ftp_server"><?php _e('FTP Server Host/IP', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="rev_ftp_server" id="rev_ftp_server" placeholder="XXX.XXX.XXX.XXX" value="<?php echo $rev_ftp_server; ?>" class="input-text" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="rev_ftp_user"><?php _e('FTP User Name', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="rev_ftp_user" id="rev_ftp_user"  value="<?php echo $rev_ftp_user; ?>" class="input-text" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="rev_ftp_password"><?php _e('FTP Password', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="password" name="rev_ftp_password" id="rev_ftp_password"  value="<?php echo $rev_ftp_password; ?>" class="input-text" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="rev_ftp_port"><?php _e('FTP Port', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="rev_ftp_port" id="rev_ftp_port"  value="<?php echo $rev_ftp_port; ?>" class="input-text" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="rev_use_ftps"><?php _e('Use FTPS', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="checkbox" name="rev_use_ftps" id="rev_use_ftps" class="checkbox" <?php checked($rev_use_ftps, 1); ?> />
                                </td>
                            </tr>
                            <tr >
                                <th>
                                    <input type="button" id="rev_test_ftp_connection" class="button button-primary" value="<?php _e('Test FTP', 'wf_csv_import_export'); ?>" />
                                    <span class ="spinner " ></span>
                                </th>
                                <td id="prod_rev_ftp_test_notice"></td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="rev_auto_export_ftp_path"><?php _e('Export Path', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="rev_auto_export_ftp_path" id="rev_auto_export_ftp_path"  value="<?php echo $rev_auto_export_ftp_path; ?>"/>
                                    <p style="font-size: 12px"><?php _e('Exported CSV will be stored in the above directory.', 'wf_csv_import_export'); ?></p>
                                </td>
                            </tr>

                            <tr style="border-bottom: 1px solid #f1f1f1">
                                <th>
                                    <label for="rev_auto_export_ftp_file_name"><?php _e('Export Filename', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="rev_auto_export_ftp_file_name" id="rev_auto_export_ftp_file_name"  value="<?php echo $rev_auto_export_ftp_file_name; ?>" placeholder="For example sample.csv"/>
                                    <p style="font-size: 12px"><?php _e('Exported CSV will have the above file name(if specified).', 'wf_csv_import_export'); ?></p>
                                </td>
                            </tr>

                            <tr style="border-bottom: 1px dotted #f1f1f1">
                                <th>
                                    <label for="rev_auto_export"><?php _e('Automatically Export Reviews', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <select class="" style="" id="rev_auto_export" name="rev_auto_export">
                                        <option <?php if ($rev_auto_export === 'Disabled') echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
                                        <option <?php if ($rev_auto_export === 'Enabled') echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tbody class="rev_export_section">
                                <tr>
                                    <th>
                                        <label for="rev_auto_export_start_time"><?php _e('Export Start Time', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="rev_auto_export_start_time" id="rev_auto_export_start_time"  value="<?php echo $rev_auto_export_start_time; ?>"/>
                                        <span class="description"><?php echo sprintf(__('Local time is <code>%s</code>.', 'wf_csv_import_export'), date_i18n(wc_time_format())) . ' ' . $rev_scheduled_desc; ?></span>
                                        <br/>
                                        <span class="description"><?php _e('<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export'); ?></span>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f1f1">
                                    <th>
                                        <label for="rev_auto_export_interval"><?php _e('Export Interval [ Minutes ]', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="rev_auto_export_interval" id="rev_auto_export_interval"  value="<?php echo $rev_auto_export_interval; ?>"  />
                                    </td>
                                </tr>
                            </tbody>





                            <tr style="border-bottom: 1px dotted #f1f1f1">
                                <th>
                                    <label for="rev_auto_import"><?php _e('Automatically Import Reviews', 'wf_csv_import_export'); ?></label>
                                </th>
                                <td>
                                    <select class="" style="" id="rev_auto_import" name="rev_auto_import">
                                        <option <?php if ($rev_auto_import === 'Disabled') echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
                                        <option <?php if ($rev_auto_import === 'Enabled') echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tbody class="rev_import_section">
                                <tr>
                                    <th>
                                        <label for="rev_auto_import_start_time"><?php _e('Import Start Time', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="rev_auto_import_start_time" id="rev_auto_import_start_time"  value="<?php echo $rev_auto_import_start_time; ?>"/>
                                        <span class="description"><?php echo sprintf(__('Local time is <code>%s</code>.', 'wf_csv_import_export'), date_i18n(wc_time_format())) . ' ' . $rev_scheduled_import_desc; ?></span>
                                        <br/>
                                        <span class="description"><?php _e('<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="rev_auto_import_interval"><?php _e('Import Interval [ Minutes ]', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="rev_auto_import_interval" id="rev_auto_import_interval"  value="<?php echo $rev_auto_import_interval; ?>"  />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="rev_auto_import_merge"><?php _e('Merge Reviews if exist', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="rev_auto_import_merge" id="rev_auto_import_merge"  class="checkbox" <?php checked($rev_auto_import_merge, 1); ?> />
                                    </td>
                                </tr>

                                <?php
                                $rev_mapping_from_db = get_option('wf_prod_review_csv_imp_exp_mapping');
                                if (!empty($rev_mapping_from_db)) {
                                    ?>
                                    <tr>
                                        <th>
                                            <label for="rev_auto_import_profile"><?php _e('Select a mapping file.'); ?></label>
                                        </th>
                                        <td>
                                            <select name="rev_auto_import_profile">
                                                <option value="">--Select--</option>
                                                <?php foreach ($rev_mapping_from_db as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>" <?php selected($key, $rev_auto_import_profile); ?>><?php echo $key; ?></option>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>        

                        </table> 
                    </td></tr>


            </table>
        </div>



        <!--Import form URL code start here by fasil-->
        <div class="tool-box bg-white p-20p">
            <h3 class="title aw-title"><?php _e('URL Settings for Import Products', 'wf_csv_import_export'); ?></h3>
            <table class="form-table">

                <tr>
                    <th>
                        <label for="pro_enable_url_ie"><?php _e('Enable URL Import', 'wf_csv_import_export'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="pro_enable_url_ie" id="pro_enable_url_ie" class="checkbox" <?php checked($pro_enable_url_ie, 1); ?> />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="form-table" id="pro_import_from_url_section_all">
                            <tbody class="pro_import_from_url_section">

                                <?php /*
                                  <!--                  <tr>
                                  <th>
                                  <label for="import_url"><?php _e('Source URL', 'wf_csv_import_export'); ?></label>
                                  </th>
                                  <td>
                                  <input type="text" name="import_url" id="import_from_url" placeholder="" value="<?php echo $import_url; ?>" class="input-text" />
                                  </td>
                                  </tr>


                                  <tr>
                                  <th>
                                  <label for="pro_auto_import_from_url"><?php _e('Automatically Import Products from URL', 'wf_csv_import_export'); ?></label>
                                  </th>
                                  <td>
                                  <select class="" style="" id="pro_auto_import_from_url" name="pro_auto_import_from_url">
                                  <option <?php if ($pro_auto_import_from_url === 'Disabled') echo 'selected'; ?> value="Disabled"><?php _e('Disabled', 'wf_csv_import_export'); ?></option>
                                  <option <?php if ($pro_auto_import_from_url === 'Enabled') echo 'selected'; ?> value="Enabled"><?php _e('Enabled', 'wf_csv_import_export'); ?></option>
                                  </select>
                                  </td>
                                  </tr>--> */ ?>


                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url"><?php _e('Import URL', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_auto_import_url" id="pro_auto_import_url" value="<?php echo $pro_auto_import_url; ?>" placeholder="For example /root/temp/a.csv"/>
                                        <p style="font-size: 12px"><?php _e('Complete CSV path including name.', 'wf_csv_import_export'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url_delimiter"><?php _e('Delimiter', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" id="pro_auto_import_url_delimiter" name="pro_auto_import_url_delimiter" placeholder="," size="2" value="<?php echo $pro_auto_import_url_delimiter; ?>"/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url_start_time"><?php _e('Import Start Time', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_auto_import_url_start_time" id="pro_auto_import_url_start_time"  value="<?php echo $pro_auto_import_url_start_time; ?>"/>
                                        <span class="description"><?php echo sprintf(__('Local time is <code>%s</code>.', 'wf_csv_import_export'), date_i18n(wc_time_format())) . ' ' . $pro_scheduled_import_url_desc; ?></span>
                                        <br/>
                                        <span class="description"><?php _e('<code>Enter like 6:18pm or 12:27am</code>', 'wf_csv_import_export'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url_interval"><?php _e('Import Interval [ Minutes ]', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="pro_auto_import_url_interval" id="pro_auto_import_url_interval"  value="<?php echo $pro_auto_import_url_interval; ?>"  />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url_merge"><?php _e('Merge Products if exist', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="pro_auto_import_url_merge" id="pro_auto_import_url_merge"  class="checkbox" <?php checked($pro_auto_import_url_merge, 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="pro_auto_import_url_skip"><?php _e('Skip new product', 'wf_csv_import_export'); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="pro_auto_import_url_skip" id="pro_auto_import_url_skip"  class="checkbox" <?php checked($pro_auto_import_url_skip, 1); ?> />
                                    </td>
                                </tr>
                                
                                <tr>
                                        <th>
                                            <label for="pro_auto_import_url_delete_products"><?php _e('Delete existing products', 'wf_csv_import_export'); ?></label>                                            
                                        </th>
                                        <td>
                                            <input type="checkbox" name="pro_auto_import_url_delete_products" id="pro_auto_import_url_delete_products" class="checkbox" <?php checked($pro_auto_import_url_delete_products, 1); ?>  />
                                            <p style="font-size: 12px"><?php _e('check to <b style="color:red"> delete existing products </b>  that are not present in the CSV', 'wf_csv_import_export'); ?></p>                                            
                                        </td>
                                    </tr>

                                <?php
                                $pro_mapping_from_db = get_option('wf_prod_csv_imp_exp_mapping');
                                if (!empty($pro_mapping_from_db)) {
                                    ?>
                                    <tr>
                                        <th>
                                            <label for="pro_auto_import_url_profile"><?php _e('Select a mapping file.'); ?></label>
                                        </th>
                                        <td>
                                            <select name="pro_auto_import_url_profile">
                                                <option value="">--Select--</option>
                                                <?php foreach ($pro_mapping_from_db as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>" <?php selected($key, $pro_auto_import_url_profile); ?>><?php echo $key; ?></option>

                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>

                        </table>
                    </td>
                </tr>

            </table>

        </div>
        <!--Import form URL code end here by fasil--> 




        <p class="submit"><input type="submit" class="button button-primary" value="<?php _e('Save Settings', 'wf_csv_import_export'); ?>" /></p>

    </form>
</div>
