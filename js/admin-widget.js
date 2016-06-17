jQuery(document).ready(function($){

	/**
	 * See docs: http://mjolnic.com/fontawesome-iconpicker/
	 * Dependant of bfa-fontawesom plugin script
	 */
	$('body').on('click', '.ejo-icon-picker', function() {
		$(this).iconpicker();
		$(this).trigger('click'); 
	});

});