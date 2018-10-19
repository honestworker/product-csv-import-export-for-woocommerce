<div class="woocommerce">
    <div class="icon32" id="icon-woocommerce-importer"><br></div>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex') ?>" class="nav-tab <?php echo ($tab == 'import') ? 'nav-tab-active' : ''; ?>"><?php _e('Product', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_pr_rev_csv_im_ex&tab=review') ?>" class="nav-tab <?php echo ($tab == 'review') ? 'nav-tab-active' : ''; ?>"><?php _e('Product Reviews ', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=settings') ?>" class="nav-tab <?php echo ($tab == 'settings') ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'wf_csv_import_export'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=advanced') ?>" class="nav-tab <?php echo ($tab == 'advanced') ? 'nav-tab-active' : ''; ?>"><?php _e('Advanced', 'wf_csv_import_export'); ?></a>
        <?php
        $plugin_name = 'productimportexport';
        $status = get_option($plugin_name . '_activation_status'); ?>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=licence') ?>" class="nav-tab licence-tab <?php echo ($tab == 'licence') ? 'nav-tab-active' : ''; ?>"><?php _e('Licence', 'wf_csv_import_export') . ($status ? _e('<span class="actived">Activated</span>', 'wf_csv_import_export') : _e('<span class="deactived">Deactivated</span>', 'wf_csv_import_export')); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wf_woocommerce_csv_im_ex&tab=help') ?>" class="nav-tab <?php echo ($tab == 'help') ? 'nav-tab-active' : ''; ?>"><?php _e('Help Guide', 'wf_csv_import_export'); ?></a> 
    </h2>

    <?php
    switch ($tab) {
        case "export" :
            $this->admin_export_page();
            break;
        case "settings" :
            $this->admin_settings_page();
            break;
        case "review" :
            $this->admin_review_page();
            break;
        case "advanced" :
            $this->admin_advanced_page();
            break;
        case "licence" :
            $this->admin_licence_page($plugin_name);
            break;
        case "help" :
            $this->admin_help_page();
            break;
        default :
            $this->admin_import_page();
            break;
    }
    ?>
</div>