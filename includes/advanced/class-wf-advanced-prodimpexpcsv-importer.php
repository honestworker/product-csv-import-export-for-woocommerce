<?php

if (!defined('WPINC')) {
    exit;
}

class WF_Advanced_ProdImpExpCsv_Importer {

    /**
     * Product Exporter Tool
     */
    public static function get_categories_html($headers, $categories, $mappings) {
        $result = array(
            'product_cats' => array(),
            'import_cats' => $categories,
            'cats_html' => ''
        );
        
        $taxonomy     = 'product_cat';
        $orderby      = 'name';  
        $show_count   = 0;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;
        
        $plugin_url = WF_ProdImpExpCsv_Admin_Screen::hf_get_wc_path();
        
        $result['cats_html'] = "<table class=\"widefat widefat_importer\">"
                            . "<thead>"
                            . "</thead>"
                            . "<tbody>";
                            
        $main_args = array(
               'taxonomy'     => $taxonomy,
               'orderby'      => $orderby,
               'show_count'   => $show_count,
               'pad_counts'   => $pad_counts,
               'hierarchical' => $hierarchical,
               'title_li'     => $title,
               'hide_empty'   => $empty
        );
        $all_categories = get_categories( $main_args );
        
        foreach ($all_categories as $cat) {
            if ($cat->category_parent == 0) {
                $category_id = $cat->term_id;
                $sub_args = array(
                    'taxonomy'     => $taxonomy,
                    'child_of'     => 0,
                    'parent'       => $category_id,
                    'orderby'      => $orderby,
                    'show_count'   => $show_count,
                    'pad_counts'   => $pad_counts,
                    'hierarchical' => $hierarchical,
                    'title_li'     => $title,
                    'hide_empty'   => $empty
                );
                $sub_cats = get_categories( $sub_args );
                
                $sub_cats_result = null;
                if($sub_cats) {
                    foreach($sub_cats as $sub_category) {
                        $sub_cats_result[] = array('term_id' => $sub_category->term_id, 'name' => $sub_category->name, 'sub_cats' => null);
                    }   
                }
                $result['product_cats'][] = array('term_id' => $cat->term_id, 'name' => $cat->name, 'sub_cats' => $sub_cats_result);
            }
        }
        
        foreach ($result['import_cats'] as $cat_key => $cat_value) {
            $main_category = $sub_category = "";
            if ($mappings[$cat_key]['_default_']) {
                $category = explode(' > ', $mappings[$cat_key]['_default_']);
                if (isset($category[1])) {
                    $main_category = $category[0];
                    $sub_category = $category[1];
                } else {
                    $main_category = $mappings[$cat_key]['_default_'];
                }
            }

            $result['cats_html'] .= "<tr>"
                                . "<td width=\"25%\">" . $cat_key . "</td>"
                                . "<td width=\"25%\"></td>"
                                . "<td width=\"25%\">"
                                . "<select name=\"prod_map_main_category\" class=\"prod_map_main_category\">"
                                . "<option value=\"\" data-id=\"0\">Unspecified</option>";

            foreach ($result['product_cats'] as $product_cat) {
                if ($main_category == str_replace('&amp;', '&', $product_cat['name'])) {
                    $result['cats_html'] .= "<option value=\"" . $product_cat['name'] ."\" data-id=\"" . $product_cat['term_id'] . "\" selected>" . $product_cat['name'] . "</option>";
                } else {
                    $result['cats_html'] .= "<option value=\"" . $product_cat['name'] ."\" data-id=\"" . $product_cat['term_id'] . "\">" . $product_cat['name'] . "</option>";
                }
            }
            $result['cats_html'] .=  "</select></td>"
                                . "<td width=\"25%\">"
                                . "<select name=\"prod_map_sub_category\" class=\"prod_map_sub_category\">"
                                . "<option value=\"\" data-id=\"0\">Unspecified</option>";
            if ($main_category) {
                foreach ($result['product_cats'] as $product_cat) {
                    if ($main_category == str_replace('&amp;', '&', $product_cat['name'])) {
                        if ($product_cat['sub_cats']) {
                            foreach ($product_cat['sub_cats'] as $sub_cat) {
                                if ($sub_cat['name'] == str_replace('&amp;', '&',  $sub_category)) {
                                    $result['cats_html'] .= "<option value=\"" . $sub_cat['name'] ."\" data-id=\"" . $sub_cat['term_id'] . "\" selected>" . $sub_cat['name'] . "</option>";
                                } else {
                                    $result['cats_html'] .= "<option value=\"" . $sub_cat['name'] ."\" data-id=\"" . $sub_cat['term_id'] . "\">" . $sub_cat['name'] . "</option>";
                                }
                            }
                        }
                    }
                }
            }
            $result['cats_html'] .= "</select></td>";
            $result['cats_html'] .= "</tr>";
            
            foreach ($cat_value as $cat) {
                $main_category = $sub_category = "";
                if ($mappings[$cat_key][$cat]) {
                    $category = explode(' > ', $mappings[$cat_key][$cat]);
                    if (isset($category[1])) {
                        $main_category = $category[0];
                        $sub_category = $category[1];
                    } else {
                        $main_category = $mappings[$cat_key][$cat];
                    }
                }
                $result['cats_html'] .= "<tr>"
                                    . "<td width=\"25%\"></td>"
                                    . "<td width=\"25%\">" . $cat . "</td>"
                                    . "<td width=\"25%\">"
                                    . "<select name=\"prod_map_main_category\" class=\"prod_map_main_category\">"
                                    . "<option value=\"\" data-id=\"0\">Unspecified</option>";

                foreach ($result['product_cats'] as $product_cat) {
                    if ($main_category == str_replace('&amp;', '&', $product_cat['name'])) {
                        $result['cats_html'] .= "<option value=\"" . $product_cat['name'] ."\" data-id=\"" . $product_cat['term_id'] . "\" selected>" . $product_cat['name'] . "</option>";
                    } else {
                        $result['cats_html'] .= "<option value=\"" . $product_cat['name'] ."\" data-id=\"" . $product_cat['term_id'] . "\" >" . $product_cat['name'] . "</option>";
                    }
                }
                $result['cats_html'] .= "</select></td>";
                $result['cats_html'] .= "<td width=\"25%\">";
                $result['cats_html'] .= "<select name=\"prod_map_sub_category\" class=\"prod_map_sub_category\">"
                                        . "<option value=\"\" data-id=\"0\">Unspecified</option>";
                if ($main_category) {
                    foreach ($result['product_cats'] as $product_cat) {
                        if ($main_category == str_replace('&amp;', '&', $product_cat['name'])) {
                            if ($product_cat['sub_cats']) {
                                foreach ($product_cat['sub_cats'] as $sub_cat) {
                                    if ($sub_cat['name'] == str_replace('&amp;', '&', $sub_category)) {
                                        $result['cats_html'] .= "<option value=\"" . $sub_cat['name'] ."\" data-id=\"" . $sub_cat['term_id'] . "\" selected>" . $sub_cat['name'] . "</option>";
                                    } else {
                                        $result['cats_html'] .= "<option value=\"" . $sub_cat['name'] ."\" data-id=\"" . $sub_cat['term_id'] . "\">" . $sub_cat['name'] . "</option>";
                                    }
                                }
                            }
                        }
                    }
                }
                $result['cats_html'] .= "</select></td>";
                $result['cats_html'] .= "</tr>";
            }
        }
        $result['cats_html'] .= "</tbody>";
        $result['cats_html'] .= "</table>";

        $result['cats_html'] .= "<br><input type=\"button\" id=\"prod-advanced-import-file\" class=\"button button-primary\" value=\"Import File\"/>";

        return $result;
    }
}