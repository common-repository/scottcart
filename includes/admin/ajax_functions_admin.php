<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get state list for country id in cart - for settings page - tax section
function scottcart_get_state_list_tax_callback() {
	
	global $scottcart_options;
    
	$id = 	intval($_POST['id']);
	$i = 	sanitize_text_field($_POST['i']);
	
	$state_list = scottcart_get_shop_states($id);
	
	if (!empty($state_list)) {
		echo "<select class='scottcart_input scottcart_tax_state' name='scottcart_settings[tax_state][]' id='scottcart_state_list'>";
			foreach ($state_list as $state_id => $state) {
				echo "<option"; if (!empty($scottcart_options['tax_state'][$i])) {  if ($scottcart_options['tax_state'][$i] == $state_id) { echo " SELECTED "; } } echo " value='$state_id'>$state</option>";
			}
		echo "</select>";
	} else {
		echo "<input type='text' class='scottcart_input' id='scottcart_state_list' value=''>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_state_list_tax', 'scottcart_get_state_list_tax_callback' );


// get country list in cart - for settings page - tax section
function scottcart_get_country_list_tax_callback() {
	
	echo "<select class='scottcart_input scottcart_tax_country' name='scottcart_settings[tax_country][]'>";
		$country_list = scottcart_get_country_list();
		foreach ($country_list as $country_id => $country) {
			echo "<option value='$country_id'>$country</option>";
		}
	echo "</select>";
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_country_list_tax', 'scottcart_get_country_list_tax_callback' );


// get state list for country id in  - for settings page - shipping section
function scottcart_get_state_list_shipping_callback() {

	global $scottcart_options;
    
	$id = 	sanitize_text_field($_POST['id']);
	$i = 	sanitize_text_field($_POST['i']);
	
	$state_list = scottcart_get_shop_states($id);
	
	if (!empty($state_list)) {
		echo "<select class='scottcart_input scottcart_shipping_state' name='scottcart_settings[shipping_state][]' id='scottcart_state_list'>";
			foreach ($state_list as $state_id => $state) {
				echo "<option"; if (!empty($scottcart_options['shipping_state'][$i])) {  if ($scottcart_options['shipping_state'][$i] == $state_id) { echo " SELECTED "; } } echo " value='$state_id'>$state</option>";
			}
		echo "</select>";
	} else {
		echo "<input type='text' name='scottcart_settings[shipping_state][]' class='scottcart_input scottcart_shipping_state' id='scottcart_state_list' value='"; echo $scottcart_options['shipping_state'][$i]; echo "'>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_state_list_shipping', 'scottcart_get_state_list_shipping_callback' );


// get country list in cart - for settings page - shipping section
function scottcart_get_country_list_shipping_callback() {

	global $scottcart_options;
	
	echo "<select class='scottcart_input scottcart_shipping_country' name='scottcart_settings[shipping_country][]'><option></option><option value='worldwide'>Worldwide</option>";
		$country_list = scottcart_get_country_list();
		foreach ($country_list as $country_id => $country) {
			echo "<option value='$country_id'>$country</option>";
		}
	echo "</select>";
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_country_list_shipping', 'scottcart_get_country_list_shipping_callback' );


// get shipping types
function scottcart_get_shipping_types_callback() {

	global $scottcart_options;
	
	$counter = sanitize_text_field($_POST['counter']);
		
		$count = "0";
		echo "<select class='scottcart_input' id='scottcart_state_list' name='scottcart_settings[shipping_type][$counter][]'>";
			for($i=0;$i<$scottcart_options['shipping_types_count'];$i++) {
				echo "<option value='$count'>"; echo $scottcart_options['shipping_types_name'][$count]; echo "</option>";
				$count++;
			}
		echo "</select>";
		
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_shipping_types', 'scottcart_get_shipping_types_callback' );


// get order product names
function scottcart_get_products_name_callback() {
		
		$order_id = 	intval($_POST['order_id']);
		$product_id = 	sanitize_text_field($_POST['input']);
		
		echo "<select style='width:150px;' class='product' name='product[]'><option></option>";
		
		$args = array(
			'post_type'					=> 'scottcart_product',
			'post_status'				=> 'publish',
			'update_post_term_cache'	=> false, // don't retrieve post terms
			'meta_query'		=> array(
			'relation'=>'or',
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
		
		foreach ($posts_array->posts as $post) {
			$selected = get_post_meta($order_id,'scottcart_item_name'.$product_id,true);
			echo "<option value='$post->ID'"; if ($selected == $post->ID) { echo "SELECTED"; } echo ">$post->post_title</option>";
		}
		
		echo "</select>";
		
		
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_products_name', 'scottcart_get_products_name_callback' );


// get order variations with price
function scottcart_get_variations_callback() {
	$product_id = 			intval($_POST['id']); 			// product id
	$variation_id = 		intval($_POST['vid']); 			// variation id
	$attribute_id = 		intval($_POST['attribute_id']); // attribute id
	$order_id = 			intval($_POST['order_id']); 	// order post id
	$cart_id = 				intval($_POST['cart_id']); 		// cart row number
	
	if (!empty($variation_id)) {
		$variation_id = $variation_id;
	} else {
		$variation_id = "0";
	}
	
	$scottcart_type = get_post_meta($product_id,'scottcart_type', true);
	
	// physical
	if ($scottcart_type == "0") {
		
		// see if attributes(variations) are turned on
		$scottcart_variations = get_post_meta($product_id,'scottcart_variations', true);
		
		// variation
		$scottcart_physical_variation_count = get_post_meta($product_id,'scottcart_physical_count', true);
		
		echo "<select style='width:150px;' class='variation' name='variation[]'>";
		for($i=0;$i<=$scottcart_physical_variation_count;$i++) {
			$scottcart_name = get_post_meta($product_id,'scottcart_physical_name'.$i, true);
			if (!empty($scottcart_name)) {
				echo "<option value='$product_id|$i'"; if ($variation_id == $i) { echo "SELECTED "; } echo ">$scottcart_name</option>";
			}
		}
		echo "</select>";
		
		echo "|*";
		// price
		$scottcart_item_gross = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
		if (!empty($scottcart_item_gross)) {
			// an order exists, so we should use that price
			$total = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		} else {
			// an order does not exist, so we will use the price from the product page
			$total = get_post_meta($product_id,'scottcart_physical_price'.$variation_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		}
		
		echo "|*";
		if ($scottcart_variations == '1') {
			// attributes
			$scottcart_attribute_count = get_post_meta($product_id,'scottcart_physical_attribute_count', true);
			echo "<select style='width:150px;' class='attribute' name='attribute[]'>";
			for($i=0;$i<=$scottcart_attribute_count;$i++) {
				$scottcart_name = get_post_meta($product_id,'scottcart_physical_attribute_name'.$i, true);
				if (!empty($scottcart_name)) {
					echo "<option value='$product_id|$i'"; if ($attribute_id == $i) { echo " SELECTED "; } echo ">$scottcart_name</option>";
				}
			}
			echo "</select>";
		} else {
			echo "-";
		}
		
		echo "|*";
		// quantity
		$quantity = get_post_meta($order_id,'scottcart_item_quantity'.$cart_id, true);
		if (empty($quantity)) { $quantity = "1"; }
		echo "<input size='4' type='text' name=quantity[] value='$quantity'>";
		
	}
	
	// digital
	if ($scottcart_type == "1") {
		// variation
		$scottcart_digital_variation_count = get_post_meta($product_id,'scottcart_digital_count', true);
		echo "<select style='width:150px;' class='variation' name='variation[]'>";
		for($i=0;$i<=$scottcart_digital_variation_count;$i++) {
			$scottcart_name = get_post_meta($product_id,'scottcart_digital_name'.$i, true);
			if (!empty($scottcart_name)) {
				echo "<option value='$product_id|$i'"; if ($variation_id == $i) { echo "SELECTED "; } echo ">$scottcart_name</option>";
			}
		}
		echo "</select>";
		
		echo "|*";
		// price
		$scottcart_item_gross = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
		if (!empty($scottcart_item_gross)) {
			// an order exists, so we should use that price
			$total = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		} else {
			// an order does not exist, so we will use the price from the product page
			$total = get_post_meta($product_id,'scottcart_digital_price'.$variation_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		}
		
		echo "|*-";
		// attributes
		echo "<input type='hidden' value='' name='attribute[]'>";
		
		echo "|*";
		// quantity
		$quantity = get_post_meta($order_id,'scottcart_item_quantity'.$cart_id, true);
		if (empty($quantity)) { $quantity = "1"; }
		echo "<input size='4' type='text' name=quantity[] value='$quantity'>";
		
	}
	
	// service
	if ($scottcart_type == "2") {
		// variation
		$scottcart_service_variation_count = get_post_meta($product_id,'scottcart_service_count', true);
		echo "<select style='width:150px;' class='variation' name='variation[]'>";
		for($i=0;$i<=$scottcart_service_variation_count;$i++) {
			$scottcart_name = get_post_meta($product_id,'scottcart_service_name'.$i, true);
			if (!empty($scottcart_name)) {
				echo "<option value='$product_id|$i'"; if ($variation_id == $i) { echo "SELECTED "; } echo ">$scottcart_name</option>";
			}
		}
		echo "</select>";
		
		echo "|*";
		// price
		$scottcart_item_gross = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
		if (!empty($scottcart_item_gross)) {
			// an order exists, so we should use that price
			$total = get_post_meta($order_id,'scottcart_item_gross'.$cart_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		} else {
			// an order does not exist, so we will use the price from the product page
			$total = get_post_meta($product_id,'scottcart_service_price'.$variation_id, true);
			$total = scottcart_sanitize_currency_meta($total,false);
			echo "<input size='8' type='text' name=total[] value='$total'>";
		}
		
		echo "|*-";
		// attributes
		echo "<input type='hidden' value='' name='attribute[]'>";
		
		echo "|*";
		// quantity
		$quantity = get_post_meta($order_id,'scottcart_item_quantity'.$cart_id, true);
		if (empty($quantity)) { $quantity = "1"; }
		echo "<input size='4' type='text' name=quantity[] value='$quantity'>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_variations', 'scottcart_get_variations_callback' );


// get order variations price (and possibly attribute) if called manually
function scottcart_get_variations_price_callback() {
	$id = intval($_POST['id']);
	
	$result = explode("|", $id);
	
	$scottcart_type = get_post_meta($result[0],'scottcart_type', true);
	
	// physical
	if ($scottcart_type == "0") {
		$total = get_post_meta($result[0],'scottcart_physical_price'.$result[1], true);
		
		$scottcart_attribute_count = get_post_meta($result[0],'scottcart_physical_attribute_count', true);
		
		echo "<select style='width:150px;' class='attribute' name='attribute[]'>";
		for($i=0;$i<=$scottcart_attribute_count;$i++) {
			$scottcart_name = get_post_meta($result[0],'scottcart_physical_attribute_name'.$i, true);
			if (!empty($scottcart_name)) {
				echo "<option value='$id|$i'"; if ($vid == $i) { echo "SELECTED "; } echo ">$scottcart_name</option>";
			}
		}
		echo "</select>";
		
		echo "|*";
		echo "<input size='4' type='text' name=total[] value='$total'>";
	}
	
	// digital
	if ($scottcart_type == "1") {
		$total = get_post_meta($result[0],'scottcart_digital_price'.$result[1], true);
		$total = scottcart_sanitize_currency_meta($total,false);
		echo "<input type='hidden' name='attribute[]'>";
		echo "-|*";
		echo "<input size='4' type='text' name=total[] value='$total'>";
	}
	
	// service
	if ($scottcart_type == "2") {
		$total = get_post_meta($result[0],'scottcart_service_price'.$result[1], true);
		$total = scottcart_sanitize_currency_meta($total,false);
		echo "<input type='hidden' name='attribute[]'>";
		echo "-|*";
		echo "<input size='4' type='text' name=total[] value='$total'>";
	}

	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_variations_price', 'scottcart_get_variations_price_callback' );


// resend customer order email
function scottcart_resend_customer_email_callback() {
	$order_id = intval($_POST['id']);
	
	$result = scottcart_send_customer_email($order_id);
	
	if ($result == "1") {
		echo "<span style='color: green;font-weight:bold;'>Sent</span>";
	} else {
		echo "<span style='color: red;font-weight:bold;'>Error</span>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_resend_customer_email', 'scottcart_resend_customer_email_callback' );


// load image thumbnail
function scottcart_get_img_url_callback() {
	$id = intval($_POST['id']);
	
	echo wp_get_attachment_image($id,array('50', '50'));
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_get_img_url', 'scottcart_get_img_url_callback' );


// get state list for settings page
function scottcart_settings_get_state_list_callback() {

	$country_id = 		sanitize_text_field($_POST['country_id']);
	
	// state / province list
	$state_list = scottcart_get_shop_states($country_id);
	
	if (!empty($state_list)) {
		echo "<select class='scottcart_cell_width' name='scottcart_settings[base_state]'>";
			foreach ($state_list as $state_id => $state) {
				echo "<option "; if (scottcart_get_option('base_state') == $state_id) { echo " SELECTED "; } echo "value='$state_id'>$state</option>";
			}
		echo "</select>";
	} else {
		echo "<input type='text' class='scottcart_cell_width' value='"; echo scottcart_get_option('base_state'); echo "' name='scottcart_settings[base_state]'>";
	}
	
	wp_die();
}
add_action( 'wp_ajax_scottcart_settings_get_state_list', 'scottcart_settings_get_state_list_callback' );
add_action( 'wp_ajax_nopriv_scottcart_settings_get_state_list', 'scottcart_settings_get_state_list_callback' );


// process deactivate survey form submission
function scottcart_deactivate_survey_callback() {
		
		$_reason       = sanitize_text_field(wp_unslash($_POST['reason']));
		$reason_detail = sanitize_text_field(wp_unslash($_POST['reason_detail']));
		$reason        = '';
		
		if ($_reason == '1') {
			$reason = 'I need a feature';
		} elseif ($_reason == '2') {
			$reason = 'I found a better plugin / platform';
		} elseif ($_reason == '3') {
			$reason = 'The plugin broke my site';
		} elseif ($_reason == '4') {
			$reason = 'The plugin suddenly stopped working';
		} elseif ($_reason == '5') {
			$reason = 'I no longer need the plugin';
		} elseif ($_reason == '6') {
			$reason = 'It\'s a temporary deactivation. I\'m just debugging an issue.';
		} elseif ($_reason == '7') {
			$reason = 'Other';
		}
		
		$fields = array(
			'action'            => 'Deactivate',
			'reason'            => $reason,
			'reason_detail'     => $reason_detail,
		);
		
		$site_email = 	get_option('admin_email');
		$site_url = 	get_option('siteurl');
		
		$headers[] = 'From: '. $site_email .' <'. $site_email .'>' . "\r\n";
		$headers[] = "Content-type: text/html";
		
		$message = "Reason: 	".$reason."<br />";
		$message .= "Site: 		".$site_url."<br />";
		$message .= "Email: 	".$site_email."<br />";
		$message .= "Ver #: 	".SCOTTCART_VERSION."<br />";
		$message .= "Feedback: 	".$reason_detail."<br />";
		
		$message = nl2br($message);
		
		$mail_result = wp_mail('wpplugin.org@gmail.com','Feedback Survey',$message,$headers);
		
		wp_die();
}
add_action('wp_ajax_scottcart_deactivate_survey', 'scottcart_deactivate_survey_callback');