(function( $, pluginsData ) {

	'use strict';

	var JetPluginsPage = {

		init: function() {

			var self = this;

			$( document )
				.on( 'click.JetPluginsPage', 'a[data-action="install"]', self.installPlugin )
				.on( 'click.JetPluginsPage', 'a[data-action="activate"]', self.activatePlugin )
				.on( 'click.JetPluginsPage', 'a[data-action="update"]', self.updatePlugin );

		},

		showError: function( $button, message ) {
			$button.closest( '.jet-plugin' ).find( '.jet-plugin__errors' ).html( message );
		},

		installPlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.installing );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'jet_core_install_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {

					$this.html( pluginsData.activate );
					$this.data( 'activate' );
					$this.attr( 'data-action', 'activate' );

					$this.closest( '.jet-plugin' ).find( '.user-version b' ).html( response.data.version );

				} else {
					JetPluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		},

		activatePlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.activating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'jet_core_activate_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.replaceWith( pluginsData.activated );
				} else {
					JetPluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		},

		updatePlugin: function( event ) {

			event.preventDefault();

			var $this  = $( this ),
				plugin = $this.data( 'plugin' );

			$this.html( pluginsData.updating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action:  'jet_core_update_plugin',
					plugin: plugin
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.closest( '.jet-plugin' ).find( '.user-version b' ).html( response.data.newVersion );
					$this.replaceWith( pluginsData.updated );
				} else {
					JetPluginsPage.showError( $this, response.data.errorMessage );
					$this.html( pluginsData.failed );
				}

			});

		}

	};

	JetPluginsPage.init();

})( jQuery, window.JetPluginsData );