(function( $, settingsData ) {

	'use strict';

	var JetSettingsPage = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.JetSettingsPage', '#jet_activate_license', self.activateLicese )
				.on( 'click.JetSettingsPage', '#jet_deactivate_license', self.deactivateLicese );

		},

		activateLicese: function() {

			var $licenseInput = $( '#jet_core_license' ),
				licesne       = $licenseInput.val();

			if ( ! licesne ) {
				$licenseInput.addClass( 'jet-error' );
				$( '.jet-core-license__errors' ).html( settingsData.messages.empty );
			} else {
				window.location = settingsData.activateUrl.replace( '%license_key%', licesne );
			}

		},

		deactivateLicese: function() {
			window.location = settingsData.deactivateUrl;
		}

	};

	JetSettingsPage.init();

})( jQuery, window.JetSettingsData );