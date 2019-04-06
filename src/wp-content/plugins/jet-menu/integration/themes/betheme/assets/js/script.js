( function( $ ) {
	jQuery( '.jet-menu' ).on( 'jetMenuCreated', function() {
		$( this ).closest( '#menu' ).removeAttr( 'id' ).removeAttr( 'class' );
		$( '.responsive-menu-toggle ' ).css( 'display', 'none' );
	} );
}( jQuery ) );