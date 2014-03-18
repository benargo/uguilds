/**
 * Filter function
 *
 * This function runs whenever one of the form elements changes
 * and a filtration needs to happen
 */
function filter(options)
{
	// Variables
	var roster = JSON.parse(window.localStorage.roster);
	var matches = []; // Characters to remove

	roster.members.filter(function(element)
	{
		// Name
		if(options.name !== undefined)
		{
			// If the character name matches the filter option
			if(element.name.toLowerCase().search(options.name.toLowerCase()) !== -1)
			{
				// Add the character to the matches
				console.log(element.name +' will be shown because its name matches '+ options.name);
				matches.push(element.name);
			}

		}
		
		// Race
		if(options.race !== 'all' && options.race !== undefined)
		{
			// If the character race matches the filter option
			if(element.race === options.race)
			{
				// Add the character to the matches
				console.log(element.name +' will be shown because its race matches '+ options.race);
				matches.push(element.name);
			}
		}

		// Class
		if(options.class !== 'all' && options.class !== undefined)
		{
			// If the character's class matches the filter option
			if(element.class === options.class)
			{
				// Add the character to the matches
				console.log(element.name +' will be shown because its class matches '+ options.class);
				matches.push(element.name);
			}

		}

		// Level range
		if(options.minLevel !== undefined && options.maxLevel !== undefined)
		{
			// If the character's level is MORE than the minimum level
			// OR the character's level is LESS than the maximum level
			if(element.level > parseInt(options.minLevel, 10) ||
				element.level < parseInt(options.maxLevel, 10))
			{
				// Add the character to the matches
				console.log(element.name +' will be shown because it\'s in the level range of '+ options.minLevel +' - '+ options.maxLevel);
				matches.push(element.name);
			}
		}

		// Guild rank
		if(options.rank !== undefined && options.rank !== 'all')
		{
			// If the character's rank matches
			if(element.rank === options.rank)
			{
				// Add the character to the matches.
				console.log(element.name +' will be shown because its guild rank matches '+ options.rank);
				matches.push(element.name);
			}
		}

	});

	// Loop through the list of members
	$.each(roster.members, function(index,element)
	{
		// If the character is in the list of matches
		if($.inArray(element.name, matches) !== -1 && $('tr.character.'+ element.name).hasClass('hidden'))
		{
			// Show this character
			console.log('Showing '+ element.name);
			$('tr.character.'+ element.name).removeClass('hidden').fadeIn('medium');
		}

		// If the character is NOT in the list of matches
		if($.inArray(element.name, matches) === -1 && !$('tr.character.'+ element.name).hasClass('hidden'))
		{
			// Hide the character
			console.log('Hiding '+ element.name);
			$('tr.character.'+ element.name).addClass('hidden').fadeOut('medium');
		}
	});

	// Loop through each of the links and change the href attributes
	$('td.race a, td.class a').each(function()
	{
		var ending = $(this).attr('href').match(/\/[\w\-]+\=[\w\-]+$/);
		$(this).attr('href', path + ending);
	});

	$(".guild-roster").trigger("update");
}

