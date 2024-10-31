<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// allows add_query_arg field scottcart-action value to load a fucntion
function scottcart_process_actions() {
	if (isset($_REQUEST['scottcart-action'])) {
		do_action('scottcart_' . sanitize_text_field($_REQUEST['scottcart-action']),$_REQUEST);
	}
}
add_action('admin_init','scottcart_process_actions');


// get options with defaults - used in settings_api.php to load defaults for settings page
function scottcart_get_option($key) {
	
	$scottcart_options = get_option('scottcart_settings');
	
	$result = '';
	
	// check if option has been saved
	if (isset($scottcart_options[$key])) {
		// get option from saved options
		$result = $scottcart_options[$key];
	} else {
		// get option from default in settings array
		$settings = scottcart_settings();
		
		// loop through remaining values to get default
		foreach ($settings as $tabs ) {
			foreach ($tabs as $page) {
				foreach ($page as $option) {
					if ($option['name'] == $key) {
						if (isset($option['default'])) {
							$result = $option['default'];
						}
					}
				}
			}
		}
		
		// save default to we don't need to search again
		$scottcart_options[$key] = $result;
		update_option('scottcart_settings',$scottcart_options);
		
	}
	return $result;
}



// load and save all options - the loop should only run on install
function scottcart_get_options() {
	
	$scottcart_options = get_option('scottcart_settings');
	
	if (!isset($scottcart_options['tab'])) {
		
		$settings = scottcart_settings();
		
		foreach ($settings as $tabs ) {
			foreach ($tabs as $page) {
				foreach ($page as $option) {
					if (isset($option['default'])) {
						$scottcart_options[$option['name']] = $option['default'];
						update_option('scottcart_settings',$scottcart_options);
					}
				}
			}
		}
		
		$scottcart_options['tab'] = 'tab00';;
		update_option('scottcart_settings',$scottcart_options);
		
		$scottcart_options = get_option('scottcart_settings');
		return $scottcart_options;
		
	} else {
		return $scottcart_options;
	}
	exit;
}


// add items to the cart via a URL
// example use https://www.scottcart.com/?scottcart_action=direct_add&id=661&price_id=1
function scottcart_direct_add_fn($request) {
	if (isset($request) && $request['scottcart_action'] == 'direct_add') {
		
		// make sure id and post id exist as input and post exists
		if (isset($_REQUEST['id']) && get_post_status(intval($_REQUEST['id'])) == 'publish' && get_post_type(sanitize_text_field($_REQUEST['id'])) == 'scottcart_product') {
			
			if (isset($_REQUEST['id'])) {
				$id = intval($_REQUEST['id']);
			} else {
				$id = '';
			}
			
			$type = get_post_meta(intval($_REQUEST['id']),'scottcart_type', true);
			
			if (isset($_REQUEST['price_id'])) { 
				$price_id = intval($_REQUEST['price_id']);
				// decrement, since values actually start from 0
				$price_id--;
			} else {
				$price_id = '0';
			}
			
			if (isset($_REQUEST['attribute_id'])) {
				$attribute_id = intval($_REQUEST['attribute_id']);
				// decrement, since values actually start from 0
				$price_id--;
			} else {
				$attribute_id = '';
			}
			
			if (isset($_REQUEST['quantity'])) {
				$quantity = intval($_REQUEST['quantity']);
			} else {
				$quantity = '1';
			}
			
			$_SESSION['scottcart_cart'][] = array('id' => $id, 'type' => $type, 'price_id' => $price_id, 'attribute_id' => $attribute_id, 'quantity' => $quantity);
			
			// redirect to cart
			$cart_url = get_permalink(scottcart_get_option('cart_page'));
			
			wp_redirect($cart_url);
			
			exit;
			
		}
	}
}
add_action('scottcart_direct_add','scottcart_direct_add_fn');


// url hook - REQUEST
function scottcart_url_hook() {
	if (isset($_REQUEST['scottcart_action'])) {
		do_action('scottcart_'.$_REQUEST['scottcart_action'],$_REQUEST);
	}
}
add_action('init','scottcart_url_hook');