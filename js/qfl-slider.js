jQuery(document).ready(function($) {
	$('.slider-wrap').find('.head').click(function() {

		// console.log($(this).parent().find('.content'));
		var content = $(this).parent().find('.content');
		console.log(content.css('display'));
		if (content.css('display') === 'none') {
			content.css('display', 'block');
		} else if (content.css('display') === 'block') {
			content.css('display', 'none');
		}
		// $(this).parent().find('.content').toggle();
	})
})