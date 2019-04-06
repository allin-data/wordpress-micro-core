( function( $ ) {

	'use strict';

	var JetElementsEditor = {

		activeSection: null,

		editedElement: null,

		init: function() {
			elementor.channels.editor.on( 'section:activated', JetElementsEditor.onAnimatedBoxSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				elementor.$preview[0].contentWindow.JetElementsEditor = JetElementsEditor;

				JetElementsEditor.onPreviewLoaded();
			});
		},

		onAnimatedBoxSectionActivated: function( sectionName, editor ) {
			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.JetElementsEditor.editedElement;

			if ( prevEditedElement && 'jet-animated-box' === prevEditedElement.model.get( 'widgetType' ) ) {

				prevEditedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped' );
				prevEditedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );

				window.JetElementsEditor.editedElement = null;
			}

			if ( 'jet-animated-box' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetElementsEditor.editedElement = editedElement;
			window.JetElementsEditor.activeSection = sectionName;

			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( sectionName );

			if ( isBackSide ) {
				editedElement.$el.find( '.jet-animated-box' ).addClass( 'flipped' );
				editedElement.$el.find( '.jet-animated-box' ).addClass( 'flipped-stop' );
			} else {
				editedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped' );
				editedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		onPreviewLoaded: function() {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/jet-dropbar.default', function( $scope ){

				$scope.find( '.jet-dropbar-edit-link' ).on( 'click', function( event ) {
					window.open( $( this ).attr( 'href' ) );
				} );
			} );
		}
	};

	$( window ).on( 'elementor:init', JetElementsEditor.init );

	window.JetElementsEditor = JetElementsEditor;

}( jQuery ) );
