<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// dashboard item - getting started
function scottcart_dashboard_load_getting($scottcart_dashboard_array) {

	$getting_started_array = array(
		'getting started' => array(
			'title'  				=> __( 'Getting Started', 'scottcart' ),
			'body'  				=> __( "
					1. This plugin has PayPal Standard built in. In order to collect payments using PayPal you will need to have a PayPal account and enter your account Email or Merchand ID on the <a href='admin.php?page=scottcart_settings_page&tab=51'>Payments tab</a>.<br /><br />
					2. Create a product on the <a href='edit.php?post_type=scottcart_product'>Products page</a>.<br /><br />
					3. When this plugin was installed it automatically created 5 pages. The Shop, Cart, Account, Order Cancelled, and Purchase Confirmation pages. You may wish you include these pages in your theme <a href='nav-menus.php'>menu</a>. Products will automatically appear on the Shop Page.<br /><br />
					4. When someone purchases your product, you will be able to see their order on the <a href='edit.php?post_type=scottcart_order'>Orders page</a>.<br /><br />
					5. That's it for the basic setup. Feel free to explore the plugin and settings.
			", 'scottcart' ),
		),
	);

	return array_merge($scottcart_dashboard_array,$getting_started_array);
}
add_filter( 'scottcart_dashboard_array','scottcart_dashboard_load_getting');


// dashboard item - warnings
function scottcart_dashboard_warnings($scottcart_dashboard_array) {

	$warnings = array();
	$warnings_list = '';
	
	if (!function_exists('curl_version')) {
		$warnings[] = "cURL is not installed or enabled on your server. Orders may not appear.";
	}


	// other checks can be added here

	
	$number = "1";
	foreach ($warnings as $warning) {
		$warnings_list .= $number.'. '.$warning.'<br />';
		$number++;
	}
	
	if (empty($warnings)) {
		$warnings_list  = __( 'No issues found.', 'scottcart' );
	}

	$warnings_array = array(
		'warnings' => array(
			'title'  				=> __( 'Notices', 'scottcart' ),
			'body'  				=> __( $warnings_list, 'scottcart' ),
		),
	);

	return array_merge($scottcart_dashboard_array,$warnings_array);
}
add_filter( 'scottcart_dashboard_array','scottcart_dashboard_warnings');


// dashboard item - extensions
function scottcart_dashboard_extensions($scottcart_dashboard_array) {

	$extensions_array = array(
		'extensions' => array(
			'title'  				=> __( 'Extensions', 'scottcart' ),
			'body'  				=> __( "
			
			Extensions are a way for you to add more functionality to ScottCart. <br /><br />
			
			Please visit <a target='_blank' href='https://www.scottcart.com/products/?utm_source=scottcart&utm_campaign=extensions&utm_medium=settings&utm_content=all'>ScottCart.com</a> to view all of our extensions.
			
			", 'scottcart' ),
		),
	);

	return array_merge($scottcart_dashboard_array,$extensions_array);
}
add_filter( 'scottcart_dashboard_array','scottcart_dashboard_extensions');