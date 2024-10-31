// orders page add products


// load product list - new orders
function load_products_new(input) {
	var data = {
		'action': 'scottcart_get_products_name',
		'order_id': jQuery('#scottcart_order_id').val()
	};
	
	jQuery.post( ajaxurl, data, function (response) {
		jQuery('.product-id').html(response);
	});
}


jQuery(document).ready(function($) {

   jQuery(function() {
    jQuery(".scottcart-datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
   });
	
	
	
	// add new product - for new
	jQuery(".scottcart_addCF").click(function(){
		
		var data = {
			'action': 'scottcart_get_products_name',
			'order_id': jQuery('#scottcart_order_id').val()
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			counter = document.getElementById("customFields").rows.length;
			jQuery("#customFields").append('<tr valign="top"><td class="row-id">'+counter+'</td><td class="product-id">'+response+'</td><td class="variation-id"></td><td class="attribute-id"></td><td class="quantity-id"></td><td class="price-id"></td><td><a href="javascript:void(0);" class="scottcart_remCF"><span class="dashicons dashicons-trash"></span></a></td></tr>');
		});
	});
	
	// remove product
	jQuery("#customFields").on('click','.scottcart_remCF',function() {
		var $row = $(this).closest('tr'),
			$table = $row.closest('table');
		$row.remove();
		
		$table.find('tr').each(function(i,v) {
			jQuery(v).find('.row-id').text(i);
		});
	});
	
	
	// change product new
	jQuery("#customFields").on('change','.product',function() {
		
		var data = {
			'action': 'scottcart_get_variations',
			'id': jQuery(this).closest("tr").find(".product").val()
		};
		
		var table = jQuery(this).closest("tr");
		
		jQuery.post( ajaxurl, data, function (response) {
			result = response.split('|*');
			table.find(".variation-id").html(result[0]);
			table.find(".price-id").html(result[1]);
			table.find(".attribute-id").html(result[2]);
			table.find(".quantity-id").html(result[3]);
		});
	});
	
	
	
	// change product existing
	jQuery("#customFields").on('click','.scottcart_load_variations',function() {
		
		var data = {
			'action': 'scottcart_get_variations',
			'id': jQuery(this).closest("tr").find(".product").val(),
			'vid': jQuery(this).closest("tr").find(".variation_id").val(),
			'cart_id': jQuery(this).closest("tr").find(".cart_id").val(),
			'order_id': jQuery(this).closest("tr").find(".order_id").val(),
			'attribute_id': jQuery(this).closest("tr").find(".attribute_id").val()
		};
		
		var table = jQuery(this).closest("tr");
		
		jQuery.post( ajaxurl, data, function (response) {
			result = response.split('|*');
			table.find(".variation-id").html(result[0]);
			table.find(".price-id").html(result[1]);
			table.find(".attribute-id").html(result[2]);
			table.find(".quantity-id").html(result[3]);
			
		});
		
	});
	
	
	
	
	// change variation
	jQuery("#customFields").on('change','.variation',function() {
		
		var data = {
			'action': 'scottcart_get_variations_price',
			'id': jQuery(this).closest("tr").find(".variation").val()
		};
		
		var table = jQuery(this).closest("tr");
		
		jQuery.post( ajaxurl, data, function (response) {
			result = response.split('|*');
			table.find(".attribute-id").html(result[0]);
			table.find(".price-id").html(result[1]);
		});
	});
	
	
	
	// resend order customer email
	jQuery("#scottcart_resend_customer_email").click(function() {
		
		var data = {
			'action': 'scottcart_resend_customer_email',
			'id': jQuery('#scottcart_customer_order_id').val()
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery("#scottcart_customer_email_status").html(response);
		});
	});



	
	
	
	
	// Tooltips
	jQuery('.scottcart-help-tip').tooltip({
		content: function() {
			return jQuery(this).prop('title');
		},
		tooltipClass: 'scottcart-ui-tooltip',
		position: {
			my: 'center top',
			at: 'center bottom+10',
			collision: 'flipfit',
		},
		hide: {
			duration: 200,
		},
		show: {
			duration: 200,
		},
	});
	
	

});



