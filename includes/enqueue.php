<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// admin enqueue
function scottcart_admin_enqueue() {

	// media uploader and thickbox
	wp_enqueue_media();
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	
	// admin css
	wp_register_style('scottcart-admin-css',plugins_url('../assets/css/admin.css',__FILE__),false,SCOTTCART_VERSION);
	wp_enqueue_style('scottcart-admin-css');
	
	// admin js
	wp_enqueue_script('scottcart-admin',plugins_url('../assets/js/admin.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// order page js
	wp_enqueue_script('scottcart-admin-order',plugins_url('../assets/js/order.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// product page js
	wp_enqueue_script('scottcart-admin-product',plugins_url('../assets/js/product.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	wp_localize_script('scottcart-admin-product', 'ajax_object',
		apply_filters('scottcart_admin_product_js', array(
	)));
	
	// settings page js
	wp_enqueue_script('scottcart-admin-settings',plugins_url('../assets/js/settings.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// reports page js
	wp_enqueue_script('scottcart-admin-reports',plugins_url('../assets/js/reports.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// admin tabs js - used on the settings page
	wp_enqueue_script('scottcart-admin-tabs',plugins_url('../assets/js/admin_tabs.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// jquery flot - used on the reports page
	wp_enqueue_script('scottcart-flot',plugins_url('../assets/js/jquery.flot.min.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	wp_enqueue_script('scottcart-flot-resize',plugins_url('../assets/js/jquery.flot.resize.min.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	wp_enqueue_script('scottcart-jqplot',plugins_url('../assets/js/jqPlot/jquery.jqplot.min.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// jquery date picker
	wp_enqueue_script('jquery-ui-datepicker');
	
	// jquery ui css - datepicker only - used for the styling of the datepicker
	wp_register_style('scottcart-jquery-ui-datepicker-css',plugins_url('../assets/css/jquery-ui-datepicker-only-custom-min.css',__FILE__),false,SCOTTCART_VERSION);
	wp_enqueue_style('scottcart-jquery-ui-datepicker-css');
	
	// jquery ui tooltips
	wp_enqueue_script('jquery-ui-tooltip');
	
	// color picker
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	
	// deactivate survey
	add_action('admin_footer', 'scottcart_deactivate_survey');
}
add_action('admin_enqueue_scripts','scottcart_admin_enqueue');




// public enqueue
function scottcart_public_enqueue() {
	
	// cart js
	wp_enqueue_script('scottcart-cart',plugins_url('../assets/js/cart.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	wp_localize_script('scottcart-cart', 'ajax_object',
		apply_filters('scottcart_cart_cart_js', array(
		'ajax_url' 		=> admin_url('admin-ajax.php'),
		'ajax_post' 	=> admin_url('admin-post.php'),
		'loading_icon' 	=> SCOTTCART_SITE_URL.'/wp-admin/images/loading.gif',
		'loading_text' 	=> __('Loading','scottcart'),
		'submit_text' 	=> scottcart_get_option('text_3'),
		'cart_url' 		=> get_permalink(scottcart_get_option('cart_page')),
		'return_url' 	=> get_permalink(scottcart_get_option('confirmation_page')),
	)));
	
	// purchase confirmation page js
	wp_enqueue_script( 'scottcart-purchase_confirmation',plugins_url('../assets/js/purchase_confirmation.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// public css
	if (scottcart_get_option('default_css') == "0") {
		wp_register_style('scottcart-public',plugins_url('../assets/css/public.css',__FILE__),false,SCOTTCART_VERSION);
		wp_enqueue_style('scottcart-public');
	}
	
	// single page js
	wp_enqueue_script('scottcart-single-cart',plugins_url('../assets/js/single_product.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// single page zoom js - uses jquery zoom
	if (scottcart_get_option('zoom') == "0") {
		wp_enqueue_script( 'scottcart-zoom',plugins_url('../assets/js/jquery.zoom.min.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	}
	
	// account page tabs js
	wp_enqueue_script('scottcart-tabs',plugins_url('../assets/js/tabs.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// account page js
	wp_enqueue_script('scottcart-account',plugins_url('../assets/js/account.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// cart page - js form validation
	wp_enqueue_script('scottcart-jquery-validate',plugins_url('../assets/js/jquery.validate.js',__FILE__),array('jquery'),SCOTTCART_VERSION);
	
	// dashicons
	wp_enqueue_style('scottcart-dashicons',get_stylesheet_uri(),'dashicons');
	wp_enqueue_style('dashicons');
	
}
add_action('wp_enqueue_scripts','scottcart_public_enqueue',10);