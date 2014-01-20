/**
 * uGuilds.js
 * This is the primary JavaScript file for uGuilds. 
 * It performs the basic functions for each instance of the application.
 *
 ** Table of Contents
 * Navigational jQuery
 */

/** 
 * Navigational jQuery
 */
$(function() {
	$("#navigation select option").each(function() {
		if($(this).val() == window.location.href) {
			$(this).attr('selected', 'true');
		}
	});

	$("#navigation select").change(function(event) {
		event.preventDefault();
		window.location.href = $(this).val();
	});
});





