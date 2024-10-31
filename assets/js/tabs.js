jQuery(document).ready(function() {

	jQuery('#scottcart-tabs li a').click(function(e) {
		e.preventDefault();
		
		var t = jQuery(this).attr('id');
		
		jQuery('#tab').val(t);
			
			jQuery('.scottcart_account_purchase_details_tab').hide();
			jQuery('.scottcart-nav-tab').removeClass('scottcart-nav-tab-active');
			jQuery(this).addClass('scottcart-nav-tab-active');
			
			jQuery('.scottcart-container').hide();
			jQuery('#'+ t + 'C').show();
			
			console.log('#'+ t + 'C');
	});
	
});