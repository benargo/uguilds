// Variables
var options, path, roster;

// On load stuff
$(function()
{
	// Remove the input's which have the .nojs class
	$('input.nojs').remove();

	// Run the AJAX request
	$.ajax({
		async: false,
		cache: true,
		ifModified: true,
		type: 'GET',
		url: '/ajax/roster/all',
		success: function (data, textStatus, jqXHR) {

			// If we received an HTTP 200 OK status code
			if(jqXHR.status == 200)
			{
				console.log('Updated the local copy of the roster');
				
				// Update the local storage copy of the roster
				window.localStorage.setItem('roster', JSON.stringify(data));

				$(".guild-roster").trigger("update");
			}
		}
	});

	// Declare options as an empty array
	options = [];

	// Set a default path
	path = '/roster';

	// Parse the roster
	var roster = JSON.parse(window.localStorage.getItem('roster'));

	// On Resize Events
	$(window).on('resize', function()
	{
		if($(window).width() <= '640')
		{
			$('th.guild-rank').html('Rank');
			$('th.achievements').html('Achiev\'s');
		}
		else
		{
			$('th.guild-rank').html('Guild Rank');
			$('th.achievements').html('Achievement Points');
		}
	});

	// Get the current URL
	var url = window.location.pathname;
	if(url.length >= 8)
	{
		path = url;
		var segments = url.substr(8).split('/');

		segments.forEach(function(element)
		{
			var param = element.split('=');
			if(param[0] === 'race' || param[0] === 'class' || param[0] === 'rank')
			{
				$('select[name="'+ param[0] +'"] option').each(function(){
					if($(this).text().replace(' ','-').toLowerCase() === param[1])
					{
						param[1] = parseInt($(this).val());
					}
				});
			}
			options[param[0]] = param[1];
			$('input[name$="'+ param[0] +'"], select[name$="'+ param[0] +'"]').val(param[1]).trigger('change');
		});
		
		filter(options);
	}

	// Table Sorter
	$.tablesorter.addParser({

		// set a unique id 
		id: 'ranks',
		is: function()
		{
			// return false so this parser is not auto detected 
			return false;
		},
		format: function(s)
		{
			// format your data for normalization 
			$.each(roster.ranks, function(element,index)
			{
				var regex = element + '\\n';
				regex = new RegExp(regex);
				s = s.replace(regex, index);
			});
			
			return s;
		},
		// set type, either numeric or text 
		type: 'numeric'
	});

	/**
	 * jQuery Table Sorter
	 *
	 * Configuration options
	 */
	$(".guild-roster").tablesorter({
		headers: {
			1: {
				sorter:false
			},
			2: {
				sorter:false
			},
			4: {
				sorter:'ranks'
			}
		}
	});

	/**
	 * Roster Filtration
	 *
	 * Bind form elements
	 */

	// Character Name
	$('input[name="characterName"]').bind("keyup change", function()
	{
		options.name = $(this).val();
		filter(options);
	});

	// Race
	$('select[name="race"]').change(function()
	{
		options.race = $(this).val();

		path = path.replace(/\/race=[\w\-]+/,'');

		if(options.race !== 'all')
		{
			path = path+'/race='+roster.races[options.race].name.replace(' ','-').toLowerCase();
		}

		history.pushState(null,null,path);

		filter(options);
	});

	// Class
	$('select[name="class"]').change(function()
	{
		options['class'] = $(this).val();

		path = path.replace(/\/class=[\w\-]+/,'');

		if(options['class'] !== 'all')
		{
			path = path+'/class='+roster.classes[options.class].name.replace(' ','-').toLowerCase();
		}

		history.pushState(null,null,path);

		filter(options);
	});

	// Level
	$('input[name$="level"]').bind("keyup change", function()
	{
		var input = $(this).attr('name');
		options[input] = $(this).val();

		path = path.replace(/\/level=[0-9]+[\-0-9]*/,'');

		if(options.minlevel === undefined)
		{
			options.minlevel = $('input[name="minLevel"]').attr('min');
		}
		if(options.maxlevel === undefined)
		{
			options.maxlevel = $('input[name="maxLevel"]').attr('max');
		}

		path = path+'/level='+ options.minlevel +'-'+ options.maxlevel;
		history.pushState(null,null,path);

		filter(options);
	});

	// Rank
	$('select[name="rank"]').change(function()
	{
		options.rank = $(this).val();

		path = path.replace(/\/rank=[\w\-]+/,'');

		if(options.rank !== 'all')
		{
			path = path +'/rank='+ roster.ranks[options.rank].toString().replace(' ','-').toLowerCase();
		}

		history.pushState(null,null,path);

		filter(options, event);
	});

	// Reset button
	$('input[type="reset"]').click(function()
	{
		options = [];

		path = '/roster';
		history.pushState(null,null,path);

		filter(options);
	});
});

