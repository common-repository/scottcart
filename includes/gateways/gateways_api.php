<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// make gateways array
function scottcart_gateways_api() {
	$scottcart_gateway_array = apply_filters('scottcart_gateway_array', array());
	return $scottcart_gateway_array;
}

// load each gateway that is in the gateways array
// 1 - return gateway names - used for orders page
// 2 - return menu - used on cart page
// 3 - load gateway function - used on cart page
// 4 - return gateway names - used for settings page - removes free gateway from list
function scottcart_load_gateways($input = "1") {

	$gateways_array = scottcart_gateways_api();
	
	// return gateway names - used for orders page
	if ($input == "1") {
		$array = [];
		foreach ($gateways_array as $gateway) {
			$array[$gateway['slug']] = $gateway['title'];
		}
		return $array;
	}
	
	// return gateway names - used for settings page
	if ($input == "4") {
		$array = [];
		foreach ($gateways_array as $gateway) {
			$array[$gateway['slug']] = $gateway['title'];
		}
		unset($array['free']);
		return $array;
	}
	
	// return menu - used on cart page
	if ($input == "2") {
		$result = '';
		
		$result .= "<div class='scottcart_shipping_left'></div>";
		$result .= "<div class='scottcart_shipping_middle'>"; $result .= __('Payment Method','scottcart'); $result .= "</div>";
		$result .= "<div class='scottcart_shipping_right'></div>";
		
		$counter = '0';
		
		
		// reorder gateway array so that the default gateway is on top of the array
		$default = scottcart_get_option('default_gateway');
		$array = $gateways_array[$default];
		unset($gateways_array[$default]);
		$gateways_array = array($default => $array) + $gateways_array;
		
		
		foreach ($gateways_array as $count => $gateway) {
			
			// check if gateway is enabled on settings page
			if (trim(scottcart_get_option($gateway['slug']) == '0')) {
				
				
				if (empty($gateway['function_js'])) {
					$gateway['function_js'] = 'scottcart_empty';
				}
				
				
				$result .= "<div "; $result .= " class='scottcart_alternate "; if ($counter == "0") { $result .= " scottcart_cart_gateway_selected"; } $result .=" '>";
					$result .= "<div class='scottcart_gateway_left'>";
						$result .= "<input autocomplete='off' type='radio' data-js='"; $result .= $gateway['function_js']; $result .= "' data-fn='"; $result .= $gateway['function_public']; $result .= "' data-fnp='"; $result .= $gateway['function_private']; $result .= "' name='scottcart_payment_method' value='$count' "; if (scottcart_get_option('default_gateway') == $gateway['slug']) { $result .= 'checked="checked"'; } $result .= ">";
					$result .= "</div>";
					
					$result .= "<div class='scottcart_gateway_middle'>";
						$result .= scottcart_get_option($gateway['slug']."_title");
						$result .= "<br />(";
						$result .= scottcart_get_option($gateway['slug']."_desc");
						$result .= ")";
					$result .= "</div>";
					
					$result .= "<div class='scottcart_gateway_right'>";
						$result .= "<img src='";
						$result .= $gateway['icon'];
						$result .= "'>";
					$result .= "</div>";
				$result .= "</div>";
				
				$counter++;
			}
			
		}
		
		return $result;
	}
	
	// load gateway function - used on cart page
	if ($input == "3") {
		foreach ($gateways_array as $gateway) {
			if (scottcart_get_option('default_gateway') == $gateway['slug']) {
				$function = $gateway['function_public'];
				return call_user_func($function);
			}
		}
	}

}