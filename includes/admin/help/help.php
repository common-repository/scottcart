<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_settings_help () {
    $screen = get_current_screen();

    $screen-> add_help_tab(array(
        'id'	=> 'my_help_tab',
        'title'	=> __('My Help Tab'),
        'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
    ));
	
	$screen-> add_help_tab(array(
        'id'	=> 'my_help_tab2',
        'title'	=> __('My Help Tab2'),
        'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.2' ) . '</p>',
    ));
	
	$screen-> set_help_sidebar(
	'<p>' . __('This is the content you will be adding to the sidebar for the current page.') . '</p>'
	);
}