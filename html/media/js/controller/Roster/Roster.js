$.ajax({
    url: '/ajax/roster/all',
    type: 'GET',
    dataType: 'json',
    ifModified: true
}).done(function(data) {
    window.localStorage.setItem('roster', JSON.stringify(data));
});

function filter(options, event)
{
    var key = event.keyCode;
    var roster = JSON.parse(window.localStorage['roster']);
    var matches = Array(); // Characters to remove

    roster.members.filter(function(element){
        if(options['name'] !== undefined)
        {
            // If the character name matches the filter option
            if(element.character.name.toLowerCase().search(options['name'].toLowerCase()) == -1)
            {
                // Add the character to the matches
                console.log('Added '+ element.character.name +' because it\'s name matches');
                matches.push(element.character.name);
            }

        }
        
        if(options['race'] != 'all' && options['race'] !== undefined)
        {
            // If the character race matches the filter option
            if(element.character.race != options['race'])
            {
                // Add the character to the matches
                console.log('Added '+ element.character.name +' because its race matches');
                matches.push(element.character.name);
            }
        }

        if(options['class'] != 'all' && options['class'] !== undefined)
        {
            // If the character's class matches the filter option
            if(element.character.class != options['class'])
            {
                // Add the character to the matches
                console.log('Added '+ element.character.name +' because its class matches');
                matches.push(element.character.name);
            }

        }

        if(options['minLevel'] !== undefined && options['maxLevel'] !== undefined)
        {
            // If the character's level is LESS than the minimum level
            // OR the character's level is MORE than the maximum level
            if(element.character.level < parseInt(options['minLevel'], 10) ||
                element.character.level > parseInt(options['maxLevel'], 10))
            {
                // Add the character to the matches
                console.log('Added '+ element.character.name +' because it\'s NOT in the level range');
                matches.push(element.character.name);
            }
        }

        if(options['rank'] !== undefined && options['rank'] != 'all')
        {
            // If the character's rank DOES NOT matches
            if(element.rank != options['rank'])
            {
                // Add the character to the matches.
                console.log('Added '+ element.character.name +' because it\'s NOT the correct rank');
                matches.push(element.character.name);
            }
        }

    });

    $.each(roster.members, function(index,element){
        // If the character does NOT match the filters
        if($.inArray(element.character.name, matches) == -1)
        {
            // Show this character
            $('tr.character.'+element.character.name.toLowerCase()).removeClass('hidden').fadeIn('medium');
        }
        // If the character DOES match the filters
        if($.inArray(element.character.name, matches) != -1)
        {
            // Hide the character
            $('tr.character.'+element.character.name.toLowerCase()).addClass('hidden').fadeOut('medium');
        }
    });
}

$(function() {
    // On Resize Events
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

    $('input.nojs').remove();

    var options = Array();
    var path = '/roster';

    $('input[name="characterName"]').bind("keyup change", function(event) {
        options['name'] = $(this).val();
        filter(options, event);
    });

    $('select[name="race"]').change(function(event) {
        options['race'] = $(this).val();

        path = path.replace(/\/race=[\w\-]+/,'');

        if(options['race'] != 'all')
        {
            var roster = JSON.parse(window.localStorage['roster']);
            path = path+'/race='+roster.races[options['race']].name.replace(' ','-').toLowerCase();
        }
        history.pushState(null,null,path);

        filter(options, event);
    });

    $('select[name="class"]').change(function(event){
        options['class'] = $(this).val();

        path = path.replace(/\/class=[\w\-]+/,'');

        if(options['class'] != 'all')
        {
            var roster = JSON.parse(window.localStorage['roster']);
            path = path+'/class='+roster.classes[options['class']].name.replace(' ','-').toLowerCase();
        }

        history.pushState(null,null,path);

        filter(options, event);
    });

    $('input[name$="Level"]').bind("keyup change", function(event){
        input = $(this).attr('name');
        options[input] = $(this).val();

        path = path.replace(/\/level=[0-9]+[\-0-9]*/,'');

        if(options['minLevel'] === undefined)
        {
            options['minLevel'] = $('input[name="minLevel"]').attr('min');
        }
        if(options['maxLevel'] === undefined)
        {
            options['maxLevel'] = $('input[name="maxLevel"]').attr('max');
        }

        path = path+'/level='+options['minLevel']+'-'+options['maxLevel'];
        history.pushState(null,null,path);

        filter(options, event);
    });

    $('select[name="rank"]').change(function(event){
        options['rank'] = $(this).val();

        path = path.replace(/\/rank=[\w\-]+/,'');

        if(options['rank'] != 'all')
        {
            var roster = JSON.parse(window.localStorage['roster']);
            path = path+'/rank='+roster.ranks[options['rank']].toString().replace(' ','-').toLowerCase();
        }

        history.pushState(null,null,path);

        filter(options, event);
    });

    $('input[type="reset"]').click(function(event)
    {
        options = [];

        path = '/roster';
        history.pushState(null,null,path);

        filter(options, event);
    });
});