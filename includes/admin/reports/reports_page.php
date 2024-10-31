<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_reports() {
	
	echo "<div class='scottcart-wrapper'>";
		scottcart_reports_render_menu();
		
		scottcart_reports_render();
		
		echo "<div id='scottcart_report'>"; scottcart_report_overview(); echo "</div>";
		
	echo "</div>";
	
}