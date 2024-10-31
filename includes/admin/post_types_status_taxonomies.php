<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly


// register post types
function scottcart_register_post_type() {
	
	// product
	$product_labels = array(
		'name'				=> __( 'Products', 'scottcart' ),
		'singular_name' 	=> __( 'Product', 'scottcart' ),
		'add_new_item' 		=> __( 'Add New Product', 'scottcart' ),
		'search_items' 		=> __( 'Search Products', 'scottcart' ),
		'edit_item' 		=> __( 'Edit Product', 'scottcart' ),
		'new_item' 			=> __( 'New Product', 'scottcart' ),
		'not_found' 		=> __( 'No Products found', 'scottcart' ),
		'all_items' 		=> __( 'Products', 'scottcart' )
	);
	
	$product_args = array(
		'labels' 				=> $product_labels,
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> false,
		'query_var'          	=> true,
		'map_meta_cap' 			=> true,
		'has_archive' 			=> false,
		'hierarchical'       	=> false,
		'rewrite' 				=> array('slug' => 'products','with_front' => false ),
	);
	
	register_post_type('scottcart_product',$product_args);
	
	// order
	$order_labels = array(
		'name' 				=> __( 'Orders', 'scottcart' ),
		'singular_name' 	=> __( 'Order', 'scottcart' ),
		'add_new_item' 		=> __( 'Add New Order', 'scottcart' ),
		'search_items' 		=> __( 'Search Orders', 'scottcart' ),
		'edit_item' 		=> __( 'Edit Order', 'scottcart' ),
		'new_item' 			=> __( 'New Order', 'scottcart' ),
		'not_found' 		=> __( 'No Orders found', 'scottcart' ),
		'all_items' 		=> __( 'Orders', 'scottcart' )
	);
	
	$order_args = array(
		'labels' 				=> $order_labels,
		'public' 				=> false,
		'show_ui' 				=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> 'scottcart_menu',
		'has_archive' 			=> true,
		'map_meta_cap' 			=> true,
	);
	register_post_type('scottcart_order',$order_args);
	
	// customer
	$customer_labels = array(
		'name' 				=> __( 'Customers', 'scottcart' ),
		'singular_name' 	=> __( 'Customer', 'scottcart' ),
		'add_new_item' 		=> __( 'Add New Customer', 'scottcart' ),
		'search_items' 		=> __( 'Search Customers', 'scottcart' ),
		'edit_item' 		=> __( 'Edit Customer', 'scottcart' ),
		'new_item' 			=> __( 'New Customer', 'scottcart' ),
		'not_found' 		=> __( 'No Customers found', 'scottcart' ),
		'all_items' 		=> __( 'Customers', 'scottcart' )
	);
	
	$customer_args = array(
		'labels' 				=> $customer_labels,
		'public' 				=> false,
		'show_ui' 				=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> false,
		'has_archive' 			=> true,
		'map_meta_cap' 			=> true,
	);
	register_post_type('scottcart_customer',$customer_args);
	
	// coupon
	$coupon_labels = array(
		'name' 				=> __( 'Discounts', 'scottcart' ),
		'singular_name' 	=> __( 'Discount', 'scottcart' ),
		'add_new_item' 		=> __( 'Add New Discount', 'scottcart' ),
		'search_items' 		=> __( 'Search Discounts', 'scottcart' ),
		'edit_item' 		=> __( 'Edit Discount', 'scottcart' ),
		'new_item' 			=> __( 'New Discount', 'scottcart' ),
		'not_found' 		=> __( 'No Discounts found', 'scottcart' ),
		'all_items' 		=> __( 'Discounts', 'scottcart' )
	);
	
	if (scottcart_get_option('coupon') == "0") {
		$menu = 'scottcart_menu';
	} else {
		$menu = 'false';
	}
	
	$coupon_args = array(
		'labels' 				=> $coupon_labels,
		'public' 				=> false,
		'show_ui' 				=> true,
		'exclude_from_search' 	=> true,
		'show_in_menu' 			=> false,
		'has_archive' 			=> true,
		'map_meta_cap' 			=> true,
	);
	register_post_type('scottcart_discount',$coupon_args);
	
}
add_action('init','scottcart_register_post_type', 1 );


// register taxonomies
function scottcart_register_taxonomies() {

	// register product category
	$category_labels = array(
		'name' 					=> _x( 'Product Categories', 'taxonomy general name' ,'scottcart'),
		'singular_name' 		=> _x( 'Product Category', 'taxonomy singular name' ,'scottcart'),
		'search_items' 			=> __( 'Search Product Categories' ,'scottcart'),
		'all_items' 			=> __( 'All Product Categories' ,'scottcart'),
		'parent_item' 			=> __( 'Parent Product Category' ,'scottcart'),
		'parent_item_colon' 	=> __( 'Parent Parent Product Category:' ,'scottcart'),
		'edit_item' 			=> __( 'Edit Parent Product Category','scottcart'),
		'update_item' 			=> __( 'Update Product Category','scottcart'),
		'add_new_item' 			=> __( 'Add New Product Category','scottcart'),
		'new_item_name' 		=> __( 'New Product Category Name','scottcart'),
		'menu_name' 			=> __( 'Categories','scottcart'),
	  );
		
	  $category_args = array(
		'hierarchical' 			=> true,
		'labels' 				=> $category_labels,
		'show_ui' 				=> true,
		'query_var'    			=> 'product_category',
		'rewrite'     			=> array('slug' => 'product/category', 'with_front' => false, 'hierarchical' => true ),
	  );
	  
	register_taxonomy('product_category', array('scottcart_product'), $category_args );
	register_taxonomy_for_object_type('product_category','scottcart_product');
	  
		
	// register product tags
	$tag_labels = array(
		'name' 					=> _x( 'Product Tags', 'taxonomy general name' ,'scottcart'),
		'singular_name' 		=> _x( 'Product Tag', 'taxonomy singular name' ,'scottcart'),
		'search_items' 			=> __( 'Search Product Tags' ,'scottcart'),
		'all_items' 			=> __( 'All Product Tags' ,'scottcart'),
		'parent_item'			=> __( 'Parent Product Tag' ,'scottcart'),
		'parent_item_colon' 	=> __( 'Parent Parent Product Tag:' ,'scottcart'),
		'edit_item' 			=> __( 'Edit Parent Product Tag','scottcart'),
		'update_item' 			=> __( 'Update Product Tag','scottcart'),
		'add_new_item' 			=> __( 'Add New Product Tag','scottcart'),
		'new_item_name' 		=> __( 'New Product Tag Name','scottcart'),
		'menu_name' 			=> __( 'Tags','scottcart'),
	  );
	  
	$tag_args = array(
		'hierarchical' 			=> false,
		'labels' 				=> $tag_labels,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'show_in_nav_menus' 	=> true,
		'show_admin_column' 	=> true,
		
		'query_var'    			=> 'product_tag',
		'rewrite' 				=> array('slug' => 'product/tag'),
	);
	
	register_taxonomy('product_tag', array('scottcart_product'), $tag_args );
	register_taxonomy_for_object_type('product_tag','scottcart_product');
	
	
	// register product features
	$feature_labels = array(
		'name' 					=> _x( 'Product Features', 'taxonomy general name' ,'scottcart'),
		'singular_name' 		=> _x( 'Product Feature', 'taxonomy singular name' ,'scottcart'),
		'search_items' 			=> __( 'Search Product Features' ,'scottcart'),
		'all_items' 			=> __( 'All Product Features' ,'scottcart'),
		'parent_item' 			=> __( 'Parent Product Feature' ,'scottcart'),
		'parent_item_colon' 	=> __( 'Parent Parent Product Feature:' ,'scottcart'),
		'edit_item' 			=> __( 'Edit Parent Product Feature','scottcart'),
		'update_item' 			=> __( 'Update Product Feature','scottcart'),
		'add_new_item' 			=> __( 'Add New Product Feature','scottcart'),
		'new_item_name' 		=> __( 'New Product Feature Name','scottcart'),
		'menu_name' 			=> __( 'Features','scottcart'),
	  );
	  
	$feature_args = array(
		'hierarchical' 			=> true,
		'labels' 				=> $feature_labels,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'show_in_nav_menus' 	=> true,
		'show_admin_column' 	=> true,
		'query_var'    			=> 'product_feature',
		'rewrite' 				=> array('slug' => 'product/feature'),
	);

	register_taxonomy('product_feature', array('scottcart_product'), $feature_args );
	register_taxonomy_for_object_type('product_feature','scottcart_product');
}
add_action('init','scottcart_register_taxonomies', 0 );




// register custom post status types
function scottcart_custom_post_status(){

	// Completed
	register_post_status( 'completed', array(
		'label'                     => __( 'Completed', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>' ),
	));
	
	// Pending - using pend because of a problem with wp update - if status is pending, then post_date can't be changed on new posts
	register_post_status( 'pend', array(
		'label'                     => __( 'Pending', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>' ),
	));
	
	// Processing
	register_post_status( 'processing', array(
		'label'                     => __( 'Processing', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Processing <span class="count">(%s)</span>', 'Processing <span class="count">(%s)</span>' ),
	));
	
	// Abandoned
	register_post_status( 'abandoned', array(
		'label'                     => __( 'abandoned', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Abandoned <span class="count">(%s)</span>', 'Abandoned <span class="count">(%s)</span>' ),
	));
	
	// cancelled
	register_post_status( 'cancelled', array(
		'label'                     => __( 'cancelled', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>' ),
	));
	
	// Refunded
	register_post_status( 'refunded', array(
		'label'                     => __( 'refunded', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>' ),
	));
	
	// Active
	register_post_status( 'active', array(
		'label'                     => __( 'active', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>' ),
	));
	
	// Inactive
	register_post_status( 'inactive', array(
		'label'                     => __( 'inactive', 'scottcart' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Inactive <span class="count">(%s)</span>', 'Inactive <span class="count">(%s)</span>' ),
	));
}
add_action( 'init', 'scottcart_custom_post_status' );


// hide post metaboxes in custom post types
function scottcart_hide_post_type_boxes() {
	// order
	remove_post_type_support( 'scottcart_order', 'title' );
	remove_post_type_support( 'scottcart_order', 'editor' );
	
	// discount
	remove_post_type_support( 'scottcart_discount', 'title' );
	remove_post_type_support( 'scottcart_discount', 'editor' );
	
	// customer
	remove_post_type_support( 'scottcart_customer', 'title' );
	remove_post_type_support( 'scottcart_customer', 'editor' );
}
add_action('init','scottcart_hide_post_type_boxes');


// show front end admin bar product edit link
function scottcart_admin_bar_edit_link() {
    global $wp_admin_bar;
    global $post;
	
    if (!is_super_admin() || !is_admin_bar_showing()) {
        return;
	}
    if (is_single()) {
		$wp_admin_bar->add_menu( array(
			'id'		=> 'edit_fixed',
			'parent'	=> false,
			'title'		=> __( 'Edit Product'),
			'href'		=> get_edit_post_link($post->id)
		));
	}
}
add_action( 'wp_before_admin_bar_render', 'scottcart_admin_bar_edit_link' );