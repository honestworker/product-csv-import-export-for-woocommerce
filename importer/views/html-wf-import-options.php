<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex') ?>" class="nav-tab nav-tab-active"><?php _e('Product', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=review') ?>" class="nav-tab "><?php _e('Product Reviews ', 'wf_csv_import_export'); ?></a>
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
        <li><a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex') ?>" class=""><?php _e('Export', 'wf_csv_import_export'); ?></a> | </li>
        <li><a href="<?php echo admin_url('admin.php?import=woocommerce_csv') ?>" class="current"><?php _e('Import', 'wf_csv_import_export'); ?></a> </li>
    </ul>
    <br/>
<br/>
<div class=" wf-import-greeting tool-box bg-white p-20p">
    <form action="<?php echo admin_url('admin.php?import=' . $this->import_page . '&step=2&merge=' . $this->merge . '&skip_new=' . $this->skip_new); ?>" method="post">
        <?php wp_nonce_field('import-woocommerce'); ?>
        <input type="hidden" name="import_id" value="<?php echo $this->id; ?>" />
        <?php if ($this->file_url_import_enabled) : ?>
            <input type="hidden" name="import_url" value="<?php echo $this->file_url; ?>" />
        <?php endif; ?>
        <h3><?php _e('Map Fields', 'wf_csv_import_export'); ?></h3>
        <?php if ($this->profile == '') { ?>
            <?php _e('Mapping file name:', 'wf_csv_import_export'); ?> <input type="text" name="profile" value="" placeholder="Enter filename to save" />
        <?php } else { ?>
            <input type="hidden" name="profile" value="<?php echo $this->profile; ?>" />
        <?php } ?>
        <p><?php _e('Here you can map your imported columns to product data fields.', 'wf_csv_import_export'); ?></p>
        <table class="widefat widefat_importer">
            <thead>
                <tr>
                    <th><?php _e('Map to', 'wf_csv_import_export'); ?></th>
                    <th><?php _e('Column Header', 'wf_csv_import_export'); ?></th>
                    <th><?php _e('Evaluation Field', 'wf_csv_import_export'); ?>
                        <?php $plugin_url = WF_ProdImpExpCsv_Admin_Screen::hf_get_wc_path(); ?>
                        <img class="help_tip" style="float:none;" data-tip="<?php _e('Assign constant value HikeFoce to post_author:</br>=HikeFoce</br>Add 5% to Price:sale_price:</br>+5%</br>Reduce 5% from Price:sale_price:</br>-5%</br>Add $5 to Price:sale_price:</br>+5</br>Reduce $5 to Price:sale_price:</br>-5</br>Multiple 1.05 to Price:sale_price:</br>*1.05</br>Divide Price:sale_price by 2:</br>/2</br>Append a value By HikeFoce to post_title:</br>&By HikeFoce</br>Prepend a value HikeFoce to post_title:</br>&HikeFoce [VAL].', 'wf_csv_import_export'); ?>" src="<?php echo $plugin_url; ?>/assets/images/help.png" height="20" width="20" /> 
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $wpost_attributes = include( dirname(__FILE__) . '/../data/data-wf-reserved-fields-pair.php' );

                $taxonomy_n_attributes_items = array();
                foreach ($taxonomies as $taxonomy) {
                    if (substr($taxonomy, 0, 3) !== 'pa_')
                        $taxonomy_n_attributes_items['tax:' . $taxonomy] = 'tax:' . $taxonomy . '| Product Taxonomies';
                }
                foreach ($taxonomies as $taxonomy) {
                    if (substr($taxonomy, 0, 3) == 'pa_')
                        $taxonomy_n_attributes_items['attribute:' . $taxonomy] = 'attribute:' . $taxonomy . '| Taxonomy Attributes';
                }
                foreach ($attributes as $attr) {
                    $attr = sanitize_title($attr);
                    if (substr($attr, 0, 3) !== 'pa_')
                        $taxonomy_n_attributes_items['attribute:' . $attr] = 'attribute:' . $attr . '| Product Attributes';
                    $taxonomy_n_attributes_items['attribute_data:' . $attr] = 'attribute_data:' . $attr . '| Product Attributes Data';
                    $taxonomy_n_attributes_items['attribute_default:' . $attr] = 'attribute_default:' . $attr . '| Product Attributes default';
                }

                foreach ($raw_headers as $key => $column) {
                    if (!empty($taxonomy_n_attributes_items[$key]))
                        continue;
                    if (strstr($key, 'tax:')) {
                        $column = trim(str_replace('tax:', '', $key));
                        $taxonomy_n_attributes_items['tax:' . $column] = 'tax:' . $column . '| New Taxonomy:' . $column;
                    } elseif (strstr($key, 'meta:')) {
                        $column = trim(str_replace('meta:', '', $key));
                        $taxonomy_n_attributes_items['meta:' . $column] = 'meta:' . $column . '| Custom Field:' . $column;
                    } elseif (strstr($key, 'attribute:')) {
                        $column = trim(str_replace('attribute:', '', $key));
                        $taxonomy_n_attributes_items['attribute:' . $column] = 'attribute:' . $column . '| New Product Attribute:' . $column;
                    } elseif (strstr($key, 'attribute_data:')) {
                        $column = trim(str_replace('attribute_data:', '', $key));
                        $taxonomy_n_attributes_items['attribute_data:' . $column] = 'attribute_data:' . $column . '| New Product Attribute Data:' . $column;
                    } elseif (strstr($key, 'attribute_default:')) {
                        $column = trim(str_replace('attribute_default:', '', $key));
                        $taxonomy_n_attributes_items['attribute_default:' . $column] = 'attribute_default:' . $column . '| New Product Attribute default value:' . $column;
                    }
                }

                if (function_exists('woocommerce_gpf_install')) {
                    $gpf = array(
                        "gpf:availability" => "gpf:availability",
                        "gpf:condition" => "gpf:condition",
                        "gpf:brand" => "gpf:brand",
                        "gpf:product_type" => "gpf:product_type",
                        "gpf:google_product_category" => "gpf:google_product_category",
                        "gpf:gtin" => "gpf:gtin",
                        "gpf:mpn" => "gpf:mpn",
                        "gpf:gender" => "gpf:gender",
                        "gpf:age_group" => "gpf:age_group",
                        "gpf:color" => "gpf:color",
                        "gpf:size" => "gpf:size",
                        "gpf:adwords_grouping" => "gpf:adwords_grouping",
                        "gpf:adwords_labels" => "gpf:adwords_labels"
                    );
                    foreach ($gpf as $key => $value) {
                        $wpost_attributes[$key] = $value;
                    }
                }

                foreach ($taxonomy_n_attributes_items as $key => $value) {
                    if (!empty($wpost_attributes[$key]))
                        continue;
                    $wpost_attributes[$key] = $value;
                }

                foreach ($wpost_attributes as $key => $value) :
                    $sel_key = ($saved_mapping && isset($saved_mapping[$key])) ? $saved_mapping[$key] : $key;
                    $evaluation_value = ($saved_evaluation && isset($saved_evaluation[$key])) ? $saved_evaluation[$key] : '';
                    $evaluation_value = stripslashes($evaluation_value);
                    $values = explode('|', $value);
                    $value = $values[0];
                    $tool_tip = $values[1];
                    ?>
                    <tr>
                        <td width="25%">
                            <img class="help_tip" style="float:none;" data-tip="<?php echo $tool_tip; ?>" src="<?php echo $plugin_url; ?>/assets/images/help.png" height="20" width="20" /> 
                            <select name="map_to[<?php echo $key; ?>]" disabled="true" 
                                    style=" -webkit-appearance: none;
                                    -moz-appearance: none;
                                    text-indent: 1px;
                                    text-overflow: '';
                                    background-color: #f1f1f1;
                                    border: none;
                                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.07) inset;
                                    color: #32373c;
                                    outline: 0 none;
                                    transition: border-color 50ms ease-in-out 0s;">
                                <option value="<?php echo $key; ?>" <?php if ($key == $key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                            </select>                             
                        </td>
                        <td width="25%">
                            <select name="map_from[<?php echo $key; ?>]">
                                <option value=""><?php _e('Do not import', 'wf_csv_import_export'); ?></option>
                                <?php
                                foreach ($row as $hkey => $hdr):
                                    $hdr = strlen($hdr) > 50 ? substr(strip_tags($hdr), 0, 50) . "..." : $hdr;
                                    ?>
                                    <option value="<?php echo $raw_headers[$hkey]; ?>" <?php selected(strtolower($sel_key), $hkey); ?>><?php echo $raw_headers[$hkey] . " &nbsp;  : &nbsp; " . $hdr; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php do_action('woocommerce_csv_product_data_mapping', $key); ?>
                        </td>
                        <td width="10%"><input type="text" name="eval_field[<?php echo $key; ?>]" value="<?php echo $evaluation_value; ?>"  /></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit">
            <span style="color:gray;"><i><?php _e('Time taken to Import the products depends on the time taken to fetch the images and the internet speed. If you have more than 1000 products we recommend doing the import in batches by splitting the CSV file. Please do not navigate away or close the window while the import is in progress.', 'wf_csv_import_export'); ?></i></span>
            <br/><br/>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e('Submit', 'wf_csv_import_export'); ?>" />
            <input type="hidden" name="delimiter" value="<?php echo $this->delimiter ?>" />
            <input type="hidden" name="merge_empty_cells" value="<?php echo $this->merge_empty_cells ?>" />
            <input type="hidden" name="delete_products" value="<?php echo $this->delete_products ?>" />
            <input type="hidden" name="use_sku_upsell_crosssell" value="<?php echo $this->use_sku_upsell_crosssell ?>" />
            <input type="hidden" name="pro_stop_thumbnail_regen" value="<?php echo $this->pro_stop_thumbnail_regen ?>" />
            <input type="hidden" name="new_prod_status" value="<?php echo $this->new_prod_status ?>" />
            <input type="hidden" name="prod_use_chidren_sku" value="<?php echo $this->prod_use_chidren_sku ?>" />
            <input type="hidden" name="merge" value="<?php echo $this->merge ?>" />
            <input type="hidden" name="skip_new" value="<?php echo $this->skip_new ?>" />
        </p>
    </form>
</div>