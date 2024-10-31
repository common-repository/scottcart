jQuery(document).ready(function($) {
	
	var $input = jQuery('#scottcart_purcahse_confirmation_status');
	
	if ($input.val() == 'pend') {
		var timeout = setInterval(scott_cart_reload, 5000);    
		
		function scott_cart_reload () {
			location.reload(true)
		}
	}
	
});