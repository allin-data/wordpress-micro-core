// CherryPortfolioPlugin plugin
( function( $, CherryJsCore ) {
	var methods = {
		init: function( options ) {
			var settings = {
				call: function() {}
			};

			return this.each( function() {
				var $this                   = $( this ),
					$window                 = $( window ),
					popupSettings           = $this.data( 'popup-settings' ),
					popupsLocalStorageData  = getLocalStorageData() || {},
					popupAvailable          = popupsLocalStorageData[ popupSettings.id ] || 'enable',
					$showAgainCheck         = $( '.cherry-popup-show-again-check', $this ),
					$subscribeForm          = $( '.cherry-popup-subscribe', $this ),
					cherrySubscribeFormAjax = null,
					subscribeFormAjaxId     = 'cherry_subscribe_form_ajax',
					$subscribeFormMessage   = null,

					$loginForm              = $( '.cherry-popup-login', $this ),
					cherryLoginFormAjax     = null,
					loginFormAjaxId         = 'cherry_login_form_ajax',
					$loginFormMessage       = null,

					$registerForm           = $( '.cherry-popup-register', $this ),
					cherryRegisterFormAjax  = null;
					registerFormAjaxId      = 'cherry_register_form_ajax',
					$registerFormMessage    = null;

				if ( options ) {
					$.extend( settings, options );
				}

				( function() {
					if ( 'disable' === popupAvailable ) {
						$this.remove();
						return false;
					}

					// Check and create popup data in localStorage
					if ( ! popupsLocalStorageData.hasOwnProperty( popupSettings.id ) ) {
						popupsLocalStorageData[ popupSettings.id ] = 'enable';
					}

					setLocalStorageData( popupsLocalStorageData );

					switch ( popupSettings.use ) {
						case 'open-page':
							addOpenEventsFunction();
							break;
						case 'close-page':
							addCloseEventsFunction();
							break;
					}

					// Custom event for popup openning
					customEventsFunction();

					// Add check again button event
					if ( 'false' === popupSettings['show-once'] ) {
						checkEvents();
					} else {
						$showAgainCheck.remove();
						popupsLocalStorageData[ popupSettings.id ] = 'disable';
						setLocalStorageData( popupsLocalStorageData );
					}

					// Add close button event
					closePopupEvent();

					// Subscribe form init
					if ( $subscribeForm[0] ) {
						subscribeFormInit();
					}

					// Subscribe form init
					if ( $loginForm[0] ) {
						loginFormInit();
					}

					// Register form init
					if ( $registerForm[0] ) {
						registerFormInit();
					}

				} )();

				/*
				 * Add open events functions
				 *
				 * @return {void}
				 */
				function addOpenEventsFunction() {

					switch ( popupSettings['open-appear-event'] ) {
						case 'page-load':
							pageLoadEvent( popupSettings['load-open-delay'] );
							break;
						case 'user-inactive':
							userInactiveEvent( popupSettings['inactive-time'] );
							break;
						case 'scroll-page':
							scrollPageEvent( popupSettings['page-scrolling-value'] );
							break;
					}
				}

				/**
				 * Add close events functions
				 *
				 * @return {void}
				 */
				function addCloseEventsFunction() {

					switch ( popupSettings['close-appear-event'] ) {
						case 'outside-viewport':
							viewportLeaveEvent();
							break;
						case 'page-focusout':
							pageFocusoutEvent();
							break;
					}
				}

				/**
				 * Custom events functions
				 *
				 * @return {void}
				 */
				function customEventsFunction() {
					var eventType = popupSettings['custom-event-type'],
						selector  = popupSettings['popup-selector'];

					if ( '' === selector ) {
						return false;
					}

					switch( eventType ) {
						case 'click':
							$( document ).on( 'click', selector, function( event ) {
								event.preventDefault();
								showPopup();
							} )
							break;
						case 'hover':
							$( document ).on( 'mouseenter', selector, function( event ) {
								showPopup();
							} )
						break;
					}
				}

				/**
				 * Close button event.
				 *
				 * @return {void}
				 */
				function closePopupEvent() {
					var timeout = null;

					$this.on( 'click', '.cherry-popup-close-button', function( event ) {
						var button = event.currentTarget;

							$this.toggleClass( 'hide-animation show-animation' );

							clearTimeout( timeout );
							timeout = setTimeout( function() {
								hidePopup();
							}, 300 );
					} );

					$this.on( 'click', '.cherry-popup-overlay', function( event ) {
						var overlay = event.currentTarget;

							$this.toggleClass( 'hide-animation show-animation' );

							clearTimeout( timeout );
							timeout = setTimeout( function() {
								hidePopup();
							}, 300 );
					} );

					$this.on( 'click', '.cherry-popup-close-label', function( event ) {
						var overlay = event.currentTarget;

							$this.toggleClass( 'hide-animation show-animation' );

							clearTimeout( timeout );
							timeout = setTimeout( function() {
								hidePopup();
							}, 300 );
					} );

					$( document ).on( 'keyup', function( event ) {

						if ( ! $this.hasClass( 'show-animation' ) ) {
							return false;
						}

						if ( 27 == event.keyCode && $this.hasClass( 'show-animation' ) ) {
							$this.toggleClass( 'hide-animation show-animation' );

							clearTimeout( timeout );
							timeout = setTimeout( function() {
								hidePopup();
							}, 300 );
						}
					} );
				}

				/**
				 * Add check events function
				 *
				 * @return {void}
				 */
				function checkEvents() {
					$this.on( 'click', '.cherry-popup-show-again-check', function( event ) {
						var check = event.currentTarget,
							popupsLocalStorageData = getLocalStorageData() || {};

						if ( ! $( check ).hasClass( 'checked' ) ) {
							$( check ).addClass( 'checked' );
							popupsLocalStorageData[ popupSettings.id ] = 'disable';
						} else {
							$( check ).removeClass( 'checked' );
							popupsLocalStorageData[ popupSettings.id ] = 'enable';
						}

						setLocalStorageData( popupsLocalStorageData );

					} );
				}

				/**
				 * Show Popup
				 *
				 * @return {void}
				 */
				function showPopup() {
					var $popupList   = $( '.cherry-popup' );

					$popupList.each( function() {
						var $popup = $( this ),
							timeout = null;

						if ( $popup.hasClass( 'show-animation' ) ) {
							$popup.toggleClass( 'hide-animation show-animation' );

							clearTimeout( timeout );
							timeout = setTimeout( function() {
								$popup.toggleClass( 'waiting-status hide-animation' );
							}, 300 );
						}
					});

					$this.removeClass( 'waiting-status' );
					$this.addClass( 'show-animation' );

					if ( $( '.cherry-popup-login__input-user', $loginForm )[0] ) {
						$( '.cherry-popup-login__input-user', $loginForm ).focus();
					}

					if ( $( '.cherry-popup-register__input-login', $registerForm )[0] ) {
						$( '.cherry-popup-register__input-login', $registerForm ).focus();
					}
				}

				/**
				 * Hide Popup
				 *
				 * @return {void}
				 */
				function hidePopup() {
					$this.toggleClass( 'waiting-status hide-animation' );
				}

				/**
				 * Page on load event
				 *
				 * @param  {int} openDelay Open delay time.
				 * @return {void}
				 */
				function pageLoadEvent( openDelay ) {
					var delay = +openDelay || 0;

					delay = delay * 1000;

					$( document ).on( 'ready', function() {
						setTimeout( function() {
							showPopup();
						}, delay );

					} );
				}

				/**
				 * User Inactivity event
				 *
				 * @param  {int} inactiveDelay [description]
				 * @return {void}
				 */
				function userInactiveEvent( inactiveDelay ) {
					var delay      = +inactiveDelay || 0,
						isInactive = true;

					delay = delay * 1000;

					setTimeout( function() {
						if ( isInactive ) {
							showPopup();
						}
					}, delay );

					$( document ).on( 'click focus resize keyup scroll', function() {
						isInactive = false;
					} );
				}

				/**
				 * Scrolling Page Event
				 *
				 * @param  {int} scrollingValue Scrolling porgress value
				 * @return {void}
				 */
				function scrollPageEvent( scrollingValue ) {
					var scrolledValue  = +scrollingValue || 0;

					$( window ).on( 'scroll.cherryPopupScrollEvent resize.cherryPopupResizeEvent', function() {
						var $window          = $( window ),
							windowHeight     = $window.height(),
							documentHeight   = $( document ).height(),
							scrolledHeight   = documentHeight - windowHeight,
							scrolledProgress = Math.max( 0, Math.min( 1, $window.scrollTop() / scrolledHeight ) ) * 100;

						if ( scrolledProgress >= scrolledValue ) {
							$( window ).off( 'scroll.cherryPopupScrollEvent resize.cherryPopupResizeEvent' );
							showPopup();
						}
					} ).trigger( 'scroll.cherryPopupScrollEvent' );
				}

				/**
				 * Viewport leave event
				 *
				 * @return {void}
				 */
				function viewportLeaveEvent() {
					var pageY = 0;

					$( document ).on( 'mouseleave', 'body', function( event ) {
						if ( ! $( '.open-page-type' )[0] ) {
							pageY = event.pageY - $window.scrollTop();

							if ( 0 > pageY ) {
								showPopup();
							}
						}
					} );
				}

				/**
				 * Page focus out event.
				 *
				 * @return {void}
				 */
				function pageFocusoutEvent() {
					$( window ).on( 'blur', function() {
						if ( ! $( '.open-page-type' )[0] ) {
							showPopup();
						}
					} );
				}

				/*
				 * Subscribe Form Init
				 *
				 * @return {void}
				 */
				function subscribeFormInit() {
					$subscribeFormMessage = $( '.cherry-popup-subscribe__message', $subscribeForm );
					cherrySubscribeFormAjax = new CherryJsCore.CherryAjaxHandler(
						{
							handlerId: subscribeFormAjaxId,
							successCallback: subscribeFormAjaxSuccessCallback
						}
					);

					$subscribeForm.on( 'click', '.cherry-popup-subscribe__submit', subscribeFormAjax );
				}

				/**
				 * Subscribe submit form click event
				 *
				 * @param  {object} event Click event.
				 * @return {void}
				 */
				function subscribeFormAjax() {
					var $input     = $( '.cherry-popup-subscribe__input', $subscribeForm ),
						inputValue = $input.val();

					cherrySubscribeFormAjax.sendData( { 'mail': inputValue } );
				}

				/**
				 * Subscribe form ajax success callback
				 *
				 * @param  {object} data Success data.
				 * @return {void}
				 */
				function subscribeFormAjaxSuccessCallback( data ) {
					var successType = data.type,
						message     = data.message || '',
						timeout     = null;

					if ( 'success' === successType ) {
						$subscribeFormMessage.addClass( 'success-type' );
					}
					$( 'span', $subscribeFormMessage ).html( message );
					$subscribeFormMessage.slideDown( 300 );

					timeout = setTimeout( function() {
						$subscribeFormMessage.slideUp( 300, function() {
							$subscribeFormMessage.removeClass( 'success-type' );
							clearTimeout( timeout );
						} );
					}, 6000 );
				}

				/*
				 * Login Form Init
				 *
				 * @return {void}
				 */
				function loginFormInit() {
					$loginFormMessage = $( '.cherry-popup-login__message', $loginForm );

					cherryLoginFormAjax = new CherryJsCore.CherryAjaxHandler(
						{
							handlerId: loginFormAjaxId,
							successCallback: loginFormAjaxSuccessCallback
						}
					);

					$loginForm.on( 'click', '.cherry-popup-login__login-in', loginFormAjax );

					$loginForm.on( 'keyup', '.cherry-popup-login__input-user, .cherry-popup-login__input-pass', function( event ) {
						if ( 13 == ( event.keyCode ? event.keyCode : event.which ) ) {
							loginFormAjax();
						}
					} );

					$loginForm.on( 'click', '.cherry-popup-login__remember', function() {
						$( this ).toggleClass( 'checked' );
					} );
				}

				/**
				 * Login submit form click event
				 *
				 * @param  {object} event Click event.
				 * @return {void}
				 */
				function loginFormAjax() {
					var user     = $( '.cherry-popup-login__input-user', $loginForm ).val(),
						pass     = $( '.cherry-popup-login__input-pass', $loginForm ).val(),
						$remember = $( '.cherry-popup-login__remember', $loginForm ),
						data = {
							'user': user,
							'pass': pass,
							'remember': $remember.hasClass( 'checked' ) ? 'true' : 'false'
						};

					$( '.cherry-popup-spinner', $loginForm ).css( { 'display':'block'} );
					cherryLoginFormAjax.sendData( data );
				}

				/**
				 * Login form ajax success callback
				 *
				 * @param  {object} data Success data.
				 * @return {void}
				 */
				function loginFormAjaxSuccessCallback( data ) {
					var successType = data.type,
						message     = data.message || '',
						timeout     = null;

					$( '.cherry-popup-spinner', $loginForm ).css( { 'display':'none'} );

					if ( 'success' === successType ) {
						$loginFormMessage.addClass( 'success-type' );
						window.location.reload();
					}

					$( 'span', $loginFormMessage ).html( message );
					$loginFormMessage.slideDown( 300 );

					/*timeout = setTimeout( function() {
						$loginFormMessage.slideUp( 300, function() {
							$loginFormMessage.removeClass( 'success-type' );

							clearTimeout( timeout );
						} );
					}, 6000 );*/
				}

				/*
				 * Register Form Init
				 *
				 * @return {void}
				 */
				function registerFormInit() {
					$registerFormMessage = $( '.cherry-popup-register__message', $registerForm );

					cherryRegisterFormAjax = new CherryJsCore.CherryAjaxHandler( {
						handlerId: registerFormAjaxId,
						successCallback: registerFormAjaxSuccessCallback
					} );

					$registerForm.on( 'click', '.cherry-popup-register__sign-up', registerFormAjax );

					$registerForm.on( 'keyup', '.cherry-popup-register__input-login, .cherry-popup-login__input-mail', function( event ) {
						if ( 13 == ( event.keyCode ? event.keyCode : event.which ) ) {
							registerFormAjax();
						}
					} );

				}

				/**
				 * Register submit form click event
				 *
				 * @param  {object} event Click event.
				 * @return {void}
				 */
				function registerFormAjax() {
					var login = $( '.cherry-popup-register__input-login', $registerForm ).val(),
						mail  = $( '.cherry-popup-login__input-mail', $registerForm ).val(),
						data = {
							'login': login,
							'mail': mail
						};

					$( '.cherry-popup-spinner', $registerForm ).css( { 'display':'block' } );
					cherryRegisterFormAjax.sendData( data );
				}

				/**
				 * Register form ajax success callback
				 *
				 * @param  {object} data Success data.
				 * @return {void}
				 */
				function registerFormAjaxSuccessCallback( data ) {
					var $registerFormMessage = $( '.cherry-popup-register__message', $registerForm ),
						successType = data.type,
						message     = data.message || '',
						timeout     = null;

					$( '.cherry-popup-spinner', $registerForm ).css( { 'display':'none' } );

					if ( 'success' === successType ) {
						$registerFormMessage.addClass( 'success-type' );
					}

					$( 'span', $registerFormMessage ).html( message );
					$registerFormMessage.slideDown( 300 );

					/*timeout = setTimeout( function() {
						$registerFormMessage.slideUp( 300, function() {
							$registerFormMessage.removeClass( 'success-type' );

							clearTimeout( timeout );
						} );
					}, 6000 );*/
				}

				/**
				 * Get localStorage data.
				 *
				 * @return {object|boolean}
				 */
				function getLocalStorageData() {
					try {
						return JSON.parse( localStorage.getItem( 'popupsLocalStorageData' ) );
					} catch ( e ) {
						return false;
					}
				}

				/**
				 * Set localStorage data.
				 *
				 * @return {object|boolean}
				 */
				function setLocalStorageData( object ) {
					try {
						localStorage.setItem( 'popupsLocalStorageData', JSON.stringify( object ) );
					} catch ( e ) {
						return false;
					}
				}

			});
		},
		destroy: function() {},
		update: function() {}
	};

	$.fn.cherryPopupsPlugin = function( method ) {
		if ( methods[method] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		} else if ( 'object' === typeof method || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method with name ' + method + ' is not exist for jQuery.cherryPopupsPlugin' );
		}
	};

} )( jQuery, window.CherryJsCore );
