jQuery(document).ready(function($){

	/**
	 * See docs: http://mjolnic.com/fontawesome-iconpicker/
	 * Dependant of bfa-fontawesom plugin script
	 */

	// Ik wil de iconpicker in een widget laden
	// Maar bij opslaan van widget verdwijnen de event handlers oid
	// Dus daarom bij click de iconpicker genereren
	// Maar dat werkt niet goed, dus vlak voor click: mousedown
	// Bovendien checken of iconpicker al is gegenereerd, want anders blijf je bezig
	/**
	 * Generate iconpicker at mousedown so the iconpicker will appear on click-event
	 * This fixes problem that iconpicker doesn't appear on saving or moving widget
	 */
	$( 'body' ).on( 'mousedown', '.ejo-iconpicker', function(e) {
		// e.preventDefault();

		//* Initialize once
		if ( !$( this ).hasClass( 'initialized' ) ) {
			$( this ).addClass( 'initialized' );

			$( this ).iconpicker({
				placement: 'bottomRight',
				component: '.ejo-iconpicker-component'
			});
		}

		// $( this ).trigger( 'click' );
	});

	// $('.ejo-iconpicker').iconpicker({
	// 	placement: 'bottomLeft'
	// });

	// $('.ejo-iconpicker').on('iconpickerSelected', function(e) {
 //    	$('.lead .picker-target').get(0).className = 'picker-target fa-3x ' +
 //        	e.iconpickerInstance.options.iconBaseClass + ' ' +
 //            e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
 //    });

	// $( 'body' ).on( 'mousedown', '.ejo-iconpicker', function(e) {  // Use mousedown even to allow for triggering click later without infinite looping.
	// 	e.preventDefault();

	// 	$( this ).not( ' .initialized' )
	// 		.addClass( 'initialized' )
	// 		// .iconpicker();
	// 		.iconpicker({
	// 			placement: 'bottomLeft',
	// 			hideOnSelect: true,
	// 			animation: false,
	// 			selectedCustomClass: 'selected',
	// 			// icons: bfa_vars.fa_icons,
	// 			// fullClassFormatter: function( val ) {
	// 			// 	if ( bfa_vars.fa_prefix ) {
	// 			// 		return bfa_vars.fa_prefix + ' ' + bfa_vars.fa_prefix + '-' + val;
	// 			// 	} else {
	// 			// 		return val;
	// 			// 	}
	// 			// },
	// 		});

	// 	$( this ).trigger( 'click' );
	// })
	// .on( 'click', '.ejo-iconpicker', function(e) {
	// 	$( this ).find( '.iconpicker-search' ).focus();
	// });

	// $('body').on('click', '.ejo-iconpicker', function() {
	// 	$(this).iconpicker();
	// 	$(this).trigger('click'); 
	// });

});