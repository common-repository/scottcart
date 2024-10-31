jQuery(document).ready(function($) {

	// sync physical product names with product attributes dropdown
	jQuery( '#scottcart_products_table_physical' ).on( 'keyup', '.scottcart_physical_name', function() {
		
		var id = jQuery(this).attr('id');
		var name = jQuery(this).val();
		var a = jQuery( '.scottcart_physical_attribute_assignment option[value=' + id + ']' );
		
		if ( a.length > 0 ) {
			a.text( name );
		} else {
			jQuery( '.scottcart_physical_attribute_assignment' ).append(
				jQuery( '<option></option>' ).attr( 'value', id ).text( name )
			);
		};
		
	});
	
	// sync digtal product names with file attributes dropdown
	jQuery( '#scottcart_products_table_digital' ).on( 'keyup', '.scottcart_digital_name', function() {
		
		var id = jQuery(this).attr('id');
		var name = jQuery( this ).val();
		var a = jQuery( '.scottcart_digital_attribute_assignment option[value=' + id + ']' );
		
		if ( a.length > 0 ) {
			a.text( name );
		} else {
			jQuery( '.scottcart_digital_attribute_assignment' ).append(
				jQuery( '<option></option>' ).attr( 'value', id ).text( name )
			);
		};
		
	});

	// upload images
	jQuery("#scottcart_products_table_images").on('click','.scottcart_file',function(e) {
		e.preventDefault();
		
		var id = e.target.id;
		jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
		
		var image = wp.media({
			title: 'Upload Image',
			multiple: false
		}).open()
		
		.on('select', function() {
			jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
			
			var uploaded_image = image.state().get('selection').first();
			var image_id = uploaded_image.toJSON().id;
			jQuery('#scottcart_image_'+id).val(image_id);
			
			var data = {
				'action': 'scottcart_get_img_url',
				'id': image_id
			};	
			
			jQuery.post( ajaxurl, data, function (response) {
				jQuery('.scottcart_image_'+id).html(response);
			});
			
		})
		
		.on('close', function() {
			jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
		});
	});




	// upload files
	jQuery("#scottcart_products_table_digital_attributes").on('click','.scottcart_file',function(e) {
		e.preventDefault();
		
		var id = e.target.id;
		jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
		
		var image = wp.media({
			title: 'Upload Image / File',
			multiple: false
		}).open()
		
		.on('select', function() {
			jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
			
			var uploaded_image = image.state().get('selection').first();
			var image_id = uploaded_image.toJSON().id;
			var image_title = uploaded_image.toJSON().title;
			
			jQuery('#scottcart_digital_attribute_'+id).val(image_id);
			jQuery('#scottcart_digital_attribute_title_'+id).val(image_title);
		})
		
		.on('close', function() {
			jQuery('html, body').animate({ scrollTop: jQuery("#"+id).offset().top }, 400);
		});
	});












	// add new product physical
	jQuery(".scottcart_addCF_product_physical").click(function() {
		counter = document.getElementById("scottcart_products_table_physical").rows.length;
		jQuery("#scottcart_products_table_physical").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' class='scottcart_physical_name' id='"+counter+"' name='scottcart_physical_name[]' value=''></td><td><input type='text' size='6' name='scottcart_physical_price[]' value=''></td><td><input type='text' size='6' class='scottcart_variation scottcart_physical_inventory' name='scottcart_physical_qty[]' value=''></td><td><input type='text' size='6' class='scottcart_variation scottcart_physical_inventory' name='scottcart_physical_sku[]' value=''></td><td><a href='javascript:void(0);' class='scottcart_remCF_product' id='"+counter+"'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		
		var value = jQuery('#scottcart_physical_inventory_change').val();
		if (value == 0) {
			jQuery(".scottcart_physical_inventory").hide();
		}
	
	});
	
	// add new product digital
	jQuery(".scottcart_addCF_product_digital").click(function() {
		
		counter = document.getElementById("scottcart_products_table_digital").rows.length;
		jQuery("#scottcart_products_table_digital").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' class='scottcart_digital_name' name='scottcart_digital_name[]' value=''></td><td><input type='text' size='6' name='scottcart_digital_price[]' value=''></td><td class='scottcart_digital_inventory'><input type='text' size='6' class='scottcart_digital_inventory' name='scottcart_digital_qty[]' value=''></td><td class='scottcart_digital_inventory'><input type='text' size='6' class='scottcart_digital_inventory' name='scottcart_digital_sku[]' value=''></td>"+ajax_object.new_col+"<td><a href='javascript:void(0);' id='"+counter+"' class='scottcart_remCF_product'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		
		var value = jQuery('#scottcart_inventory_change').val();
		if (value == 0) {
			jQuery(".scottcart_digital_inventory").hide();
		}
		
	});
	
	// add new product service
	jQuery(".scottcart_addCF_product_service").click(function() {
		counter = document.getElementById("scottcart_products_table_service").rows.length;
		jQuery("#scottcart_products_table_service").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' name='scottcart_service_name[]' value=''></td><td><input type='text' size='6' name='scottcart_service_price[]' value=''></td><td><a href='javascript:void(0);' class='scottcart_remCF_service'><span class='dashicons dashicons-trash'></span></a></td></tr>");
	});
	
	
	
	
	
	
	
	// add new physical attribute
	jQuery(".scottcart_addCF_product_physical_attribute").click(function() {
		counter = document.getElementById("scottcart_products_table_physical_attributes").rows.length;
		jQuery("#scottcart_products_table_physical_attributes").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' name='scottcart_physical_attribute_name[]' value=''></td><td class='scottcart_physical_attribute_inventory'><input type='text' size='6' name='scottcart_physical_attribute_qty[]' value=''></td><td class='scottcart_physical_attribute_inventory'><input type='text' size='6' name='scottcart_physical_attribute_sku[]' value=''></td><td><select style='max-width:80px;' class='scottcart_physical_attribute_assignment' id='scottcart_physical_attribute_assignment"+counter+"' name='scottcart_physical_attribute_assignment[]'><option value='a'>All</option></td><td><a href='javascript:void(0);' class='scottcart_remCF_physical_attribute'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		
		var value = jQuery('#scottcart_physical_inventory_change').val();
		if (value == 0) {
			jQuery(".scottcart_physical_attribute_inventory").hide();
		}
		
		// sync product dropdown names with new attribute assignment dropdown
		jQuery('.scottcart_physical_name').each(function() {
			
			var name = jQuery(this).val();
			var id = jQuery(this).attr('id');
			
			jQuery( '#scottcart_physical_attribute_assignment'+counter ).append(
				jQuery( '<option></option>' ).attr( 'value', id ).text( name )
			);
			
		});
	
	});
	
	
	// add new digital attribute
	jQuery(".scottcart_addCF_product_digital_attribute").click(function() {
		counter = document.getElementById("scottcart_products_table_digital_attributes").rows.length;
		
		jQuery("#scottcart_products_table_digital_attributes").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' id='scottcart_digital_attribute_title_"+counter+"' size='15' name='scottcart_digital_attribute_name[]' value=''></td><td><input type='hidden' id='scottcart_digital_attribute_"+counter+"' name='scottcart_digital_attribute_file[]' value=''><a id='"+counter+"' class='scottcart_file'>Upload</a></td><td><select style='max-width:80px;' class='scottcart_digital_attribute_assignment' id='scottcart_digital_attribute_assignment"+counter+"' name='scottcart_digital_attribute_assignment[]'><option value='a'>All</option></td><td><a href='javascript:void(0);' class='scottcart_remCF_digital_attribute'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		
		// sync product dropdown names with new attribute assignment dropdown
		jQuery('.scottcart_digital_name').each(function() {
			
			var name = jQuery(this).val();
			var id = jQuery(this).attr('id');
			
			jQuery( '#scottcart_digital_attribute_assignment'+counter ).append(
				jQuery( '<option></option>' ).attr( 'value', id ).text( name )
			);
			
		});
		
	});
	
	
	
	
	
	// add new image
	jQuery(".scottcart_addCF_image").click(function() {
		counter = document.getElementById("scottcart_products_table_images").rows.length;
		
		if (counter < 9) {
			jQuery("#scottcart_products_table_images").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><div class='scottcart_image_"+counter+"'></div></td><td><input type='hidden' id='scottcart_image_"+counter+"' name='scottcart_image_file[]' value=''><a id='"+counter+"' class='scottcart_file'>Upload</a></td><td><input value='"+counter+"' type='radio' size='6' name='scottcart_image_featured' value=''></td><td><a href='javascript:void(0);' class='scottcart_remCF_image'><span class='dashicons dashicons-trash'></span></a></td></tr>");
			
			var value = jQuery('#scottcart_type_change').val();
			if (value != 0) {
				jQuery(".scottcart_image_assignment").hide();
			}
		} else {
			alert("You can only have 8 images per product.");
		}
		
	});
	
	// remove product physical
	jQuery("#scottcart_products_table_physical").on('click','.scottcart_remCF_product',function() {
		
		var id = jQuery(this).attr('id');
		
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		jQuery( '.scottcart_physical_attribute_assignment option[value="' + id + '"]' ).remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	// remove product digital
	jQuery("#scottcart_products_table_digital").on('click','.scottcart_remCF_product',function() {
		
		var id = jQuery(this).attr('id');
		
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		jQuery( '.scottcart_digital_attribute_assignment option[value="' + id + '"]' ).remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	// remove product service
	jQuery("#scottcart_products_table_service").on('click','.scottcart_remCF_service',function() {
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	// remove physical attribute
	jQuery("#scottcart_products_table_physical_attributes").on('click','.scottcart_remCF_physical_attribute',function() {
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	// remove digital attribute
	jQuery("#scottcart_products_table_digital_attributes").on('click','.scottcart_remCF_digital_attribute',function() {
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	// remove digital attribute
	jQuery("#scottcart_products_table_images").on('click','.scottcart_remCF_image',function() {
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	
	
	
	// onload
	var value = jQuery('#scottcart_type_change').val();
	var inventory = jQuery('#scottcart_inventory_change').val();
	var physical_inventory = jQuery('#scottcart_physical_inventory_change').val();
	var variation = jQuery('#scottcart_variation_change').val();
	var multi = jQuery('#scottcart_multi_change').val();
	
	// digital inventory
	if (inventory == "0") {
		jQuery(".scottcart_digital_inventory").hide();
	}
	
	if (inventory == "1") {
		jQuery(".scottcart_digital_inventory").show();
	}
	
	
	// physical inventory
	if (variation == "0" && physical_inventory == "0") {
		jQuery(".scottcart_physical_inventory").hide();
		jQuery(".scottcart_physical_attribute_inventory").hide();
		jQuery("#scottcart_physical_attributes").hide();
		jQuery("#scottcart_physical_variation_attributes").show();
	}
	
	if (variation == "1" && physical_inventory == "0") {
		jQuery(".scottcart_physical_inventory").hide();
		jQuery(".scottcart_physical_attribute_inventory").hide();
		jQuery("#scottcart_physical_attributes").show();
		jQuery("#scottcart_physical_variation_attributes").hide();
	}
	
	if (variation == "0" && physical_inventory == "1") {
		jQuery(".scottcart_physical_inventory").show();
		jQuery(".scottcart_physical_attribute_inventory").show();
		jQuery("#scottcart_physical_attributes").hide();
		jQuery("#scottcart_physical_variation_attributes").show();
	}
	
	if (variation == "1" && physical_inventory == "1") {
		jQuery(".scottcart_physical_inventory").hide();
		jQuery(".scottcart_physical_attribute_inventory").show();
		jQuery("#scottcart_physical_attributes").show();
		jQuery("#scottcart_physical_variation_attributes").hide();
	}
	
	
	// physical
	if (value == "0") {
		jQuery("#scottcart_physical").show();
		jQuery("#scottcart_digital").hide();
		jQuery("#scottcart_service").hide();
		jQuery("#scottcart_external").hide();
		jQuery("#scottcart_variations").show();
		jQuery("#scottcart_multi").show();
		jQuery("#scottcart_inventory").hide();
		jQuery("#scottcart_physical_inventory").show();
		jQuery("#scottcart_digital_attributes").hide();
		jQuery("#scottcart_service_attributes").hide();
		jQuery("#scottcart_external_attributes").hide();
		jQuery(".scottcart_image_assignment").show();
		jQuery("#scottcart_physical_shipping_type").show();
		jQuery("#scottcart_physical_shipping_type_none").hide();
	}
	
	// digital
	if (value == "1") {
		jQuery("#scottcart_physical").hide();
		jQuery("#scottcart_digital").show();
		jQuery("#scottcart_service").hide();
		jQuery("#scottcart_external").hide();
		jQuery("#scottcart_variations").hide();
		jQuery("#scottcart_multi").show();
		jQuery("#scottcart_inventory").show();
		jQuery("#scottcart_physical_inventory").hide();
		jQuery("#scottcart_digital_attributes").show();
		jQuery("#scottcart_physical_attributes").hide();
		jQuery("#scottcart_service_attributes").hide();
		jQuery("#scottcart_external_attributes").hide();
		jQuery(".scottcart_image_assignment").hide();
		jQuery("#scottcart_physical_variation_attributes").hide();
		jQuery("#scottcart_physical_shipping_type").hide();
		jQuery("#scottcart_physical_shipping_type_none").show();
	}
	
	// service
	if (value == "2") {
		jQuery("#scottcart_physical").hide();
		jQuery("#scottcart_digital").hide();
		jQuery("#scottcart_service").show();
		jQuery("#scottcart_external").hide();
		jQuery("#scottcart_variations").hide();
		jQuery("#scottcart_multi").show();
		jQuery("#scottcart_inventory").hide();
		jQuery("#scottcart_physical_inventory").hide();
		jQuery("#scottcart_digital_attributes").hide();
		jQuery("#scottcart_physical_attributes").hide();
		jQuery("#scottcart_service_attributes").show();
		jQuery("#scottcart_external_attributes").hide();
		jQuery(".scottcart_image_assignment").hide();
		jQuery("#scottcart_physical_variation_attributes").hide();
		jQuery("#scottcart_physical_shipping_type").hide();
		jQuery("#scottcart_physical_shipping_type_none").show();
	}
	
	// external
	if (value == "3") {
		jQuery("#scottcart_physical").hide();
		jQuery("#scottcart_digital").hide();
		jQuery("#scottcart_service").hide();
		jQuery("#scottcart_external").show();
		jQuery("#scottcart_variations").hide();
		jQuery("#scottcart_multi").hide();
		jQuery("#scottcart_inventory").hide();
		jQuery("#scottcart_physical_inventory").hide();
		jQuery("#scottcart_digital_attributes").hide();
		jQuery("#scottcart_physical_attributes").hide();
		jQuery("#scottcart_service_attributes").hide();
		jQuery("#scottcart_external_attributes").show();
		jQuery(".scottcart_image_assignment").hide();
		jQuery("#scottcart_physical_variation_attributes").hide();
		jQuery("#scottcart_physical_multi").hide();
		jQuery("#scottcart_physical_shipping_type").hide();
		jQuery("#scottcart_physical_shipping_type_none").show();
	}
	
	
	
	
	// on change
	
	// digital inventory
	jQuery("#scottcart_inventory_change").change(function () {
		
		if (this.value == "0") {
			jQuery(".scottcart_digital_inventory").hide();
		}
		
		if (this.value == "1") {
			jQuery(".scottcart_digital_inventory").show();
		}
		
	});
	
	
	// physical inventory
	jQuery("#scottcart_physical_inventory_change").change(function () {
		
		var variation = jQuery('#scottcart_variation_change').val();
		
		if (variation == "0" && this.value == "0") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").hide();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_physical_variation_attributes").show();
		}
		
		if (variation == "1" && this.value == "0") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").hide();
			jQuery("#scottcart_physical_attributes").show();
			jQuery("#scottcart_physical_variation_attributes").hide();
		}
		
		if (variation == "0" && this.value == "1") {
			jQuery(".scottcart_physical_inventory").show();
			jQuery(".scottcart_physical_attribute_inventory").show();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_physical_variation_attributes").show();
		}
		
		if (variation == "1" && this.value == "1") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").show();
			jQuery("#scottcart_physical_attributes").show();
			jQuery("#scottcart_physical_variation_attributes").hide();
		}
		
	});
	
	// physical variations
	jQuery("#scottcart_variation_change").change(function () {
		
		var inventory = jQuery('#scottcart_physical_inventory_change').val();
		
		if (this.value == "0" && inventory == "0") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").hide();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_physical_variation_attributes").show();
		}
		
		if (this.value == "1" && inventory == "0") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").hide();
			jQuery("#scottcart_physical_attributes").show();
			jQuery("#scottcart_physical_variation_attributes").hide();
		}
		
		if (this.value == "0" && inventory == "1") {
			jQuery(".scottcart_physical_inventory").show();
			jQuery(".scottcart_physical_attribute_inventory").show();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_physical_variation_attributes").show();
		}
		
		if (this.value == "1" && inventory == "1") {
			jQuery(".scottcart_physical_inventory").hide();
			jQuery(".scottcart_physical_attribute_inventory").show();
			jQuery("#scottcart_physical_attributes").show();
			jQuery("#scottcart_physical_variation_attributes").hide();
		}
	});
	
	
	
	
	
	
	jQuery("#scottcart_type_change").change(function () {
		
		// physical
		if (this.value == "0") {
			jQuery("#scottcart_physical").show();
			jQuery("#scottcart_digital").hide();
			jQuery("#scottcart_service").hide();
			jQuery("#scottcart_external").hide();
			jQuery("#scottcart_variations").show();
			jQuery("#scottcart_multi").show();
			jQuery("#scottcart_inventory").hide();
			jQuery("#scottcart_physical_inventory").show();
			jQuery("#scottcart_shipping").show();
			jQuery("#scottcart_digital_attributes").hide();
			jQuery("#scottcart_service_attributes").hide();
			jQuery("#scottcart_external_attributes").hide();
			jQuery(".scottcart_image_assignment").show();
			jQuery("#scottcart_physical_shipping_type").show();
			jQuery("#scottcart_physical_shipping_type_none").hide();
			
			var value = jQuery('#scottcart_variation_change').val();
			if (value == 0) {
				jQuery("#scottcart_physical_attributes").hide();
				jQuery("#scottcart_physical_variation_attributes").show();
			}
			if (value == 1) {
				jQuery("#scottcart_physical_attributes").show();
				jQuery("#scottcart_physical_variation_attributes").hide();
			}
			
		}
		
		// digital
		if (this.value == "1") {
			jQuery("#scottcart_physical").hide();
			jQuery("#scottcart_digital").show();
			jQuery("#scottcart_service").hide();
			jQuery("#scottcart_external").hide();
			jQuery("#scottcart_variations").hide();
			jQuery("#scottcart_multi").show();
			jQuery("#scottcart_inventory").show();
			jQuery("#scottcart_physical_inventory").hide();
			jQuery("#scottcart_shipping").show();
			jQuery("#scottcart_digital_attributes").show();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_service_attributes").hide();
			jQuery("#scottcart_external_attributes").hide();
			jQuery(".scottcart_image_assignment").hide();
			jQuery("#scottcart_physical_variation_attributes").hide();
			jQuery("#scottcart_physical_shipping_type").hide();
			jQuery("#scottcart_physical_shipping_type_none").show();
		}
		
		// service
		if (this.value == "2") {
			jQuery("#scottcart_physical").hide();
			jQuery("#scottcart_digital").hide();
			jQuery("#scottcart_service").show();
			jQuery("#scottcart_external").hide();
			jQuery("#scottcart_variations").hide();
			jQuery("#scottcart_multi").show();
			jQuery("#scottcart_inventory").hide();
			jQuery("#scottcart_physical_inventory").hide();
			jQuery("#scottcart_shipping").show();
			jQuery("#scottcart_digital_attributes").hide();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_service_attributes").show();
			jQuery("#scottcart_external_attributes").hide();
			jQuery(".scottcart_image_assignment").hide();
			jQuery("#scottcart_physical_variation_attributes").hide();
			jQuery("#scottcart_physical_shipping_type").hide();
			jQuery("#scottcart_physical_shipping_type_none").show();
		}
		
		// external
		if (this.value == "3") {
			jQuery("#scottcart_physical").hide();
			jQuery("#scottcart_digital").hide();
			jQuery("#scottcart_service").hide();
			jQuery("#scottcart_external").show();
			jQuery("#scottcart_variations").hide();
			jQuery("#scottcart_multi").hide();
			jQuery("#scottcart_inventory").hide();
			jQuery("#scottcart_physical_inventory").hide();
			jQuery("#scottcart_shipping").hide();
			jQuery("#scottcart_digital_attributes").hide();
			jQuery("#scottcart_physical_attributes").hide();
			jQuery("#scottcart_service_attributes").hide();
			jQuery("#scottcart_external_attributes").show();
			jQuery(".scottcart_image_assignment").hide();
			jQuery("#scottcart_physical_variation_attributes").hide();
			jQuery("#scottcart_physical_multi").hide();
			jQuery("#scottcart_physical_shipping_type").hide();
			jQuery("#scottcart_physical_shipping_type_none").show();
		}
		
	});
	
});