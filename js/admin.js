(function ($) {
	"use strict";
	$(function () {

		// make the taxonomies list sortable
		$('.widget-liquid-right').on('mouseenter', 'ul.ejo-features-list-admin', function() {
			$(this).sortable();
		});
		
	});
	
}(jQuery));