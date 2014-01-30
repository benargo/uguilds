$(function() {
	$('a.spec.primary').click(function()
	{
		if($(this).hasClass('passive'))
		{
			$(this).addClass('active').removeClass('passive');
			$('a.spec.secondary').addClass('passive').removeClass('active')
		}

		$('.talents.primary, .glyphs.primary').show();
		$('.talents.secondary, .glyphs.secondary').hide();
	});

	$('a.spec.secondary').click(function()
	{
		if($(this).hasClass('passive'))
		{
			$(this).addClass('active').removeClass('passive');
			$('a.spec.primary').addClass('passive').removeClass('active')
		}

		$('.talents.primary, .glyphs.primary').hide();
		$('.talents.secondary, .glyphs.secondary').show();
	});
});