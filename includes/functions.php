<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// This page is for general functions


// redirect to settings page on install
function scottcart_firstrun() {
	if (!get_option('scottcart_firstrun')) {
		update_option("scottcart_firstrun", "true");
		exit(wp_redirect(admin_url( 'admin.php?page=scottcart_settings_page')));
	}
}
add_action('admin_init', 'scottcart_firstrun');


// create new wp account for customer
function scottcart_new_account($input) {
	
	// turn array into variables
	extract($input);
	
	if (scottcart_get_option('account') == "0") {
		if (email_exists($payer_email) == false) {
			// account does not already exist
			$random_password = wp_generate_password($length=12, $include_standard_special_chars=false);
			$user_id = wp_create_user($payer_email, $random_password, $payer_email);
			wp_send_new_user_notifications($user_id);
			
			// set the customers name
			$user_first_name = 	get_post_meta($order_id,'scottcart_first_name', true);
			$user_last_name = 	get_post_meta($order_id,'scottcart_last_name', true);
			if (empty($user_firstname)) { $user_firstname = ""; }
			if (empty($user_lastname))  { $user_lastname = ""; }
			
			$result = wp_update_user(array(
				'ID' 				=> $user_id,
				'first_name' 		=> $user_first_name,
				'last_name' 		=> $user_last_name
			));
		}
	}
}


// decrement inventory when an item is sold
function scottcart_inventory($order_id) {

	$post = get_post($order_id);
	
	// check to see if inventory has already been reduced for this order
	$inventory_reduced = get_post_meta($post->ID,'scottcart_inventory_reduced',true);
	
	if (!$inventory_reduced) {
		
		$scottcart_num_cart_items = $post->post_excerpt;
		
		// foreach item in order
		for($i=0;$i<$scottcart_num_cart_items;$i++) {
			
			$selected = 						get_post_meta($post->ID,'scottcart_item_id'.$i,true);
			$scottcart_type = 					get_post_meta($selected,'scottcart_type', true);
			$selected_variation = 				get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
			
			if ($scottcart_type == '0') {
				$scottcart_inventory_management = 	get_post_meta($selected,'scottcart_physical_inventory', true);
			} else {
				$scottcart_inventory_management = 	get_post_meta($selected,'scottcart_inventory', true);
			}
			
			// determine if inventory management is on for that product
			if ($scottcart_inventory_management == '1') {
				$scottcart_quantity = '0';
				
				// only physical and digital product types can have inventory
				
				// physical
				if ($scottcart_type == "0") {
					$scottcart_attribute_id = 	get_post_meta($post->ID,'scottcart_item_attribute'.$i,true);
					$scottcart_sold_quantity = 	get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
					
					// does the product have variations enabled
					if ($scottcart_attribute_id) {
						$scottcart_current_quantity = get_post_meta($selected,'scottcart_physical_attribute_qty'.$scottcart_attribute_id, true);
						$new_quantity = $scottcart_current_quantity - $scottcart_sold_quantity;
						update_post_meta($selected,'scottcart_physical_attribute_qty'.$scottcart_attribute_id,$new_quantity);
					} else {
						$scottcart_current_quantity = get_post_meta($selected,'scottcart_physical_qty'.$selected_variation, true);
						$new_quantity = $scottcart_current_quantity - $scottcart_sold_quantity;
						update_post_meta($selected,'scottcart_physical_qty'.$selected_variation,$new_quantity);
					}
				}
				
				
				// digital
				if ($scottcart_type == "1") {
					$scottcart_sold_quantity = 		get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
					$scottcart_current_quantity = 	get_post_meta($selected,'scottcart_digital_qty'.$selected_variation, true);
					$new_quantity = $scottcart_current_quantity - $scottcart_sold_quantity;
					update_post_meta($selected,'scottcart_digital_qty'.$i,$new_quantity);
				}
			}
		}
		
		// inventory has been successfully reduced for this order - update post meta
		update_post_meta($post->ID,'scottcart_inventory_reduced',true);
	}
}


// hide admin bar for subscribers if settings option is set
function scottcart_hide_admin_bar() {

	if (current_user_can('read') && !current_user_can('upload_files')) {
		if (scottcart_get_option('scottcart_hide_admin') == "0") {
			show_admin_bar( false );
		}
	}
}
add_action('init', 'scottcart_hide_admin_bar');


// make sold table
function scottcart_sold_table($id = null) {
    
	if (isset($_POST['id'])) {
		$id = 		intval($_POST['id']);
	}	
	
	$post = get_post($id);
	
	$scottcart_item = $post_items = $post->post_excerpt;
	
	if (isset($scottcart_item)) {
		$scottcart_num_cart_items = $scottcart_item;
	} else {
		$scottcart_num_cart_items = "";
	}
	
	$scottcart_total = $post->post_content;
	
	$sold_table = "<table width='100%' style='max-width: 800px;' class='scottcart_order_details' id='customFields'><tr class='scottcart_alternate_account' valign='top'><td width='15px'>#</td><td width='290px'>"; $sold_table .= __('Product','scottcart'); $sold_table .= "</td><td width='110px'>"; $sold_table .= __('Price','scottcart'); $sold_table .= "</td><td width='110px'>"; $sold_table .= __('Quantity','scottcart'); $sold_table .= "</td><td width='110px'>"; $sold_table .= __('Total','scottcart'); $sold_table .= "</td></tr>";
	
	$counter = "1";
	$scottcart_subtotal = "0.00";
	for($i=0;$i<$scottcart_num_cart_items;$i++) {
		
		$selected = 			get_post_meta($post->ID,'scottcart_item_id'.$i,true);
		$scottcart_type = 		get_post_meta($selected,'scottcart_type', true);
		$selected_variation = 	get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
		
		// physical
		if ($scottcart_type == "0") {
			$scottcart_name = 			get_post_meta($selected,'scottcart_physical_name'.$selected_variation, true);
			$scottcart_price = 			get_post_meta($selected,'scottcart_physical_price'.$selected_variation, true);
			$scottcart_attribute_id =	get_post_meta($post->ID,'scottcart_item_attribute'.$i,true);
			$scottcart_attribute_name = get_post_meta($selected,'scottcart_physical_attribute_name'.$scottcart_attribute_id, true);
			$scottcart_quantity = 		get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		// digital
		if ($scottcart_type == "1") {
			$scottcart_name = 		get_post_meta($selected,'scottcart_digital_name'.$selected_variation, true);
			$scottcart_price = 		get_post_meta($selected,'scottcart_digital_price'.$selected_variation, true);
			$scottcart_quantity = 	get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		// service
		if ($scottcart_type == "2") {
			$scottcart_name = 		get_post_meta($selected,'scottcart_service_name', true);
			$scottcart_price = 		get_post_meta($selected,'scottcart_service_price', true);
			$scottcart_quantity = 	get_post_meta($post->ID,'scottcart_item_quantity'.$i, true);
		}
		
		$sold_table .= "<tr class='scottcart_alternate_account'><td valign='top'>"; $sold_table .= $counter; $sold_table .= "</td><td valign='top'>"; $sold_table .= get_the_title($selected); $sold_table .= " - "; $sold_table .= $scottcart_name;
		
		// attribute name
		if (!empty($scottcart_attribute_name)) {
			$sold_table .= " - ";
			$sold_table .= $scottcart_attribute_name;
		}
		
		
		if ($scottcart_type == "1") {
			$sold_table .= "<br /><br />";
			$sold_table .= __('Download(s):','scottcart');
			$sold_table .= "<br />";
			
			// get purchased product id and variation id
			$product_id = get_post_meta($post->ID,'scottcart_item_id'.$i,true);
			$variation_id = get_post_meta($post->ID,'scottcart_item_variation'.$i,true);
			
			// get how many files the product has
			$scottcart_digital_attribute_count = get_post_meta($product_id,'scottcart_digital_attribute_count', true);
			
			if ($scottcart_digital_attribute_count > 0) {
				// get all files that the purchased download contains
				for($a=0;$a<$scottcart_digital_attribute_count;$a++) {
					$scottcart_digital_attribute_assignment = get_post_meta($product_id,'scottcart_digital_attribute_assignment'.$a, true);
					if ($scottcart_digital_attribute_assignment == $variation_id || $scottcart_digital_attribute_assignment == "a") {
						$download__file_name = 	get_post_meta($product_id,'scottcart_digital_attribute_name'.$a,true);
						$download_id = 			get_post_meta($product_id,'scottcart_digital_attribute_file'.$a,true);
						
						$download = get_the_title($product_id);
						
						if (!empty($post->ID)) {
							
							$download_url = scottcart_generate_download_url($post->ID,$download_id);
							$sold_table .= "<a href='$download_url'>";
							$sold_table .= $download__file_name;
							$sold_table .= "</a>";
							$sold_table .= "<br />";
						}
					}
				}
			}
			
			$hook_array = array (
				'order_id' 		=> $post->ID,
				'product_id' 	=> $product_id,
				'variation_id'	=> $variation_id,
				'cart_id'		=> $i					// position of item in the cart
			);
			
			do_action('scottcart_purchase_details_digital_line_item',$hook_array);
			
		}
		
		
		// used to show line item product notes
		$sold_table .= apply_filters('scottcart_purchase_details_line_item',$selected);
		
		
		// price
		$sold_table .= "</td><td valign='top'>"; $sold_table .= sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); $sold_table .= "</td>";
		
		// quantity
		$sold_table .= "</td><td valign='top'>"; $sold_table .= $scottcart_quantity; $sold_table .= "</td>";
		
		// item total
		$scottcart_item_total = $scottcart_price * $scottcart_quantity;
		$sold_table .= "</td><td valign='top'>"; $sold_table .= sanitize_meta( 'currency_scottcart',$scottcart_item_total,'post'); $sold_table .= "</td></tr>";
		
		$scottcart_subtotal = $scottcart_subtotal + $scottcart_item_total;
		
		$counter++;
		
	}
	
	
	$sold_table .= "<tr class='scottcart_alternate_account'><td><br /></td><td></td><td></td><td></td><td></td></tr>";
	$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Subtotal','scottcart'); $sold_table .= "</td><td>"; $sold_table .= sanitize_meta('currency_scottcart',$scottcart_subtotal,'post'); $sold_table .="</td></tr>";
	
	
	$scottcart_discount_code = get_post_meta($post->ID,'scottcart_discount_code',true);
	if ($scottcart_discount_code != "") {
		$scottcart_discount_amount = get_post_meta($post->ID,'scottcart_discount_amount',true);
		$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Discount Code','scottcart'); $sold_table .= "</td><td>"; $sold_table .= $scottcart_discount_code; $sold_table .="</td></tr>";
		$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Discount Amount','scottcart'); $sold_table .= "</td><td>"; $sold_table .= sanitize_meta('currency_scottcart',$scottcart_discount_amount,'post'); $sold_table .="</td></tr>";
	}
	
	$scottcart_tax = get_post_meta($post->ID,'scottcart_tax',true);
	if ($scottcart_tax != scottcart_sanitize_currency_meta('0',false)) {
		$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Tax','scottcart'); $sold_table .= "</td><td>"; $sold_table .= sanitize_meta('currency_scottcart',$scottcart_tax,'post'); $sold_table .="</td></tr>";
	}
	
	$scottcart_shipping = get_post_meta($post->ID,'scottcart_shipping',true);
	if ($scottcart_shipping != scottcart_sanitize_currency_meta('0',false)) {
		$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Shipping','scottcart'); $sold_table .= "</td><td>"; $sold_table .= sanitize_meta('currency_scottcart',$scottcart_shipping,'post'); $sold_table .="</td></tr>";
	}
	
	$sold_table .= "<tr class='scottcart_alternate_account'><td></td><td></td><td></td><td style='text-align: right;'>"; $sold_table .= __('Total','scottcart'); $sold_table .= "</td><td>"; if ($scottcart_total < 0) { $sold_table .= sanitize_meta('currency_scottcart','0','post'); } else { $sold_table .= sanitize_meta( 'currency_scottcart',$scottcart_total,'post'); } $sold_table .="</td></tr>";
	
	$sold_table .= "</table>";
	
	return $sold_table;
}


// display grid of images for single product page
function scottcart_single_product_images($post_id) {

	$scottcart_images = get_post_meta($post_id,'scottcart_image_count', true);
	
	echo "<div id='scottcart_preview_parent'>";
	
	if ($scottcart_images > 4) {
		$col_a = "4";
		$col_b = $scottcart_images;
		echo "<table id='scottcart_table_inline_block'><tr><td class='scottcart_top'>";
	} else {
		$col_a = $scottcart_images;
	}
	
		// col a
		for ($i = 0; $i < $col_a; $i++) {
			$img = "";
			$img = get_post_meta($post_id,'scottcart_image_file'.$i);
			
			echo '<div class="scottcart_box1">';
					echo wp_get_attachment_image($img[0],'large','', array( 'class' => 'scottcart_image'));
			echo '</div>';
		}
		
		if (isset($col_b)) {
			// col b
			echo "</td><td class='scottcart_top'>";
			for ($i = 4; $i < $col_b; $i++) {
				$img = "";
				$img = get_post_meta($post_id,'scottcart_image_file'.$i);
				
				echo '<div class="scottcart_box1">';
						echo wp_get_attachment_image($img[0],'large','', array( 'class' => 'scottcart_image'));
				echo '</div>';
			}
			echo "</td></tr></table>";
		}
		
	echo "</div>";

}


// get users ip address
function scottcart_get_the_user_ip() {

	if (!empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
	} elseif (!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
	} else {
		$ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
	}
	
	return $ip;
}


// is a 404 happens, then refresh the permalinks with a soft flush
function scottcart_refresh_permalinks_on_bad_404() {

	global $wp;

	if( ! is_404() ) {
		return;
	}

	if(isset($_GET['scottcart-flush'])) {
		return;
	}

	if(false === get_transient('scottcart_refresh_404_permalinks')) {
		
		$parts = explode( '/', $wp->request );
		
		if('product' !== $parts[0] ) {
			return;
		}
		
		flush_rewrite_rules( false );
		
		set_transient( 'scottcart_refresh_404_permalinks', 1, HOUR_IN_SECONDS * 12 );
		
		wp_redirect( home_url( add_query_arg( array( 'scottcart-flush' => 1 ), $wp->request ) ) ); exit;
		
	}
}
add_action('template_redirect','scottcart_refresh_permalinks_on_bad_404');


// empty array
function scottcart_empty_list() {
	$empty = array(
		''   => '',
	);
	
	return $empty;
}


// fix for custom post type template menu highlighting
function scottcart_nav_classes($classes, $item) {

	// remove highlight class from whatever page it is currently assigned to
    if (get_post_type() == 'scottcart_product') {
        $classes = array_diff($classes,array('current_page_parent'));
    }
	
	// add class to shop page
	$shop_page = scottcart_get_option('shop_page');
	if ($item->object_id == $shop_page && get_post_type() == 'scottcart_product') {
        $classes[] = 'current_page_parent';
    }
	
    return $classes;
}
add_filter('nav_menu_css_class','scottcart_nav_classes',10,2);


// add custom css to site head
function scottcart_custom_css() {
	global $post;
	
    echo "<style type='text/css'>";
		// insert custom css from settings page
        echo scottcart_get_option('custom_css');
		
		// hide mobile cart menu if setting is on
		if (isset($post->ID) && $post->ID == scottcart_get_option('cart_page') && scottcart_get_option('hide_cart_theme_menu') == '1') {
			echo ".btn-menu { display: none !important; }";
		}
		
    echo "</style>";
}
add_action('wp_head','scottcart_custom_css');


// hide theme primary menu on cart page
function scottcart_cart_hide_menu( $args ) {
	global $post;
	
	if (isset($post->ID) && $post->ID == scottcart_get_option('cart_page') && scottcart_get_option('hide_cart_theme_menu') == '1') {
		$args['fallback_cb']    = '__return_false';
		$args['theme_location'] = '_'; // unlikely menu name
	}

    return $args;
}
add_filter( 'wp_nav_menu_args', 'scottcart_cart_hide_menu' );


// checks to see if items already exists in cart
function scottcart_in_array_r($item){
	if (isset($_SESSION['scottcart_cart'])) {
		if (preg_match('/'.$item.'/',json_encode($_SESSION['scottcart_cart']))) {
			return true; 
		}
	}
}


// Should the menu item for the cart be hidden if no items are in the cart
function scottcart_hide_admin_menu_cart_link( $items, $menu, $args ) {
	
	// checks the current setting
	if (scottcart_get_option('menu_cart_link') == '1') {
		
		// does a cart session currently exist
		if (isset($_SESSION['scottcart_cart']) && (!empty($_SESSION['scottcart_cart']))) { } else {
			
			foreach ($items as $key => $item) {
				if ($item->object_id == scottcart_get_option('cart_page')) unset( $items[$key]);
			}
			
		}
		
	}

    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'scottcart_hide_admin_menu_cart_link', null, 3 );


// get slug by post type
function scottcart_get_slug_by_post_type($post_type) {
	if ($post_type) {
		$post_type_object = get_post_type_object($post_type);
		return $post_type_object->rewrite['slug'];
	}
}


// add purchase details after order notes
function scottcart_after_order_notes ($input) {

	$product_id = 	sanitize_text_field($input);
	$scottcart_after_notes = get_post_meta($product_id,'scottcart_after_notes', true);

	if (!empty($scottcart_after_notes)) {
		$table = "<br /><br />";
		$table .= __('Notes:','scottcart');
		$table .= "<br />";
		
		// show notes
		$table .= $scottcart_after_notes;
		
		$table .= "<br />";
		
		return $table;
	}
	
}
add_filter('scottcart_purchase_details_line_item','scottcart_after_order_notes',1);


// fix for some themes
function scottcart_rewrite_post_class($classes) {
	global $post;
	
	if ( $post && $post->post_type == 'scottcart_product' && is_singular('scottcart_product') && is_main_query() && !post_password_required() ) {
		// Add a class
		$classes[] = 'type-page';
	}
	return $classes;
}
add_filter('post_class','scottcart_rewrite_post_class');