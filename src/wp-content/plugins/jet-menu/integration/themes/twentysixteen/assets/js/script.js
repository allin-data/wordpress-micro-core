( function( $ ) {
	jQuery( '.jet-menu' ).on( 'jetMenuCreated', function() {
		$( this ).closest( '.main-navigation' ).removeClass( 'main-navigation' );
	} );
}( jQuery ) );