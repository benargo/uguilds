(function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
})( jQuery );

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
    $('select[name="characterName"], select[name="minLevel"], select[name="maxLevel"]').combobox();

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

        if(options['minLevel'] === undefined || options['minLevel'] === '')
        {
            options['minLevel'] = $('input[name="minLevel"]').attr('min');
        }
        if(options['maxLevel'] === undefined || options['maxLevel'] === '')
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

    $('input[type="reset"]').click(function(event){
        options = [];
        path = '/roster';
        history.pushState(null,null,path);
        filter(options, event);
    });
});