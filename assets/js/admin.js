jQuery(document).ready(function($) {

	
	// Tooltips
	jQuery('.scottcart-help-tip').tooltip( {
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
	
	
	// load function
	jQuery("#scottcart_load_function").click(function(){
		
		var className = jQuery("#scottcart_load_function").attr('class');
		
		var data = {
			'action': 'scottcart_load_function',
			'function': jQuery(this).data('placeholder')
		};
		
		jQuery.post( ajaxurl, data, function (response) {
			jQuery('.T'+className).text(response);
			jQuery('.T'+className).show();
		});
		
	});
	
	
	
});


// Deactive survey form
(function($) {
	$(function() {

	var pluginSlug = 'scottcart';

	$(document).on('click', 'tr[data-slug="' + pluginSlug + '"] .deactivate', function(e) {
		e.preventDefault();
		$('.scottcart-popup-overlay').addClass('scottcart-active');
		$('body').addClass('scottcart-hidden');
	});
	
	$(document).on('click', '.scottcart-popup-button-close', function () {
		close_popup();
	});
	
	$(document).on('click', ".scottcart-serveypanel,tr[data-slug='" + pluginSlug + "'] .deactivate",function(e) {
		e.stopPropagation();
	});

	$(document).click(function() {
		close_popup();
	});
	
	$('.scottcart-reason label').on('click', function() {
		if($(this).find('input[type="radio"]').is(':checked')) {
			$(this).next().next('.scottcart-reason-input').show().end().end().parent().siblings().find('.scottcart-reason-input').hide();
		}
	});
	
	$('input[type="radio"][name="scottcart-selected-reason"]').on('click', function(event) {
		$(".scottcart-popup-allow-deactivate").removeAttr('disabled');
		$('.scottcart_input_field_error').removeClass('scottcart_input_error');
	});
	
	$(document).on('submit', '#scottcart-deactivate-form', function(event) {
		event.preventDefault();
		
		var _reason =  $(this).find('input[type="radio"][name="scottcart-selected-reason"]:checked').val();
		var _reason_details = '';
		
		if ( _reason == 2 ) {
			_reason_details = $(this).find("textarea[name='better_plugin']").val();
		} else if ( _reason == 7 ) {
			_reason_details = $(this).find("textarea[name='other_reason']").val();
		} else if ( _reason == 1 ) {
			_reason_details = $(this).find("textarea[name='feature']").val();
		}
		
		if ( ( _reason == 7 || _reason == 2 || _reason == 1 ) && _reason_details == '' ) {
			$('.scottcart_input_field_error').addClass('scottcart_input_error');
			return ;
		}
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action        : 'scottcart_deactivate_survey',
				reason        : _reason,
				reason_detail : _reason_details,
			},
			beforeSend: function(){
				$(".scottcart-spinner").show();
				$(".scottcart-popup-allow-deactivate").attr("disabled", "disabled");
			}
		})
		.done(function() {
			$(".scottcart-spinner").hide();
			$(".scottcart-popup-allow-deactivate").removeAttr("disabled");
			window.location.href =  $("tr[data-slug='"+ pluginSlug +"'] .deactivate a").attr('href');
		});
	});

	$('.loginpress-popup-skip-feedback').on('click', function(e) {
		window.location.href =  $("tr[data-slug='"+ pluginSlug +"'] .deactivate a").attr('href');
	})

	function close_popup() {
		$('.scottcart-popup-overlay').removeClass('scottcart-active');
		$('#scottcart-deactivate-form').trigger("reset");
		$(".scottcart-popup-allow-deactivate").attr('disabled', 'disabled');
		$(".scottcart-reason-input").hide();
		$('body').removeClass('scottcart-hidden');
		$('.scottcart_input_field_error').removeClass('scottcart_input_error');
	}
	});
})(jQuery);