<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


	// register meta box
	function scottcart_products_meta_boxes() {
		add_meta_box( 'meta-box-id-short-desc', 	__( 'Short Description', 'scottcart' ), 'scottcart_callback_short_desc', 'scottcart_product','normal');
		add_meta_box( 'meta-box-id-aftert_notes', 	__( 'After Order Notes', 'scottcart' ), 'scottcart_callback_after_notes', 'scottcart_product','normal');
		add_meta_box( 'meta-box-id-general', 		__( 'General', 'scottcart' ), 'scottcart_callback_general', 'scottcart_product','normal');
		add_meta_box( 'meta-box-id-price', 			__( 'Price', 'scottcart' ), 'scottcart_callback_price', 'scottcart_product','normal');
		add_meta_box( 'meta-box-id-attributes', 	__( 'Attributes', 'scottcart' ), 'scottcart_callback_attributes', 'scottcart_product','normal');
		add_meta_box( 'meta-box-id-images', 		__( 'Images', 'scottcart' ), 'scottcart_callback_images', 'scottcart_product','normal');
		//add_meta_box( 'meta-box-id-shipping', 	__( 'Shipping', 'scottcart' ), 'scottcart_callback_shipping', 'scottcart_product','side');
	}
	add_action( 'add_meta_boxes', 'scottcart_products_meta_boxes' );




	// callback short desc
	function scottcart_callback_short_desc($post) {
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			echo '<table class="form-table"><tr>';
			
			$scottcart_short_desc = get_post_meta($post->ID,'scottcart_short_desc', true);
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('Short Description','scottcart'); echo ": </td><td><input size='60' type='text' name='scottcart_short_desc' value='$scottcart_short_desc'></td>";
			
			echo '</tr></table>';
			
		echo "</div>";
	}
	
	// callback after order notes
	function scottcart_callback_after_notes($post) {
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			echo '<table class="form-table"><tr>';
			
			$scottcart_after_notes = get_post_meta($post->ID,'scottcart_after_notes', true);
			
			echo "<td class='scottcart_cell_width_product'>"; echo __('After Order_Notes','scottcart'); echo ": </td><td><input size='60' type='text' name='scottcart_after_notes' value='$scottcart_after_notes'><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('After you sell an item, this text is visable in the purchase details. Basic HTML is allowed.','scottcart'); echo "'></span></td>";
			
			echo '</tr></table>';
			
		echo "</div>";
	}
	
	
	// callback general
	function scottcart_callback_general($post) {
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			
			$scottcart_type = get_post_meta($post->ID,'scottcart_type', true);
			echo "<table><tr><td class='scottcart_cell_width_product'>"; echo __('Product Type','scottcart'); echo ":</td><td><select id='scottcart_type_change' name='scottcart_type'>";
			echo "<option value='0'"; if ($scottcart_type == "0") { echo "SELECTED"; } echo">"; echo __('Physical','scottcart'); echo "</option>";
			echo "<option value='1'"; if ($scottcart_type == "1") { echo "SELECTED"; } echo">"; echo __('Digital','scottcart'); echo "</option>";
			echo "<option value='2'"; if ($scottcart_type == "2") { echo "SELECTED"; } echo">"; echo __('Service','scottcart'); echo "</option>";
			echo "<option value='3'"; if ($scottcart_type == "3") { echo "SELECTED"; } echo">"; echo __('External','scottcart'); echo "</option>";
			echo "</select></td></tr></table>";
			
			
			echo "<div id='scottcart_variations'>";
				$scottcart_variations = get_post_meta($post->ID,'scottcart_variations', true);
				echo "<table><tr><td class='scottcart_cell_width_product'>Variations: </td><td><select id='scottcart_variation_change' name='scottcart_variations'>";
				echo "<option value='0'"; if ($scottcart_variations == "0") { echo "SELECTED"; } echo">"; echo __('No','scottcart'); echo "</option>";
				echo "<option value='1'"; if ($scottcart_variations == "1") { echo "SELECTED"; } echo">"; echo __('Yes','scottcart'); echo "</option>";
				echo "</select><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('Does this product have different colors, sizes, etc?','scottcart'); echo "'></span></td></tr></table>";
			echo "</div>";
			
			
			echo "<div id='scottcart_inventory'>";
				$scottcart_inventory = get_post_meta($post->ID,'scottcart_inventory', true);
				echo "<table><tr><td class='scottcart_cell_width_product'>Inventory Management: </td><td><select id='scottcart_inventory_change' name='scottcart_inventory'>";
				echo "<option value='0'"; if ($scottcart_inventory == "0") { echo "SELECTED"; } echo">"; echo __('No','scottcart'); echo "</option>";
				echo "<option value='1'"; if ($scottcart_inventory == "1") { echo "SELECTED"; } echo">"; echo __('Yes','scottcart'); echo "</option>";
				echo "</select><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('When you sell an item, should the quantity available go down?','scottcart'); echo "'></span></td></tr></table>";
			echo "</div>";
			
			echo "<div id='scottcart_physical_inventory'>";
				$scottcart_physical_inventory = get_post_meta($post->ID,'scottcart_physical_inventory', true);
				echo "<table><tr><td class='scottcart_cell_width_product'>Inventory Management: </td><td><select id='scottcart_physical_inventory_change' name='scottcart_physical_inventory'>";
				echo "<option value='0'"; if ($scottcart_physical_inventory == "0") { echo "SELECTED"; } echo">"; echo __('No','scottcart'); echo "</option>";
				echo "<option value='1'"; if ($scottcart_physical_inventory == "1") { echo "SELECTED"; } echo">"; echo __('Yes','scottcart'); echo "</option>";
				echo "</select><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('When you sell an item, should the quantity available go down?','scottcart'); echo "'></span></td></tr></table>";
			echo "</div>";
			
			
			echo "<div id='scottcart_multi'>";
				$scottcart_multi = get_post_meta($post->ID,'scottcart_multi', true);
				echo "<table><tr><td class='scottcart_cell_width_product'>Multi item: </td><td><select id='scottcart_multi_change' name='scottcart_multi'>";
				echo "<option value='0'"; if ($scottcart_multi == "0") { echo "SELECTED"; } echo">"; echo __('No','scottcart'); echo "</option>";
				echo "<option value='1'"; if ($scottcart_multi == "1") { echo "SELECTED"; } echo">"; echo __('Yes','scottcart'); echo "</option>";
				echo "</select><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('The no option will make a dropdown menu on the sales page.<br /><br />The yes option will make a checkbox menu on the sales page.','scottcart'); echo "'></span></td></tr></table>";
			echo "</div>";
			
			
			$scottcart_button_name = get_post_meta($post->ID,'scottcart_button_name', true);
			echo "<table><tr><td class='scottcart_cell_width_product'>Button Text</td><td><input type='text' name='scottcart_button_name' value='$scottcart_button_name'><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('Default: Add to cart.<br /><br />Setting this value will override the value on the settings page (Settings->General->Button Text).','scottcart'); echo "'></span></td></tr></table>";
			
			echo '</tr></table>';
			
		echo "</div>";
	}


	// callback price
	function scottcart_callback_price($post) {
		
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			
			// physical
			echo "<div id='scottcart_physical'>";			
				$scottcart_physical_count = get_post_meta($post->ID,'scottcart_physical_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_physical'><tr valign='top'><td width='15px'></td><td width='150px'>"; echo __('Physical Product Name','scottcart'); echo "</td><td width='100px'>"; echo __('Price','scottcart'); echo "</td><td width='100px' class='scottcart_variation scottcart_physical_inventory'>"; echo __('Quantity','scottcart'); echo "</td><td width='100px' class='scottcart_variation scottcart_physical_inventory'>"; echo __('SKU','scottcart'); echo "</td><td width='100px'></td></tr>";
				
				$a = "1";
				$counter = "0";
				for($i=0;$i<$scottcart_physical_count;$i++) {
					
					$scottcart_name = get_post_meta($post->ID,'scottcart_physical_name'.$i, true);
					$scottcart_price = get_post_meta($post->ID,'scottcart_physical_price'.$i, true);
					$scottcart_qty = get_post_meta($post->ID,'scottcart_physical_qty'.$i, true);
					$scottcart_sku = get_post_meta($post->ID,'scottcart_physical_sku'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$a</td><td class='product-id'>";
					echo "<input type='text' class='scottcart_physical_name' id='$counter' name='scottcart_physical_name[]' value='$scottcart_name'></td><td>";
					echo "<input type='text' size='6' name='scottcart_physical_price[]' value='$scottcart_price'></td><td>"; 
					echo "<input type='text' class='scottcart_variation scottcart_physical_inventory' size='6' name='scottcart_physical_qty[]' value='$scottcart_qty'></td><td>";
					echo "<input type='text' class='scottcart_variation scottcart_physical_inventory' size='6' name='scottcart_physical_sku[]' value='$scottcart_sku'></td><td>";
					echo "<a href='javascript:void(0);' id='$counter' class='scottcart_remCF_product'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
					$a++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_product_physical'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			// digital
			echo "<div id='scottcart_digital'>";			
				$scottcart_digital_count = get_post_meta($post->ID,'scottcart_digital_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_digital'><tr valign='top'>
				<td width='15px'></td><td width='150px'>"; echo __('Digital Product Name','scottcart'); echo "</td>
				<td width='100px'>"; echo __('Price','scottcart'); echo "</td>
				<td width='100px' class='scottcart_digital_inventory'>"; echo __('Quantity','scottcart'); echo "</td>
				<td width='100px' class='scottcart_digital_inventory'>"; echo __('SKU','scottcart'); echo "</td>";
				
				do_action('scottcart_product_digital_price_col_name');
				
				echo "<td width='100px'></td></tr>";
				
				
				$a = "1";
				$counter = "0";
				for($i=0;$i<$scottcart_digital_count;$i++) {
					
					$scottcart_name = get_post_meta($post->ID,'scottcart_digital_name'.$i, true);
					$scottcart_price = get_post_meta($post->ID,'scottcart_digital_price'.$i, true);
					$scottcart_qty = get_post_meta($post->ID,'scottcart_digital_qty'.$i, true);
					$scottcart_sku = get_post_meta($post->ID,'scottcart_digital_sku'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$a</td><td class='product-id'>";
					echo "<input type='text' class='scottcart_digital_name' id='$counter' name='scottcart_digital_name[]' value='$scottcart_name'></td><td>";
					echo "<input type='text' size='6' name='scottcart_digital_price[]' value='$scottcart_price'></td><td class='scottcart_digital_inventory'>"; 
					echo "<input type='text' size='6' class='scottcart_digital_inventory' name='scottcart_digital_qty[]' value='$scottcart_qty'></td><td class='scottcart_digital_inventory'>";
					echo "<input type='text' size='6' class='scottcart_digital_inventory' name='scottcart_digital_sku[]' value='$scottcart_sku'></td><td>";
					
					$scottcart_product_digital_price_col_array = array (
						'post_id' 	=> $post->ID,
						'i'			=> $i
					);
					
					do_action('scottcart_product_digital_price_col',$scottcart_product_digital_price_col_array);
					
					echo "<a href='javascript:void(0);' id='$counter' class='scottcart_remCF_product'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
					$a++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_product_digital'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			// service
			echo "<div id='scottcart_service'>";			
				$scottcart_service_count = get_post_meta($post->ID,'scottcart_service_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_service'><tr valign='top'><td width='15px'></td><td width='150px'>"; echo __('Service Name','scottcart'); echo "</td><td width='100px'>"; echo __('Price','scottcart'); echo "</td><td width='100px'></td></tr>";
				
				$counter = "1";
				for($i=0;$i<$scottcart_service_count;$i++) {
					
					$scottcart_name = get_post_meta($post->ID,'scottcart_service_name'.$i, true);
					$scottcart_price = get_post_meta($post->ID,'scottcart_service_price'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$counter</td><td class='product-id'>";
					echo "<input type='text' name='scottcart_service_name[]' value='$scottcart_name'></td><td>";
					echo "<input type='text' size='6' name='scottcart_service_price[]' value='$scottcart_price'></td><td>"; 
					echo "<a href='javascript:void(0);' class='scottcart_remCF_service'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_product_service'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			// external
			echo "<div id='scottcart_external'>";
				echo "<table>";
				echo "<tr><td class='scottcart_cell_width_product'></td><td>"; echo __('External URL','scottcart'); echo "</td></tr>";
				
				$scottcart_external = get_post_meta($post->ID,'scottcart_external', true);
				echo "<tr><td>"; echo __('External Product','scottcart'); echo ":</td><td><input type='text' name='scottcart_external' value='$scottcart_external'></td>";
				
				echo "</tr></table>";
			echo "</div>";
		
		echo "</div>";
	}
	
	
	// callback attributes
	function scottcart_callback_attributes($post) {
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			
			// physical attributes
			echo "<div id='scottcart_physical_attributes'>";
				
				$scottcart_physical_attribute_count = get_post_meta($post->ID,'scottcart_physical_attribute_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_physical_attributes'><tr valign='top'><td width='15px'></td><td width='100px'>"; echo __('Variation Name','scottcart'); echo "</td><td class='scottcart_physical_attribute_inventory' width='100px'>"; echo __('Quantity','scottcart'); echo "</td><td class='scottcart_physical_attribute_inventory' width='100px'>"; echo __('SKU','scottcart'); echo "</td><td width='100px'>"; echo __('Assignment','scottcart'); echo "</td><td width='100px'></td></tr>";
				
				$counter = "1";
				for($i=0;$i<$scottcart_physical_attribute_count;$i++) {
					
					$scottcart_name = get_post_meta($post->ID,'scottcart_physical_attribute_name'.$i, true);
					$scottcart_qty = get_post_meta($post->ID,'scottcart_physical_attribute_qty'.$i, true);
					$scottcart_sku = get_post_meta($post->ID,'scottcart_physical_attribute_sku'.$i, true);
					$scottcart_attribute = get_post_meta($post->ID,'scottcart_physical_attribute_assignment'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$counter</td><td class='product-id'>";
					
					echo "<input type='text' name='scottcart_physical_attribute_name[]' value='$scottcart_name'></td><td class='scottcart_physical_attribute_inventory'>";
					echo "<input type='text' size='6' name='scottcart_physical_attribute_qty[]' value='$scottcart_qty'></td><td class='scottcart_physical_attribute_inventory'>"; 
					echo "<input type='text' size='6' name='scottcart_physical_attribute_sku[]' value='$scottcart_sku'></td><td>";
					
					
					
					echo "<select style='max-width:80px;' class='scottcart_physical_attribute_assignment' name='scottcart_physical_attribute_assignment[]'><option value='a'>"; echo __('All','scottcart'); echo "</option>";
					
					$scottcart_physical_count = get_post_meta($post->ID,'scottcart_physical_count', true);
					
					$count = "0";
					for($a=0;$a<$scottcart_physical_count;$a++) {
						echo "<option value='$count' "; if ($scottcart_attribute == $count) { echo " SELECTED "; } echo">";
							echo $scottcart_name = get_post_meta($post->ID,'scottcart_physical_name'.$a, true);
						echo "</option>";
						$count++;
					}
					
					echo "</select></td><td>";
					
					
					
					
					echo "<a href='javascript:void(0);' class='scottcart_remCF_physical_attribute'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_product_physical_attribute'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			
			// digital attributes
			echo "<div id='scottcart_digital_attributes'>";
				
				$scottcart_digital_attribute_count = get_post_meta($post->ID,'scottcart_digital_attribute_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_digital_attributes'><tr valign='top'><td width='15px'></td><td width='100px'>"; echo __('File Name','scottcart'); echo "</td><td width='100px'>"; echo __('File','scottcart'); echo "</td><td width='100px'>"; echo __('Assignment','scottcart'); echo "</td><td width='100px'></td></tr>";
				
				$counter = "1";
				for($i=0;$i<$scottcart_digital_attribute_count;$i++) {
					
					$scottcart_name = get_post_meta($post->ID,'scottcart_digital_attribute_name'.$i, true);
					$scottcart_file = get_post_meta($post->ID,'scottcart_digital_attribute_file'.$i, true);
					$scottcart_attribute = get_post_meta($post->ID,'scottcart_digital_attribute_assignment'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$counter</td><td class='product-id'>";
					
					echo "<input type='text' id='scottcart_digital_attribute_title_$counter' size='15' name='scottcart_digital_attribute_name[]' value='$scottcart_name'></td><td>";
					echo "<input type='hidden' id='scottcart_digital_attribute_$counter' name='scottcart_digital_attribute_file[]' value='$scottcart_file'>";
					echo "<a id='$counter' class='scottcart_file'>Upload</a></td><td>";
					
					
					
					echo "<select style='max-width:80px;' class='scottcart_digital_attribute_assignment' name='scottcart_digital_attribute_assignment[]'><option value='a'>"; echo __('All','scottcart'); echo "</option>";
					
					
					
					$scottcart_digital_count = get_post_meta($post->ID,'scottcart_digital_count', true);
					
					$count = "0";
					for($a=0;$a<$scottcart_digital_count;$a++) {
						echo "<option value='$count' "; if ($scottcart_attribute == $count) { echo " SELECTED "; } echo">";
							echo $scottcart_name = get_post_meta($post->ID,'scottcart_digital_name'.$a, true);
						echo "</option>";
						$count++;
					}
					
					echo "</select></td><td>";
					
					
					
					
					
					
					
					echo "<a href='javascript:void(0);' class='scottcart_remCF_digital_attribute'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_product_digital_attribute'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			// physical varation attributes
			echo "<div id='scottcart_physical_variation_attributes'>";
				echo __('No attributes available for this varation type.','scottcart');
			echo "</div>";
			
			// service attributes
			echo "<div id='scottcart_service_attributes'>";
				echo __('No attributes available for this product type.','scottcart');
			echo "</div>";
			
			// external attributes
			echo "<div id='scottcart_external_attributes'>";
				echo __('No attributes available for this product type.','scottcart');
			echo "</div>";
			
			
			echo '</tr></table>';
			
		echo "</div>";
	}
	
	
	
	// callback images
	function scottcart_callback_images($post) {
		global $meta_box, $post;
		
		echo "<div class='scottcart_meta_box'>";
			
			// Use nonce for verification
			echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
			
			echo '<input type="hidden" name="scottcart_submit" value="1" />';
			
			echo "<div id='scottcart_images'>";
				
				$scottcart_images = get_post_meta($post->ID,'scottcart_image_count', true);
				
				echo "<table width='100%' class='form-table' id='scottcart_products_table_images'><tr valign='top'><td width='15px'></td><td width='100px'>"; echo __('Image','scottcart'); echo "</td><td width='100px'>"; echo __('File','scottcart'); echo "</td><td width='100px'>"; echo __('Featured','scottcart'); echo "</td><td width='100px'></td></tr>";
				
				$counter = "1";
				for($i=0;$i<$scottcart_images;$i++) {
					
					$scottcart_file = get_post_meta($post->ID,'scottcart_image_file'.$i, true);
					$scottcart_featured = get_post_meta($post->ID,'scottcart_image_featured', true);
					$scottcart_assignment = get_post_meta($post->ID,'scottcart_image_assignment'.$i, true);
					
					echo "<tr valign='top'><td class='row-id'>$counter</td><td class='product-id'>";
					
					echo "<div class='scottcart_image_$counter'>"; echo wp_get_attachment_image($scottcart_file,array('50', '50')); echo "</div></td><td>";
					echo "<input type='hidden' id='scottcart_image_$counter' name='scottcart_image_file[]' value='$scottcart_file'>";
					echo "<a id='$counter' class='scottcart_file'>"; echo __('Upload','scottcart'); echo "</a></td><td>";
					echo "<input type='radio' value='$counter' name='scottcart_image_featured'"; if ($scottcart_featured == $counter) { echo 'checked="checked"'; } echo "></td><td>";
					
					echo "<a href='javascript:void(0);' class='scottcart_remCF_image'><span class='dashicons dashicons-trash'></span></a></td></tr>";
					$counter++;
				}
				
				echo "</table>";
				echo "<table width='100%' class='form-table'><tr><td width='15px'></td><td width='100px'><a href='javascript:void(0);' class='scottcart_addCF_image'>"; echo __('Add','scottcart'); echo "</a></td><td width='100px'></td><td width='100px'></td><td width='100px'></td></tr></table>";
			echo "</div>";
			
			
			echo '</tr></table>';
			
		echo "</div>";
	}
	
	
	
	
	

// shipping metabox
function scottcart_callback_shipping($post) {
	global $meta_box, $post, $scottcart_options;
	
	echo "<div class='scottcart_meta_box'>";
		
		// Use nonce for verification
		echo '<input type="hidden" name="scottcart_MetaNonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		echo '<input type="hidden" name="scottcart_submit_order" value="1" />';
		
		echo "<div id='scottcart_physical_shipping_type'>";
			echo __('Shipping Type','scottcart');
			echo ": <select name='scottcart_physical_shipping_type'><option></option>";
			
			
			echo $scottcart_physical_shipping_type = get_post_meta($post->ID,'scottcart_physical_shipping_type', true);
			for($c=0;$c<$scottcart_options['shipping_types_count'];$c++) {
				echo "<option value='$c'"; if (!empty($scottcart_physical_shipping_type) || $scottcart_physical_shipping_type == '0') if ($scottcart_physical_shipping_type == $c) { { echo " SELECTED "; } } echo ">"; echo $scottcart_options['shipping_types_name'][$c]; echo "</option>";
			}
			echo "</select><span alt='f223' class='scottcart-help-tip dashicons dashicons-editor-help' title='"; echo __('Optional. Lock this product to a shipping type. <br /><br /> This is useful if this product requires a certain shipping type, perhaps because this product is espically heavy or light with regard to your other items for sale.','scottcart'); echo "'></span>";
		echo "</div>";
		
		echo "<div id='scottcart_physical_shipping_type_none'>";
			echo __('No shipping options are available for this product type.','scottcart');
		echo "</div>";
		
	echo "</div>";
}

	
	
	
	
	
	
	

	// save
	function scottcart_save_meta_box_product($post_id) {
		if (isset($_POST['scottcart_submit']) && $_POST['scottcart_submit'] == "1") {
			// verify nonce
			if (!wp_verify_nonce($_POST['scottcart_MetaNonce'], basename(__FILE__))) {
				return $post_id;
			}
			
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
			}
			
			
			//** delete old values if they are empty **
			
			// get old counts
			$scottcart_physical_count = 			get_post_meta($post_id,'scottcart_physical_count', true);
			$scottcart_digital_count = 				get_post_meta($post_id,'scottcart_digital_count', true);
			$scottcart_service_count = 				get_post_meta($post_id,'scottcart_service_count', true);
			$scottcart_physical_attribute_count = 	get_post_meta($post_id,'scottcart_physical_attribute_count', true);
			$scottcart_digital_attribute_count = 	get_post_meta($post_id,'scottcart_digital_attribute_count', true);
			$scottcart_images = 					get_post_meta($post_id,'scottcart_image_count', true);
			
			// physical products
			for($i=0;$i<$scottcart_physical_count;$i++) {
				if (empty($_POST['scottcart_physical_name'][$i])) {
					delete_post_meta($post_id,'scottcart_physical_name'.$i);
					delete_post_meta($post_id,'scottcart_physical_sku'.$i);
					delete_post_meta($post_id,'scottcart_physical_quantity'.$i);
					delete_post_meta($post_id,'scottcart_physical_price'.$i);
				}	
			}
			
			// digital products
			for($i=0;$i<$scottcart_digital_count;$i++) {
				if (empty($_POST['scottcart_digital_name'][$i])) {
					delete_post_meta($post_id,'scottcart_digital_name'.$i);
					delete_post_meta($post_id,'scottcart_digital_sku'.$i);
					delete_post_meta($post_id,'scottcart_digital_quantity'.$i);
					delete_post_meta($post_id,'scottcart_digital_price'.$i);
				}	
			}
			
			// service products
			for($i=0;$i<$scottcart_service_count;$i++) {
				if (empty($_POST['scottcart_service_name'][$i])) {
					delete_post_meta($post_id,'scottcart_service_name'.$i);
					delete_post_meta($post_id,'scottcart_service_price'.$i);
				}	
			}
			
			// physical attributes
			for($i=0;$i<$scottcart_physical_attribute_count;$i++) {
				if (empty($_POST['scottcart_physical_attribute_name'][$i])) {
					delete_post_meta($post_id,'scottcart_physical_attribute_name'.$i);
					delete_post_meta($post_id,'scottcart_physical_attribute_qty'.$i);
					delete_post_meta($post_id,'scottcart_physical_attribute_sku'.$i);
					delete_post_meta($post_id,'scottcart_physical_attribute_assignment'.$i);
				}	
			}
			
			// digital attributes
			for($i=0;$i<$scottcart_digital_attribute_count;$i++) {
				if (empty($_POST['scottcart_digital_attribute_name'][$i])) {
					delete_post_meta($post_id,'scottcart_digital_attribute_name'.$i);
					delete_post_meta($post_id,'scottcart_digital_attribute_file'.$i);
					delete_post_meta($post_id,'scottcart_digital_attribute_assignment'.$i);
				}	
			}
			
			// images
			for($i=0;$i<$scottcart_images;$i++) {
				if (empty($_POST['scottcart_image_file'][$i])) {
					delete_post_meta($post_id,'scottcart_image_file'.$i);
					delete_post_meta($post_id,'scottcart_image_featured'.$i);
					delete_post_meta($post_id,'scottcart_image_assignment'.$i);
				}	
			}
			
			
			//** get new data **
			
			// type
			update_post_meta($post_id,'scottcart_type',sanitize_text_field($_POST['scottcart_type']));
			
			// variations
			update_post_meta($post_id,'scottcart_variations',sanitize_text_field($_POST['scottcart_variations']));
			
			// digital inventory
			update_post_meta($post_id,'scottcart_inventory',sanitize_text_field($_POST['scottcart_inventory']));
			
			// physical inventory
			update_post_meta($post_id,'scottcart_physical_inventory',sanitize_text_field($_POST['scottcart_physical_inventory']));
			
			// multi
			update_post_meta($post_id,'scottcart_multi',sanitize_text_field($_POST['scottcart_multi']));
			
			// shipping - currently unused
			//update_post_meta($post_id,'scottcart_physical_shipping_type',sanitize_text_field($_POST['scottcart_physical_shipping_type']));			
			
			// physical products
			$count_physical = "0";
			if (isset($_POST['scottcart_physical_name'])) {
				
				foreach (array_map('sanitize_text_field',$_POST['scottcart_physical_name']) as $physical => $name) {
					update_post_meta($post_id,'scottcart_physical_name'.$count_physical,$name);
					update_post_meta($post_id,'scottcart_physical_sku'.$count_physical,sanitize_text_field($_POST['scottcart_physical_sku'][$physical]));
					update_post_meta($post_id,'scottcart_physical_qty'.$count_physical,intval(sanitize_text_field($_POST['scottcart_physical_qty'][$physical])));
					$scottcart_price = scottcart_sanitize_currency_meta(sanitize_text_field($_POST['scottcart_physical_price'][$physical]),false);
					update_post_meta($post_id,'scottcart_physical_price'.$count_physical,$scottcart_price);
					$count_physical++;
				}
			}
			
			// digital products
			$count_digital = "0";
			if (isset($_POST['scottcart_digital_name'])) {
				foreach (array_map('sanitize_text_field',$_POST['scottcart_digital_name']) as $digital => $name) {
					update_post_meta($post_id,'scottcart_digital_name'.$count_digital,$name);
					update_post_meta($post_id,'scottcart_digital_sku'.$count_digital,$_POST['scottcart_digital_sku'][$digital]);
					update_post_meta($post_id,'scottcart_digital_qty'.$count_digital,intval($_POST['scottcart_digital_qty'][$digital]));
					$scottcart_price = scottcart_sanitize_currency_meta($_POST['scottcart_digital_price'][$digital],false);
					update_post_meta($post_id,'scottcart_digital_price'.$count_digital,$scottcart_price);
					$count_digital++;
				}
			}
			
			// service products
			$count_service = "0";
			if (isset($_POST['scottcart_service_name'])) {
				foreach (array_map('sanitize_text_field',$_POST['scottcart_service_name']) as $service => $name) {
					update_post_meta($post_id,'scottcart_service_name'.$count_service,$name);
					$scottcart_price = scottcart_sanitize_currency_meta(sanitize_text_field($_POST['scottcart_service_price'][$service]),false);
					update_post_meta($post_id,'scottcart_service_price'.$count_service,$scottcart_price);
					$count_service++;
				}
			}
			
			// physical attributes
			$count_physical_attributes = "0";
			if (isset($_POST['scottcart_physical_attribute_name'])) {
				foreach (array_map('sanitize_text_field',$_POST['scottcart_physical_attribute_name']) as $physical => $name) {
					update_post_meta($post_id,'scottcart_physical_attribute_name'.$count_physical_attributes,$name);
					update_post_meta($post_id,'scottcart_physical_attribute_qty'.$count_physical_attributes,intval($_POST['scottcart_physical_attribute_qty'][$physical]));
					update_post_meta($post_id,'scottcart_physical_attribute_sku'.$count_physical_attributes,sanitize_text_field($_POST['scottcart_physical_attribute_sku'][$physical]));
					update_post_meta($post_id,'scottcart_physical_attribute_assignment'.$count_physical_attributes,sanitize_text_field($_POST['scottcart_physical_attribute_assignment'][$physical]));
					$count_physical_attributes++;
				}
			}
			
			// digital attributes
			$count_digital_attributes = "0";
			if (isset($_POST['scottcart_digital_attribute_name'])) {
				foreach (array_map('sanitize_text_field',$_POST['scottcart_digital_attribute_name']) as $digital => $name) {
					update_post_meta($post_id,'scottcart_digital_attribute_name'.$count_digital_attributes,$name);
					update_post_meta($post_id,'scottcart_digital_attribute_file'.$count_digital_attributes,sanitize_text_field($_POST['scottcart_digital_attribute_file'][$digital]));
					update_post_meta($post_id,'scottcart_digital_attribute_assignment'.$count_digital_attributes,sanitize_text_field($_POST['scottcart_digital_attribute_assignment'][$digital]));
					$count_digital_attributes++;
				}
			}
			
			// images
			$count_images = "0";
			$count_files = "1";
			if (isset($_POST['scottcart_image_file'])) {
				foreach (array_map('sanitize_text_field',$_POST['scottcart_image_file']) as $digital => $name) {
					
					if (isset($_POST['scottcart_image_featured'])) {
						$scottcart_image_featured = $_POST['scottcart_image_featured'];
					} else {
						$scottcart_image_featured = '';
					}
					
					update_post_meta($post_id,'scottcart_image_file'.$count_images,sanitize_text_field($_POST['scottcart_image_file'][$digital]));
					update_post_meta($post_id,'scottcart_image_featured',intval($scottcart_image_featured));
					update_post_meta($post_id,'scottcart_image_assignment'.$count_images,intval($_POST['scottcart_image_assignment'][$digital]));
					$count_images++;
					$count_files++;
				}
			}
			
			
			// counts - how many items are currently used
			update_post_meta($post_id,'scottcart_physical_count',$count_physical);
			update_post_meta($post_id,'scottcart_digital_count',$count_digital);
			update_post_meta($post_id,'scottcart_service_count',$count_service);
			update_post_meta($post_id,'scottcart_physical_attribute_count',$count_physical_attributes);
			update_post_meta($post_id,'scottcart_digital_attribute_count',$count_digital_attributes);
			update_post_meta($post_id,'scottcart_image_count',$count_images);
			
			
			// button name
			update_post_meta($post_id,'scottcart_button_name',sanitize_text_field($_POST['scottcart_button_name']));
			
			// external
			update_post_meta($post_id,'scottcart_external',sanitize_text_field($_POST['scottcart_external']));
			
			// short desc
			update_post_meta($post_id,'scottcart_short_desc',sanitize_text_field($_POST['scottcart_short_desc']));
			
			// after order notes
			update_post_meta($post_id,'scottcart_after_notes',wp_kses_post($_POST['scottcart_after_notes']));
			
			
			$action_array = array(
				'post_id' 	=> $post_id,
				'post' 		=> $_POST
			);
			
			do_action('scottcart_product_save',$action_array);
			
			
		}	
	}
	add_action( 'save_post', 'scottcart_save_meta_box_product' );