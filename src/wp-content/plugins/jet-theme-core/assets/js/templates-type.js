(function( $ ) {

	'use strict';

	var JetTemplatesType = {

		errorClass: 'jet-template-types-popup__error',

		init: function() {

			var self = this;

			$( document )
				.on( 'click.JetTemplatesType', '.page-title-action', self.openPopup )
				.on( 'click.JetTemplatesType', '.jet-template-types-popup__overlay', self.closePopup )
				.on( 'click.JetTemplatesType', '#templates_type_submit', self.validateForm )
				.on( 'change.JetTemplatesType', '#template_type', self.changeType );

		},

		openPopup: function( event ) {
			event.preventDefault();
			$( '.jet-template-types-popup' ).addClass( 'jet-template-types-popup-active' );
		},

		closePopup: function() {
			$( '.jet-template-types-popup' ).removeClass( 'jet-template-types-popup-active' );
		},

		changeType: function() {

			var $this = $( this ),
				value = $this.find( 'option:selected' ).val();

			if ( '' !== value ) {
				$this.removeClass( JetTemplatesType.errorClass );
			}

		},

		validateForm: function() {

			var $this = $( this ),
				$form = $this.closest( '#templates_type_form' ),
				$type = $form.find( '#template_type' ),
				$name = $form.find( '#template_name' ),
				type  = $type.find( 'option:selected' ).val();

			$type.removeClass( JetTemplatesType.errorClass );

			if ( '' !== type ) {
				$form.submit();
			} else {
				$type.addClass( JetTemplatesType.errorClass );
			}

		}

	};

	JetTemplatesType.init();

})( jQuery );