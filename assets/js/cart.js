// globals
var order_id;

jQuery(document).ready(function($) {

	// globals
	var problem;
	
	// on back button press (ex. from paypal) refresh cart page since it will empty
	jQuery(document).ready(function(e) {
		var $input = jQuery('#scottcart_refresh');
		$input.val() == 'yes' ? location.reload(true) : $input.val('yes');
	});
	
	
	// remove item from cart or change quantity
	jQuery('.scottcart_cart_container').on( 'click keyup', '.scottcart_cart_item_remove, .scottcart_quantity', function(event) {
		
		// screen to cover cart body
		jQuery("<div></div>").css({
			position: "absolute",
			width: "100%",
			height: "100%",
			top: 0,
			left: 0,
			background: "#F7F7F7",
			opacity: .5,
		}).appendTo($("#scottcart_cart_body").css("position", "relative"));
		
		var data = {
			'action':	'scottcart_update_cart',
			'element':	jQuery(event.target).attr('element'),
			'nonce':	jQuery('#scottcart_cart_nonce').val(),
			'id':		jQuery(this).data("id"),
			'quantity':	jQuery(this).val(),
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
				
				if (result.body == "empty") {
					location.reload();
				} else {
					jQuery("#scottcart_cart_subtotal").html(result.subtotal);
					jQuery("#scottcart_cart_coupon_left").html(result.discount_left);
					jQuery("#scottcart_cart_coupon_right").html(result.discount_right);
					jQuery(".scottcart_tax_cart_amount").html(result.tax);
					jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
					jQuery(".scottcart_cart_purchase_total").html(result.total);
					jQuery("#scottcart_cart_body").html(result.body);
					jQuery(".scottcart_cart_details_shipping_div").html(result.shipping_body);
					jQuery("#scottcart_gateway_payment_methods_container").html(result.gateway_body_header);
					jQuery("#scottcart_gateway_container").html(result.gateway_body);
					jQuery("#scottcart_payment_container").css('display','block');
					
					if (result.free == "free") {
						jQuery("#scottcart_gateway_payment_methods_container").html('');
						jQuery("#scottcart_gateway_container").html('');
						jQuery("#scottcart_payment_container").css('display','none');
					}
					
					if (result.reload == "reload") {
						jQuery(".scottcart_shipping_address").html('');
						jQuery(".scottcart_shipping_details").html('');
					}
				}
				
			}
		});
		
	});
	
	// fucntions used for disabling button or undisabling buttons
	function scottcart_disable_button() {
		jQuery('#scottcart_cart_add').val(ajax_object.loading_text);
		jQuery('#scottcart_cart_add').attr('disabled', 'disabled');
	}
	
	function scottcart_undisable_button(response) {
		//jQuery('#scottcart_cart_add').val(response);
		//jQuery('#scottcart_cart_add').removeAttr("disabled");
		//jQuery('#scottcart_cart_add').attr("id",'scottcart_cart');
		jQuery('#scottcart_cart_add').toggle();
		jQuery('#scottcart_cart').toggle();
	}
	
	
	// redirect to cart
	jQuery("#scottcart_product_top").on('click','#scottcart_cart',function(e) {
		
		e.preventDefault();
		
		jQuery('#scottcart_cart').attr('disabled', 'disabled');
		
		var path = jQuery('#scottcart_cart_path').val();
		window.location.href = ajax_object.cart_url;
	});
	
	
	
	// add item to cart
	jQuery('#scottcart_cart_add').on('click', function(e) {
		
		e.preventDefault();
		
		var quantity = jQuery('#scottcart_quantity').val();
		
		scottcart_disable_button();
		
		if (jQuery('#scottcart_product_multi').val() == "1") {
			//multi item
			
			var count = jQuery('#scottcart_product_count').val();
			
			for(counter = 0; counter < count; counter++) {
				
				if(document.getElementById('scottcart_product_price_id' + counter).checked) {
					
					var sent;
					sent = true;
					
					var data = {
						'action': 		'scottcart_add_item_to_cart',
						'nonce':		jQuery('#scottcart_cart_nonce').val(),
						'id': 			jQuery('#scottcart_product_id').val(),
						'quantity': 	quantity,
						'price_id': 	jQuery('#scottcart_product_price_id' + counter).val(),
						'attribute_id': jQuery('#scottcart_product_price_id' + counter).data('attribute')
					};
					// note - multi item cannot have attributes for physical product types
					
					jQuery.post(ajax_object.ajax_url, data, function(response) {
						if (response == "redirect") {
							window.location.href = ajax_object.cart_url;
						} else {
							scottcart_undisable_button(response);
						}
					});
					
				}
				
				if (sent != true) {
					scottcart_undisable_button();
				}
				
			}
			
		} else {
			// single item
			
			if (jQuery('#scottcart_product_menu').val() == "1" && jQuery('#scottcart_product_count').val() > "1") {
				
				// radio 
				var data = {
					'action': 		'scottcart_add_item_to_cart',
					'nonce':		jQuery('#scottcart_cart_nonce').val(),
					'id': 			jQuery('#scottcart_product_id').val(),
					'type': 		jQuery('#scottcart_product_type').val(),
					'quantity': 	quantity,
					'price_id': 	jQuery("input[name=scottcart_radio_name]:checked").val(),
					'attribute_id': jQuery("input[name=scottcart_radio_attribute_name]:checked").val()
				};
				
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					if (response == "redirect") {
						window.location.href = ajax_object.cart_url;
					} else {
						scottcart_undisable_button(response);
					}
				});
				
			} else {
				// dropdown
				var data = {
					'action': 		'scottcart_add_item_to_cart',
					'nonce':		jQuery('#scottcart_cart_nonce').val(),
					'id': 			jQuery('#scottcart_product_id').val(),
					'type': 		jQuery('#scottcart_product_type').val(),
					'quantity': 	quantity,
					'price_id': 	jQuery('.scottcart_product_price_id').val(),
					'attribute_id': jQuery('.scottcart_product_attribute_id').val()
				};
				
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					if (response == "redirect") {
						window.location.href = ajax_object.cart_url;
					} else {
						scottcart_undisable_button(response);
					}
				});
				
			}
			
		}
		
	});
	
	
	// apply coupon
	jQuery('.scottcart_cart_container').on( 'keyup', '#scottcart_coupon', function(e) {
		
		e.preventDefault();
		
		var data = {
			'action': 		'scottcart_check_coupon',
			'nonce':		jQuery('#scottcart_cart_nonce').val(),
			'code': 		jQuery('#scottcart_coupon').val(),
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
				
				if (result.false_result == "false") {
					jQuery('#scottcart_coupon').addClass('scottcart-has-error');
				} else {
					jQuery("#scottcart_cart_subtotal").html(result.subtotal);
					jQuery("#scottcart_cart_coupon_left").html(result.discount_left);
					jQuery("#scottcart_cart_coupon_right").html(result.discount_right);
					jQuery(".scottcart_tax_cart_amount").html(result.tax);
					jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
					jQuery(".scottcart_cart_purchase_total").html(result.total);
					jQuery(".scottcart_cart_details_shipping_div").html(result.shipping_body);
					jQuery("#scottcart_payment_container").css('display','block');
					
					if (result.free == "free") {
						jQuery("#scottcart_gateway_payment_methods_container").html('');
						jQuery("#scottcart_gateway_container").html('');
						jQuery("#scottcart_payment_container").css('display','none');
					}
				}
				
			}
		});
	
	});
	
	
	// remove coupon 
	jQuery('.scottcart_cart_container').on( 'click', '#scottcart_discount_remove', function(e) {
		
		e.preventDefault();
		
		var data = {
			'action': 		'scottcart_coupon_remove',
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
				
				if (result.false_result == "false") {
					jQuery('#scottcart_coupon').addClass('scottcart-has-error');
				} else {
					jQuery("#scottcart_cart_subtotal").html(result.subtotal);
					jQuery("#scottcart_cart_coupon_left").html(result.discount_left);
					jQuery("#scottcart_cart_coupon_right").html(result.discount_right);
					jQuery(".scottcart_tax_cart_amount").html(result.tax);
					jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
					jQuery(".scottcart_cart_purchase_total").html(result.total);
					jQuery(".scottcart_cart_details_shipping_div").html(result.shipping_body);
					jQuery("#scottcart_gateway_payment_methods_container").html(result.gateway_body_header);
					jQuery("#scottcart_gateway_container").html(result.gateway_body);
					jQuery("#scottcart_payment_container").css('display','block');
					
					if (result.free == "free") {
						jQuery("#scottcart_gateway_payment_methods_container").html('');
						jQuery("#scottcart_gateway_container").html('');
						jQuery("#scottcart_payment_container").css('display','none');
					}
				}
				
			}
		});
		
	});
	
	
	
	
	// change billing or shipping country - load state / province list
	jQuery('.scottcart_cart_container').on( 'change', '.scottcart_country_list', function(event) {
		
		var country_id = 	jQuery(this).val();
		var data_id = 		jQuery(this).data('id');
		
		jQuery('.scottcart_state_list').attr("disabled","disabled");
		
		if (country_id != "0" && country_id != "" && country_id != undefined) {
			
			var data = {
				'action': 			'scottcart_get_state_list',
				'nonce':			jQuery('#scottcart_cart_nonce').val(),
				'country_id': 		country_id,
				'element': 			data_id,
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
					
					jQuery(".scottcart_tax_cart_amount").html(result.tax);
					jQuery("#scottcart_state_list_div_"+data_id).html(result.state_list);
					jQuery(".scottcart_cart_purchase_total").html(result.total);
					
					if (data_id == 'shipping') {
						jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
						jQuery(".scottcart_cart_details_shipping_div").html(result.shipping_body);
					}
					
					jQuery('.scottcart_state_list').removeAttr('disabled');
				}
			});
		
		}
	});
	
	// change billing or shipping state / province - load details
	jQuery('.scottcart_cart_container').on( 'change keyup', '.scottcart_state_list', function(event) {
		
		var country_id = 	jQuery(this).data("id");
		var element = 		jQuery(event.target).attr('element');
		
		if (country_id != "0" && country_id != "" && country_id != undefined) {
			
			var data = {
				'action': 			'scottcart_get_state_details',
				'nonce':			jQuery('#scottcart_cart_nonce').val(),
				'country_id': 		country_id,
				'state_id': 		jQuery(this).val(),
				'element': 			element,
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
					
					jQuery(".scottcart_tax_cart_amount").html(result.tax);
					jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
					jQuery(".scottcart_cart_purchase_total").html(result.total);
					
					if (element == 'shipping') {
						jQuery(".scottcart_cart_details_shipping_div").html(result.shipping_body);
					}
					
				}
			});
		
		}
	});
	
	
	
	// change shipping rate
	jQuery('.scottcart_cart_details_shipping_div').on('click','.scottcart_alternate', function() {
		jQuery('input[name="scottcart_shipping_method"]', this).prop("checked",true);
		jQuery('.scottcart_alternate').removeClass('scottcart_cart_shipping_selected');
		jQuery(this).addClass('scottcart_cart_shipping_selected');
		
		var data = {
			'action': 			'scottcart_set_shipping_rate',
			'nonce':			jQuery('#scottcart_cart_nonce').val(),
			'amount': 			jQuery(this).attr("data-id"),
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
				
				jQuery(".scottcart_tax_cart_amount").html(result.tax);
				jQuery(".scottcart_tax_shipping_amount").html(result.shipping);
				jQuery(".scottcart_cart_purchase_total").html(result.total);
				
			}
		});
	});
	
	
	// change payment method
	jQuery('#scottcart_payment_container').on('click','.scottcart_alternate', function() {
		
		jQuery('input[name="scottcart_payment_method"]', this).prop("checked",true);
		jQuery('.scottcart_alternate').removeClass('scottcart_cart_gateway_selected');
		jQuery(this).addClass('scottcart_cart_gateway_selected');
		
		var data = {
			'action': 			'scottcart_load_function',
			'nonce':			jQuery('#scottcart_cart_nonce').val(),
			'function': 		jQuery('input[name="scottcart_payment_method"]', this).attr('data-fn'),
		};
		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			jQuery("#scottcart_gateway_container").html(response);
		});
	});


	
	
	
	
	
	////////////////////////////////////////////////  non cart specific
	
	// empty cart
	jQuery(".scottcart_cart_container").on('click','.scottcart_empty_cart',function(e) {
		
		e.preventDefault();
		
		var data = {
			'action': 			'scottcart_empty_cart',
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
				location.reload();
			}
		});
		
	});
	
	

	
	
	// redirect to external
	jQuery('#scottcart_external').click(function(e) {
		
		e.preventDefault();
		
		jQuery('#scottcart_cart').attr('disabled', 'disabled');
		
		var path = jQuery(this).attr("data-id");
		window.location.href = path;
	});
	
	
	
	////////////////////////////////////////////////  form validation
	
	// pre validation
    jQuery('#scottcart_cart_form').validate({
        rules: {
			email: {
                required: true,
				email: true
            },
			email_again: {
                required: true,
				email: true,
				equalTo: "#scottcart_email"
            },
        },
		errorPlacement: function () { }, // remove error messages
		errorClass : "scottcart-has-error", // apply error class
		submitHandler: function(form) {
            //jQuery.post('subscript.php', jQuery('#myForm').serialize());
			
			// form is valid - run pending order function
			scottcart_create_pending_order();
        }
    });
	
	
	/////////////////////////////////////////**********************////////////////////////////////////////////////////////////////// change class to scottcart_validate
	// dynamically add a required validation rule to every input element that has the class scottcart_validate
	jQuery(".scottcart_validate").each(function () {
		jQuery(this).rules('add', {
			required: true
		});
	});
	
	
	// create pending order
	function scottcart_create_pending_order() {
	
		scottcart_cart_submit_disable();
		
		// create pending order
		var data = {
			'action': 			'scottcart_cart_submit',
			'email': 			jQuery('#scottcart_email').val(),
			'first_name': 		jQuery('#scottcart_first_name').val(),
			'last_name': 		jQuery('#scottcart_last_name').val(),
			'billing_name': 	jQuery('#scottcart_billing_name').val(),
			'billing_line_1': 	jQuery('#scottcart_billing_line_1').val(),
			'billing_line_2': 	jQuery('#scottcart_billing_line_2').val(),
			'billing_country': 	jQuery('#scottcart_billing_country').val(),
			'billing_state': 	jQuery('#scottcart_billing_state').val(),
			'billing_city': 	jQuery('#scottcart_billing_city').val(),
			'billing_zip': 		jQuery('#scottcart_billing_zip').val(),
			'shipping_name': 	jQuery('#scottcart_shipping_name').val(),
			'shipping_line_1': 	jQuery('#scottcart_shipping_line_1').val(),
			'shipping_line_2': 	jQuery('#scottcart_shipping_line_2').val(),
			'shipping_country': jQuery('#scottcart_shipping_country').val(),
			'shipping_state': 	jQuery('#scottcart_shipping_state').val(),
			'shipping_city': 	jQuery('#scottcart_shipping_city').val(),
			'shipping_zip': 	jQuery('#scottcart_shipping_zip').val(),
			'gateway': 			jQuery('input[name=scottcart_payment_method]:checked').val(), // gateway slug passed in value field
			'ip': 				jQuery('#purchase_ip').val(),
			'nonce':			jQuery('#scottcart_cart_nonce').val(),
		};
		
		jQuery.ajax({
			type: "POST",
			data: data,
			dataType: "json",
			async: false,
			url: ajax_object.ajax_url,
			xhrFields: {
				withCredentials: true
			},
			success: function (result) {
				order_id = result.order_id;
				
				// if free redirect to confirmation
				if (result.status == "completed") {
					window.location.replace(ajax_object.return_url + "?order_id=" + result.order_id);
					return false;
				} else {
					// run gateway JS function
					scottcart_gateway_function();
				}
				
			}
		});

	}
	
	// run gateway JS function code
	function scottcart_gateway_function() {
		
		var fn = jQuery('input[name=scottcart_payment_method]:checked').data('js')+'()';
		
		if (fn != 'undefined()') {
			var tmpFunc = new Function(fn);
			tmpFunc();
		} else {
			scottcart_empty();
		}
	}
	
});

// below functions are defined in the global namespace so that they can be easily accessable for gateways

// empty function used in case a gateway does not have any JS that needs to be run
function scottcart_empty() {
	scottcart_private_function();
}


// run private gateway function
function scottcart_private_function() {

	var url = ajax_object.ajax_post;
	var nonce = jQuery('#scottcart_cart_nonce').val();
	var fnp = jQuery('input[name=scottcart_payment_method]:checked').data('fnp'); // gateway private function
	
	var form = jQuery('<form action="' + url + '" method="post">' +
	'<input type="hidden" name="scottcart_cart_nonce" value="' + nonce + '" />' +
	'<input type="hidden" name="action" value="scottcart_cart_submit" />' +
	'<input type="hidden" name="fnp" value="' + fnp + '" />' +
	'<input type="hidden" name="order_id" value="' + order_id + '" />' +
	'</form>');
	jQuery('body').append(form);
	form.submit();
	
}


function scottcart_cart_submit_disable() {
	jQuery('#scottcart_purchase_button').attr('disabled', 'disabled');
	//jQuery('#scottcart_purchase_button').css({"background-image" : "url("+ajax_object.loading_icon+")","background-repeat" : "no-repeat","background-position" : "center"});
	jQuery('#scottcart_purchase_button').val(ajax_object.loading_text);
}

function scottcart_cart_submit_enable() {
	jQuery('#scottcart_purchase_button').removeAttr("disabled");
	//jQuery('#scottcart_purchase_button').css({"background-image" : "unset"});
	jQuery('#scottcart_purchase_button').val(ajax_object.submit_text);
}