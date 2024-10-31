<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// profile tab
function scottcart_account_profile_tab() {

	$output = '';
	
	$current_user = wp_get_current_user();
	
	$output .= "<input type='hidden' id='scottcart-id' value='$current_user->ID'>";
	
	// create nonce
	$ajax_nonce = wp_create_nonce('scottcart_update_user_account_'.$current_user->ID);
	$output .= "<input type='hidden' id='scottcart-nonce' value='$ajax_nonce'>";
	
	$output .= "<table class='scottcart_account_table'>";
	$output .= "<tr><td valign='top'>".__('First Name','scottcart')."</td><td valign='top'><input type='text' id='scottcart-fname' value='$current_user->user_firstname'></td></tr>";
	$output .= "<tr><td valign='top'>".__('Last Name','scottcart')."</td><td valign='top'><input type='text' id='scottcart-lname' value='$current_user->user_lastname'></td></tr>";
	$output .= "<tr><td valign='top'>".__('Email','scottcart')."</td><td valign='top'>$current_user->user_email</td></tr>";
	$output .= "<tr><td></td><td></td></tr>";
	$output .= "<tr><td valign='top'><input type='submit' id='scottcart_account_submit' style='background-color:"; $output .= scottcart_get_option('button_color'); $output .= "; color:"; $output .= scottcart_get_option('button_text_color'); $output .= ";' value='"; $output .= scottcart_get_option('text_5'); $output .="'><br /><span id='scottcart-result'></span></td><td></td></tr>";
	$output .= "</table>";
	
	return $output;
	
}





// purchases tab
function scottcart_account_purchases_tab() {

	$output = '';
	
	$current_user = wp_get_current_user();
	
	$args = array(
		'post_type'		=> 'scottcart_order',
		'post_status'	=> 'any',
		'title' 		=> $current_user->user_email
	);
	
	$order_array = get_posts($args);
	
	$output .= "<table class='scottcart_account_table'><tr><td width='80px'>"; $output .= __('Order #','scottcart'); $output .= "</td><td width='110px'>"; $output .= __('Date','scottcart'); $output .= "</td><td width='80px' class='scottcart-account-hide'>"; $output .= __('Amount','scottcart'); $output .= "</td><td width='70px' class='scottcart-account-hide'>"; $output .= __('Items','scottcart'); $output .= "</td><td width='110px' class='scottcart-account-hide'>"; $output .= __('Status','scottcart'); $output .= "</td><td width='60px'></td></tr>";
	
	foreach ($order_array as $order) {
		$purchases = "true";
		$post_total = $order->post_content;
		$post_items = $order->post_excerpt;
		
		$output .= "<tr class='scottcart_alternate_account scottcart_account_row'><td>";
		$output .= $order->ID;
		$output .= "</td><td>";
		$date = explode(' ',$order->post_date);
		$output .= date(get_option('date_format'), strtotime($date['0']));
		$output .= "</td><td class='scottcart-account-hide'>";
		$output .= sanitize_meta( 'currency_scottcart',$post_total,'post');
		$output .= "</td><td class='scottcart-account-hide'>";
		$output .= $post_items;
		$output .= "</td><td class='scottcart-account-hide'>";
		$scottcart_status = $order->post_status;
		if ($scottcart_status == "pend") { $scottcart_status = "pending"; }
		$output .= ucfirst($scottcart_status);
		$output .= "</td><td>";
		
		
		$nonce = wp_create_nonce( 'view_details_'.$order->ID);
		
		$output .= "<a href='#' class='scottcart_account_details_view' id='$nonce' data-id='$order->ID'>"; $output .= __('View','scottcart'); $output .= "</a>";
		$output .= "</td></tr>";
		
	}
	
	if (!isset($purchases)) {
		$output .= "<tr class='scottcart_alternate_account'><td colspan='6'>"; $output .= __('You have not purchased anything yet.','scottcart'); $output .= "</td></tr>";
	}
	
	$output .= "</table>";
	
	return $output;
	
}


// files tab
function scottcart_account_files_tab() {

	$output = '';

	$current_user = wp_get_current_user();

	$args = array(
		'post_type'		=> 'scottcart_order',
		'post_status'	=> 'completed',
		'title' 		=> $current_user->user_email
	);
	
	$order_array = get_posts($args);
	
	$output .= "<table class='scottcart_account_table'><tr><td width='80px'>"; $output .= __('Product Name','scottcart'); $output .= "</td><td width='110px'>"; $output .= __('Download','scottcart'); $output .= "</td></tr>";
	
	// loop thru customers orders
	foreach ($order_array as $order) {
		
		// get number of items in cart
		$scottcart_num_cart_items = $order->post_excerpt;
		
		$items = "0";
		
		for($i=0;$i<$scottcart_num_cart_items;$i++) {
			
			// get purchased product id and variation id
			$product_id = get_post_meta($order->ID,'scottcart_item_id'.$i,true);
			$variation_id = get_post_meta($order->ID,'scottcart_item_variation'.$i,true);
			
			// get how many files the product has
			$scottcart_digital_attribute_count = get_post_meta($product_id,'scottcart_digital_attribute_count', true);
			
			if ($scottcart_digital_attribute_count > 0) {
				// get all files that the purchased download contains
				for($a=0;$a<$scottcart_digital_attribute_count;$a++) {
					$scottcart_digital_attribute_assignment = get_post_meta($product_id,'scottcart_digital_attribute_assignment'.$a, true);
					if ($scottcart_digital_attribute_assignment == $variation_id || $scottcart_digital_attribute_assignment == "a") {
						$download__file_name = 	get_post_meta($product_id,'scottcart_digital_attribute_name'.$a,true);
						$download_id = 			get_post_meta($product_id,'scottcart_digital_attribute_file'.$a,true);
						
						$download = get_the_title($product_id);
						
						if (!empty($order->ID)) {
							$output .= "<tr class='scottcart_alternate'><td>";
							$output .= $download;
							$output .= "</td><td>";
							
							$download_url = scottcart_generate_download_url($order->ID,$download_id);
							$output .= "<a href='$download_url'>";
							$output .= $download__file_name;
							$output .= "</a>";
							
							$output .= "</td></tr>";
							$items++;
						}
					}
				}
			}
		}
	}
	
	if ($items == '0') {
		$output .= "<tr class='scottcart_alternate_account'><td colspan='2'>"; $output .= __('You do not have any downloads available.','scottcart'); $output .= "</td></tr>";
	}
	
	$output .= "</table>";
	
	return $output;
}



// purchase details tab
function scottcart_account_purchase_details_tab() {
	
	$output = '';

	$output .= "<div class='scottcart_account_purchase_details_tab' style='display:none;'>";
	$output .= "</div>";
	
	return $output;
}