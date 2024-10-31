<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// make admin menu
function scottcart_plugin_menu() {
	
	// pages
	add_menu_page("ScottCart", "ScottCart", "manage_options", "scottcart_menu", "scottcart_dashboard",'dashicons-cart','28.5');
	add_submenu_page("scottcart_menu", __('Products','scottcart'), __('Products','scottcart'), 'manage_options', 'edit.php?post_type=scottcart_product',false );
	add_submenu_page("scottcart_menu", __('Product Categories','scottcart'), '&nbsp;&nbsp;&nbsp;-'.__('Categories','scottcart'), 'manage_options', 'edit-tags.php?taxonomy=product_category&post_type=scottcart_product',false );
	add_submenu_page("scottcart_menu", __('Product Tags','scottcart'), '&nbsp;&nbsp;&nbsp;-'.__('Tags','scottcart'), 'manage_options', 'edit-tags.php?taxonomy=product_tag&post_type=scottcart_product',false );
	add_submenu_page("scottcart_menu", __('Product Features','scottcart'), '&nbsp;&nbsp;&nbsp;-'.__('Features','scottcart'), 'manage_options', 'edit-tags.php?taxonomy=product_feature&post_type=scottcart_product',false );
	add_submenu_page("scottcart_menu", __('Customers','scottcart'), __('Customers','scottcart'), 'manage_options', 'edit.php?post_type=scottcart_customer',false );
	add_submenu_page("scottcart_menu", __('Discounts','scottcart'), __('Discounts','scottcart'), 'manage_options', 'edit.php?post_type=scottcart_discount',false );
	add_submenu_page("scottcart_menu", __('Reports','scottcart'), __('Reports','scottcart'), "manage_options", "scottcart_reports", "scottcart_reports");
	$settings_page = add_submenu_page("scottcart_menu", __('Settings','scottcart'), __('Settings','scottcart'), "manage_options", "scottcart_settings_page", "scottcart_settings_page");

	// help tab element for settings page top right corner
	//add_action('load-'.$settings_page, 'scottcart_settings_help',10);
	
}
add_action("admin_menu", "scottcart_plugin_menu");



// fix highlighting for dashboard submenu items
function scottcart_select_highlight($file) {
	global $plugin_page,$submenu_file;
	
	$screen = get_current_screen();
	
	if ($screen->post_type == 'scottcart_order') {
		$plugin_page = 'edit.php?post_type=scottcart_order';
		$submenu_file = 'edit.php?post_type=scottcart_order';
	}
	
	if ($screen->post_type == 'scottcart_product') {
		$plugin_page = 'edit.php?post_type=scottcart_product';
		$submenu_file = 'edit.php?post_type=scottcart_product';
	}
	
	if ($screen->post_type == 'scottcart_customer') {
		$plugin_page = 'edit.php?post_type=scottcart_customer';
		$submenu_file = 'edit.php?post_type=scottcart_customer';
	}
	
	if ($screen->post_type == 'scottcart_discount') {
		$plugin_page = 'edit.php?post_type=scottcart_discount';
		$submenu_file = 'edit.php?post_type=scottcart_discount';
	}
	
	if ($screen->taxonomy == 'product_category') {
		$plugin_page = 'edit-tags.php?taxonomy=product_category&post_type=scottcart_product';
		$submenu_file = 'edit-tags.php?taxonomy=product_category&post_type=scottcart_product';
	}
	
	if ($screen->taxonomy == 'product_tag') {
		$plugin_page = 'edit-tags.php?taxonomy=product_tag&post_type=scottcart_product';
		$submenu_file = 'edit-tags.php?taxonomy=product_tag&post_type=scottcart_product';
	}
	
	if ($screen->taxonomy == 'product_feature') {
		$plugin_page = 'edit-tags.php?taxonomy=product_feature&post_type=scottcart_product';
		$submenu_file = 'edit-tags.php?taxonomy=product_feature&post_type=scottcart_product';
	}
	
}
add_filter('parent_file', 'scottcart_select_highlight');




// plugins menu links - for wordpress plugins page
function scottcart_action_links($links) {
	global $support_link, $edit_link, $settings_link;
	
	//$links[] = '<a href="" target="_blank">Support</a>';
	$links[] = "<a href='admin.php?page=scottcart_settings_page'>".__('Settings','scottcart')."</a>";
	
	return $links;
}
add_filter( 'plugin_action_links_' . SCOTTCART_PLUGIN_BASENAME, 'scottcart_action_links' );