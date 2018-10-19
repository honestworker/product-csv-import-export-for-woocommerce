<?php
if ( ! defined( 'WPINC' ) ) {
	exit;
}

// New postmeta defaults
return apply_filters( 'woocommerce_wf_csv_product_advanced_mapping_defaults', array(
	'description'				  => 'post_content',
	'subCategory'				  => 'subCategory',
	'name'						  => 'post_title',
	'qty'						  => 'stock',
	'attributes'				  => 'attributes',
	'barcod'					  => 'import_as_attr',
	'image'						  => '',
	'manufacturer_id'			  => 'import_as_attr',
	'VendorID'					  => 'import_as_attr',
	'category'					  => 'mainCategory',
	'price'					  	  => 'price',
	'STAT'					  	  => 'STAT',
	'id'					  	  => 'sku',
	'vendorItemNo'				  => 'import_as_attr',
) );