<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// load paypal standard gateway
function scottcart_gateway_load_paypal_standard($scottcart_gateway_array) {

	$paypal_standard_array = array(
		'paypal_standard' => array(
			'title'  				=> __( 'PayPal Standard', 'scottcart' ),
			'slug'  				=> __( 'paypal_standard', 'scottcart' ),
			'function_public'  		=> 'scottcart_paypal_standard_public',
			'function_private'  	=> 'scottcart_paypal_standard_private',
			'function_js'  			=> '',
			'live_link'  			=> 'https://history.paypal.com/cgi-bin/webscr?cmd=_history-details-from-hub&id=',
			'sandbox_link'  		=> 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_history-details-from-hub&id=',
			'icon'  				=> WP_PLUGIN_URL."/".SCOTTCART_SLUG.'/assets/images/paypal.png',
			'help' 					=> '',
		),
	);

	return array_merge($scottcart_gateway_array,$paypal_standard_array);
}
add_filter( 'scottcart_gateway_array','scottcart_gateway_load_paypal_standard');


// public function - not used
function scottcart_paypal_standard_public() {
}


// add sub tab to payments tab
function scottcart_add_tab_paypal_standard( $array ) {

	$stripe_settings = 		array(
		'PayPal Standard' 	=> array(
			'title' 		=> __( 'Enable PayPal Standard', 'scottcart' ),
			'name' 			=> 'paypal_standard',
			'type' 			=> 'dropdown',
			'options' 		=> array (
				__('Yes (Default)','scottcart'),
				__('No','scottcart'),
			),
			'default'		=> '0',
			'help' 			=> __( '', 'scottcart' ),
		),
		array(
			'title' 		=> __( 'Test Mode', 'scottcart' ),
			'name' 			=> 'paypal_standard_mode',
			'type' 			=> 'dropdown',
			'options' 		=> array (
				__('On (Sandbox mode - Fake transactions)','scottcart'),
				__('Off (Live mode - Real transactions)','scottcart'),
			),
			'default'		=> '0',
			'help' 			=> '',
		),
		array(
			'title' 		=> __( 'Live Account', 'scottcart' ),
			'name' 			=> 'paypal_standard_live',
			'type' 			=> 'input',
			'default'		=> '',
			'help'			=> 'Enter your PayPal Live Merchant ID.',
		),
		array(
			'title' 		=> __( 'Sandbox Account', 'scottcart' ),
			'name' 			=> 'paypal_standard_sandbox',
			'type' 			=> 'input',
			'default'		=> '',
			'help'			=> 'Enter your PayPal Sandbox Merchant ID.',
		),
		array(
			'title' 		=> __( 'Title', 'scottcart' ),
			'name' 			=> 'paypal_standard_title',
			'type' 			=> 'input',
			'default'		=> __('PayPal Standard','scottcart'),
			'help'			=> __('What title should be used on the checkout page?','scottcart'),
		),
		array(
			'title' 		=> __( 'Description', 'scottcart' ),
			'name' 			=> 'paypal_standard_desc',
			'type' 			=> 'input',
			'default'		=> __('Secure payment with your Credit Card or PayPal Account.','scottcart'),
			'help'			=> '',
		),
	);

	return array_merge( $array,array($stripe_settings));
}
add_filter( 'scottcart_settings_payments_tab', 'scottcart_add_tab_paypal_standard' );






















function scottcart_paypal_standard_private($order_id) {
	
	$totals = scottcart_cart_total(1);
	
	// live or test mode
	if (scottcart_get_option('paypal_standard_mode') == "0") {
		$account = scottcart_get_option('paypal_standard_sandbox');
		$path = "sandbox.paypal";
	} else {
		$account = scottcart_get_option('paypal_standard_live');
		$path = "paypal";
	}
	
	// notify url - PPS_IPN stands for PayPal Standard Instant Payment Notification
	$notify_url = add_query_arg('scottcart_action','PPS_IPN',home_url( 'index.php'));
	
	// return url
	$return_url = add_query_arg(
		array(
			'order_id'		=> $order_id
		),
		get_permalink(scottcart_get_option('confirmation_page'))
	);
	
	// cancel url
	$cancel_url = add_query_arg(
		array(
			'order_id'		=> $order_id
		),
		get_permalink(scottcart_get_option('cancellation_page'))
	);
	
	$array = array(
		'business'			=> $account,
		'email'				=> get_the_title($order_id),
		'first_name'		=> get_post_meta($order_id,'scottcart_first_name',true),
		'last_name'			=> get_post_meta($order_id,'scottcart_last_name',true),
		'invoice'			=> $order_id,
		'no_shipping'		=> '1',
		'no_note'			=> '1',
		'currency_code'		=> scottcart_get_option('currency'),
		'charset'			=> get_bloginfo('charset'),
		'custom'			=> $order_id,
		'rm'				=> '1', 				// return method for reuturn url, use 1 for GET since we may need to fresh the page and we don't want a resend post notification
		'return'			=> $return_url,
		'cancel_return'		=> $cancel_url,
		'notify_url'		=> $notify_url,
		'cbt'				=> get_bloginfo('name'),
		'bn'				=> 'WPPlugin_SP',
		'cmd'				=> '_cart',
		'upload'			=> '1'
	);
	
	// cart items
	$i = '1';
	foreach ($_SESSION['scottcart_cart'] as $cart_item) {
		
		$result = '';
		
		if ($cart_item['type'] == "0") { $scottcart_type_name = "physical"; }
		if ($cart_item['type'] == "1") { $scottcart_type_name = "digital"; }
		if ($cart_item['type'] == "2") { $scottcart_type_name = "service"; }
		
		// price
		$price = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_price".$cart_item['price_id'], true);
		if (empty($price)) { $price = sanitize_meta( 'currency_scottcart','0','post'); }
		
		$name = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_name".$cart_item['price_id'], true);
		
		if ($cart_item['attribute_id'] >= "0") {
			$attribute_name = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_attribute_name".$cart_item['attribute_id'], true);
		}
		
		// name
		$result .= get_the_title($cart_item['id']);
		$result .= " - ";
		$result .= $name;
		
		if ($cart_item['attribute_id'] >= "0") {
			$result .= " - ";
			$result .= $attribute_name;
		}
		
		$name = $result;
		
		// quantity	
		$quantity = $cart_item['quantity'];
		
		// put items into array
		$array['item_name_'.$i] = stripslashes_deep(html_entity_decode(wp_strip_all_tags($name),ENT_COMPAT,'UTF-8'));
		$array['amount_'.$i] = $price;
		$array['quantity_'.$i] = $quantity;
		
		$i++;
	}
	
	
	// shipping
	if (scottcart_get_option('shipping') == 1) {
		$array['shipping_1'] = $totals['total_shipping'];
	}
	
	
	// tax
	if (scottcart_get_option('tax') == 1) {
		$array['tax_cart'] = $totals['total_tax'];
	}
	
	// discount
	$array['discount_amount_cart'] = $totals['total_discount'];
	
	// generate url with parameters
	$paypal_url = "https://www.$path.com/cgi-bin/webscr?";
	$paypal_url .= http_build_query($array);
	$paypal_url = htmlentities($paypal_url); // fix for &curren was displayed literally
	$paypal_url = str_replace('&amp;','&',$paypal_url);
	
	
	// empty cart
	$_SESSION['scottcart_cart_pending'] 	= null;
	$_SESSION['scottcart_cart'] 			= null;
	
	// redirect
	wp_redirect($paypal_url);
	exit;
	
}