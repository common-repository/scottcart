<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo "<style>.entry-title { display: none; }</style>";
echo "<style>";
echo scottcart_get_option('custom_css');
echo "</style>";
echo "<h3 class='scottcart_title'>"; echo scottcart_get_option('text_order_cancelled'); echo "</h3>";

echo scottcart_get_option('order_cancelled_page_text');

echo '<p></p><br />';

if (isset($_GET['order_id'])) {
	
	// get value and make sure it is a number
	$order_id = intval($_GET['order_id']);

	// only show confirmation if order id matches ip address
	$ip = sanitize_text_field(get_post_meta($order_id,'scottcart_ip',true));
	
	if (scottcart_get_the_user_ip() == $ip) {
		
		// update post status
		$my_post = array(
			'ID'           	=> $order_id,
			'post_status'   => 'cancelled',
		);
		wp_update_post($my_post);
		
		$nonce = wp_create_nonce( 'view_details_'.$order_id);
		
		echo '<p></p>';
		
		scottcart_get_account_purchase_details_callback($order_id,$nonce);
		
	} else {
		if (!is_user_logged_in()) {
			echo __('Please login to view your order details.','scottcart');
		}
		
	}

} else {
	if (!is_user_logged_in()) {
		echo __('Please login to view your order details.','scottcart');
	}
}