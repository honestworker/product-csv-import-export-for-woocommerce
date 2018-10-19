<?php
if (!defined('WPINC')) {
    exit;
}

class WF_ProdReviewImpExpCsv_Admin_Screen {

    /**
     * Constructor
     */
    public function __construct() {
       
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_print_styles', array($this, 'admin_scripts'));
        add_action('admin_notices', array($this, 'admin_notices'));

        add_action('bulk_actions-edit-comments', array($this, 'add_product_reviews_bulk_actions'));
        add_action('admin_action_download_to_pr_review_csv_hf', array($this, 'process_product_reviews_bulk_actions'));

        add_filter('manage_edit-comments_columns', array($this, 'custom_comment_columns'));
        add_filter('manage_comments_custom_column', array($this, 'custom_comment_column_data'), 10, 2);

        if (is_admin()) {
            add_action('wp_ajax_comment_export_to_csv_single', array($this, 'process_ajax_export_single_comment'));
        }
    }

    public function custom_comment_columns($columns) {
        $columns['comment_export_to_csv'] = __('Export');
        return $columns;
    }

    public function custom_comment_column_data($column, $comment_ID) {
        if ('comment_export_to_csv' == $column) {
            $action_general = 'download_comment_csv';
            $url_general = wp_nonce_url(admin_url('admin-ajax.php?action=comment_export_to_csv_single&comment_ID=' . $comment_ID), 'wf_csv_import_export');
            $name_general = __('Download to CSV', 'wf_csv_import_export');
            printf('<a class="button tips %s" href="%s" data-tip="%s">%s</a>', $action_general, esc_url($url_general), $name_general, $name_general);
        }
    }

    public function process_ajax_export_single_comment() {
        if (!is_admin()) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wf_csv_import_export'));
        }

        $comment_ID = !empty($_GET['comment_ID']) ? absint($_GET['comment_ID']) : '';
        if (!$comment_ID) {
            die;
        }
        $comment_IDs = array(0 => $comment_ID);
        include_once( 'exporter/class-wf-pr_revimpexpcsv-exporter.php' );
        WF_PrRevImpExpCsv_Exporter::do_export($comment_IDs);
    }

    /**
     * Notices in admin
     */
    public function admin_notices() {
        if (!function_exists('mb_detect_encoding')) {
            echo '<div class="error"><p>' . __('Product CSV Import Export requires the function <code>mb_detect_encoding</code> to import and export CSV files. Please ask your hosting provider to enable this function.', 'wf_csv_import_export') . '</p></div>';
        }
    }

    /**
     * Admin Menu
     */
    public function admin_menu() {
        $page = add_submenu_page('woocommerce', __('Product Reviews Im-Ex', 'wf_csv_import_export'), __('Product Reviews Im-Ex', 'wf_csv_import_export'), apply_filters('product_reviews_csv_product_role', 'manage_woocommerce'), 'wf_pr_rev_csv_im_ex', array($this, 'output'));
    }

    /**
     * Get WC Plugin path without fail on any version
     */
    public static function hf_get_wc_path() {
        if (function_exists('WC')) {
            $wc_path = WC()->plugin_url();
        } else {
            $wc_path = plugins_url() . '/woocommerce';
        }
        return $wc_path;
    }

    /**
     * Admin Scripts
     */
    public function admin_scripts() {

        $wc_path = self::hf_get_wc_path();
        wp_enqueue_script('wc-enhanced-select');
        
        $screen = get_current_screen();
        $allowed_creen_id = array('woocommerce_page_wf_woocommerce_csv_im_ex', 'edit-product', 'edit-shop_order',
                'admin',
                'edit-shop_subscription', 'edit-shop_coupon', 'users_page_hf_wordpress_customer_im_ex',
                'woocommerce_page_wf_woocommerce_order_im_ex');

        if (in_array($screen->id, $allowed_creen_id)) {
            
            wp_enqueue_style('woocommerce_admin_styles', $wc_path . '/assets/css/admin.css');
            wp_enqueue_style('woocommerce-product-csv-importer1', plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/styles/wf-style.css', basename(__FILE__)), '', '1.0.0', 'screen');
            wp_enqueue_style('woocommerce-product-csv-importer3', plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/styles/jquery-ui.css', basename(__FILE__)), '', '1.0.0', 'screen');

            wp_enqueue_script('woocommerce-product-csv-importer2', plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/js/product-rev-csv-import-export-for-woocommerce.min.js', basename(__FILE__)), '', '1.0.0', 'screen');
            wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_import_params', array('calendar_icon' => plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/images/calendar.png', basename(__FILE__))));
            wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_cron_params', array('rev_enable_ftp_ie' => '', 'rev_auto_export' => 'Disabled', 'rev_auto_import' => 'Disabled'));
        }


        //wp_enqueue_script('woocommerce-product-csv-importer2', plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/js/product-rev-csv-import-export-for-woocommerce.min.js', basename(__FILE__)), '', '1.0.0', 'screen');
        wp_enqueue_script('jquery-ui-datepicker');
        //wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_import_params', array('calendar_icon' => plugins_url(basename(plugin_dir_path(WF_PrRevImpExpCsv_FILE)) . '/images/calendar.png', basename(__FILE__))));
        //wp_localize_script('woocommerce-product-csv-importer', 'woocommerce_review_csv_cron_params', array('rev_enable_ftp_ie' => '', 'rev_auto_export' => 'Disabled', 'rev_auto_import' => 'Disabled'));

        wp_enqueue_script('jquery-ui-datepicker');
    }

    /**
     * Admin Screen output
     */
    public function output() {
        
        $tab = 'import';

        if (!empty($_GET['page'])) {
            if ($_GET['page'] == 'wf_pr_rev_csv_im_ex') {
                $tab = 'review';
            }
        }
        if (!empty($_GET['tab'])) {
            if ($_GET['tab'] == 'export') {
                $tab = 'export';
            } else if ($_GET['tab'] == 'review') {
                $tab = 'review';
            } else if ($_GET['tab'] == 'settings') {
                $tab = 'settings';
            } else if  ($_GET['tab'] == 'advanced') {
                $tab = 'advanced';
            }
        }

        include( 'views/html-wf-admin-screen.php' );
    }

    /**
     * Product review list page bulk export action add to action list
     * 
     */
    public function add_product_reviews_bulk_actions($action) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var $downloadToCSV = $('<option>').val('download_to_pr_review_csv_hf').text('<?php _e('Download as CSV', 'wf_csv_import_export') ?>');
                $('select[name^="action"]').append($downloadToCSV);
            });
        </script>
        <?php
        return $action;
    }

    /**
     * Product page bulk export action
     * 
     */
    public function process_product_reviews_bulk_actions() {
        //wp_die( '<pre>' . print_r( $_REQUEST ) . '</pre>' ); 
        $action = $_REQUEST['action'];
        if (!in_array($action, array('download_to_pr_review_csv_hf')))
            return;

        if (isset($_REQUEST['delete_comments'])) {
            $pr_rev_ids = array_map('absint', $_REQUEST['delete_comments']);
        }
        if (empty($pr_rev_ids)) {
            return;
        }
        // give an unlimited timeout if possible
        @set_time_limit(0);

        if ($action == 'download_to_pr_review_csv_hf') {
            include_once( 'exporter/class-wf-pr_revimpexpcsv-exporter.php' );
            WF_PrRevImpExpCsv_Exporter::do_export($pr_rev_ids);
        }
    }

    /**
     * Admin page for importing
     */
    public function admin_import_page() {
        include( 'views/html-wf-getting-started.php' );
        include( 'views/import/html-wf-import-product-reviews.php' );
        $post_columns = include( 'exporter/data/data-wf-post-columns.php' );
        include( 'views/export/html-wf-export-product-reviews.php' );
    }

    public function admin_review_page() {
        //include( 'views/html-wf-getting-started-review.php' );
        //include( 'views/import/html-wf-import-product-reviews.php' );
        $post_columns = include( 'exporter/data/data-wf-post-columns-review.php' );
        include( 'views/export/html-wf-export-product-reviews.php' );
    }

    /**
     * Admin Page for exporting
     */
    public function admin_export_page() {
        $post_columns = include( 'exporter/data/data-wf-post-columns.php' );
        include( 'views/export/html-wf-export-product-reviews.php' );
    }

    /**
     * Admin Page for settings
     */
    public function admin_settings_page() {
        include( 'views/settings/html-wf-settings-products.php' );
    }

    /**
     * Admin Page for advanced
     */
    public function admin_advanced_page() {
        include( 'views/advanced/html-wf-advanced-import-product.php' );
    }
}

new WF_ProdReviewImpExpCsv_Admin_Screen();
