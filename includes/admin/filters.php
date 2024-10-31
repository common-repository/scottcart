<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



// on new product page, change title field value
function scottcart_custom_enter_title( $input ) {

    global $post_type;

    if( is_admin() && 'Enter title here' == $input && 'scottcart' == $post_type ) {
        return 'Product name';
	}
	
	if ( $input == 'Publish' && $post_type == 'scottcart_order') {
		return 'Save Order';
	}
	
	if ( $input == 'Update' && $post_type == 'scottcart_order') {
		return 'Update Order';
	}
	
	if ( $input == 'Publish' && $post_type == 'scottcart_product') {
		return 'Save Product';
	}
	
	if ( $input == 'Update' && $post_type == 'scottcart_product') {
		return 'Update Product';
	}
	
	
    return $input;
}
add_filter('gettext','scottcart_custom_enter_title');