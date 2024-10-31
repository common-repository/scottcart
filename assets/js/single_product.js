jQuery(document).ready(function($) {

	// single product page - load images on mouseover
	jQuery(".scottcart_image").hover(function() {
		var src = jQuery(this).attr('src');
		jQuery('.scottcart_image').removeClass('scottcart_image_hover');
		jQuery(this).addClass('scottcart_image_hover');
		jQuery("#scottcart_image_main").attr('src',src);
		jQuery(".zoomImg").attr('src',src);
		jQuery("#scottcart_image_main").attr('srcset',src);
		jQuery("#scottcart_image_main").attr('sizes','');
		jQuery("#scottcart_image_main").attr('width','');
		jQuery("#scottcart_image_main").attr('height','');
	});

	// zoom effect
	var zoom_effect = jQuery("#scottcart_zoom_effect").val();
	if (zoom_effect == "0") {
		// apply zoom effect for shop main image
		jQuery("#scottcart_image_main").wrap('<span style="display:inline-block"></span>').css('display', 'block').parent().zoom();
	}

	// remove width and height of main shop image
	// wp_get_attachment_image defaults do not look right with max-height and max-width set via css
	jQuery("#scottcart_image_main").attr('width','');
	jQuery("#scottcart_image_main").attr('height','');


	// physical product - reload attributes on options change
	jQuery(".scottcart_product_price_id").change(function () {
		
		var data = {
			'action': 		'scottcart_get_price_attributes',
			'id': 			jQuery(this).val(),
			'type': 		jQuery(this).attr("data-id"),
			'post_id': 		jQuery('#scottcart_product_id').val(),
			'nonce':		jQuery('#scottcart_cart_nonce').val(),
		};
		
		jQuery.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_object.ajax_url,
			xhrFields: {
				withCredentials: true
			},
			success: function (result) {
				jQuery(".scottcart_product_attribute_id").html(result.response);
				
				// disabled submit button if there is no quantity
				if (result.disabled == 'true') {
					jQuery("#scottcart_cart_add").prop('disabled', true);
				} else {
					jQuery("#scottcart_cart_add").prop('disabled', false);
				}
			}
		});
	});


	jQuery('.scottcart_single_product_radio').on('click','.scottcart_alternate', function() {
		jQuery('input[name="scottcart_radio_name"]', this).prop("checked",true);
		jQuery('input[name="scottcart_radio_attribute_name"]', this).prop("checked",true);
		jQuery('input[name="scottcart_radio_attribute_name"]', this).prop("checked",true);
		
		var data = {
			'action': 		'scottcart_get_price_attributes',
			'id': 			jQuery(this).attr("data-value"),
			'type': 		jQuery(this).attr("data-id"),
			'post_id': 		jQuery('#scottcart_product_id').val(),
			'nonce':		jQuery('#scottcart_cart_nonce').val(),
		};
		
		jQuery.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			url: ajax_object.ajax_url,
			xhrFields: {
				withCredentials: true
			},
			success: function (result) {
				jQuery(".scottcart_product_attribute_id").html(result.response);
				
				// disabled submit button if there is no quantity
				if (result.disabled == 'true') {
					jQuery("#scottcart_cart_add").prop('disabled', true);
				} else {
					jQuery("#scottcart_cart_add").prop('disabled', false);
				}
			}
		});
	});
	
	jQuery('.scottcart_single_product_radio_attribute').on('click','.scottcart_alternate', function() {
		jQuery('input[name="scottcart_radio_attribute_name"]', this).prop("checked",true);
	});
	
	jQuery('.scottcart_single_product_multi').on('click','.scottcart_alternate', function() {
		jQuery('input[name="scottcart_multi_name"]', this).trigger('click');
	});
	
});