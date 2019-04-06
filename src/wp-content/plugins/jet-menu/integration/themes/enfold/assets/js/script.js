( function( $ ) {
	jQuery( '.jet-menu' ).on( 'jetMenuCreated', function() {
		$( this ).closest( '.main_menu' ).removeClass( 'main_menu' ).addClass( 'jet_main_menu' );
		$( this ).closest( '.avia-menu' ).removeClass( 'avia-menu av-main-nav-wrap' );
	} );
}( jQuery ) );