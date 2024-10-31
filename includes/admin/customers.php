<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


	// register meta box
	function scottcart_customer_meta_boxes() {
		add_meta_box( 'meta-box-id-general', __( 'Customer Details', 'textdomain' ), 'scottcart_customer_callback_general', 'scottcart_customer','normal');
		add_meta_box( 'meta-box-id-save', __( 'Save', 'textdomain' ), 'scottcart_customer_callback_save', 'scottcart_customer','side');
	}
	add_action( 'add_meta_boxes', 'scottcart_customer_meta_boxes' );

	
	
	// callback customer details
	function scottcart_customer_callback_general($post) {
		global $meta_box, $post;
		
		echo "<style>#post-body-content { margin-bottom: 0px; } #post-body #normal-sortables { min-height: 0px; } </style>";
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit_customer" value="1" />';
			
			echo '<table><tr>';
			
			$scottcart_email = $post->post_title;
			
			if (!isset($scottcart_email)) { $scottcart_email = ""; }
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('Email','scottcart'); echo ":</td><td><input type='text' name='scottcart_email' value='$scottcart_email' size='40'></td></tr><tr>";
			
			$scottcart_email_id = email_exists($scottcart_email);
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('WordPress Account','scottcart'); echo":</td><td><a href='user-edit.php?user_id=$scottcart_email_id'>Link</a></td></tr><tr>";
			
			$date = explode(' ',$post->post_date);
			echo "<td class='scottcart_cell_width_product'>"; echo __('Customer Since','scottcart'); echo ":</td><td>"; echo date(get_option('date_format'), strtotime($date['0'])); echo "</td></tr><tr>";
			
			
			$args = array(
				'post_status'      	=> 'completed',
				'post_type' 		=> 'scottcart_order',
				'post_title'		=> $post->post_title,
			);
			
			$posts_customer = get_posts($args);
			
			
			$total = "";
			$items = "";
			
			foreach($posts_customer as $post_customer) {
				if ($post_customer->post_title == $post->post_title) {
					$total = $total + $post_customer->post_content;
					$items = $items + $post_customer->post_excerpt;
				}
			}
			
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('Total Items Purchased','scottcart'); echo ":</td><td>"; echo $items; echo "</td></tr><tr>";
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('Total Amount Purchased','scottcart'); echo ":</td><td>"; echo sanitize_meta( 'currency_scottcart',$total,'post'); echo "</td></tr><tr>";
			
			echo '</tr></table>';
			
		echo "</div>";
	}
	
	// save metabox
	function scottcart_customer_callback_save($post) {
		global $meta_box, $post;
		
		$scottcart_status = $post->post_status;
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo '<table><tr>';
		
		echo "<td width='100px'>";
		
		echo "<div class='submitbox' id='submitpost'><a class='submitdelete' href='" . get_delete_post_link( $post->ID, '', false ) . "'>Move to Trash</a></div>";
		
		echo "</td><td align='right'><input id='publish' class='button-primary' type='submit' value='Update Discount' accesskey='p' tabindex='5' name='save'></td></tr><tr>";
		
		echo '</tr></table>';
	}

	
	
	
	
	// save
	function scottcart_save_meta_box_customer ($post_id) {
		if (isset($_POST['scottcart_submit_customer']) && $_POST['scottcart_submit_customer'] == "1") {
			
			// verify nonce
			if (!wp_verify_nonce($_POST['scottcart_MetaNonce'], basename(__FILE__))) {
				return $post_id;
			}
			
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
			
			// update
			update_post_meta($post_id,'scottcart_email',sanitize_email($_POST['scottcart_email']));
			
			
			
		}	
	}
	add_action( 'save_post', 'scottcart_save_meta_box_customer' );
	
	
	
	// title
	function scottcart_modify_title_customer ($data,$postarr) {
		
		if ($data['post_type'] == 'scottcart_customer') {
			if (isset($_POST['scottcart_email'])) {
				$data['post_title'] = 	sanitize_email($_POST['scottcart_email']);
				$data['post_status'] =  'private';
			}
		}
		return $data;
	}
	add_filter( 'wp_insert_post_data' , 'scottcart_modify_title_customer' , '99', 2 );