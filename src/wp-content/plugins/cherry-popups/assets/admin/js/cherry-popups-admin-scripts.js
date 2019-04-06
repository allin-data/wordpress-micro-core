( function( $, CherryJsCore ) {
	'use strict';

	CherryJsCore.utilites.namespace( 'cherryPopupsBackScripts' );
	CherryJsCore.cherryPopupsBackScripts = {

		saveHandlerId: 'cherry_save_options_ajax',
		setAsDefaultHandlerId: 'cherry_set_as_default_options_ajax',
		resetHandlerId: 'cherry_restore_options_ajax',

		saveButtonId: '#cherry-popups-save-options',
		resetButtonId: '#cherry-popups-restore-options',
		formId: '#cherry-popups-options-form',

		saveOptionsInstance: null,
		resetOptionsInstance: null,

		init: function() {

			this.saveOptionsInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.saveHandlerId,
					successCallback: this.saveSuccessCallback.bind( this )
				}
			);
			this.resetOptionsInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.resetHandlerId,
					successCallback: this.restoreSuccessCallback.bind( this )
				}
			);

			this.addEvents();
		},

		addEvents: function() {
			$( 'body' )
				.on( 'click', this.saveButtonId, this.saveOptionsHandler.bind( this ) )
				.on( 'click', this.resetButtonId, this.resetOptionsHandler.bind( this ) );
		},

		saveOptionsHandler: function( event ) {
			this.disableButton( event.target );
			this.saveOptionsInstance.sendFormData( this.formId );
		},

		resetOptionsHandler: function( event ) {
			this.disableButton( event.target );
			this.resetOptionsInstance.send();
		},

		saveSuccessCallback: function() {
			this.enableButton( this.saveButtonId );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.cherryPopupSettings.save_message );
		},

		restoreSuccessCallback: function() {
			this.enableButton( this.resetButtonId );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.cherryPopupSettings.restore_message );
			setTimeout( function() {
				window.location.href = window.cherryPopupSettings.redirect_url;
			}, 500 );
		},

		disableButton: function( button ) {
			$( button )
				.attr( 'disabled', 'disabled' );
		},

		enableButton: function( button ) {
			var timer = null;

			$( button )
				.removeAttr( 'disabled' )
				.addClass( 'success' );

			timer = setTimeout(
				function() {
					$( button ).removeClass( 'success' );
					clearTimeout( timer );
				},
				1000
			);
		}
	};

	CherryJsCore.cherryPopupsBackScripts.init();
}( jQuery, window.CherryJsCore ) );
