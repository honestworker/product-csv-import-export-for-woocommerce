<?php
$ftp_server = '';
$ftp_user = '';
$ftp_password = '';
$ftp_port = '';
$use_ftps = '';
$enable_ftp_ie = '';
$ftp_server_path = '';
if (!empty($ftp_settings)) {
    $ftp_server = !empty($ftp_settings['rev_ftp_server']) ? $ftp_settings['rev_ftp_server'] : '';
    $ftp_user = !empty($ftp_settings['rev_ftp_user']) ? $ftp_settings['rev_ftp_user'] : '';
    $ftp_password = !empty($ftp_settings['rev_ftp_password']) ? $ftp_settings['rev_ftp_password'] : '';
    $ftp_port = !empty($ftp_settings['rev_ftp_port']) ? $ftp_settings['rev_ftp_port'] : 21;
    $use_ftps = !empty($ftp_settings['rev_use_ftps']) ? $ftp_settings['rev_use_ftps'] : '';
    $enable_ftp_ie = !empty($ftp_settings['rev_enable_ftp_ie']) ? $ftp_settings['rev_enable_ftp_ie'] : '';
    $ftp_server_path = !empty($ftp_settings['rev_ftp_server_path']) ? $ftp_settings['rev_ftp_server_path'] : '';
}
wp_enqueue_script('woocommerce-prod-rev-test-ftp', plugins_url(basename(plugin_dir_path(WF_ProdImpExpCsv_FILE)) . '/js/piep_test_ftp_connection.js', basename(__FILE__)));
$xa_rev_piep_ftp = array('admin_ajax_url' => admin_url('admin-ajax.php'));
wp_localize_script('woocommerce-prod-rev-test-ftp', 'xa_prod_review_test_ftp', $xa_rev_piep_ftp);
?>
<div class="woocommerce">
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex') ?>" class="nav-tab "><?php _e('Product', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=review') ?>" class="nav-tab nav-tab-active"><?php _e('Product Reviews ', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=settings') ?>" class="nav-tab "><?php _e('Settings', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=advanced') ?>" class="nav-tab "><?php _e('Advanced', 'wf_csv_import_export'); ?></a>
        <?php
        $plugin_name = 'productimportexport';
        $status = get_option($plugin_name . '_activation_status');
        ?>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=licence') ?>" class="nav-tab licence-tab "><?php _e('Licence', 'wf_csv_import_export') . ($status ? _e('<span class="actived">Activated</span>', 'wf_csv_import_export') : _e('<span class="deactived">Deactivated</span>', 'wf_csv_import_export')); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=help') ?>" class="nav-tab "><?php _e('Help Guide', 'wf_csv_import_export'); ?></a> 
    </h2>

    <ul class="subsubsub" style="margin-left: 15px;">
        <li><a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=review') ?>" class=""><?php _e('Export', 'wf_csv_import_export'); ?></a> | </li>
        <li><a href="<?php echo admin_url('admin.php?import=product_reviews_csv') ?>" class="current"><?php _e('Import', 'wf_csv_import_export'); ?></a> </li>
    </ul>
    <br/>  
    <div class="wf-import-greeting-review tool-box bg-white p-20p">
        <h3 class="title aw-title"><?php _e('Import Product Reviews in CSV Format:', 'wf_csv_import_export'); ?></h3>
        <p><?php _e('Import product reviews in CSV format from different sources (  from your computer OR from another server via FTP).', 'wf_csv_import_export'); ?></p>
        <p><?php //_e('You can import product reviews (in CSV format) in to the shop using any of below methods.', 'wf_csv_import_export');  ?></p>

        <?php if (!empty($upload_dir['error'])) : ?>
            <div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
                <p><strong><?php echo $upload_dir['error']; ?></strong></p></div>
        <?php else : ?>
            <form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($action, 'import-upload')); ?>">
                <table class="form-table">
                    <tbody>
                        <tr style="">
                            <th style="">
                                <label for="upload"><?php _e('Method 1: Select a file from your computer', 'wf_csv_import_export'); ?></label>
                            </th>
                            <td>
                                <input type="file" id="upload" name="import" size="25" />
                                <input type="hidden" name="action" value="save" />
                                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                                <small><?php printf(__('Maximum size: %s'), $size); ?></small>
                            </td>
                        </tr>

                        <tr style="">
                            <th style="">
                                <label for="ftp"><?php _e('Method 2: Provide FTP Details:', 'wf_csv_import_export'); ?></label>
                            </th>
                            <td>
                                <table class="form-table">
                                    <tr>
                                        <th>
                                            <label for="rev_enable_ftp_ie"><?php _e('Enable FTP import', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="rev_enable_ftp_ie" id="rev_enable_ftp_ie" class="checkbox" <?php checked($enable_ftp_ie, 1); ?> />
                                            <p style="font-size: 12px"><?php _e('Check to enable FTP', 'wf_csv_import_export'); ?></p> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="rev_ftp_server"><?php _e('FTP Server Host/IP', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="rev_ftp_server" id="rev_ftp_server" placeholder="<?php _e('XXX.XXX.XXX.XXX', 'wf_csv_import_export'); ?>" value="<?php echo $ftp_server; ?>" class="input-text" />
                                            <p style="font-size: 12px"><?php _e('Enter your FTP server hostname', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="ftp_user"><?php _e('FTP User Name', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="rev_ftp_user" id="rev_ftp_user"  value="<?php echo $ftp_user; ?>" class="input-text" />
                                            <p style="font-size: 12px"><?php _e('Enter your FTP username', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="rev_ftp_password"><?php _e('FTP Password', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="password" name="rev_ftp_password" id="rev_ftp_password"  value="<?php echo $ftp_password; ?>" class="input-text" />
                                            <p style="font-size: 12px"><?php _e('Enter your FTP password', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="rev_ftp_port"><?php _e('FTP Port', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="rev_ftp_port" id="rev_ftp_port" placeholder="21 (default) " value="<?php echo $ftp_port; ?>" class="input-text" />
                                            <p style="font-size: 12px"><?php _e('Enter your port number', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label for="rev_ftp_server_path"><?php _e('FTP Server Path', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" name="rev_ftp_server_path" id="rev_ftp_server_path"  value="<?php echo $ftp_server_path; ?>" class="input-text" />
                                            <p style="font-size: 12px"><?php _e('Enter the FTP server path', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>
                                            <label for="rev_use_ftps"><?php _e('Use FTPS', 'wf_csv_import_export'); ?></label>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="rev_use_ftps" id="rev_use_ftps" class="checkbox" <?php checked($use_ftps, 1); ?> />
                                            <p style="font-size: 12px"><?php _e('Enable this send data over a network with SSL encryption', 'wf_csv_import_export'); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <input type="button" id="rev_test_ftp_connection" class="button button-primary" value="<?php _e('Test FTP', 'wf_csv_import_export'); ?>" />
                                            <span class ="spinner " ></span>
                                        </th>
                                        <td id="prod_rev_ftp_test_notice"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="">
                    <h3 class="title aw-title"><?php _e('Available Options', 'wf_csv_import_export'); ?></h3>
                    <table class="form-table">
                        <tbody>
                            <?php
                            $mapping_from_db = get_option('wf_prod_review_csv_imp_exp_mapping');
                            if (!empty($mapping_from_db)) {
                                ?>
                                <tr>
                                    <th>
                                        <label for="profile"><?php _e('Select a mapping file.'); ?></label>
                                    </th>
                                    <td>
                                        <select name="profile">
                                            <option value="">--Select--</option>
                                            <?php foreach ($mapping_from_db as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $key; ?></option>

                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <th><label><?php _e('Update reviews if exists', 'wf_csv_import_export'); ?></label><br/></th>
                                <td>
                                    <input type="checkbox" name="merge" id="merge">
                                    <span class="description"><?php _e('Existing products peview are identified by their IDs. If this option is not selected and if a review with same ID is found in the CSV, that review will not be imported.', 'wf_csv_import_export'); ?></span>
                                </td>

                            </tr>
                            <tr>
                                <th><label><?php _e('Delimiter', 'wf_csv_import_export'); ?></label><br/></th>
                                <td>
                                    <input type="text" name="delimiter" placeholder="," size="2" />
                                    <span class="description"><?php _e('<br />Select a delimiter to separate the field values with. ‘,’ is the default delimiter', 'wf_csv_import_export'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php _e('Use SKU', 'wf_csv_import_export'); ?></label><br/></th>
                                <td>
                                    <input type="checkbox" name="use_sku" />
                                    <span class="description"><?php _e('Check to link products and reviews using SKU instead of product ID', 'wf_csv_import_export'); ?></span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <p class="submit">
                    <input type="submit" class="button button-primary" value="<?php esc_attr_e('Upload file and import'); ?>" />
                </p>
            </form>
        <?php endif; ?>
    </div>
</div>