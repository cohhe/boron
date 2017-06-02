// Admin Javascript
jQuery( document ).ready( function( $ ) {

	// Choose layout
	$("#vh_layouts img").click(function() {
		$(this).parent().parent().find(".selected").removeClass("selected");
		$(this).addClass("selected");
	});

	$('.rpp_show-expert-options').live('change', function(){
		if( $(this).is(':checked') ) {
			$(this).parent().parent().find('.rpp_expert-panel').show();
		} else {
			$(this).parent().parent().find('.rpp_expert-panel').hide();
		}
	});

	jQuery(document).on('click', '.boron-rating-dismiss', function() {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { 
				'action': 'boron_dismiss_notice'
			},
			success: function(data) {
				jQuery('.boron-rating-notice').remove();
			}
		});
	});
});