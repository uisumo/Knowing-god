/**
 * Admin related script
 */

jQuery( document ).ready( function( $ ) {
	"use strict";

    $( document ).on( "click", ".custom_image_button", function() {

        var inputText = $(this).data('field_name');
		jQuery.data( document.body, 'prevElement', $( this ).prev() );
		
        window.send_to_editor = function ( html ) {
			// console.log( inputText );
			var imgurl = jQuery( 'img', html ).attr( 'src' );
			
			var image_class = jQuery( 'img', html ).attr( 'class' );
			
			if ( typeof( imgurl ) == 'undefined' ) {
			 imgurl = $( html ).attr( "src" );
			}
			if ( typeof( imgurl ) == 'undefined' ) {
			 imgurl = $( html ).attr( "href" );
			}
            // var inputText = jQuery.data( document.body, 'prevElement' );
			// var inputText = $(this).data('field_name');
			// console.log( inputText );
			
            if ( typeof( inputText ) != 'undefined' && inputText != '' )
            {
				 console.log( imgurl );
                // inputText.val( imgurl );
				$( '#' + inputText ).val( imgurl );
				$( '#' + inputText +'_display' ).val( imgurl );
            }

            tb_remove();
        };

        tb_show( '', 'media-upload.php?type=image&TB_iframe=true' );
        return false;
    });
});
