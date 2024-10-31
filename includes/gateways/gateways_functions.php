<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// load free gateway
function scottcart_gateway_load_free($scottcart_gateway_array) {

	$free_array = array(
		'free' => array(
			'title'  				=> __( 'Free', 'scottcart' ),
			'slug'  				=> __( 'free', 'scottcart' ),
			'function_public'  		=> '',
			'function_private'  	=> '',
			'function_js'  			=> '',
			'live_link'  			=> '',
			'sandbox_link'  		=> '',
			'icon'  				=> '',
			'help' 					=> '',
		),
	);

	return array_merge($scottcart_gateway_array,$free_array);
}
add_filter( 'scottcart_gateway_array','scottcart_gateway_load_free');


// process cart submit - uses hidden field named 'action' with value 'scottcart_cart_submit'
function scottcart_admin_scottcart_cart_submit() {

	$scottcart_cart_nonce = 	sanitize_text_field($_POST['scottcart_cart_nonce']);
	$function = 				sanitize_text_field($_POST['fnp']);
	$order_id = 				intval($_POST['order_id']);

	// verify nonce
	if (!wp_verify_nonce($scottcart_cart_nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	// run private gateway function
	// only run function is it exists
	if (function_exists($function)) {
		echo call_user_func($function,$order_id);
	} else {
		echo __('Error - No gateway found.','scottcart');
	}

	wp_die();
}

add_action('admin_post_scottcart_cart_submit','scottcart_admin_scottcart_cart_submit');
add_action('admin_post_nopriv_scottcart_cart_submit','scottcart_admin_scottcart_cart_submit');


// cart totals
function scottcart_show_total($total) {
	
	if (scottcart_get_option('cart_show_totals') == "0") {
		echo "<h3 class='scottcart_title'>"; echo __('Total','scottcart'); echo "</h3>";
		
		echo "<div id='scottcart_cart_purchase_total_div'>";
				echo "<div class='scottcart_cart_purchase_total_wrapper'>";
				
				if (scottcart_get_option('tax') == "1") {
					echo "<div class='scottcart_alternate'>";
						echo "<div class='scottcart_cart_left'>";
							echo __('Tax','scottcart');
							echo ": ";
						echo "</div>";
						echo "<div class='scottcart_cart_right'>";
							echo "<span class='scottcart_tax_cart_amount'>";
								echo sanitize_meta('currency_scottcart','0','post');
							echo "</span>";
						echo "</div>";
					echo "</div>";
				}
				
				if (scottcart_get_option('shipping') == "1") {
					echo "<div class='scottcart_alternate'>";
						echo "<div class='scottcart_cart_left'>";
							echo __('Shipping','scottcart');
							echo ": ";
						echo "</div>";
						echo "<div class='scottcart_cart_right'>";
							echo "<span class='scottcart_tax_shipping_amount'>";
								echo sanitize_meta('currency_scottcart','0','post');
							echo "</span>";
						echo "</div>";
					echo "</div>";
				}
				
				echo "<div class='scottcart_alternate'>";
					echo "<div class='scottcart_cart_left'>";
						echo "<b>";
						echo __('Total','scottcart');
						echo ": ";
					echo "</div>";
					echo "<div class='scottcart_cart_right'>";
						echo "<span class='scottcart_cart_purchase_total'>";
							echo sanitize_meta('currency_scottcart',$total,'post');
						echo "</span>";
						echo "</b>";
					echo "</div>";
				echo "</div>";
				
			echo "</div>";
		echo "</div><br />";
	}
}

add_action('scottcart_cart_before_purchase_button','scottcart_show_total');


// empty cart - not used - functions tends to run prematurely and empties the cart before it is a successful payment or redirect
function scottcart_empty_cart() {

	// no nonce is needed for this since it is so simple
	
	// empty cart
	$_SESSION['scottcart_cart_pending'] = null;
	$_SESSION['scottcart_cart'] = null;
	
	wp_die();
}
add_action('wp_ajax_scottcart_empty_cart','scottcart_empty_cart');
add_action('wp_ajax_nopriv_scottcart_empty_cart','scottcart_empty_cart');























// submit cart
function scottcart_cart_submit_callback() {
	
	// get variables
	if (isset($_POST['nonce'])) {
		$nonce = 	sanitize_text_field($_POST['nonce']);
	} else {
		$nonce = '';
	}
	
	if (isset($_POST['email'])) {
		$payer_email = 	sanitize_email($_POST['email']);
	} else {
		$payer_email = '';
	}
	
	if (isset($_POST['first_name'])) {
		$first_name = 	sanitize_text_field($_POST['first_name']);
	} else {
		$first_name = '';
	}
	
	if (isset($_POST['last_name'])) {
		$last_name = 	sanitize_text_field($_POST['last_name']);
	} else {
		$last_name = '';
	}
	
	if (isset($_POST['gateway'])) {
		$gateway = 		sanitize_text_field($_POST['gateway']);
	} else {
		$gateway = '';
	}
	
	if (isset($_POST['ip'])) {
		$ip = 			sanitize_text_field($_POST['ip']);
	} else {
		$ip = '';
	}
	
	// billing
	if (isset($_POST['billing_name'])) {
		$billing_name = 	sanitize_text_field($_POST['billing_name']);
	} else {
		$billing_name = '';
	}
	
	if (isset($_POST['billing_line_1'])) {
		$billing_line_1 = 	sanitize_text_field($_POST['billing_line_1']);
	} else {
		$billing_line_1 = '';
	}
	
	if (isset($_POST['billing_line_2'])) {
		$billing_line_2 = 	sanitize_text_field($_POST['billing_line_2']);
	} else {
		$billing_line_2 = '';
	}
	
	if (isset($_POST['billing_country'])) {
		$billing_country = 	sanitize_text_field($_POST['billing_country']);
	} else {
		$billing_country = '';
	}
	
	if (isset($_POST['billing_state'])) {
		$billing_state = 	sanitize_text_field($_POST['billing_state']);
	} else {
		$billing_state = '';
	}
	
	if (isset($_POST['billing_city'])) {
		$billing_city = 	sanitize_text_field($_POST['billing_city']);
	} else {
		$billing_city = '';
	}
	
	if (isset($_POST['billing_zip'])) {
		$billing_zip = 	sanitize_text_field($_POST['billing_zip']);
	} else {
		$billing_zip = '';
	}
	
	// shipping
	if (isset($_POST['shipping_name'])) {
		$shipping_name = 	sanitize_text_field($_POST['shipping_name']);
	} else {
		$shipping_name = '';
	}
	
	if (isset($_POST['shipping_line_1'])) {
		$shipping_line_1 = 	sanitize_text_field($_POST['shipping_line_1']);
	} else {
		$shipping_line_1 = '';
	}
	
	if (isset($_POST['shipping_line_2'])) {
		$shipping_line_2 = 	sanitize_text_field($_POST['shipping_line_2']);
	} else {
		$shipping_line_2 = '';
	}
	
	if (isset($_POST['shipping_country'])) {
		$shipping_country = 	sanitize_text_field($_POST['shipping_country']);
	} else {
		$shipping_country = '';
	}
	
	if (isset($_POST['shipping_state'])) {
		$shipping_state = 	sanitize_text_field($_POST['shipping_state']);
	} else {
		$shipping_state = '';
	}
	
	if (isset($_POST['shipping_city'])) {
		$shipping_city = 	sanitize_text_field($_POST['shipping_city']);
	} else {
		$shipping_city = '';
	}
	
	if (isset($_POST['shipping_zip'])) {
		$shipping_zip = 	sanitize_text_field($_POST['shipping_zip']);
	} else {
		$shipping_zip = '';
	}
	
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }

	
	// set empty post_id
	$post_id = '';
	$cart = 		scottcart_cart_total(0);
	$total = 		scottcart_cart_total(1);
	$discount = 	scottcart_cart_total(2);
	
	
	
	if ($total['total_amount'] == '0') {
		$gateway = "free";
	}
	
	
	// if free, then set order status to completed
	if ($gateway == "free") { $status = "completed"; } else { $status = "pend"; }






	// create or updating pending order
	
	// check to see if pending order session already exists - if not then create the order
	if (!isset($_SESSION['scottcart_cart_pending'])) {
		
		// no order id yet
		$pending_order = array(
			'post_content'    	=> $total['total_amount'],
			'post_excerpt'    	=> $total['total_items'],
			'post_status'   	=> $status,
			'post_title'		=> $payer_email,
			'post_type'     	=> 'scottcart_order'
		);
		
		$post_id = wp_insert_post($pending_order);
		
		$_SESSION['scottcart_cart_pending'] = $post_id;
		
	} else {
		
		// update pending order in case cart contents changed
		$post_id = sanitize_text_field($_SESSION['scottcart_cart_pending']);
		
		$pending_order = array(
			'ID'           		=> $post_id,
			'post_content'    	=> $total['total_amount'],
			'post_excerpt'    	=> $total['total_items'],
			'post_status'   	=> $status,
			'post_title'		=> $payer_email
		);
		
		wp_update_post($pending_order);
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	// update post meta
	if (!empty($discount['coupon'])) {				update_post_meta($post_id,'scottcart_discount_code',		$discount['coupon']); }
	if (!empty($total['total_discount'])) {			update_post_meta($post_id,'scottcart_discount_amount',		$total['total_discount']); }
	if (!empty($gateway)) { 						update_post_meta($post_id,'scottcart_gateway',				$gateway); }
	if (!empty($ip)) {								update_post_meta($post_id,'scottcart_ip',					$ip); }
	if (!empty($first_name)) {						update_post_meta($post_id,'scottcart_first_name',			$first_name); }
	if (!empty($last_name)) {						update_post_meta($post_id,'scottcart_last_name',			$last_name); }
	
	if (!empty($total['total_tax'])) {				update_post_meta($post_id,'scottcart_tax',					$total['total_tax']); }
	if (!empty($total['total_shipping'])) {			update_post_meta($post_id,'scottcart_shipping',				$total['total_shipping']); }
	
	if (!empty($billing_name)) {					update_post_meta($post_id,'scottcart_billing_name',			$billing_name); }
	if (!empty($billing_line_1)) {					update_post_meta($post_id,'scottcart_billing_line_1',		$billing_line_1); }
	if (!empty($billing_line_2)) {					update_post_meta($post_id,'scottcart_billing_line_2',		$billing_line_2); }
	if (!empty($billing_country)) {					update_post_meta($post_id,'scottcart_billing_country',		$billing_country); }
	if (!empty($billing_state)) {					update_post_meta($post_id,'scottcart_billing_state',		$billing_state); }
	if (!empty($billing_city)) {					update_post_meta($post_id,'scottcart_billing_city',			$billing_city); }
	if (!empty($billing_zip)) {						update_post_meta($post_id,'scottcart_billing_zip',			$billing_zip); }
	
	if (!empty($shipping_name)) {					update_post_meta($post_id,'scottcart_shipping_name',		$shipping_name); }
	if (!empty($shipping_line_1)) {					update_post_meta($post_id,'scottcart_shipping_line_1',		$shipping_line_1); }
	if (!empty($shipping_line_2)) {					update_post_meta($post_id,'scottcart_shipping_line_2',		$shipping_line_2); }
	if (!empty($shipping_country)) {				update_post_meta($post_id,'scottcart_shipping_country',		$shipping_country); }
	if (!empty($shipping_state)) {					update_post_meta($post_id,'scottcart_shipping_state',		$shipping_state); }
	if (!empty($shipping_city)) {					update_post_meta($post_id,'scottcart_shipping_city',		$shipping_city); }
	if (!empty($shipping_zip)) {					update_post_meta($post_id,'scottcart_shipping_zip',			$shipping_zip); }
	
	// this is used to show that the order was not manually created
	update_post_meta($post_id,'scottcart_new',"1");
	

	// update cart items
	
	$i = "0";
	foreach ($cart as $cart_item) {
		$item_id = sanitize_text_field($cart_item['id']);
		update_post_meta($post_id, 'scottcart_item_id'.$i, $item_id);
		
		$variation_id = sanitize_text_field($cart_item['variation']);
		update_post_meta($post_id, 'scottcart_item_variation'.$i, $variation_id);
		
		if (isset($cart_item['attribute'])) {
			$attribute_id = sanitize_text_field($cart_item['attribute']);
			update_post_meta($post_id, 'scottcart_item_attribute'.$i, $attribute_id);
		}
		
		$quantity = sanitize_text_field($cart_item['quantity']);
		update_post_meta($post_id, 'scottcart_item_quantity'.$i, $quantity);
		
		$mc_gross = sanitize_text_field($cart_item['price']);
		update_post_meta($post_id, 'scottcart_item_gross'.$i, $mc_gross);
		
		$i++;
	}

	
	// mode - if this a real or sandbox transaction
	if ($gateway == 'free') {
		$mode = "1";
	} else {
		$mode = scottcart_get_option($gateway.'_mode'); // mode of gateway is the gateway slug + _mode
	}
	
	update_post_meta($post_id,'scottcart_mode',$mode);

	
	// save customer order data
	
	// check to see if customer email already exists
	$posts_customer = get_page_by_title($payer_email,'','scottcart_customer');
	
	if (empty($posts_customer)) {
		// email does not exist so make a new post
		$customer_post = array(
			'post_title'    		=> $payer_email,
			'post_status'   		=> 'private',
			'post_type'     		=> 'scottcart_customer'
		);
		
		$cutomer_id = wp_insert_post($customer_post);
	} // else, email exists so do nothing
	
	
	// reassign order id to session - occasionally it gets lost when this function runs, and this fixes the problem
	$_SESSION['scottcart_cart_pending'] = $post_id;
	

	// if free send emails and empty cart
	if ($gateway == "free") {
		
		// reduce quantity
		scottcart_inventory($post_id);
		
		// empty cart
		$_SESSION['scottcart_cart_pending'] 	= null;
		$_SESSION['scottcart_cart'] 			= null;
		
		// successful payment hook
		do_action('scottcart_payment_complete',$post_id);
		
		// send emails -- needs to be after payment hook to generate license keys, etc
		scottcart_send_emails($post_id);
	}


	// json response
	$response = array(
		'order_id'   	=> $post_id,
		'status'		=> $status
	);

	echo json_encode($response);
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_cart_submit', 'scottcart_cart_submit_callback' );
add_action( 'wp_ajax_nopriv_scottcart_cart_submit', 'scottcart_cart_submit_callback' );