<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// register meta boxs
function scottcart_order_register_meta_boxes() {
	add_meta_box('meta-box-id-price', 			__('Item Details', 'scottcart'), 	'scottcart_order_display_callback_price', 'scottcart_order','normal');
	add_meta_box('meta-box-id-order', 			__('Order', 'scottcart'), 			'scottcart_order_display_callback_order', 'scottcart_order','side');
	add_meta_box('meta-box-id-tools', 			__('Tools', 'scottcart'), 			'scottcart_order_display_callback_tools', 'scottcart_order','side');
	add_meta_box('meta-box-id-payment', 		__('Payment', 'scottcart'), 		'scottcart_order_display_callback_payment', 'scottcart_order','normal');
	add_meta_box('meta-box-id-order_details', 	__('Order Details', 'scottcart'), 	'scottcart_order_display_callback_order_details', 'scottcart_order','normal');
	add_meta_box('meta-box-id-files', 			__('Downloads', 'scottcart'), 		'scottcart_order_display_callback_files', 'scottcart_order','normal');
	//add_meta_box('meta-box-id-log', 			__('Logs', 'scottcart'), 			'scottcart_order_display_callback_logs', 'scottcart_order','side');
}
add_action('add_meta_boxes', 'scottcart_order_register_meta_boxes');
	

// item info metabox
function scottcart_order_display_callback_price($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		echo "<style>#post-body-content { margin-bottom: 0px; }</style>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		
		$scottcart_status = $post->post_status;
		if ($scottcart_status != "auto-draft") {
			// data already exists
			$scottcart_num_cart_items = $post->post_excerpt;
			
			echo "<table width='100%' class='form-table' id='customFields'>
			<tr valign='top'><td width='15px'></td><td class='scottcart_cell_title_width'>"; echo __('Product','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Variation','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Attribute','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Quantity','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Price','scottcart'); echo "</td><td class='scottcart_cell_title_width'></td></tr>";
			
			$counter = "1";
			for($i=0;$i<$scottcart_num_cart_items;$i++) {
				
				echo "<tr valign='top'><td class='row-id'>$counter</td><td class='product-id'>";
				echo "<select style='width:150px;' class='product' name='product[]'><option></option>";
				
				// get all products for dropdown			
				$args = array(
					'post_type'					=> 'scottcart_product',
					'post_status'				=> 'publish',
					'update_post_term_cache'	=> false, // don't retrieve post terms
					'meta_query'		=> array(
					'relation'			=> 'or',
						array(
							'key'		=> 'scottcart_type',
							'value'		=> '0',
							'compare'	=> '=',
						),
						array(
							'key'		=> 'scottcart_type',
							'value'		=> '1',
							'compare'	=> '=',
						),
						array(
							'key'		=> 'scottcart_type',
							'value'		=> '2',
							'compare'	=> '=',
						)
					)
				);
				
				$posts_array = new WP_Query($args);
				
				foreach ($posts_array->posts as $posta) {
					$selected = get_post_meta($post->ID,'scottcart_item_id'.$i,true);
					$selected_variation = get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
					$selected_attribute = get_post_meta($post->ID,'scottcart_item_attribute'.$i,true);
					echo "<option value='$posta->ID'"; if ($selected == $posta->ID) { echo " SELECTED "; } echo " >$posta->post_title</option>";
				}
				echo "</select>";
				
				$scottcart_order_page_line_item_hook_array = array (
					'order_id' 		=> $post->ID,
					'product_id' 	=> $selected,
					'variation_id'	=> $selected_variation,
					'cart_id'		=> $i
				);
				
				do_action('scottcart_order_page_line_item',$scottcart_order_page_line_item_hook_array); echo "</td><td class='variation-id'></td><td class='attribute-id'></td><td class='quantity-id'></td><td class='price-id'></td><td><a href='javascript:void(0);' class='scottcart_remCF'><span class='dashicons dashicons-trash'></span></a><a href='javascript:void(0);' class='scottcart_load_variations'></a><input type='hidden' class='variation_id' value='$selected_variation'><input type='hidden' class='attribute_id' value='$selected_attribute'><input type='hidden' class='cart_id' value='$i'><input type='hidden' class='order_id' value='$post->ID'></td></tr>";
				echo '<script>jQuery(document).ready(function() { jQuery(".scottcart_load_variations").trigger("click"); });</script>'; // load variations
				$counter++;
			}
			
			echo "</table>";
			echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td class='scottcart_cell_title_width'><a href='javascript:void(0);' class='scottcart_addCF'>"; echo __('Add','scottcart'); echo "</a></td><td class='scottcart_cell_title_width'></td><td class='scottcart_cell_title_width'></td><td class='scottcart_cell_title_width'></td></tr></table>";
			
			
			
			
		} else {
			// no order items currently exist
			
			echo "<script type='text/javascript'>load_products_new();</script>";
			
			echo "
			<table width='100%' class='form-table' id='customFields'>
			<tr valign='top'><td width='15px'></td><td class='scottcart_cell_title_width'>"; echo __('Product','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Variation','scottcart'); echo "</td><td class='scottcart_cell_title_width'>"; echo __('Price','scottcart'); echo "</td><td class='scottcart_cell_title_width'></td></tr>
			<tr>
			<td class='row-id'>1</td>
			<td class='product-id'></td>
			<td class='variation-id'></td>
			<td class='price-id'></td>
			<td></td>
			</tr>
			</table>";
			
			echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td class='scottcart_cell_title_width'><a href='javascript:void(0);' class='scottcart_addCF'>"; echo __('Add','scottcart'); echo "</a></td><td class='scottcart_cell_title_width'></td><td class='scottcart_cell_title_width'></td><td class='scottcart_cell_title_width'></td></tr></table>";
			
		}
		
	echo "</div>";
}


// order metabox
function scottcart_order_display_callback_order($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo '<table><tr>';
		
		$scottcart_order = $post->ID;
		$scottcart_email = $post->post_title;
		$scottcart_date =  $post->post_date;
		
		
		$scottcart_status = $post->post_status;
		if ($scottcart_status == "auto-draft") { $scottcart_status = "pend"; }
		
		echo "<input type='hidden' name='scottcart_status' value='$scottcart_status'>";
		
		
		$scottcart_new = get_post_meta($post->ID,'scottcart_new',true);
		echo "<input type='hidden' name='scottcart_new' value='1'>";
		
		echo "<td class='scottcart_cell_title_width'>Order #: </td><td><b>$scottcart_order</b></td></tr><tr>";
		
		
		echo "<td class='scottcart_cell_title_width'>Status: </td><td><select name='scottcart_status'>";
		echo "<option value='completed' "; if ($scottcart_status == "completed") { echo "SELECTED"; } echo " >"; 	echo __('Completed','scottcart'); echo "</option>";
		echo "<option value='pend'"; if ($scottcart_status == "pend") { echo "SELECTED"; } echo " >"; 				echo __('Pending','scottcart'); echo "</option>";
		echo "<option value='processing'"; if ($scottcart_status == "processing") { echo "SELECTED"; } echo " >"; 	echo __('Processing','scottcart'); echo "</option>";
		echo "<option value='abandoned'"; if ($scottcart_status == "abandoned") { echo "SELECTED"; } echo " >"; 	echo __('Abandoned','scottcart'); echo "</option>";
		echo "<option value='cancelled'"; if ($scottcart_status == "cancelled") { echo "SELECTED"; } echo " >"; 	echo __('Cancelled','scottcart'); echo "</option>";
		echo "<option value='refunded'"; if ($scottcart_status == "refunded") { echo "SELECTED"; } echo " >"; 		echo __('Refunded','scottcart'); echo "</option>";
		echo "</select></td></tr><tr>";
		
		echo "<td class='scottcart_cell_title_width'>"; echo __('Date','scottcart'); echo ": </td><td><input size='12' type='text' name='scottcart_date' class='scottcart-datepicker' value='"; echo date('Y-m-d', strtotime($scottcart_date)); echo "' /></td></tr><tr>";
		echo "<td class='scottcart_cell_title_width'>"; echo __('Time','scottcart'); echo ": </td><td>
		<input type='text' size='2' name='scottcart_hh' value='"; echo date('H', strtotime($scottcart_date)); echo "' />:
		<input type='text' size='2' name='scottcart_mm' value='"; echo date('i', strtotime($scottcart_date)); echo "' />
		</td></tr><tr>";
		
		
		echo "<td class='scottcart_cell_title_width'></td><td><br /></td></tr><tr>";
		
		
		echo "<td class='scottcart_cell_title_width'>";
		
		echo "<div class='submitbox' id='submitpost'><a class='submitdelete' href='" . get_delete_post_link( $post->ID, '', false ) . "'>"; echo __('Move to Trash','scottcart'); echo "</a></div>";
		
		echo "</td><td align='right'><input id='publish' class='button-primary' type='submit' value='"; echo __('Update Order','scottcart'); echo "' accesskey='p' tabindex='5' name='save'></td></tr><tr>";
		
		echo '</tr></table>';
	echo "</div>";
}







// tools metabox
function scottcart_order_display_callback_tools($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo '<table><tr>';
		
		echo "<td><a href='#' id='scottcart_resend_customer_email'>"; echo __('Resend Customer Email','scottcart'); echo "</a><input id='scottcart_customer_order_id' type='hidden' value='$post->ID'><div id='scottcart_customer_email_status'></div></td>";
		
		echo '</tr></table>';
		
	echo "</div>";
}




// files metabox
function scottcart_order_display_callback_files($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo "<table width='100%'><tr><td>"; echo __('Product Name','scottcart'); echo "</td><td>"; echo __('Download Name','scottcart'); echo "</td><td>"; echo __('Download URL','scottcart'); echo "</td></tr>";
		
		$scottcart_conatains_files = "false";
		
		// get number of items in cart
		$scottcart_item = $post->post_excerpt;
		
		if (isset($scottcart_item)) {
			$scottcart_num_cart_items = $scottcart_item;
			
			for($i=0;$i<$scottcart_num_cart_items;$i++) {
				
				// get purchased product id and variation id
				$product_id = get_post_meta($post->ID,'scottcart_item_id'.$i,true);
				$variation_id = get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
				
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
							
							if (!empty($post->ID)) {
								$scottcart_conatains_files = "true";
								echo "<tr><td>";
								echo $download;
								echo "</td><td>";
								$download_url = scottcart_generate_download_url($post->ID,$download_id);
								echo $download__file_name;
								echo "</td><td>";
								echo "<input onClick='this.select();' style='width:100%;' type='text' value='$download_url'>";
								echo "</td></tr>";
							}
						}
					}
				}	
			}
		
		}
		
		if ($scottcart_conatains_files == "false") {
			echo "<tr><td>";
			echo __('This order does not contain any downloads.','scottcart');
			echo "</td></tr>";
		}
		
		echo "</table>";
		
	echo "</div>";
}


// logs metabox
function scottcart_order_display_callback_logs($post) {
	global $meta_box, $post, $scottcart_conatains_files;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo '<table><tr>';
		
		echo "<td><a href='#'>"; echo __('View File Download Logs','scottcart'); echo "</a></td>";
		
		echo '</tr></table>';
		
	echo "</div>";
}




// order metabox
function scottcart_order_display_callback_payment($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo '<table><tr>';
		
		$scottcart_new = get_post_meta($post->ID,'scottcart_new',true);
		if ($scottcart_new == "1") {
			// existing order
			$scottcart_total = $post->post_content;
			$scottcart_num_cart_items = $post->post_excerpt;
			
			$scottcart_discount_code = 				get_post_meta($post->ID,'scottcart_discount_code',true);
			$scottcart_discount_amount = 			get_post_meta($post->ID,'scottcart_discount_amount',true);
			$scottcart_txn_id = 					get_post_meta($post->ID,'scottcart_txn_id',true);
			$scottcart_gateway = 					get_post_meta($post->ID,'scottcart_gateway',true);
			$scottcart_mode = 						get_post_meta($post->ID,'scottcart_mode',true);
			$scottcart_tax = 						get_post_meta($post->ID,'scottcart_tax',true);
			$scottcart_shipping = 					get_post_meta($post->ID,'scottcart_shipping',true);
			$scottcart_total_quantity = 			get_post_meta($post->ID,'scottcart_total_quantity',true);
			
			
			
			$gateways_array = scottcart_gateways_api();
			
			// was the transaction live or sandbox
			$scottcart_link = "";
			if ($scottcart_mode == "0") {
				$scottcart_link = $gateways_array[strtolower($scottcart_gateway)]['sandbox_link'];
			}
			if ($scottcart_mode == "1") {
				$scottcart_link = $gateways_array[strtolower($scottcart_gateway)]['live_link'];
			}
			
			if ($scottcart_mode == "0") { $scottcart_mode = "Testing"; } elseif ($scottcart_mode == "1") { $scottcart_mode = "Live"; } else { $scottcart_mode = ""; }
			
			// # of cart products
			echo "<td class='scottcart_cell_title_width'>"; echo __('# Products','scottcart'); echo ": </td><td>$scottcart_num_cart_items</td></tr><tr>";
			
			// # of cart items
			echo "<td class='scottcart_cell_title_width'>"; echo __('# Items','scottcart'); echo ": </td><td>$scottcart_total_quantity</td></tr><tr>";
			
			// subtotal
			//$scottcart_subtotal = ( + $scottcart_discount_amount) - $scottcart_tax - $scottcart_shipping;
			//echo "<td class='scottcart_cell_title_width'>"; echo __('Subtotal','scottcart'); echo ": </td><td>"; if ($scottcart_subtotal < 0) { echo sanitize_meta( 'currency_scottcart','0','post'); } else { echo sanitize_meta( 'currency_scottcart',$scottcart_subtotal,'post'); } echo"</td></tr><tr>";
			
			// discounts
			echo "<tr><td class='scottcart_cell_title_width' valign='top'>"; echo __('Discount Code','scottcart'); echo ": </td><td>";  echo chunk_split($scottcart_discount_code,10); echo "</td></tr>";
			echo "<tr><td class='scottcart_cell_title_width' valign='top'>"; echo __('Discount Amount','scottcart'); echo ": </td><td>";  echo sanitize_meta('currency_scottcart',($scottcart_discount_amount),'post'); echo "</td></tr>";
			
			// tax and shipping
			echo "<td class='scottcart_cell_title_width'>"; echo __('Tax','scottcart'); echo ": </td><td>"; if ($scottcart_tax < 0) { echo sanitize_meta( 'currency_scottcart','0','post'); } else { echo sanitize_meta( 'currency_scottcart',$scottcart_tax,'post'); } echo"</td></tr><tr>";
			echo "<td class='scottcart_cell_title_width'>"; echo __('Shipping','scottcart'); echo ": </td><td>"; if ($scottcart_shipping < 0) { echo sanitize_meta( 'currency_scottcart','0','post'); } else { echo sanitize_meta( 'currency_scottcart',$scottcart_shipping,'post'); } echo"</td></tr><tr>";
			
			
			// total
			echo "<td class='scottcart_cell_title_width'><b>"; echo __('Total','scottcart'); echo ": </b></td><td><b>"; if ($scottcart_total < 0) { echo sanitize_meta( 'currency_scottcart','0','post'); } else { echo sanitize_meta( 'currency_scottcart',$scottcart_total,'post'); } echo"</b></td></tr><tr>";
			
			// gateway
			echo "<tr><td class='scottcart_cell_title_width'><br />"; echo __('Gateway','scottcart'); echo ": </td><td><br /><select class='scottcart_cell_width' name='scottcart_gateway'>";
			
			foreach ($gateways_array as $gateway) {
				echo "<option "; if ($scottcart_gateway == $gateway['slug']) { echo "SELECTED "; } echo " value='"; echo $gateway['slug']; echo "'>"; echo $gateway['title']; echo "</option>";
			}
			echo "</select></td></tr>";
			
			echo "<tr><td class='scottcart_cell_title_width'>Sandbox: </td><td><select class='scottcart_cell_width' name='scottcart_mode'>";
			echo "<option "; if ($scottcart_mode == "Testing") { echo "SELECTED "; } echo " value='0'>"; echo __('Testing','scottcart'); echo "</option>";
			echo "<option "; if ($scottcart_mode == "Live") { echo "SELECTED "; } echo " value='1'>"; echo __('Live','scottcart'); echo "</option>";
			echo "</select></td></tr>";
			
			echo "<tr><td class='scottcart_cell_title_width' valign='top'>"; echo __('Txn #','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_txn_id' value='$scottcart_txn_id'>";
			if (!empty($scottcart_txn_id)) {
				echo " <a target='_blank' href=$scottcart_link$scottcart_txn_id>"; echo __('Link','scottcart'); echo "</a>";
			}
			echo "</td></tr>";
			
		} else {
			// new order
			
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Gateway','scottcart'); echo ": </td><td><select class='scottcart_cell_width' name='scottcart_gateway'>";
			$gateways = scottcart_load_gateways(1);
			
			if (empty($scottcart_gateway)) {
				$scottcart_gateway = '';
			}
			
			foreach ($gateways as $gateway) {
				echo "<option "; if ($scottcart_gateway == $gateway) { echo "SELECTED "; } echo " value='$gateway'>$gateway</option>";
			}
			echo "</select></td></tr>";
			
			echo "<td class='scottcart_cell_title_width'>"; echo __('Sandbox','scottcart'); echo ": </td><td><select class='scottcart_cell_width' name='scottcart_mode'>";
			echo "<option value='0'>Testing</option>";
			echo "<option value='1'>Live</option>";
			echo "</select></td></tr><tr>";
			
			echo "<td class='scottcart_cell_title_width' valign='top'>"; echo __('Txn #','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_txn_id' value=''></td></tr>";
			
			
		}
		
		
		// payment id
		$scottcart_stripe_id = get_post_meta($post->ID,'scottcart_stripe_id',true);
		$scottcart_mode = get_post_meta($post->ID,'scottcart_mode',true);
		if (!empty($scottcart_stripe_id) && ($scottcart_mode == "0")) { $scottcart_link = "https://dashboard.stripe.com/test/customers/"; }
		if (!empty($scottcart_stripe_id) && ($scottcart_mode == "1")) { $scottcart_link = "https://dashboard.stripe.com/customers/"; }
		$scottcart_new = get_post_meta($post->ID,'scottcart_new',true);
		if ($scottcart_new == "1") {
			// existing order
			echo "<td class='scottcart_cell_title_width'>"; echo __('Payment ID','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_stripe_id' value='$scottcart_stripe_id'>";
			if (!empty($scottcart_stripe_id)) {
				echo " <a target='_blank' href='$scottcart_link$scottcart_stripe_id'>"; echo __('Link','scottcart'); echo "</a>";
			}
			echo "</td></tr>";
		} else {
			// new order
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Payment ID','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_stripe_id' value=''></td></tr>";
		}
		
		
		echo '</table>';
	echo "</div>";
}




// order details metabox
function scottcart_order_display_callback_order_details($post) {
	global $meta_box, $post;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		
		// main table
		echo "<table width='100%'><tr><td width='50%' valign='top'>";
			
			
			// customer table
			echo "<table width='100%'><tr>";
			
			$scottcart_email = $post->post_title;
			$scottcart_ip = get_post_meta($post->ID,'scottcart_ip',true);
			$scottcart_link = "";
			
			
			echo "<td class='scottcart_cell_title_width'>"; echo __('Email','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_email' value='"; echo $scottcart_email; echo "'>";
			if (!empty($scottcart_email)) {
				$scottcart_email_id = get_page_by_title($scottcart_email,OBJECT,'scottcart_customer');
				
				if (!empty($scottcart_email_id)) {
					echo " <a href='post.php?post=$scottcart_email_id->ID&action=edit'>"; echo __('View Customer','scottcart'); echo "</a>";
				}
			}
			echo "</td></tr>";
			
			
			// ip address
			echo "<tr><td class='scottcart_cell_title_width'>IP: </td><td><input class='scottcart_cell_width' type='text' name='scottcart_ip' value='"; echo $scottcart_ip; echo "'</td></tr>";
			
			// billing info
			$scottcart_billing_name = get_post_meta($post->ID,'scottcart_billing_name',true);
			$scottcart_billing_line_1 = get_post_meta($post->ID,'scottcart_billing_line_1',true);
			$scottcart_billing_line_2 = get_post_meta($post->ID,'scottcart_billing_line_2',true);
			$scottcart_billing_country = get_post_meta($post->ID,'scottcart_billing_country',true);
			$scottcart_billing_state = get_post_meta($post->ID,'scottcart_billing_state',true);
			$scottcart_billing_city = get_post_meta($post->ID,'scottcart_billing_city',true);
			$scottcart_billing_zip = get_post_meta($post->ID,'scottcart_billing_zip',true);
			
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Name','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_name' value='$scottcart_billing_name'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address Line 1','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_line_1' value='$scottcart_billing_line_1'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address Line 2','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_line_2' value='$scottcart_billing_line_2'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address Country','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_country' value='$scottcart_billing_country'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address State','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_state' value='$scottcart_billing_state'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address City','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_city' value='$scottcart_billing_city'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Billing Address Zip','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_billing_zip' value='$scottcart_billing_zip'></td></tr>";
			
			echo '</table>'; // end customer table
			
		echo "</td><td width='50%' valign='bottom'>"; // main table
			
			echo "<table width='100%'><tr>"; // shipping table
			
			// shipping info
			$scottcart_shipping_name = get_post_meta($post->ID,'scottcart_shipping_name',true);
			$scottcart_shipping_line_1 = get_post_meta($post->ID,'scottcart_shipping_line_1',true);
			$scottcart_shipping_line_2 = get_post_meta($post->ID,'scottcart_shipping_line_2',true);
			$scottcart_shipping_country = get_post_meta($post->ID,'scottcart_shipping_country',true);
			$scottcart_shipping_state = get_post_meta($post->ID,'scottcart_shipping_state',true);
			$scottcart_shipping_city = get_post_meta($post->ID,'scottcart_shipping_city',true);
			$scottcart_shipping_zip = get_post_meta($post->ID,'scottcart_shipping_zip',true);
			
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Name','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_name' value='$scottcart_shipping_name'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address Line 1','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_line_1' value='$scottcart_shipping_line_1'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address Line 2','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_line_2' value='$scottcart_shipping_line_2'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address Country','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_country' value='$scottcart_shipping_country'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address State','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_state' value='$scottcart_shipping_state'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address City','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_city' value='$scottcart_shipping_city'></td></tr>";
			echo "<tr><td class='scottcart_cell_title_width'>"; echo __('Shipping Address Zip','scottcart'); echo ": </td><td><input class='scottcart_cell_width' type='text' name='scottcart_shipping_zip' value='$scottcart_shipping_zip'></td></tr>";
			
			echo '</table>'; // end shipping table
			
		echo '</table>'; // end main table
		
	echo "</div>";
}


// save
function scottcart_save_meta_box_order ($post_id) {
	global $post;

	if (isset($_POST['scottcart_submit_order']) && $_POST['scottcart_submit_order'] == "1") {
		
		// verify nonce
		if (!wp_verify_nonce($_POST['scottcart_MetaNonce'], basename(__FILE__))) {
			return $post_id;
		}
		
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		
		
		
		// delete old values
		
		$scottcart_attribute_count = get_post_meta($post_id,'scottcart_attribute_count', true);
		
		// attributes - necessary if the product type / variation type is changed - attributes will not be deleted, even if they don't exist the new product / variation type
		for($i=0;$i<$scottcart_attribute_count;$i++) {
			delete_post_meta($post_id,'scottcart_item_attribute'.$i);
		}
		
		
		
		
		// update
		if (isset($_POST['scottcart_txn_id'])) { 			update_post_meta($post_id,'scottcart_txn_id',sanitize_text_field($_POST['scottcart_txn_id'])); }
		if (isset($_POST['scottcart_stripe_id'])) {			update_post_meta($post_id,'scottcart_stripe_id',sanitize_text_field($_POST['scottcart_stripe_id'])); }
		if (isset($_POST['scottcart_ip'])) { 				update_post_meta($post_id,'scottcart_ip',sanitize_text_field($_POST['scottcart_ip'])); }
		if (isset($_POST['scottcart_gateway'])) { 			update_post_meta($post_id,'scottcart_gateway',sanitize_text_field($_POST['scottcart_gateway'])); }
		if (isset($_POST['scottcart_mode'])) {				update_post_meta($post_id,'scottcart_mode',sanitize_text_field($_POST['scottcart_mode'])); }
		if (isset($_POST['scottcart_new'])) {				update_post_meta($post_id,'scottcart_new',sanitize_text_field($_POST['scottcart_new'])); }
		
		if (isset($_POST['scottcart_billing_name'])) {		update_post_meta($post_id,'scottcart_billing_name',sanitize_text_field($_POST['scottcart_billing_name'])); }
		if (isset($_POST['scottcart_billing_line_1'])) {	update_post_meta($post_id,'scottcart_billing_line_1',sanitize_text_field($_POST['scottcart_billing_line_1'])); }
		if (isset($_POST['scottcart_billing_line_2'])) {	update_post_meta($post_id,'scottcart_billing_line_2',sanitize_text_field($_POST['scottcart_billing_line_2'])); }
		if (isset($_POST['scottcart_billing_country'])) {	update_post_meta($post_id,'scottcart_billing_country',sanitize_text_field($_POST['scottcart_billing_country'])); }
		if (isset($_POST['scottcart_billing_state'])) {		update_post_meta($post_id,'scottcart_billing_state',sanitize_text_field($_POST['scottcart_billing_state'])); }
		if (isset($_POST['scottcart_billing_city'])) {		update_post_meta($post_id,'scottcart_billing_city',sanitize_text_field($_POST['scottcart_billing_city'])); }
		if (isset($_POST['scottcart_billing_zip'])) {		update_post_meta($post_id,'scottcart_billing_zip',sanitize_text_field($_POST['scottcart_billing_zip'])); }
		
		if (isset($_POST['scottcart_shipping_name'])) {		update_post_meta($post_id,'scottcart_shipping_name',sanitize_text_field($_POST['scottcart_shipping_name'])); }
		if (isset($_POST['scottcart_shipping_line_1'])) {	update_post_meta($post_id,'scottcart_shipping_line_1',sanitize_text_field($_POST['scottcart_shipping_line_1'])); }
		if (isset($_POST['scottcart_shipping_line_2'])) {	update_post_meta($post_id,'scottcart_shipping_line_2',sanitize_text_field($_POST['scottcart_shipping_line_2'])); }
		if (isset($_POST['scottcart_shipping_country'])) {	update_post_meta($post_id,'scottcart_shipping_country',sanitize_text_field($_POST['scottcart_shipping_country'])); }
		if (isset($_POST['scottcart_shipping_state'])) {	update_post_meta($post_id,'scottcart_shipping_state',sanitize_text_field($_POST['scottcart_shipping_state'])); }
		if (isset($_POST['scottcart_shipping_city'])) {		update_post_meta($post_id,'scottcart_shipping_city',sanitize_text_field($_POST['scottcart_shipping_city'])); }
		if (isset($_POST['scottcart_shipping_zip'])) {		update_post_meta($post_id,'scottcart_shipping_zip',sanitize_text_field($_POST['scottcart_shipping_zip'])); }
		
		
		// caculate total based on items assigned to order
		// update row gross product amount - used for manual and completed sale amounts - in case product price changes.
		
		if (isset($_POST['total'])) {
			$total = "";
			$gross_count = "0";
			foreach (array_map('sanitize_text_field',$_POST['total']) as $total_item) {
				update_post_meta($post_id,'scottcart_item_gross'.$gross_count,$total_item);
				$total = $total + $total_item;
				$gross_count++;
			}
		}
		
		// product
		$i = "0";
		if ($_POST['product']) {
			foreach (array_map('sanitize_text_field',$_POST['product']) as $product) {
				update_post_meta($post_id,'scottcart_item_id'.$i,$product);
				$i++;
			}
			
			// variation
			$v = "0";
			if (isset($_POST['variation'])) {
				foreach (array_map('sanitize_text_field',$_POST['variation']) as $variation) {
					$result = explode("|", $variation);
					update_post_meta($post_id,'scottcart_item_variation'.$v,$result[1]);
					$v++;
				}
			}
			
			// attribute
			$a = "0";
			if (isset($_POST['attribute'])) {
				foreach (array_map('sanitize_text_field',$_POST['attribute']) as $attribute) {
					$result = explode("|", $attribute);
					if ($result[1] != '') {
						update_post_meta($post_id,'scottcart_item_attribute'.$a,$result[1]);
						$a++;
					}
				}
			}
			
			// quantity
			$q = "0";
			$running_quantity = "0";
			if (isset($_POST['quantity'])) {
				foreach (array_map('sanitize_text_field',$_POST['quantity']) as $quantity) {
					$result = explode("|", $quantity);
					$running_quantity = $result[0] + $running_quantity;
					update_post_meta($post_id,'scottcart_item_quantity'.$q,$result[0]);
					$q++;
				}
			}
		}
		
		// update attribute count
		update_post_meta($post_id,'scottcart_attribute_count',$a);
		
		// update total quantity
		update_post_meta($post_id,'scottcart_total_quantity',$running_quantity);
		
		// post date
		$date = sanitize_text_field($_POST['scottcart_date'])." ".sanitize_text_field($_POST['scottcart_hh']).":".sanitize_text_field($_POST['scottcart_mm']).":00";
		
		// to avoid infinite loop
		remove_action('save_post','scottcart_save_meta_box_order');
		
		$order_post = array(
			'ID'			=> $post_id,
			'post_status'	=> sanitize_text_field($_POST['scottcart_status']),
			'post_title'	=> sanitize_text_field($_POST['scottcart_email']),
			'post_date'		=> $date,
			'post_content'	=> $total,
			'post_excerpt'	=> $i,
		);
		
		
		
		do_action('scottcart_order_save',$post_id);
		
		
		
		$result = wp_update_post($order_post);
		
		add_action('save_post','scottcart_save_meta_box_order');
	}	
}
add_action('save_post','scottcart_save_meta_box_order');