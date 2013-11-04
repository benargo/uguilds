$(document).ready(function() {
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

    // Table Sorter
/*    $.tablesorter.addParser({
        // set a unique id 
        id: 'ranks',
        is: function(s) {
            // return false so this parser is not auto detected 
            return false;
        },
        format: function(s) {
            // format your data for normalization 
            return s.data('id');
        },
        // set type, either numeric or text 
        type: 'numeric'
    }); */

    $("table.guild-roster").tablesorter(/*{
        headers: {
            4: {
                sorter:'ranks'
            }
        }
    }*/);
});