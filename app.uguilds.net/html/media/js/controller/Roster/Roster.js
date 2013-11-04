$(function() {
	$(window).on('resize', function() {
		if($(window).width() <= '640')
		{
			$('th.guild-rank').html('Rank');
			$('th.achievements').html('Achiev\'s');
		} else
		{
			$('th.guild-rank').html('Guild Rank');
			$('th.achievements').html('Achievement Points');
		}
	});

	try {
		$("select.race").msDropDown();
	} catch(e) {
		alert(e.message);
	}
});