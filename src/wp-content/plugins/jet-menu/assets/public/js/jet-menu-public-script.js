(function( $ ){
	'use strict';

	var jetMenu = {

		init: function() {
			var rollUp                   = false,
				jetMenuMouseleaveDelay   = 500,
				jetMenuMegaWidthType     = 'container',
				jetMenuMegaWidthSelector = '',
				jetMenuMegaOpenSubType   = 'hover',
				jetMenuMobileBreakpoint  = 768;

			if ( window.jetMenuPublicSettings && window.jetMenuPublicSettings.menuSettings ) {
				rollUp                   = ( 'true' === jetMenuPublicSettings.menuSettings.jetMenuRollUp ) ? true : false;
				jetMenuMouseleaveDelay   = jetMenuPublicSettings.menuSettings.jetMenuMouseleaveDelay || 500;
				jetMenuMegaWidthType     = jetMenuPublicSettings.menuSettings.jetMenuMegaWidthType || 'container';
				jetMenuMegaWidthSelector = jetMenuPublicSettings.menuSettings.jetMenuMegaWidthSelector || '';
				jetMenuMegaOpenSubType   = jetMenuPublicSettings.menuSettings.jetMenuMegaOpenSubType || 'hover';
				jetMenuMobileBreakpoint  = jetMenuPublicSettings.menuSettings.jetMenuMobileBreakpoint || 768;
			}

			$( '.jet-menu-container' ).JetMenu( {
				enabled: rollUp,
				mouseLeaveDelay: +jetMenuMouseleaveDelay,
				megaWidthType: jetMenuMegaWidthType,
				megaWidthSelector: jetMenuMegaWidthSelector,
				openSubType: jetMenuMegaOpenSubType,
				threshold: +jetMenuMobileBreakpoint
			} );

		},

	};

	jetMenu.init();

}( jQuery ));
