<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// send all emails
function scottcart_send_emails($order_id) {

	scottcart_send_admin_email($order_id);
	scottcart_send_customer_email($order_id);
	
}

// send admin purchase email
function scottcart_send_admin_email($order_id) {
	
	if (scottcart_get_option('send_admin_email') == "0") {
		
		$admin_email = 	scottcart_get_option('admin_email');
		$site_from = 	scottcart_get_option('site_from');
		$site_email = 	scottcart_get_option('site_email');
		
		if (!empty(scottcart_get_option('admin_cc_email'))) {
			$admin_cc_email = scottcart_get_option('admin_cc_email');
			$admin_to = $admin_email.",".$admin_cc_email;
		} else {
			$admin_to = $admin_email;
		}
		
		if (!empty($admin_to)) {
			
			// get fields from order
			$first_name = get_post_meta($order_id,		'scottcart_first_name', 		true);
			$last_name = get_post_meta($order_id,		'scottcart_last_name', 			true);
			$txn_id = get_post_meta($order_id,			'scottcart_txn_id', 			true);
			$discount_code = get_post_meta($order_id,	'scottcart_discount_code',		true);
			$discount_amount = get_post_meta($order_id,	'scottcart_discount_amount',	true);
			$shipping = get_post_meta($order_id,		'scottcart_shipping',			true);
			$tax = get_post_meta($order_id,				'scottcart_tax',				true);
			
			$shipping_details = get_post_meta($order_id,	'scottcart_shipping_name',		true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_line_1',	true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_line_2',	true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_country',	true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_state',		true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_city',		true);
			$shipping_details .= "<br />";
			$shipping_details .= get_post_meta($order_id,	'scottcart_shipping_zip',		true);
			$shipping_details .= "<br />";
			
			$billing_details = get_post_meta($order_id,		'scottcart_billing_name',		true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_line_1',		true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_line_2',		true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_country',	true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_state',		true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_city',		true);
			$billing_details .= "<br />";
			$billing_details .= get_post_meta($order_id,	'scottcart_billing_zip',		true);
			$billing_details .= "<br />";
			
			$post = get_post($order_id);
			$payer_email = $post->post_title;
			
			$order_status = ucfirst($post->post_status);
			if ($order_status == "Pend") { $order_status = "Pending"; }		
			
			$payment_amount = $post->post_content;
			
			$admin_subject = scottcart_get_option('admin_subject');
			$message = scottcart_get_option('admin_email_template');
			$message = stripslashes($message);
			
			$payment_amount = sanitize_meta('currency_scottcart',$payment_amount,'post');
			
			$headers[] = 'From: '. $site_from .' <'. $site_email .'>' . "\r\n";
			$headers[] = "Content-type: text/html";
			
			$sold_table = scottcart_sold_table($order_id);
			
			// replace body
			$message = str_replace("{customer_email}",			$payer_email,		$message);
			$message = str_replace("{sold_table}",				$sold_table,		$message);
			$message = str_replace("{txn_total}",				$payment_amount,	$message);
			$message = str_replace("{txn_id}",					$txn_id,			$message);
			$message = str_replace("{txn_shipping}",			$shipping,			$message);
			$message = str_replace("{txn_tax}",					$tax,				$message);
			$message = str_replace("{order_num}",				$order_id,			$message);
			$message = str_replace("{order_status}",			$order_status,		$message);
			$message = str_replace("{discount_code_used}",		$discount_code,		$message);
			$message = str_replace("{discount_code_amount}",	$discount_amount,	$message);
			$message = str_replace("{customer_first_name}",		$first_name,		$message);
			$message = str_replace("{customer_last_name}",		$last_name,			$message);
			$message = str_replace("{shipping_details}",		$shipping_details,	$message);
			$message = str_replace("{billing_details}",			$billing_details,	$message);
			
			// replace subject
			$admin_subject = str_replace("{customer_email}",		$payer_email,		$admin_subject);
			$admin_subject = str_replace("{txn_total}",				$payment_amount,	$admin_subject);
			$admin_subject = str_replace("{txn_shipping}",			$shipping,			$admin_subject);
			$admin_subject = str_replace("{txn_tax}",				$tax,				$admin_subject);
			$admin_subject = str_replace("{txn_id}",				$txn_id,			$admin_subject);
			$admin_subject = str_replace("{order_num}",				$order_id,			$admin_subject);
			$admin_subject = str_replace("{order_status}",			$order_status,		$admin_subject);
			$admin_subject = str_replace("{discount_code_used}",	$discount_code,		$admin_subject);
			$admin_subject = str_replace("{discount_code_amount}",	$discount_amount,	$admin_subject);
			$admin_subject = str_replace("{customer_first_name}",	$first_name,		$admin_subject);
			$admin_subject = str_replace("{customer_last_name}",	$last_name,			$admin_subject);
			
			$message = nl2br($message);
			
			// send email
			$mail_result = wp_mail($admin_to, $admin_subject, $message, $headers);
			
			return $mail_result;
			
		}
	}
}


// send customer purchase email
function scottcart_send_customer_email($order_id) {

	if (scottcart_get_option('send_customer_email') == "0") {
		
		$customer_subject = 	scottcart_get_option('customer_subject');
		$site_from = 			scottcart_get_option('site_from');
		$site_email = 			scottcart_get_option('site_email');
		
		if (empty($site_email)) { echo __('No admin from email set.','scottcart')."<br />"; }
		
		// get fields from order
		$first_name 		= get_post_meta($order_id,		'scottcart_first_name', 		true);
		$last_name 			= get_post_meta($order_id,		'scottcart_last_name', 			true);
		$txn_id 			= get_post_meta($order_id,		'scottcart_txn_id', 			true);
		$discount_code 		= get_post_meta($order_id,		'scottcart_discount_code',		true);
		$discount_amount 	= get_post_meta($order_id,		'scottcart_discount_amount',	true);
		$shipping 			= get_post_meta($order_id,		'scottcart_shipping',			true);
		$tax 				= get_post_meta($order_id,		'scottcart_tax',				true);
		
		
		// shipping address
		$scottcart_shipping_name 	= get_post_meta($order_id,	'scottcart_shipping_name',		true);
		$scottcart_shipping_line_1 	= get_post_meta($order_id,	'scottcart_shipping_line_1',	true);
		$scottcart_shipping_line_2 	= get_post_meta($order_id,	'scottcart_shipping_line_2',	true);
		$scottcart_shipping_country = get_post_meta($order_id,	'scottcart_shipping_country',	true);
		$scottcart_shipping_state 	= get_post_meta($order_id,	'scottcart_shipping_state',		true);
		$scottcart_shipping_city 	= get_post_meta($order_id,	'scottcart_shipping_city',		true);
		$scottcart_shipping_zip 	= get_post_meta($order_id,	'scottcart_shipping_zip',		true);
		
		$shipping_details = '';
		if (!empty($scottcart_shipping_name)) {
			$shipping_details .= $scottcart_shipping_name."<br />";
		}
		if (!empty($scottcart_shipping_line_1)) {
			$shipping_details .= $scottcart_shipping_line_1."<br />";
		}
		if (!empty($scottcart_shipping_line_2)) {
			$shipping_details .= $scottcart_shipping_line_2."<br />";
		}
		if (!empty($scottcart_shipping_country)) {
			$shipping_details .= $scottcart_shipping_country."<br />";
		}
		if (!empty($scottcart_shipping_state)) {
			$shipping_details .= $scottcart_shipping_state."<br />";
		}
		if (!empty($scottcart_shipping_city)) {
			$shipping_details .= $scottcart_shipping_city."<br />";
		}
		if (!empty($scottcart_shipping_zip)) {
			$shipping_details .= $scottcart_shipping_zip;
		}
		
		// billing address
		$scottcart_billing_name 	= get_post_meta($order_id,	'scottcart_billing_name',		true);
		$scottcart_billing_line_1 	= get_post_meta($order_id,	'scottcart_billing_line_1',		true);
		$scottcart_billing_line_2 	= get_post_meta($order_id,	'scottcart_billing_line_2',		true);
		$scottcart_billing_country 	= get_post_meta($order_id,	'scottcart_billing_country',	true);
		$scottcart_billing_state 	= get_post_meta($order_id,	'scottcart_billing_state',		true);
		$scottcart_billing_city 	= get_post_meta($order_id,	'scottcart_billing_city',		true);
		$scottcart_billing_zip 		= get_post_meta($order_id,	'scottcart_billing_zip',		true);
		
		$billing_details = '';
		if (!empty($scottcart_billing_name)) {
			$billing_details .= $scottcart_billing_name."<br />";
		}
		if (!empty($scottcart_billing_line_1)) {
			$billing_details .= $scottcart_billing_line_1."<br />";
		}
		if (!empty($scottcart_billing_line_2)) {
			$billing_details .= $scottcart_billing_line_2."<br />";
		}
		if (!empty($scottcart_billing_country)) {
			$billing_details .= $scottcart_billing_country."<br />";
		}
		if (!empty($scottcart_billing_state)) {
			$billing_details .= $scottcart_billing_state."<br />";
		}
		if (!empty($scottcart_billing_city)) {
			$billing_details .= $scottcart_billing_city."<br />";
		}
		if (!empty($scottcart_billing_zip)) {
			$billing_details .= $scottcart_billing_zip;
		}
		
		
		$post = get_post($order_id);
		$payer_email = $post->post_title;
		
		if (!empty($payer_email)) {
			
			$order_status = ucfirst($post->post_status);
			if ($order_status == "Pend") { $order_status = "Pending"; }
			
			$payment_amount = $post->post_content;
			
			$message = scottcart_get_option('customer_email_template');
			$message = stripslashes($message);
			
			$payment_amount = sanitize_meta('currency_scottcart',$payment_amount,'post');
			
			$headers[] = 'From: '. $site_from .' <'. $site_email .'>' . "\r\n";
			$headers[] = "Content-type: text/html";
			
			$sold_table = scottcart_sold_table($order_id);
			
			// replace body
			$message = str_replace("{customer_email}",			$payer_email,		$message);
			$message = str_replace("{sold_table}",				$sold_table,		$message);
			$message = str_replace("{txn_total}",				$payment_amount,	$message);
			$message = str_replace("{txn_shipping}",			$shipping,			$message);
			$message = str_replace("{txn_tax}",					$tax,				$message);
			$message = str_replace("{txn_id}",					$txn_id,			$message);
			$message = str_replace("{order_num}",				$order_id,			$message);
			$message = str_replace("{order_status}",			$order_status,		$message);
			$message = str_replace("{discount_code_used}",		$discount_code,		$message);
			$message = str_replace("{discount_code_amount}",	$discount_amount,	$message);
			$message = str_replace("{customer_first_name}",		$first_name,		$message);
			$message = str_replace("{customer_last_name}",		$last_name,			$message);
			$message = str_replace("{shipping_details}",		$shipping_details,	$message);
			$message = str_replace("{billing_details}",			$billing_details,	$message);
			
			// replace subject
			$customer_subject = str_replace("{customer_email}",			$payer_email,		$customer_subject);
			$customer_subject = str_replace("{txn_total}",				$payment_amount,	$customer_subject);
			$customer_subject = str_replace("{txn_shipping}",			$shipping,			$customer_subject);
			$customer_subject = str_replace("{txn_tax}",				$tax,				$customer_subject);
			$customer_subject = str_replace("{txn_id}",					$txn_id,			$customer_subject);
			$customer_subject = str_replace("{order_num}",				$order_id,			$customer_subject);
			$customer_subject = str_replace("{order_status}",			$order_status,		$customer_subject);
			$customer_subject = str_replace("{discount_code_used}",		$discount_code,		$customer_subject);
			$customer_subject = str_replace("{discount_code_amount}",	$discount_amount,	$customer_subject);
			$customer_subject = str_replace("{customer_first_name}",	$first_name,		$customer_subject);
			$customer_subject = str_replace("{customer_last_name}",		$last_name,			$customer_subject);
			
			$message = nl2br($message);
			
			// send email
			$mail_result = wp_mail($payer_email, $customer_subject, $message, $headers);
			return $mail_result;
			
		}
	}
}