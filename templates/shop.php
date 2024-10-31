<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_shop_page($atts) {

	$output = "";
	
	$output .= "<div class='scottcart_body_main hentry'>";

	
	// hide entry-title
	if (scottcart_get_option('shop_plugin_heading') == '1') {
		echo "<style>.entry-title { display: none; }</style>";
	}
	
	if (scottcart_get_option('shop_plugin_heading') == '1') {
		
		$output .= "<h1 class='scottcart_title title-post'>";
		
		if ($atts['title']) {
			$output .= $atts['title'];
		} else {
			$output .= scottcart_get_option('text_shop');
		}
		
		$output .= "</h1>";
		
	}
	
	if (!empty($_GET['category'])) {
		$output .= "<h3>";
		$category_input = sanitize_text_field($_GET['category']);
		$atts['category'] = $category_input;
		$term = get_term_by('slug',$category_input,'product_category');
		$output .= $term->name;
		$output .= "</h3>";
	}
		
		
		if (!empty($atts['category_menu'])) {
			$terms = get_terms( array(
				'taxonomy' => 'product_category',
				'hide_empty' => true,
			));
			
			foreach ($terms as $term) {
				$output .= "<div class='scottcart_term_item'>";
				$output .= "<a href='?category=";
				$output .= $term->slug;
				$output .= "'>";
				$output .= $term->name;
				$output .= "</a>";
				$output .= "</div>";
			}
			
			$output .= "<br /><br />";
		}
		
		
		$output .= '<div class="scottcart_parent">';
			
			if (!empty($atts['page'])) {
				$paged = $atts['page'];
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
			}
			
			if (!empty($atts['limit'])) {
				$per_page = $atts['limit'];
			} else {
				$per_page = scottcart_get_option('products_per_page');
			}
			
			$query = array(
				'post_type'      	=> 'scottcart_product',
				'orderby'        	=> $atts['orderby'],
				'order'          	=> $atts['order'],
				'paged' 			=> $paged,
				'post_status'		=> 'publish',
				'posts_per_page' 	=> $per_page,
			);
			
			$query['tax_query'] = array(
					'relation' => $atts['relation']
			);
			
			if ($atts['category']) {
				
				$categories = explode(',',$atts['category']);
				
				foreach ($categories as $category) {
					
					$query['tax_query'][] = array(
						'taxonomy' => 'product_category',
						'field'    => 'slug',
						'terms'    => $category,
					);
				}
			}
			
			$scottcart_products = new WP_Query($query);
			
			foreach ($scottcart_products->posts as $product) {
				
				$output .= "<div style='width:"; if ($atts['width']) { $output .= $atts['width']; } else { $output .= scottcart_get_option('box_width'); } $output .= "px' class='scottcart_box'>";
					$product_slug = scottcart_get_slug_by_post_type('scottcart_product');
					$url = get_site_url()."/".$product_slug."/".$product->post_name;
					$output .= "<a class='scottcart_fill-div' href='$url'>";
						
						// set defaults
						$featured = "";
						$img = "";
						
						$featured = get_post_meta($product->ID,'scottcart_image_featured');
						
						if (!empty($featured)) {
							if ($featured[0] != "0") {
								$featured[0]--;
								$img = get_post_meta($product->ID,'scottcart_image_file'.$featured[0]);
							} else {
								$img_default = get_post_meta($product->ID,'scottcart_image_file0');
								if (!empty($img_default)) {
									$img = get_post_meta($product->ID,'scottcart_image_file0');
								}
							}
							
							if (!empty($img)) {
								// use feature image
								$shop_page_image_size = scottcart_get_option('shop_page_image_size');
								if ($shop_page_image_size == '0') {
									$size = 'medium';
								} else {
									$size = 'large';
								}
								
								$output .= wp_get_attachment_image($img[0],$size,'', array( 'class' => 'scottcart_image_shop'));
							}
						}
						
						$output .= "<div class='scottcart_spacing_title'><span class='scottcart_shop_title'>$product->post_title</span></div>";
						
						$desc = get_post_meta($product->ID,'scottcart_short_desc', true);
						if (!empty($desc)) {
							$output .= "<div class='scottcart_spacing_desc'><span class='scottcart_shop_desc'>"; $output .= get_post_meta($product->ID,'scottcart_short_desc', true); $output .= "</span></div>";
						}
						
						$output .= "<span class='scottcart_shop_more' style='background-color:"; $output .= scottcart_get_option('secondary_button_color'); $output .= "; color:"; $output .= scottcart_get_option('secondary_button_text_color'); $output .= ";'>"; if ($atts['more']) { $output .= $atts['more']; } else { $output .= scottcart_get_option('text_2'); } $output .= "</span><br /><br />";
						
					$output .= "</a>";
				$output .= '</div>';
			}
			
			$output .= "<br /><br />";
				
				if (empty($atts['pagination'])) { $atts['pagination'] = '0'; }
				
				if ($atts['pagination'] == '0') {
					$output .= "<div class='scottcart_pagination'>";
						// pagination
						$big = 999999999;
						 $output .= paginate_links( array(
							'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $scottcart_products->max_num_pages
						) );
					$output .= '</div>';
				}
				
				// if manualy height is set, use that to override existing image height.
				$box_height = scottcart_get_option('box_height');
				
				if ($atts['height']) {
					echo "<style>.scottcart_image_shop { height:"; echo $atts['height']; echo "px !important; } </style>";
				} elseif (!empty($box_height)) {
					echo "<style>.scottcart_image_shop { height:"; echo $box_height; echo "px !important; } </style>";
				}
				
		$output .= '</div>';
		
	$output .= '</div>';

	return $output;

}