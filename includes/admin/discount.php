<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// register meta box
function scottcart_discount_meta_boxes() {
	add_meta_box('meta-box-id-details', __( 'Discount Details', 'scottcart' ), 'scottcart_discount_callback_details', 'scottcart_discount','normal');
	add_meta_box('meta-box-id-order', __( 'Save', 'scottcart' ), 'scottcart_discount_callback_order', 'scottcart_discount','side');
}
add_action( 'add_meta_boxes', 'scottcart_discount_meta_boxes' );




// discount details metabox
function scottcart_discount_callback_details($post) {
	global $meta_box, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<input type="hidden" name="scottcart_submit_discount" value="1" />';
	
	echo "<style>#post-body-content { margin-bottom: 0px; }</style>";
	
	echo '<table><tr>';
	
	$scottcart_title = get_post_meta($post->ID,'scottcart_title', true);
	$scottcart_code = get_post_meta($post->ID,'scottcart_code', true);
	$scottcart_type = get_post_meta($post->ID,'scottcart_type', true);
	$scottcart_amount = get_post_meta($post->ID,'scottcart_amount', true);
	$scottcart_date_from = get_post_meta($post->ID,'scottcart_date_from', true);
	$scottcart_date_to = get_post_meta($post->ID,'scottcart_date_to', true);
	
	echo "<td class='scottcart_cell_width_product'>"; echo __('Title: ','scottcart'); echo "</td><td><input class='scottcart_cell_width' type='text' name='scottcart_title' value='$scottcart_title'></td></tr><tr>";
	echo "<td class='scottcart_cell_width_product'>"; echo __('Code: ','scottcart'); echo "</td><td><input class='scottcart_cell_width' type='text' name='scottcart_code' value='$scottcart_code'></td></tr><tr>";
	
	echo "<td class='scottcart_cell_width_product'>"; echo __('Type:','scottcart'); echo "</td><td><select class='scottcart_cell_width' id='scottcart_type' name='scottcart_type'>";
	echo "<option value='0'"; if ($scottcart_type == "0") { echo "SELECTED"; } echo">"; echo __('Rate','scottcart'); echo "</option>";
	echo "<option value='1'"; if ($scottcart_type == "1") { echo "SELECTED"; } echo">"; echo __('Fixed','scottcart'); echo "</option>";
	echo "</select></td></tr><tr>";
	
	echo "<td class='scottcart_cell_width_product'>"; echo __('Amount: ','scottcart'); echo "</td><td><input class='scottcart_cell_width' type='text' name='scottcart_amount' value='$scottcart_amount'></td></tr><tr>";
	
	echo "<td class='scottcart_cell_width_product' width='100px'>"; echo __('Valid From: ','scottcart'); echo "</td><td><input type='text' name='scottcart_date_from' class='scottcart-datepicker scottcart_cell_width' value='"; if (!empty($scottcart_date_from)) { echo date('Y-m-d', strtotime($scottcart_date_from)); } echo "' /><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('Optional. Specify a valid from date. <br /><br/> You can specify a valid from date without specifying a valid to date if you want.','scottcart'); echo "'></span></td></tr>";
	echo "<td class='scottcart_cell_width_product' width='100px'>"; echo __('Valid To: ','scottcart'); echo "</td><td><input type='text' name='scottcart_date_to' class='scottcart-datepicker scottcart_cell_width' value='"; if (!empty($scottcart_date_to)) { echo date('Y-m-d', strtotime($scottcart_date_to)); } echo "' /><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('Optional. Specify a valid to date. <br /><br/> You can specify a valid to date without specifying a valid from date if you want.','scottcart'); echo "'></span></td></tr>";

	echo '</table>';
}


// discount metabox
function scottcart_discount_callback_order($post) {
	global $meta_box, $post;
	
	$scottcart_status = $post->post_status;
	
	// Use nonce for verification
	echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
	
	echo '<table><tr>';
	
	echo "<td width='100px'>Status: </td><td><select name='scottcart_status'>";
	echo "<option value='active' "; if ($scottcart_status == "active") { echo "SELECTED"; } echo " >"; echo __('Active','scottcart'); echo "</option>";
	echo "<option value='inactive'"; if ($scottcart_status == "inactive") { echo "SELECTED"; } echo " >"; echo __('Inactive','scottcart'); echo "</option>";
	echo "</select></td></tr><tr>";
	
	echo "<td><br /></td></tr><tr>";
	
	echo "<td width='100px'>";
	
	echo "<div class='submitbox' id='submitpost'><a class='submitdelete' href='" . get_delete_post_link( $post->ID, '', false ) . "'>"; echo __('Move to Trash','scottcart'); echo "</a></div>";
	
	echo "</td><td align='right'><input id='publish' class='button-primary' type='submit' value='"; echo __('Update Discount','scottcart'); echo "' accesskey='p' tabindex='5' name='save'></td></tr><tr>";
	
	echo '</tr></table>';
}










// save
function scottcart_save_meta_box_discount ($post_id) {
	if (isset($_POST['scottcart_submit_discount']) && $_POST['scottcart_submit_discount'] == "1") {
		
		// verify nonce
		if (!wp_verify_nonce($_POST['scottcart_MetaNonce'], basename(__FILE__))) {
			return $post_id;
		}
		
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		
		// update
		update_post_meta($post_id,'scottcart_title',sanitize_text_field($_POST['scottcart_title']));
		update_post_meta($post_id,'scottcart_code',sanitize_text_field($_POST['scottcart_code']));
		update_post_meta($post_id,'scottcart_type',sanitize_text_field($_POST['scottcart_type']));
		
		$scottcart_amount = scottcart_sanitize_currency_meta(sanitize_text_field($_POST['scottcart_amount']),false);
		update_post_meta($post_id,'scottcart_amount',$scottcart_amount);
		
		update_post_meta($post_id,'scottcart_date_from',sanitize_text_field($_POST['scottcart_date_from']));
		update_post_meta($post_id,'scottcart_date_to',sanitize_text_field($_POST['scottcart_date_to']));
		
		// to avoid infinite loop
		remove_action('save_post','scottcart_save_meta_box_discount');
		
		$order_post = array(
			'ID'			=> $post_id,
			'post_status'	=> sanitize_text_field($_POST['scottcart_status'])
		);
		
		$result = wp_update_post($order_post);
		
		add_action('save_post','scottcart_save_meta_box_discount');
		
		
	}	
}
add_action( 'save_post', 'scottcart_save_meta_box_discount' );





// title
function scottcart_modify_title_discount( $data , $postarr ) {
	
	if ($data['post_type'] == 'scottcart_discount') {
		if (isset($_POST['scottcart_title'])) {
			$data['post_title'] = sanitize_text_field($_POST['scottcart_title']);
		}
	}
	return $data;
}
add_filter('wp_insert_post_data','scottcart_modify_title_discount','99',2);



function scottcart_change_discount_status($post) {

	if (isset($_REQUEST['_wpnonce'])) {
		$nonce = sanitize_text_field($_REQUEST['_wpnonce']);
		if ( ! wp_verify_nonce( $nonce, 'scottcart_discount_nonce' ) ) {
			die(__('You do not have permission to access this page','scottcart'));
		}
	} else {
		die(__('You do not have permission to access this page','scottcart'));
	}	
	
	if ($post['status'] == "active") {
		$new_status = "inactive";
	} else {
		$new_status = "active";
	}
	
	$order_post = array(
		'ID'			=> intval($post['ID']),
		'post_status'	=> $new_status
	);
	
	wp_update_post($order_post);

}
add_action('scottcart_change_discount_status','scottcart_change_discount_status');