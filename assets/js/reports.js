jQuery(document).ready(function($) {


	// change product new
	jQuery('#scottcart_report').on( 'change', '#scottcart_year_report_change', function() {
		
		var year = jQuery(this).val();
		
		var data = {
			'action': 'scottcart_earnings_report',
			'year': jQuery(this).val()
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_report").html(response);
		});
	});
	
	
	// top level
	jQuery('#scottcart-tabs-reports li a').click(function() {
		var t = jQuery(this).attr('id');
		
		jQuery('#tab').val(t);
		
		jQuery('.nav-tab').removeClass('nav-tab-active');
		jQuery(this).addClass('nav-tab-active');
		
		var function_name = jQuery('#'+ t + 'C').attr("data-id");
		
		jQuery('.scottcart-more').hide();
		
		t = t.slice(0, -1);
		jQuery('.scottcart-more-'+ t).show();
		jQuery('.tab-more').removeClass('current');
		jQuery('.'+ t + '0T').addClass('current');
		
		var data = {
			'action': 'scottcart_load_function',
			'function': function_name
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_report").html(response);
		});
		
	});
	
	// 2nd level
	jQuery('#scottcart-tabs-more-reports li a').click(function() {
		var t = jQuery(this).attr('id');
		jQuery('#tab').val(t);
		
		jQuery('.tab-more').removeClass('current');
		jQuery(this).addClass('current');
		
		
		var function_name = jQuery('#'+ t + 'C').attr("data-id");
		
		var data = {
			'action': 'scottcart_load_function',
			'function': function_name
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_report").html(response);
		});
		
	});
	
});