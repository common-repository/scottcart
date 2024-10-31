jQuery(document).ready(function($) {

	// update account profile info
	jQuery('#scottcart_account_submit').click(function(e) {
		
		jQuery('#scottcart_account_submit').attr('disabled', 'disabled');
		
		var data = {
			'action': 'scottcart_account_profile',
			'fname': jQuery('#scottcart-fname').val(),
			'lname': jQuery('#scottcart-lname').val(),
			'id': jQuery('#scottcart-id').val(),
			'nonce': jQuery('#scottcart-nonce').val()
		};
		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			jQuery('#scottcart-result').html(response);
			jQuery('#scottcart-result').show();
			jQuery('#scottcart-result').delay(2000).fadeOut(400);
			jQuery('#scottcart_account_submit').removeAttr("disabled");
		});
		
		e.preventDefault();
		
	});
	
	jQuery('.scottcart_account_details_view').click(function(e) {
		
		jQuery('.scottcart-container').hide();
		jQuery('.scottcart_account_purchase_details_tab').hide();
		
		
		var data = {
			'action': 'scottcart_get_account_purchase_details',
			'id': jQuery(this).attr("data-id"),
			'nonce': jQuery(this).attr("id")
		};
		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			jQuery(".scottcart_account_purchase_details_tab").html(response);
			jQuery('.scottcart_account_purchase_details_tab').show();
		});
		
	});
});