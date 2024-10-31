<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// register dashboard widget
function scottcart_dashboard_widget() {

	wp_add_dashboard_widget(
		'scottcart_dashboard_widget',
		__('ScottCart Sales Overview','scottcart'),
		'scottcart_dashboard'
	);	
}
add_action( 'wp_dashboard_setup', 'scottcart_dashboard_widget' );



// dashboard widget
function scottcart_dashboard() {

	echo "<div class='scottcart_widget'>";
		echo "<table width='100%'><tr><td>";
			
			$current_year = date('Y');
			$current_month = date('m');
			$current_day = date('d');
			
			
			
			// today
			echo "<table width='100%'><tr><td class='scottcart_widget_row' colspan='2'>"; echo __('Today','scottcart'); echo "</td></tr>";
			$args = array(
				'year'     			=> $current_year,
				'monthnum' 			=> $current_month,
				'day' 				=> $current_day,
				'post_status'     	=> 'completed',
				'post_type'       	=> 'scottcart_order',
				'posts_per_page'    => '-1',
			);
			$posts = get_posts( $args );
			
			echo "<tr><td>";
			echo __('Sales','scottcart'); echo "</td><td>".count($posts);
			echo "</td></tr>";
			
			$total = "";
			foreach ($posts as $post) {
				$total_sale = $post->post_content;
				$total += $total_sale;
			}
			$total = sanitize_meta( 'currency_scottcart',$total, 'post' );
			if ($total == "") { $total = "0.00"; }
			echo "<tr><td>";
			echo __('Earnings','scottcart'); echo "</td><td>"  . $total;
			echo "</td></tr></table>";
			
		echo "</td><td>";

			// current month
			echo "<table width='100%'><tr><td class='scottcart_widget_row' colspan='2'>"; echo __('Current Month','scottcart'); echo "</td></tr>";
			$args = array(
				'year'     			=> $current_year,
				'monthnum' 			=> $current_month,
				'post_status'     	=> 'completed',
				'post_type'       	=> 'scottcart_order',
				'posts_per_page'    => '-1',
			);
			$posts = get_posts( $args );
			
			echo "<tr><td>";
			echo __('Sales','scottcart'); echo "</td><td>".count($posts);
			echo "</td></tr>";
			
			$total = "";
			foreach ($posts as $post) {
				$total_sale = $post->post_content;
				$total += $total_sale;
			}
			$total = sanitize_meta( 'currency_scottcart',$total, 'post' );
			if ($total == "") { $total = "0.00"; }
			echo "<tr><td>";
			echo __('Earnings','scottcart'); echo "</td><td>"  . $total;
			echo "</td></tr></table>";
			
		echo "</td></tr><tr><td colspan='2'>";
			
			
			
			// recent sales
			echo "<table width='100%' class='scottcart_top_margin'><tr><td class='scottcart_widget_row' colspan='4'><a href='edit.php?post_type=scottcart_order'>"; echo __('Recent Sales','scottcart'); echo "</a></td></tr>";
			
			$args = array(
				'posts_per_page'   	=> 3,
				'post_status'     	=> 'completed',
				'post_type'       	=> 'scottcart_order',
				'order'            	=> 'DSC',
			);
			$posts = get_posts( $args );
			
			foreach ($posts as $post) {
				$total = $post->post_content;
				
				echo "<tr><td width='33%' valign='top'>";
					echo "<a href='post.php?post=$post->ID&action=edit'>";
					echo $post->ID;
					echo "</a>";
				echo "</td><td width='33%' valign='top'>";
					echo sanitize_meta('currency_scottcart',$total,'post');
				echo "</td><td width='33%' valign='top'>";
					echo $post->post_title;
			}
			
			echo "</td></tr></table>";
			
			
		echo "</td></tr></table>";
	echo "</div>";
	
}