( function( $, elementorFrontend ) {

	'use strict';

	var JetPopup = {

		init: function() {
			var $popup_list = $( '.jet-popup:not(.jet-popup--single-preview)' ),
				editMode    = Boolean( elementorFrontend.isEditMode() );

			if ( ! editMode ) {
				$popup_list.each( function( index ) {
					var $target  = $( this ),
						instance = null,
						settings = $target.data( 'settings' );

					instance = new window.jetPopup( $target, settings );
					instance.init();
				} );
			}

			elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', JetPopup.elementorWidget );

			var widgets = {
				'jet-popup-action-button.default' : JetPopup.widgetPopupActionButton,
				'jet-popup-mailchimp.default' : JetPopup.widgetPopupMailchimp
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

		},

		elementorWidget: function( $scope ) {
			var widget_id   = $scope.data( 'id' ),
				widgetType  = $scope.data( 'element_type' ),
				widgetsData = jetPopupData.elements_data.widgets;

			if ( widgetsData.hasOwnProperty( widget_id ) ) {
				var widgetData     = widgetsData[ widget_id ],
					opentEvent     = widgetData[ 'trigger-type' ],
					customSelector = widgetData[ 'trigger-custom-selector' ],
					popupData      = {
						popupId: widgetData[ 'attached-popup' ]
					};

				switch( opentEvent ) {
					case 'click-self':
						$scope.addClass( 'jet-popup-cursor-pointer' );

						$scope.on( 'click.JetPopup', function( event ) {
							event.preventDefault();

							var $target = $( this );

							if ( window.elementorFrontend.hooks ) {
								popupData = elementorFrontend.hooks.applyFilters(
									'jet-popup/widget-extensions/popup-data',
									popupData,
									widgetData,
									$scope,
									event
								);
							}

							$( window ).trigger( {
								type: 'jet-popup-open-trigger',
								popupData: popupData
							} );

							return false;
						} );
						break;
					case 'click':
						$scope.on( 'click.JetPopup', '.elementor-button, .jet-button__instance .jet-popup-action-button__instance', function( event ) {
							event.preventDefault();

							if ( window.elementorFrontend.hooks ) {
								popupData = elementorFrontend.hooks.applyFilters(
									'jet-popup/widget-extensions/popup-data',
									popupData,
									widgetData,
									$scope,
									event
								);
							}

							$( window ).trigger( {
								type: 'jet-popup-open-trigger',
								popupData: popupData
							} );

							return false;
						} );
						break;
					case 'click-selector':

						if ( '' !== customSelector ) {
							$( customSelector ).addClass( 'jet-popup-cursor-pointer' );

							$scope.on( 'click.JetPopup', customSelector, function( event ) {
								event.preventDefault();

								var $target = $( event.currentTarget );

								$target.addClass( 'jet-popup-cursor-pointer' );

								if ( window.elementorFrontend.hooks ) {
									popupData = elementorFrontend.hooks.applyFilters(
										'jet-popup/widget-extensions/popup-data',
										popupData,
										widgetData,
										$scope,
										event
									);
								}

								$( window ).trigger( {
									type: 'jet-popup-open-trigger',
									popupData: popupData
								} );

								return false;
							} );
						}
						break;
					case 'hover':
						$scope.on( 'mouseenter.JetPopup', function( event ) {

							if ( window.elementorFrontend.hooks ) {
								popupData = elementorFrontend.hooks.applyFilters(
									'jet-popup/widget-extensions/popup-data',
									popupData,
									widgetData,
									$scope,
									event
								);
							}

							$( window ).trigger( {
								type: 'jet-popup-open-trigger',
								popupData: popupData
							} );
						} );
						break;
					case 'scroll-to':
						elementorFrontend.waypoint( $scope, function( event ) {

							if ( window.elementorFrontend.hooks ) {
								popupData = elementorFrontend.hooks.applyFilters(
									'jet-popup/widget-extensions/popup-data',
									popupData,
									widgetData,
									$scope,
									event
								);
							}

							$( window ).trigger( {
								type: 'jet-popup-open-trigger',
								popupData: popupData
							} );
						}, {
							offset: 'bottom-in-view'
						} );
						break;
				}

			}

		},

		widgetPopupActionButton: function( $scope ) {
			var $button    = $( '.jet-popup-action-button__instance', $scope ),
				settings   = $button.data( 'settings' ),
				actionType = settings['action-type'];

			switch ( actionType ) {

				case 'link':

					$button.on( 'click.JetPopup', function( event ) {
						event.preventDefault();

						var $currentPopup = $button.closest( '.jet-popup ' ),
							link          = $( this ).attr( 'href' ),
							popupId       = $currentPopup.attr( 'id' );

						$( window ).trigger( {
							type: 'jet-popup-close-trigger',
							popupData: {
								popupId: popupId,
								constantly: false
							}
						} );

						window.location = link;

						return false;
					} );
				break;

				case 'leave':
					$button.on( 'click.JetPopup', function( event ) {
						event.preventDefault();

						window.history.back();
					} );
				break;

				case 'close-popup':
					$button.on( 'click.JetPopup', function( event ) {
						event.preventDefault();

						var $currentPopup = $button.closest( '.jet-popup ' ),
							popupId = $currentPopup.attr( 'id' );

						$( window ).trigger( {
							type: 'jet-popup-close-trigger',
							popupData: {
								popupId: popupId,
								constantly: false
							}
						} );
					} );
				break;

				case 'close-constantly':
					$button.on( 'click.JetPopup', function( event ) {
						event.preventDefault();

						var $currentPopup = $button.closest( '.jet-popup ' ),
							popupId = $currentPopup.attr( 'id' );

						$( window ).trigger( {
							type: 'jet-popup-close-trigger',
							popupData: {
								popupId: popupId,
								constantly: true
							}
						} );
					} );
				break;
			}
		},

		widgetPopupMailchimp: function( $scope ) {
			var $target               = $scope.find( '.jet-popup-mailchimp' ),
				scoreId               = $scope.data( 'id' ),
				settings              = $target.data( 'settings' ),
				$subscribeForm        = $( '.jet-popup-mailchimp__form', $target ),
				$fields               = $( '.jet-popup-mailchimp__fields', $target ),
				$mailField            = $( '.jet-popup-mailchimp__mail-field', $target ),
				$inputData            = $mailField.data( 'instance-data' ),
				$submitButton         = $( '.jet-popup-mailchimp__submit', $target ),
				$subscribeFormMessage = $( '.jet-popup-mailchimp__message', $target ),
				invalidMailMessage    = 'Please specify a valid email',
				timeout               = null,
				ajaxRequest           = null,
				$currentPopup         = $target.closest( '.jet-popup' );

			$mailField.on( 'focus', function() {
				$mailField.removeClass( 'mail-invalid' );
			} );

			$( document ).keydown( function( event ) {

				if ( 13 === event.keyCode && $mailField.is( ':focus' ) ) {
					subscribeHandle();

					return false;
				}
			} );

			$submitButton.on( 'click', function() {
				subscribeHandle();

				return false;
			} );

			self.subscribeHandle = function() {
				var inputValue     = $mailField.val(),
					sendData       = {
						'email': inputValue,
						'target_list_id': settings['target_list_id'] || '',
						'data': $inputData
					},
					serializeArray = $subscribeForm.serializeArray(),
					additionalFields = {};

				if ( validateEmail( inputValue ) ) {

					$.each( serializeArray, function( key, fieldData ) {
						if ( 'email' === fieldData.name ) {
							sendData[ fieldData.name ] = fieldData.value;
						} else {
							additionalFields[ fieldData.name ] = fieldData.value;
						}
					} );

					sendData['additional'] = additionalFields;

					ajaxRequest = jQuery.ajax( {
						type: 'POST',
						url: window.jetPopupData.ajax_url,
						data: {
							'action': 'jet_popup_mailchimp_ajax',
							'data': sendData
						},
						beforeSend: function( jqXHR, ajaxSettings ) {
							if ( null !== ajaxRequest ) {
								ajaxRequest.abort();
							}
						},
						error: function( jqXHR, ajaxSettings ) {

						},
						success: function( data, textStatus, jqXHR ) {
							var successType   = data.type,
								message       = data.message || '',
								responceClass = 'jet-popup-mailchimp--response-' + successType;

							$submitButton.removeClass( 'loading' );

							$target.removeClass( 'jet-popup-mailchimp--response-error' );
							$target.addClass( responceClass );

							$( 'span', $subscribeFormMessage ).html( message );
							$subscribeFormMessage.css( { 'visibility': 'visible' } );

							timeout = setTimeout( function() {
								$subscribeFormMessage.css( { 'visibility': 'hidden' } );
								$target.removeClass( responceClass );
							}, 10000 );

							if ( settings['redirect'] ) {
								window.location.href = settings['redirect_url'];
							}

							$( window ).trigger( {
								type: 'jet-popup/mailchimp',
								elementId: scoreId,
								successType: successType,
								inputData: $inputData
							} );

							if ( true === settings['close_popup_when_success'] && $currentPopup[0] && 'success' === successType ) {
								var popupId = $currentPopup.attr( 'id' );

								timeout = setTimeout( function() {
									$( window ).trigger( {
										type: 'jet-popup-close-trigger',
										popupData: {
											popupId: popupId,
											constantly: false
										}
									} );
								}, 3000 );

							}
						}
					} );


					$submitButton.addClass( 'loading' );
				} else {
					$mailField.addClass( 'mail-invalid' );

					$target.addClass( 'jet-popup-mailchimp--response-error' );
					$( 'span', $subscribeFormMessage ).html( invalidMailMessage );
					$subscribeFormMessage.css( { 'visibility': 'visible' } );

					timeout = setTimeout( function() {
						$target.removeClass( 'jet-popup-mailchimp--response-error' );
						$subscribeFormMessage.css( { 'visibility': 'hidden' } );
						$mailField.removeClass( 'mail-invalid' );
					}, 10000 );
				}
			}

			function validateEmail( email ) {
				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

				return re.test( email );
			}
		}
	};

	/**
	 * [jetPopup description]
	 * @param  {[type]} $popup   [description]
	 * @param  {[type]} settings [description]
	 * @return {[type]}          [description]
	 */
	window.jetPopup = function( $popup, settings ) {
		var self                   = this,
			$window                = $( window ),
			$document              = $( document ),
			popupSettings          = settings,
			id                     = popupSettings['id'],
			popupId                = popupSettings['jet-popup-id'],
			popupsLocalStorageData = {},
			editMode               = Boolean( elementorFrontend.isEditMode() ),
			isAnimation            = false,
			isOpen                 = false,
			ajaxGetContentHanler   = null,
			ajaxContentLoad        = true;

		self.init = function() {

			var popupAvailable = self.popupAvailableCheck();

			if ( ! popupAvailable || editMode ) {
				return false;
			}

			self.setLocalStorageData( popupId, 'enable' );

			self.initCompatibilityHandler();

			self.initOpenEvent();

			self.initCloseEvent();
		};

		/**
		 * [popupAvailableCheck description]
		 * @return {[type]} [description]
		 */
		self.popupAvailableCheck = function() {
			var storageData = self.getLocalStorageData() || {};

			if ( ! storageData.hasOwnProperty( popupId ) ) {
				return true;
			}

			var popupData     = storageData[ popupId ],
				status        = 'enable',
				showAgainDate = 'none';

			if ( 'disable' === popupData ) {
				return false;
			}

			if ( 'enable' === popupData ) {
				return true;
			}

			if ( popupData.hasOwnProperty( 'status' ) ) {
				status = popupData['status'];
			}

			if ( 'enable' === status ) {
				return true;
			}

			if ( popupData.hasOwnProperty( 'show-again-date' ) ) {
				showAgainDate = popupData['show-again-date'];
			}

			if ( 'none' === showAgainDate && 'disable' === status ) {
				return false;
			}

			if ( showAgainDate < Date.now() ) {
				return true;
			} else {
				return false;
			}
		};

		/**
		 * [initOpenEvent description]
		 * @return {[type]} [description]
		 */
		self.initOpenEvent = function() {

			switch ( popupSettings['open-trigger'] ) {
				case 'page-load':

					self.pageLoadEvent( popupSettings['page-load-delay'] );
					break;
				case 'user-inactive':

					self.userInactiveEvent( popupSettings['user-inactivity-time'] );
					break;
				case 'scroll-trigger':

					self.scrollPageEvent( popupSettings['scrolled-to'] );
					break;
				case 'try-exit-trigger':

					self.tryExitEvent();
					break;

				case 'on-date':

					self.onDateEvent( popupSettings['on-date'] );
					break;

				case 'custom-selector':

					self.onCustomSelector( popupSettings['custom-selector'] );
					break;
			}

			$window.on( 'jet-popup-open-trigger', function( event ) {
				var popupData   = event.popupData || {},
					popupUniqId = popupData.popupId || false;

				if ( popupUniqId == popupId ) {
					self.showPopup( popupData );
				}
			});

			$window.on( 'jet-popup-close-trigger', function( event ) {
				var popupData   = event.popupData || {},
					popupUniqId = popupData.popupId,
					constantly  = popupData.constantly;

				if ( popupUniqId == popupId ) {
					self.hidePopup( {
						constantly: constantly
					} );
				}
			});
		};

		/**
		 * [initCloseEvent description]
		 * @return {[type]} [description]
		 */
		self.initCloseEvent = function() {
			$popup.on( 'click', '.jet-popup__close-button', function( event ) {
				var target = event.currentTarget;

				self.hidePopup( {
					constantly: popupSettings['show-once']
				} );
			} );

			$popup.on( 'click', '.jet-popup__overlay', function( event ) {
				var target = event.currentTarget;

				self.hidePopup( {
					constantly: popupSettings['show-once']
				} );
			} );

			$document.on( 'keyup.jetPopup', function( event ) {
				var key = event.keyCode;

				if ( 27 === key && isOpen ) {
					self.hidePopup( {
						constantly: popupSettings['show-once']
					} );
				}
			} );
		};

		/**
		 * [initCompatibilityHandler description]
		 * @return {[type]} [description]
		 */
		self.initCompatibilityHandler = function() {
			var $elementorProFormWidget = $( '.elementor-widget-form', $popup );

			if ( $elementorProFormWidget[0] ) {
				$elementorProFormWidget.each( function() {
					var $this = $( this ),
						$form = $( '.elementor-form', $this );

					$form.on( 'submit_success', function( data ) {

						setTimeout( function() {
							$window.trigger( {
								type: 'jet-popup-close-trigger',
								popupData: {
									popupId: popupId,
									constantly: false
								}
							} );
						}, 3000 );

					} );
				} );
			}
		};

		/**
		 * Page on load event
		 *
		 * @param  {int} openDelay Open delay time.
		 * @return {void}
		 */
		self.pageLoadEvent = function( openDelay ) {
			var delay = +openDelay || 0;

			delay = delay * 1000;

			$( document ).on( 'ready.jetPopup', function() {
				setTimeout( function() {
					self.showPopup();
				}, delay );
			} );
		};

		/**
		 * User Inactivity event
		 *
		 * @param  {int} inactiveDelay [description]
		 * @return {void}
		 */
		self.userInactiveEvent = function( inactiveDelay ) {
			var delay      = +inactiveDelay || 0,
				isInactive = true;

			delay = delay * 1000;

			setTimeout( function() {
				if ( isInactive ) {
					self.showPopup();
				}
			}, delay );

			$( document ).on( 'click focus resize keyup scroll', function() {
				isInactive = false;
			} );
		};

		/**
		 * Scrolling Page Event
		 *
		 * @param  {int} scrollingValue Scrolling porgress value
		 * @return {void}
		 */
		self.scrollPageEvent = function( scrollingValue ) {
			var scrolledValue  = +scrollingValue || 0;

			$window.on( 'scroll.cherryJetScrollEvent resize.cherryJetResizeEvent', function() {
				var $window          = $( window ),
					windowHeight     = $window.height(),
					documentHeight   = $( document ).height(),
					scrolledHeight   = documentHeight - windowHeight,
					scrolledProgress = Math.max( 0, Math.min( 1, $window.scrollTop() / scrolledHeight ) ) * 100;

				if ( scrolledProgress >= scrolledValue ) {
					$window.off( 'scroll.cherryJetScrollEvent resize.cherryJetResizeEvent' );
					self.showPopup();
				}
			} ).trigger( 'scroll.cherryJetResizeEvent' );
		};

		/**
		 * Viewport leave event
		 *
		 * @return {void}
		 */
		self.tryExitEvent = function() {
			var pageY = 0;

			$( document ).on( 'mouseleave', 'body', function( event ) {

				pageY = event.pageY - $window.scrollTop();

				if ( 0 > pageY && $popup.hasClass( 'jet-popup--hide-state' ) ) {
					self.showPopup();
				}
			} );
		};

		/**
		 * onDateEvent Event
		 *
		 * @return {void}
		 */
		self.onDateEvent = function( date ) {
			var nowDate   = Date.now(),
				startDate = Date.parse( date );

			if ( startDate < nowDate ) {

				setTimeout( function() {
					self.showPopup();
				}, 1000 );
			}
		}

		/**
		 * [onCustomSelector description]
		 * @param  {[type]} selector [description]
		 * @return {[type]}          [description]
		 */
		self.onCustomSelector = function( selector ) {
			var $selector = $( selector );

			if ( $selector[0] ) {
				$selector.on( 'click', function( event ) {
					event.preventDefault();

					self.showPopup();
				} );
			}
		}

		/**
		 * Show Popup
		 *
		 * @return {void}
		 */
		self.showPopup = function( data ) {
			var popupData              = data || {},
				animeOverlay           = null,
				animeContainer         = null,
				animeOverlaySettings   = jQuery.extend(
					{
						targets: $( '.jet-popup__overlay', $popup )[0]
					},
					self.avaliableEffects[ 'fade' ][ 'show' ]
				);

			animeOverlay = anime( animeOverlaySettings );

			$popup.toggleClass( 'jet-popup--hide-state jet-popup--show-state' );

			// Get Ajax Content
			self.renderContent( popupData );
		};

		/**
		 * [renderContent description]
		 * @return {[type]} [description]
		 */
		self.renderContent = function( data ) {
			var popupData        = data || {},
				popupDefaultData = {
					forceLoad: popupSettings['force-ajax'] || false, // Trigger Ajax Every Time
					customContent: '' // Show Popup with Custom Content
				},
				animeContainerInstance   = null,
				$popupContainer  = $( '.jet-popup__container', $popup ),
				$content         = $( '.jet-popup__container-content', $popup ),
				animeContainer   = jQuery.extend(
					{
						targets: $( '.jet-popup__container', $popup )[0],
						begin: function( anime ) {
							isAnimation = true;

							$window.trigger( 'jet-popup/show-event/before-show', {
								self: self,
								data: popupData,
								anime: anime
							} );
						},
						complete: function( anime ) {
							isAnimation = false;
							isOpen      = true;

							$window.trigger( 'jet-popup/show-event/after-show', {
								self: self,
								data: popupData,
								anime: anime
							} );
						}
					},
					self.avaliableEffects[ popupSettings['animation'] ][ 'show' ]
				);

			popupData = jQuery.extend( popupDefaultData, popupData );

			if ( '' !== popupData.customContent ) {
				$content.html( popupData.customContent );
				self.elementorFrontendInit();

				// Show Popup Container
				animeContainerInstance = anime( animeContainer );

				$window.trigger( 'jet-popup/render-content/render-custom-content', {
					self: self,
					popup_id: id,
					data: popupData,
				} );

				return false;
			}

			if ( popupData.forceLoad ) {
				ajaxContentLoad = true;
			}

			if ( ! popupSettings['use-ajax'] || ! ajaxContentLoad ) {

				// Show Popup Container
				animeContainerInstance = anime( animeContainer );

				$window.trigger( 'jet-popup/render-content/show-content', {
					self: self,
					popup_id: id,
					data: popupData,
				} );

				return false;
			}

			popupData = jQuery.extend( popupData, {
				'popup_id': id
			} );

			ajaxGetContentHanler = jQuery.ajax( {
				type: 'POST',
				url: window.jetPopupData.ajax_url,
				data: {
					'action': 'jet_popup_get_content',
					'data': popupData
				},
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== ajaxGetContentHanler ) {
						ajaxGetContentHanler.abort();
					}

					// Before ajax send Trigger
					$window.trigger( 'jet-popup/render-content/ajax/before-send', {
						self: self,
						popup_id: id,
						data: popupData
					} );

					$popup.addClass( 'jet-popup--loading-state' );
				},
				error: function( jqXHR, ajaxSettings ) {},
				success: function( data, textStatus, jqXHR ) {
					var successType = data.type,
						content     = data.content || '';

					$popup.removeClass( 'jet-popup--loading-state' );

					if ( 'error' === successType ) {
						var message = data.message;

						$content.html( '<h3>' + message + '</h3>' );

						// Show Popup Container
						animeContainerInstance = anime( animeContainer );
					}

					if ( 'success' === successType ) {
						$content.html( content );

						ajaxContentLoad = false;

						self.elementorFrontendInit();

						// Show Popup Container
						animeContainerInstance = anime( animeContainer );

						// Ajax Success Trigger
						$window.trigger( 'jet-popup/render-content/ajax/success', {
							self: self,
							popup_id: id,
							data: popupData,
							request: data
						} );
					}
				}
			} );
		};

		/**
		 * Hide Popup
		 *
		 * @return {void}
		 */
		self.hidePopup = function ( data ) {
			var popupData              = data || {},
				$content               = $( '.jet-popup__container-content', $popup ),
				constantly             = popupData.constantly || false,
				animeOverlay           = null,
				animeContainer         = null,
				animeOverlaySettings   = jQuery.extend( { targets: $( '.jet-popup__overlay', $popup )[0] }, self.avaliableEffects[ 'fade' ][ 'hide' ] ),
				animeContainerSettings = jQuery.extend(
					{
						targets: $( '.jet-popup__container', $popup )[0],
						begin: function( anime ) {
							isAnimation = true;

							$window.trigger( 'jet-popup/hide-event/before-hide', {
								self: self,
								data: popupData,
								anime: anime
							} );
						},
						complete: function( anime ) {
							isAnimation = false;
							isOpen = false;
							$popup.toggleClass( 'jet-popup--show-state jet-popup--hide-state' );

							if ( popupSettings['force-ajax'] ) {
								$content.html( '' );
							}

							// After Popup Hide Action
							$window.trigger( 'jet-popup/hide-event/after-hide', {
								self: self,
								data: popupData,
								anime: anime
							} );
						}
					},
					self.avaliableEffects[ popupSettings['animation'] ][ 'hide' ]
				);

			if ( constantly ) {
				self.setLocalStorageData( popupId, 'disable' );
			}

			if ( isAnimation ){
				return false;
			}

			if ( $popup.hasClass('jet-popup--show-state') ) {
				animeOverlay = anime( animeOverlaySettings );
				animeContainer = anime( animeContainerSettings );
			}

			// On Hide Handler
			self.onHidePopupAction();

			// Before Popup Hide Action
			$window.trigger( 'jet-popup/close-hide-event/before-hide', {
				self: self,
				data: popupData
			} );
		};

		/**
		 * [elementorFrontendInit description]
		 * @return {[type]} [description]
		 */
		self.elementorFrontendInit = function() {
			var $content = $( '.jet-popup__container-content', $popup );

			$content.find( 'div[data-element_type]' ).each( function() {
				var $this       = $( this ),
					elementType = $this.data( 'element_type' );

				if (!elementType) {
					return;
				}

				try {
					if( 'widget' === elementType ){
						elementType = $this.data( 'widget_type' );
						window.elementorFrontend.hooks.doAction( 'frontend/element_ready/widget', $this, $ );
					}
					window.elementorFrontend.hooks.doAction( 'frontend/element_ready/' + elementType, $this, $ );

				} catch( err ) {
					console.log(err);

					$this.remove();

					return false;
				}

			});

			// On Show Handler
			self.onShowPopupAction();
		}

		/**
		 * [onShowPopupAction description]
		 * @return {[type]} [description]
		 */
		self.onShowPopupAction = function() {

		};

		/**
		 * [onHidePopupAction description]
		 * @return {[type]} [description]
		 */
		self.onHidePopupAction = function() {

		};

		/**
		 * Avaliable Effects
		 */
		self.avaliableEffects = {
			'fade' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 600,
						easing: 'easeOutQuart',
					},
				},
				'hide': {
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
						easing: 'easeOutQuart',
						duration: 400,
					},
				}
			},

			'zoom-in' : {
				'show': {
					duration: 500,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 0, 1 ],

					},
					scale: {
						value: [ 0.75, 1 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					scale: {
						value: [ 1, 0.75 ],
					}
				}
			},

			'zoom-out' : {
				'show': {
					duration: 500,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 0, 1 ],

					},
					scale: {
						value: [ 1.25, 1 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					scale: {
						value: [ 1, 1.25 ],
					}
				}
			},

			'rotate' : {
				'show': {
					duration: 500,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 0, 1 ],

					},
					scale: {
						value: [ 0.75, 1 ],
					},
					rotate: {
						value: [ -65, 0 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					scale: {
						value: [ 1, 0.9 ],
					},
				}
			},

			'move-up' : {
				'show': {
					duration: 500,
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],

					},
					translateY: {
						value: [ 50, 1 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					translateY: {
						value: [ 1, 50 ],
					}
				}
			},

			'flip-x' : {
				'show': {
					duration: 500,
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],

					},
					rotateX: {
						value: [ 65, 0 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					}
				}
			},

			'flip-y' : {
				'show': {
					duration: 500,
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],

					},
					rotateY: {
						value: [ 65, 0 ],
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					}
				}
			},

			'bounce-in' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 500,
						easing: 'easeOutQuart',
					},
					scale: {
						value: [ 0.2, 1 ],
						duration: 800,
						elasticity: function(el, i, l) {
							return (400 + i * 200);
						},
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					scale: {
						value: [ 1, 0.8 ],
					}
				}
			},

			'bounce-out' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 500,
						easing: 'easeOutQuart',
					},
					scale: {
						value: [ 1.8, 1 ],
						duration: 800,
						elasticity: function(el, i, l) {
							return (400 + i * 200);
						},
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeOutQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					scale: {
						value: [ 1, 1.5 ],
					}
				}
			},

			'slide-in-up' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 400,
						easing: 'easeOutQuart',
					},
					translateY: {
						value: ['100vh', 0],
						duration: 750,
						easing: 'easeOutQuart',
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeInQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					translateY: {
						value: [0,'100vh'],
					}
				}
			},

			'slide-in-right' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 400,
						easing: 'easeOutQuart',
					},
					translateX: {
						value: ['100vw', 0],
						duration: 750,
						easing: 'easeOutQuart',
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeInQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					translateX: {
						value: [0,'100vw'],
					}
				}
			},

			'slide-in-down' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 400,
						easing: 'easeOutQuart',
					},
					translateY: {
						value: ['-100vh', 0],
						duration: 750,
						easing: 'easeOutQuart',
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeInQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					translateY: {
						value: [0,'-100vh'],
					}
				}
			},

			'slide-in-left' : {
				'show': {
					opacity: {
						value: [ 0, 1 ],
						duration: 400,
						easing: 'easeOutQuart',
					},
					translateX: {
						value: ['-100vw', 0],
						duration: 750,
						easing: 'easeOutQuart',
					}
				},
				'hide': {
					duration: 400,
					easing: 'easeInQuart',
					opacity: {
						value: [ 1, 0 ],
					},
					translateX: {
						value: [0,'-100vw'],
					}
				}
			}

		};

		/**
		 * Get localStorage data.
		 *
		 * @return {object|boolean}
		 */
		self.getLocalStorageData = function() {

			try {
				return JSON.parse( localStorage.getItem( 'jetPopupData' ) );
			} catch ( e ) {
				return false;
			}
		};

		/**
		 * Set localStorage data.
		 *
		 * @return {object|boolean}
		 */
		self.setLocalStorageData = function( id, status ) {

			var jetPopupData = self.getLocalStorageData() || {},
				newData      = {};

			newData['status'] = status;

			if ( 'disable' === status ) {

				var nowDate             = Date.now(),
					showAgainDelay      = popupSettings['show-again-delay'],
					showAgainDate       = 'none' !== showAgainDelay ? ( nowDate + showAgainDelay ) : 'none';

				newData['show-again-date'] = showAgainDate;
			}

			jetPopupData[ id ] = newData;

			localStorage.setItem( 'jetPopupData', JSON.stringify( jetPopupData ) );
		}

	}

	$( window ).on( 'elementor/frontend/init', JetPopup.init );

}( jQuery, window.elementorFrontend ) );
