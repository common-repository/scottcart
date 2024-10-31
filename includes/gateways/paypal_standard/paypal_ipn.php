<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// IPN post
function scottcart_listen_for_paypal_ipn($request) {

	if (isset($request) && $request['scottcart_action'] == 'PPS_IPN') {
		
		//scottcart_log("IPN - Listener request received.");
		
		// Check the request method is POST
		if (isset( $_SERVER['REQUEST_METHOD'] )&& $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			//scottcart_log("IPN - GET request detected.");
			return;
		}
		
		// Set initial post data to empty string
		$post_data = '';
		
		// Fallback just in case post_max_size is lower than needed
		if ( ini_get( 'allow_url_fopen' ) ) {
			$post_data = file_get_contents( 'php://input' );
		} else {
			// If allow_url_fopen is not enabled, then make sure that post_max_size is large enough
			ini_set( 'post_max_size', '12M' );
		}
		// Start the encoded data collection with notification command
		$encoded_data = 'cmd=_notify-validate';
		
		// Get current arg separator
		$arg_separator = scottcart_get_php_arg_separator();
		
		// Verify there is a post_data
		if ( $post_data || strlen( $post_data ) > 0 ) {
			// Append the data
			$encoded_data .= $arg_separator.$post_data;
		} else {
			// Check if POST is empty
			if ( empty( $_POST ) ) {
				// Nothing to do
				return;
			} else {
				// Loop through each POST
			    foreach ($_POST as $key => $value) {
			        // Sanitize the value
			        $sanitized_value = sanitize_text_field($value);
			        
			        // Encode the sanitized value and append the data
			        $encoded_data .= $arg_separator . $key . "=" . urlencode($sanitized_value);
			    }
			}
		}
		
		// Convert collected post data to an array
		parse_str( $encoded_data, $encoded_data_array );
		
		foreach ( $encoded_data_array as $key => $value ) {
		
			if ( false !== strpos( $key, 'amp;' ) ) {
				$new_key = str_replace( '&amp;', '&', $key );
				$new_key = str_replace( 'amp;', '&' , $new_key );
				
				unset( $encoded_data_array[ $key ] );
				$encoded_data_array[ $new_key ] = $value;
			}
			
		}
		
		// Get the PayPal redirect uri
		$paypal_redirect = scottcart_get_paypal_redirect( true );
		
		//disable_paypal_verification setting
		$bypass = '0';
		
		if ( $bypass == '1' ) {
			
			// Validate the IPN
			
			$remote_post_vars      = array(
				'method'           => 'POST',
				'timeout'          => 45,
				'redirection'      => 5,
				'httpversion'      => '1.1',
				'blocking'         => true,
				'headers'          => array(
					'host'         => 'www.paypal.com',
					'connection'   => 'close',
					'content-type' => 'application/x-www-form-urlencoded',
					'post'         => '/cgi-bin/webscr HTTP/1.1',
					
				),
				'sslverify'        => false,
				'body'             => $encoded_data_array
			);
			
			// Get response
			$api_response = wp_remote_post( scottcart_get_paypal_redirect(), $remote_post_vars );
			
			if ( is_wp_error($api_response)) {
				$error = json_encode($api_response);
				//scottcart_log("Invalid IPN verification response. IPN data:".$error);
				return; // Something went wrong
			}
			
			if ( $api_response['body'] !== 'VERIFIED' ) {
				$error = json_encode($api_response);
				//scottcart_log("Invalid IPN verification response. IPN data:".$error);
				return; // Response not okay
			}
			
		}
			
		// Check if $post_data_array has been populated
		if ( ! is_array( $encoded_data_array ) && !empty( $encoded_data_array ) ) {
			return;
		}
			
		$defaults = array(
			'txn_type'       => '',
			'payment_status' => ''
		);
		
		// Get POST values from PayPal
		$encoded_data_array = wp_parse_args( $encoded_data_array, $defaults );
		
		//$encoded_data_arraya = serialize($encoded_data_array);
		
		
		// log all post data
		//scottcart_log($encoded_data_arraya);
		
		
		
		if (isset($encoded_data_array['txn_type']) && ($encoded_data_array['txn_type'] == "cart")) {
			
			
			// sanatize post data
			$mc_gross = 				sanitize_text_field($encoded_data_array['mc_gross']);
			$payment_status = 			sanitize_text_field($encoded_data_array['payment_status']);
			$payer_email = 				sanitize_text_field($encoded_data_array['payer_email']);
			$txn_id = 					sanitize_text_field($encoded_data_array['txn_id']);
			$num_cart_items =			sanitize_text_field($encoded_data_array['num_cart_items']);
			$order_id =					sanitize_text_field($encoded_data_array['custom']);
			
			// save order post data
			$ipn_post = array(
				'ID'			=> $order_id,
				'post_status'   => 'completed'
			);
			
			$result = wp_update_post($ipn_post);			
			
			// save meta data
			update_post_meta($order_id,	'scottcart_txn_id',			$txn_id);
			update_post_meta($order_id,	'scottcart_gateway', 		'paypal_standard');
			
			// create new wp account
			
			// it's important to use email from checkout page and not paypal email
			$email = get_the_title($order_id);
			$customer = array(
				"order_id" 			=> $order_id,
				"payer_email" 		=> $email );
			scottcart_new_account($customer);
			
			// decrement inventory
			scottcart_inventory($order_id);
			
			// successful payment hook
			do_action('scottcart_payment_complete',$order_id);
			
			// send emails
			scottcart_send_emails($order_id);
			
		}
		
		
		
	}
}
add_action('scottcart_PPS_IPN','scottcart_listen_for_paypal_ipn');




function scottcart_get_paypal_redirect( $ssl_check = false ) {

	// Check if SSL is being used on the site
	if ( is_ssl() || ! $ssl_check ) {
		$protocal = 'https://';
	} else {
		$protocal = 'http://';
	}

	// Check the current payment mode
	
	if (scottcart_get_option('paypal_standard_mode') == "0") {
		$paypal_uri = $protocal . 'www.sandbox.paypal.com/cgi-bin/webscr';
	} else {
		$paypal_uri = $protocal . 'www.paypal.com/cgi-bin/webscr';
	}
	

	return apply_filters( 'scottcart_paypal_uri', $paypal_uri );
}




// get php arg separator
function scottcart_get_php_arg_separator() {
	return ini_get('arg_separator.output');
}