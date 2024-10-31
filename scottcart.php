<?php

/*
Plugin Name: ScottCart
Plugin URI: https://scottcart.com
Description: A complete eCommerce solution.
Author: Scott Paterson
Author URI: https://scottcart.com
Version: 1.1
Text Domain: scottcart
Domain Path: /i18n/languages/
*/

/*  Copyright (c) 2017-2023, Scott Paterson

    ScottCart is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    ScottCart is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// define common variables
if (!defined('SCOTTCART_PLUGIN_PATH')) {
	define('SCOTTCART_PLUGIN_PATH',			plugin_dir_path(__FILE__));
}
if (!defined('SCOTTCART_PLUGIN_BASENAME')) {
	define('SCOTTCART_PLUGIN_BASENAME',		plugin_basename(__FILE__));
}
if (!defined('SCOTTCART_SITE_URL')) {
	define('SCOTTCART_SITE_URL',			get_site_url());
}
if (!defined('SCOTTCART_NAME')) {
	define('SCOTTCART_NAME', 				'ScottCart');
}
if (!defined('SCOTTCART_SLUG')) {
	define('SCOTTCART_SLUG', 				'scottcart');
}
if (!defined('SCOTTCART_VERSION')) {
	define('SCOTTCART_VERSION', 			'1.1');
}
if (!defined('SCOTTCART_SETTINGS_PAGE')) {
	define('SCOTTCART_SETTINGS_PAGE', 		'scottcart_settings_page');
}

// languages
load_plugin_textdomain('scottcart', false, SCOTTCART_PLUGIN_BASENAME . '/i18n/languages');

// wp version
global $wp_version;


// check plugin requirements
if ((version_compare(PHP_VERSION, '5.6', '<')) || (version_compare($wp_version, '4.0', '<'))) {
	
	// notices
	add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>". __('ScottCart requires PHP 5.6+ and WordPress 4.0+ to function properly. Your current configuration does not meet one or more of these requirements.', 'scottcart'). "</p></div>';" ) );

	add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>".__('ScottCart has been auto-deactivated.', 'scottcart') ."</p></div>';" ) );
	
	// deactivate plugin
	function scottcart_deactivate_self() {
		deactivate_plugins(plugin_basename( __FILE__ ));
	}
	add_action('admin_init','scottcart_deactivate_self');
	
	// remove plugin activated notice
	if (isset($_GET['activate'])) {
        unset($_GET['activate']);
    }
	
	return;
	
} else {

	// activate hook
	function activation_scottcart() {
		global $wp_rewrite;
		
		include_once ('includes/admin/post_types_status_taxonomies.php');
		include_once ('includes/admin/install.php');
		
		
		// register post types and taxonomies
		scottcart_register_post_type();
		scottcart_register_taxonomies();
		
		scottcart_install();
		
		// flush
		flush_rewrite_rules();
		
	}

	// deactivate hook
	function deactivation_scottcart() {
		global $wp_rewrite;
		
		delete_option("scottcart_firstrun");
		
		flush_rewrite_rules();
	}

	// uninstall hook
	function uninstall_scottcart() {
		
		// remove all plugin data if option is enabled
		if (scottcart_get_option('uninstall') == "1") {
			
			scottcart_uninstall();
			
		}
		
	}

	// register hooks
	register_activation_hook(__FILE__,'activation_scottcart');
	register_deactivation_hook(__FILE__, 'deactivation_scottcart');
	register_uninstall_hook(__FILE__,'uninstall_scottcart');


	// public includes
	include_once ('includes/admin/post_types_status_taxonomies.php');
	include_once ('i18n/locations.php');
	include_once ('i18n/currency.php');
	include_once ('includes/settings/settings_api.php');
	include_once ('includes/actions.php');
	include_once ('includes/gateways/gateways_api.php');
	include_once ('includes/gateways/gateways_functions.php');
	include_once ('includes/account/account_api.php');
	include_once ('includes/account/account_functions.php');
	include_once ('includes/functions.php');
	include_once ('includes/cron.php');
	include_once ('includes/emails.php');
	
	// get settings
	$scottcart_options = scottcart_get_options();

	include_once ('includes/ajax_functions.php');
	include_once ('includes/shortcodes.php');
	include_once ('includes/enqueue.php');
	include_once ('includes/formatting.php');
	include_once ('includes/file_downloads.php');
	
	// template pages
	include_once ('templates/account.php');
	include_once ('templates/cart.php');
	include_once ('templates/single_product.php');
	
	// public gateway includes
	include_once ('includes/gateways/gateways_api.php');
	include_once ('includes/gateways/paypal_standard/paypal_standard.php');
	include_once ('includes/gateways/paypal_standard/paypal_ipn.php');
	
	// admin includes
	if (is_admin()) {
		include_once ('includes/admin/ajax_functions_admin.php');
		include_once ('includes/admin/tables.php');
		include_once ('includes/admin/orders.php');
		include_once ('includes/admin/products.php');
		include_once ('includes/admin/reports/reports_page.php');
		include_once ('includes/admin/reports/reports_api.php');
		include_once ('includes/admin/reports/reports_ajax.php');
		include_once ('includes/admin/customers.php');
		include_once ('includes/admin/menu.php');
		include_once ('includes/admin/help/help.php');
		include_once ('includes/admin/dashboard_widget.php');
		include_once ('includes/admin/settings/settings_dashboard_items.php');
		include_once ('includes/admin/discount.php');
		include_once ('includes/admin/filters.php');
		include_once ('includes/admin/settings/settings_page.php');
		include_once ('includes/admin/uninstall.php');
		include_once ('includes/admin/deactivate_survey.php');
	}

}