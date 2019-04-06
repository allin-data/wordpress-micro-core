( function( $ ) {

	'use strict';

	var JetBlocksEditor = {

		activeSection: null,

		modal: false,

		init: function() {

			elementor.channels.editor.on( 'section:activated', JetBlocksEditor.onAuthSectionActivated );
			elementor.channels.editor.on( 'section:activated', JetBlocksEditor.onSearchSectionActivated );
			elementor.channels.editor.on( 'section:activated', JetBlocksEditor.onCartSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				elementor.$preview[0].contentWindow.JetBlocksEditor = JetBlocksEditor;

				JetBlocksEditor.onPreviewLoaded();
			});
		},

		onCartSectionActivated: function( sectionName, editor ) {

			var editedElement = editor.getOption( 'editedElementView' );

			if ( 'jet-blocks-cart' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetBlocksEditor.activeSection = sectionName;

			var isCart = -1 !== [ 'cart_list_style', 'cart_list_items_style', 'cart_buttons_style' ].indexOf( sectionName );

			if ( isCart ) {
				editedElement.$el.find( '.jet-blocks-cart' ).addClass( 'jet-cart-hover' );
			} else {
				editedElement.$el.find( '.jet-blocks-cart' ).removeClass( 'jet-cart-hover' );
			}

		},

		onSearchSectionActivated: function( sectionName, editor ) {

			var editedElement = editor.getOption( 'editedElementView' );

			if ( 'jet-search' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetBlocksEditor.activeSection = sectionName;

			var isPopup = -1 !== [ 'section_popup_style', 'section_popup_close_style', 'section_form_style' ].indexOf( sectionName );

			if ( isPopup ) {
				editedElement.$el.find( '.jet-search' ).addClass( 'jet-search-popup-active' );
			} else {
				editedElement.$el.find( '.jet-search' ).removeClass( 'jet-search-popup-active' );
			}

		},

		onAuthSectionActivated: function( sectionName, editor ) {

			var editedElement = editor.getOption( 'editedElementView' );

			if ( 'jet-auth-links' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetBlocksEditor.activeSection = sectionName;

			var isLogout     = -1 !== [ 'section_logout_link', 'section_logout_link_style' ].indexOf( sectionName );
			var isRegistered = -1 !== [ 'section_registered_link', 'section_registered_link_style' ].indexOf( sectionName );

			if ( isLogout ) {
				editedElement.$el.find( '.jet-auth-links__logout' ).css( 'display', 'flex' );
				editedElement.$el.find( '.jet-auth-links__login' ).css( 'display', 'none' );
			} else {
				editedElement.$el.find( '.jet-auth-links__logout' ).css( 'display', 'none' );
				editedElement.$el.find( '.jet-auth-links__login' ).css( 'display', 'flex' );
			}

			if ( isRegistered ) {
				editedElement.$el.find( '.jet-auth-links__registered' ).css( 'display', 'flex' );
				editedElement.$el.find( '.jet-auth-links__register' ).css( 'display', 'none' );
			} else {
				editedElement.$el.find( '.jet-auth-links__registered' ).css( 'display', 'none' );
				editedElement.$el.find( '.jet-auth-links__register' ).css( 'display', 'flex' );
			}

		},

		onPreviewLoaded: function() {
			var $previewContents = window.elementor.$previewContents,
				elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/jet-hamburger-panel.default', function( $scope ){
				$scope.find( '.jet-blocks__edit-cover' ).on( 'click', JetBlocksEditor.showTemplatesModal );

				$scope.find( '.jet-blocks-new-template-link' ).on( 'click', function( event ) {
					//window.location.href = $( this ).attr( 'href' );
					window.open( $( this ).attr( 'href' ) ); // changed on this for the opened link in new tab
				} );
			} );

			JetBlocksEditor.getModal().on( 'hide', function() {
				window.elementor.reloadPreview();
			});
		},

		showTemplatesModal: function() {
			var editLink = $( this ).data( 'template-edit-link' );

			JetBlocksEditor.showModal( editLink );
		},

		showModal: function( link ) {
			var $iframe,
				$loader;

			JetBlocksEditor.getModal().show();

			$( '#jet-blocks-edit-template-modal .dialog-message' ).html( '<iframe src="' + link + '" id="jet-blocks-edit-frame" width="100%" height="100%"></iframe>' );
			$( '#jet-blocks-edit-template-modal .dialog-message' ).append( '<div id="jet-blocks-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>' );

			$iframe = $( '#jet-blocks-edit-frame' );
			$loader = $( '#jet-blocks-loading' );

			$iframe.on( 'load', function() {
				$loader.fadeOut( 300 );
			} );
		},

		getModal: function() {

			if ( ! JetBlocksEditor.modal ) {
				this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
					id: 'jet-blocks-edit-template-modal',
					closeButton: true,
					closeButtonClass: 'eicon-close',
					hide: {
						onBackgroundClick: false
					}
				} );
			}

			return JetBlocksEditor.modal;
		}

	};

	$( window ).on( 'elementor:init', JetBlocksEditor.init );

	window.JetBlocksEditor = JetBlocksEditor;

}( jQuery ) );
