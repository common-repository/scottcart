<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly













// reports overview tab
function scottcart_report_overview() {
	
	$overview_array = array();
	
	$current_month = date('m');
	$current_year = date('Y');
	
	// revenue this month
	$args = array(
		'year'     			=> $current_year,
		'monthnum' 			=> $current_month,
		'post_status'     	=> 'completed',
		'post_type'       	=> 'scottcart_order',
		'posts_per_page'    => '-1',
	);
	$posts_sales = get_posts($args);
	
	// revenue all time
	$args = array(
		'post_status'     	=> 'completed',
		'post_type'       	=> 'scottcart_order',
		'posts_per_page'    => '-1',
	);
	$posts_sales_alltime = get_posts($args);
	
	// pending sales
	$args = array(
		'post_status'     	=> 'pend',
		'post_type'       	=> 'scottcart_order',
		'posts_per_page'    => '-1',
	);
	$posts_sales_pending = get_posts($args);
	
	// returns this month
	$args = array(
		'year'     			=> $current_year,
		'monthnum' 			=> $current_month,
		'post_status'     	=> 'refunded',
		'post_type'       	=> 'scottcart_order',
		'posts_per_page'    => '-1',
	);
	$refunded_sales = get_posts($args);
	
	// get sale for date range

	$args = array(
		'post_type'       	=> 'scottcart_customer',
		'post_status'		=> 'private',
		'posts_per_page'    => '-1',
	);
	$posts_customers = get_posts($args);
	
	
	
	$number_days_in_month = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
	
	$overview_array['revenue_total']['title'] = __('Total Revenue','scottcart');
	$overview_array['revenue_total']['value'] = scottcart_sanitize_currency_meta('0',false);
	$overview_array['revenue_total']['type'] = 'currency';
	$overview_array['revenue_total']['cat'] = 'total';
	
	$overview_array['revenue_month']['title'] = __('Revenue','scottcart');
	$overview_array['revenue_month']['value'] = scottcart_sanitize_currency_meta('0',false);
	$overview_array['revenue_month']['type'] = 'currency';
	$overview_array['revenue_month']['cat'] = 'month';
	
	$overview_array['sales']['title'] = __('Sales','scottcart');
	$overview_array['sales']['value'] = '0';
	$overview_array['sales']['type'] = 'number';
	$overview_array['sales']['cat'] = 'month';
	
	$overview_array['sales_pending']['title'] = __('Pending Sales','scottcart');
	$overview_array['sales_pending']['value'] = scottcart_sanitize_currency_meta('0',false);
	$overview_array['sales_pending']['type'] = 'number';
	$overview_array['sales_pending']['cat'] = 'month';
	
	$overview_array['items']['title'] = __('Items Sold','scottcart');
	$overview_array['items']['value'] = '0';
	$overview_array['items']['type'] = 'number';
	$overview_array['items']['cat'] = 'month';
	
	$overview_array['returns']['title'] = __('Returns','scottcart');
	$overview_array['returns']['value'] = "0";
	$overview_array['returns']['type'] = 'number';
	$overview_array['returns']['cat'] = 'month';
	
	$overview_array['returns_total']['title'] = __('Returns Amount','scottcart');
	$overview_array['returns_total']['value'] = "0";
	$overview_array['returns_total']['type'] = 'currency';
	$overview_array['returns_total']['cat'] = 'month';
	
	$overview_array['total_customers']['title'] = __('Total Customers','scottcart');
	$overview_array['total_customers']['value'] = '0';
	$overview_array['total_customers']['type'] = 'number';
	$overview_array['total_customers']['cat'] = 'total';
	
	
	
	foreach($posts_sales_alltime as $post) {
		$post_total = $post->post_content;
		$post_items = $post->post_excerpt;
		
		$overview_array['revenue_total']['value'] += $post_total;
	}
	
	foreach($posts_sales as $post) {
		$post_total = $post->post_content;
		$post_items = $post->post_excerpt;
		
		$overview_array['revenue_month']['value'] += $post_total;
		$overview_array['items']['value'] += $post_items;
	}
	
	foreach($refunded_sales as $post) {
		$post_total = $post->post_content;
		
		$overview_array['returns_total']['value'] += $post_total;
	}
	
	$overview_array['sales']['value'] = count($posts_sales);
	$overview_array['returns']['value'] = count($refunded_sales);
	$overview_array['total_customers']['value'] = count($posts_customers);
	$overview_array['sales_pending']['value'] = count($posts_sales_pending);
	
	
	if (count($posts_sales_alltime) == 0) {
		echo __('You do not have any completed sales yet.','scottcart');
	} else {
		
		echo "<h2>";
		echo date('F');
		echo "</h2>";
		
		
		foreach ($overview_array as $box) {
			if (isset($box['cat']) && $box['cat'] == "month") {
				echo "<div class='scottcart_report_overview'>";
					echo "<h2 class='scottcart_report_title_gray'>";
					echo $box['title'];
					echo "</h2>";
					
					echo "<h1 class='scottcart_report_title_blue'>";
					
					if ($box['type'] == "currency") {
						echo sanitize_meta( 'currency_scottcart',$box['value'],'post');
					}
					
					if ($box['type'] == "number") {
						echo $box['value'];
					}
					
					echo "</h1>";
					
				echo "</div>";
			}
			
		}	
		
		echo "<hr style='border-top: 1px solid #ccc;'>";
		
		echo "<h2>";
		echo __('Total','scottcart');
		echo "</h2>";
		foreach ($overview_array as $box) {
			if (isset($box['cat']) && $box['cat'] == "total") {
				echo "<div class='scottcart_report_overview'>";
					echo "<h2 class='scottcart_report_title_gray'>";
					echo $box['title'];
					echo "</h2>";
					
					echo "<h1 class='scottcart_report_title_blue'>";
					
					if ($box['type'] == "currency") {
						echo sanitize_meta('currency_scottcart',$box['value'],'post');
					}
					
					if ($box['type'] == "number") {
						echo $box['value'];
					}
					
					echo "</h1>";
					
				echo "</div>";
			}
			
		}
		
	}

}












// reports graph tab
function scottcart_earnings_report_callback() {

	// choosen year
	if (isset($_POST['year'])) {
		$current_year = intval($_POST['year']);
	} else {
		$current_year = date('Y');
	}
	
	
	// get first sale year
	$args = array(
		'post_type'       			=> 'scottcart_order',
		'post_status'     			=> 'completed',
		'order'            			=> 'ASC',
		'posts_per_page'			=> '1',
		'update_post_term_cache'	=> false, // don't retrieve post terms
        'update_post_meta_cache' 	=> false, // don't retrieve post meta
	);
	
	$array = new WP_Query($args);	
	
	$first_year = date('Y', strtotime($array->posts[0]->post_date));
	
	
	// year dropdown
	$years = range ($first_year,date('Y'));
	
	echo "<select id='scottcart_year_report_change'>";
	foreach($years as $year) {
		echo "<option value='$year'"; if ($year == $current_year) { echo " SELECTED"; } echo ">$year</option>";
	}
	echo "</select>";
	
	
	// get data
	$args = array(
		'post_type'       			=> 'scottcart_order',
		'post_status'     			=> 'completed',
		'order'            			=> 'ASC',
		'posts_per_page'            => '-1',
		'update_post_term_cache'	=> false, // don't retrieve post terms
        'update_post_meta_cache' 	=> false, // don't retrieve post meta
	);
	
	$query = new WP_Query($args);
	

	$new_array = array();
	$new_array_day = array();
	foreach($query->posts as $post) {
		
		$post_total = $post->post_content;
		$post_items = $post->post_excerpt;
		
		// for other then day view
		$time = strtotime(get_the_time('m/d/Y',$post));
		if (array_key_exists($time,$new_array)) {
			$new_array[$time]['sale_amount'] = $new_array[$time]['sale_amount'] + $post_total;
			$new_array[$time]['sale_items'] = $new_array[$time]['sale_items']+ $post_items;
		} else {
			$new_array[$time]['sale_timestamp'] = $time * 1000;
			$new_array[$time]['sale_amount'] = $post_total;
			$new_array[$time]['sale_items'] = $post_items;
		}
		
		// for day view - limit time to only that time period so that the array is not unnecessarly large
		$time = get_the_time('U',$post);
		$yesterday = strtotime('-1 day',$time);
		$tomrrow = strtotime('+1 day',$time);
		
		if ($time > $yesterday && $time < $tomrrow) {
			$new_array_day[$time]['sale_timestamp'] = $time * 1000;
			$new_array_day[$time]['sale_amount'] = $post_total;
			$new_array_day[$time]['sale_items'] = $post_items;
		}
	}
	
	// for everything other then day view
	foreach ($new_array as $array) {
		$data1 .= "[".$array['sale_timestamp'].",".$array['sale_amount']."],";
		$data2 .= "[".$array['sale_timestamp'].",".$array['sale_items']."],";
	}
	
	
	// for day view
	foreach ($new_array_day as $array_day) {
		$data3 .= "[".$array_day['sale_timestamp'].",".$array_day['sale_amount']."],";
		$data4 .= "[".$array_day['sale_timestamp'].",".$array_day['sale_items']."],";
	}

	?>
	<script type="text/javascript">
	
		jQuery(function() {
		
		
		var data1 = [
			<?php echo $data1; ?>
		];
		
		var data2 = [
			<?php echo $data2; ?>
		];
	
		var data3 = [
			<?php echo $data3; ?>
		];
		
		var data4 = [
			<?php echo $data4; ?>
		];
		
		
		
		// reload on range click
		
		// today
		jQuery("#today").click(function () {
			
			var month = (new Date).getMonth();
			var day = (new Date).getDate();
			
			var min = (new Date(<?php echo $current_year; ?>, month, day, 00,00,00)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, day, 23,59,59)).getTime();
			
			reload(min,max,true);
		});
		
		// this week
		jQuery("#week").click(function () {
			
			var day = (new Date).getDate();
			var month = (new Date).getMonth();
			
			Date.prototype.addDays = function(days)
			{
				var dat = new Date(this.valueOf());
				dat.setDate(dat.getDate() + days);
				return dat;
			}
			
			var dat = new Date();
			var week = dat.addDays(4);
			
			dat.setDate(dat.getDate() - 4);
			
			var min = (new Date(dat)).getTime();
			var max = (new Date(week)).getTime();
			
			reload(min,max);
		});
		
		
		// this month
		jQuery("#month").click(function () {
			
			var month = (new Date).getMonth();
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, month, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		// this q1
		jQuery("#q1").click(function () {
			
			var month = 2
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, 0, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		// this q2
		jQuery("#q2").click(function () {
			
			var month = 5
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, 3, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		// this q3
		jQuery("#q3").click(function () {
			
			var month = 8
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, 6, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		
		// this q4
		jQuery("#q4").click(function () {
			
			var month = 11
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, 9, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		
		// this year
		jQuery("#year").click(function () {
			
			var month = 11
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
			
			var min = (new Date(<?php echo $current_year; ?>, 0, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		
		// for month
		jQuery(".month").click(function () {
			
			var month = jQuery(this).attr("data-id");
			// decrement because JS date starts from 0 not 1
			month--;
			
			var year = (new Date).getYear();
			
			var monthStart = new Date(year, month, 1);
			var monthEnd = new Date(year, month + 1, 1);
			var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24)
			
			var min = (new Date(<?php echo $current_year; ?>, month, 1)).getTime();
			var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
			
			reload(min,max);
		});
		
		
		
		
		function reload(min,max,set) {
			
			if (set == true) {
				data_a = data3;
				data_b = data4;
			} else {
				data_a = data1;
				data_b = data2;
			}
			
			var sales = jQuery('#sales').is(":checked");
			var amount = jQuery('#amount').is(":checked");
			var fill = jQuery('#fill').is(":checked");
			var bar = jQuery('#bar').is(":checked");
			var lines = jQuery('#lines').is(":checked");
			var points = jQuery('#points').is(":checked");
			var grid = jQuery('#grid').is(":checked");
			
			
			if (amount == true) {
				var set1 = {
					label: "Earnings",
					id: "earnings",
					data: data_a,
					points: {
						show: points,
					},
					bars: {
						show: bar,
						barWidth: 10,
						aling: 'center'
					},
					lines: {
						show: lines,
						fill: fill
					},
					yaxis: 1
				};
			} else {
				var set1 = "";
			}
			
			
			if (sales == true) {
				var set2 = {
					label: "Sales",
					id: "sales",
					data: data_b,
					points: {
						show: points,
					},
					bars: {
						show: bar,
						barWidth: 10,
						aling: 'center'
					},
					lines: {
						show: lines,
						fill: fill
					},
					yaxis: 2
				};
			} else {
				var set2 = "";
			}
			
			
			
			
			jQuery.plot(jQuery("#placeholder"),[set1,set2,], {
				grid: {
					show: grid,
					aboveData: false,
					color: "#bbb",
					backgroundColor: "#f9f9f9",
					borderColor: "#ccc",
					borderWidth: 2,
					clickable: false,
					hoverable: true
				},
					xaxis: {
					mode: "time",
					tickSize: "",
					min: min,
					max: max
				},
				legend: { show: true, placement: 'outsideGrid', noColumns:2, container: jQuery('.scottcart-graph-legend') },
				yaxes: [{
					position: "left",
					min: 0,
				}, {
					position: "right",
					min: 0,
				}],
			});
			
		};
		
		
		
		
		// onload
		var month = (new Date).getMonth();
		var year = (new Date).getYear();
		
		var monthStart = new Date(year, month, 1);
		var monthEnd = new Date(year, month + 1, 1);
		var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
		
		var min = (new Date(<?php echo $current_year; ?>, month, 1)).getTime();
		var max = (new Date(<?php echo $current_year; ?>, month, monthLength)).getTime();
		
		
		
		
		// onload
		jQuery.plot(jQuery("#placeholder"),
				[
					{
						label: "Earnings",
						id: "earnings",
						data: data1,
						points: {
							show: true,
						},
						bars: {
							show: false,
							barWidth: 12,
							aling: 'center'
						},
						lines: {
							show: true
						},
						yaxis: 1
					}, {
						label: "Sales",
						id: "sales",
						data: data2,
						points: {
							show: true,
						},
						bars: {
							show: false,
							barWidth: 12,
							aling: 'center'
						},
						lines: {
							show: true
						},
						yaxis: 2
					},
				],
			{
			grid: {
				show: true,
				aboveData: false,
				color: "#bbb",
				backgroundColor: "#f9f9f9",
				borderColor: "#ccc",
				borderWidth: 2,
				clickable: false,
				hoverable: true
			},
			legend: { show: true, placement: 'outsideGrid', noColumns:2, container: jQuery('.scottcart-graph-legend') },
			xaxis: {
				mode: "time",
				tickSize: "",
				min: min,
				max: max
			},
			yaxes: [{
				position: "left",
				min: 0,
			}, {
				position: "right",
				min: 0,
			}],
			}
		);
		
		});
		
		
		// hover tooltips
		jQuery("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #ccc",
			padding: "2px",
			opacity: 0.80
		}).appendTo("body");
		
		jQuery("#placeholder").bind("plothover", function (event, pos, item) {
			if (item) {
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
					
				<?php
				$currency_symbol = scottcart_get_option('currency_symbol');
				$currency_position = scottcart_get_option('currency_position');
				?>
				
				if (item.series.label == 'Earnings') {
					<?php if ($currency_position == '0') { ?>
						jQuery("#tooltip").html(item.series.label + " - <?php echo $currency_symbol; ?>" + y).css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
					<?php } ?>
					
					<?php if ($currency_position == '2') { ?>
						jQuery("#tooltip").html(item.series.label + " - <?php echo $currency_symbol; ?> " + y).css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
					<?php } ?>
					
					<?php if ($currency_position == '1') { ?>
						jQuery("#tooltip").html(item.series.label + " - " + y + "<?php echo $currency_symbol; ?>").css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
					<?php } ?>
					
					<?php if ($currency_position == '3') { ?>
						jQuery("#tooltip").html(item.series.label + " - " + y + " <?php echo $currency_symbol; ?>").css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
					<?php } ?>
				} else {
					jQuery("#tooltip").html(item.series.label + " - " + y).css({top: item.pageY+5, left: item.pageX+5}).fadeIn(200);
				}
			} else {
				jQuery("#tooltip").hide();
			}
		});
		
		jQuery('.button').on("click",function() {
			jQuery('.button').removeClass('active');
			jQuery(this).toggleClass('active');
		});
		
	</script>

	<h2>Sales for <?php echo $current_year; ?></h2>

	<div id="content">
		
		Lines: 
		<input type='checkbox' id='sales' value='1' checked='checked'><?php echo __('Sales','scottcart'); ?>
		<input type='checkbox' id='amount' value='1' checked='checked'><?php echo __('Amount','scottcart'); ?>
		
		<br /><br />
		
		Options:
		<input type='checkbox' id='lines' value='1' checked='checked'><?php echo __('Lines','scottcart'); ?>
		<input type='checkbox' id='points' value='1' checked='checked'><?php echo __('Points','scottcart'); ?>
		<input type='checkbox' id='fill' value='1'><?php echo __('Fill','scottcart'); ?>
		<input type='checkbox' id='bar' value='1'><?php echo __('Bars','scottcart'); ?>
		<input type='checkbox' id='grid' value='1' checked='checked'><?php echo __('Grid','scottcart'); ?>
		
		<br /><br />
		
		
		
		
		Range: 
		<button class='button' id='today'><?php echo __('Today','scottcart'); ?></button>
		<button class='button' id='week'><?php echo __('This Week','scottcart'); ?></button>
		<button class='button active' id='month'><?php echo __('This Month','scottcart'); ?></button>
		<button class='button' id='q1'><?php echo __('Quarter 1','scottcart'); ?></button>
		<button class='button' id='q2'><?php echo __('Quarter 2','scottcart'); ?></button>
		<button class='button' id='q3'><?php echo __('Quarter 3','scottcart'); ?></button>
		<button class='button' id='q4'><?php echo __('Quarter 4','scottcart'); ?></button>
		<button class='button' id='year'><?php echo __('This Year','scottcart'); ?></button>
		
		<br /><br />
		Month: 
		<?php
		for ($m=1; $m<=12; $m++) {
			echo "<button class='month button' data-id='$m'>".date('F', mktime(0,0,0,$m))."</button> ";
		}
		?>
		
		<div class="scottcart-graph-container">
			<div class="scottcart-graph-legend"></div>
			<div id="placeholder" class="scottcart-graph-placeholder" style="float:left; width:100%;"></div>
		</div>
	</div>
	<?php
	
	if (isset($_POST['year'])) {
		wp_die();
	}
	
}
add_action( 'wp_ajax_scottcart_earnings_report', 'scottcart_earnings_report_callback' );
add_action( 'wp_ajax_nopriv_scottcart_earnings_report', 'scottcart_earnings_report_callback' );


