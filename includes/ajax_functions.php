<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// start session
function scottcart_start_session() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'scottcart_start_session', 1);



// caculate cart total, items, and discounts
// 0 for items
// 1 for totals (after discount)
// 2 for discount code
function scottcart_cart_total($input = "0") {

	if (isset($_SESSION['scottcart_cart'])) {
		
		// caculate total
		$quantity_value = 0;
		$price = 0;
		$total = 0;
		$result = [];
		$physical_count = 0;
		
		foreach ($_SESSION['scottcart_cart'] as $cart_item) {
			
			// physical
			if ($cart_item['type'] == 0) {
				$price = get_post_meta($cart_item['id'],'scottcart_physical_price'.$cart_item['price_id'], true);
				$id = $cart_item['id'];
				$variation = $cart_item['price_id'];
				$attribute = $cart_item['attribute_id'];
				$price = $price * $cart_item['quantity'];
				$total .= $price;
				$quantity_value++;
				$physical_count += $cart_item['quantity'];
			}
			
			// digital
			if ($cart_item['type'] == 1) {
				$price = get_post_meta($cart_item['id'],'scottcart_digital_price'.$cart_item['price_id'], true);
				$id = $cart_item['id'];
				$variation = $cart_item['price_id'];
				$price = $price * $cart_item['quantity'];
				$total = $total + $price;
				$quantity_value++;
			}
			
			// service
			if ($cart_item['type'] == 2) {
				$price = get_post_meta($cart_item['id'],'scottcart_service_price'.$cart_item['price_id'], true);
				$id = $cart_item['id'];
				$variation = $cart_item['price_id'];
				$price = $price * $cart_item['quantity'];
				$total = $total + $price;
				$quantity_value++;
			}
			
			// assign values to array values
			if ($input == "0") {
				$result['item_'.$quantity_value]['id'] = 			$id;
				$result['item_'.$quantity_value]['variation'] = 	$variation;
				if (isset($attribute)) {
					$result['item_'.$quantity_value]['attribute'] = $attribute;
				}
				$result['item_'.$quantity_value]['type'] = 			$cart_item['type'];
				$result['item_'.$quantity_value]['quantity'] = 		$cart_item['quantity'];
				$result['item_'.$quantity_value]['price'] = 		$price;
			}
		}
		
		
		// set total = subtotal before discount, tax and shipping caculations start
		$subtotal = $total;
		
		
		// caculate discounts
		if ($input == "1") {
			$discount_total = scottcart_sanitize_currency_meta('0',false);
			if (isset($_SESSION['scottcart_cart_discount'])) {
				$type = 	sanitize_text_field($_SESSION['scottcart_cart_discount'][0]['type']);
				$amount = 	sanitize_text_field($_SESSION['scottcart_cart_discount'][0]['amount']);
				
				// rate
				if ($type == '0') {
					$discount_total = $total * ($amount / 100);
					$total = $total - $discount_total;
				}
				
				// fixed
				if ($type == '1') {
					$total = $total - $amount;
					$discount_total = $amount;
				}
			}
		}
		
		
		// totals
		if ($input == "1") {
			if ($total <= "0" || $total == "") { $total = scottcart_sanitize_currency_meta('0',false); }
			
			
			// caculate tax
			if ($input == "1" && isset($_SESSION['scottcart_cart_tax_rate']) && $_SESSION['scottcart_cart_tax_shipping'] == null) {
				$tax_amount = $total * (sanitize_text_field($_SESSION['scottcart_cart_tax_rate']) / 100);
				if (empty($tax_amount)) { $tax_amount = scottcart_sanitize_currency_meta('0',false); }
				$total = $total + $tax_amount;
			} else {
				$tax_amount = scottcart_sanitize_currency_meta('0',false);
			}
			
			// caculate shipping
			if ($input == "1" && isset($_SESSION['scottcart_cart_shipping'])) {
				$shipping_amount = sanitize_text_field($_SESSION['scottcart_cart_shipping']);
				if (empty($shipping_amount)) { $shipping_amount = scottcart_sanitize_currency_meta('0',false); }
				$total = $total + $shipping_amount;
				if ($total <= "0" || $total == "") { $tax_amount = scottcart_sanitize_currency_meta('0',false); }
			} else {
				$shipping_amount = scottcart_sanitize_currency_meta('0',false);
			}
			
			// tax shipping - if setting is on
			if ($input == "1" && $_SESSION['scottcart_cart_tax_shipping'] != null) {
				//if ($_SESSION['scottcart_cart_tax_shipping'] == '0') {
					$tax_amount = $total * (sanitize_text_field($_SESSION['scottcart_cart_tax_rate']) / 100);
					if (empty($tax_amount)) { $tax_amount = scottcart_sanitize_currency_meta('0',false); }
					$total = $total + $tax_amount;
				//}
			}
			
			
			$result['physical_count'] = 	$physical_count;
			$result['total_subamount'] = 	$subtotal;
			$result['total_discount'] = 	$discount_total;
			$result['total_items'] = 		$quantity_value;
			$result['total_tax'] = 			$tax_amount;
			$result['total_shipping'] = 	$shipping_amount;
			$result['total_amount'] = 		$total;
		}
		
		if ($input == "2") {
			if (isset($_SESSION['scottcart_cart_discount'])) {
				$result['coupon'] = sanitize_text_field($_SESSION['scottcart_cart_discount'][0]['code']);
			}
		}
		
		return $result;
		
	}
}

// get cart body
function scottcart_get_cart_body_callback() {

	$count = "0";
	$total = "";
	$result = '';
	
	foreach ($_SESSION['scottcart_cart'] as $cart_item) {
		
		if ($cart_item['type'] == "0") { $scottcart_type_name = "physical"; }
		if ($cart_item['type'] == "1") { $scottcart_type_name = "digital"; }
		if ($cart_item['type'] == "2") { $scottcart_type_name = "service"; }
		
		$price = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_price".$cart_item['price_id'], true);
		$name = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_name".$cart_item['price_id'], true);
		
		if ($cart_item['attribute_id'] >= "0") {
			$attribute_name = get_post_meta($cart_item['id'],"scottcart_{$scottcart_type_name}_attribute_name".$cart_item['attribute_id'], true);
		}
		
		if (empty($price)) { $price = sanitize_meta( 'currency_scottcart','0','post'); }
		
		$result .= '<div class="scottcart_cart_row scottcart_alternate">';
			
			$result .= '<div class="scottcart_cart_left">';
				$slug = get_post_field('post_name',$cart_item['id']);
				$product_slug = scottcart_get_slug_by_post_type('scottcart_product');
				$url = get_site_url()."/".$product_slug."/".$slug;
				$result .= "<a href='$url'>";
					$result .= get_the_title($cart_item['id']);
					$result .= " - ";
					$result .= $name;
					
					if ($cart_item['attribute_id'] >= "0") {
						$result .= " - ";
						$result .= $attribute_name;
					}
					
				$result .= '</a>';
				
				$result .= "<a href='#' data-id='$count' class='scottcart_cart_item_remove'>";
					$trash_img_path = WP_PLUGIN_URL."/".SCOTTCART_SLUG."/assets/images/trash.png";
					$result .= "&nbsp; <img src='$trash_img_path' alt='trash' element='remove'>";
				$result .= "</a>";
				
			$result .= '</div>';
			
			if (scottcart_get_option('quantity_product_page') == "0") {
				$result .= '<div class="scottcart_cart_middle_left">';
					$result .= sanitize_meta( 'currency_scottcart',$price,'post');
				$result .= '</div>';
				
				$result .= '<div class="scottcart_cart_middle_right">';
					$result .= "<input min='0' step='1' data-id='$count' name='scottcart_quantity' autocomplete='off' class='scottcart_quantity' element='quantity' value='"; $result .= $cart_item['quantity']; $result .= "' type='number'>";
				$result .= '</div>';
			}
			
			$result .= '<div class="scottcart_cart_right">';
				$price = $price * $cart_item['quantity'];
				$result .= sanitize_meta( 'currency_scottcart',$price,'post');
				$total .= $price;
			$result .= '</div>';
			
		$result .= '</div>';
		
		$count++;
	}
	
	if ($count == "0") {
		$result .= "empty";
	}
	
	return $result;

}
add_action( 'wp_ajax_scottcart_get_cart_body', 'scottcart_get_cart_body_callback' );
add_action( 'wp_ajax_nopriv_scottcart_get_cart_body', 'scottcart_get_cart_body_callback' );


// refresh and return cart values
function scottcart_return_cart_values(
	$cart_body 			= null,
	$shipping_body 		= null,
	$gateway_body 		= null,
	$country_id 		= null,
	$state_id 			= null,
	$element 			= null,
	$state_list			= null
	) {


	// caculate tax
	if ($element == 'shipping') {
		$element_id = '0';
	} elseif ($element == 'billing') {
		$element_id = '1';
	} else {
		$element_id = '';
	}
	
	// determine if tax should be caculated
	// - tax setting must be enabled
	// - cannot caculate tax without atleast country_id
	// - how to caculate tax setting must match
	if (scottcart_get_option('tax') == "1" && $country_id != null && scottcart_get_option('caculate_tax') == $element_id) {
		scottcart_caculate_tax($country_id,$state_id);
		$_SESSION['scottcart_cart_caculate_tax_method'] = $element_id;
	}

	// refresh values
	$totals = 			scottcart_cart_total(1);
	$subtotal = 		$totals['total_subamount'];
	$total_discount = 	$totals['total_discount'];
	$tax = 				$totals['total_tax'];
	$shipping = 		$totals['total_shipping'];
	$total = 			$totals['total_amount'];
	$physical_count = 	$totals['physical_count'];
	
	
	// return cart discount
	$discount_left = __('Discount Code','scottcart').": ";
	$discount_right = "<input type='text' name='scottcart_coupon' id='scottcart_coupon' class='scottcart_input' autocomplete='off'>";
	if (isset($_SESSION['scottcart_cart_discount'])) {
	
		$trash_img_path = WP_PLUGIN_URL."/".SCOTTCART_SLUG."/assets/images/trash.png";
		
		$discount_left = sanitize_text_field($_SESSION['scottcart_cart_discount'][0]['code'])."<a href='#' id='scottcart_discount_remove'><img src='$trash_img_path' alt='trash'></span></a>";
		$discount_right = "-".sanitize_meta( 'currency_scottcart',$total_discount,'post');
	}
	
	// return cart body
	if ($cart_body == true) {
		$body_result = scottcart_get_cart_body_callback();
	} else {
		$body_result = '';
	}
	
	// return shipping body
	$shipping_body_result = '';
	if ($shipping_body != null) {
		if ($element == 'shipping') {
			if (scottcart_get_option('shipping') == "1" && $country_id != null) {
				$shipping_body_result = scottcart_get_shipping_rate($country_id,$state_id);
			}
		} else {
			$shipping_body_result = __('Enter your shipping address to get shipping options.','scottcart');
		}
	}
	
	// if free then remove payment method section of cart page
	$free = '';
	if ($total <= "0" || $total <= sanitize_meta('currency_scottcart','0','post')) {
		$free = "free";
	}
	
	// if paid
	$gateway_body_header_result = '';
	if ($gateway_body != null) {
		if ($total >= "0" || $total >= sanitize_meta( 'currency_scottcart','0','post')) {
			$gateway_body_header_result .= scottcart_load_gateways(2);
		}
	}
	
	$gateway_body_result = '';
	if ($gateway_body != null) {
		if ($total >= "0" || $total >= sanitize_meta( 'currency_scottcart','0','post')) {
			$gateway_body_result = scottcart_load_gateways(3);
		}
	}
	
	// physical refresh needed
	$reload = '';
	if ($physical_count < 1) {
		$reload = "reload";
	}
	
	// state / province list
	$state_list_body = '';
	if ($state_list != null) {
		$state_list_array = scottcart_get_shop_states($country_id);
		
		if (!empty($state_list_array)) {
			$state_list_body .= "<select class='scottcart_input scottcart_state_list' id='scottcart_".$element."_state' data-id='$country_id' element='$element'>";
				foreach ($state_list_array as $state_id => $state) {
					$state_list_body .= "<option value='$state_id'>$state</option>";
				}
			$state_list_body .="</select>";
		} else {
			$state_list_body .= "<input type='text' class='scottcart_input scottcart_state_list' id='scottcart_".$element."_state' value='' data-id='$country_id' element='$element'>";
		}
	}

	$response = array(
		'subtotal'         		=> sanitize_meta('currency_scottcart',$subtotal,'post'),
		'discount_left'     	=> $discount_left,
		'discount_right'        => $discount_right,
		'tax'         			=> sanitize_meta('currency_scottcart',$tax,'post'),
		'shipping'         		=> sanitize_meta('currency_scottcart',$shipping,'post'),
		'total'         		=> sanitize_meta('currency_scottcart',$total,'post'),
		'body'         			=> $body_result,
		'shipping_body'     	=> $shipping_body_result,
		'free'     				=> $free,
		'gateway_body_header' 	=> $gateway_body_header_result,
		'gateway_body'     		=> $gateway_body_result,
		'reload'     			=> $reload,
		'state_list'     		=> $state_list_body,
	);

	echo json_encode($response);
	
	wp_die();

}


// apply coupon
function scottcart_check_coupon_callback() {

	$code =		sanitize_text_field($_POST['code']);
	$nonce =	sanitize_text_field($_POST['nonce']);
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }

	$args = array(
		'orderby' 			=> 'ID',
		'order' 			=> 'DESC',
		'posts_per_page'	=> -1,
		'post_status'		=> 'active',
		'post_type' 		=> 'scottcart_discount',
		'meta_query' 		=> array(
			'relation'=>'or',
			array(
				'key' 		=> 'scottcart_code',
				'value' 	=> $code,
				'compare' 	=> '=',
		   )
		)
	);

	$posts = get_posts($args);
	
	if (isset($posts[0])) {
		
		// check dates
		$today = date("Y-m-d");
		$from_date = get_post_meta($posts[0]->ID,'scottcart_date_from', true);
		$to_date = get_post_meta($posts[0]->ID,'scottcart_date_to', true);
		
		// if either date condition is false, then it will return false - user can enter from and end or from or end
		
		$result = '';
		
		// from date
		if (!empty($from_date)) {
			if ($from_date > $today) {
				$result = "false";
			}
		}
		
		// to date
		if (!empty($to_date)) {
			if ($to_date < $today) {
				$result = "false";
			}
		}
		
		$scottcart_amount = "";
		if (!empty($posts)) {
			
			$scottcart_type = get_post_meta($posts[0]->ID,'scottcart_type', true);
			$scottcart_amount = get_post_meta($posts[0]->ID,'scottcart_amount', true);
			
			$scottcart_amount = scottcart_sanitize_currency_meta($scottcart_amount,false);
			if (!empty($scottcart_amount)) {
				$_SESSION['scottcart_cart_discount'][] = array('code' => $code,'type' => $scottcart_type, 'amount' => $scottcart_amount);
			}
			
		} else {
			$result = "false";
		}
		
		
		if ($result == 'false') {
			
			$response = array(
				'false_result' => 'false',
			);
			
			echo json_encode($response);
			
			wp_die();
			
		}
		
		
		// refresh and return values
		scottcart_return_cart_values(null,null,true);
		
	} else {
		$response = array(
			'false_result' => 'false',
		);
		
		echo json_encode($response);
		
		wp_die();
	}
	
	wp_die();

}
add_action( 'wp_ajax_scottcart_check_coupon', 'scottcart_check_coupon_callback' );
add_action( 'wp_ajax_nopriv_scottcart_check_coupon', 'scottcart_check_coupon_callback' );


// remove discount
function scottcart_coupon_remove_callback() {

	$nonce = sanitize_text_field($_POST['nonce']);

	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	// remove discount
	$_SESSION['scottcart_cart_discount'] = NULL;
	
	// refresh and return values
	scottcart_return_cart_values(null,null,true);
	
}
add_action( 'wp_ajax_scottcart_coupon_remove', 'scottcart_coupon_remove_callback' );
add_action( 'wp_ajax_nopriv_scottcart_coupon_remove', 'scottcart_coupon_remove_callback' );


// update cart
function scottcart_update_cart_callback() {
	
	if (isset($_POST['element'])) {
		$element = 	sanitize_text_field($_POST['element']);
	}
	if (isset($_POST['nonce'])) {
		$nonce = 	sanitize_text_field($_POST['nonce']);
	}
	if (isset($_POST['id'])) {
		$id = 		intval($_POST['id']);
	}
	if (isset($_POST['quantity'])) {
		$quantity = sanitize_text_field($_POST['quantity']);
	}
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( "<span style='color: red;'>".__('Error - Nonce validation failed.','scottcart')."</span>" ); }
	
	// remove item from cart
	if ($element == 'remove') {
		
		// unset shipping - this is useful if there are two products in the cart, one physical and one digital, and the physical one is removed
		$_SESSION['scottcart_cart_shipping'] = null;
		$_SESSION['scottcart_cart_country'] = null;
		$_SESSION['scottcart_cart_state'] = null;
		
		unset($_SESSION['scottcart_cart'][$id]);
	}
	
	// change quantity
	if ($element == 'quantity') {
		if ($quantity == "0") {
			unset($_SESSION['scottcart_cart'][$id]);
		} else {
			$_SESSION['scottcart_cart'][$id]['quantity'] = $quantity;
		}
	}
	
	if (isset($_SESSION['scottcart_cart_country'])) {
		$country_id = 	sanitize_text_field($_SESSION['scottcart_cart_country']);
		$state_id = 	sanitize_text_field($_SESSION['scottcart_cart_state']);
	} else {
		$country_id = '';
		$state_id = '';
	}
	
	// get first shipping rate
	scottcart_get_shipping_rate_first($country_id,$state_id);
	
	// renumber array keys
	$_SESSION['scottcart_cart'] = array_values($_SESSION['scottcart_cart']);
	
	// return values
	scottcart_return_cart_values(true,true,true,$country_id,$state_id,'shipping');
	
	wp_die();

}
add_action('wp_ajax_scottcart_update_cart','scottcart_update_cart_callback');
add_action('wp_ajax_nopriv_scottcart_update_cart','scottcart_update_cart_callback');



// add item to the cart
function scottcart_add_item_to_cart_callback() {
    
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
	} else {
		$id = '';
	}
	
	$type = get_post_meta(intval($_POST['id']),'scottcart_type', true);
	
	if (isset($_POST['price_id'])) {
		$price_id = intval($_POST['price_id']);
	} else {
		$price_id = '';
	}
	
	if (isset($_POST['nonce'])) {
		$nonce = sanitize_text_field($_POST['nonce']);
	} else {
		$nonce = '';
	}
	
	if (isset($_POST['attribute_id'])) {
		$attribute_id = intval($_POST['attribute_id']);
	} else {
		$attribute_id = '';
	}
	
	if (isset($_POST['quantity'])) {
		$quantity = intval($_POST['quantity']);
	} else {
		$quantity = '';
	}
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce_'.$id)) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	$_SESSION['scottcart_cart'][] = array('id' => $id, 'type' => $type, 'price_id' => $price_id, 'attribute_id' => $attribute_id, 'quantity' => $quantity);
	
	if (scottcart_get_option('redirect') == "1") {
		echo "redirect";
	} else {
		echo scottcart_get_option('text_1');
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_add_item_to_cart', 'scottcart_add_item_to_cart_callback' );
add_action( 'wp_ajax_nopriv_scottcart_add_item_to_cart', 'scottcart_add_item_to_cart_callback' );


// get state list, tax, shipping for country id in cart - for cart page
function scottcart_get_state_list_callback() {
    
	$nonce = 			sanitize_text_field($_POST['nonce']);
	$country_id = 		sanitize_text_field($_POST['country_id']);
	$element = 			sanitize_text_field($_POST['element']);
	$state_id =			'';

	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	// get first shipping rate
	if ($element == 'shipping') {
		scottcart_get_shipping_rate_first($country_id,$state_id);
	}
	
	// refresh and return values
	scottcart_return_cart_values(null,true,null,$country_id,null,$element,true);
	
}
add_action( 'wp_ajax_scottcart_get_state_list', 'scottcart_get_state_list_callback' );
add_action( 'wp_ajax_nopriv_scottcart_get_state_list', 'scottcart_get_state_list_callback' );




// get state list details, tax, shipping for country id in cart - for cart page
function scottcart_get_state_details_callback() {
    
	$nonce = 			sanitize_text_field($_POST['nonce']);
	$country_id = 		sanitize_text_field($_POST['country_id']);
	$state_id = 		sanitize_text_field($_POST['state_id']);
	$element = 			sanitize_text_field($_POST['element']);
	
	if (empty($state_id)) { $state_id = ''; }

	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	// get first shipping rate
	if ($element == 'shipping') {
		scottcart_get_shipping_rate_first($country_id,$state_id);
	}
	
	// refresh and return values
	scottcart_return_cart_values(null,true,null,$country_id,$state_id,$element);
	
}
add_action( 'wp_ajax_scottcart_get_state_details', 'scottcart_get_state_details_callback' );
add_action( 'wp_ajax_nopriv_scottcart_get_state_details', 'scottcart_get_state_details_callback' );





// load function
function scottcart_load_function_callback() {
	
	$function = sanitize_text_field($_POST['function']);
	
	// only run function is it exists
	if (function_exists($function)) {
		echo call_user_func($function);
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_load_function', 'scottcart_load_function_callback' );
add_action( 'wp_ajax_nopriv_scottcart_load_function', 'scottcart_load_function_callback' );


// set shipping rate
function scottcart_set_shipping_rate_callback() {
    
	$nonce =  sanitize_text_field($_POST['nonce']);
	$amount = sanitize_text_field($_POST['amount']);
	
	$country_id = sanitize_text_field($_SESSION['scottcart_cart_country']);
	$state_id =   sanitize_text_field($_SESSION['scottcart_cart_state']);
	
	$element_id = scottcart_get_option('caculate_tax');
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce')) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	// set shipping rate
	$_SESSION['scottcart_cart_shipping'] = $amount;
	
	// refresh and return values
	scottcart_return_cart_values(null,null,null,$country_id,$state_id,$element_id);
	
}
add_action( 'wp_ajax_scottcart_set_shipping_rate', 'scottcart_set_shipping_rate_callback' );
add_action( 'wp_ajax_nopriv_scottcart_set_shipping_rate', 'scottcart_set_shipping_rate_callback' );


// caculate tax
function scottcart_caculate_tax($country_id,$state_id, $fallback = null) {
	global $scottcart_options;
	
	$country_id = strtolower($country_id);
	$state_id = strtolower($state_id);
	
	$_SESSION['scottcart_cart_tax_rate'] = null;
	$_SESSION['scottcart_cart_tax_shipping'] = null;
	
	if (empty($state_id)) {
		$state_id = '';
	}
	
	$tax_shipping = '';
	
	if ($fallback != true) {
		
		for($i=0;$i<$scottcart_options['tax_count'];$i++) {
			
			// tax entire country
			if (isset($scottcart_options['tax_entire'][$i])) {
				if ($scottcart_options['tax_entire'][$i] == $i && $scottcart_options['tax_entire'][$i] != null && $country_id == strtolower($scottcart_options['tax_country'][$i])) {
					$tax_rate = $scottcart_options['tax_rate'][$i];
					$tax_shipping = $scottcart_options['tax_shipping'][$i];
				}
			}
			
			// tax county with states / provinces
			if ($country_id == strtolower($scottcart_options['tax_country'][$i]) && $state_id == strtolower($scottcart_options['tax_state'][$i])) {
				$tax_rate = $scottcart_options['tax_rate'][$i];
				if (isset($scottcart_options['tax_shipping'][$i])) {
					$tax_shipping = $scottcart_options['tax_shipping'][$i];
				}
				
			}
		}
		
	}
	
	// fall back tax
	if (!isset($tax_rate) && !empty($scottcart_options['fallback_tax_rate'])) {
		$tax_rate = $scottcart_options['fallback_tax_rate'];
	}
	
	$_SESSION['scottcart_cart_tax_rate'] = $tax_rate;
	$_SESSION['scottcart_cart_tax_shipping'] = $tax_shipping;
}



// get shipping first rate
function scottcart_get_shipping_rate_first($country_id,$state_id) {

	global $scottcart_options;
	
	$country_id = strtolower($country_id);
	$state_id = strtolower($state_id);
	
	if (!empty($country_id)) {
		
		$_SESSION['scottcart_cart_shipping'] = null;
		
		
		// if additional item charge is set, then caculate numbe of physical items
		$physical_count = '';
		foreach ($_SESSION['scottcart_cart'] as $cart_item) {
			// physical
			if ($cart_item['type'] == 0) {
				$physical_count += $cart_item['quantity'];
			}
		}
		
		// decrement quantity count since we dont want to add the additional charege unless it is needed
		$real_count = $physical_count;
		if ($physical_count > 1) {
			$physical_count--;
		}
		
		
		$count = "1";
		$no_country = '';
		for($i=0;$i<$scottcart_options['shipping_count'];$i++) {
			
			// shipping entire country
			if (isset($scottcart_options['shipping_rate'][$count]['0'])) {
				if (isset($scottcart_options['shipping_entire'][$i])) {
					if ($scottcart_options['shipping_entire'][$i] == $i && $scottcart_options['shipping_entire'][$i] != null && $country_id == strtolower($scottcart_options['shipping_country'][$i])) {
						$_SESSION['scottcart_cart_shipping'] = $scottcart_options['shipping_rate'][$count][$i];
						
						// additional item charge
						if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
							$_SESSION['scottcart_cart_shipping'] = $_SESSION['scottcart_cart_shipping'] + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
						}
					}
				}
			}
			
			// shipping county with states / provinces
			if (isset($scottcart_options['shipping_rate'][$count]['0'])) {
				if ($country_id == strtolower($scottcart_options['shipping_country'][$i]) && $state_id == strtolower($scottcart_options['shipping_state'][$i])) {
					$_SESSION['scottcart_cart_shipping'] = $scottcart_options['shipping_rate'][$count]['0'];
					
					// additional item charge
					if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
						$_SESSION['scottcart_cart_shipping'] = $_SESSION['scottcart_cart_shipping'] + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
					}
				}
			}
			
			// worldwide
			if (isset($scottcart_options['shipping_rate'][$count]['0']) && empty($_SESSION['scottcart_cart_shipping'])) {
				if ('worldwide' == strtolower($scottcart_options['shipping_country'][$i])) {
					$_SESSION['scottcart_cart_shipping'] = $scottcart_options['shipping_rate'][$count]['0'];
					
					// additional item charge
					if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
						$_SESSION['scottcart_cart_shipping'] = $_SESSION['scottcart_cart_shipping'] + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
					}
				}
			}
			
			$count++;
		}
		
	}
}


// get shipping rates body
function scottcart_get_shipping_rate($country_id,$state_id) {
	
	global $scottcart_options;

	$country_id = strtolower($country_id);
	$state_id = strtolower($state_id);
	
	// assign country and state to session - it could be needed later if the quantity in the cart is updated
	$_SESSION['scottcart_cart_country'] = $country_id;
	$_SESSION['scottcart_cart_state'] = $state_id;
	
	// if additional item charge is set, then caculate numbe of physical items
	$physical_count = '';
	foreach ($_SESSION['scottcart_cart'] as $cart_item) {
		// physical
		if ($cart_item['type'] == 0) {
			$physical_count += $cart_item['quantity'];
		}
	}
	
	// decrement quantity count since we dont want to add the additional charege unless it is needed
	$real_count = $physical_count;
	if ($physical_count > 1) {
		$physical_count--;
	}
	
	$result = '';
	
	$count = "1";
	$no_country = '';
	$rates = '';
	for($i=0;$i<$scottcart_options['shipping_count'];$i++) {
		
		// shipping entire country
		if (isset($scottcart_options['shipping_rate'][$count]['0'])) {
			if (isset($scottcart_options['shipping_entire'][$i])) {
				if ($scottcart_options['shipping_entire'][$i] == $i && $scottcart_options['shipping_entire'][$i] != null && $country_id == strtolower($scottcart_options['shipping_country'][$i])) {
					
					$result .= "<div class='scottcart_shipping_left'></div><div class='scottcart_shipping_middle'>"; $result .= __('Type','scottcart'); $result .= "</div><div class='scottcart_shipping_right'>"; $result .= __('Price','scottcart'); $result .= "</div>";
					for($i=0;$i<$scottcart_options['shipping_count'.$count];$i++) {
						
						$rate = $scottcart_options['shipping_rate'][$count][$i];
						
						// additional item charge
						if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
							$rate = $rate + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
						}
						
						$result .= "<div data-id='".$rate."' class='scottcart_alternate'"; if ($i == "0") { $result .= " scottcart_cart_shipping_selected"; } $result .= ">";
							$result .= "<div class='scottcart_shipping_left'><input class='scottcart_shipping_radio_button' name='scottcart_shipping_method'"; if ($i == "0") { $result .= "checked='checked'"; } $result .= "value='0' type='radio'></div>";
							
							$result .= "<div class='scottcart_shipping_middle'>".$scottcart_options['shipping_types_name'][$scottcart_options['shipping_type'][$count][$i]];
							
							$result .= "<br/>";
							$desc = $scottcart_options['shipping_types_desc'][$scottcart_options['shipping_type'][$count][$i]];
							if ($desc) {
								$result .= "(".$desc.")";
							}
							$result .= "</div>";
							
							$result .= "<div class='scottcart_shipping_right'>"; $result .= scottcart_sanitize_currency_meta($rate); $result .= "</div>";
							
						$result .= "</div>";
					}
					
					$rates = "1";
					
				}
			}
		}
		
		// shipping county with states / provinces 
		if (isset($scottcart_options['shipping_rate'][$count]['0'])) {
			if ($country_id == strtolower($scottcart_options['shipping_country'][$i]) && $state_id == strtolower($scottcart_options['shipping_state'][$i])) {
				
				$result .= "<div class='scottcart_shipping_left'></div><div class='scottcart_shipping_middle'>"; $result .= __('Type','scottcart'); $result .= "</div><div class='scottcart_shipping_right'>"; $result .= __('Price','scottcart'); $result .= "</div>";
				for($i=0;$i<$scottcart_options['shipping_count'.$count];$i++) {
					
					$rate = $scottcart_options['shipping_rate'][$count][$i];
					
					// additional item charge
					if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
						$rate = $rate + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
					}
					
					$result .= "<div data-id='".$rate."' class='scottcart_alternate"; if ($i == "0") { $result .= " scottcart_cart_shipping_selected"; } $result .= "'>";
						$result .= "<div class='scottcart_shipping_left'><input class='scottcart_shipping_radio_button' name='scottcart_shipping_method'"; if ($i == "0") { $result .= "checked='checked'"; } $result .= "value='0' type='radio'></div>";
						
						$result .= "<div class='scottcart_shipping_middle'>".$scottcart_options['shipping_types_name'][$scottcart_options['shipping_type'][$count][$i]];
						
						$result .= "<br/>";
						$desc = $scottcart_options['shipping_types_desc'][$scottcart_options['shipping_type'][$count][$i]];
						if ($desc) {
							$result .= "(".$desc.")";
						}
						$result .= "</div>";
						
						$result .= "<div class='scottcart_shipping_right'>"; $result .= scottcart_sanitize_currency_meta($rate); $result .= "</div>";
						
					$result .= "</div>";
				}
				
				$rates = "1";
			}
		}
		
		$count++;
		
		if ($country_id == strtolower($scottcart_options['shipping_country'][$i])) {
			$no_country = '1';
		}
		
	}
	
	// worldwide shipping rate
	$count = "1";
	if ($rates != "1") {
		for($i=0;$i<$scottcart_options['shipping_count'];$i++) {
			
			if (isset($scottcart_options['shipping_rate'][$count]['0'])) {
				if ('worldwide' == $scottcart_options['shipping_country'][$i]) {
					
					$result .= "<div class='scottcart_shipping_left'></div><div class='scottcart_shipping_middle'>"; $result .= __('Type','scottcart'); $result .= "</div><div class='scottcart_shipping_right'>"; $result .= __('Price','scottcart'); $result .= "</div>";
					for($i=0;$i<$scottcart_options['shipping_count'.$count];$i++) {
						
						$rate = $scottcart_options['shipping_rate'][$count][$i];
						
						// additional item charge
						if (isset($scottcart_options['shipping_rate_additional'][$count][$i]) && $physical_count >= 1 && $real_count >= 2) {
							$rate = $rate + ($scottcart_options['shipping_rate_additional'][$count][$i] * $physical_count);
						}
						
						$result .= "<div data-id='".$rate."' class='scottcart_alternate"; if ($i == "0") { $result .= " scottcart_cart_shipping_selected"; } $result .= "'>";
							$result .= "<div class='scottcart_shipping_left'><input class='scottcart_shipping_radio_button' name='scottcart_shipping_method'"; if ($i == "0") { $result .= "checked='checked'"; } $result .= "value='0' type='radio'></div>";
							
							$result .= "<div class='scottcart_shipping_middle'>".$scottcart_options['shipping_types_name'][$scottcart_options['shipping_type'][$count][$i]];
							$result .= "<br/>(".$scottcart_options['shipping_types_desc'][$scottcart_options['shipping_type'][$count][$i]].")</div>";
							
							$result .= "<div class='scottcart_shipping_right'>"; $result .= scottcart_sanitize_currency_meta($rate); $result .= "</div>";
							
						$result .= "</div>";
					}
					
					$rates = "1";
				}
			}
			
			$count++;
		}
	}	

	
	if ($rates != "1" && $no_country != '1' || $rates != "1" && $no_country == '1' && !empty($state_id)) {
		$result .= __('No shipping options available for your location.','scottcart');
	}
	
	if ($rates != "1" && $no_country == '1' && empty($state_id)) {
		$result .= __('Please enter your States / Province to get shipping options.','scottcart');
	}
	
	return $result;
}


// update account profile 
function scottcart_account_profile_callback() {

	$user_id = 			intval($_POST['id']);
	$user_firstname = 	sanitize_text_field($_POST['fname']);
	$user_lastname = 	sanitize_text_field($_POST['lname']);
	$nonce = 			sanitize_text_field($_POST['nonce']);
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_update_user_account_'.$user_id)) { die( "<span style='color: red;'>".__('Error - Nonce validation failed.','scottcart')."</span>" ); }

	$result = wp_update_user(array(
		'ID' 				=> $user_id,
		'first_name' 		=> $user_firstname,
		'last_name' 		=> $user_lastname
	));
	
	if (is_wp_error($result)) {
		echo "<span style='color: red;'>"; echo __('Error','scottcart'); echo "</span>";
	} else {
		echo "<span style='color: green;'>"; echo __('Saved','scottcart'); echo "</span>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_account_profile', 'scottcart_account_profile_callback' );
add_action( 'wp_ajax_nopriv_scottcart_account_profile', 'scottcart_account_profile_callback' );


// get account purchase details
function scottcart_get_account_purchase_details_callback($id = null, $nonce = null) {
    
	if (isset($_POST['id'])) {
		$id = 		intval($_POST['id']);
		$nonce = 	sanitize_text_field($_POST['nonce']);
	}	
	
	$post = get_post($id);
	
	if (isset($nonce)) {
		if (!wp_verify_nonce( $nonce, 'view_details_'.$id ) ) {
			die(__('You do not have permission to access this page','scottcart'));
		}
	} else {
		die(__('You do not have permission to access this page','scottcart'));
	}
	
	$scottcart_item = $post_items = $post->post_excerpt;
	
	if (isset($scottcart_item)) {
		$scottcart_num_cart_items = $scottcart_item;
	} else {
		$scottcart_num_cart_items = "";
	}
	
	$scottcart_total = $post->post_content;
	
	echo "<table width='100%' class='scottcart_order_details' id='customFields'>
	<tr class='scottcart_alternate_account' valign='top'><td width='20px'>#</td><td width='210px'>"; echo __('Product','scottcart'); echo "</td><td width='110px'>"; echo __('Price','scottcart'); echo "</td><td width='110px'>"; echo __('Quantity','scottcart'); echo "</td><td width='110px'>"; echo __('Total','scottcart'); echo "</td></tr>";
	
	$counter = "1";
	$scottcart_subtotal = "0.00";
	for($i=0;$i<$scottcart_num_cart_items;$i++) {
		
		$selected = 			get_post_meta($post->ID,'scottcart_item_id'.$i,true);
		$scottcart_type = 		get_post_meta($selected,'scottcart_type', true);
		$selected_variation = 	get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
		
		// physical
		if ($scottcart_type == "0") {
			$scottcart_name = get_post_meta($selected,'scottcart_physical_name'.$selected_variation, true);
			$scottcart_price = get_post_meta($selected,'scottcart_physical_price'.$selected_variation, true);
			$scottcart_attribute_id = get_post_meta($post->ID,'scottcart_item_attribute'.$i,true);
			$scottcart_attribute_name = get_post_meta($selected,'scottcart_physical_attribute_name'.$scottcart_attribute_id, true);
			$scottcart_quantity = get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		// digital
		if ($scottcart_type == "1") {
			$scottcart_name = get_post_meta($selected,'scottcart_digital_name'.$selected_variation, true);
			$scottcart_price = get_post_meta($selected,'scottcart_digital_price'.$selected_variation, true);
			$scottcart_quantity = get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		// service
		if ($scottcart_type == "2") {
			$scottcart_name = get_post_meta($selected,'scottcart_service_name', true);
			$scottcart_price = get_post_meta($selected,'scottcart_service_price', true);
			$scottcart_quantity = get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		echo "<tr class='scottcart_alternate_account'><td valign='top'>"; echo $counter; echo "</td><td valign='top'>"; echo get_the_title($selected); echo " - "; echo $scottcart_name;
		
		// attribute name
		if (!empty($scottcart_attribute_name)) {
			echo " - ";
			echo $scottcart_attribute_name;
		}
		
		
		if ($scottcart_type == "1") {
			echo "<br /><br />";
			echo __('Download(s):','scottcart');
			echo "<br />";
			
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
							
							$download_url = scottcart_generate_download_url($post->ID,$download_id);
							echo "<a href='$download_url'>";
							echo $download__file_name;
							echo "</a>";
							echo "<br />";
						}
					}
				}
			}
			
			$hook_array = array (
				'order_id' 		=> $post->ID,
				'product_id' 	=> $product_id,
				'variation_id'	=> $variation_id,
				'cart_id'		=> $i					// position of item in the cart
			);
			
			do_action('scottcart_purchase_details_digital_line_item',$hook_array);
			
		}
		
		
		// used to show line item product notes
		echo apply_filters('scottcart_purchase_details_line_item',$selected);
		
		
		// price
		echo "</td><td valign='top'>"; echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); echo "</td>";
		
		// quantity
		echo "</td><td valign='top'>"; echo $scottcart_quantity; echo "</td>";
		
		// item total
		$scottcart_item_total = $scottcart_price * $scottcart_quantity;
		echo "</td><td valign='top'>"; echo sanitize_meta( 'currency_scottcart',$scottcart_item_total,'post'); echo "</td></tr>";
		
		$scottcart_subtotal = $scottcart_subtotal + $scottcart_item_total;
		
		$counter++;
	}
	
	
	
	
	
	
	echo "<tr class='scottcart_alternate_account'><td><br /></td><td></td><td></td><td></td><td></td></tr>";
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Subtotal','scottcart'); echo "</td><td>"; echo sanitize_meta('currency_scottcart',$scottcart_subtotal,'post'); echo"</td></tr>";
	
	
	$scottcart_discount_code = get_post_meta($post->ID,'scottcart_discount_code',true);
	if ($scottcart_discount_code != "") {
		$scottcart_discount_amount = get_post_meta($post->ID,'scottcart_discount_amount',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Discount Code','scottcart'); echo "</td><td>"; echo $scottcart_discount_code; echo"</td></tr>";
		echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Discount Amount','scottcart'); echo "</td><td>"; echo sanitize_meta('currency_scottcart',$scottcart_discount_amount,'post'); echo"</td></tr>";
	}
	
	$scottcart_tax = get_post_meta($post->ID,'scottcart_tax',true);
	if ($scottcart_tax != scottcart_sanitize_currency_meta('0',false)) {
		echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Tax','scottcart'); echo "</td><td>"; echo sanitize_meta('currency_scottcart',$scottcart_tax,'post'); echo"</td></tr>";
	}
	
	$scottcart_shipping = get_post_meta($post->ID,'scottcart_shipping',true);
	if ($scottcart_shipping != scottcart_sanitize_currency_meta('0',false)) {
		echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Shipping','scottcart'); echo "</td><td>"; echo sanitize_meta('currency_scottcart',$scottcart_shipping,'post'); echo"</td></tr>";
	}
	
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Total','scottcart'); echo "</td><td>"; if ($scottcart_total < 0) { echo sanitize_meta('currency_scottcart','0','post'); } else { echo sanitize_meta( 'currency_scottcart',$scottcart_total,'post'); } echo"</td></tr>";
	
	echo "<tr class='scottcart_alternate_account'><td><br /></td><td></td><td></td><td></td><td></td></tr>";
	
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Order #','scottcart'); echo "</td><td>$id</td></tr>";
	
	$date = explode(' ',$post->post_date);
	$scottcart_date = date(get_option('date_format'), strtotime($date['0']));
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Order Date','scottcart'); echo "</td><td>$scottcart_date</td></tr>";
	
	$scottcart_gateway = get_post_meta($post->ID,'scottcart_gateway',true);
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Payment Method','scottcart'); echo "</td><td>$scottcart_gateway</td></tr>";
	
	$scottcart_txn_id = get_post_meta($post->ID,'scottcart_txn_id',true);
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Payment ID','scottcart'); echo "</td><td>$scottcart_txn_id</td></tr>";
	
	$scottcart_status = $post->post_status;
	if ($scottcart_status == "pend") { $scottcart_status = "pending"; }
	echo "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; echo __('Payment Status','scottcart'); echo "</td><td>"; echo ucfirst($scottcart_status); echo"</td></tr>";
	
	
	// billing and shipping section 
	$scottcart_billing_name = get_post_meta($post->ID,'scottcart_billing_name',true);
	$scottcart_shipping_name = get_post_meta($post->ID,'scottcart_shipping_name',true);
	
	if (!empty($scottcart_billing_name) || !empty($scottcart_shipping_name)) {
		
		echo "<tr class='scottcart_alternate_account'><td><br /></td><td></td><td></td><td></td><td></td></tr>";
		
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo __('Billing Details','scottcart'); echo "</td><td colspan='2'>"; echo __('Shipping Details','scottcart'); echo "</td></tr>";
		
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_name; echo "</td><td colspan='2'>"; echo $scottcart_shipping_name; echo "</td></tr>";
		
		$scottcart_billing_line_1 = get_post_meta($post->ID,'scottcart_billing_line_1',true);
		$scottcart_shipping_line_1 = get_post_meta($post->ID,'scottcart_shipping_line_1',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_line_1; echo "</td><td colspan='2'>"; echo $scottcart_shipping_line_1; echo "</td></tr>";
		
		$scottcart_billing_line_2 = get_post_meta($post->ID,'scottcart_billing_line_2',true);
		$scottcart_shipping_line_2 = get_post_meta($post->ID,'scottcart_shipping_line_2',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_line_2; echo "</td><td colspan='2'>"; echo $scottcart_shipping_line_2; echo "</td></tr>";
		
		$scottcart_billing_country = get_post_meta($post->ID,'scottcart_billing_country',true);
		$scottcart_shipping_country = get_post_meta($post->ID,'scottcart_shipping_country',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_country; echo "</td><td colspan='2'>"; echo $scottcart_shipping_country; echo "</td></tr>";
		
		$scottcart_billing_state = get_post_meta($post->ID,'scottcart_billing_state',true);
		$scottcart_shipping_state = get_post_meta($post->ID,'scottcart_shipping_state',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_state; echo "</td><td colspan='2'>"; echo $scottcart_shipping_state; echo "</td></tr>";
		
		$scottcart_billing_city = get_post_meta($post->ID,'scottcart_billing_city',true);
		$scottcart_shipping_city = get_post_meta($post->ID,'scottcart_shipping_city',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_city; echo "</td><td colspan='2'>"; echo $scottcart_shipping_city; echo "</td></tr>";
		
		$scottcart_billing_zip = get_post_meta($post->ID,'scottcart_billing_zip',true);
		$scottcart_shipping_zip = get_post_meta($post->ID,'scottcart_shipping_zip',true);
		echo "<tr class='scottcart_alternate_account'><td></td><td colspan='2'>"; echo $scottcart_billing_zip; echo "</td><td colspan='2'>"; echo $scottcart_shipping_zip; echo "</td></tr>";
	}
	
	echo "</table>";
	
	if (isset($_POST['id'])) {
		wp_die();
	}
}
add_action( 'wp_ajax_scottcart_get_account_purchase_details', 'scottcart_get_account_purchase_details_callback' );
add_action( 'wp_ajax_nopriv_scottcart_get_account_purchase_details', 'scottcart_get_account_purchase_details_callback' );


// get attributes for single product page on options change - currently this is only used for physical products, so the physical option is hardcoded below
function scottcart_get_price_attributes_callback() {
    
	$id = 		intval($_POST['id']);
	$type = 	sanitize_text_field($_POST['type']);
	$post_id = 	sanitize_text_field($_POST['post_id']);
	$nonce = 	sanitize_text_field($_POST['nonce']);
	
	// verify nonce
	if (!wp_verify_nonce($nonce,'scottcart_cart_nonce_'.$post_id)) { die( __('Error - Nonce validation failed.','scottcart')); }
	
	$scottcart_hide_sold_out = 					scottcart_get_option('hide_sold_out');
	$scottcart_inventory_management_product = 	get_post_meta($post_id,"scottcart_physical_inventory", true);
	$scottcart_variations = 					get_post_meta($post_id,"scottcart_variations", true);
	$scottcart_attribute_count = 				get_post_meta($post_id,"scottcart_physical_attribute_count", true);
	
	// set variables
	$result = '';
	$disabled = 'false';
	$disabled_force = 'false';
	
	// dropdown attribute
	if ($type == 'dropdown' && $scottcart_variations == '1' && $scottcart_inventory_management_product == '1') {
		$result .= "<select class='scottcart_input scottcart_product_attribute_id'>";
		for($i=0;$i<$scottcart_attribute_count;$i++) {
			$attribute_assignment = get_post_meta($post_id,"scottcart_physical_attribute_assignment".$i, true);
			$scottcart_qty = get_post_meta($post_id,"scottcart_physical_attribute_qty".$i, true);
			
			if ($attribute_assignment == $id || $attribute_assignment == 'a') {
				$scottcart_name = get_post_meta($post_id,"scottcart_physical_attribute_name".$i, true);
				
				if (!empty($scottcart_name)) {
					if ($scottcart_hide_sold_out == '0' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
						$result .= "<option value='$i'>"; $result .= $scottcart_name; $result .= "</option>";
						$disabled = 'false';
						$disabled_force = 'true';
					}
				}
			}
		}
		$result .= "</select>";
	}
	
	
	// radio attribute
	if ($type == 'radio' && $scottcart_variations == '1' && $scottcart_inventory_management_product == '1') {
		$count = "0";
		$result .= "<table class='scottcart_product_attribute_id'>";
		for($i=0;$i<$scottcart_attribute_count;$i++) {
			$attribute_assignment = get_post_meta($post_id,"scottcart_physical_attribute_assignment".$i, true);
			$scottcart_qty = get_post_meta($post_id,"scottcart_physical_attribute_qty".$i, true);
			
			if ($attribute_assignment == $id || $attribute_assignment == 'a') {
				$scottcart_name = get_post_meta($post_id,"scottcart_physical_attribute_name".$i, true);
				if (!empty($scottcart_name)) {
					if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
						$result .= "<tr class='scottcart_alternate'>";
						$result .= "<td class='scottcart_single_product_radio_width'><input type='radio'"; if ($count == "0") { $result .= " CHECKED "; } $result .= "name='scottcart_radio_attribute_name' class='scottcart_product_attribute_id' value='$i'>";
						$result .= "<td>"; $result .= $scottcart_name; $result .= "</td>";
						$result .= "</tr>";
						$count++;
						$disabled = 'false';
						$disabled_force = 'true';
					}
				}
			}
		}
		$result .= "</table>";
	}
	
	
	$response = array(
		'response' => $result,
		'disabled' => $disabled,
	);
	
	echo json_encode($response);
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_price_attributes', 'scottcart_get_price_attributes_callback' );
add_action( 'wp_ajax_nopriv_scottcart_get_price_attributes', 'scottcart_get_price_attributes_callback' );