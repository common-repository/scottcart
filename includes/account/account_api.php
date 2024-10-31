<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// account array
function scottcart_account_array() {

	$scottcart_account_array = apply_filters('scottcart_account_top_level', array(
		array(
			__('Profile', 'scottcart') 	=> array(
				array(
					'function' 				=> 'scottcart_account_profile_tab',
				),
			),
		),
		array(
			__('Purchases', 'scottcart') 	=> array(
				array(
					'function' 				=> 'scottcart_account_purchases_tab',
				),
			),
		),
		array(
			__('Files', 'scottcart') 	=> array(
				array(
					'function' 				=> 'scottcart_account_files_tab',
				),
			),
		),
	));
	
	return $scottcart_account_array;
}




// render menu
function scottcart_account_render_menu() {

	$output = '';
	
	// get account
	$account = scottcart_account_array();
	
	// make array
	$tabs_array = [];
	$tabs_array_level1 = [];
	$tabs_array_level2 = [];
	
	$level = "0";
	foreach ($account as $tab) {
		
		$tabs_array_level1[] = key($tab);
		
		foreach ($tab as $element) {
			
			$tabs_array_level2[$level][] = key($element);
			
		}
		$level++;
		
		
	}
		
	$tabs_array = array_merge(array($tabs_array_level1),$tabs_array_level2);

	// make tabs
	$scottcart_active_tab = "tab0";
	
	// menu div
	$output .= "<div id='scottcart-menu-div'>";
		
		// menu level 1
		$output .= "<h1 class='scottcart-nav-tab-wrapper'>";
			$counter = "0";
			foreach ($tabs_array as $tabs => $tab) {
				
				if ($tabs == 0) {
					$output .= "<ul id='scottcart-tabs'>";
					foreach ($tab as $count => $title) {
						$output .= "<li><a href='#' id='tab$count' class='scottcart-nav-tab"; if ($scottcart_active_tab == "tab".$count) { $output .= " scottcart-nav-tab-active'"; } $output .= "'>$title</a></li>";
					}
					$output .= "</ul>";
				}
			}
		$output .= "</h1>";
		
	$output .= "</div>";
	
	return $output;
}

// render account page
function scottcart_account_render() {

	$output = '';
	
	$account = scottcart_account_array();
	
	// make tabs
	$scottcart_active_tab = "tab0";
	
	$output .= "<br />";
		
		$tab_id = "0";
		foreach ($account as $tab) {
			
			$page_id = "0";
			foreach ($tab as $element) {
				
				foreach ($element as $item) {
					$function_name = $item['function'];
				}
				
				$output .= "<div class='postbox scottcart-container' "; if ($scottcart_active_tab == "tab$tab_id") { $output .= "style='display:block;'"; } else { $output .= "style='display:none;'"; } $output .= " id='tab"; $output .= $tab_id;  $output .= "C'>";
				$output .= call_user_func($function_name);
				$output .= "</div>";
				$page_id++;
			}
			$tab_id++;
		}
		
	return $output;
}