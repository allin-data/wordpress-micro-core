( function( $, elementor ) {

	"use strict";

	var JetElements = {

		init: function() {

			var widgets = {
				'jet-carousel.default' : JetElements.widgetCarousel,
				'jet-circle-progress.default' : JetElements.widgetProgress,
				'jet-map.default' : JetElements.widgetMap,
				'jet-countdown-timer.default' : JetElements.widgetCountdown,
				'jet-posts.default' : JetElements.widgetPosts,
				'jet-animated-text.default' : JetElements.widgetAnimatedText,
				'jet-animated-box.default' : JetElements.widgetAnimatedBox,
				'jet-images-layout.default' : JetElements.widgetImagesLayout,
				'jet-slider.default' : JetElements.widgetSlider,
				'jet-testimonials.default' : JetElements.widgetTestimonials,
				'jet-image-comparison.default' : JetElements.widgetImageComparison,
				'jet-instagram-gallery.default' : JetElements.widgetInstagramGallery,
				'jet-scroll-navigation.default' : JetElements.widgetScrollNavigation,
				'jet-subscribe-form.default' : JetElements.widgetSubscribeForm,
				'jet-progress-bar.default' : JetElements.widgetProgressBar,
				'jet-portfolio.default' : JetElements.widgetPortfolio,
				'jet-timeline.default': JetElements.widgetTimeLine,
				'jet-table.default': JetElements.widgetTable,
				'jet-dropbar.default': JetElements.widgetDropbar,
				'jet-video.default': JetElements.widgetVideo,
				'jet-audio.default': JetElements.widgetAudio,
				'jet-horizontal-timeline.default': JetElements.widgetHorizontalTimeline,
				'mp-timetable.default': JetElements.widgetTimeTable,
				'jet-pie-chart.default': JetElements.widgetPieChart
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			elementor.hooks.addAction( 'frontend/element_ready/section', JetElements.elementorSection );
		},

		widgetCountdown: function( $scope ) {

			var timeInterval,
				$coutdown = $scope.find( '[data-due-date]' ),
				endTime = new Date( $coutdown.data( 'due-date' ) * 1000 ),
				elements = {
					days: $coutdown.find( '[data-value="days"]' ),
					hours: $coutdown.find( '[data-value="hours"]' ),
					minutes: $coutdown.find( '[data-value="minutes"]' ),
					seconds: $coutdown.find( '[data-value="seconds"]' )
				};

			JetElements.widgetCountdown.updateClock = function() {

				var timeRemaining = JetElements.widgetCountdown.getTimeRemaining( endTime );

				$.each( timeRemaining.parts, function( timePart ) {

					var $element = elements[ timePart ];

					if ( $element.length ) {
						$element.html( this );
					}

				} );

				if ( timeRemaining.total <= 0 ) {
					clearInterval( timeInterval );
				}
			};

			JetElements.widgetCountdown.initClock = function() {
				JetElements.widgetCountdown.updateClock();
				timeInterval = setInterval( JetElements.widgetCountdown.updateClock, 1000 );
			};

			JetElements.widgetCountdown.splitNum = function( num ) {

				var num   = num.toString(),
					arr   = [],
					reult = '';

				if ( 1 === num.length ) {
					num = 0 + num;
				}

				arr = num.match(/\d{1}/g);

				$.each( arr, function( index, val ) {
					reult += '<span class="jet-countdown-timer__digit">' + val + '</span>';
				});

				return reult;
			};

			JetElements.widgetCountdown.getTimeRemaining = function( endTime ) {

				var timeRemaining = endTime - new Date(),
					seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
					minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
					hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
					days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

				if ( days < 0 || hours < 0 || minutes < 0 ) {
					seconds = minutes = hours = days = 0;
				}

				return {
					total: timeRemaining,
					parts: {
						days: JetElements.widgetCountdown.splitNum( days ),
						hours: JetElements.widgetCountdown.splitNum( hours ),
						minutes: JetElements.widgetCountdown.splitNum( minutes ),
						seconds: JetElements.widgetCountdown.splitNum( seconds )
					}
				};
			};

			JetElements.widgetCountdown.initClock();

		},

		widgetMap: function( $scope ) {

			var $container = $scope.find( '.jet-map' ),
				map,
				init,
				pins;

			if ( ! window.google || ! $container.length ) {
				return;
			}

			init = $container.data( 'init' );
			pins = $container.data( 'pins' );
			map  = new google.maps.Map( $container[0], init );

			if ( pins ) {
				$.each( pins, function( index, pin ) {

					var marker,
						infowindow,
						pinData = {
							position: pin.position,
							map: map
						};

					if ( '' !== pin.image ) {
						pinData.icon = pin.image;
					}

					marker = new google.maps.Marker( pinData );

					if ( '' !== pin.desc ) {
						infowindow = new google.maps.InfoWindow({
							content: pin.desc,
							disableAutoPan: true
						});
					}

					marker.addListener( 'click', function() {
						infowindow.setOptions({ disableAutoPan: false });
						infowindow.open( map, marker );
					});

					if ( 'visible' === pin.state && '' !== pin.desc ) {
						infowindow.open( map, marker );
					}

				});
			}

		},

		widgetProgress: function( $scope ) {

			var $progress = $scope.find( '.circle-progress' );

			if ( ! $progress.length ) {
				return;
			}

			var $value            = $progress.find( '.circle-progress__value' ),
				$meter            = $progress.find( '.circle-progress__meter' ),
				percent           = parseInt( $value.data( 'value' ) ),
				progress          = percent / 100,
				duration          = $scope.find( '.circle-progress-wrap' ).data( 'duration' ),
				responsiveSizes   = $progress.data( 'responsive-sizes' ),
				desktopSizes      = responsiveSizes.desktop,
				tabletSizes       = responsiveSizes.tablet,
				mobileSizes       = responsiveSizes.mobile,
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				prevDeviceMode    = currentDeviceMode,
				isAnimatedCircle  = false;

			if ( 'tablet' === currentDeviceMode ) {
				updateSvgSizes( tabletSizes.size, tabletSizes.viewBox, tabletSizes.center, tabletSizes.radius, tabletSizes.valStroke, tabletSizes.bgStroke, tabletSizes.circumference );
			}

			if ( 'mobile' === currentDeviceMode ) {
				updateSvgSizes( mobileSizes.size, mobileSizes.viewBox, mobileSizes.center, mobileSizes.radius, mobileSizes.valStroke, mobileSizes.bgStroke, mobileSizes.circumference );
			}

			elementorFrontend.waypoint( $scope, function() {

				// animate counter
				var $number = $scope.find( '.circle-counter__number' ),
					data = $number.data();

				var decimalDigits = data.toValue.toString().match( /\.(.*)/ );

				if ( decimalDigits ) {
					data.rounding = decimalDigits[1].length;
				}

				data.duration = duration;

				$number.numerator( data );

				// animate progress
				var circumference = parseInt( $progress.data( 'circumference' ) ),
					dashoffset    = circumference * (1 - progress);

				$value.css({
					'transitionDuration': duration + 'ms',
					'strokeDashoffset': dashoffset
				});

				isAnimatedCircle = true;

			}, {
				offset: 'bottom-in-view'
			} );

			$( window ).on( 'resize.jetCircleProgress orientationchange.jetCircleProgress', circleResizeHandler );

			function circleResizeHandler( event ) {
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

				if ( 'desktop' === currentDeviceMode && 'desktop' !== prevDeviceMode ) {
					updateSvgSizes( desktopSizes.size, desktopSizes.viewBox, desktopSizes.center, desktopSizes.radius, desktopSizes.valStroke, desktopSizes.bgStroke, desktopSizes.circumference );
					prevDeviceMode = 'desktop';
				}

				if ( 'tablet' === currentDeviceMode && 'tablet' !== prevDeviceMode ) {
					updateSvgSizes( tabletSizes.size, tabletSizes.viewBox, tabletSizes.center, tabletSizes.radius, tabletSizes.valStroke, tabletSizes.bgStroke, tabletSizes.circumference );
					prevDeviceMode = 'tablet';
				}

				if ( 'mobile' === currentDeviceMode && 'mobile' !== prevDeviceMode ) {
					updateSvgSizes( mobileSizes.size, mobileSizes.viewBox, mobileSizes.center, mobileSizes.radius, mobileSizes.valStroke, mobileSizes.bgStroke, mobileSizes.circumference );
					prevDeviceMode = 'mobile';
				}
			}

			function updateSvgSizes( size, viewBox, center, radius, valStroke, bgStroke, circumference ) {
				var dashoffset = circumference * (1 - progress);

				$progress.attr( {
					'width': size,
					'height': size,
					'data-radius': radius,
					'data-circumference': circumference
				} );

				$progress[0].setAttribute( 'viewBox', viewBox );

				$meter.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': bgStroke
				} );

				if ( isAnimatedCircle ) {
					$value.css( {
						'transitionDuration': ''
					} );
				}

				$value.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': valStroke
				} );

				$value.css( {
					'strokeDasharray': circumference,
					'strokeDashoffset': isAnimatedCircle ? dashoffset : circumference
				} );
			}
		},

		widgetCarousel: function( $scope ) {

			var $carousel = $scope.find( '.jet-carousel' );

			if ( ! $carousel.length ) {
				return;
			}

			JetElements.initCarousel( $carousel, $carousel.data( 'slider_options' ) );

		},

		widgetPosts: function ( $scope ) {

			var $target = $scope.find( '.jet-carousel' );

			if ( ! $target.length ) {
				return;
			}

			JetElements.initCarousel( $target.find( '.jet-posts' ), $target.data( 'slider_options' ) );

		},

		widgetAnimatedText: function( $scope ) {
			var $target = $scope.find( '.jet-animated-text' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new jetAnimatedText( $target, settings );
			instance.init();
		},

		widgetAnimatedBox: function( $scope ) {

			JetElements.onAnimatedBoxSectionActivated( $scope );

			var $target      = $scope.find( '.jet-animated-box' ),
				toogleEvents = 'mouseenter mouseleave',
				scrollOffset = $( window ).scrollTop(),
				firstMouseEvent = true;

			if ( ! $target.length ) {
				return;
			}

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$target.on( 'touchstart', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$target.on( 'touchend', function( event ) {

					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( ! $( this ).hasClass( 'flipped-stop' ) ) {
						$( this ).toggleClass( 'flipped' );
					}
				} );

			} else {
				$target.on( toogleEvents, function( event ) {

					if ( firstMouseEvent && 'mouseleave' === event.type ) {
						return;
					}

					if ( firstMouseEvent && 'mouseenter' === event.type ) {
						firstMouseEvent = false;
					}

					if ( ! $( this ).hasClass( 'flipped-stop' ) ) {
						$( this ).toggleClass( 'flipped' );
					}
				} );
			}
		},

		onAnimatedBoxSectionActivated: function( $scope ) {
			if ( ! window.elementor ) {
				return;
			}

			if ( ! window.JetElementsEditor ) {
				return;
			}

			if ( ! window.JetElementsEditor.activeSection ) {
				return;
			}

			var section = window.JetElementsEditor.activeSection;
			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( section );

			if ( isBackSide ) {
				$scope.find( '.jet-animated-box' ).addClass( 'flipped' );
				$scope.find( '.jet-animated-box' ).addClass( 'flipped-stop' );
			} else {
				$scope.find( '.jet-animated-box' ).removeClass( 'flipped' );
				$scope.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		widgetImagesLayout: function( $scope ) {
			var $target = $scope.find( '.jet-images-layout' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new jetImagesLayout( $target, settings );
			instance.init();
		},

		widgetPortfolio: function( $scope ) {
			var $target = $scope.find( '.jet-portfolio' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new jetPortfolio( $target, settings );
			instance.init();
		},

		widgetInstagramGallery: function( $scope ) {
			var $target         = $scope.find( '.jet-instagram-gallery__instance' ),
				instance        = null,
				defaultSettings = {},
				settings        = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' ),

			/*
			 * Default Settings
			 */
			defaultSettings = {
				layoutType: 'masonry',
				columns: 3,
				columnsTablet: 2,
				columnsMobile: 1,
			}

			/**
			 * Checking options, settings and options merging
			 */
			$.extend( defaultSettings, settings );

			if ( 'masonry' === settings.layoutType ) {
				salvattore.init();
			}
		},

		widgetScrollNavigation: function( $scope ) {
			var $target         = $scope.find( '.jet-scroll-navigation' ),
				instance        = null,
				settings        = $target.data( 'settings' );

			instance = new jetScrollNavigation( $target, settings );
			instance.init();
		},

		widgetSubscribeForm: function( $scope ) {
			var $target               = $scope.find( '.jet-subscribe-form' ),
				scoreId               = $scope.data( 'id' ),
				settings              = $target.data( 'settings' ),
				jetSubscribeFormAjax  = null,
				subscribeFormAjax     = 'jet_subscribe_form_ajax',
				ajaxRequestSuccess    = false,
				$subscribeForm        = $( '.jet-subscribe-form__form', $target ),
				$fields               = $( '.jet-subscribe-form__fields', $target ),
				$mailField            = $( '.jet-subscribe-form__mail-field', $target ),
				$inputData            = $mailField.data( 'instance-data' ),
				$submitButton         = $( '.jet-subscribe-form__submit', $target ),
				$subscribeFormMessage = $( '.jet-subscribe-form__message', $target ),
				timeout               = null,
				invalidMailMessage    = window.jetElements.messages.invalidMail || 'Please specify a valid email';

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

			function subscribeHandle() {
				var inputValue     = $mailField.val(),
					sendData       = {
						'email': inputValue,
						'use_target_list_id': settings['use_target_list_id'] || false,
						'target_list_id': settings['target_list_id'] || '',
						'data': $inputData
					},
					serializeArray = $subscribeForm.serializeArray(),
					additionalFields = {};

				if ( JetElementsTools.validateEmail( inputValue ) ) {

					$.each( serializeArray, function( key, fieldData ) {

						if ( 'email' === fieldData.name ) {
							sendData[ fieldData.name ] = fieldData.value;
						} else {
							additionalFields[ fieldData.name ] = fieldData.value;
						}
					} );

					sendData['additional'] = additionalFields;

					if ( ! ajaxRequestSuccess && jetSubscribeFormAjax ) {
						jetSubscribeFormAjax.abort();
					}

					jetSubscribeFormAjax = $.ajax( {
						type: 'POST',
						url: window.jetElements.ajaxUrl,
						data: {
							action: subscribeFormAjax,
							data: sendData
						},
						cache: false,
						beforeSend: function() {
							$submitButton.addClass( 'loading' );
							ajaxRequestSuccess = false;
						},
						success: function( data ){
							var successType   = data.type,
								message       = data.message || '',
								responceClass = 'jet-subscribe-form--response-' + successType;

							$submitButton.removeClass( 'loading' );
							ajaxRequestSuccess = true;

							$target.removeClass( 'jet-subscribe-form--response-error' );
							$target.addClass( responceClass );

							$( 'span', $subscribeFormMessage ).html( message );
							$subscribeFormMessage.css( { 'visibility': 'visible' } );

							timeout = setTimeout( function() {
								$subscribeFormMessage.css( { 'visibility': 'hidden' } );
								$target.removeClass( responceClass );
							}, 20000 );

							if ( settings['redirect'] ) {
								window.location.href = settings['redirect_url'];
							}

							$( window ).trigger( {
								type: 'jet-elements/subscribe',
								elementId: scoreId,
								successType: successType,
								inputData: $inputData
							} );
						}
					});

				} else {
					$mailField.addClass( 'mail-invalid' );

					$target.addClass( 'jet-subscribe-form--response-error' );
					$( 'span', $subscribeFormMessage ).html( invalidMailMessage );
					$subscribeFormMessage.css( { 'visibility': 'visible' } );

					timeout = setTimeout( function() {
						$target.removeClass( 'jet-subscribe-form--response-error' );
						$subscribeFormMessage.css( { 'visibility': 'hidden' } );
						$mailField.removeClass( 'mail-invalid' );
					}, 20000 );
				}
			}
		},

		widgetProgressBar: function( $scope ) {
			var $target      = $scope.find( '.jet-progress-bar' ),
				percent      = $target.data( 'percent' ),
				type         = $target.data( 'type' ),
				deltaPercent = percent * 0.01;

			elementorFrontend.waypoint( $target, function( direction ) {
				var $this       = $( this ),
					animeObject = { charged: 0 },
					$statusBar  = $( '.jet-progress-bar__status-bar', $this ),
					$percent    = $( '.jet-progress-bar__percent-value', $this ),
					animeProgress,
					animePercent;

				if ( 'type-7' == type ) {
					$statusBar.css( {
						'height': percent + '%'
					} );
				} else {
					$statusBar.css( {
						'width': percent + '%'
					} );
				}

				animePercent = anime({
					targets: animeObject,
					charged: percent,
					round: 1,
					duration: 1000,
					easing: 'easeInOutQuad',
					update: function() {
						$percent.html( animeObject.charged );
					}
				});

			} );
		},

		widgetSlider: function( $scope ) {
			var $target        = $scope.find( '.jet-slider' ),
				$imagesTagList = $( '.sp-image', $target ),
				instance       = null,
				defaultSettings = {
					imageScaleMode: 'cover',
					slideDistance: { size: 10, unit: 'px' },
					slideDuration: 500,
					sliderAutoplay: true,
					sliderAutoplayDelay: 2000,
					sliderAutoplayOnHover: 'pause',
					sliderFadeMode: false,
					sliderFullScreen: true,
					sliderFullscreenIcon: 'fa fa-arrows-alt',
					sliderHeight: { size: 600, unit: 'px' },
					sliderHeightTablet: { size: 400, unit: 'px' },
					sliderHeightMobile: { size: 300, unit: 'px' },
					sliderLoop: true,
					sliderNaviOnHover: false,
					sliderNavigation: true,
					sliderNavigationIcon: 'fa fa-angle-left',
					sliderPagination: false,
					sliderShuffle: false,
					sliderWidth: { size: 100, unit: '%' },
					thumbnailWidth: 120,
					thumbnailHeight: 80,
					thumbnails: true,
					rightToLeft: false,
				},
				instanceSettings = $target.data( 'settings' ) || {},
				settings        = $.extend( {}, defaultSettings, instanceSettings );

			if ( ! $target.length ) {
				return;
			}

			$target.imagesLoaded().progress( function( instance, image ) {
				var loadedImages = null,
					progressBarWidth = null;

				if ( image.isLoaded ) {

					if ( $( image.img ).hasClass( 'sp-image' ) ) {
						$( image.img ).addClass( 'image-loaded' );
					}

					loadedImages = $( '.image-loaded', $target );
					progressBarWidth = 100 * ( loadedImages.length / $imagesTagList.length ) + '%';

					$( '.jet-slider-loader', $target ).css( { width: progressBarWidth } );
				}

			} ).done( function( instance ) {

				$( '.slider-pro', $target ).addClass( 'slider-loaded' );
				$( '.jet-slider-loader', $target ).css( { 'display': 'none' } );
			} );

			var tabletHeight = '' !== settings['sliderHeightTablet']['size'] ? settings['sliderHeightTablet']['size'] + settings['sliderHeightTablet']['unit'] : settings['sliderHeight']['size'] + settings['sliderHeight']['unit'];
			var mobileHeight = '' !== settings['sliderHeightMobile']['size'] ? settings['sliderHeightMobile']['size'] + settings['sliderHeightMobile']['unit'] : tabletHeight;

			// Breakpoint Settings Start
			var tabletBreakpoint = ( undefined !== elementor.config.breakpoints.lg ) ? elementor.config.breakpoints.lg - 1 : 1023,
				mobileBreakpoint = ( undefined !== elementor.config.breakpoints.md ) ? elementor.config.breakpoints.md - 1 : 767,
				breakpointsSettings = {};

			if ( elementor.isEditMode() ) {
				mobileBreakpoint -= 17; // needed for fixed bug when the height of the slider does not work for the tablet in the editor mode
			}

			breakpointsSettings[tabletBreakpoint] = {
				height: tabletHeight
			};

			breakpointsSettings[mobileBreakpoint] = {
				height: mobileHeight
			};
			// Breakpoint Settings End

			$( '.slider-pro', $target ).sliderPro( {
				width: settings['sliderWidth']['size'] + settings['sliderWidth']['unit'],
				height: settings['sliderHeight']['size'] + settings['sliderHeight']['unit'],
				arrows: settings['sliderNavigation'],
				fadeArrows: settings['sliderNaviOnHover'],
				buttons: settings['sliderPagination'],
				autoplay: settings['sliderAutoplay'],
				autoplayDelay: settings['sliderAutoplayDelay'],
				autoplayOnHover: settings['sliderAutoplayOnHover'],
				fullScreen: settings['sliderFullScreen'],
				shuffle: settings['sliderShuffle'],
				loop: settings['sliderLoop'],
				fade: settings['sliderFadeMode'],
				slideDistance: ( 'string' !== typeof settings['slideDistance']['size'] ) ? settings['slideDistance']['size'] : 0,
				slideAnimationDuration: +settings['slideDuration'],
				//imageScaleMode: settings['imageScaleMode'],
				imageScaleMode: 'exact',
				waitForLayers: false,
				grabCursor: false,
				thumbnailWidth: settings['thumbnailWidth'],
				thumbnailHeight: settings['thumbnailHeight'],
				rightToLeft: settings['rightToLeft'],
				init: function() {
					this.resize();

					$( '.sp-previous-arrow', $target ).append( '<i class="' + settings['sliderNavigationIcon'] + '"></i>' );
					$( '.sp-next-arrow', $target ).append( '<i class="' + settings['sliderNavigationIcon'] + '"></i>' );

					$( '.sp-full-screen-button', $target ).append( '<i class="' + settings['sliderFullscreenIcon'] + '"></i>' );
				},
				breakpoints: breakpointsSettings
			} );
		},

		widgetTestimonials: function( $scope ) {
			var $target        = $scope.find( '.jet-testimonials__instance' ),
				$imagesTagList = $( '.jet-testimonials__figure', $target ),
				instance       = null,
				settings       = $target.data( 'settings' );

			if ( ! $target.length ) {
				return;
			}

			settings.adaptiveHeight = settings['adaptiveHeight'];

			JetElements.initCarousel( $target, settings );
		},

		widgetImageComparison: function( $scope ) {
			var $target              = $scope.find( '.jet-image-comparison__instance' ),
				instance             = null,
				imageComparisonItems = $( '.jet-image-comparison__container', $target ),
				settings             = $target.data( 'settings' ),
				elementId            = $scope.data( 'id' );

			if ( ! $target.length ) {
				return;
			}

			window.juxtapose.scanPage( '.jet-juxtapose' );

			settings.draggable = false;
			settings.infinite = false;
			//settings.adaptiveHeight = true;
			JetElements.initCarousel( $target, settings );
		},

		widgetTimeTable: function( $scope ) {

			var $mptt_shortcode_wrapper = $scope.find( '.mptt-shortcode-wrapper' );

			if ( ( typeof typenow ) !== 'undefined' ) {
				if ( pagenow === typenow ) {
					switch ( typenow ) {

						case 'mp-event':
							Registry._get( 'Event' ).init();
							break;

						case 'mp-column':
							Registry._get( 'Event' ).initDatePicker();
							Registry._get( 'Event' ).columnRadioBox();
							break;

						default:
							break;
					}
				}
			}

			if ( $mptt_shortcode_wrapper.length ) {

				Registry._get( 'Event' ).initTableData();
				Registry._get( 'Event' ).filterShortcodeEvents();
				Registry._get( 'Event' ).getFilterByHash();

				$mptt_shortcode_wrapper.show();
			}

			if ( $( '.upcoming-events-widget' ).length || $mptt_shortcode_wrapper.length ) {
				Registry._get( 'Event' ).setColorSettings();
			}
		},

		elementorSection: function( $scope ) {
			var $target   = $scope,
				instance  = null,
				editMode  = Boolean( elementor.isEditMode() );

			if ( window.jetElements.hasOwnProperty( 'jetParallaxSections' ) || editMode ) {
				instance = new jetSectionParallax( $target );
				instance.init();
			}
		},

		initCarousel: function( $target, options ) {

			var tabletSlides, mobileSlides, defaultOptions, slickOptions;

			if ( options.slidesToShow.tablet ) {
				tabletSlides = options.slidesToShow.tablet;
			} else {
				tabletSlides = 1 === options.slidesToShow.desktop ? 1 : 2;
			}

			if ( options.slidesToShow.mobile ) {
				mobileSlides = options.slidesToShow.mobile;
			} else {
				mobileSlides = 1;
			}

			options.slidesToShow = options.slidesToShow.desktop;

			defaultOptions = {
				customPaging: function(slider, i) {
					return $( '<span />' ).text( i + 1 );
				},
				dotsClass: 'jet-slick-dots',
				responsive: [
					{
						breakpoint: 1025,
						settings: {
							slidesToShow: tabletSlides,
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: mobileSlides,
							slidesToScroll: 1
						}
					}
				]
			};

			slickOptions = $.extend( {}, defaultOptions, options );

			$target.slick( slickOptions );
		},

		widgetTimeLine : function ( $scope ){
			var $target = $scope.find( '.jet-timeline' ),
				instance = null;

			if ( ! $target.length ) {
				return;
			}

			instance = new jetTimeLine( $target );
			instance.init();
		},

		widgetTable: function( $scope ) {
			var $target = $scope.find( '.jet-table' ),
				options = {
					cssHeader: 'jet-table-header-sort',
					cssAsc: 'jet-table-header-sort--up',
					cssDesc: 'jet-table-header-sort--down',
					initWidgets: false
				};

			if ( ! $target.length ) {
				return;
			}

			if ( $target.hasClass( 'jet-table--sorting' ) ) {
				$target.tablesorter( options );
			}
		},

		widgetDropbar: function( $scope ) {
			var $dropbar       = $scope.find( '.jet-dropbar' ),
				$dropbar_inner = $dropbar.find( '.jet-dropbar__inner' ),
				$btn           = $dropbar.find( '.jet-dropbar__button' ),
				$content       = $dropbar.find( '.jet-dropbar__content' ),
				settings       = $dropbar.data( 'settings' ) || {},
				mode           = settings['mode'] || 'hover',
				hide_delay     = +settings['hide_delay'] || 0,
				activeClass    = 'jet-dropbar-open',
				scrollOffset,
				timer;

			if ( 'click' === mode ) {
				$btn.on( 'click.jetDropbar', function( event ) {
					$dropbar.toggleClass( activeClass );
				} );
			} else {
				if ( 'ontouchstart' in window || 'ontouchend' in window ) {
					$btn.on( 'touchend.jetDropbar', function( event ) {
						if ( $( window ).scrollTop() !== scrollOffset ) {
							return;
						}

						$dropbar.toggleClass( activeClass );
					} );
				} else {
					$dropbar_inner.on( 'mouseenter.jetDropbar', function( event ) {
						clearTimeout( timer );
						$dropbar.addClass( activeClass );
					} );

					$dropbar_inner.on( 'mouseleave.jetDropbar', function( event ) {
						timer = setTimeout( function() {
							$dropbar.removeClass( activeClass );
						}, hide_delay );
					} );
				}
			}

			$( document ).on( 'touchstart.jetDropbar', function( event ) {
				scrollOffset = $( window ).scrollTop();
			} );

			$( document ).on( 'click.jetDropbar touchend.jetDropbar', function( event ) {

				if ( 'touchend' === event.type && $( window ).scrollTop() !== scrollOffset ) {
					return;
				}

				if ( $( event.target ).closest( $btn ).length || $( event.target ).closest( $content ).length ) {
					return;
				}

				if ( ! $dropbar.hasClass( activeClass ) ) {
					return;
				}

				$dropbar.removeClass( activeClass );
			} );
		},

		widgetVideo: function( $scope ) {
			var $video = $scope.find( '.jet-video' ),
				$iframe = $scope.find( '.jet-video-iframe' ),
				$videoPlaer = $scope.find( '.jet-video-player' ),
				$mejsPlaer = $scope.find( '.jet-video-mejs-player' ),
				mejsPlaerControls = $mejsPlaer.data( 'controls' ) || ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen'],
				$overlay = $scope.find( '.jet-video__overlay' ),
				hasOverlay = $overlay.length > 0,
				settings = $video.data( 'settings' ) || {},
				autoplay = settings.autoplay || false;

			if ( $overlay[0] ) {
				$overlay.on( 'click.jetVideo', function( event ) {

					if ( $videoPlaer[0] ) {
						$videoPlaer[0].play();

						$overlay.remove();
						hasOverlay = false;

						return;
					}

					if ( $iframe[0] ) {
						iframeStartPlay();
					}
				} );
			}

			if ( autoplay && $iframe[0] && $overlay[0] ) {
				iframeStartPlay();
			}

			function iframeStartPlay() {
				var lazyLoad = $iframe.data( 'lazy-load' );

				if ( lazyLoad ) {
					$iframe.attr( 'src', lazyLoad );
				}

				if ( ! autoplay ) {
					$iframe[0].src = $iframe[0].src.replace( '&autoplay=0', '&autoplay=1' );
				}

				$overlay.remove();
				hasOverlay = false;
			}

			if ( $videoPlaer[0] ) {
				$videoPlaer.on( 'play.jetVideo', function( event ) {
					if ( hasOverlay ) {
						$overlay.remove();
						hasOverlay = false;
					}
				} );
			}

			if ( $mejsPlaer[0] ) {
				$mejsPlaer.mediaelementplayer( {
					videoVolume: 'horizontal',
					hideVolumeOnTouchDevices: false,
					enableProgressTooltip: false,
					features: mejsPlaerControls,
					success: function( media ) {
						media.addEventListener( 'timeupdate', function( event ) {
							var $currentTime = $scope.find( '.mejs-time-current' ),
								inlineStyle  = $currentTime.attr( 'style' );

							if ( inlineStyle ) {
								var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

								if ( scaleX ) {
									$currentTime.css( 'width', scaleX * 100 + '%' );
								}
							}
						}, false );
					}
				} );
			}
		},

		widgetAudio: function( $scope ) {
			var $wrapper = $scope.find( '.jet-audio' ),
				$player  = $scope.find( '.jet-audio-player' ),
				settings = $wrapper.data( 'settings' );

			if ( ! $player[0] ) {
				return;
			}

			$player.mediaelementplayer( {
				features: settings['controls'] || ['playpause', 'current', 'progress', 'duration', 'volume'],
				audioVolume: settings['audioVolume'] || 'horizontal',
				startVolume: settings['startVolume'] || 0.8,
				hideVolumeOnTouchDevices: settings['hideVolumeOnTouchDevices'],
				enableProgressTooltip: false,
				success: function( media ) {
					media.addEventListener( 'timeupdate', function( event ) {
						var $currentTime = $scope.find( '.mejs-time-current' ),
							inlineStyle  = $currentTime.attr( 'style' );

						if ( inlineStyle ) {
							var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

							if ( scaleX ) {
								$currentTime.css( 'width', scaleX * 100 + '%' );
							}
						}
					}, false );
				}
			} );
		},

		widgetHorizontalTimeline: function( $scope ) {
			var $timeline         = $scope.find( '.jet-hor-timeline' ),
				$timelineTrack    = $scope.find( '.jet-hor-timeline-track' ),
				$items            = $scope.find( '.jet-hor-timeline-item' ),
				$arrows           = $scope.find( '.jet-arrow' ),
				$nextArrow        = $scope.find( '.jet-next-arrow' ),
				$prevArrow        = $scope.find( '.jet-prev-arrow' ),
				columns           = $timeline.data( 'columns' ) || {},
				desktopColumns    = columns.desktop || 3,
				tabletColumns     = columns.tablet || desktopColumns,
				mobileColumns     = columns.mobile || tabletColumns,
				firstMouseEvent   = true,
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				prevDeviceMode    = currentDeviceMode,
				itemsCount        = $scope.find( '.jet-hor-timeline-list--middle .jet-hor-timeline-item' ).length,
				currentTransform  = 0,
				currentPosition   = 0,
				transform = {
					desktop: 100 / desktopColumns,
					tablet:  100 / tabletColumns,
					mobile:  100 / mobileColumns
				},
				maxPosition = {
					desktop: Math.max( 0, (itemsCount - desktopColumns) ),
					tablet:  Math.max( 0, (itemsCount - tabletColumns) ),
					mobile:  Math.max( 0, (itemsCount - mobileColumns) )
				};

			if ( 'ontouchstart' in window || 'ontouchend' in window ) {
				$items.on( 'touchend.jetHorTimeline', function( event ) {
					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			} else {
				$items.on( 'mouseenter.jetHorTimeline mouseleave.jetHorTimeline', function( event ) {

					if ( firstMouseEvent && 'mouseleave' === event.type ) {
						return;
					}

					if ( firstMouseEvent && 'mouseenter' === event.type ) {
						firstMouseEvent = false;
					}

					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			}

			// Set Line Position
			setLinePosition();
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', setLinePosition );

			function setLinePosition() {
				var $line             = $scope.find( '.jet-hor-timeline__line' ),
					$firstPoint       = $scope.find( '.jet-hor-timeline-item__point-content:first' ),
					$lastPoint        = $scope.find( '.jet-hor-timeline-item__point-content:last' ),
					firstPointLeftPos = $firstPoint.position().left + parseInt( $firstPoint.css( 'marginLeft' ) ),
					lastPointLeftPos  = $lastPoint.position().left + parseInt( $lastPoint.css( 'marginLeft' ) ),
					pointWidth        = $firstPoint.outerWidth();

				$line.css( {
					'left': firstPointLeftPos + pointWidth/2,
					'width': lastPointLeftPos - firstPointLeftPos
				} );

				// var $progressLine   = $scope.find( '.jet-hor-timeline__line-progress' ),
				// 	$lastActiveItem = $scope.find( '.jet-hor-timeline-list--middle .jet-hor-timeline-item.is-active:last' );
				//
				// if ( $lastActiveItem[0] ) {
				// 	var $lastActiveItemPointWrap = $lastActiveItem.find( '.jet-hor-timeline-item__point' ),
				// 		progressLineWidth        = $lastActiveItemPointWrap.position().left + $lastActiveItemPointWrap.outerWidth() - firstPointLeftPos - pointWidth / 2;
				//
				// 	$progressLine.css( {
				// 		'width': progressLineWidth
				// 	} );
				// }
			}

			// Arrows Navigation Type
			if ( $nextArrow[0] && maxPosition[ currentDeviceMode ] === 0 ) {
				$nextArrow.addClass( 'jet-arrow-disabled' );
			}

			if ( $arrows[0] ) {
				$arrows.on( 'click.jetHorTimeline', function( event ){
					var $this = $( this ),
						direction = $this.hasClass( 'jet-next-arrow' ) ? 'next' : 'prev',
						currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

					if ( 'next' === direction && currentPosition < maxPosition[ currentDeviceMode ] ) {
						currentTransform -= transform[ currentDeviceMode ];
						currentPosition += 1;
					}

					if ( 'prev' === direction && currentPosition > 0 ) {
						currentTransform += transform[ currentDeviceMode ];
						currentPosition -= 1;
					}

					if ( currentPosition > 0 ) {
						$prevArrow.removeClass( 'jet-arrow-disabled' );
					} else {
						$prevArrow.addClass( 'jet-arrow-disabled' );
					}

					if ( currentPosition === maxPosition[ currentDeviceMode ] ) {
						$nextArrow.addClass( 'jet-arrow-disabled' );
					} else {
						$nextArrow.removeClass( 'jet-arrow-disabled' );
					}

					if ( currentPosition === 0 ) {
						currentTransform = 0;
					}

					$timelineTrack.css({
						'transform': 'translateX(' + currentTransform + '%)'
					});

				} );
			}

			setArrowPosition();
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', setArrowPosition );
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', timelineSliderResizeHandler );

			function setArrowPosition() {
				if ( ! $arrows[0] ) {
					return;
				}

				var $middleList = $scope.find( '.jet-hor-timeline-list--middle' ),
					middleListTopPosition = $middleList.position().top,
					middleListHeight = $middleList.outerHeight();

				$arrows.css({
					'top': middleListTopPosition + middleListHeight/2
				});
			}

			function timelineSliderResizeHandler( event ) {
				if ( ! $timeline.hasClass( 'jet-hor-timeline--arrows-nav' ) ) {
					return;
				}

				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					resetSlider = function() {
						$prevArrow.addClass( 'jet-arrow-disabled' );

						if ( $nextArrow.hasClass( 'jet-arrow-disabled' ) ) {
							$nextArrow.removeClass( 'jet-arrow-disabled' );
						}

						if ( maxPosition[ currentDeviceMode ] === 0 ) {
							$nextArrow.addClass( 'jet-arrow-disabled' );
						}

						currentTransform = 0;
						currentPosition = 0;

						$timelineTrack.css({
							'transform': 'translateX(0%)'
						});
					};

				switch ( currentDeviceMode ) {
					case 'desktop':
						if ( 'desktop' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'desktop';
						}
						break;

					case 'tablet':
						if ( 'tablet' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'tablet';
						}
						break;

					case 'mobile':
						if ( 'mobile' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'mobile';
						}
						break;
				}
			}
		},

		widgetPieChart: function( $scope ) {
			var $container     = $scope.find( '.jet-pie-chart-container' ),
				$canvas        = $scope.find( '.jet-pie-chart' )[0],
				data           = $container.data( 'chart' ) || {},
				options        = $container.data( 'options' ) || {},
				defaultOptions = {
					maintainAspectRatio: false
				};

			options = $.extend( {}, defaultOptions, options );

			elementorFrontend.waypoint( $scope, function() {
				var chartInstance = new Chart( $canvas, {
					type:    'pie',
					data:    data,
					options: options
				} );
			}, {
				offset: 'bottom-in-view'
			} );
		}
	};

	$( window ).on( 'elementor/frontend/init', JetElements.init );

	var JetElementsTools = {
		debounce: function( threshold, callback ) {
			var timeout;

			return function debounced( $event ) {
				function delayed() {
					callback.call( this, $event );
					timeout = null;
				}

				if ( timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( delayed, threshold );
			};
		},

		getObjectNextKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				nextIndex = idIndex += 1;

			if( nextIndex >= keys.length ) {
				//we're at the end, there is no next
				return false;
			}

			var nextKey = keys[ nextIndex ];

			return nextKey;
		},

		getObjectPrevKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				prevIndex = idIndex -= 1;

			if ( 0 > idIndex ) {
				//we're at the end, there is no next
				return false;
			}

			var prevKey = keys[ prevIndex ];

			return prevKey;
		},

		getObjectFirstKey: function( object ) {
			return Object.keys( object )[0];
		},

		getObjectLastKey: function( object ) {
			return Object.keys( object )[ Object.keys( object ).length - 1 ];
		},

		getObjectValues: function( object ) {
			var values;

			if ( !Object.values ) {
				values = Object.keys( object ).map( function( e ) {
					return object[e]
				} );

				return values;
			}

			return Object.values( object );
		},

		validateEmail: function( email ) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			return re.test( email );
		},

		mobileAndTabletcheck: function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		}
	}

	/**
	 * Jet animated text Class
	 *
	 * @return {void}
	 */
	window.jetAnimatedText = function( $selector, settings ) {
		var self                   = this,
			$instance              = $selector,
			$animatedTextContainer = $( '.jet-animated-text__animated-text', $instance ),
			$animatedTextList      = $( '.jet-animated-text__animated-text-item', $animatedTextContainer ),
			timeOut                = null,
			defaultSettings        = {
				effect: 'fx1',
				delay: 3000
			},
			settings               =  $.extend( defaultSettings, settings || {} ),
			currentIndex           = 0,
			animationDelay         = settings.delay;

		/**
		 * Avaliable Effects
		 */
		self.avaliableEffects = {
			'fx1' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 75 + index * 100; },
					easing: 'easeOutElastic',
					elasticity: 650,
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['100%','0%']
				},
				out: {
					duration: 300,
					delay: function(el, index) { return index*40; },
					easing: 'easeInOutExpo',
					opacity: 0,
					translateY: '-100%'
				}
			},
			'fx2' : {
				in: {
					duration: 800,
					delay: function( el, index) { return index * 50; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];
					}
				},
				out: {
					duration: 300,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function( el, index ) {
						return index%2 === 0 ? '80%' : '-80%';
					}
				}
			},
			'fx3' : {
				in: {
					duration: 700,
					delay: function(el, index) {
						return ( el.parentNode.children.length - index - 1 ) * 80;
					},
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? [ '-80%', '0%' ] : [ '80%', '0%' ];
					},
					rotateZ: [90,0]
				},
				out: {
					duration: 300,
					delay: function(el, index) { return (el.parentNode.children.length-index-1) * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function(el, index) {
						return index%2 === 0 ? '80%' : '-80%';
					},
					rotateZ: function(el, index) {
						return index%2 === 0 ? -25 : 25;
					}
				}
			},
			'fx4' : {
				in: {
					duration: 700,
					delay: function( el, index ) { return 550 + index * 50; },
					easing: 'easeOutQuint',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateY: [ 180, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 30; },
					easing: 'easeInQuint',
					opacity: {
						value: 0,
						easing: 'linear',
					},
					translateY: '100%',
					rotateY: -180
				}
			},
			'fx5' : {
				in: {
					duration: 250,
					delay: function( el, index ) { return 200 + index * 25; },
					easing: 'easeOutCubic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['-50%','0%']
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 25; },
					easing: 'easeOutCubic',
					opacity: 0,
					translateY: '50%'
				}
			},
			'fx6' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateY: [ -90, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: 0,
					rotateY: 45
				}
			},
			'fx7' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 100 + index * 30; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateZ: function( el, index ) {
						return [ anime.random( 20, 40 ), 0 ];
					}
				},
				out: {
					duration: 300,
					opacity: {
						value: [ 1, 0 ],
						easing: 'easeOutExpo',
					}
				}
			},
			'fx8' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 200 + index * 20; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateY: [ -90, 0 ],
					translateY: [ '50%','0%' ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateY: 90
				}
			},
			'fx9' : {
				in: {
					duration: 400,
					delay: function(el, index) { return 200+index*30; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateX: [90,0]
				},
				out: {
					duration: 250,
					delay: function(el, index) { return index*30; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -90
				}
			},
			'fx10' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 100 + index * 50; },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateX: [ 110, 0 ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -110
				}
			},
			'fx11' : {
				in: {
					duration: function( el, index ) { return anime.random( 800, 1000 ); },
					delay: function( el, index ) { return anime.random( 100, 300 ); },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateZ: function( el, index ) { return [ anime.random( -50, 50 ), 0 ]; }
				},
				out: {
					duration: function( el, index ) { return anime.random( 200, 300 ); },
					delay: function( el, index ) { return anime.random( 0, 80 ); },
					easing: 'easeInQuart',
					opacity: 0,
					translateY: '50%',
					rotateZ: function( el, index ) { return anime.random( -50, 50 ); }
				}
			},
			'fx12' : {
				in: {
					elasticity: false,
					duration: 1,
					delay: function( el, index ) {
						var delay = index * 100 + anime.random( 50, 100 );

						return delay;
					},
					width: [ 0, function( el, i ) { return $( el ).width(); } ]
				},
				out: {
					duration: 1,
					delay: function( el, index ) { return ( el.parentNode.children.length - index - 1 ) * 20; },
					easing: 'linear',
					width: {
						value: 0
					}
				}
			}
		};

		self.textChange = function() {
			var currentDelay = animationDelay,
				$prevText    = $animatedTextList.eq( currentIndex ),
				$nextText;

			if ( currentIndex < $animatedTextList.length - 1 ) {
				currentIndex++;
			} else {
				currentIndex = 0;
			}

			$nextText = $animatedTextList.eq( currentIndex );

			self.hideText( $prevText, settings.effect, null, function( anime ) {
				$prevText.toggleClass( 'visible' );

				var currentDelay = animationDelay;

				if ( timeOut ) {
					clearTimeout( timeOut );
				}

				self.showText(
					$nextText,
					settings.effect,
					function() {
						$nextText.toggleClass( 'active' );
						$prevText.toggleClass( 'active' );

						$nextText.toggleClass( 'visible' );
					},
					function() {
						timeOut = setTimeout( function() {
							self.textChange();
						}, currentDelay );
					}
				);

			} );
		};

		self.showText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				$( this ).css( {
					'width': 'auto',
					'opacity': 1,
					'WebkitTransform': '',
					'transform': ''
				});
				targets.push( this );
			});

			self.animateText( targets, 'in', effect, beginCallback, completeCallback );
		};

		self.hideText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				targets.push(this);
			});

			self.animateText( targets, 'out', effect, beginCallback, completeCallback );
		};

		self.animateText = function( targets, direction, effect, beginCallback, completeCallback ) {
			var effectSettings   = self.avaliableEffects[ effect ] || {},
				animationOptions = effectSettings[ direction ],
				animeInstance = null;

			animationOptions.targets = targets;

			animationOptions.begin = beginCallback;
			animationOptions.complete = completeCallback;

			animeInstance = anime( animationOptions );
		};

		self.init = function() {
			var $text = $animatedTextList.eq( currentIndex );

			self.showText(
				$text,
				settings.effect,
				null,
				function() {
					var currentDelay = animationDelay;

					if ( timeOut ) {
						clearTimeout( timeOut );
					}

					timeOut = setTimeout( function() {
						self.textChange();
					}, currentDelay );

				}
			);
		};
	}

	/**
	 * Jet Images Layout Class
	 *
	 * @return {void}
	 */
	window.jetImagesLayout = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.jet-images-layout__list', $instance ),
			$itemsList      = $( '.jet-images-layout__item', $instance ),
			defaultSettings = {},
			settings        = settings || {};

		/*
		 * Default Settings
		 */
		defaultSettings = {
			layoutType: 'masonry',
			columns: 3,
			columnsTablet: 2,
			columnsMobile: 1,
			justifyHeight: 300
		}

		/**
		 * Checking options, settings and options merging
		 */
		$.extend( defaultSettings, settings );

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {
			switch ( settings['layoutType'] ) {
				case 'masonry':
					salvattore.init();
				break;
				case 'justify':
					$itemsList.each( function() {
						var $this          = $( this ),
							$imageInstance = $( '.jet-images-layout__image-instance', $this),
							imageWidth     = $imageInstance.data( 'width' ),
							imageHeight    = $imageInstance.data( 'height' ),
							imageRatio     = +imageWidth / +imageHeight,
							flexValue      = imageRatio * 100,
							newWidth       = +settings['justifyHeight'] * imageRatio,
							newHeight      = 'auto';

						$this.css( {
							'flex-grow': flexValue,
							'flex-basis': newWidth
						} );
					} );
				break;
			}

			if ( $.isFunction( $.fn.imagesLoaded ) ) {

				$( '.jet-images-layout__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
					var $image      = $( image.img ),
						$parentItem = $image.closest( '.jet-images-layout__item' ),
						$loader     = $( '.jet-images-layout__image-loader', $parentItem );

					$parentItem.addClass( 'image-loaded' );

					$loader.fadeTo( 500, 0, function() {
						$( this ).remove();
					} );

				});

			} else {
				var $loader = $( '.jet-images-layout__image-loader', $itemsList );

				$itemsList.addClass( 'image-loaded' );

				$loader.fadeTo( 500, 0, function() {
					$( this ).remove();
				} );
			}
		}

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();
		}
	}

	/**
	 * Jet Scroll Navigation Class
	 *
	 * @return {void}
	 */
	window.jetScrollNavigation = function( $selector, settings ) {
		var self            = this,
			$window         = $( window ),
			$document       = $( document ),
			$body           = $( 'body' ),
			$instance       = $selector,
			$htmlBody       = $( 'html, body' ),
			$itemsList      = $( '.jet-scroll-navigation__item', $instance ),
			sectionList     = [],
			defaultSettings = {
				speed: 500,
				blockSpeed: 500,
				offset: 0,
				sectionSwitch: false
			},
			settings        = $.extend( {}, defaultSettings, settings ),
			sections        = {},
			currentSection  = null,
			isScrolling     = false,
			isSwipe         = false,
			hash            = window.location.hash.slice(1),
			timeout         = null,
			timeStamp       = 0,
			platform        = navigator.platform;

		jQuery.extend( jQuery.easing, {
			easeInOutCirc: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
				return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
			}
		});

		/**
		 * [init description]
		 * @return {[type]} [description]
		 */
		self.init = function() {
			self.setSectionsData();

			if ( hash && sections.hasOwnProperty( hash ) ) {
				$itemsList.addClass( 'invert' );
			}

			// Add Events
			$itemsList.on( 'click.jetScrollNavigation', function( event ) {
				var $this     = $( this ),
					sectionId = $this.data( 'anchor' );

				self.onAnchorChange( sectionId );
			} );

			$window.on( 'resize.jetScrollNavigation orientationchange.jetScrollNavigation', JetElementsTools.debounce( 50, self.onResize ) );
			$window.on( 'load', function() { self.setSectionsData(); } );

			$document.keydown( function( event ) {

				if ( 38 == event.keyCode ) {
					self.directionSwitch( event, 'up' );
				}

				if ( 40 == event.keyCode ) {
					self.directionSwitch( event, 'down' );
				}
			} );

			// waypoint section detection
			self.waypointHandler();

			// hijaking handler
			self.hijakingHandler();
		};

		/**
		 * [setSectionsData description]
		 */
		self.setSectionsData = function() {
			$itemsList.each( function() {
				var $this         = $( this ),
					sectionId     = $this.data('anchor'),
					sectionInvert = 'yes' === $this.data('invert') ? true : false,
					$section      = $( '#' + sectionId );

				if ( $section[0] ) {
					$section.addClass( 'jet-scroll-navigation-section' );
					//$section.attr( 'touch-action', 'none' );
					$section[0].dataset.sectionName = sectionId;
					sections[ sectionId ] = {
						selector: $section,
						offset: Math.round( $section.offset().top ),
						height: $section.outerHeight(),
						invert: sectionInvert
					};
				}
			} );
		};

		/**
		 * [waypointHandler description]
		 * @return {[type]} [description]
		 */
		self.waypointHandler = function() {

			for ( var section in sections ) {
				var $section = sections[section].selector;

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'down' === direction && ! isScrolling ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '70%',
					triggerOnce: false
				} );

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'up' === direction && ! isScrolling ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '0%',
					triggerOnce: false
				} );
			}
		};

		/**
		 * [onAnchorChange description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onAnchorChange = function( sectionId ) {
			var $this     = $( '[data-anchor=' + sectionId + ']', $instance ),
				offset    = null;

			if ( ! sections.hasOwnProperty( sectionId ) ) {
				return false;
			}

			offset = sections[sectionId].offset - settings.offset;

			if ( ! isScrolling ) {
				isScrolling = true;

				window.history.pushState( null, null, '#' + sectionId );
				currentSection = sectionId;

				$itemsList.removeClass( 'active' );
				$this.addClass( 'active' );

				$itemsList.removeClass( 'invert' );

				if ( sections[sectionId].invert ) {
					$itemsList.addClass( 'invert' );
				}

				$htmlBody.animate( { 'scrollTop': offset }, settings.speed, 'easeInOutCirc', function() {
					isScrolling = false;
				} );
			}
		};

		/**
		 * [directionSwitch description]
		 * @param  {[type]} event     [description]
		 * @param  {[type]} direction [description]
		 * @return {[type]}           [description]
		 */
		self.directionSwitch = function( event, direction ) {
			var direction = direction || 'up',
				nextItem = $( '[data-anchor=' + currentSection + ']', $instance ).next(),
				prevItem = $( '[data-anchor=' + currentSection + ']', $instance ).prev();

			if ( isScrolling ) {
				return false;
			}

			if ( 'up' === direction ) {
				if ( prevItem[0] ) {
					prevItem.trigger( 'click.jetScrollNavigation' );
				}
			}

			if ( 'down' === direction ) {
				if ( nextItem[0] ) {
					nextItem.trigger( 'click.jetScrollNavigation' );
				}
			}
		};

		/**
		 * [scrollifyHandler description]
		 * @return {[type]} [description]
		 */
		self.hijakingHandler = function() {
			var isMobile    = JetElementsTools.mobileAndTabletcheck(),
				touchStartY = 0,
				touchEndY   = 0;

			if ( settings.sectionSwitch ) {

				if ( ! isMobile ) {
					document.addEventListener( 'wheel', self.onWheel, { passive: false } );
				} else {

					document.addEventListener( 'touchstart', function( event ) {
						var $target   = $( event.target ),
							$section  = $target.closest( '.elementor-top-section' ),
							sectionId = $section.attr( 'id' ) || false;

						touchStartY = event.changedTouches[0].screenY;

						if ( sectionId && isScrolling ) {
							event.preventDefault();
						}

					}, { passive: false } );

					document.addEventListener( 'touchend', function( event ) {
						var $target         = $( event.target ),
							$navigation     = $target.closest( '.jet-scroll-navigation' ) || false,
							$section        = $target.closest( '.elementor-top-section' ) || false,
							sectionId       = $section.attr( 'id' ) || false,
							endScrollTop    = $window.scrollTop(),
							touchEndY       = event.changedTouches[0].screenY,
							direction       = touchEndY > touchStartY ? 'up' : 'down',
							sectionOffset   = false,
							newSectionId    = false,
							prevSectionId   = false,
							nextSectionId   = false;

						if ( $navigation[0] ) {
							return false;
						}

						if ( sectionId && sections.hasOwnProperty( sectionId ) ) {

							prevSectionId = JetElementsTools.getObjectPrevKey( sections, sectionId );
							nextSectionId = JetElementsTools.getObjectNextKey( sections, sectionId );

							sectionOffset = sections[ sectionId ].offset;

							if ( 'up' === direction ) {

								if ( sectionOffset < endScrollTop ) {
									prevSectionId = sectionId;
								}

								if ( prevSectionId ) {
									newSectionId = prevSectionId;
								}
							}

							if ( 'down' === direction ) {

								if ( sectionOffset > endScrollTop ) {
									nextSectionId = sectionId;
								}

								if ( nextSectionId ) {
									newSectionId = nextSectionId;
								}
							}

							if ( newSectionId ) {

								self.onAnchorChange( newSectionId );
							}
						}

					}, { passive: false } );
				}
			}
		}

		/**
		 * [onScroll description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onScroll = function( event ) {
			event.preventDefault();
		};

		self.onWheel = function( event ) {

			if ( isScrolling ) {
				event.preventDefault();
			}

			var $target         = $( event.target ),
				$section        = $target.closest( '.elementor-top-section' ),
				sectionId       = $section.attr( 'id' ) || false,
				delta           = event.deltaY,
				direction       = ( 0 > delta ) ? 'up' : 'down',
				sectionOffset   = false,
				newSectionId    = false,
				prevSectionId   = false,
				nextSectionId   = false,
				windowScrollTop = $window.scrollTop();

			if ( sectionId && sections.hasOwnProperty( sectionId ) ) {

				prevSectionId = JetElementsTools.getObjectPrevKey( sections, sectionId );
				nextSectionId = JetElementsTools.getObjectNextKey( sections, sectionId );

				sectionOffset = sections[ sectionId ].offset;

				if ( 'up' === direction ) {

					if ( sectionOffset < windowScrollTop + settings.offset - 10 ) {
						prevSectionId = sectionId;
					}

					if ( prevSectionId ) {
						newSectionId = prevSectionId;
					}
				}

				if ( 'down' === direction ) {

					if ( sectionOffset > windowScrollTop + settings.offset + 10 ) {
						nextSectionId = sectionId;
					}

					if ( nextSectionId ) {
						newSectionId = nextSectionId;
					}
				}

				if ( newSectionId ) {
					event.preventDefault();

					if ( event.timeStamp - timeStamp > 10 && 'MacIntel' == platform ) {
						timeStamp = event.timeStamp;

						return false;
					}

					self.onAnchorChange( newSectionId );
				}
			}

			return false;
		};

		/**
		 * [onResize description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onResize = function( event ) {
			self.setSectionsData();
		};

		/**
		 * [scrollStop description]
		 * @return {[type]} [description]
		 */
		self.scrollStop = function() {
			$htmlBody.stop( true );
		};

		/**
		 * Mobile and tablet check funcion.
		 *
		 * @return {boolean} Mobile Status
		 */
		self.mobileAndTabletcheck = function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		};

	}

	/**
	 * jetSectionParallax Class
	 *
	 * @return {void}
	 */
	window.jetSectionParallax = function( $target ) {
		var self             = this,
			sectionId        = $target.data('id'),
			settings         = false,
			editMode         = Boolean( elementor.isEditMode() ),
			$window          = $( window ),
			$body            = $( 'body' ),
			scrollLayoutList = [],
			mouseLayoutList  = [],
			winScrollTop     = $window.scrollTop(),
			winHeight        = $window.height(),
			requesScroll     = null,
			requestMouse     = null,
			tiltx            = 0,
			tilty            = 0,
			isSafari         = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/),
			platform         = navigator.platform;

		/**
		 * Init
		 */
		self.init = function() {

			if ( ! editMode ) {
				settings = jetElements[ 'jetParallaxSections' ][ sectionId ] || false;
			} else {
				settings = self.generateEditorSettings( sectionId );
			}

			if ( ! settings ) {
				return false;
			}

			self.generateLayouts();

			//$window.on( 'scroll.jetSectionParallax resize.jetSectionParallax', JetElementsTools.debounce( 5, self.scrollHandler ) );

			if ( 0 !== scrollLayoutList.length ) {
				$window.on( 'scroll.jetSectionParallax resize.jetSectionParallax', self.scrollHandler );
			}

			if ( 0 !== mouseLayoutList.length ) {
				$target.on( 'mousemove.jetSectionParallax resize.jetSectionParallax', self.mouseMoveHandler );
				$target.on( 'mouseleave.jetSectionParallax', self.mouseLeaveHandler );
			}

			self.scrollUpdate();
		};

		self.generateEditorSettings = function( sectionId ) {
			var editorElements      = null,
				sectionsData        = {},
				sectionData         = {},
				sectionParallaxData = {},
				settings            = [];

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			$.each( editorElements.models, function( index, obj ) {
				if ( sectionId == obj.id ) {
					sectionData = obj.attributes.settings.attributes;
				}
			} );

			if ( ! sectionData.hasOwnProperty( 'jet_parallax_layout_list' ) || 0 === Object.keys( sectionData ).length ) {
				return false;
			}

			sectionParallaxData = sectionData[ 'jet_parallax_layout_list' ].models;

			$.each( sectionParallaxData, function( index, obj ) {
				settings.push( obj.attributes );
			} );

			if ( 0 !== settings.length ) {
				return settings;
			}

			return false;
		};

		self.generateLayouts = function() {

			$( '.jet-parallax-section__layout', $target ).remove();

			$.each( settings, function( index, layout ) {

				var imageData      = layout['jet_parallax_layout_image'],
					speed          = layout['jet_parallax_layout_speed']['size'] || 50,
					zIndex         = layout['jet_parallax_layout_z_index'],
					bgSize         = layout['jet_parallax_layout_bg_size'] || 'auto',
					animProp       = layout['jet_parallax_layout_animation_prop'] || 'bgposition',
					bgX            = layout['jet_parallax_layout_bg_x'],
					bgY            = layout['jet_parallax_layout_bg_y'],
					type           = layout['jet_parallax_layout_type'] || 'none',
					device         = layout['jet_parallax_layout_on'] || ['desktop', 'tablet'],
					_id            = layout['_id'],
					isDynamicImage = layout.hasOwnProperty( '__dynamic__' ) && layout.__dynamic__.hasOwnProperty( 'jet_parallax_layout_image' ),
					$layout        = null,
					layoutData     = {},
					safariClass    = isSafari ? ' is-safari' : '',
					macClass       = 'MacIntel' == platform ? ' is-mac' : '';

				if ( '' === imageData['url'] && ! isDynamicImage ) {
					return false;
				}

				if ( ! $target.hasClass( 'jet-parallax-section' ) ) {
					$target.addClass( 'jet-parallax-section' );
				}

				$layout = $( '<div class="jet-parallax-section__layout elementor-repeater-item-' + _id + ' jet-parallax-section__' + type +'-layout' + macClass + '"><div class="jet-parallax-section__image"></div></div>' )
					.prependTo( $target )
					.css({
						'z-index': zIndex
					});

				var imageCSS = {
					'background-size': bgSize,
					'background-position-x': bgX + '%',
					'background-position-y': bgY + '%'
				};

				if ( '' !== imageData['url'] ) {
					imageCSS['background-image'] = 'url(' + imageData['url'] + ')';
				}

				$( '> .jet-parallax-section__image', $layout ).css( imageCSS );

				layoutData = {
					selector: $layout,
					image: imageData['url'],
					size: bgSize,
					prop: animProp,
					type: type,
					device: device,
					xPos: bgX,
					yPos: bgY,
					speed: 2 * ( speed / 100 )
				};

				if ( 'none' !== type ) {
					if ( 'scroll' === type || 'zoom' === type ) {
						scrollLayoutList.push( layoutData );
					}

					if ( 'mouse' === type ) {
						mouseLayoutList.push( layoutData );
					}
				}

			});

		};

		self.scrollHandler = function( event ) {
			winScrollTop = $window.scrollTop();
			winHeight    = $window.height();

			self.scrollUpdate();
		};

		self.scrollUpdate = function() {
			$.each( scrollLayoutList, function( index, layout ) {

				var $this      = layout.selector,
					$image     = $( '.jet-parallax-section__image', $this ),
					speed      = layout.speed,
					offsetTop  = $this.offset().top,
					thisHeight = $this.outerHeight(),
					prop       = layout.prop,
					type       = layout.type,
					posY       = ( winScrollTop - offsetTop + winHeight ) / thisHeight * 100,
					device     = elementorFrontend.getCurrentDeviceMode();

				if ( -1 == layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateY(0)',
						'background-position-y': layout.yPos
					} );

					return false;
				}

				if ( winScrollTop < offsetTop - winHeight ) posY = 0;
				if ( winScrollTop > offsetTop + thisHeight) posY = 200;

				posY = parseFloat( speed * posY ).toFixed(1);

				switch( type ) {
					case 'scroll':
						if ( 'bgposition' === layout.prop ) {
							$image.css( {
								'background-position-y': 'calc(' + layout.yPos + '% + ' + posY + 'px)'
							} );
						} else {
							$image.css( {
								'transform': 'translateY(' + posY + 'px)'
							} );
						}
						break;
					case 'zoom':
						var deltaScale = ( winScrollTop - offsetTop + winHeight ) / winHeight,
							scale      = deltaScale * speed;

						scale = scale + 1;

						$image.css( {
							'transform': 'scale(' + scale + ')'
						} );
						break;
				}

			} );

			//requesScroll = requestAnimationFrame( self.scrollUpdate );
			//requestAnimationFrame( self.scrollUpdate );
		};

		self.mouseMoveHandler = function( event ) {
			var windowWidth  = $window.width(),
				windowHeight = $window.height(),
				cx           = Math.ceil( windowWidth / 2 ),
				cy           = Math.ceil( windowHeight / 2 ),
				dx           = event.clientX - cx,
				dy           = event.clientY - cy;

			tiltx = -1 * ( dx / cx );
			tilty = -1 * ( dy / cy);

			self.mouseMoveUpdate();
		};

		self.mouseLeaveHandler = function( event ) {

			$.each( mouseLayoutList, function( index, layout ) {
				var $this  = layout.selector,
					$image = $( '.jet-parallax-section__image', $this );

				switch( layout.prop ) {
					case 'transform3d':
						TweenMax.to(
							$image[0],
							1.2, {
								x: 0,
								y: 0,
								z: 0,
								rotationX: 0,
								rotationY: 0,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

		self.mouseMoveUpdate = function() {
			$.each( mouseLayoutList, function( index, layout ) {
				var $this   = layout.selector,
					$image  = $( '.jet-parallax-section__image', $this ),
					speed   = layout.speed,
					prop    = layout.prop,
					posX    = parseFloat( tiltx * 125 * speed ).toFixed(1),
					posY    = parseFloat( tilty * 125 * speed ).toFixed(1),
					posZ    = layout.zIndex * 50,
					rotateX = parseFloat( tiltx * 25 * speed ).toFixed(1),
					rotateY = parseFloat( tilty * 25 * speed ).toFixed(1),
					device  = elementorFrontend.getCurrentDeviceMode();

				if ( -1 == layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateX(0) translateY(0)',
						'background-position-x': layout.xPos,
						'background-position-y': layout.yPos
					} );

					return false;
				}

				switch( prop ) {
					case 'bgposition':
						TweenMax.to(
							$image[0],
							1, {
								backgroundPositionX: 'calc(' + layout.xPos + '% + ' + posX + 'px)',
								backgroundPositionY: 'calc(' + layout.yPos + '% + ' + posY + 'px)',
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform':
						TweenMax.to(
							$image[0],
							1, {
								x: posX,
								y: posY,
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform3d':
						TweenMax.to(
							$image[0],
							2, {
								x: posX,
								y: posY,
								z: posZ,
								rotationX: rotateY,
								rotationY: -rotateX,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

	}

	/**
	 * Jet Portfolio Class
	 *
	 * @return {void}
	 */
	window.jetPortfolio = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.jet-portfolio__list', $instance ),
			$itemsList      = $( '.jet-portfolio__item', $instance ),
			$filterList     = $( '.jet-portfolio__filter-item', $instance ),
			$moreWrapper    = $( '.jet-portfolio__view-more', $instance ),
			$moreButton     = $( '.jet-portfolio__view-more-button', $instance ),
			isViewMore      = $moreButton[0],
			itemsData       = {},
			filterData      = {},
			activeSlug      = [],
			defaultSettings = {
				layoutType: 'masonry',
				columns: 3,
				columnsTablet: 2,
				columnsMobile: 1,
				perPage: 6
			},
			masonryOptions = {
				itemSelector: '.jet-portfolio__item',
				percentPosition: true,
				//isAnimated: true
			},
			settings        = $.extend( defaultSettings, settings ),
			$masonryInstance,
			page            = 1;

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();
		}

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {

			self.generateData();

			$filterList.data( 'showItems', isViewMore ? settings.perPage : 'all' );

			if ( 'justify' == settings['layoutType'] ) {
				masonryOptions['columnWidth'] = '.grid-sizer';
			}

			$masonryInstance = $instanceList.masonry( masonryOptions );

			if ( $.isFunction( $.fn.imagesLoaded ) ) {

				$( '.jet-portfolio__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
					var $image      = $( image.img ),
						$parentItem = $image.closest( '.jet-portfolio__item' ),
						$loader     = $( '.jet-portfolio__image-loader', $parentItem );

					$loader.remove();

					$parentItem.addClass( 'item-loaded' );

					$masonryInstance.masonry( 'layout' );
				} );

			} else {
				var $loader = $( '.jet-portfolio__image-loader', $itemsList );

				$itemsList.addClass( 'item-loaded' );

				$loader.remove();
			}

			$filterList.on( 'click.jetPortfolio', self.filterHandler );
			$moreButton.on( 'click.jetPortfolio', self.moreButtonHandler );

			self.render();
			self.checkMoreButton();
		};

		self.generateData = function() {
			if ( $filterList[0] ) {
				$filterList.each( function( index ) {
					var $this = $( this ),
						slug  = $this.data('slug');

					filterData[ slug ] = false;

					if ( 'all' == slug ) {
						filterData[ slug ] = true;
					}
				} );
			} else {
				filterData['all'] = true;
			}

			$itemsList.each( function( index ) {
				var $this = $( this ),
					slug  = $this.data('slug');

				itemsData[ index ] = {
					selector: $this,
					slug: slug,
					visible: $this.hasClass( 'visible-status' ) ? true : false,
					more: $this.hasClass( 'hidden-status' ) ? true : false
				};
			} );
		};

		self.filterHandler = function( event ) {
			var $this = $( this ),
				counter = 1,
				slug  = $this.data( 'slug' ),
				showItems = $this.data( 'showItems' );

			$filterList.removeClass( 'active' );
			$this.addClass( 'active' );

			for ( var slugName in filterData ) {
				filterData[ slugName ] = false;

				if ( slugName == slug ) {
					filterData[ slugName ] = true;
				}
			}

			$.each( itemsData, function( index, obj ) {
				var visible = false;

				if ( 'all' === showItems ) {

					if ( self.isItemVisible( obj.slug ) && ! obj['more'] ) {
						visible = true;
					}

				} else if ( self.isItemVisible( obj.slug ) ) {

					if ( counter <= showItems ) {
						visible = true;
						obj.more = false;
					} else {
						obj.more = true;
					}

					counter++
				}

				obj.visible = visible;

			} );

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [moreButtonHandler description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.moreButtonHandler = function( event ) {
			var $this   = $( this ),
				counter = 1,
				activeFilter = $( '.jet-portfolio__filter-item.active', $instance ),
				showItems;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more && counter <= settings.perPage ) {
					obj.more = false;
					obj.visible = true;

					counter++;
				}
			} );

			if ( activeFilter[0] ) {
				showItems = activeFilter.data( 'showItems' );
				activeFilter.data( 'showItems', showItems + counter - 1 );
			}

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [checkmoreButton description]
		 * @return {[type]} [description]
		 */
		self.checkMoreButton = function() {
			var check = false;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more ) {
					check = true;
				}
			} );

			if ( check ) {
				$moreWrapper.removeClass( 'hidden-status' );
			} else {
				$moreWrapper.addClass( 'hidden-status' );
			}
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.isItemVisible = function( slugs ) {
			var slugList = JetElementsTools.getObjectValues( slugs );

			for ( var slug in filterData ) {
				var checked = filterData[ slug ];

				if ( checked && -1 !== slugList.indexOf( slug ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.anyFilterEnabled = function() {

			for ( var slug in filterData ) {
				if ( filterData[ slug ] ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		self.render = function() {
			var hideAnimation,
				showAnimation;

			$itemsList.removeClass( 'visible-status' ).removeClass( 'hidden-status' );

			$.each( itemsData, function( index, itemData ) {
				var selector = $( '.jet-portfolio__inner', itemData.selector );

				if ( itemData.visible ) {
					itemData.selector.addClass( 'visible-status' );

					showAnimation = anime( {
						targets: selector[0],
						opacity: {
							value: 1,
							duration: 400,
						},
						scale: {
							value: 1,
							duration: 500,
							easing: 'easeOutExpo'
						},
						delay: 50,
						elasticity: false
					} );
				} else {
					itemData.selector.addClass( 'hidden-status' );

					hideAnimation = anime( {
						targets: selector[0],
						opacity: 0,
						scale: 0,
						duration: 500,
						elasticity: false
					} );
				}
			} );

			$masonryInstance.masonry( 'layout' );
		}
	}

	/**
	 * Jet Timeline Class
	 *
	 * @return {void}
	 */
	window.jetTimeLine = function ( $element ){
		var $viewport		= $(window),
			self			= this,
			$line 			= $element.find( '.jet-timeline__line' ),
			$progress		= $line.find( '.jet-timeline__line-progress' ),
			$cards			= $element.find( '.jet-timeline-item' ),
			$points 		= $element.find('.timeline-item__point'),

			currentScrollTop 		= $viewport.scrollTop(),
			lastScrollTop 			= -1,
			currentWindowHeight 	= $(window).height(),
			currentViewportHeight 	= $viewport.outerHeight(),
			lastWindowHeight 		= -1,
			requestAnimationId 		= null,
			flag 					= false;

		self.onScroll = function (){
			currentScrollTop = $viewport.scrollTop();

			self.updateFrame();
			self.animateCards();
		};

		self.onResize = function() {
			currentScrollTop = $viewport.scrollTop();
			currentWindowHeight = $viewport.height();

			self.updateFrame();
		};

		self.updateWindow = function() {
			flag = false;

			$line.css({
				'top' 		: $cards.first().find( $points ).offset().top - $cards.first().offset().top,
				'bottom'	: ( $element.offset().top + $element.outerHeight() ) - $cards.last().find( $points ).offset().top
			});

			if ( ( lastScrollTop !== currentScrollTop ) ) {
				lastScrollTop 		= currentScrollTop;
				lastWindowHeight = currentWindowHeight;

				self.updateProgress();
			}
		};

		self.updateProgress = function() {
			var progressFinishPosition = $cards.last().find( $points ).offset().top,
				progressHeight = ( currentScrollTop - $progress.offset().top ) + ( currentViewportHeight / 2 );

			if ( progressFinishPosition <= ( currentScrollTop + currentViewportHeight / 2 ) ) {
				progressHeight = progressFinishPosition - $progress.offset().top;
			}

			$progress.css({
				'height' : progressHeight + 'px'
			});

			$cards.each( function() {
				if ( $(this).find( $points ).offset().top < ( currentScrollTop + currentViewportHeight * 0.5 ) ) {
					$(this).addClass('is--active');
				} else {
					$(this).removeClass('is--active');
				}
			});
		};

		self.updateFrame = function() {
			if ( ! flag ) {
				requestAnimationId = requestAnimationFrame( self.updateWindow );
			}
			flag = true;
		};

		self.animateCards = function() {
			$cards.each( function() {
				if( $(this).offset().top <= currentScrollTop + currentViewportHeight * 0.9 && $(this).hasClass('jet-timeline-item--animated') ) {
					$(this).addClass('is--show');
				}
			});
		};

		self.init = function(){
			$(document).ready(self.onScroll);
			$(window).on('scroll.jetTimeline', self.onScroll);
			$(window).on('resize.jetTimeline orientationchange.jetTimeline', JetElementsTools.debounce( 50, self.onResize ));
		};
	}

}( jQuery, window.elementorFrontend ) );
