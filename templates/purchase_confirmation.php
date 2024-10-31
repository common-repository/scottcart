<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo "<style>.entry-title { display: none; }</style>";
echo "<style>";
echo scottcart_get_option('custom_css');
echo "</style>";
echo "<h3 class='scottcart_title'>"; echo scottcart_get_option('text_purchase_confirmation'); echo "</h3>";

echo scottcart_get_option('purchase_Confirmation_page_text');

echo '<p></p><br />';

if (isset($_GET['order_id'])) {
	
	// get value and make sure it is a number
	$order_id = intval($_GET['order_id']);

	// only show confirmation if order id matches ip address
	$ip = get_post_meta($order_id,'scottcart_ip',true);
	
	if (scottcart_get_the_user_ip() == $ip) {
		
		// get the order status - this is used to update the page every 10 seconds, if the order status is not set to completed yet - this is because it can take the IPN a few seconds to come through sometimes
		$status = get_post_status($order_id);
		
		echo "<input type='hidden' id='scottcart_purcahse_confirmation_status' value='$status'>";
		
		if ($status == 'pend') {
			
			echo __('Your order is pending. This page will automatically refresh every 10 seconds.','scottcart');
			echo "&nbsp; <img src='"; echo SCOTTCART_SITE_URL.'/wp-includes/images/spinner.gif'; echo "'>";
			echo '<br />';
			
		} else {
			
			// make nonce, needed to show order details
			$nonce = wp_create_nonce( 'view_details_'.$order_id);
			
			// show order details
			scottcart_get_account_purchase_details_callback($order_id,$nonce);
			
		}
		
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