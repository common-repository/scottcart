jQuery(document).ready(function($) {
	
	// tax section ********************
	
	// add new tax rate
	jQuery(".scottcart_addCF_tax_rate").click(function() {
		counter = document.getElementById("scottcart_tax_table").rows.length;
		
		var entire_country_id = counter;
		entire_country_id--;
		
		var data = {
			'action': 'scottcart_get_country_list_tax'
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_tax_table").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><div class='scottcart_tax_country_list_div'>"+response+"</div></td><td class='scottcart_tax_entire'><input type='checkbox' class='scottcart_tax_entire' name='scottcart_settings[tax_entire]["+entire_country_id+"]' value='"+entire_country_id+"'></td><td><div class='scottcart_tax_state_list_div'><input type='text' class='scottcart_tax_state' name='scottcart_settings[tax_state][]' value=''></div></td><td class='scottcart_tax_rate'><input type='text' size='5' name='scottcart_settings[tax_rate][]' value=''></td><td class='scottcart_tax_shipping'><input type='checkbox' name='scottcart_settings[tax_shipping]["+entire_country_id+"]' value='"+entire_country_id+"'></td><td><a href='javascript:void(0);' class='scottcart_remCF_tax_rate' id='"+counter+"'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		});
		
		// get current count
		var tax_count = jQuery('#tax_count').val();
		
		tax_count++;
		
		// set new count
		jQuery('#tax_count').val(tax_count);
		
	});
	
	// remove tax rate
	jQuery("#scottcart_tax_table").on('click','.scottcart_remCF_tax_rate',function() {
		
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		// get current count
		var tax_count = jQuery('#tax_count').val();
		
		tax_count--;
		
		// set new count
		jQuery('#tax_count').val(tax_count);
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
		
	});


	// change country, load state / province list
	//jQuery(".tax_country").change(function () {
	jQuery("#scottcart_tax_table").on('change','.scottcart_tax_country',function() {
		
		var country_id = jQuery(this).val();
		var table = jQuery(this).closest("tr");
		
		var data = {
			'action': 'scottcart_get_state_list_tax',
			'id': country_id,
			'i': table.find('.scottcart_row_id').val()
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			table.find(".scottcart_tax_state_list_div").html(response);
			
			if (table.find(".scottcart_tax_entire").is(':checked')) {
				table.find(".scottcart_tax_state").hide();
			}
			
		});
		
	});
	
	
	// hide / show state / province column if entire country is checked
	jQuery("#scottcart_tax_table").on('click','.scottcart_tax_entire',function() {
		
		var table = jQuery(this).closest("tr");
		
		table.find(".scottcart_tax_state").toggle(this.checked);
		
	});
	
	// load state / province column on load
	jQuery('.scottcart_tax_country').each(function() {
		
		var country_id = jQuery(this).val();
		var table = jQuery(this).closest("tr");
		
		var data = {
			'action': 'scottcart_get_state_list_tax',
			'id': country_id,
			'i': table.find('.scottcart_row_id').val()
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			table.find(".scottcart_tax_state_list_div").html(response);
			
			if (table.find(".scottcart_tax_entire").is(':checked')) {
				table.find(".scottcart_tax_state").hide();
			}
			
		});
		
		
	});
	
	// hide state / province column on load
	jQuery('.scottcart_tax_entire').each(function() {
		
		var table = jQuery(this).closest("tr");
		
		if( jQuery(this).is(':checked')) {
			table.find(".scottcart_tax_state").hide();
		}
		
	});
	
	
	
	
	// shipping section ********************
	
	// add new shipping location
	jQuery(".scottcart_addCF_shipping_location").click(function() {
	
		counter = document.getElementById("scottcart_shipping_table_main").rows.length;
		
		counter = Math.ceil(counter / 2);
		
		var entire_country_id = counter;
		entire_country_id--;
		
		var data = {
			'action': 'scottcart_get_country_list_shipping'
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_shipping_table_main").append("<tr valign='top'><td class='row-ida'>"+counter+"</td><td><div class='scottcart_shipping_country_list_div'>"+response+"</div></td><td class='scottcart_shipping_entire'><input type='checkbox' class='scottcart_shipping_entire scottcart_shipping_entire' name='scottcart_settings[shipping_entire]["+entire_country_id+"]' value='"+entire_country_id+"'></td><td><div class='scottcart_shipping_state_list_div'><input type='text' class='scottcart_shipping_state' name='scottcart_settings[shipping_state][]' value=''></div></td><td><a href='javascript:void(0);' class='scottcart_remCF_shipping_location' id='"+counter+"'><span class='dashicons dashicons-trash'></span><input type='hidden' name='scottcart_settings[shipping_count"+counter+"]' id='shipping_count"+counter+"' value=''></a></td></tr><tr class='scottcart_shipping_location_row'><td></td><td colspan='3'><table id='scottcart_shipping_table"+counter+"' class='scottcart_shipping_table scottcart_shipping_table_rate' width='100%'><tr><td width='15px'></td><td width='150px'>Type</td><td width='150px'>Rate</td><td width='100px'>Rate For Each Additional Item</td></tr></table><table width='100%' class='scottcart_shipping_table_rate scottcart_shipping_table_rate_row'><tr><td width='15px'></td><td><a href='javascript:void(0);' class='scottcart_addCF_shipping_rate' data-id='"+counter+"'>Add New Shipping Rate</a></td></tr></table>");
		});
		
		// get current count
		var shipping_count = jQuery('#shipping_count').val();
		
		shipping_count++;
		
		// set new count
		jQuery('#shipping_count').val(shipping_count);
		
	});
	
	// remove shipping location
	jQuery("#scottcart_shipping_table_main").on('click','.scottcart_remCF_shipping_location',function() {
		
		$(this).closest('tr').next().remove();
		$(this).closest('tr').remove();
		
		// get current count
		var shipping_count = jQuery('#shipping_count').val();
		
		shipping_count--;
		
		// set new count
		jQuery('#shipping_count').val(shipping_count);
		
		jQuery('.row-ida').each(function(i) {
			jQuery(this).text(i+1);
		});
		
		
	});
	
	// hide state / province column onload
	jQuery('.scottcart_shipping_entire').each(function() {
		
		var table = jQuery(this).closest("tr");
		
		if( jQuery(this).is(':checked')) {
			table.find(".scottcart_shipping_state").hide();
		}
		
	});
	
	
	
	
	
	
	
	// add new shipping rate
	//jQuery(".scottcart_addCF_shipping_rate").click(function() {
	jQuery("#scottcart_shipping_table_main").on('click','.scottcart_addCF_shipping_rate',function() {
		
		var counter = jQuery(this).attr("data-id");
		
		var data = {
			'action': 'scottcart_get_shipping_types',
			'counter': counter
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_shipping_table"+counter).append("<tr valign='top'><td width='15px'></td><td width='150px'><div>"+response+"</div></td><td width='150px'><input type='text' class='scottcart_input' name='scottcart_settings[shipping_rate]["+counter+"][]' value=''></td><td width='150px'><input type='text' class='scottcart_input' name='scottcart_settings[shipping_rate_additional]["+counter+"][]' value=''></td><td width='150px'><a href='javascript:void(0);' class='scottcart_remCF_shipping_rate' id='"+counter+"'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		});
		
		// get current count
		var shipping_count = jQuery('#shipping_count'+counter).val();
		
		shipping_count++;
		
		// set new count
		jQuery('#shipping_count'+counter).val(shipping_count);
		
	});
	
	// remove shipping rate
	jQuery("#scottcart_shipping_table_main").on('click','.scottcart_remCF_shipping_rate',function() {
		
		var counter = jQuery(this).attr("data-id");
		
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		// get current count
		var shipping_count = jQuery('#shipping_count'+counter).val();
		
		shipping_count--;
		
		// set new count
		jQuery('#shipping_count'+counter).val(shipping_count);
		
	});
	
	
	
	
	
	
	
	
	
	
	
	


	// change country, load state / province list
	jQuery("#scottcart_shipping_table_main").on('change','.scottcart_shipping_country',function() {
		
		if (jQuery(this).val() != "worldwide") {
		
			var country_id = jQuery(this).val();
			var table = jQuery(this).closest("tr");
			
			var data = {
				'action': 'scottcart_get_state_list_shipping',
				'id': country_id,
				'i': table.find('.scottcart_row_id').val()
			};
			
			jQuery.post( ajaxurl, data, function (response) {
				table.find(".scottcart_shipping_state_list_div").html(response);
				
				if (table.find(".scottcart_shipping_entire").is(':checked')) {
					table.find(".scottcart_shipping_state").hide();
				}
				
			});
			
		}
		
	});
	
	
	// hide / show state / province column if entire country is checked
	jQuery("#scottcart_shipping_table_main").on('click','.scottcart_shipping_entire',function() {
		
		var table = jQuery(this).closest("tr");
		
		table.find(".scottcart_shipping_state").toggle(this.checked);
		
	});
	
	
	
	
	
	// if country is set to worldwide hide checkbox for entire and state / province dropdown
	jQuery("#scottcart_shipping_table_main").on('change','.scottcart_shipping_country',function() {
		
		var table = jQuery(this).closest("tr");
		
		if (jQuery(this).val() == "worldwide") {
		
		table.find(".scottcart_shipping_state").hide();
		table.find(".scottcart_shipping_entire").hide();
		
		} else {
		
		table.find(".scottcart_shipping_state").show();
		table.find(".scottcart_shipping_entire").show();
		
		}
		
	});
	
	
	
	
	
	
	
	// load state / province column on load
	jQuery('.scottcart_shipping_country').each(function() {
		
		var country_id = jQuery(this).val();
		var table = jQuery(this).closest("tr");
		
		if (jQuery(this).val() != "worldwide") {
			
			var data = {
				'action': 'scottcart_get_state_list_shipping',
				'id': country_id,
				'i': table.find('.scottcart_row_id').val()
			};
			
			jQuery.post( ajaxurl, data, function (response) {
				table.find(".scottcart_shipping_state_list_div").html(response);
				
				if (table.find(".scottcart_shipping_entire").is(':checked')) {
					table.find(".scottcart_shipping_state").hide();
				}
				
			});
			
		}
	});
	
	
	// if country is set to worldwide hide checkbox for entire and state / province dropdown onload
	//jQuery("#scottcart_shipping_table_main").on('change','.scottcart_shipping_country',function() {
	jQuery('.scottcart_shipping_country').each(function() {
		
		var table = jQuery(this).closest("tr");
		
		if (jQuery(this).val() == "worldwide") {
		
		table.find(".scottcart_shipping_state").hide();
		table.find(".scottcart_shipping_entire").hide();
		
		} else {
		
		table.find(".scottcart_shipping_state").show();
		table.find(".scottcart_shipping_entire").show();
		
		}
		
	});
	
	
	// shipping types section ********************
	
	
	// add new shipping type
	jQuery(".scottcart_addCF_shipping_type").click(function() {
		
		var counter = document.getElementById("scottcart_shipping_types_table").rows.length;
		
		jQuery("#scottcart_shipping_types_table").append("<tr valign='top'><td class='row-id'>"+counter+"</td><td><input type='text' class='scottcart_input scottcart_shipping_types_name' name='scottcart_settings[shipping_types_name][]' value=''></div></td><td><input type='text' class='scottcart_input scottcart_shipping_types_desc' name='scottcart_settings[shipping_types_desc][]' value=''></div></td><td><a href='javascript:void(0);' class='scottcart_remCF_shipping_type' id='"+counter+"'><span class='dashicons dashicons-trash'></span></a></td></tr>");
		
		
		// get current count
		var shipping_count = jQuery('#shipping_types_count').val();
		
		shipping_count++;
		
		// set new count
		jQuery('#shipping_types_count').val(shipping_count);
		
		
	});
	
	
	// remove shipping type
	jQuery("#scottcart_shipping_types_table").on('click','.scottcart_remCF_shipping_type',function() {
		
		var delete_msg = jQuery('#scottcart_confirm_delete_msg').val();
		
		if (confirm(delete_msg)) {
		
			var $row = $(this).closest('tr'),
				$table = $row.closest('table');
			$row.remove();
			
			// get current count
			var shipping_count = jQuery('#shipping_types_count').val();
			
			shipping_count--;
			
			// set new count
			jQuery('#shipping_types_count').val(shipping_count);
			
			$table.find('tr').each(function(i,v) {
				jQuery(v).find('.row-id').text(i);
			});
		
		}
		
	});
	
	
	jQuery(function () {
		jQuery('.scottcart_colorpicker').wpColorPicker();
	});
	
	
	
	// change base country - load state / province list
	jQuery(".scottcart_base_country").click(function() {
		
		var country_id = 	jQuery(this).val();
		
		if (country_id != "0" && country_id != "" && country_id != undefined) {
			
			var data = {
				'action': 			'scottcart_settings_get_state_list',
				'country_id': 		country_id,
			};
			
			jQuery.post( ajaxurl, data, function (response) {
				jQuery(".scottcart_base_state").html(response);
			});
			
		}
	});
	
	// onload base country - load state / province list
	var country_id = jQuery(".scottcart_base_country").val();
	
	if (country_id != "0" && country_id != "" && country_id != undefined) {
		
		var data = {
			'action': 			'scottcart_settings_get_state_list',
			'country_id': 		country_id,
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery(".scottcart_base_state").html(response);
		});
		
	};
	
});