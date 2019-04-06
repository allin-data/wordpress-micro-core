(function( $, themeData ) {

	'use strict';

	var JetThemePage = {

		xhr: null,

		init: function() {

			var self = this;

			$( document )
				.on( 'click.JetThemePage', 'a[data-action="update-theme"]', self.updateTheme )
				.on( 'click.JetThemePage', 'button[data-action="install-child"]', self.installChild )
				.on( 'click.JetThemePage', '.jet-backup-delete', self.confirmDelete )
				.on( 'click.JetThemePage', 'button[data-action="activate-child"]', self.activateChild );

			$( window ).on( 'cx-switcher-change', self.updateBackupsSetting );

		},

		confirmDelete: function( event ) {

			event.preventDefault();

			var $this = $( this );

			if ( confirm( 'Are you sure you want to delete this file?' ) ) {
				window.location = $this.attr( 'href' );
			}

		},

		updateBackupsSetting: function( event ) {

			var controlName   = event.controlName,
				controlStatus = event.controlStatus;

			if ( 'jet_core_auto_backup' !== controlName ) {
				return;
			}

			if ( null !== JetThemePage.xhr ) {
				JetThemePage.xhr.abort();
			}

			JetThemePage.xhr = $.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'jet_core_update_backup_status',
					new_value: controlStatus,
				}
			}).done( function( response ) {
				JetThemePage.xhr = null;
			});

		},

		updateTheme: function( event ) {

			event.preventDefault();

			var $this = $( this );

			$this.html( themeData.updating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'jet_core_update_theme'
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$( '.jet-theme__update-notice' ).remove();
					$( '.jet-theme__version-val' ).html( response.data.newVersion );
				} else {
					$this.replaceWith( themeData.failed );
					$( '.jet-theme__errors' ).html( response.data.errorMessage );
				}

				if ( response.data.backupsList ) {
					$( '.jet-backups-wrap' ).html( response.data.backupsList );
				}

			});

		},

		installChild: function( event ) {

			event.preventDefault();

			var $this = $( this );

			$this.html( themeData.installing );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'jet_core_install_child_theme'
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.html( themeData.activate );
					$this.data( 'action', 'activate-child' );
					$this.attr( 'data-action', 'activate-child' );
					$this.data( 'theme', response.data.theme );
					$( '.jet-child-theme__status span' ).html( themeData.installed );
				} else {
					$this.replaceWith( themeData.failed );
					$( '.jet-child-theme__errors' ).html( response.data.errorMessage );
				}

			});

		},

		activateChild: function( event ) {

			event.preventDefault();

			var $this = $( this );

			$this.html( themeData.activating );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'jet_core_activate_child_theme'
				}
			}).done( function( response ) {

				if ( true === response.success ) {
					$this.remove();
					$( '.jet-child-theme__status span' ).html( themeData.activated );
				} else {
					$this.replaceWith( themeData.failed );
					$( '.jet-child-theme__errors' ).html( response.data.errorMessage );
				}

			});
		}

	};

	JetThemePage.init();

})( jQuery, window.JetThemeData );