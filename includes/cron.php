<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// daily wp cron job
if (!wp_next_scheduled('scottcart_twicedaily_events')) {
  wp_schedule_event( time(),'twicedaily','scottcart_twicedaily_events');
}


// convert pending orders into abandoned orders - runs daily, checks to see if day is equal to current day
function scottcart_convert_pending_order_to_abandoned() {
	
	// get all pending posts
	$post_args = array(
		'post_type'       			=> 'scottcart_order',
		'post_status'     			=> 'pend',
		'update_post_term_cache'	=> false, // don't retrieve post terms
		'update_post_meta_cache' 	=> false, // don't retrieve post meta
	);

	$posts_array = new WP_Query($post_args);	

	$current_day = current_time('d');

	foreach ($posts_array->posts as $post) {
		if (get_the_date('d',$post->ID) != $current_day) {
			
			$my_post = array(
				'ID'           	=> $post->ID,
				'post_status'   => 'abandoned',
			);
			wp_update_post($my_post);
		}
	}

}
add_action('scottcart_daily_events','scottcart_convert_pending_order_to_abandoned');
