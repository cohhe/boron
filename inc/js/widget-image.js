var image_field;
jQuery(function($){
	$(document).on('click', 'input.select-img', function(evt){
		image_field = $(this).siblings('.img');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	$(document).on('click', 'input.remove-img', function(evt){
		image_field = $(this).siblings('.img');
		image_src = $(this).siblings('.author-img');

		image_field.val('');
		image_src.attr('src', '');
		
	});
	window.send_to_editor = function(html) {
		imgurl = $('img', html).attr('src');
		image_field.val(imgurl);
		$('.author-img').attr('src', imgurl);
		tb_remove();
	}
});