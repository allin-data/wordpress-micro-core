( function( $, CherryJsCore ) {
	'use strict';

	CherryJsCore.utilites.namespace( 'cherryPopupsFrontScripts' );
	CherryJsCore.cherryPopupsFrontScripts = {
		init: function() {
			if ( $( '.cherry-popup-wrapper' )[0] ) {
				$( '.cherry-popup-wrapper' ).cherryPopupsPlugin();
			}
		}
	};
	CherryJsCore.cherryPopupsFrontScripts.init();
}( jQuery, window.CherryJsCore ) );

