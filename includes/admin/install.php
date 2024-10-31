<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// page pages
function scottcart_install() {
	if (!get_option('scottcart_pages')) {
		
		$cart = wp_insert_post(
			array(
				'post_title'     => __( 'Cart', 'scottcart' ),
				'post_content'   => '[scottcart_cart]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
		
		$shop = wp_insert_post(
			array(
				'post_title'     => __('Products', 'scottcart'),
				'post_content'   => '[scottcart_shop]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
		
		$confirmation = wp_insert_post(
			array(
				'post_title'     => __( 'Purchase Confirmation', 'scottcart' ),
				'post_content'   => '[scottcart_purchase_confirmation]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
		
		$cancelled = wp_insert_post(
			array(
				'post_title'     => __( 'Order Cancelled', 'scottcart' ),
				'post_content'   => '[scottcart_order_cancelled]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
		
		$account = wp_insert_post(
			array(
				'post_title'     => __( 'Account', 'scottcart' ),
				'post_content'   => '[scottcart_account]',
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);
		
		// assign pages id in settings
		$options = array();
		$options['cart_page']			= $cart;
		$options['shop_page']			= $shop;
		$options['confirmation_page']	= $confirmation;
		$options['cancellation_page']	= $cancelled;
		$options['account_page']		= $account;
		
		update_option('scottcart_pages',$options);
	}
}