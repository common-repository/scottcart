<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function scottcart_settings_page() {

	global 	$scottcart_options, $scottcart_active_tab;

	if ( !current_user_can( "manage_options" ) )  {
		wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator.",'scottcart' ));
	}

	?>
	
	<div class="scottcart-wrapper">
		<br />
		<form method='POST' action='options.php'>
			
			<?php settings_fields('scottcart_settings_group'); ?>
					
					<?php scottcart_settings_render_menu(); ?>
					
			<?php scottcart_settings_render(); ?>
			
			<input type="hidden" name="scottcart_settings[tab]" id="tab" value="<?php echo $scottcart_active_tab; ?>">			
			
		</form>
	</div>
	
	<script>
		jQuery("input[name=_wp_http_referer]").val('admin.php?page=scottcart_settings_page');
	</script>
	<?php

}