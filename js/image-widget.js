/**
 * Tribe Image Widget Javascript
 * @author Modern Tribe, Inc.
 * Copyright 2013.
 */
jQuery(document).ready(function($){

	EJO_Featured_Widget = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string ) {

			var frame = wp.media({
				title : EjoFeaturedWidget.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : EjoFeaturedWidget.button_title }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				EJO_Featured_Widget.render( widget_id, widget_id_string, attachments[0] );
			});

			frame.open();
			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, attachment ) {

			$("#" + widget_id_string + 'preview').html(EJO_Featured_Widget.imgHTML( attachment ));

			$("#" + widget_id_string + 'fields').slideDown();

			$("#" + widget_id_string + 'attachment_id').val(attachment.id);
			$("#" + widget_id_string + 'imageurl').val(attachment.url);
			$("#" + widget_id_string + 'aspect_ratio').val(attachment.width/attachment.height);
			$("#" + widget_id_string + 'width').val(attachment.width);
			$("#" + widget_id_string + 'height').val(attachment.height);

			$("#" + widget_id_string + 'size').val('full');
			$("#" + widget_id_string + 'custom_size_selector').slideDown();
			EJO_Featured_Widget.toggleSizes( widget_id, widget_id_string );

			EJO_Featured_Widget.updateInputIfEmpty( widget_id_string, 'title', attachment.title );
			EJO_Featured_Widget.updateInputIfEmpty( widget_id_string, 'alt', attachment.alt );
			EJO_Featured_Widget.updateInputIfEmpty( widget_id_string, 'description', attachment.description );
			// attempt to populate 'description' with caption if description is blank.
			EJO_Featured_Widget.updateInputIfEmpty( widget_id_string, 'description', attachment.caption );
		},

		// Update input fields if it is empty
		updateInputIfEmpty : function( widget_id_string, name, value ) {
			var field = $("#" + widget_id_string + name);
			if ( field.val() == '' ) {
				field.val(value);
			}
		},

		// Render html for the image.
		imgHTML : function( attachment ) {
			var img_html = '<img src="' + attachment.url + '" ';
			img_html += 'width="' + attachment.width + '" ';
			img_html += 'height="' + attachment.height + '" ';
			if ( attachment.alt != '' ) {
				img_html += 'alt="' + attachment.alt + '" ';
			}
			img_html += '/>';
			return img_html;
		},

		// Hide or display the WordPress image size fields depending on if 'Custom' is selected.
		toggleSizes : function( widget_id, widget_id_string ) {
			if ( $( '#' + widget_id_string + 'size' ).val() == 'tribe_image_widget_custom' ) {
				$( '#' + widget_id_string + 'custom_size_fields').slideDown();
			} else {
				$( '#' + widget_id_string + 'custom_size_fields').slideUp();
			}
		},

		// Update the image height based on the image width.
		changeImgWidth : function( widget_id, widget_id_string ) {
			var aspectRatio = $("#" + widget_id_string + 'aspect_ratio').val();
			if ( aspectRatio ) {
				var width = $( '#' + widget_id_string + 'width' ).val();
				var height = Math.round( width / aspectRatio );
				$( '#' + widget_id_string + 'height' ).val(height);
				//EJO_Featured_Widget.changeImgSize( widget_id, widget_id_string, width, height );
			}
		},

		// Update the image width based on the image height.
		changeImgHeight : function( widget_id, widget_id_string ) {
			var aspectRatio = $("#" + widget_id_string + 'aspect_ratio').val();
			if ( aspectRatio ) {
				var height = $( '#' + widget_id_string + 'height' ).val();
				var width = Math.round( height * aspectRatio );
				$( '#' + widget_id_string + 'width' ).val(width);
				//EJO_Featured_Widget.changeImgSize( widget_id, widget_id_string, width, height );
			}
		}

	};

});