<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// order admin page - fill table columns with data
function scottcart_manage_order_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'id' :
			echo $id = "<a href='post.php?post=$post_id&action=edit'><b>$post_id</b></a>";
		break;
		
		case 'customer' :
			echo $post->post_title;
		break;
		
		case 'status' :
			$status = ucfirst($post->post_status);
			if ($status == "Pend") { $status = "Pending"; }
			echo $status;
		break;
		
		case 'total' :
			$post_total = $post->post_content;
			echo sanitize_meta( 'currency_scottcart',$post_total,'post');
		break;
		
		case 'date_sold' :
			$date = explode(' ',$post->post_date);
			echo date(get_option('date_format'), strtotime($date['0']));
		break;
		
		default :
			break;
	}
}
add_action( 'manage_scottcart_order_posts_custom_column', 'scottcart_manage_order_columns', 10, 2 );


// product admin page - fill table columns with data
function scottcart_manage_product_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		
		case 'type' :
			$type = get_post_meta($post->ID,'scottcart_type', true);
			if ($type == "0") { echo "Physical"; }
			if ($type == "1") { echo "Digital"; }
			if ($type == "2") { echo "Service"; }
			if ($type == "3") { echo "External"; }
		break;
		
		case 'date_made' :
			$date = explode(' ',$post->post_date);
			echo date(get_option('date_format'), strtotime($date['0']));
		break;
		
		default :
			break;
	}
}
add_action( 'manage_scottcart_product_posts_custom_column', 'scottcart_manage_product_columns', 10, 2 );


// discount admin page - fill table columns with data
function scottcart_manage_discount_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'id' :
			echo $id = "<a href='post.php?post=$post_id&action=edit'><b>$post->post_title</b></a>";
		break;
		
		case 'code' :
			echo get_post_meta($post_id,'scottcart_code')[0];
		break;
		
		case 'type' :
			$scottcart_type = get_post_meta($post_id,'scottcart_type')[0];
			if ($scottcart_type == "0") { echo "Rate"; }
			if ($scottcart_type == "1") { echo "Fixed"; }
		break;
		
		case 'amount' :
			echo sanitize_meta( 'currency_scottcart',get_post_meta($post_id,'scottcart_amount')[0],'post');
		break;
		
		case 'status' :
			echo ucfirst($post->post_status);
		break;
		
		default :
			break;
	}
}
add_action( 'manage_scottcart_discount_posts_custom_column', 'scottcart_manage_discount_columns', 10, 2 );


// customers admin page - fill table columns with data
function scottcart_manage_customer_columns( $column, $post_id ) {
	global $post;

	$args = array(
		'post_status'      			=> 'completed',
		'post_type' 				=> 'scottcart_order',
		'post_title'				=> $post->post_title,
		'posts_per_page'   			=> -1,
		'update_post_term_cache'	=> false, // don't retrieve post terms
        'update_post_meta_cache' 	=> false, // don't retrieve post meta
	);

	$posts_customer = new WP_Query($args);	

	$total = "0";
	$items = "0";
	
	foreach($posts_customer->posts as $post_customer) {
		
		if ($post_customer->post_title == $post->post_title) {
			
			$post_total = $post_customer->post_content;
			$post_items = $post_customer->post_excerpt;
			
			$total = $total + $post_total;
			$items = $items + $post_items;	
			
		}
	}

	switch( $column ) {
		case 'id' :
			echo $id = "<a href='post.php?post=$post_id&action=edit'><b>$post_id</b></a>";
		break;
		
		case 'customer' :
			echo $post->post_title;
		break;
		
		case 'items' :
			echo $items;
		break;
		
		case 'total' :
			echo sanitize_meta( 'currency_scottcart',$total,'post');
		break;
		
		case 'date_sold' :
			$date = explode(' ',$post->post_date);
			echo date(get_option('date_format'), strtotime($date['0']));
		break;
		
		default :
			break;
	}
}
add_action('manage_scottcart_customer_posts_custom_column','scottcart_manage_customer_columns', 10, 2 );


// remove metaboxs in custom post types
function scottcart_remove_metaboxs() {
	remove_meta_box('submitdiv','scottcart_order','side');
	remove_meta_box('submitdiv','scottcart_discount','side');
	remove_meta_box('submitdiv','scottcart_customer','side');
	remove_meta_box('slugdiv','scottcart_customer','normal');
	remove_meta_box('slugdiv','scottcart_discount','normal');
	remove_meta_box('slugdiv','scottcart_order','normal');
}
add_action('admin_menu','scottcart_remove_metaboxs');


// admin table column width - includes mobile support
function scottcart_change_admin_tables() {

remove_meta_box('postimagediv','scottcart_product','side');
	
	$screen = get_current_screen();
	
	// order table column width
	if( in_array( $screen->id, array( 'page', 'edit-scottcart_order' ) ) ) {
		echo '<style type="text/css">';
		echo '@media all and ( min-width: 860px ) { .column-id { width:100px !important; } .column-customer { width:300px !important; overflow:hidden } }';
		echo '</style>';
	}
	
	// customer table column width
	if( in_array( $screen->id, array( 'page', 'edit-scottcart_customer' ) ) ) {
		echo '<style type="text/css">';
		echo '@media all and ( min-width: 860px ) { .column-id { width:100px !important; } .column-customer { width:300px !important; overflow:hidden } }';
		echo '</style>';
	}
}
add_action('admin_head','scottcart_change_admin_tables');


// titles for admin order table
function scottcart_order_columns($columns) {

	$columns = array(
		'cb' => 			'<input type="checkbox" />',
		'id' => 			__('Order #','scottcart'),
		'customer' =>		__('Customer','scottcart'),
		'total' => 			__('Total','scottcart'),
		'status' => 		__('Status','scottcart'),
		'date_sold' => 		__('Date','scottcart')
	);

	return $columns;
}
add_filter('manage_edit-scottcart_order_columns','scottcart_order_columns');


// titles for admin customer table
function scottcart_customer_columns($columns) {

	$columns = array(
		'cb' => 			'<input type="checkbox" />',
		'id' => 			__('Customer #','scottcart'),
		'customer' => 		__('Customer Email','scottcart'),
		'items' => 			__('Total Items Purchased','scottcart'),
		'total' => 			__('Total Amount Purchased','scottcart'),
		'date_sold' => 		__('Customer Since','scottcart')
	);

	return $columns;
}
add_filter('manage_edit-scottcart_customer_columns','scottcart_customer_columns');


// titles for admin discount table
function scottcart_discount_columns($columns) {

	$columns = array(
		'cb' => 			'<input type="checkbox" />',
		'id' => 			__('Title','scottcart'),
		'code' => 			__('Code','scottcart'),
		'type' => 			__('Type','scottcart'),
		'amount' => 		__('Amount','scottcart'),
		'status' => 		__('Status','scottcart')
	);

	return $columns;
}
add_filter('manage_edit-scottcart_discount_columns','scottcart_discount_columns');


// titles for admin product table
function scottcart_product_columns($columns) {

	$columns = array(
		'cb' => 			'<input type="checkbox" />',
		'title' => 			__( 'Customer #','scottcart'),
		'type' => 			__( 'Type','scottcart'),
		'date_made' => 		__( 'Date Created','scottcart'),
	);

	return $columns;
}
add_filter('manage_edit-scottcart_product_columns','scottcart_product_columns');


// set default column for orders & customers table
function scottcart_slide_list_table_primary_column( $column, $screen ) {
	
	if ('edit-scottcart_order' === $screen || 'edit-scottcart_customer' === $screen || 'edit-scottcart_discount' === $screen) {
        $column = 'id';
    }
    return $column;
}
add_filter( 'list_table_primary_column', 'scottcart_slide_list_table_primary_column', 10, 2 );


// order & customer table quick links
function scottcart_quick_links($actions, $post) {

    if ($post->post_type =="scottcart_order" || $post->post_type =="scottcart_customer") {
		unset($actions['inline hide-if-no-js']);
    }
	if ($post->post_type =="scottcart_discount") {
		
		unset($actions['inline hide-if-no-js']);
		
		if ($post->post_status == "active") {
			$actions['deactivate'] = '<a href="' . esc_url( wp_nonce_url( add_query_arg( array( 'scottcart-action' => 'change_discount_status', 'ID' => $post->ID, 'status' => 'active' ) ), 'scottcart_discount_nonce' ) ) . '">' . __( 'Deactivate', 'scottcart' ) . '</a>';
		} else {
			$actions['deactivate'] = '<a href="' . esc_url( wp_nonce_url( add_query_arg( array( 'scottcart-action' => 'change_discount_status', 'ID' => $post->ID, 'status' => 'inactive' ) ), 'scottcart_discount_nonce' ) ) . '">' . __( 'Activate', 'scottcart' ) . '</a>';
		}
		
    }
    return $actions;
}
add_filter('post_row_actions','scottcart_quick_links', 10, 2);


// customers table - remove subsubsub menu links
function scottcart_customer_table_sub_menu_links( $views ) {

	unset($views['private']);
	unset($views['mine']);
	unset($views['publish']);

    return $views;
}
add_filter('views_edit-scottcart_customer','scottcart_customer_table_sub_menu_links');
