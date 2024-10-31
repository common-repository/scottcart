<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_cart() {

	// cart section
	if (isset($_SESSION['scottcart_cart']) && (!empty($_SESSION['scottcart_cart']))) {
	
		// hide entry-title
		if (scottcart_get_option('cart_plugin_heading') == '1') {
			echo "<style>.entry-title { display: none; }</style>";
		}
		
		echo "<style>.entry-content { width: unset !important; }</style>";
		
		// tax, shipping and discount code should be recaculated onload
		$_SESSION['scottcart_cart_tax_rate'] = 				null;
		$_SESSION['scottcart_cart_tax_shipping'] = 			null;
		$_SESSION['scottcart_cart_shipping'] = 				null;
		$_SESSION['scottcart_cart_discount'] = 				null;
		$_SESSION['scottcart_cart_country'] = 				null;
		$_SESSION['scottcart_cart_state'] = 				null;
		$_SESSION['scottcart_cart_physical_quantity'] =		null;
		
		
		// onload get values
		$totals = 			scottcart_cart_total(1);
		
		$subtotal = 		$totals['total_subamount'];
		$total_discount = 	$totals['total_discount'];
		$tax = 				$totals['total_tax'];
		$total = 			$totals['total_amount'];
		$shipping = 		$totals['total_shipping'];
		$physical_count = 	$totals['physical_count'];
		
		
		echo'<div class="scottcart_cart_container">';
			
			if (scottcart_get_option('cart_plugin_heading') == '1') {
				echo "<h3 class='scottcart_title'>"; echo scottcart_get_option('text_cart'); echo "</h3>";
			}
			
			echo "<form id='scottcart_cart_form' method='post'>";
			
			echo'<div class="scottcart_cart_top">';
				
				// cart header
				echo '<div class="scottcart_cart_row">';
					echo'<div class="scottcart_cart_left">';
						echo __('Product','scottcart');
					echo'</div>';
					
					if (scottcart_get_option('quantity_product_page') == "0") {
						echo'<div class="scottcart_cart_middle_left">';
							echo __('Price','scottcart');
						echo'</div>';
						
						echo'<div class="scottcart_cart_middle_right">';
							echo scottcart_get_option('text_8');
						echo'</div>';
					}
					
					echo'<div class="scottcart_cart_right">';
						echo __('Total','scottcart');
					echo'</div>';
				echo'</div>';
				
				// cart body
				echo "<div id='scottcart_cart_body'>";
					
					echo scottcart_get_cart_body_callback();
					
				echo'</div>';
				
				// cart totals
				echo'<div class="scottcart_cart_totals">';
					
					// cart subtotal
					echo '<div class="scottcart_cart_row">';
						echo'<div class="scottcart_cart_left_total">';
							echo __('Subtotal','scottcart'); echo ": ";
						echo'</div>';
						
						echo'<div class="scottcart_cart_right_total" id="scottcart_cart_subtotal">';
							echo sanitize_meta( 'currency_scottcart',$subtotal,'post');
						echo'</div>';
						
					echo'</div>';
					
					// cart discount
					if (scottcart_get_option('coupon') == "0") {
						echo '<div class="scottcart_cart_row">';
						
							echo'<div class="scottcart_cart_left_total" id="scottcart_cart_coupon_left">';
								echo __('Discount Code','scottcart'); echo": ";
							echo'</div>';
							
							if (!isset($_SESSION['scottcart_cart_discount'])) {
								echo'<div class="scottcart_cart_right_total" id="scottcart_cart_coupon_right">';
									echo "<input type='text' name='scottcart_coupon' id='scottcart_coupon' class='scottcart_input' autocomplete='off'>";
								echo '</div>';
							}
						echo '</div>';
					}
					
					// cart tax
					if (scottcart_get_option('tax') == "1") {
						echo '<div class="scottcart_cart_row">';
							echo'<div class="scottcart_cart_left_total">';
								echo __('Tax','scottcart'); echo ": ";
							echo'</div>';
							
							echo'<div class="scottcart_cart_right_total scottcart_tax_cart_amount">';
								echo sanitize_meta( 'currency_scottcart',$tax,'post');
							echo'</div>';
						echo'</div>';
					}
					
					// cart shipping
					if (scottcart_get_option('shipping') == "1") {
						echo '<div class="scottcart_cart_row">';
							echo'<div class="scottcart_cart_left_total">';
								echo __('Shipping','scottcart'); echo ": ";
							echo'</div>';
							
							echo'<div class="scottcart_cart_right_total scottcart_tax_shipping_amount">';
								echo sanitize_meta( 'currency_scottcart',$shipping,'post');
							echo'</div>';
						echo'</div>';
					}
					
					
					// cart total
					echo '<div class="scottcart_cart_row_end">';
						echo'<div class="scottcart_cart_left_total">';
							echo __('Total','scottcart'); echo ": ";
						echo'</div>';
						
						echo'<div class="scottcart_cart_right_total scottcart_cart_purchase_total">';
							echo $total = scottcart_sanitize_currency_meta($total,true);
						echo'</div>';
					echo'</div>';
					
					echo '<div class="scottcart_cart_after_cart_body_total">';
						do_action('scottcart_cart_after_cart_body_total');
					echo'</div>';
					
				echo "</div>"; // end cart totals
				
				
			echo "</div>"; // end cart top
			
			
			
			// order details
			echo "<div class='scottcart_cart_details'>";
				echo "<div class='scottcart_cart_details_left'>";
					echo "<h3>"; echo __('Order Details','scottcart'); echo "</h3>";
					
					// if user is logged in then get their name and email and fill in the fields for them
					if ( is_user_logged_in() ) {
						$current_user = wp_get_current_user();
						$scottcart_customer_first_name = $current_user->user_firstname;
						$scottcart_customer_last_name = $current_user->user_lastname;
						$scottcart_customer_user_email = $current_user->user_email;
					} else {
						$scottcart_customer_first_name = "";
						$scottcart_customer_last_name = "";
						$scottcart_customer_user_email = "";
					}
					
				if (scottcart_get_option('customer_name') != "2" || scottcart_get_option('customer_name') == "0") {
					
					if (scottcart_get_option('customer_name') != "2") {
							echo "<label for='scottcart_first_name'>"; echo __('First Name','scottcart'); echo "</label><br />";
							echo "<input name='first_name' data-msg='test' type='text' id='scottcart_first_name' autocomplete='off' class='scottcart_input scottcart_validate' value='$scottcart_customer_first_name'><br />";
						}
						if (scottcart_get_option('customer_name') == "0") {
							echo "<label for='scottcart_last_name'>"; echo __('Last Name','scottcart'); echo "</label><br />";
							echo "<input name='last_name' type='text' id='scottcart_last_name' autocomplete='off' class='scottcart_input scottcart_validate' value='$scottcart_customer_last_name'><br />";
						}
							
					echo "</div>";
					
					echo "<div class='scottcart_cart_details_middle'>";
					echo "</div>";
					
					echo "<div class='scottcart_cart_details_right'>";
					
					echo "<h3>&nbsp;</h3>";
				}
					
					echo "<label for='scottcart_email'>"; echo __('Email','scottcart'); echo "</label><br />";
					echo "<input name='email' type='text' id='scottcart_email' autocomplete='off' class='scottcart_input scottcart_validate' value='$scottcart_customer_user_email'><br />";
					
					echo "<label for='scottcart_email_again'>"; echo __('Email Again','scottcart'); echo "</label><br />";
					echo "<input name='email_again' type='text' id='scottcart_email_again' autocomplete='off' class='scottcart_input scottcart_validate' value='$scottcart_customer_user_email'><br />";
					
				echo "</div>";
			echo "</div><br />";
			
			
			// billing address
			echo "<div class='scottcart_cart_details'>";
				echo "<div class='scottcart_cart_details_left scottcart_billing_address'>";
				if (scottcart_get_option('caculate_tax') == "1" && scottcart_get_option('tax') == "1" || scottcart_get_option('billing_address') == "1") {
						echo "<h3>"; echo __('Billing Address','scottcart'); echo "</h3>";
						echo __('Address Name','scottcart'); echo ": <br /><input name='billing_name' type='text' id='scottcart_billing_name' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Address Line 1','scottcart'); echo ": <br /><input name='billing_line_1' type='text' id='scottcart_billing_line_1' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Address Line 2','scottcart'); echo ": <br /><input name='billing_line_2' type='text' id='scottcart_billing_line_2' autocomplete='off' class='scottcart_input' value=''><br />";
						echo __('Country','scottcart'); echo ": <br />";
						
						
						echo "<select name='billing_country' class='scottcart_input scottcart_country_list scottcart_validate' id='scottcart_billing_country' data-id='billing' autocomplete='off'>";
						$country_list = scottcart_get_country_list();
						foreach ($country_list as $country_id => $country) {
							echo "<option value='$country_id'>$country</option>";
						}
						echo "</select>";
						
						echo "<br />";
						echo __('State / Province','scottcart'); echo ": <br />";
						
						echo "<div id='scottcart_state_list_div_billing'>";
							echo "<select name='billing_state' class='scottcart_input scottcart_validate' id='scottcart_billing_state' data-id='billing' autocomplete='off'>";
							echo "</select>";
						echo "</div>";
						
						echo __('City','scottcart'); echo ": <br /><input name='billing_city' type='text' id='scottcart_billing_city' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Zip / Postal Code'); echo ": <br /><input name='billing_zip' type='text' id='scottcart_billing_zip' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
					
				echo "</div>";
				
				echo "<div class='scottcart_cart_details_middle'>";
				echo "</div>";
				
				
				
				
				// shipping address
				echo "<div class='scottcart_cart_details_right scottcart_shipping_address'>";
				}
					if (scottcart_get_option('shipping') == "1" && $physical_count > 0 || scottcart_get_option('caculate_tax') == "0" && scottcart_get_option('tax') == "1") {
						echo "<h3>"; echo __('Shipping Address','scottcart'); echo "</h3>";
						echo __('Address Name','scottcart'); echo ": <br /><input name='shipping_name' type='text' id='scottcart_shipping_name' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Address Line 1','scottcart'); echo ": <br /><input name='shipping_line_1' type='text' id='scottcart_shipping_line_1' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Address Line 2','scottcart'); echo ": <br /><input name='shipping_line_2' type='text' id='scottcart_shipping_line_2' autocomplete='off' class='scottcart_input' value=''><br />";
						echo __('Country','scottcart'); echo ": <br />";
						
						
						echo "<select name='shipping_country' class='scottcart_input scottcart_country_list scottcart_validate' id='scottcart_shipping_country' data-id='shipping' autocomplete='off'>";
						$country_list = scottcart_get_country_list();
						foreach ($country_list as $country_id => $country) {
							echo "<option value='$country_id'>$country</option>";
						}
						echo "</select>";
						
						echo "<br />";
						echo __('State / Province','scottcart'); echo ": <br />";
						
						echo "<div id='scottcart_state_list_div_shipping'>";
							echo "<select name='shipping_state' class='scottcart_input scottcart_validate' id='scottcart_shipping_state' data-id='shipping' autocomplete='off'>";
							echo "</select>";
						echo "</div>";
						
						echo __('City','scottcart'); echo ": <br /><input name='shipping_city' type='text' id='scottcart_shipping_city' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
						echo __('Zip / Postal Code'); echo ": <br /><input name='shipping_zip' type='text' id='scottcart_shipping_zip' autocomplete='off' class='scottcart_input scottcart_validate' value=''><br />";
					}
				echo "</div>";
			echo "</div>"; // end cart details
			
			
			
			// shipping type section
			if (scottcart_get_option('shipping') == "1" && $physical_count > 0) {
				echo "<div class='scottcart_cart_details scottcart_shipping_details'>";
					echo "<h3>"; echo __('Shipping Details','scottcart'); echo "</h3>";
					echo "<div id='scottcart_cart_details_div' class='scottcart_cart_details_shipping_div'>";
						echo __('Enter your shipping address to get shipping options.','scottcart');
					echo "</div>";
				echo "<br /></div>"; // end shipping type section
			}
			
			
			
			
			
			
		// payment section


		// hidden fields
		echo "<input type='hidden' id='purchase_ip' value='"; echo scottcart_get_the_user_ip(); echo "'>";
		echo "<input type='hidden' id='scottcart_refresh' value='no'>";
		
		
		// create nonce
		$ajax_nonce = wp_create_nonce('scottcart_cart_nonce');
		echo "<input type='hidden' name='scottcart_cart_nonce' id='scottcart_cart_nonce' value='$ajax_nonce'>";
			
			//paid download
			echo "<div id='scottcart_payment_container'>";
				if ($totals['total_amount'] == scottcart_sanitize_currency_meta('0',false)) { } else {
				echo "<h3>"; echo __('Payment Details','scottcart'); echo "</h3>";
				
				echo "<div id='scottcart_gateway_payment_methods_container'>";
					echo scottcart_load_gateways(2);
				echo "</div>";
				
				echo "<div id='scottcart_gateway_container'>";
					echo scottcart_load_gateways(3);
				echo "</div>";
				}
			echo "</div>"; // end payment container
			
			do_action('scottcart_cart_before_purchase_button',$total);
			
			echo "<div class='scottcart_purchase_button_div'>";
				echo "<br /><br />";
				echo "<input type='submit' id='scottcart_purchase_button' class='scottcart_submit' style='background-color:"; echo scottcart_get_option('button_color'); echo "; color:"; echo scottcart_get_option('button_text_color'); echo ";'value='"; echo scottcart_get_option('text_3'); echo"'>";
			echo "</div>";
			
		echo '</form>';
		
		echo "</div>"; //  end cart container
		
	} else {
		echo "<h3>";
		echo __('Your cart is empty.','scottcart');
		echo "</h3><br />";
		
		do_action('scottcart_emtpy_cart');
	}
}