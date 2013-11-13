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

    $('select[name="characterName"]').combobox();
    $('select[name="minLevel"]').combobox();
    $('select[name="maxLevel"]').combobox();
});