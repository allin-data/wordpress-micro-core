/**
 * ResizeSensor.js
 */
!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.ResizeSensor=t()}("undefined"!=typeof window?window:this,function(){function e(e,t){var i=Object.prototype.toString.call(e),n="[object Array]"===i||"[object NodeList]"===i||"[object HTMLCollection]"===i||"[object Object]"===i||"undefined"!=typeof jQuery&&e instanceof jQuery||"undefined"!=typeof Elements&&e instanceof Elements,o=0,s=e.length;if(n)for(;o<s;o++)t(e[o]);else t(e)}if("undefined"==typeof window)return null;var t=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||function(e){return window.setTimeout(e,20)},i=function(n,o){function s(){var e=[];this.add=function(t){e.push(t)};var t,i;this.call=function(){for(t=0,i=e.length;t<i;t++)e[t].call()},this.remove=function(n){var o=[];for(t=0,i=e.length;t<i;t++)e[t]!==n&&o.push(e[t]);e=o},this.length=function(){return e.length}}function r(e,i){if(e)if(e.resizedAttached)e.resizedAttached.add(i);else{e.resizedAttached=new s,e.resizedAttached.add(i),e.resizeSensor=document.createElement("div"),e.resizeSensor.className="resize-sensor";var n="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; visibility: hidden;",o="position: absolute; left: 0; top: 0; transition: 0s;";e.resizeSensor.style.cssText=n,e.resizeSensor.innerHTML='<div class="resize-sensor-expand" style="'+n+'"><div style="'+o+'"></div></div><div class="resize-sensor-shrink" style="'+n+'"><div style="'+o+' width: 200%; height: 200%"></div></div>',e.appendChild(e.resizeSensor),e.resizeSensor.offsetParent!==e&&(e.style.position="relative");var r,d,c,l,f=e.resizeSensor.childNodes[0],a=f.childNodes[0],h=e.resizeSensor.childNodes[1],u=e.offsetWidth,z=e.offsetHeight,v=function(){a.style.width="100000px",a.style.height="100000px",f.scrollLeft=1e5,f.scrollTop=1e5,h.scrollLeft=1e5,h.scrollTop=1e5};v();var p=function(){d=0,r&&(u=c,z=l,e.resizedAttached&&e.resizedAttached.call())},y=function(){c=e.offsetWidth,l=e.offsetHeight,(r=c!=u||l!=z)&&!d&&(d=t(p)),v()},m=function(e,t,i){e.attachEvent?e.attachEvent("on"+t,i):e.addEventListener(t,i)};m(f,"scroll",y),m(h,"scroll",y)}}e(n,function(e){r(e,o)}),this.detach=function(e){i.detach(n,e)}};return i.detach=function(t,i){e(t,function(e){e&&(e.resizedAttached&&"function"==typeof i&&(e.resizedAttached.remove(i),e.resizedAttached.length())||e.resizeSensor&&(e.contains(e.resizeSensor)&&e.removeChild(e.resizeSensor),delete e.resizeSensor,delete e.resizedAttached))})},i});

( function( $, settings ) {

	'use strict';

	var jetMenuAdmin = {

		instance: [],
		savedTimeout: null,
		menuId: 0,
		depth: 0,

		saveHandlerId: 'jet_menu_save_options_ajax',
		resetHandlerId: 'jet_menu_restore_options_ajax',

		saveOptionsHandlerInstance: null,
		resetOptionsHandlerInstance: null,

		init: function() {

			this.initTriggers();

			$( document )
				.on( 'click.jetMenuAdmin', '.jet-settings-tabs__nav-item ', this.switchTabs )
				.on( 'click.jetMenuAdmin', '.jet-menu-editor', this.openEditor )
				.on( 'click.jetMenuAdmin', '.jet-menu-trigger', this.initPopup )
				.on( 'click.jetMenuAdmin', '.jet-menu-popup__overlay', this.closePopup )
				.on( 'click.jetMenuAdmin', '.jet-close-frame', this.closeEditor )
				.on( 'click.jetMenuAdmin', '.jet-save-menu', this.saveMenu )
				.on( 'click.jetMenuAdmin', '.jet-menu-settins-save', this.saveSettins )
				.on( 'click.jetMenuAdmin', '.jet-menu-import-btn', this.switchImportControl )
				.on( 'click.jetMenuAdmin', '.jet-menu-run-import-btn', this.runImport )
				.on( 'click.jetMenuAdmin', '#jet-menu-reset-options', this.resetOptions )
				.on( 'click.jetMenuAdmin', '#jet-menu-create-preset', this.createPreset )
				.on( 'click.jetMenuAdmin', '#jet-menu-update-preset', this.updatePreset )
				.on( 'click.jetMenuAdmin', '#jet-menu-load-preset', this.loadPreset )
				.on( 'click.jetMenuAdmin', '#jet-menu-delete-preset', this.deletePreset )
				.on( 'click.jetMenuAdmin', '.jet-menu-popup__close', this.closePopup )
				.on( 'focus.jetMenuAdmin', '.jet-preset-name', this.clearPresetError );


			this.saveOptionsHandlerInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.saveHandlerId,
					successCallback: this.saveSuccessCallback.bind( this )
				}
			);
			this.resetOptionsHandlerInstance = new CherryJsCore.CherryAjaxHandler(
				{
					handlerId: this.resetHandlerId,
					successCallback: this.restoreSuccessCallback.bind( this )
				}
			);

			this.addOptionPageEvents();

			if ( 0 < $( '.cherry-tab__tabs-wrap' ).length ) {

				var stickySidebar = new StickySidebar( '.cherry-tab__tabs-wrap', {
					topSpacing: 55,
					containerSelector: '.cherry-tab__tabs',
					innerWrapperSelector: '.cherry-tab__tabs-wrap-content'
				} );

			}

		},

		createPreset: function() {

			var $this      = $( this ),
				$input     = $this.prev( '.cherry-ui-text' ),
				$msg       = $this.next( '.jet-preset-msg' ),
				presetName = $input.val(),
				fields     = null,
				data       = {};

			if ( '' === presetName ) {
				$msg.addClass( 'jet-menu-error-message' ).text( settings.optionPageMessages.preset.nameError );
				return;
			}

			data.action   = 'jet_menu_create_preset';
			data.name     = presetName;
			data.settings = CherryJsCore.cherryHandlerUtils.serializeObject( $( '#jet-menu-options-form' ) );

			$this.prop( 'disabled', true );

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: data
			}).done( function( response ) {
				if ( true === response.success ) {
					$msg.text( settings.optionPageMessages.preset.created );
					window.location = settings.menuPageUrl;
				} else {
					$this.prop( 'disabled', false );
					$msg.addClass( 'jet-menu-error-message' ).text( response.data.message );
				}

			});
		},

		updatePreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.jet-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.updateError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmUpdate ) ) {

				data.action   = 'jet_menu_update_preset';
				data.preset   = preset;
				data.settings = CherryJsCore.cherryHandlerUtils.serializeObject( $( '#jet-menu-options-form' ) );

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.updated );
					$this.prop( 'disabled', false );
					setTimeout( function() {
						$msg.empty();
					}, 3000 );
				});
			}

		},

		loadPreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.jet-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.loadError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmLoad ) ) {

				data.action   = 'jet_menu_load_preset';
				data.preset   = preset;

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.loaded );
					window.location = settings.menuPageUrl;
				});
			}

		},

		deletePreset: function() {

			var $this   = $( this ),
				$select = $this.prev( '.cherry-ui-select' ),
				$msg    = $this.next( '.jet-preset-msg' ),
				preset  = $select.find( ':selected' ).val(),
				fields  = null,
				data    = {};

			if ( '' === preset ) {
				$msg.text( settings.optionPageMessages.preset.deleteError );
				return;
			}

			if ( confirm( settings.optionPageMessages.preset.confirmDelete ) ) {

				data.action   = 'jet_menu_delete_preset';
				data.preset   = preset;

				$this.prop( 'disabled', true );

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: data
				}).done( function( response ) {
					$msg.text( settings.optionPageMessages.preset.deleted );
					window.location = settings.menuPageUrl;
				});
			}

		},

		clearPresetError: function() {
			$( this ).siblings( '.jet-preset-msg' ).removeClass( 'jet-menu-error-message' ).text( '' );
		},

		resetOptions: function() {

			if ( confirm( settings.optionPageMessages.resetMessage ) ) {
				window.location = settings.resetUrl;
			}

		},

		switchImportControl: function() {
			$( this ).siblings( '.jet-menu-import' ).toggleClass( 'import-active' );
		},

		runImport: function() {

			var $this      = $( this ),
				$fileInput = $this.siblings( '.jet-menu-import-file' ),
				$messages  = $this.siblings( '.jet-menu-import-messages' ),
				file       = $fileInput.val();

			$messages.removeClass( 'jet-menu-error-message jet-menu-success-message' ).html( '' );

			if ( ! file ) {
				$messages.addClass( 'jet-menu-error-message' ).html( settings.optionPageMessages.emptyImportFile );
				return;
			}

			var fileExt = file.split('.').pop().toLowerCase();

			if ( 'json' !== fileExt ) {
				$messages.addClass( 'jet-menu-error-message' ).html( settings.optionPageMessages.incorrectImportFile );
				return;
			}

			var fileToLoad = $fileInput[0].files[0];
			var fileReader = new FileReader();

			$this.prop( 'disabled', true );

			fileReader.onload = function( fileLoadedEvent ) {

				var textFromFileLoaded = fileLoadedEvent.target.result;

				$.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'jet_menu_import_options',
						data: JSON.parse( textFromFileLoaded )
					},
				}).done( function( response ) {

					if ( true === response.success ) {
						$messages.addClass( 'jet-menu-success-message' ).html( response.data.message );
						window.location.reload();
					} else {
						$messages.addClass( 'jet-menu-error-message' ).html( response.data.message );
					}

					$this.prop( 'disabled', false );

				});

			};

			fileReader.readAsText( fileToLoad, 'UTF-8' );

		},

		openEditor: function() {

			var $popup   = $( this ).closest( '.jet-menu-popup' ),
				menuItem = $popup.attr( 'data-id' ),
				url      = settings.editURL.replace( '%id%', menuItem ),
				frame    = null,
				loader   = null,
				editor   = wp.template( 'editor-frame' );

			url = url.replace( '%menuid%', settings.currentMenuId );

			$popup
				.addClass( 'jet-menu-editor-active' )
				.find( '.jet-menu-editor-wrap' )
				.addClass( 'jet-editor-active' )
				.html( editor( { url: url } ) );

			frame  = $popup.find( '.jet-edit-frame' )[0];
			loader = $popup.find( '#elementor-loading' );

			$( frame.contentWindow ).load( function() {
				$popup.find( '.jet-close-frame' ).addClass( 'jet-loaded' );
				loader.fadeOut( 300 );
			} );

		},

		initPopup: function() {

			var $this   = $( this ),
				id      = $this.data( 'item-id' ),
				depth   = $this.data( 'item-depth' ),
				content = null,
				wrapper = wp.template( 'popup-wrapper' ),
				tabs    = wp.template( 'popup-tabs' );

			if ( ! jetMenuAdmin.instance[ id ] ) {

				content = wrapper( {
					id: id,
					content: tabs( { tabs: settings.tabs, depth: depth } ),
					saveLabel: settings.strings.saveLabel
				} );

				$( 'body' ).append( content );
				jetMenuAdmin.instance[ id ] = '#jet-popup-' + id;
			}

			$( jetMenuAdmin.instance[ id ] ).removeClass( 'jet-hidden' );

			jetMenuAdmin.menuId = id;
			jetMenuAdmin.depth  = depth;

			jetMenuAdmin.tabs.showActive(
				$( jetMenuAdmin.instance[ id ] ).find( '.jet-settings-tabs__nav-item:first' )
			);

		},

		switchTabs: function() {
			jetMenuAdmin.tabs.showActive( $( this ) );
		},

		saveSettins: function() {

			var $this        = $( this ),
				$loader      = $this.closest( '.submit' ).siblings( '.spinner' ),
				$saved       = $this.closest( '.submit' ).siblings( '.dashicons-yes' ),
				data         = [],
				preparedData = {};

			data = $( '.jet-menu-settings-fields input, .jet-menu-settings-fields select' ).serializeArray();

			$.each( data, function( index, field ) {
				preparedData[ field.name ] = field.value;
			});

			clearTimeout( jetMenuAdmin.savedTimeout );

			$saved.addClass( 'hidden' );
			$loader.css( 'visibility', 'visible' );

			preparedData.action       = 'jet_save_settings';
			preparedData.current_menu = settings.currentMenuId;

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: preparedData
			}).done( function( response ) {

				$loader.css( 'visibility', 'hidden' );

				if ( true === response.success ) {
					$saved.removeClass( 'hidden' );
					jetMenuAdmin.savedTimeout = setTimeout( function() {
						$saved.addClass( 'hidden' );
					}, 1000 );
				}
			});

		},

		saveMenu: function() {

			var $this        = $( this ),
				$loader      = $this.siblings( '.spinner' ),
				$saved       = $this.siblings( '.dashicons-yes' ),
				data         = [],
				preparedData = {};

			data = $( '.jet-settings-tabs__content input, .jet-settings-tabs__content select' ).serializeArray();

			$.each( data, function( index, field ) {
				preparedData[ field.name ] = field.value;
			});

			clearTimeout( jetMenuAdmin.savedTimeout );

			$saved.addClass( 'hidden' );
			$loader.css( 'visibility', 'visible' );

			preparedData.action  = 'jet_save_menu';
			preparedData.menu_id = jetMenuAdmin.menuId;

			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: preparedData
			}).done( function( response ) {

				$loader.css( 'visibility', 'hidden' );

				if ( true === response.success ) {
					$saved.removeClass( 'hidden' );
					jetMenuAdmin.savedTimeout = setTimeout( function() {
						$saved.addClass( 'hidden' );
					}, 1000 );
				}
			});

		},

		tabs: {
			showActive: function( $item ) {

				var tab      = $item.data( 'tab' ),
					action   = $item.data( 'action' ),
					template = $item.data( 'template' ),
					$content = $item.closest( '.jet-settings-tabs' ).find( '.jet-settings-tabs__content-item[data-tab="' + tab + '"]' ),
					loaded   = parseInt( $content.data( 'loaded' ) );

				if ( $item.hasClass( 'jet-active-tab' ) ) {
					return;
				}

				if ( 0 === loaded ) {
					jetMenuAdmin.tabs.loadTabContent( tab, $content, template, action );
				}

				$item.addClass( 'jet-active-tab' ).siblings().removeClass( 'jet-active-tab' );
				$content.removeClass( 'jet-hidden-tab' ).siblings().addClass( 'jet-hidden-tab' );

			},

			loadTabContent: function( tab, $content, template, action ) {

				if ( ! template && ! action ) {
					return;
				}

				var renderTemplate = null,
					$popup         = $content.closest( '.jet-menu-popup' ),
					id             = $popup.attr( 'data-id' ),
					data           = {};

				$content.has( '.tab-loader' ).addClass( 'tab-loading' );

				if ( ! template ) {

					if ( 0 < settings.tabs[ tab ].data.length ) {
						data         = settings.tabs[ tab ].data;
						data.action  = action;
						data.menu_id = id;
					} else {
						data = {
							action: action,
							menu_id: id
						};
					}

					$.ajax({
						url: ajaxurl,
						type: 'get',
						dataType: 'json',
						data: data
					}).done( function( response ) {
						if ( true === response.success ) {

							$content.removeClass( 'tab-loading' ).html( response.data.content );

							if ( CherryJsCore.ui_elements.iconpicker && window.cherry5IconSets ) {
								CherryJsCore.ui_elements.iconpicker.setIconsSets( window.cherry5IconSets );
							}

							$( 'body' ).trigger( {
								type: 'cherry-ui-elements-init',
								_target: $content
							} );

						}
					});

				} else {
					renderTemplate = wp.template( template );
					$content.html( renderTemplate( settings.tabs[ tab ].data ) );
				}

				$content.data( 'loaded', 1 );

			}
		},

		closePopup: function( event ) {

			event.preventDefault();

			jetMenuAdmin.menuId = 0;
			jetMenuAdmin.depth  = 0;

			$( this )
				.closest( '.jet-menu-popup' ).addClass( 'jet-hidden' )
				.removeClass( 'jet-menu-editor-active' )
				.find( '.jet-menu-editor-wrap.jet-editor-active' ).removeClass( 'jet-editor-active' )
				.find( '.jet-close-frame' ).removeClass( 'jet-loaded' )
				.siblings( '#elementor-loading' ).fadeIn( 0 );
		},

		closeEditor: function( event ) {

			var $this    = $( this ),
				$popup   = $this.closest( '.jet-menu-popup' ),
				$frame   = $( this ).siblings( 'iframe' ),
				$loader  = $popup.find( '#elementor-loading' ),
				$editor  = $frame.closest( '.jet-menu-editor-wrap' ),
				$content = $frame[0].contentWindow,
				saver    = null,
				enabled  = true;

			if ( $content.elementor.saver && 'function' === typeof $content.elementor.saver.isEditorChanged ) {
				saver = $content.elementor.saver;
			} else if ( 'function' === typeof $content.elementor.isEditorChanged ) {
				saver = $content.elementor;
			}

			if ( null !== saver &&  true === saver.isEditorChanged() ) {

				if ( ! confirm( settings.strings.leaveEditor ) ) {
					enabled = false;
				}

			}

			if ( ! enabled ) {
				return;
			}

			$loader.fadeIn(0);
			$popup.removeClass( 'jet-menu-editor-active' );
			$this.removeClass( 'jet-loaded' );
			$editor.removeClass( 'jet-editor-active' );

		},

		getItemId: function( $item ) {
			var id = $item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},


		getItemDepth: function( $item ) {
			var depthClass = $item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			}

			return depthClass[0].replace( 'menu-item-depth-', '' );
		},

		initTriggers: function() {

			var trigger = wp.template( 'menu-trigger' );

			$( document ).on( 'menu-item-added', function( event, $menuItem ) {
				var id = jetMenuAdmin.getItemId( $menuItem );
				$menuItem.find( '.item-title' ).append( trigger( { id: id, label: settings.strings.triggerLabel } ) );
			});

			$( '#menu-to-edit .menu-item' ).each( function() {
				var $this = $( this ),
					depth = jetMenuAdmin.getItemDepth( $this ),
					id    = jetMenuAdmin.getItemId( $this );

				$this.find( '.item-title' ).append( trigger( {
					id: id,
					depth: depth,
					label: settings.strings.triggerLabel
				} ) );
			});

		},

		addOptionPageEvents: function() {
			$( 'body' )
				.on( 'click', '#jet-menu-save-options', this.saveOptionsHandler.bind( this ) )
				.on( 'click', '#jet-menu-restore-options', this.resetOptionsHandler.bind( this ) );
		},

		saveOptionsHandler: function( event ) {
			this.disableFormButton( event.target );
			this.saveOptionsHandlerInstance.sendFormData( '#jet-menu-options-form' );
		},

		resetOptionsHandler: function( event ) {
			this.disableFormButton( event.target );
			this.resetOptionsHandlerInstance.send();
		},

		saveSuccessCallback: function() {
			this.enableFormButton( '#jet-menu-save-options' );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.jetMenuAdminSettings.optionPageMessages.saveMessage );
		},

		restoreSuccessCallback: function() {
			this.enableFormButton( '#jet-menu-restore-options' );
			CherryJsCore.cherryHandlerUtils.noticeCreate( 'success-notice', window.jetMenuAdminSettings.optionPageMessages.restoreMessage );

			setTimeout( function() {
				window.location.href = window.jetMenuAdminSettings.optionPageMessages.redirectUrl;
			}, 500 );
		},

		disableFormButton: function( button ) {
			$( button )
				.attr( 'disabled', 'disabled' );
		},

		enableFormButton: function( button ) {
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

	jetMenuAdmin.init();

}( jQuery, window.jetMenuAdminSettings ) );
