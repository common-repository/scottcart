<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// reports array
function scottcart_reports_array() {

	$scottcart_reports_array = apply_filters('scottcart_reports_top_level', array(
		apply_filters('scottcart_reports_overview_tab', array(
			__('Overview', 'scottcart') 	=> array(
				array(
					'title' 				=> __('Overview', 'scottcart'),
					'function' 				=> 'scottcart_report_overview',
				),
			),
		)),
		apply_filters('scottcart_reports_sales_tab', array(
			__('Earnings', 'scottcart') 		=> array(
				__('Earnings Overview', 'scottcart') 	=> array(
					'function' 				=> 'scottcart_earnings_report_callback',
				),
			),
		)),
	));
	
	return $scottcart_reports_array;
}


// render menu
function scottcart_reports_render_menu() {

	global $scottcart_options;
	
	// get reports
	$reports = scottcart_reports_array();
	
	// make array
	$tabs_array = [];
	$tabs_array_level1 = [];
	$tabs_array_level2 = [];
	
	$level = "0";
	foreach ($reports as $tab) {
		
		$tabs_array_level1[] = key($tab);
		
		foreach ($tab as $element) {
			
			$tabs_array_level2[$level][] = key($element);
			
		}
		$level++;
		
		
	}
		
	$tabs_array = array_merge(array($tabs_array_level1),$tabs_array_level2);

	// make tabs
	$scottcart_active_tab = "tab00";
	
	$scottcart_active_tab_top = substr($scottcart_active_tab, 0, -1);
	
	echo "<br /><table width='100%'><tr><td width='90%'>";
	
	echo "<span class='dashicons dashicons-chart-line'></span><span id='scottcart-menu-title'>&nbsp;"; echo SCOTTCART_NAME; echo " "; echo __('Reports','scottcart'); echo "</span><span class='scottcart-menu-sub-title'>";
	
	echo "</td></tr></table>";

	// menu div
	echo "<div id='scottcart-menu-div'>";
	
	// menu level 1
	echo "<h1 class='nav-tab-wrapper'>";
	
	$counter = "0";
	foreach ($tabs_array as $tabs => $tab) {
		
		if ($tabs == 0) {
			echo "<ul id='scottcart-tabs-reports'>";
			foreach ($tab as $count => $title) {
				echo "<li><a href='#' id='tab$count$counter' class='nav-tab"; if ($scottcart_active_tab_top == "tab".$count) { echo " nav-tab-active'"; } echo "'>$title</a></li>";
			}
			echo "</ul>";
		}
	}
	echo "</h1>";
	
	
	// menu level 2
	$counter = "0";
	foreach ($tabs_array as $tabs => $tab) {
		if ($tabs > 0) {
			echo "<ul id='scottcart-tabs-more-reports' class='subsubsub scottcart-more scottcart-more-tab$counter'"; if ($scottcart_active_tab_top == "tab".$counter) { echo "style='display: block;'"; } echo ">";
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
	
	return;
}

// render option types
// allowed types
// -------------
// text - plain text
function scottcart_reports_render_option($item) {

	global $scottcart_options;
	
	extract($item);
	
	if (!isset($default)) { $default = ''; }

	// text
	if ($type == "text") {
		echo "<table><tr><td>";
		echo $options;
		echo "</td></tr></table>";
	}	
	
}


// render reports page
function scottcart_reports_render() {

	global $scottcart_active_tab,$scottcart_options;
	
	$reports = scottcart_reports_array();
	
	// make tabs
	if (!empty($scottcart_options['tab'])) {
		$scottcart_active_tab =  $scottcart_options['tab'];
	} else {
		$scottcart_active_tab = "tab00";
	}
	
	echo "<div class='metabox-holder'>";

	$tab_id = "0";
	foreach ($reports as $tab) {
		
		$page_id = "0";
		foreach ($tab as $element) {
			
			foreach ($element as $item) {
				$function_name = $item['function'];
			}
			
			echo "<div data-id='$function_name'"; echo " id='tab"; echo $tab_id; echo $page_id; echo "C'>";
			echo "</div>";
			$page_id++;
		}
		$tab_id++;
	}
	
	echo "</div>";
}