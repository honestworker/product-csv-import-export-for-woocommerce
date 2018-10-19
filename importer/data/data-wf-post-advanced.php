<?php

if (!defined('WPINC')) {
    exit;
}

return apply_filters('product_reviews_csv_product_post_columns', array(
    'comment_ID' => 'comment_ID',
    'comment_post_ID' => 'comment_post_ID',
    'comment_author' => 'comment_author',
    'comment_author_email' => 'comment_author_email',
    'comment_author_IP' => 'comment_author_IP',
    'comment_date' => 'comment_date',
    'comment_date_gmt' => 'comment_date_gmt',
    'comment_content' => 'comment_content',
    //'comment_karma'			=> 'comment_karma',
    'comment_approved' => 'comment_approved',
    'comment_parent' => 'comment_parent',
    'user_id' => 'user_id',
    //Meta
    'rating' => 'rating',
    'verified' => 'verified',
    'title' => 'title',
    //Product SKU associated with the comment
    'product_SKU' => 'product_SKU',	    //comment_post_ID must be exported to export Product SKU 
    'product_title' => 'product_title',	    //comment_post_ID must be exported to export Product Title
    'comment_alter_id' => 'comment_alter_id',
));
