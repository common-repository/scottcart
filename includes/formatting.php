<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// currency validation
// value - price or amount to be formatted
// symbol - true by default, false will not return currency symbol in result - this needs to be passed by calling the function directly - ex: scottcart_sanitize_currency_meta('5',false);
function scottcart_sanitize_currency_meta($value,$symbol = true) {
		
		$result = '';
		
		$currency_symbol = scottcart_get_option('currency_symbol');
		$currency_position = scottcart_get_option('currency_position');
		$currency_thousands = scottcart_get_option('currency_thousands');
		$currency_decimal = scottcart_get_option('currency_decimal');
		
		if ($currency_position == '0' && $symbol == true) {
			$result .= $currency_symbol;
		}
		
		if ($currency_position == '2' && $symbol == true) {
			$result .= $currency_symbol;
			$result .= " ";
		}
		
		$value = preg_replace("/[^0-9,.]/","",$value);
		
		if (empty($value) || !isset($value)) { $value = '0'; }
		
		$formatted = number_format($value, 2, '.', ',');
		$formatted = str_replace(',',$currency_thousands,$formatted);
		$formatted = str_replace('.',$currency_decimal,$formatted);
		$result .= $formatted;
		
		if ($currency_position == '1' && $symbol == true) {
			$result .= $currency_symbol;
		}
		
		if ($currency_position == '3' && $symbol == true) {
			$result .= " ";
			$result .= $currency_symbol;
		}
		
		return $result;
	
}
add_filter( 'sanitize_post_meta_currency_scottcart', 'scottcart_sanitize_currency_meta' );