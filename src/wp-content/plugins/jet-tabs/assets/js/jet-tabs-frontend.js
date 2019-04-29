( function( $, elementor, settings ) {

	'use strict';

	var JetTabs = {

		init: function() {
			var widgets = {
				'jet-tabs.default': JetTabs.tabsInit,
				'jet-accordion.default': JetTabs.accordionInit,
				'jet-image-accordion.default': JetTabs.imageAccordionInit,
				'jet-switcher.default': JetTabs.switcherInit,
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});
		},

		tabsInit: function( $scope ) {
			var $target         = $( '.jet-tabs', $scope ).first(),
				$widgetId       = $target.data( 'id' ),
				$window         = $( window ),
				$controlWrapper = $( '.jet-tabs__control-wrapper', $target ).first(),
				$contentWrapper = $( '.jet-tabs__content-wrapper', $target ).first(),
				$controlList    = $( '> .jet-tabs__control', $controlWrapper ),
				$contentList    = $( '> .jet-tabs__content', $contentWrapper ),
				settings        = $target.data( 'settings' ) || {},
				toogleEvents    = 'mouseenter mouseleave',
				scrollOffset,
				autoSwitchInterval = null,
				curentHash      = window.location.hash || false,
				tabsArray       = curentHash ? curentHash.replace( '#', '' ).split( '&' ) : false;

			if ( 'click' === settings['event'] ) {
				addClickEvent();
			} else {
				addMouseEvent();
			}

			if ( settings['autoSwitch'] ) {

				var startIndex        = settings['activeIndex'],
					currentIndex      = startIndex,
					controlListLength = $controlList.length;

				autoSwitchInterval = setInterval( function() {

					if ( currentIndex < controlListLength - 1 ) {
						currentIndex++;
					} else {
						currentIndex = 0;
					}

					switchTab( currentIndex );

				}, +settings['autoSwitchDelay'] );
			}

			$( window ).on( 'resize.jetTabs orientationchange.jetTabs', function() {
				$contentWrapper.css( { 'height': 'auto' } );
			} );

			function addClickEvent() {
				$controlList.on( 'click.jetTabs', function() {
					var $this = $( this ),
						tabId = +$this.data( 'tab' ) - 1;

					clearInterval( autoSwitchInterval );
					switchTab( tabId );
				});
			}

			function addMouseEvent() {
				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$controlList.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );

					$controlList.on( 'touchend', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' ) - 1;

						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}

						clearInterval( autoSwitchInterval );
						switchTab( tabId );
					} );

				} else {
					$controlList.on( 'mouseenter', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' ) - 1;

						clearInterval( autoSwitchInterval );
						switchTab( tabId );
					} );
				}
			}

			function switchTab( curentIndex ) {
				var $activeControl      = $controlList.eq( curentIndex ),
					$activeContent      = $contentList.eq( curentIndex ),
					activeContentHeight = 'auto',
					timer;

				$contentWrapper.css( { 'height': $contentWrapper.outerHeight( true ) } );

				$controlList.removeClass( 'active-tab' );
				$activeControl.addClass( 'active-tab' );

				$contentList.removeClass( 'active-content' );
				activeContentHeight = $activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrapper.css( 'border-top-width' ) ) + parseInt( $contentWrapper.css( 'border-bottom-width' ) );
				$activeContent.addClass( 'active-content' );

				$contentWrapper.css( { 'height': activeContentHeight } );

				$window.trigger( 'jet-tabs/tabs/show-tab-event/before', {
					widgetId: $widgetId,
					tabIndex: curentIndex,
				} );

				if ( timer ) {
					clearTimeout( timer );
				}

				timer = setTimeout( function() {
					$window.trigger( 'jet-tabs/tabs/show-tab-event/after', {
						widgetId: $widgetId,
						tabIndex: curentIndex,
					} );

					$contentWrapper.css( { 'height': 'auto' } );
				}, 500 );
			}

			// Hash Watch Handler
			if ( tabsArray ) {

				$controlList.each( function( index ) {
					var $this    = $( this ),
						id       = $this.attr( 'id' ),
						tabIndex = index;

					tabsArray.forEach( function( itemHash, i ) {
						if ( itemHash === id ) {
							switchTab( tabIndex );
						}
					} );

				} );
			}

			$( document ).on( 'click.jetTabAnchor', 'a[href*="#jet-tabs-control-"]', function( event ) {
				var $hash = $( this.hash );

				if ( ! $hash.closest( $scope )[0] ) {
					return;
				}

				var tabInx = $hash.data( 'tab' ) - 1;

				switchTab( tabInx );
			} );

		},// tabsInit end

		switcherInit: function( $scope ) {
			var $target          = $( '.jet-switcher', $scope ).first(),
				$widgetId        = $target.data( 'id' ),
				$window          = $( window ),
				$controlWrapper  = $( '.jet-switcher__control-wrapper', $target ).first(),
				$contentWrapper  = $( '.jet-switcher__content-wrapper', $target ).first(),
				$controlInstance = $( '> .jet-switcher__control-instance', $controlWrapper ),
				$controlList     = $( '> .jet-switcher__control-instance > .jet-switcher__control', $controlWrapper ),
				$contentList     = $( '> .jet-switcher__content', $contentWrapper ),
				$disableContent  = $( '> .jet-switcher__content--disable', $contentWrapper ),
				$enableContent   = $( '> .jet-switcher__content--enable', $contentWrapper ),
				state            = $target.hasClass( 'jet-switcher--disable' ),
				settings         = $target.data( 'settings' ) || {},
				toogleEvents     = 'mouseenter mouseleave',
				scrollOffset;

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				addTouchEvent();
			} else {
				addClickEvent();
			}

			$( window ).on( 'resize.jetSwitcher orientationchange.jetSwitcher', function() {
				$contentWrapper.css( { 'height': 'auto' } );
			} );

			function addClickEvent() {
				$controlInstance.on( 'click.jetSwitcher', function() {
					switchTab();
				});
			}

			function addTouchEvent() {
				$controlInstance.on( 'touchstart', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$controlInstance.on( 'touchend', function( event ) {
					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					switchTab();
				} );
			}

			function switchTab( curentIndex ) {
				var $activeContent,
					activeContentHeight = 'auto',
					timer;

				$contentWrapper.css( { 'height': $contentWrapper.outerHeight( true ) } );

				$target.toggleClass( 'jet-switcher--disable jet-switcher--enable' );

				if ( $target.hasClass( 'jet-switcher--disable' ) ) {
					state = false;
				} else {
					state = true;
				}

				$activeContent = ! state ? $contentList.eq(0) : $contentList.eq(1);

				$contentList.removeClass( 'active-content' );
				activeContentHeight = $activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrapper.css( 'border-top-width' ) ) + parseInt( $contentWrapper.css( 'border-bottom-width' ) );
				$activeContent.addClass( 'active-content' );

				$contentWrapper.css( { 'height': activeContentHeight } );

				$window.trigger( 'jet-tabs/switcher/show-case-event/before', {
					widgetId: $widgetId,
					caseIndex: curentIndex,
				} );

				if ( timer ) {
					clearTimeout( timer );
				}

				timer = setTimeout( function() {
					$window.trigger( 'jet-tabs/switcher/show-case-event/after', {
						widgetId: $widgetId,
						caseIndex: curentIndex,
					} );

					$contentWrapper.css( { 'height': 'auto' } );
				}, 500 );
			}
		},

		accordionInit: function( $scope ) {
			var $target       = $( '.jet-accordion', $scope ).first(),
				$widgetId     = $target.data( 'id' ),
				$window       = $( window ),
				$controlsList = $( '> .jet-accordion__inner > .jet-toggle > .jet-toggle__control', $target ),
				settings      = $target.data( 'settings' ),
				$toggleList   = $( '> .jet-accordion__inner > .jet-toggle', $target ),
				timer, timer2,
				curentHash    = window.location.hash || false,
				togglesArray  = curentHash ? curentHash.replace( '#', '' ).split( '&' ) : false;

			$( window ).on( 'resize.jetAccordion orientationchange.jetAccordion', function() {
				var activeToggle        = $( '> .jet-accordion__inner > .active-toggle', $target ),
					activeToggleContent = $( '> .jet-toggle__content', activeToggle );

				activeToggleContent.css( { 'height': 'auto' } );
			} );

			$controlsList.on( 'click.jetAccordion', function() {
				var $this       = $( this ),
					$toggle     = $this.closest( '.jet-toggle' ),
					toggleIndex = +$this.data( 'toggle' ) - 1;

				if ( settings['collapsible'] ) {

					if ( ! $toggle.hasClass( 'active-toggle' ) ) {

						$toggleList.each( function( index ) {
							var $this                = $( this ),
								$toggleContent       = $( '> .jet-toggle__content', $this ),
								$toggleContentHeight = $( '> .jet-toggle__content > .jet-toggle__content-inner', $this ).outerHeight();

							$toggleContentHeight += parseInt( $toggleContent.css( 'border-top-width' ) ) + parseInt( $toggleContent.css( 'border-bottom-width' ) );

							if ( index === toggleIndex ) {
								$this.addClass( 'active-toggle' );
								$toggleContent.css( { 'height': $toggleContentHeight } );

								$window.trigger( 'jet-tabs/accordion/show-toggle-event/before', {
									widgetId: $widgetId,
									toggleIndex: toggleIndex,
								} );

								if ( timer ) {
									clearTimeout( timer );
								}

								timer = setTimeout( function() {
									$window.trigger( 'jet-tabs/accordion/show-toggle-event/after', {
										widgetId: $widgetId,
										toggleIndex: toggleIndex,
									} );

									$toggleContent.css( { 'height': 'auto' } );
								}, 300 );

							} else {
								if ( $this.hasClass( 'active-toggle' ) ) {
									$toggleContent.css( { 'height': $toggleContent.outerHeight() } );
									$this.removeClass( 'active-toggle' );

									if ( timer2 ) {
										clearTimeout( timer2 );
									}

									timer2 = setTimeout( function() {
										$toggleContent.css( { 'height': 0 } );
									}, 5 );
								}
							}
						} );
					}
				} else {
					var $toggleContent = $( '> .jet-toggle__content', $toggle ),
						$toggleContentHeight = $( '> .jet-toggle__content > .jet-toggle__content-inner', $toggle ).outerHeight();

					$toggleContentHeight += parseInt( $toggleContent.css( 'border-top-width' ) ) + parseInt( $toggleContent.css( 'border-bottom-width' ) );

					$toggle.toggleClass( 'active-toggle' );

					if ( $toggle.hasClass( 'active-toggle') ) {
						$toggleContent.css( { 'height': $toggleContentHeight } );

						$window.trigger( 'jet-tabs/accordion/show-toggle-event/before', {
							widgetId: $widgetId,
							toggleIndex: toggleIndex,
						} );

						if ( timer ) {
							clearTimeout( timer );
						}

						timer = setTimeout( function() {
							$window.trigger( 'jet-tabs/accordion/show-toggle-event/after', {
								widgetId: $widgetId,
								toggleIndex: toggleIndex,
							} );

							$toggleContent.css( { 'height': 'auto' } );
						}, 300 );

					} else {
						$toggleContent.css( { 'height': $toggleContent.outerHeight() } );

						if ( timer2 ) {
							clearTimeout( timer2 );
						}

						timer2 = setTimeout( function() {
							$toggleContent.css( { 'height': 0 } );
						}, 5 );
					}
				}

			});

			// Hash Watch Handler
			if ( togglesArray ) {

				$controlsList.each( function( index ) {
					var $this    = $( this ),
						id       = $this.attr( 'id' ),
						toggleIndex = index;

					togglesArray.forEach( function( itemHash, i ) {
						if ( itemHash === id ) {
							$this.trigger('click.jetAccordion');
						}
					} );

				} );
			}

			$( document ).on( 'click.jetAccordionAnchor', 'a[href*="#jet-toggle-control-"]', function( event ) {
				var $hash = $( this.hash );

				if ( ! $hash.closest( $scope )[0] ) {
					return;
				}

				$hash.trigger( 'click.jetAccordion' );
			} );

		},// accordionInit end

		imageAccordionInit: function( $scope) {
			var $target  = $( '.jet-image-accordion', $scope ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );

			instance = new jetImageAccordion( $target, settings );
			instance.init();
		}// imageAccordionInit end

	};

	/**
	 * jetImageAccordion Class
	 *
	 * @return {void}
	 */
	window.jetImageAccordion = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$itemsList      = $( '.jet-image-accordion__item', $instance ),
			itemslength     = $itemsList.length,
			defaultSettings = {
				orientation: 'vertical',
				activeSize:  {
					size: 50,
					unit: '%'
				},
				duration: 500,
				activeItem: -1
			},
			settings        = settings || {},
			activeItem      = -1;

		/**
		 * Checking options, settings and options merging
		 */
		settings = $.extend( defaultSettings, settings );

		activeItem = settings['activeItem'];

		/**
		 * Layout Build
		 */
		this.layoutBuild = function( ) {

			$itemsList.css( {
				'transition-duration': settings.duration + 'ms'
			} );

			$itemsList.each( function( index ) {
				if ( index === activeItem ) {
					$( this ).addClass( 'active-accordion' );
					self.layoutRender();
				}
			} );

			$( '.jet-image-accordion__image-instance', $itemsList ).imagesLoaded().progress( function( instance, image ) {
				var $image      = $( image.img ),
					$parentItem = $image.closest( '.jet-image-accordion__item' ),
					$loader     = $( '.jet-image-accordion__item-loader', $parentItem );

				$image.addClass( 'loaded' );

				$loader.fadeTo( 250, 0, function() {
					$( this ).remove();
				} );
			});

			self.layoutRender();
			self.addEvents();
		}

		/**
		 * Layout Render
		 */
		this.layoutRender = function( $accordionItem ) {
			var $accordionItem = $accordionItem || false,
				activeSize     = settings.activeSize.size,
				basis          = ( 100 / itemslength ).toFixed(2),
				grow           = activeSize / ( ( 100 - activeSize  ) / ( itemslength - 1 ) );

			$( '.jet-image-accordion__item:not(.active-accordion)', $instance ).css( {
				'flex-grow': 1
			} );

			$( '.active-accordion', $instance ).css( {
				'flex-grow': grow
			} );
		}

		this.addEvents = function() {
			var toogleEvents = 'mouseenter',
				scrollOffset = $( window ).scrollTop();

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$itemsList.on( 'touchstart.jetImageAccordion', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$itemsList.on( 'touchend.jetImageAccordion', function( event ) {
					event.stopPropagation();

					var $this = $( this );

					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( ! $this.hasClass( 'active-accordion' ) ) {
						$itemsList.removeClass( 'active-accordion' );
						$this.addClass( 'active-accordion' );
					} else {
						$itemsList.removeClass( 'active-accordion' );
					}

					self.layoutRender();
				} );
			} else {
				$itemsList.on( 'mouseenter', function( event ) {
					var $this = $( this );

					if ( ! $this.hasClass( 'active-accordion' ) ) {
						$itemsList.removeClass( 'active-accordion' );
						$this.addClass( 'active-accordion' );
					}

					self.layoutRender();
				} );
			}

			$instance.on( 'mouseleave.jetImageAccordion', function( event ) {
				$itemsList.removeClass( 'active-accordion' );

				if ( -1 !== activeItem ) {
					$itemsList.eq( activeItem ).addClass( 'active-accordion' );
				}

				self.layoutRender();
			} );

			/*$( document ).on( 'touchend.jetImageAccordion', function( event ) {
				$itemsList.removeClass( 'active-accordion' );
				self.layoutRender();
			} );*/
		}

		/**
		 * Init
		 */
		this.init = function() {
			self.layoutBuild();
		}
	}

	$( window ).on( 'elementor/frontend/init', JetTabs.init );

}( jQuery, window.elementorFrontend, window.JetTabsSettings ) );
