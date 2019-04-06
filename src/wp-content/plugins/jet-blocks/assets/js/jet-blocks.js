( function( $, elementorFrontend, elementor ) {

	"use strict";

	var JetBlocks = {

		init: function() {

			var widgets = {
				'jet-nav-menu.default' : JetBlocks.navMenu,
				'jet-search.default' : JetBlocks.searchBox,
				'jet-auth-links.default' : JetBlocks.authLinks,
				'jet-hamburger-panel.default' : JetBlocks.hamburgerPanel,
				'jet-blocks-cart.default': JetBlocks.refreshCart
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			$( document )
				.on( 'click.jetBlocks', '.jet-search__popup-trigger', JetBlocks.searchPopupSwitch )
				.on( 'click.jetBlocks', '.jet-search__popup-close', JetBlocks.searchPopupSwitch );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/section', JetBlocks.setStickySection );

			$( document ).on( 'ready', JetBlocks.stickySection );
		},

		refreshCart: function( $scope ) {

			if ( ! elementor ) {
				return;
			}

			if ( ! window.JetBlocksEditor ) {
				return;
			}

			if ( ! window.JetBlocksEditor.activeSection ) {
				return;
			}

			var section = window.JetBlocksEditor.activeSection;
			var isCart = -1 !== [ 'cart_list_style', 'cart_list_items_style', 'cart_buttons_style' ].indexOf( section );

			if ( isCart ) {
				$scope.find( '.jet-blocks-cart' ).addClass( 'jet-cart-hover' );
			} else {
				$scope.find( '.jet-blocks-cart' ).removeClass( 'jet-cart-hover' );
			}

			$( '.widget_shopping_cart_content' ).empty();
			$( document.body ).trigger( 'wc_fragment_refresh' );

		},

		navMenu: function( $scope ) {

			if ( $scope.data( 'initialized' ) ) {
				return;
			}

			$scope.data( 'initialized', true );

			var hoverClass        = 'jet-nav-hover',
				hoverOutClass     = 'jet-nav-hover-out',
				mobileActiveClass = 'jet-mobile-menu-active';

			$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).hoverIntent({
				over: function() {
					$( this ).addClass( hoverClass );
				},
				out: function() {
					var $this = $( this );
					$this.removeClass( hoverClass );
					$this.addClass( hoverOutClass );
					setTimeout( function() {
						$this.removeClass( hoverOutClass );
					}, 200 );
				},
				timeout: 200,
				selector: '.menu-item-has-children'
			});

			if ( JetBlocks.mobileAndTabletCheck() ) {
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'touchstart.jetNavMenu', '.menu-item > a', touchStartItem );
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'touchend.jetNavMenu', '.menu-item > a', touchEndItem );

				$( document ).on( 'touchstart.jetNavMenu', prepareHideSubMenus );
				$( document ).on( 'touchend.jetNavMenu', hideSubMenus );
			} else {
				$scope.find( '.jet-nav:not(.jet-nav--vertical-sub-bottom)' ).on( 'click.jetNavMenu', '.menu-item > a', clickItem );
			}

			function touchStartItem( event ) {
				var $currentTarget = $( event.currentTarget ),
					$this = $currentTarget.closest( '.menu-item' );

				$this.data( 'offset', $( window ).scrollTop() );
				$this.data( 'elemOffset', $this.offset().top );
			}

			function touchEndItem( event ) {
				var $this,
					$siblingsItems,
					$link,
					$currentTarget,
					subMenu,
					offset,
					elemOffset,
					$hamburgerPanel;

				event.preventDefault();
				//event.stopPropagation();

				$currentTarget  = $( event.currentTarget );
				$this           = $currentTarget.closest( '.menu-item' );
				$siblingsItems  = $this.siblings( '.menu-item.menu-item-has-children' );
				$link           = $( '> a', $this );
				subMenu         = $( '.jet-nav__sub:first', $this );
				offset          = $this.data( 'offset' );
				elemOffset      = $this.data( 'elemOffset' );
				$hamburgerPanel = $this.closest( '.jet-hamburger-panel' );

				if ( offset !== $( window ).scrollTop() || elemOffset !== $this.offset().top ) {
					return false;
				}

				if ( $siblingsItems[0] ) {
					$siblingsItems.removeClass( hoverClass );
					$( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
				}

				if ( ! $( '.jet-nav__sub', $this )[0] || $this.hasClass( hoverClass ) ) {
					$link.trigger( 'click' ); // Need for a smooth scroll when clicking on an anchor link
					window.location.href = $link.attr( 'href' );

					if ( $scope.find( '.jet-nav-wrap' ).hasClass( mobileActiveClass ) ) {
						$scope.find( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
					}

					if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
						$hamburgerPanel.removeClass( 'open-state' );
						$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
					}

					return false;
				}

				if ( subMenu[0] ) {
					$this.addClass( hoverClass );
				}
			}

			function clickItem( event ) {
				var $currentTarget  = $( event.currentTarget ),
					$menuItem       = $currentTarget.closest( '.menu-item' ),
					$hamburgerPanel = $menuItem.closest( '.jet-hamburger-panel' );

				if ( ! $menuItem.hasClass( 'menu-item-has-children' ) || $menuItem.hasClass( hoverClass ) ) {

					if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
						$hamburgerPanel.removeClass( 'open-state' );
						$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
					}

				}
			}

			var scrollOffset;

			function prepareHideSubMenus( event ) {
				scrollOffset = $( window ).scrollTop();
			}

			function hideSubMenus( event ) {
				var $menu = $scope.find( '.jet-nav' );

				if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
					return;
				}

				if ( $( event.target ).closest( $menu ).length ) {
					return;
				}

				var $openMenuItems = $( '.menu-item-has-children.' + hoverClass, $menu );

				if ( ! $openMenuItems[0] ) {
					return;
				}

				$openMenuItems.removeClass( hoverClass );
				$openMenuItems.addClass( hoverOutClass );

				setTimeout( function() {
					$openMenuItems.removeClass( hoverOutClass );
				}, 200 );

				if ( $menu.hasClass( 'jet-nav--vertical-sub-bottom' ) ) {
					$( '.jet-nav__sub', $openMenuItems ).slideUp( 200 );
				}

				event.stopPropagation();
			}

			// START Vertical Layout: Sub-menu at the bottom
			$scope.find( '.jet-nav--vertical-sub-bottom' ).on( 'click.jetNavMenu', '.menu-item > a', verticalSubBottomHandler );

			function verticalSubBottomHandler( event ) {
				var $currentTarget  = $( event.currentTarget ),
					$menuItem       = $currentTarget.closest( '.menu-item' ),
					$siblingsItems  = $menuItem.siblings( '.menu-item.menu-item-has-children' ),
					$subMenu        = $( '.jet-nav__sub:first', $menuItem ),
					$hamburgerPanel = $menuItem.closest( '.jet-hamburger-panel' );

				if ( ! $menuItem.hasClass( 'menu-item-has-children' ) || $menuItem.hasClass( hoverClass ) ) {

					if ( $scope.find( '.jet-nav-wrap' ).hasClass( mobileActiveClass ) ) {
						$scope.find( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
					}

					if ( $hamburgerPanel[0] && $hamburgerPanel.hasClass( 'open-state' ) ) {
						$hamburgerPanel.removeClass( 'open-state' );
						$( 'html' ).removeClass( 'jet-hamburger-panel-visible' );
					}

					return;
				}

				event.preventDefault();
				event.stopPropagation();

				if ( $siblingsItems[0] ) {
					$siblingsItems.removeClass( hoverClass );
					$( '.menu-item-has-children', $siblingsItems ).removeClass( hoverClass );
					$( '.jet-nav__sub', $siblingsItems ).slideUp( 200 );
				}

				if ( $subMenu[0] ) {
					$subMenu.slideDown( 200 );
					$menuItem.addClass( hoverClass );
				}
			}

			$( document ).on( 'click.jetNavMenu', hideVerticalSubBottomMenus );

			function hideVerticalSubBottomMenus( event ) {
				if ( ! $scope.find( '.jet-nav' ).hasClass( 'jet-nav--vertical-sub-bottom' ) ) {
					return;
				}

				hideSubMenus( event );
			}
			// END Vertical Layout: Sub-menu at the bottom

			// Mobile trigger click event
			$( '.jet-nav__mobile-trigger', $scope ).on( 'click.jetNavMenu', function( event ) {
				$( this ).closest( '.jet-nav-wrap' ).toggleClass( mobileActiveClass );
			} );

			// START Mobile Layout: Left-side, Right-side
			if ( 'ontouchend' in window ) {
				$( document ).on( 'touchend.jetMobileNavMenu', removeMobileActiveClass );
			} else {
				$( document ).on( 'click.jetMobileNavMenu', removeMobileActiveClass );
			}

			function removeMobileActiveClass( event ) {
				var mobileLayout = $scope.find( '.jet-nav-wrap' ).data( 'mobile-layout' ),
					$navWrap     = $scope.find( '.jet-nav-wrap' ),
					$trigger     = $scope.find( '.jet-nav__mobile-trigger' ),
					$menu        = $scope.find( '.jet-nav' );

				if ( 'left-side' !== mobileLayout && 'right-side' !== mobileLayout ) {
					return;
				}

				if ( 'touchend' === event.type && scrollOffset !== $( window ).scrollTop() ) {
					return;
				}

				if ( $( event.target ).closest( $trigger ).length || $( event.target ).closest( $menu ).length ) {
					return;
				}

				if ( ! $navWrap.hasClass( mobileActiveClass ) ) {
					return;
				}

				$navWrap.removeClass( mobileActiveClass );

				event.stopPropagation();
			}

			$( '.jet-nav__mobile-close-btn', $scope ).on( 'click.jetMobileNavMenu', function( event ) {
				$( this ).closest( '.jet-nav-wrap' ).removeClass( mobileActiveClass );
			} );

			// END Mobile Layout: Left-side, Right-side

			// START Mobile Layout: Full-width
			var initMobileFullWidthCss = false;
			
			setFullWidthMenuPosition();
			$( window ).on( 'resize.jetMobileNavMenu', setFullWidthMenuPosition );

			function setFullWidthMenuPosition() {
				var mobileLayout = $scope.find( '.jet-nav-wrap' ).data( 'mobile-layout' );

				if ( 'full-width' !== mobileLayout ) {
					return;
				}
				
				var $menu = $scope.find( '.jet-nav' ),
					currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

				if ( 'mobile' !== currentDeviceMode ) {
					if ( initMobileFullWidthCss ) {
						$menu.css( { 'left': '' } );
						initMobileFullWidthCss = false;
					}
					return;
				}

				if ( initMobileFullWidthCss ) {
					$menu.css( { 'left': '' } );
				}

				var offset = - $menu.offset().left;

				$menu.css( { 'left': offset } );
				initMobileFullWidthCss = true;
			}
			// END Mobile Layout: Full-width

			if ( JetBlocks.isEditMode() ) {
				$scope.data( 'initialized', false );
			}
		},

		searchBox: function( $scope ) {

			JetBlocks.onSearchSectionActivated( $scope );

			$( document ).on( 'click.jetBlocks', function( event ) {

				var $widget       = $scope.find( '.jet-search' ),
					$popupToggle  = $( '.jet-search__popup-trigger', $widget ),
					$popupContent = $( '.jet-search__popup-content', $widget ),
					activeClass   = 'jet-search-popup-active',
					transitionOut = 'jet-transition-out';

				if ( $( event.target ).closest( $popupToggle ).length || $( event.target ).closest( $popupContent ).length ) {
					return;
				}

				if ( ! $widget.hasClass( activeClass ) ) {
					return;
				}

				$widget.removeClass( activeClass );
				$widget.addClass( transitionOut );
				setTimeout( function() {
					$widget.removeClass( transitionOut );
				}, 300 );

				event.stopPropagation();
			} );
		},

		onSearchSectionActivated: function( $scope ) {
			if ( ! elementor ) {
				return;
			}

			if ( ! window.JetBlocksEditor ) {
				return;
			}

			if ( ! window.JetBlocksEditor.activeSection ) {
				return;
			}

			var section = window.JetBlocksEditor.activeSection;

			var isPopup = -1 !== [ 'section_popup_style', 'section_popup_close_style', 'section_form_style' ].indexOf( section );

			if ( isPopup ) {
				$scope.find( '.jet-search' ).addClass( 'jet-search-popup-active' );
			} else {
				$scope.find( '.jet-search' ).removeClass( 'jet-search-popup-active' );
			}
		},

		authLinks: function( $scope ) {

			if ( ! elementor ) {
				return;
			}

			if ( ! window.JetBlocksEditor ) {
				return;
			}

			if ( ! window.JetBlocksEditor.activeSection ) {
				$scope.find( '.jet-auth-links__logout' ).css( 'display', 'none' );
				$scope.find( '.jet-auth-links__registered' ).css( 'display', 'none' );
				return;
			}

			var section      = window.JetBlocksEditor.activeSection;
			var isLogout     = -1 !== [ 'section_logout_link', 'section_logout_link_style' ].indexOf( section );
			var isRegistered = -1 !== [ 'section_registered_link', 'section_registered_link_style' ].indexOf( section );

			if ( isLogout ) {
				$scope.find( '.jet-auth-links__login' ).css( 'display', 'none' );
			} else {
				$scope.find( '.jet-auth-links__logout' ).css( 'display', 'none' );
			}

			if ( isRegistered ) {
				$scope.find( '.jet-auth-links__register' ).css( 'display', 'none' );
			} else {
				$scope.find( '.jet-auth-links__registered' ).css( 'display', 'none' );
			}
		},

		hamburgerPanel: function( $scope ) {
			var $panel        = $( '.jet-hamburger-panel', $scope ),
				$toggleButton = $( '.jet-hamburger-panel__toggle', $scope ),
				$cover        = $( '.jet-hamburger-panel__cover', $scope ),
				$inner        = $( '.jet-hamburger-panel__inner', $scope ),
				$closeButton  = $( '.jet-hamburger-panel__close-button', $scope ),
				scrollOffset,
				timer,
				editMode      = Boolean( elementorFrontend.isEditMode() ),
				$html         = $( 'html' );

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$toggleButton.on( 'touchstart', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$toggleButton.on( 'touchend', function( event ) {

					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( timer ) {
						clearTimeout( timer );
					}

					if ( ! $panel.hasClass( 'open-state' ) ) {
						timer = setTimeout( function() {
							$panel.addClass( 'open-state' );
						}, 10 );
						$html.addClass( 'jet-hamburger-panel-visible' );
					} else {
						$panel.removeClass( 'open-state' );
						$html.removeClass( 'jet-hamburger-panel-visible' );
					}
				} );

			} else {
				$toggleButton.on( 'click', function( event ) {

					if ( ! $panel.hasClass( 'open-state' ) ) {
						$panel.addClass( 'open-state' );
						$html.addClass( 'jet-hamburger-panel-visible' );
					} else {
						$panel.removeClass( 'open-state' );
						$html.removeClass( 'jet-hamburger-panel-visible' );
					}
				} );
			}

			$closeButton.on( 'click', function( event ) {

				if ( ! $panel.hasClass( 'open-state' ) ) {
					$panel.addClass( 'open-state' );
					$html.addClass( 'jet-hamburger-panel-visible' );
				} else {
					$panel.removeClass( 'open-state' );
					$html.removeClass( 'jet-hamburger-panel-visible' );
				}
			} );

			$( document ).on( 'click.JetHamburgerPanel', function( event ) {
				if ( $( event.target ).closest( $toggleButton ).length || $( event.target ).closest( $inner ).length ) {
					return;
				}

				if ( ! $panel.hasClass( 'open-state' ) ) {
					return;
				}

				$panel.removeClass( 'open-state' );

				if ( ! $( event.target ).closest( '.jet-hamburger-panel__toggle' ).length ) {
					$html.removeClass( 'jet-hamburger-panel-visible' );
				}

				event.stopPropagation();
			} );

		},

		searchPopupSwitch: function( event ) {

			//event.stopPropagation();

			var $this         = $( this ),
				$widget       = $this.closest( '.jet-search' ),
				$input        = $( '.jet-search__field', $widget ),
				activeClass   = 'jet-search-popup-active',
				transitionIn  = 'jet-transition-in',
				transitionOut = 'jet-transition-out';

			if ( ! $widget.hasClass( activeClass ) ) {
				$widget.addClass( transitionIn );
				setTimeout( function() {
					$widget.removeClass( transitionIn );
					$widget.addClass( activeClass );
				}, 300 );
				$input.focus();
			} else {
				$widget.removeClass( activeClass );
				$widget.addClass( transitionOut );
				setTimeout( function() {
					$widget.removeClass( transitionOut );
				}, 300 );
			}
		},

		stickySection: function() {
			var stickySection = {

				isEditMode: Boolean( elementorFrontend.isEditMode() ),

				correctionSelector: $( '#wpadminbar' ),

				initDesktop: false,
				initTablet:  false,
				initMobile:  false,

				init: function() {
					if ( this.isEditMode ) {
						return;
					}

					this.run();
					$( window ).on( 'resize.JetStickySection orientationchange.JetStickySection', this.run.bind( this ) );
				},

				getOffset: function(){
					var offset = 0;

					if ( this.correctionSelector[0] && 'fixed' === this.correctionSelector.css( 'position' ) ) {
						offset = this.correctionSelector.outerHeight( true );
					}

					return offset;
				},

				run: function() {
					var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
						transitionIn  = 'jet-sticky-transition-in',
						transitionOut = 'jet-sticky-transition-out',
						options = {
							stickyClass: 'jet-sticky-section--stuck',
							topSpacing: this.getOffset()
						};

					function initSticky ( section, options ) {
						section.jetStickySection( options )
							.on( 'jetStickySection:stick', function( event ) {
								$( event.target ).addClass( transitionIn );
								setTimeout( function() {
									$( event.target ).removeClass( transitionIn );
								}, 3000 );
							} )
							.on( 'jetStickySection:unstick', function( event ) {
								$( event.target ).addClass( transitionOut );
								setTimeout( function() {
									$( event.target ).removeClass( transitionOut );
								}, 3000 );
							} );
						section.trigger( 'jetStickySection:activated' );
					}

					if ( 'desktop' === currentDeviceMode && ! this.initDesktop ) {
						if ( this.initTablet ) {
							JetBlocks.getStickySectionsTablet.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initTablet = false;
						}

						if ( this.initMobile ) {
							JetBlocks.getStickySectionsMobile.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initMobile = false;
						}

						if ( JetBlocks.getStickySectionsDesktop[0] ) {
							JetBlocks.getStickySectionsDesktop.forEach( function( section, i ) {

								if ( JetBlocks.getStickySectionsDesktop[i+1] ) {
									options.stopper = JetBlocks.getStickySectionsDesktop[i+1];
								} else {
									options.stopper = '';
								}

								initSticky( section, options );
							});

							this.initDesktop = true;
						}
					}

					if ( 'tablet' === currentDeviceMode && ! this.initTablet ) {
						if ( this.initDesktop ) {
							JetBlocks.getStickySectionsDesktop.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initDesktop = false;
						}

						if ( this.initMobile ) {
							JetBlocks.getStickySectionsMobile.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initMobile = false;
						}

						if ( JetBlocks.getStickySectionsTablet[0] ) {
							JetBlocks.getStickySectionsTablet.forEach( function( section, i ) {
								if ( JetBlocks.getStickySectionsTablet[i+1] ) {
									options.stopper = JetBlocks.getStickySectionsTablet[i+1];
								} else {
									options.stopper = '';
								}

								initSticky( section, options );
							});

							this.initTablet = true;
						}
					}

					if ( 'mobile' === currentDeviceMode && ! this.initMobile ) {
						if ( this.initDesktop ) {
							JetBlocks.getStickySectionsDesktop.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initDesktop = false;
						}

						if ( this.initTablet ) {
							JetBlocks.getStickySectionsTablet.forEach( function( section, i ) {
								section.trigger( 'jetStickySection:detach' );
							});

							this.initTablet = false;
						}

						if ( JetBlocks.getStickySectionsMobile[0] ) {
							JetBlocks.getStickySectionsMobile.forEach( function( section, i ) {

								if ( JetBlocks.getStickySectionsMobile[i+1] ) {
									options.stopper = JetBlocks.getStickySectionsMobile[i+1];
								} else {
									options.stopper = '';
								}

								initSticky( section, options );
							});

							this.initMobile = true;
						}
					}
				}
			};

			stickySection.init();
		},

		getStickySectionsDesktop: [],
		getStickySectionsTablet:  [],
		getStickySectionsMobile:  [],

		setStickySection: function( $scope ) {
			var setStickySection = {

				target: $scope,

				isEditMode: Boolean( elementorFrontend.isEditMode() ),

				init: function() {
					if ( this.isEditMode ) {
						return;
					}

					if (  'yes' === this.getSectionSetting( 'jet_sticky_section' ) ) {
						var availableDevices = this.getSectionSetting( 'jet_sticky_section_visibility' ) || [];

						if ( ! availableDevices[0] ) {
							return;
						}

						if ( -1 !== availableDevices.indexOf( 'desktop' ) ) {
							JetBlocks.getStickySectionsDesktop.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'tablet' ) ) {
							JetBlocks.getStickySectionsTablet.push( $scope );
						}

						if ( -1 !== availableDevices.indexOf( 'mobile' ) ) {
							JetBlocks.getStickySectionsMobile.push( $scope );
						}
					}
				},

				getSectionSetting: function( setting ){
					var settings = {},
		 				editMode = Boolean( elementorFrontend.isEditMode() );

					if ( editMode ) {
						if ( ! elementorFrontend.hasOwnProperty( 'config' ) ) {
							return;
						}

						if ( ! elementorFrontend.config.hasOwnProperty( 'elements' ) ) {
							return;
						}

						if ( ! elementorFrontend.config.elements.hasOwnProperty( 'data' ) ) {
							return;
						}

						var modelCID = this.target.data( 'model-cid' ),
							editorSectionData = elementorFrontend.config.elements.data[ modelCID ];

						if ( ! editorSectionData ) {
							return;
						}

						if ( ! editorSectionData.hasOwnProperty( 'attributes' ) ) {
							return;
						}

						settings = editorSectionData.attributes || {};
					} else {
						settings = this.target.data( 'settings' ) || {};
					}

					if ( ! settings[ setting ] ) {
						return;
					}

					return settings[ setting ];
				}
			};

			setStickySection.init();
		},

		mobileAndTabletCheck: function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		},

		isEditMode: function() {
			return Boolean( elementorFrontend.isEditMode() );
		}
	};

	$( window ).on( 'elementor/frontend/init', JetBlocks.init );

}( jQuery, window.elementorFrontend, window.elementor ) );
