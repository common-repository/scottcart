<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// uninstall function - called from scottcart.php, only called if option to remove data is selected in the settings page
// 1. moves pages to trash
// 2. deletes posts
// 3. deletes taxonomies
// 4. removes cron events
// 5. deletes options
function scottcart_uninstall() {


	// move pages to trash
	wp_trash_post(get_option('scottcart_pages')['cart_page']);
	wp_trash_post(get_option('scottcart_pages')['shop_page']);
	wp_trash_post(get_option('scottcart_pages')['account_page']);
	wp_trash_post(get_option('scottcart_pages')['confirmation_page']);
	wp_trash_post(get_option('scottcart_pages')['cancellation_page']);
	

	// delete post types
	$post_types = array(
		'scottcart_order',
		'scottcart_product',
		'scottcart_customer',
		'scottcart_discount'
	);
	
	foreach ($post_types as $post_type) {
		
		$posts = get_posts(
			array(
				'post_type' 	=> $post_type,
				'post_status' 	=> 'any',
				'numberposts' 	=> -1, // return all
				'fields' 		=> 'ids' // only return id field
			)
		);
		
		if ($posts) {
			foreach ($posts as $post) {
				wp_delete_post($post, true);
			}
		}
	}
	

	// register taxonomies so that they can be uninstalled
	register_taxonomy('product_category', 	array('scottcart_product'));
	register_taxonomy('product_tag', 		array('scottcart_product'));
	register_taxonomy('product_feature', 	array('scottcart_product'));
	
	
	// delete terms and taxonomies
	$taxonomy_array = array(
		'product_category',
		'product_tag',
		'product_feature'
	);
	
	$terms = get_terms($taxonomy_array,
		array (
			'hide_empty' => false,
		)
	);
	
	foreach ($terms as $term) {
		wp_delete_term($term->term_id,$term->taxonomy);
	}
	
	
	// remove cron events
	wp_clear_scheduled_hook('scottcart_twicedaily_events');
	
	
	// delete all options
	delete_option("scottcart_firstrun");
	delete_option("scottcart_settings");
	delete_option("scottcart_pages");
	
}