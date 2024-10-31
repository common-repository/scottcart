<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function scottcart_account() {

	$output = '';

	$output .= "<div class='scottcart_body_main'>";

	if (is_user_logged_in()) {
		
		$current_user = wp_get_current_user();

		$output .= "<div id='scottcart-account'>";
			
			$output .= "<div id='scottcart-side-info'>";
				$output .= __('Hi','scottcart');
				$output .= ", "; 
				$output .= $current_user->user_firstname;
				$output .= " (";
				$output .= scottcart_logout_shortcode();
				$output .= ")";
			$output .= "</div><br />";
			
			$output .= scottcart_account_render_menu();
			$output .= scottcart_account_render();
			$output .= scottcart_account_purchase_details_tab();
			
		$output .= "</div>";

	} else {
		
		$args = array(
			'label_username' => __('Email Address','scottcart'),
		);
		
		$output .=  wp_login_form($args);

	}

	$output .= "</div>";


	return $output;

}