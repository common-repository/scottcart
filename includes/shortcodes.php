<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// shop page
function scottcart_shop_shortcode($atts) {
    
	// attributes
	$atts = shortcode_atts(array(
        'category' 		   => '',
        'title' 		   => '',
        'more' 		   	   => '',
        'page' 		   	   => '',
        'width' 		   => '',
        'height' 		   => '',
        'limit' 		   => '',
        'category_menu'    => '',
        'pagination' 	   => '',
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'relation'		   => 'OR',
    ), $atts);
	
	include_once (SCOTTCART_PLUGIN_PATH . 'templates/shop.php');
	
	$output = scottcart_shop_page($atts);
	
	echo $output;
}
add_shortcode('scottcart_shop', 'scottcart_shop_shortcode');


// cart page
function scottcart_cart_shortcode() {
	return scottcart_cart();
}
add_shortcode('scottcart_cart', 'scottcart_cart_shortcode');


// purchase confirmation page
function scottcart_purchase_confirmation_shortcode() {
	include_once (SCOTTCART_PLUGIN_PATH . 'templates/purchase_confirmation.php');
}
add_shortcode('scottcart_purchase_confirmation', 'scottcart_purchase_confirmation_shortcode');

// order cancellation page
function scottcart_order_cancellation_shortcode() {
	include_once (SCOTTCART_PLUGIN_PATH . 'templates/order_cancelled.php');
}
add_shortcode('scottcart_order_cancelled', 'scottcart_order_cancellation_shortcode');


// account page
function scottcart_account_shortcode() {
	
	return scottcart_account();
	
}
add_shortcode('scottcart_account', 'scottcart_account_shortcode');


// login
function scottcart_login_shortcode() {
	$output = '';
	if (!is_user_logged_in()) {
		$url = wp_logout_url(get_permalink(scottcart_get_option('account_page')));
		$output .= "<a href='$url'>"; $output .= __('Login','scottcart'); $output .= "</a>";
	}
	return $output;
}
add_shortcode('scottcart_login', 'scottcart_login_shortcode');


// logout
function scottcart_logout_shortcode() {
	$output = '';
	
	if (is_user_logged_in()) {
		$url = wp_logout_url(get_permalink(scottcart_get_option('account_page')));
		$output .= "<a href='$url'>";$output .= __('Logout','scottcart'); $output .= "</a>";
	}
	
	return $output;
}
add_shortcode('scottcart_logout', 'scottcart_logout_shortcode');


// taxonomy category menu
function scottcart_category_menu() {
	$terms = get_terms( array(
		'taxonomy' => 'product_category',
		'hide_empty' => false,
	));
	
	$output = "";
	
	foreach ($terms as $term) {
		$output .= "<div class='scottcart_term_item'>";
		$output .= "<a href='";
		$output .= get_term_link($term->term_id);
		$output .= "'>";
		$output .= $term->name;
		$output .= "</a>";
		$output .= "</div>";
	}
	
	return $output;
}
add_shortcode('scottcart_category_menu', 'scottcart_category_menu');


// single product page - should be removed in a future version
//function scottcart_single_product_page( $single_template ) {
//	global $post;
//	
//    if ($post->post_type == 'scottcart_product') {
//        $single_template = SCOTTCART_PLUGIN_PATH . '/templates/single_product.php';
//    }
//    return $single_template;
//}
//add_filter('single_template','scottcart_single_product_page');


// single product page
function scottcart_before_product_content( $content ) {
	global $post;

	if ( $post && $post->post_type == 'scottcart_product' && is_singular('scottcart_product') && is_main_query() && !post_password_required() ) {
		ob_start();
		do_action('scottcart_before_product_content',$content);
		$content = ob_get_clean();
	}
	return $content;
}
add_filter('the_content', 'scottcart_before_product_content');





// archive page
function scottcart_archive_page( $single_template ) {
	global $post;
	
    if ($post->post_type == 'scottcart_product') {
        $single_template = SCOTTCART_PLUGIN_PATH . '/templates/archive_product.php';
    }
    return $single_template;
}
add_filter('archive_template','scottcart_archive_page');