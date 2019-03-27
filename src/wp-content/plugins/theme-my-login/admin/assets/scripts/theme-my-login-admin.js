( function( $ ) {

	$( initAuthCheckForm );

	function initAuthCheckForm() {
		var authCheckForm = $( '#wp-auth-check-form' );

		if ( authCheckForm.length ) {
			authCheckForm.attr( 'data-src', tmlAdmin.interimLoginUrl );
		}
	}
} )( jQuery );

( function( $ ) {

	$( initMetaBoxes );

	function initMetaBoxes() {
		var metaboxes = $( '#tml-settings .postbox' );

		if ( metaboxes.length ) {
			// Make metaboxes toggleable
			postboxes.add_postbox_toggles( pagenow );

			// Close all metaboxes by default
			$( '.postbox' ).addClass( 'closed' );

			// Find each metabox holder
			$( '.metabox-holder' ).each( function() {
				var holder = $( this );

				// Maybe disable sorting
				if ( holder.data( 'sortable' ) == 'off' ) {
					holder.find( '.meta-box-sortables' ).sortable( 'destroy' );
					holder.find( '.postbox .hndle' ).css( 'cursor', 'default' );
				}
			} );
		}
	}
} )( jQuery );

( function( $ ) {
	$( initNotices );

	function initNotices() {
		$( '.tml-notice' ).on( 'click', '.notice-dismiss', function( e ) {
			var notice = $( e.delegateTarget );

			$.post( ajaxurl, {
				action: 'tml-dismiss-notice',
				notice: notice.data( 'notice' )
			} );
		} );
	}
} )( jQuery );
