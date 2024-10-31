<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_before_product_content_single_product($content) {
	global $post;

	// zoom effect - has to be located here on the page to properly load
	echo "<input type='hidden' id='scottcart_zoom_effect' value='"; echo scottcart_get_option('zoom'); echo "'>";
	?>	

	<div id="scottcart_top">
		
		<div id="scottcart_short-desc">
			<?php echo get_post_meta($post->ID,'scottcart_short_desc', true); ?>
		</div>
		
		
		<div id="scottcart_product_top">
			<?php
			$scottcart_images = get_post_meta($post->ID,'scottcart_image_count', true);
			
			
			if ($scottcart_images > 1) {
				echo "<div id='scottcart_product_left'>";
				scottcart_single_product_images($post->ID);
				echo "</div>";
			} else {
				echo "<div id='scottcart_product_left_small'>";
				echo "</div>";
			}
			?>
			
			
			<div id="scottcart_product_middle">
				<div id='scottcart_image'>
					<?php
					// set defaults
					$featured = "";
					$img = "";
					
					$featured = get_post_meta($post->ID,'scottcart_image_featured');
					
					if (!empty($featured)) {
						if ($featured[0] != "0") {
							$featured[0]--;
							$img = get_post_meta($post->ID,'scottcart_image_file'.$featured[0]);
						} else {
							$img_default = get_post_meta($post->ID,'scottcart_image_file0');
							if (!empty($img_default)) {
								$img = get_post_meta($post->ID,'scottcart_image_file0');
							}
						}
						
						if (!empty($img)) {
							// use feature image
								
							$product_page_image_size = scottcart_get_option('product_page_image_size');
							if ($product_page_image_size == '0') {
								$size = 'medium';
							} else {
								$size = 'large';
							}	
							
							echo wp_get_attachment_image($img[0],$size,'', array( 'id' => 'scottcart_image_main'));
						}
					}
					?>
				</div>
			</div>
			
			
			
			<div id="scottcart_product_right">
				<?php				
				
				echo "<h4>";
					echo scottcart_get_option('text_4');
				echo "</h4>";
				
				$scottcart_type = get_post_meta($post->ID,'scottcart_type', true);
				$scottcart_multi = get_post_meta($post->ID,'scottcart_multi', true);
				$scottcart_price_menu = scottcart_get_option('price_menu');
				$scottcart_hide_sold_out = scottcart_get_option('hide_sold_out');
				$scottcart_attribute_enabled = get_post_meta($post->ID,"scottcart_variations", true);
				$disabled = 'false';
				$disabled_force = 'false';
				
				if (!isset($_SESSION['scottcart_cart'])) { $_SESSION['scottcart_cart'] = NULL; }
				
				if(scottcart_in_array_r($post->ID) && scottcart_get_option('mutiple_items') == '1'){ } else {
					
					if ($scottcart_type == "0" || $scottcart_type == "1" || $scottcart_type == "2") {
						
						if ($scottcart_type == "0") { $scottcart_type_name = "physical"; }
						if ($scottcart_type == "1") { $scottcart_type_name = "digital"; }
						if ($scottcart_type == "2") { $scottcart_type_name = "service"; }
						
						$scottcart_count = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_count", true);
						
						// single product - no dropdown or radio
						if ($scottcart_count == "1") {
							echo get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name0", true);
							echo " - ";
							$scottcart_price = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_price0", true);
							echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post');
							echo "<input type='hidden' class='scottcart_product_price_id' value='0'>";
							echo "<br />";
						}
						
						if ($scottcart_type_name == 'digital') {
							$scottcart_inventory_management_product = get_post_meta($post->ID,"scottcart_inventory", true);
						} else {
							$scottcart_inventory_management_product = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_inventory", true);
						}
						
						if ($scottcart_count > "1") {
							if ($scottcart_multi == "0") {
								
								
								if (scottcart_get_option('price_menu') == '1') {
									
									// product radio
									echo scottcart_get_option('text_6');
									echo "<br />";
									echo "<table class='scottcart_single_product_radio'><tr></tr>";
									for($i=0;$i<$scottcart_count;$i++) {
										$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name".$i, true);
										$scottcart_price = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_price".$i, true);
										$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_qty".$i, true);
										if (!empty($scottcart_name)) {
											if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_attribute_enabled == '0' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
												echo "<tr class='scottcart_alternate' data-id='radio' data-value='$i'>";
												echo "<td class='scottcart_single_product_radio_width'>
												
												<input type='radio'"; if ($i == "0") { echo " CHECKED "; } echo "name='scottcart_radio_name' class='scottcart_product_price_id' data-id='radio' value='$i'></td>";
												echo "<td>$scottcart_name</td>";
												echo "<td>&nbsp;&nbsp;&nbsp;"; echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); echo "</td>";
												echo "</tr>";
												$disabled = 'false';
												$disabled_force = 'true';
											}
										}
									}
									echo "</table>";
									
								} else {
									// dropdown
									echo scottcart_get_option('text_6');
									echo "<br />";
									echo "<select class='scottcart_input scottcart_product_price_id' data-id='dropdown'>";
									for($i=0;$i<$scottcart_count;$i++) {
										$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name".$i, true);
										$scottcart_price = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_price".$i, true);
										$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_qty".$i, true);
										if (!empty($scottcart_name)) {
											if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_attribute_enabled == '0' && $scottcart_qty == '0') { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
												echo "<option value='$i'>"; echo $scottcart_name; echo " - "; echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); echo "</option>";
												$disabled = 'false';
												$disabled_force = 'true';
											}
										}
									}
									echo "</select>";
									
								}
								
							} else {
								
								// mutli selection without variations
								if ($scottcart_attribute_enabled == '0') {
									
									// standard multi select
									echo scottcart_get_option('text_6');
									echo "<br />";
									echo "<table class='scottcart_single_product_multi'>";
									for($i=0;$i<$scottcart_count;$i++) {
										$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name".$i, true);
										$scottcart_price = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_price".$i, true);
										
										$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_qty".$i, true);
										if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
											
											echo "<tr class='scottcart_alternate'>";
											echo "<td class='scottcart_single_product_multi_width'><input name='scottcart_multi_name' type='checkbox' "; if ($i == "0") { echo " CHECKED "; } echo " id='scottcart_product_price_id$i' value='$i'> ";
											echo "<td>"; echo $scottcart_name; echo "</td>";
											echo "<td>"; echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); echo "</td>";
											echo "</tr>";
											$disabled = 'false';
											$disabled_force = 'true';
											
										}
									}
									echo "</table>";
									
								} else {
									
									// variations multi select
									echo scottcart_get_option('text_6');
									echo "<br />";
									echo "<table class='scottcart_single_product_multi'>";
									
									
									$scottcart_count_all = '0';
									$scottcart_attribute_count = get_post_meta($post->ID,"scottcart_physical_attribute_count", true);
									for($i=0;$i<$scottcart_count;$i++) {
										$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name".$i, true);
										$scottcart_price = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_price".$i, true);
										
										for($a=0;$a<$scottcart_attribute_count;$a++) {
											$attribute_assignment = get_post_meta($post->ID,"scottcart_physical_attribute_assignment".$a, true);
											
											if ($attribute_assignment == $i || $attribute_assignment == 'a') {
												$scottcart_name = get_post_meta($post->ID,"scottcart_physical_attribute_name".$a, true);
												if (!empty($scottcart_name)) {
													
													$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_qty".$a, true);
													if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
														
														echo "<tr class='scottcart_alternate'>";
														echo "<td class='scottcart_single_product_multi_width'><input name='scottcart_multi_name' type='checkbox' "; if ($scottcart_count_all == "0") { echo " CHECKED "; } echo " id='scottcart_product_price_id$scottcart_count_all' data-attribute='$a' value='$i'> ";
														echo "<td>"; echo get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_name".$i, true); echo "</td>";
														echo "<td>"; echo $scottcart_name; echo "</td>";
														echo "</td>";
														echo "<td>"; echo sanitize_meta( 'currency_scottcart',$scottcart_price,'post'); echo "</td>";
														echo "</tr>";
														$scottcart_count_all++;
														$disabled = 'false';
														$disabled_force = 'true';
														
													}
												}
											}
										}
										
									}
									echo "</table>";
								
								}
								
							}
						}
						
						if (isset($scottcart_count_all)) {
							echo "<input type='hidden' id='scottcart_product_count' value='$scottcart_count_all'>";
						}
						else if (isset($scottcart_count)) {
							echo "<input type='hidden' id='scottcart_product_count' value='$scottcart_count'>";
						}
						
						
						
						
						
						// display attribute menu for physical product types with attributes
						$scottcart_attribute_count = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_count", true);
						if ($scottcart_type == "0" && $scottcart_attribute_enabled == "1") {
							
							if ($scottcart_multi == "0") {
								echo "<br />";
								echo scottcart_get_option('text_7');
									
									// attributes
									$scottcart_attribute_count = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_count", true);
									
									if ($scottcart_attribute_count > 1) {
										if (scottcart_get_option('price_menu') == '1') {
											
											// attribute radio
											echo "<table class='scottcart_product_attribute_id scottcart_single_product_radio_attribute'><tr></tr>";
											$count = '0';
											for($i=0;$i<$scottcart_attribute_count;$i++) {
												$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_name".$i, true);
												$attribute_assignment = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_assignment".$i, true);
												$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_qty".$i, true);
												if ($attribute_assignment == '0' || $attribute_assignment == 'a') {
													if (!empty($scottcart_name)) {
														if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_attribute_enabled == '1' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
															echo "<tr class='scottcart_alternate'>";
															echo "<td class='scottcart_single_product_radio_width'><input type='radio'"; if ($count == "0") { echo " CHECKED "; } echo "name='scottcart_radio_attribute_name' class='scottcart_product_attribute_id' value='$i'>";
															echo "<td>"; echo $scottcart_name; echo "</td>";
															echo "</tr>";
															$count++;
															$disabled = 'false';
															$disabled_force = 'true';
														}
													}
												}
											}
											echo "</table>";
											
										} else {
											
											// attribute dropdown
											echo "<select class='scottcart_input scottcart_product_attribute_id'>";
											for($i=0;$i<$scottcart_attribute_count;$i++) {
												$attribute_assignment = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_assignment".$i, true);
												$scottcart_qty = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_qty".$i, true);
												if ($attribute_assignment == '0' || $attribute_assignment == 'a') {
													$scottcart_name = get_post_meta($post->ID,"scottcart_{$scottcart_type_name}_attribute_name".$i, true);
													if (!empty($scottcart_name)) {
														if ($scottcart_hide_sold_out == '0' && $scottcart_inventory_management_product == '1' && $scottcart_attribute_enabled == '1' && $scottcart_qty == 0) { if ($disabled_force != 'true') { $disabled = 'true'; } } else {
															echo "<option value='$i'>"; echo $scottcart_name; echo "</option>";
															$disabled = 'false';
															$disabled_force = 'true';
														}
													}
												}
											}
											echo "</select><br />";
											
										}
									}
									
							}
						}
						
						
						
						
						
						if ($scottcart_count == "1") {
							echo "<br />";
						}
						
						if (scottcart_get_option('quantity_product_page') == "0" && $scottcart_type != "3") {
							echo "<div id='scottcart_quantity_div'><br />";
							echo scottcart_get_option('text_8');
							echo "<br />";
							echo "<input min='1' step='1' name='scottcart_quantity' id='scottcart_quantity' class='scottcart_input' value='1' type='number'>";
							echo "</div>";
						} else {
							echo "<input type='hidden' name='scottcart_quantity' id='scottcart_quantity' value='1'>";
						}
					}
				}
				
				echo "<br />";
				
				$scottcart_button_text = scottcart_get_option('text_0');
				$bname = get_post_meta($post->ID,'scottcart_button_name', true);
				if (!empty($bname)) {
					$scottcart_button_text = get_post_meta($post->ID,'scottcart_button_name', true);
				}
				
				if ($scottcart_type == "3") {
					
					
					// external product ?>
					<input type="submit" id="scottcart_external" style="background-color: <?php echo scottcart_get_option('button_color'); ?>; color: <?php echo scottcart_get_option('button_text_color'); ?>;" value="<?php echo $scottcart_button_text; ?>" data-id="<?php echo get_post_meta($post->ID,'scottcart_external', true); ?>">
					
					
				<?php } else {
					
					
					if (isset($_SESSION['scottcart_cart']) && scottcart_get_option('mutiple_items') == '1') {
						// checkout button
							if(scottcart_in_array_r($post->ID)) { ?>
							<input type="submit" id="scottcart_cart" style="background-color: <?php echo scottcart_get_option('button_color'); ?>; color: <?php echo scottcart_get_option('button_text_color'); ?>;" value="<?php echo scottcart_get_option('text_1'); ?>">
						<?php } else {
							// add to cart button ?>
							<input type="submit" id="scottcart_cart_add" style="background-color: <?php echo scottcart_get_option('button_color'); ?>; color: <?php echo scottcart_get_option('button_text_color'); ?>;" value="<?php echo $scottcart_button_text; ?>">
						<?php }
						
					} else {
						// add to cart button	?>
						<input type="submit" id="scottcart_cart_add" style="background-color: <?php echo scottcart_get_option('button_color'); ?>; color: <?php echo scottcart_get_option('button_text_color'); ?>;" value="<?php echo $scottcart_button_text; ?>">
					<?php }
					
					?>
					<div>
						<input style='display:none;' type="submit" id="scottcart_cart" style="background-color: <?php echo scottcart_get_option('button_color'); ?>; color: <?php echo scottcart_get_option('button_text_color'); ?>;" value="<?php echo scottcart_get_option('text_1'); ?>">
					</div>
					<?php
				}
				
				if (scottcart_get_option('show_features') == 0) {
					
					$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'name');
					$features = wp_get_post_terms($post->ID,'product_feature');
					
					if ($features) {
						echo "<p></p>";
						echo scottcart_get_option('text_features');
						echo ":<br />";
					}
					
					foreach ($features as $feature) {
						echo "<div class='scottcart_feature_item'>";
							echo $feature->name;
						echo "</div> ";
					}
				}
				
				// disable submit button depending on quantity
				if ($disabled == 'true') {
					echo "<script>
					document.getElementById('scottcart_cart_add').setAttribute('disabled', 'disabled');
					</script>";
				}
				
				
				// create nonce
				$ajax_nonce = wp_create_nonce('scottcart_cart_nonce_'.$post->ID);
				echo "<input type='hidden' id='scottcart_cart_nonce' value='$ajax_nonce'>";
				?>
				<input type="hidden" id="scottcart_product_id" value="<?php echo $post->ID; ?>">
				<input type="hidden" id="scottcart_product_multi" value="<?php echo $scottcart_multi; ?>">
				<input type="hidden" id="scottcart_product_menu" value="<?php echo $scottcart_price_menu; ?>">
				<input type="hidden" id="scottcart_cart_path" value="<?php echo SCOTTCART_SITE_URL; ?>">
				
				<div id="scottcart_feedback">
				</div>
			</div>
		</div>
		<br />
	</div>
	
	
		<?php		
		// post content
		
		// check if hook for a sidebar exists
		if (has_action('scottcart_single_product_content_right')) {
			echo "<div id='scottcart_product_content_left'>";
			echo $content;
			echo "</div>";
		} else {
			echo $content;
			
		}
		?>
	
	<div id="scottcart_product_content_right">
		<?php
		if (has_action('scottcart_single_product_content_right')) {
			do_action('scottcart_single_product_content_right');
		}
		?>
	</div>

<?php
}
add_action('scottcart_before_product_content','scottcart_before_product_content_single_product');