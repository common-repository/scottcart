<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// settings array
function scottcart_settings() {

	$scottcart_settings_array = apply_filters('scottcart_settings_top_level', array(
		apply_filters('scottcart_settings_scottcart_tab', array(
			__('Dashboard','scottcart') => apply_filters('scottcart_settings_getting_started_page', array(
				__('Dashboard','scottcart') 	=>  array(
					'title' 		=> __( 'Dashboard', 'scottcart' ),
					'name' 			=> '',
					'type' 			=> 'dashboard'
				),
			)),
		)),
		apply_filters('scottcart_settings_general_tab', array(
			__('General','scottcart') =>
			array(
				__('Shop Location','scottcart') => array(
					'title' 		=> __( 'Base Country', 'scottcart' ),
					'name' 			=> 'base_country',
					'type' 			=> 'dropdown',
					'options' 		=> scottcart_get_country_list(),
					'class'			=> 'scottcart_base_country',
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Base State / Province', 'scottcart' ),
					'name' 			=> 'base_state',
					'type' 			=> 'dropdown',
					'options' 		=> scottcart_empty_list(),
					'class_div' 	=> 'scottcart_base_state',
					'default'		=> '0',
					'help' 			=> '',
				),
			),
			array(
				__('Currency','scottcart') => array(
					'title' 		=> __( 'Currency', 'scottcart' ),
					'name' 			=> 'currency',
					'type' 			=> 'dropdown',
					'options' 		=> scottcart_get_currencies(),
					'default'		=> 'USD',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Currency Symbol', 'scottcart' ),
					'name' 			=> 'currency_symbol',
					'type' 			=> 'input',
					'default'		=> '$',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Currency Position', 'scottcart' ),
					'name' 			=> 'currency_position',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Before - $99.99','scottcart'),
						__('After - 99.99$','scottcart'),
						__('Before with space - $ 99.99','scottcart'),
						__('After with space - 99.99 $','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Thousands Separator', 'scottcart' ),
					'name' 			=> 'currency_thousands',
					'type' 			=> 'input',
					'default'		=> ',',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Decimal Separator', 'scottcart' ),
					'name' 			=> 'currency_decimal',
					'type' 			=> 'input',
					'default'		=> '.',
					'help' 			=> '',
				),
			),
			array(
				__('Accounts','scottcart') => array(
					'title' 		=> __( 'Automatically create accounts', 'scottcart' ),
					'name' 			=> 'account',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Hide admin for subscribers', 'scottcart' ),
					'name' 			=> 'hide_admin',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help'			=> '',
				),
			),
			array(
				__('Default Text','scottcart') 		=>
				array(
					'title' 		=> __( 'Add to Cart', 'scottcart' ),
					'name' 			=> 'text_0',
					'type' 			=> 'input',
					'default'		=> __( 'Add to Cart', 'scottcart' ),
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Checkout', 'scottcart' ),
					'name' 			=> 'text_1',
					'type' 			=> 'input',
					'default'		=> __( 'Checkout', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'More Information', 'scottcart' ),
					'name' 			=> 'text_2',
					'type' 			=> 'input',
					'default'		=> __( 'More Information', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Place Order', 'scottcart' ),
					'name' 			=> 'text_3',
					'type' 			=> 'input',
					'default'		=> __( 'Place Order', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Purchase Options', 'scottcart' ),
					'name' 			=> 'text_4',
					'type' 			=> 'input',
					'default'		=> __( 'Purchase Options', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Update', 'scottcart' ),
					'name' 			=> 'text_5',
					'type' 			=> 'input',
					'default'		=> __( 'Update', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Price Options', 'scottcart' ),
					'name' 			=> 'text_6',
					'type' 			=> 'input',
					'default'		=> __( 'Price Options', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Style Options', 'scottcart' ),
					'name' 			=> 'text_7',
					'type' 			=> 'input',
					'default'		=> __( 'Style Options', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Quantity', 'scottcart' ),
					'name' 			=> 'text_8',
					'type' 			=> 'input',
					'default'		=> __( 'Quantity', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Features', 'scottcart' ),
					'name' 			=> 'text_features',
					'type' 			=> 'input',
					'default'		=> __( 'Features', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Purchase Confirmation', 'scottcart' ),
					'name' 			=> 'text_purchase_confirmation',
					'type' 			=> 'input',
					'default'		=> __( 'Purchase Confirmation', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Shop', 'scottcart' ),
					'name' 			=> 'text_shop',
					'type' 			=> 'input',
					'default'		=> __( 'Shop', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Cart', 'scottcart' ),
					'name' 			=> 'text_cart',
					'type' 			=> 'input',
					'default'		=> __( 'Cart', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Account', 'scottcart' ),
					'name' 			=> 'text_account',
					'type' 			=> 'input',
					'default'		=> __( 'Account', 'scottcart' ),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Order Cancelled', 'scottcart' ),
					'name' 			=> 'text_order_cancelled',
					'type' 			=> 'input',
					'default'		=> __( 'Order Cancelled', 'scottcart' ),
					'help'			=> '',
				),
			),
			array(
				__('Default Colors','scottcart') => array(
					'title' 		=> __( 'Button Color', 'scottcart' ),
					'name' 			=> 'button_color',
					'type' 			=> 'color',
					'default'		=> '#F6DA95',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Button Text Color', 'scottcart' ),
					'name' 			=> 'button_text_color',
					'type' 			=> 'color',
					'default'		=> '#000000',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Secondary Button Color', 'scottcart' ),
					'name' 			=> 'secondary_button_color',
					'type' 			=> 'color',
					'default'		=> '#EEEEEE',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Secondary Button Text Color', 'scottcart' ),
					'name' 			=> 'secondary_button_text_color',
					'type' 			=> 'color',
					'default'		=> '#000000',
					'help' 			=> '',
				),
			),
			array(
				__('Menu','scottcart') => array(
					'title' 		=> __( 'Hide Menu Cart Link', 'scottcart' ),
					'name' 			=> 'menu_cart_link',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('Should the menu item for the cart be hidden if no items are in the cart? <br /><br  /> Note: The cart page is based off the Pages -> Cart Page option.','scottcart'),
				),
			),
		)),
		apply_filters('scottcart_settings_shipping_tab', array(
				__('Pages','scottcart') 		=> array(
				__('Page Locations','scottcart') =>
				array(
					'title' 		=> __( 'Cart Page', 'scottcart' ),
					'name' 			=> 'cart_page',
					'type' 			=> 'pages',
					'default'		=> get_option('scottcart_pages')['cart_page'],
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Shop Page', 'scottcart' ),
					'name' 			=> 'shop_page',
					'type' 			=> 'pages',
					'default'		=> get_option('scottcart_pages')['shop_page'],
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Account Page', 'scottcart' ),
					'name' 			=> 'account_page',
					'type' 			=> 'pages',
					'default'		=> get_option('scottcart_pages')['account_page'],
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Purchase Confirmation Page', 'scottcart' ),
					'name' 			=> 'confirmation_page',
					'type' 			=> 'pages',
					'default'		=> get_option('scottcart_pages')['confirmation_page'],
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Order Cancellation Page', 'scottcart' ),
					'name' 			=> 'cancellation_page',
					'type' 			=> 'pages',
					'default'		=> get_option('scottcart_pages')['cancellation_page'],
					'help' 			=> '',
				),
			),
			array(
				__('Product Page','scottcart') =>
				array(
					'title' 		=> __( 'Zoom in on images', 'scottcart' ),
					'name' 			=> 'zoom',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('On the product page, when an image is hovered over with the mouse cursor, should the image be zoom in?','scottcart'),
				),
				array(
					'title' 		=> __( 'Menu Type', 'scottcart' ),
					'name' 			=> 'price_menu',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Dropdown (Default)','scottcart'),
						__('Radio','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('On the product page, should the purchase items be displayed using a dropdown menu or radio buttons? <br/><br /> Note: If the product it set to multi select then this option will have no effect.','scottcart'),
				),
				array(
					'title' 		=> __( 'Show Features', 'scottcart' ),
					'name' 			=> 'show_features',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('Should features be displayed on the product page?','scottcart'),
				),
				array(
					'title' 		=> __( 'Allow mutiple items to be added to the cart', 'scottcart' ),
					'name' 			=> 'mutiple_items',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('If your product has attributes then you might want to allow different options to be sold at the same time. <br /><br />Example: You sell a red and a blue shirt and you want to let the customer be able to buy both. <br /><br />Note: This is not related to quantity.','scottcart'),
				),
				array(
					'title' 		=> __( 'Hide if no quantity', 'scottcart' ),
					'name' 			=> 'hide_sold_out',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('If a product has inventory management enabled, and the quantity is 0, should the item be hidden so that it cannot be purchased?','scottcart'),
				),
				array(
					'title' 		=> __( 'Image Size', 'scottcart' ),
					'name' 			=> 'product_page_image_size',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Medium (Default)','scottcart'),
						__('Large','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
			),
			array(
				__('Shop Page','scottcart') => array(
					'title' 		=> __( 'Products Per Page', 'scottcart' ),
					'name' 			=> 'products_per_page',
					'type' 			=> 'input',
					'default'		=> '10',
					'help' 			=> __('How many products should show per shop page?<br /><br />Page navigation links will be displayed a the bottom of the page if necessary.','scottcart'),
				),
				array(
					'title' 		=> __( 'Box Width', 'scottcart' ),
					'name' 			=> 'box_width',
					'type' 			=> 'input',
					'default'		=> '300',
					'help'			=> __('The width of the box on the shop page, in pixels.','scottcart'),
				),
				array(
					'title' 		=> __( 'Box Height', 'scottcart' ),
					'name' 			=> 'box_height',
					'type' 			=> 'input',
					'default'		=> '',
					'help'			=> __('The height of the box on the shop page, in pixels. Leave blank to automatically set height.','scottcart'),
				),
				array(
					'title' 		=> __( 'Use Theme Title', 'scottcart' ),
					'name' 			=> 'shop_plugin_heading',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes - Theme Title (Default)','scottcart'),
						__('No -  Plugin Title','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('You can use this setting to switch between using the plugin page title or the theme title.','scottcart'),
				),
				array(
					'title' 		=> __( 'Image Size', 'scottcart' ),
					'name' 			=> 'shop_page_image_size',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Medium (Default)','scottcart'),
						__('Large','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
			),
			array(
				__('Cart Page','scottcart') => array(
					'title' 		=> __( 'Enable coupons', 'scottcart' ),
					'name' 			=> 'coupon',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Name fields', 'scottcart' ),
					'name' 			=> 'customer_name',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('First & Last (Default)','scottcart'),
						__('First','scottcart'),
						__('None','scottcart'),
					),
					'default'		=> '0',
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Show billing address', 'scottcart' ),
					'name' 			=> 'billing_address',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help'			=> __('Billing Address will still show if tax is enabled and the caculate tax method is set to Billing Address.','scottcart'),
				),
				array(
					'title' 		=> __( 'Redirect to checkout immediately', 'scottcart' ),
					'name' 			=> 'redirect',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Allow quantity to be changed', 'scottcart' ),
					'name' 			=> 'quantity_product_page',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Show total section', 'scottcart' ),
					'name' 			=> 'cart_show_totals',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('Should the totals section be shown at the bottom of the cart page.','scottcart'),
				),
				array(
					'title' 		=> __( 'Hide Theme Primary Menu', 'scottcart' ),
					'name' 			=> 'hide_cart_theme_menu',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('Should the themes primary menu be hidden on the cart page. Setting this to "yes" may increase your sales and conversion rates.','scottcart'),
				),
				array(
					'title' 		=> __( 'Use theme title', 'scottcart' ),
					'name' 			=> 'cart_plugin_heading',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes - Theme Title (Default)','scottcart'),
						__('No - Plugin Title','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('You can use this setting to switch between using the plugin page title or the theme title.','scottcart'),
				),
			),
			array(
				__('Purchase Confirmation Page','scottcart') => array(
					'title' 		=> __( 'Page Text', 'scottcart' ),
					'name' 			=> 'purchase_Confirmation_page_text',
					'id' 			=> 'purchase_Confirmation_page_text_id',
					'desc' 			=> '',
					'type' 			=> 'editor',
					'default'		=> __('Thank you for your purchase.','scottcart'),
					'help' 			=> '',
				),
			),
			array(
				__('Order Cancelled Page','scottcart') => array(
					'title' 		=> __( 'Page Text', 'scottcart' ),
					'name' 			=> 'order_cancelled_page_text',
					'id' 			=> 'order_cancelled_page_text_id',
					'desc' 			=> '',
					'type' 			=> 'editor',
					'default'		=> __('Your order has been cancelled.','scottcart'),
					'help' 			=> '',
				),
			),
		)),
		apply_filters('scottcart_settings_shipping_tab', array(
				__('Shipping','scottcart') 		=> array(
				__('Shipping General','scottcart') => array(
					'title' 		=> __( 'Enable Shipping', 'scottcart' ),
					'name' 			=> 'shipping',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
			),
			array(
				__('Shipping Types','scottcart') => array(
					'title' 		=> __( 'Shipping Types', 'scottcart' ),
					'name' 			=> 'send_customer_email',
					'type' 			=> 'multiadd_shipping_types',
					'options' 		=> '',
					'help' 			=> '',
				),
			),
			array(
				__('Shipping Rates','scottcart') => array(
					'title' 		=> __( 'Shipping Rates', 'scottcart' ),
					'name' 			=> 'shipping_rates',
					'type' 			=> 'multiadd_shipping',
					'options' 		=> '',
					'help' 			=> '',
				),
			),
		)),
		apply_filters('scottcart_settings_tax_tab', array(
			__('Tax','scottcart') => array(
				__('Tax General','scottcart') => array(
					'title' 		=> __( 'Enable Tax', 'scottcart' ),
					'name' 			=> 'tax',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Caculate Tax', 'scottcart' ),
					'name' 			=> 'caculate_tax',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Shipping Address','scottcart'),
						__('Billing Address','scottcart'),
						//__('Shop Location','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('What location should be used to caculate tax? <br /><br /> Note: If this is set to Billing Address then the Billing Address fields will be displalyed at checkout, if they are not already.','scottcart'),
				),
			),
			array(
				__('Tax Rates','scottcart') => array(
					'title' 		=> __( 'Tax Rates', 'scottcart' ),
					'name' 			=> 'tax_rates',
					'type' 			=> 'multiadd_tax',
					'options' 		=> '',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Fallback Tax Rate', 'scottcart' ),
					'name' 			=> 'fallback_tax_rate',
					'type' 			=> 'input',
					'options' 		=> '',
					'default'		=> '',
					'help' 			=> __('This tax rate will be used if no others apply.','scottcart'),
				),
			),
		)),
		apply_filters('scottcart_settings_payments_tab', array(
			__('Payments','scottcart') => array(
				__('Payments General','scottcart') => array(
					'title' 		=> __( 'Default Gateway', 'scottcart' ),
					'name' 			=> 'default_gateway',
					'type' 			=> 'dropdown',
					'default' 		=> 'paypal_standard',
					'options' 		=> scottcart_load_gateways(4),
					'help' 			=> '',
				),
			),
		)),
		apply_filters('scottcart_settings_email_tab', array(
			__('Email','scottcart') => array(
				__('Email Settings','scottcart') => array(
					'title' 		=> __( 'From Name', 'scottcart' ),
					'name' 			=> 'site_from',
					'type' 			=> 'input',
					'default'		=> get_bloginfo('name'),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'From Email', 'scottcart' ),
					'name' 			=> 'site_email',
					'type' 			=> 'input',
					'default'		=> get_bloginfo('admin_email'),
					'help'			=> '',
				),
				
			),
			array(
				__('Customer Purchase Email','scottcart') => array(
					'title' 		=> __( 'Enable', 'scottcart' ),
					'name' 			=> 'send_customer_email',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Subject', 'scottcart' ),
					'name' 			=> 'customer_subject',
					'type' 			=> 'input',
					'default'		=> __('Your order #{order_num} is complete','scottcart'),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Body', 'scottcart' ),
					'id' 			=> 'customer_email_template_id',
					'name' 			=> 'customer_email_template',
					'type' 			=> 'editor',
					'default'		=> __('Thank you for your purchase.','scottcart'),
					'help'			=> '',
					'desc'			=> '
						<br /><u>'. __( 'Variables (Can be used in subject and body fields)', 'scottcart' ).':</u>
						<br />{customer_email}
						<br />{customer_first_name}
						<br />{customer_last_name}
						<br />{order_status}
						<br />{discount_code_used}
						<br />{discount_code_amount}
						<br />{txn_total}
						<br />{txn_shipping}
						<br />{txn_tax}
						<br />{order_num}
						<br />{txn_id}
						<br />{shipping_details}
						<br />{billing_details}
						<br /><u>'. __( 'Variables (Can only be used in body field)', 'scottcart' ).':</u>
						<br />{sold_table}'
				),
			),
			array(
				__('Admin Purchase Email','scottcart') => array(
					'title' 		=> __( 'Enable', 'scottcart' ),
					'name' 			=> 'send_admin_email',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				array(
					'title' 		=> __( 'Admin email address', 'scottcart' ),
					'name' 			=> 'admin_email',
					'type' 			=> 'input',
					'default'		=> get_bloginfo('admin_email'),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Admin CC email address', 'scottcart' ),
					'name' 			=> 'admin_cc_email',
					'type' 			=> 'input',
					'default'		=> '',
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Subject', 'scottcart' ),
					'name' 			=> 'admin_subject',
					'type' 			=> 'input',
					'default'		=> __('A new order (#{order_num}) has been placed order','scottcart'),
					'help'			=> '',
				),
				array(
					'title' 		=> __( 'Body', 'scottcart' ),
					'id' 			=> 'admin_email_template_id',
					'name' 			=> 'admin_email_template',
					'type' 			=> 'editor',
					'default'		=> __('A new order has been placed.','scottcart'),
					'help'			=> '',
					'desc'			=> '
						<br /><u>'. __( 'Variables (Can be used in subject and body fields)', 'scottcart' ).':</u>
						<br />{customer_email}
						<br />{customer_first_name}
						<br />{customer_last_name}
						<br />{order_status}
						<br />{discount_code_used}
						<br />{discount_code_amount}
						<br />{txn_total}
						<br />{txn_shipping}
						<br />{txn_tax}
						<br />{order_num}
						<br />{txn_id}
						<br />{shipping_details}
						<br />{billing_details}
						<br /><u>'. __( 'Variables (Can only be used in body field)', 'scottcart' ).':</u>
						<br />{sold_table}'
				),
			),
		)),
		apply_filters('scottcart_settings_advanced_tab', array(
			__('Advanced','scottcart') => array(
				__('CSS','scottcart') => array(
					'title' 		=> __( 'Custom CSS', 'scottcart' ),
					'name' 			=> 'custom_css',
					'type' 			=> 'textarea',
					'rows'			=> '10',
					'help' 			=> __('You can enter CSS (Cascading Style Sheets) code here to override the default public styles.','scottcart'),
				),
				array(
					'title' 		=> __( 'Enable Default CSS', 'scottcart' ),
					'name' 			=> 'default_css',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('Yes (Default)','scottcart'),
						__('No','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> __('Disable all default CSS stylesheets.','scottcart'),
				),
				
			),
			array(
				__('Plugin Data','scottcart') => array(
					'title' 		=> __( 'Remove all plugin data on uninstall', 'scottcart' ),
					'name' 			=> 'uninstall',
					'type' 			=> 'dropdown',
					'options' 		=> array (
						__('No (Default)','scottcart'),
						__('Yes','scottcart'),
					),
					'default'		=> '0',
					'help' 			=> '',
				),
				
			),
		)),
	));
	
	return $scottcart_settings_array;
}

// render menu
function scottcart_settings_render_menu() {

	global $scottcart_options;
	
	// get settings
	$settings = scottcart_settings();
	
	// make array
	$tabs_array = [];
	$tabs_array_level1 = [];
	$tabs_array_level2 = [];
	
	$level = "0";
	foreach ($settings as $tab) {
		
		$tabs_array_level1[] = key($tab);
		
		foreach ($tab as $element) {
			
			$tabs_array_level2[$level][] = key($element);
			
		}
		$level++;
		
		
	}
		
	$tabs_array = array_merge(array($tabs_array_level1),$tabs_array_level2);

	// make tabs
	if (!empty($scottcart_options['tab'])) {
		$scottcart_active_tab =  $scottcart_options['tab'];
	} else {
		$scottcart_active_tab = "tab00";
	}
	
	if (isset($_GET['tab'])) {
		$scottcart_active_tab = "tab".intval($_GET['tab']);
	}
	
	$scottcart_active_tab_top = substr($scottcart_active_tab, 0, -1);
	
	echo "<table width='100%'><tr><td width='90%'>";
	
	echo "<span class='dashicons dashicons-cart'></span><span id='scottcart-menu-title'>&nbsp;"; echo SCOTTCART_NAME; echo " "; echo __('Settings','scottcart'); echo "</span><br /><span class='scottcart-menu-sub-title'>";
	echo __(' Version ','scottcart'); echo SCOTTCART_VERSION; echo "</span><br />";
	//echo "<br /><span class='scottcart-menu-sub-title'> &nbsp; &nbsp; &nbsp; &nbsp;"; echo __('eCommerce built to perfection.','scottcart'); echo "</span>";
	
	
	echo "</td><td width='10%' valign='bottom'>";
	echo "<input name='submit' id='submit' class='button button-primary scottcart-settings-button' value='Save Changes' type='submit'>";
	echo "</td></tr></table>";

	// menu div
	echo "<div id='scottcart-menu-div'>";
	
	
	// menu level 1
	echo "<h1 class='nav-tab-wrapper'>";
	
	$counter = "0";
	foreach ($tabs_array as $tabs => $tab) {
		
		if (!empty($tab[0])) {
			if ($tabs == 0) {
				echo "<ul id='scottcart-tabs'>";
				foreach ($tab as $count => $title) {
					echo "<li><a href='#' id='tab$count$counter' class='nav-tab"; if ($scottcart_active_tab_top == "tab".$count) { echo " nav-tab-active'"; } echo "'>$title</a></li>";
				}
				echo "</ul>";
			}
		}
		
	}
	echo "</h1>";
	
	
	// menu level 2
	$counter = "0";
	
	foreach ($tabs_array as $tabs => $tab) {
		if ($tabs > 0) {
			
			// remove any empty elements - this is necessary for extensions that add a new top level tab
			$tab = array_filter($tab);
			$tab = array_values($tab);
			
			echo "<ul id='scottcart-tabs-more' class='subsubsub scottcart-more scottcart-more-tab$counter'"; if ($scottcart_active_tab_top == "tab".$counter) { echo "style='display: block;'"; } echo ">";
			$tab_count = count( $tab );
			$tab_count--;
			foreach ($tab as $count => $title) {
				if (!empty($title)) {
					echo "<li><a href='#' id='tab$counter$count' class='tab-more tab"; echo $counter.$count; echo "T"; if ($scottcart_active_tab == "tab".$counter.$count) { echo " current '"; } echo "'>$title</a>";
					
					if ($tab_count > $count) {
					echo "|";
					}
					
					echo "</li>";
				}
			}
			echo "</ul>";
			$counter++;
			
		}
	}
	
	echo "</div>";
	
	settings_errors();
	
	return;
}


// dashboard items array
function scottcart_dashboard_api() {
	$scottcart_dashboard_array = apply_filters('scottcart_dashboard_array', array());
	return $scottcart_dashboard_array;
}

// license items array
function scottcart_licenses_list() {
	$scottcart_license_list_array = apply_filters('scottcart_licenses_list', array());
	return $scottcart_license_list_array;
}



// render option types
// allowed types
// -------------
// text - 						plain text - 						public use
// dropdown - 					dropdown menu - 					public use
// input - 						input field - 						public use
// color - 						color picker - 						public use
// editor - 					wordpress rich editor - 			public use
// textarea - 					plain textarea - 					public use
// hr - 						horzontal rule - 					public use
// title - 						bold text in title column - 		public use
// pages - 						pages installed at install -		public use
// categories - 				product categories -				public use
// license_list - 				list of licenses, can be added to - public use
// dashboard -					settings dashboard section - 		private use
// multiadd_tax - 				tax section  - 						private use
// multiadd_shipping - 			shipping with rates section - 		private use
// multiadd_shipping_types -	shipping types section - 			private use

function scottcart_settings_render_option($item) {

	global $scottcart_options;
	
	extract($item);
	
	if (!isset($default)) { $default = ''; }
	if (!isset($class)) { $class = ''; }
	if (!isset($class_div)) { $class_div = ''; }

	// text
	if ($type == "text") {
		echo "<table><tr><td>";
		echo $options;
		echo "</td></tr></table>";
	}
	
	// dropdown
	if ($type == "dropdown") {
		
		echo "<table><tr><td  class='scottcart_cell_title_width'>";
		echo "$title:</td><td>";
		echo "<div class='$class_div'><select name='scottcart_settings[$name]' class='scottcart_cell_width $class'>";
		
		$count = "0";
		foreach($options as $key => $value) {
			echo "<option ";
			if (!empty($scottcart_options[$name])) {
				if ($scottcart_options[$name] == $count || $scottcart_options[$name] == $key) { echo " SELECTED "; }
			} else {
				if ($count == $default || $key == $default) { echo " SELECTED "; }
			}
			echo "value='$key'>"; echo $value; echo "</option>";
			$count++;
		}
		
		echo "</select></div></td><td>";
		if (!empty($help)) {
			echo "<span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
		}
		echo "</td></tr></table>";
	}
	
	// pages
	if ($type == "pages") {
		
		echo "<table><tr><td class='scottcart_cell_title_width'>";
		echo "$title:</td><td>";
		
		echo "<select name='scottcart_settings[$name]' class='scottcart_cell_width'>";
		
		$args = array(
			'sort_order' 	=> 'asc',
			'sort_column' 	=> 'post_title',
			'post_type' 	=> 'page',
			'post_status' 	=> 'publish'
		); 
		$pages = get_pages($args);
		
		foreach($pages as $page) {
			echo "<option ";
			if (!empty($scottcart_options[$name])) {
				if ($scottcart_options[$name] == $page->ID) { echo " SELECTED "; }
			} else {
				if ($page->ID == $default) { echo " SELECTED "; }
			}
			echo "value='"; echo $page->ID; echo "'>"; echo $page->post_title; echo "</option>";
		}
		
		echo "</select>";
		if (!empty($help)) {
			echo "<span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
		}
		echo "</td></tr></table>";
	}
	
	// categories
	if ($type == "categories") {
		
		echo "<table><tr><td class='scottcart_cell_title_width'>";
		echo "$title:</td><td>";
		
		echo "<select name='scottcart_settings[$name]' class='scottcart_cell_width'><option></option>";
		
		$args = array (
		'taxonomy'		=> 'product_category',
		'orderby'		=> 'name',
		'order'			=> 'ASC',
		);
		$categories = get_categories($args);
		
		foreach($categories as $category) {
			
			echo "<option ";
			if (!empty($scottcart_options[$name])) {
				if ($scottcart_options[$name] == $category->slug) { echo " SELECTED "; }
			} else {
				if ($category->slug == $default) { echo " SELECTED "; }
			}
			echo "value='"; echo $category->slug; echo "'>"; echo $category->name; echo "</option>";
		}
		
		echo "</select>";
		if (!empty($help)) {
			echo "<span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
		}
		echo "</td></tr></table>";
	}
	
	// input
	if ($type == "input") {
		echo "<table><tr><td class='scottcart_cell_title_width'>";
		echo "$title:</td><td>";
		echo "<input class='scottcart_cell_width' type='text' name='scottcart_settings[$name]' value="; echo '"'; if (!empty($scottcart_options[$name])) { echo $scottcart_options[$name]; } else { echo $default; } echo '"'; echo ">";
		
		if (!empty($help)) {
			echo " <span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
		}
		
		echo "</td></tr></table>";
	}
	
	// hr
	if ($type == "hr") {
		echo "<hr>";
	}
	
	// title
	if ($type == "title") {
		echo "<table><tr><td class='scottcart_cell_title_width'>";
		echo "<b>$title</b></td><td>";
		echo "</td></tr></table>";
	}
	
	// dashboard
	if ($type == "dashboard") {
		
		$dashboard_array = scottcart_dashboard_api();
		
		foreach ($dashboard_array as $box) {
			echo "<div class='postbox scottcart_settings_dashboard_item'>";
				
				echo "<h2 class='hndle'><span>";
					echo $box['title'];
				echo "</span></h2>";
				
				echo "<div class='inside'>";
					echo $box['body'];
				echo "</div>";
				
			echo "</div>";
		}
		
	}
	
	// color
	if ($type == "color") {
		echo "<table><tr><td class='scottcart_cell_title_width'>";
		echo "$title:</td><td>";
		echo "<input class='scottcart_cell_width scottcart_colorpicker' type='text' name='scottcart_settings[$name]' value='"; if (!empty($scottcart_options[$name])) { echo $scottcart_options[$name]; } else { echo $default; } echo "'>";
		
		if (!empty($help)) {
			echo "<span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
		}
		
		echo "</td></tr></table>";
	}
	
	// editor
	if ($type == "editor") {
		echo "<table width='70%'><tr><td class='scottcart_cell_title_width' valign='top'>";
		echo "<br />$title:</td><td>";
		$content = "";
		if (empty($scottcart_options[$name])) { $content = $default; } else { $content = $scottcart_options[$name]; }
		$content = stripslashes($content);
		$editor_id = $id;
		wp_editor($content, $editor_id, $settings = array(
			'textarea_name' => "scottcart_settings[$name]"
		));
		echo "</td></tr><tr><td></td><td>";
		
		echo $desc;
		
		
		echo "</td></tr></table>";
	}
	
	// textarea sysytem info
	if ($type == "textarea") {
		
		if (isset($load)) {
			echo "<table width='80%'><tr><td class='scottcart_cell_title_width' valign='top'>";
			echo "$title:</td><td>";
			echo "<a href='#' id='scottcart_load_function' class='scottcart_$name' data-placeholder='$load'>"; echo __('Generate Report','scottcart'); echo "</a>";
			echo "<textarea style='width:100%;display:none;' rows='20' class='Tscottcart_$name' readonly>";
			echo"</textarea>";
		} else {
			echo "<table><tr><td class='scottcart_cell_title_width' valign='top'>";
			echo "$title:</td><td>";
			if (!isset($cols)) { $cols = '34'; }
			if (!isset($rows)) { $rows = '5'; }
			echo "<textarea rows='$rows' cols='$cols' name='scottcart_settings[$name]'>";
				if (!empty($scottcart_options[$name])) { echo $scottcart_options[$name]; } else { echo $default; }
			echo"</textarea></td><td valign='top'>";
			if (!empty($help)) {
				echo "<span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='$help'></span>";
			}
		}
		
		echo "</td></tr></table>";
	}
	
	
	
	// license_list
	if ($type == "license_list") {
		$licenses = scottcart_licenses_list();
		foreach ($licenses as $license) {
			foreach ($license as $license_details) {
				
				echo "<table>";
				
				echo "<tr><td><b>";
				echo $license_details['name'];
				echo "</b></td></tr>";
				
				echo "<tr><td class='scottcart_cell_title_width'>";
				echo __('Key','scottcart'),":";
				echo "</td><td>";
				echo "<input name='scottcart_settings[".$license_details['slug']."]' size='45' type='text' value='"; if (!empty($license_details['key'])) { echo $license_details['key']; } echo "'>";
				
				// license nonce
				wp_nonce_field($license_details['slug'].'_nonce', $license_details['slug'].'_nonce');
				
				
				if ($license_details['status'] !== false && $license_details['status'] == 'active' ) {
					// active
					echo "<input type='submit' class='button-secondary' name='".$license_details['slug']."_license_deactivate' value='"; echo __('Deactivate License','scottcart'); echo "'>";
				} else {
					// inactive
					echo "<input type='submit' class='button-secondary' name='".$license_details['slug']."_license_activate' value='"; echo __('Activate License','scottcart'); echo "'>";
				}
				
				echo "</td></tr>";
				
				
				
				
				echo "<tr><td class='scottcart_cell_title_width'>";
				echo __('Status','scottcart'),":";
				echo "</td><td>";
				
				if($license_details['status'] !== false && $license_details['status'] == 'active' ) {
					
					echo "<span style='color:green;'>"; echo __('Active','test_plugin'); echo "</span> </td></tr><tr><td>";
					
					echo __('Expires','test_plugin');
					
					echo ": </td><td>";
					
					if ($license_details['expires'] == '0') {
						echo __('Never','test_plugin');
					} else {
						// get wp date format
						$date_format = get_option('date_format');
						// convert timestamp to wp date format
						echo date($date_format,$license_details['expires']);
					}
					
					echo "</td></tr>";
					
				} else {
					echo "<span style='color:red;'>"; echo __('Inactive','test_plugin'); echo "</span> </td></tr><tr><td>";
					
					if (!empty($license_details['message'])) {
						echo __('Message','test_plugin');
						
						echo ": </td><td>";
						
						echo $license_details['message'];
					}
				}
				
				echo "</td></tr>";
				
				
				echo "</table><hr>";
			}
		}
	}
	
	
	
	
	
	
	
	
	
	// multiadd tax
	if ($type == "multiadd_tax") {
		
		echo "<table width='80%'><tr><td class='scottcart_cell_title_width' valign='top'>";
			echo "$title:</td><td>";
			
			echo "<div id='scottcart_tax_div'>";
				
				echo "<table width='100%' class='form-table' id='scottcart_tax_table'><tr valign='top'><td width='15px'></td><td width='100px'>"; echo __('Country','scottcart'); echo"</td><td width='100px'>"; echo __('Entire Country','scottcart'); echo"</td><td width='100px'>"; echo __('State / Province','scottcart'); echo "</td><td width='100px'>"; echo __('Rate','scottcart'); echo "</td><td width='100px'>"; echo __('Tax Shipping','scottcart'); echo "</td><td width='100px'></td></tr>";
					
					$counter = "1";
					if (empty($scottcart_options['tax_count'])) { $tax_count = "0"; } else { $tax_count = $scottcart_options['tax_count']; }
					for($i=0;$i<$tax_count;$i++) {
						
						echo "<tr valign='top'><td class='row-id'>$counter</td>";
						
							echo "<td><div class='scottcart_tax_country_list_div'>";
							echo "<select class='scottcart_input scottcart_tax_country' name='scottcart_settings[tax_country][]'>";
							$country_list = scottcart_get_country_list();
							foreach ($country_list as $country_id => $country) {
								echo "<option value='$country_id'"; if (isset($scottcart_options['tax_country'][$i])) { if ($country_id == $scottcart_options['tax_country'][$i]) { echo " SELECTED "; } } echo ">$country</option>";
							}
							echo "</select>";
							
						echo "</div></td>";
							
							echo "<td class='scottcart_tax_entire'>";
							echo "<input type='hidden' value='$i' class='scottcart_row_id'>";
							echo "<input type='checkbox' class='scottcart_tax_entire' name='scottcart_settings[tax_entire][$i]' "; if (isset($scottcart_options['tax_entire'][$i])) { if ($scottcart_options['tax_entire'][$i] == $i) { echo " CHECKED "; } } echo "value='$i'></td>";
							
							echo "<td><div class=' scottcart_tax_state_list_div'>";
							echo "<input type='text' class='scottcart_input scottcart_tax_state' name='scottcart_settings[tax_state][]' value='"; if (!empty($scottcart_options['tax_state'][$i])) {  echo $scottcart_options['tax_state'][$i]; } echo "'></div></td>";
							
							echo "<td class='scottcart_tax_rate'>";
							echo "<input type='text' size='5' name='scottcart_settings[tax_rate][]' value='"; if (!empty($scottcart_options['tax_rate'][$i])) {  echo $scottcart_options['tax_rate'][$i]; } echo "'>";
							
							echo "<td class='scottcart_tax_shipping'>";
							echo "<input type='checkbox' name='scottcart_settings[tax_shipping][$i]' "; if (isset($scottcart_options['tax_shipping'][$i])) { if ($scottcart_options['tax_shipping'][$i] == $i) { echo " CHECKED "; } } echo "value='$i'></td>";
							
					echo "</td><td>";
						
						echo "<a href='javascript:void(0);' class='scottcart_remCF_tax_rate'><span class='dashicons dashicons-trash'></span></a></td></tr>";
						$counter++;
						
					}
					
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_tax_rate'>"; echo __('Add New Tax Rate','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			echo "<input type='hidden' name='scottcart_settings[tax_count]' id='tax_count' value='$i'>";
		echo "</td></tr></table>";
		
	}
	
	
	// multiadd shipping
	if ($type == "multiadd_shipping") {
		
		echo "<table width='80%'><tr><td class='scottcart_cell_title_width' valign='top'>";
			echo "$title:</td><td>";
			
				echo "<table width='100%' class='form-table' id='scottcart_shipping_table_main'><tr valign='top'><td width='15px'></td><td width='150px'>"; echo __('Location','scottcart'); echo"</td><td width='150px'>"; echo __('Entire Country','scottcart'); echo"</td><td width='150px'>"; echo __('State / Province','scottcart'); echo "</td><td width='20px'></td></tr>";
					
					$count = "0";
					$counter = "1";
					if (empty($scottcart_options['shipping_count'])) { $shipping_count = "0"; } else { $shipping_count = $scottcart_options['shipping_count']; }
					for($i=0;$i<$shipping_count;$i++) {
						
						echo "<tr valign='top'><td class='row-ida'>$counter</td><td width='150px'>";
							
							echo "<div class='scottcart_shipping_country_list_div'>";
							echo "<select class='scottcart_input scottcart_shipping_country' name='scottcart_settings[shipping_country][]'><option></option><option value='worldwide' "; if (isset($scottcart_options['shipping_country'][$i])) { if ('worldwide' == $scottcart_options['shipping_country'][$i]) { echo " SELECTED "; } } echo ">Worldwide</option>";
							$country_list = scottcart_get_country_list();
							foreach ($country_list as $country_id => $country) {
								echo "<option value='$country_id'"; if (isset($scottcart_options['shipping_country'][$i])) { if ($country_id == $scottcart_options['shipping_country'][$i]) { echo " SELECTED "; } } echo ">$country</option>";
							}
							echo "</select>";
							
							echo "</div>";
							
						echo "</td>";
							
						echo "<td class='scottcart_shipping_entire' width='150px'>";
							echo "<input type='hidden' value='$i' class='scottcart_row_id'>";
							echo "<input type='checkbox' class='scottcart_shipping_entire' name='scottcart_settings[shipping_entire][$i]' "; if (isset($scottcart_options['shipping_entire'][$i])) { if ($scottcart_options['shipping_entire'][$i] == $i) { echo " CHECKED "; } } echo "value='$i'></td>";
							
						echo "<td width='150px'><div class='scottcart_shipping_state_list_div'>";
							echo "<input type='text' class='scottcart_input scottcart_shipping_state' name='scottcart_settings[shipping_state][]' value='"; if (!empty($scottcart_options['shipping_state'][$i])) {  echo $scottcart_options['shipping_state'][$i]; } echo "'></div></td>";
							
						echo "</td><td width='20px'>";
							
							echo "<a href='javascript:void(0);' class='scottcart_remCF_shipping_location'><span class='dashicons dashicons-trash'></span></a>";
							echo "<input type='hidden' name='scottcart_settings[shipping_count$counter]' id='shipping_count$counter' value='"; echo $scottcart_options['shipping_count'.$counter]; echo "'></td></tr>";
							
							// add new shipping rate
							echo "<tr class='scottcart_shipping_location_row'><td></td><td colspan='3'>";
							
							echo "<table id='scottcart_shipping_table$counter' class='scottcart_shipping_table scottcart_shipping_table_rate' width='100%'><tr><td width='15px'></td><td width='150px'>"; echo __('Type','scottcart'); echo "</td><td width='150px'>"; echo __('Rate','scottcart'); echo "</td><td width='150px'>"; echo __('Rate For Each Additional Item','scottcart'); echo "</td><td width='20px'></td></tr>";
							
							for($a=0;$a<$scottcart_options['shipping_count'.$counter];$a++) {
								
								echo "<tr valign='top'><td width='15px'></td><td width='150px'>";
								
								echo "<select class='scottcart_input' name='scottcart_settings[shipping_type][$counter][]'>";
								
								for($c=0;$c<$scottcart_options['shipping_types_count'];$c++) {
									echo "<option value='$c'"; if (!empty($scottcart_options['shipping_type'][$counter][$a]) || $scottcart_options['shipping_type'][$counter][$a] == '0') if ($scottcart_options['shipping_type'][$counter][$a] == $c) { { echo " SELECTED "; } } echo ">"; echo $scottcart_options['shipping_types_name'][$c]; echo "</option>";
								}
								echo "</select>";
								
								echo "</td><td width='150px'>";
								
								echo "<input type='text' name='scottcart_settings[shipping_rate][$counter][]' class='scottcart_input' value='"; if (!empty($scottcart_options['shipping_rate'][$counter][$a])) { echo $scottcart_options['shipping_rate'][$counter][$a]; } echo "'></td>";
								
								
								echo "</td><td width='150px'>";
								
								echo "<input type='text' name='scottcart_settings[shipping_rate_additional][$counter][]' class='scottcart_input' value='"; if (!empty($scottcart_options['shipping_rate_additional'][$counter][$a])) { echo $scottcart_options['shipping_rate_additional'][$counter][$a]; } echo "'></td>";
								
								
								echo "<td width='150px'><a href='javascript:void(0);' class='scottcart_remCF_shipping_rate' data-id='$counter'><span class='dashicons dashicons-trash'></span></a></td></tr>";
								
								$count++;
							}
							
							echo "</table>";
							
							echo "<table width='100%' class='scottcart_shipping_table_rate scottcart_shipping_table_rate_row'><tr><td width='15px'></td><td><a href='javascript:void(0);' class='scottcart_addCF_shipping_rate' data-id='$counter'>"; echo __('Add Shipping Rate','scottcart');echo "</a></td></tr></table>";
							
							echo "</td></tr>";
							
						$counter++;
					}
					
				echo "</table>";
				
				echo "<table width='100%' class='form-table'><tr><td width='100px' colspan='2'><a href='javascript:void(0);' class='scottcart_addCF_shipping_location'>"; echo __('Add Shipping Location','scottcart');echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
				
			echo "<input type='hidden' name='scottcart_settings[shipping_count]' id='shipping_count' value='$i'>";
		echo "</td></tr></table>";
		
	}
	
	
	// multiadd shipping types
	if ($type == "multiadd_shipping_types") {
		
		echo "<table width='80%'><tr><td class='scottcart_cell_title_width' valign='top'>";
			echo "$title:</td><td>";
			
			echo "<div id='scottcart_shipping_types_div'>";
				
				echo "<table width='100%' class='form-table' id='scottcart_shipping_types_table'><tr valign='top'><td width='15px'></td><td width='100px'>"; echo __('Name','scottcart'); echo"</td><td width='100px'>"; echo __('Description','scottcart'); echo"</td><td width='100px'></td></tr>";
					
					$counter = "1";
					if (empty($scottcart_options['shipping_types_count'])) { $shipping_types_count = "0"; } else { $shipping_types_count = $scottcart_options['shipping_types_count']; }
					for($i=0;$i<$shipping_types_count;$i++) {
						
						echo "<tr valign='top' class='scottcart_shipping_types_location_row'><td class='row-id'>$counter</td>";
							
							echo "<td><input type='text' class='scottcart_input scottcart_shipping_types_name' name='scottcart_settings[shipping_types_name][]' value='"; if (!empty($scottcart_options['shipping_types_name'][$i])) {  echo $scottcart_options['shipping_types_name'][$i]; } echo "'></td>";
							
							echo "<td><input type='text' class='scottcart_input scottcart_shipping_types_desc' name='scottcart_settings[shipping_types_desc][]' value='"; if (!empty($scottcart_options['shipping_types_desc'][$i])) {  echo $scottcart_options['shipping_types_desc'][$i]; } echo "'></td>";
							
						echo "<td>";
						
						echo "<a href='javascript:void(0);' class='scottcart_remCF_shipping_type'><span class='dashicons dashicons-trash'></span></a></td></tr>";
						$counter++;
						
					}
					
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='100px' colspan='2'><a href='javascript:void(0);' class='scottcart_addCF_shipping_type'>"; echo __('Add Shipping Type','scottcart');echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			echo "<input type='hidden' name='scottcart_settings[shipping_types_count]' id='shipping_types_count' value='$i'>";
			echo "<input type='hidden' name='scottcart_settings[shipping_types_count_initial]' value='1'>";
			echo "<input type='hidden' id='scottcart_confirm_delete_msg' value='"; echo __('If you delete this shipping type, the order of shipping types on the Shipping Rates tab may be lost. Continue?','scottcart'); echo "'>";
		echo "</td></tr></table>";
		
	}
	
	
}




// render settings page
function scottcart_settings_render() {

	global $scottcart_active_tab,$scottcart_options;
	
	$settings = scottcart_settings();
	
	// make tabs
	if (!empty($scottcart_options['tab'])) {
		$scottcart_active_tab =  $scottcart_options['tab'];
	} else {
		$scottcart_active_tab = "tab00";
	}
	
	if (isset($_GET['tab'])) {
		$scottcart_active_tab = "tab".intval($_GET['tab']);
	}
	
	echo "<div class='metabox-holder'>";

	$tab_id = "0";
	foreach ($settings as $tab) {
		
		// remove any empty elements - this is necessary for extensions that add a new top level tab
		$tab = array_filter($tab);
		$tab = array_values($tab);
		
		$page_id = "0";
		foreach ($tab as $element) {
			
			echo "<div class='"; if ($tab_id != '0') { echo "postbox"; } echo " scottcart-container' "; if ($scottcart_active_tab == "tab$tab_id$page_id") { echo "style='display:block;'"; } else { echo "style='display:none;'"; } echo " id='tab"; echo $tab_id; echo $page_id;  echo "C'>";
				
				if ($tab_id != '0') {
					echo "<h2 class='hndle'><span>";
						// Show title of tab. If tab does not have a title then use element title.
						$title = key($element);
						if (empty($title)) {
							foreach ($element as $item) {
								echo $item['title'];
							}	
						} else {	
							echo key($element);
						}
					echo "</span></h2>";
				}
				
				echo "<div class='inside'>";
				
				foreach ($element as $item) {
					scottcart_settings_render_option($item);
				}
				echo "</div>";
				
			echo "</div>";
			$page_id++;
		}
		$tab_id++;
	}
	
	echo "</div>";
}


// register settings
function scottcart_register_settings () {
	register_setting( 'scottcart_settings_group','scottcart_settings','scottcart_settings_sanatize');
}
add_action('admin_init','scottcart_register_settings');


// sanatize settings
function scottcart_settings_sanatize ($input) {

	$keys = array_keys($input);

	// get settings
	$settings = scottcart_settings();
	
	// Loop through each setting being saved and pass it through a sanitization filter
	foreach ($settings as $setting) {
		foreach ($setting as $element) {
			foreach ($element as $item) {
				foreach($keys as $key) {
					
					if ($item['name'] == $key) {
						$type = $item['type'];
						
						// sanatize
						if ($type == "editor" || $type == "textarea") {
							$input[$item['name']] = wp_kses_post($input[$item['name']]);
						} else {
							$input[$item['name']] = sanitize_text_field($input[$item['name']]);
						}
						
						// validate settings
						$input = scottcart_settings_validate($input,$item);
						
						// save settings hook
						do_action('scottcart_save_settings',$input);
						
					}
				}
			}
		}
	}	
	
	return $input;

}


// validate settings
function scottcart_settings_validate($input,$item) {
	
	return $input;

}

