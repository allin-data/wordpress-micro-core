;( function( $, elementor, settings ) {

	"use strict";

	var JetBlog = {

		YT: null,

		init: function() {

			var widgets = {
				'jet-blog-smart-listing.default': JetBlog.initSmartListing,
				'jet-blog-smart-tiles.default': JetBlog.initSmartTiles,
				'jet-blog-text-ticker.default': JetBlog.initTextTicker,
				'jet-blog-video-playlist.default': JetBlog.initPlayList
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

		},

		initPlayList: function( $scope ) {

			if ( 'undefined' !== typeof YT.Player ) {
				JetBlog.initPlayListCb( $scope, YT );
			} else {
				$( document ).on( 'JetYouTubeIframeAPIReady', function( event, YT ) {
					JetBlog.initPlayListCb( $scope, YT );
				} );
			}

		},

		initPlayListCb: function( $scope, YT ) {

			if ( null === JetBlog.YT ) {
				JetBlog.YT = YT;
			}

			if ( $scope.hasClass( 'players-initialized' ) ) {
				return;
			}

			$scope.addClass( 'players-initialized' );

			JetBlog.switchVideo( $scope.find( '.jet-blog-playlist__item.jet-blog-active' ) );

			$scope.on( 'click.JetBlog', '.jet-blog-playlist__item', function() {
				$scope.find( '.jet-blog-playlist__canvas' ).addClass( 'jet-blog-canvas-active' );
				JetBlog.switchVideo( $( this ) );
			} );

			$scope.on( 'click.JetBlog', '.jet-blog-playlist__canvas-overlay', JetBlog.stopVideo );
		},

		initTextTicker: function( $scope ) {

			var $ticker        = $scope.find( '.jet-text-ticker__posts' ),
				sliderSettings = $ticker.data( 'slider-atts' );

			$ticker.slick( sliderSettings );

		},

		initSmartListing: function( $scope ) {

			$scope.on( 'click.JetBlog', '.jet-smart-listing__filter-item a', JetBlog.handleSmartListingFilter );
			$scope.on( 'click.JetBlog', '.jet-smart-listing__arrow', JetBlog.handleSmartListingPager );

			var $filter = $scope.find( '.jet-smart-listing__filter' ),
				rollup  = $filter.data( 'rollup' );

			if ( rollup ) {
				$filter.JetBlogMore();
			}

			$( document ).trigger( 'jet-blog-smart-list/init', [ $scope, JetBlog ] );

		},

		initSmartTiles: function( $scope ) {

			var $carousel = $scope.find( '.jet-smart-tiles-carousel' );

			if ( 0 === $carousel.length ) {
				return false;
			}

			var sliderSettings = $carousel.data( 'slider-atts' );

			$carousel.slick( sliderSettings );

		},

		stopVideo: function( event ) {
			var $target         = $( event.currentTarget ),
				$canvas         = $target.closest( '.jet-blog-playlist__canvas' ),
				currentPlayer   = $canvas.data( 'player' ),
				currentProvider = $canvas.data( 'provider' );

			if ( $canvas.hasClass( 'jet-blog-canvas-active' ) ) {
				$canvas.removeClass( 'jet-blog-canvas-active' );
				JetBlog.pauseCurrentPlayer( currentPlayer, currentProvider );
			}

		},

		switchVideo: function( $el ) {

			var $canvas         = $el.closest( '.jet-blog-playlist' ).find( '.jet-blog-playlist__canvas' ),
				$counter        = $el.closest( '.jet-blog-playlist' ).find( '.jet-blog-playlist__counter-val' ),
				id              = $el.data( 'id' ),
				$iframeWrap     = $canvas.find( '#embed_wrap_' + id ),
				newPlayer       = $el.data( 'player' ),
				newProvider     = $el.data( 'provider' ),
				currentPlayer   = $canvas.data( 'player' ),
				currentProvider = $canvas.data( 'provider' );

			if ( newPlayer ) {
				JetBlog.startNewPlayer( newPlayer, newProvider );
				$canvas.data( 'provider', newProvider );
				$canvas.data( 'player', newPlayer );
			}

			if ( currentPlayer ) {
				JetBlog.pauseCurrentPlayer( currentPlayer, currentProvider );
			}

			if ( $counter.length ) {
				$counter.html( $el.data( 'video_index' ) );
			}

			$el.siblings().removeClass( 'jet-blog-active' );

			if ( ! $el.hasClass( 'jet-blog-active' ) ) {
				$el.addClass( 'jet-blog-active' );
			}

			if ( ! $iframeWrap.length ) {

				$iframeWrap = $( '<div id="embed_wrap_' + id + '"></div>' ).appendTo( $canvas );

				switch ( newProvider ) {

					case 'youtube':
						JetBlog.intYouTubePlayer( $el, {
							id: id,
							canvas: $canvas,
							currentPlayer: currentPlayer,
							playerTarget: $iframeWrap,
							height: $el.data( 'height' ),
							videoId: $el.data( 'video_id' )
						} );
					break;

					case 'vimeo':
						JetBlog.intVimeoPlayer( $el, {
							id: id,
							canvas: $canvas,
							currentPlayer: currentPlayer,
							playerTarget: $iframeWrap,
							html: $.parseJSON( $el.data( 'html' ) )
						} );
					break;

				}

				$iframeWrap.addClass( 'jet-blog-playlist__embed-wrap' );

			}

			$iframeWrap.addClass( 'jet-blog-active' ).siblings().removeClass( 'jet-blog-active' );

		},

		intYouTubePlayer: function( $el, plSettings ) {

			var $iframe = $( '<div id="embed_' + plSettings.id + '"></div>' ).appendTo( plSettings.playerTarget );
			var player  = new JetBlog.YT.Player( $iframe[0], {
				height: plSettings.height,
				width: '100%',
				videoId: plSettings.videoId,
				playerVars: { 'showinfo': 0, 'rel': 0 },
				events: {
					onReady: function( event ) {
						$el.data( 'player', event.target );

						if ( plSettings.currentPlayer ) {
							event.target.playVideo();
						}

						plSettings.canvas.data( 'provider', 'youtube' );
						plSettings.canvas.data( 'player', event.target );

					},
					onStateChange: function( event ) {

						var $index  = $el.find( '.jet-blog-playlist__item-index' );

						if ( ! $index.length ) {
							return;
						}

						switch ( event.data ) {

							case 1:
								$index.removeClass( 'jet-is-paused' ).addClass( 'jet-is-playing' );
								if ( ! plSettings.canvas.hasClass( 'jet-blog-canvas-active' ) ) {
									plSettings.canvas.addClass( 'jet-blog-canvas-active' );
								}
							break;

							case 2:
								$index.removeClass( 'jet-is-playing' ).addClass( 'jet-is-paused' );
							break;

						}
					}
				}
			});

		},

		intVimeoPlayer: function( $el, plSettings ) {

			var $iframe = $( plSettings.html ).appendTo( plSettings.playerTarget );
			var player  = new Vimeo.Player( $iframe[0] );
			var $index  = $el.find( '.jet-blog-playlist__item-index' );

			player.on( 'loaded', function( event ) {

				$el.data( 'player', this );
				if ( plSettings.currentPlayer ) {
					this.play();
				}

				plSettings.canvas.data( 'provider', 'vimeo' );
				plSettings.canvas.data( 'player', this );
			});

			player.on( 'play', function() {
				if ( $index.length ) {
					$index.removeClass( 'jet-is-paused' ).addClass( 'jet-is-playing' );
					if ( ! plSettings.canvas.hasClass( 'jet-blog-canvas-active' ) ) {
						plSettings.canvas.addClass( 'jet-blog-canvas-active' );
					}
				}
			});

			player.on( 'pause', function() {
				if ( $index.length ) {
					$index.removeClass( 'jet-is-playing' ).addClass( 'jet-is-paused' );
				}
			});

		},

		pauseCurrentPlayer: function( currentPlayer, currentProvider ) {

			switch ( currentProvider ) {
				case 'youtube':
					currentPlayer.pauseVideo();
				break;

				case 'vimeo':
					currentPlayer.pause();
				break;
			}
		},

		startNewPlayer: function( newPlayer, newProvider ) {

			switch ( newProvider ) {
				case 'youtube':
					setTimeout( function() {
						newPlayer.playVideo();
					}, 300);
				break;

				case 'vimeo':
					newPlayer.play();
				break;
			}

		},

		handleSmartListingFilter: function( event ) {

			var $this = $( this ),
				$item = $this.closest( '.jet-smart-listing__filter-item' ),
				term  = $this.data( 'term' );

			event.preventDefault();

			$item.closest('.jet-smart-listing__filter').find( '.jet-active-item' ).removeClass( 'jet-active-item' );
			$item.addClass( 'jet-active-item' );

			JetBlog.requestPosts( $this, { term: term, paged: 1 } );

		},

		handleSmartListingPager: function() {

			var $this       = $( this ),
				$wrapper    = $this.closest( '.jet-smart-listing-wrap' ),
				currentPage = parseInt( $wrapper.data( 'page' ), 10 ),
				newPage     = 1,
				currentTerm = parseInt( $wrapper.data( 'term' ), 10 ),
				direction   = $this.data( 'dir' );

			if ( $this.hasClass( 'jet-arrow-disabled' ) ) {
				return;
			}

			if ( 'next' === direction ) {
				newPage = currentPage + 1;
			}

			if ( 'prev' === direction ) {
				newPage = currentPage - 1;
			}

			JetBlog.requestPosts( $this, { term: currentTerm, paged: newPage } );

		},

		requestPosts: function( $trigger, data ) {

			var $wrapper = $trigger.closest( '.jet-smart-listing-wrap' ),
				$loader  = $wrapper.next( '.jet-smart-listing-loading' );

			if ( $wrapper.hasClass( 'jet-processing' ) ) {
				return;
			}

			$wrapper.addClass( 'jet-processing' );

			$.ajax({
				url: settings.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_blog_smart_listing_get_posts',
					jet_request_data: data,
					jet_widget_settings: $wrapper.data( 'settings' )
				},
			}).done( function( response ) {

				var $arrows = $wrapper.find( '.jet-smart-listing__arrows' );

				$wrapper
					.removeClass( 'jet-processing' )
					.find( '.jet-smart-listing' )
					.html( response.data.posts );

				if ( $arrows.length ) {
					$arrows.replaceWith( response.data.arrows );
				}

			}).fail(function() {
				$wrapper.removeClass( 'jet-processing' );
			});

			if ( 'undefined' !== typeof data.paged ) {
				$wrapper.data( 'page', data.paged );
			}

			if ( 'undefined' !== typeof data.term ) {
				$wrapper.data( 'term', data.term );
			}

		}

	};

	$( window ).on( 'elementor/frontend/init', JetBlog.init );

	var JetBlogMore = function( el ) {

		this.$el        = $( el );
		this.$container = this.$el.closest( '.jet-smart-listing__heading' );

		if ( this.$container.find( '.jet-smart-listing__title' ).length ) {
			this.$heading = this.$container.find( '.jet-smart-listing__title' );
		} else {
			this.$heading = this.$container.find( '.jet-smart-listing__title-placeholder' );
		}

		this.settings = $.extend( {
			icon:      'fa fa-ellipsis-h',
			tag:       'i',
			className: 'jet-smart-listing__filter-item jet-smart-listing__filter-more'
		}, this.$el.data( 'more' ) );

		this.containerWidth = 0;
		this.itemsWidth     = 0;
		this.heading        = 0;

		this.init();

	};

	JetBlogMore.prototype = {

		constructor: JetBlogMore,

		init: function() {

			var self = this;

			this.containerWidth = this.$container.width();
			this.heading        = this.$heading.outerWidth();

			this.$hiddenWrap = $( '<div class="' + this.settings.className + '" hidden="hidden"><' + this.settings.tag + ' class="' + this.settings.icon + '"></' + this.settings.tag + '></div>' ).appendTo( this.$el );
			this.$hidden = $( '<div class="jet-smart-listing__filter-hidden-items"></div>' ).appendTo( this.$hiddenWrap );

			this.iter = 0;

			this.rebuildItems();

			setTimeout( function() {
				self.watch();
				self.rebuildItems();
			}, 300 );

		},

		watch: function() {

			var delay = 100;

			$( window ).on( 'resize.JetBlogMore orientationchange.JetBlogMore', this.debounce( delay, this.watcher.bind( this ) ) );
		},

		/**
		 * Responsive menu watcher callback.
		 *
		 * @param  {Object} Resize or Orientationchange event.
		 * @return {void}
		 */
		watcher: function( event ) {

			this.containerWidth = this.$container.width();
			this.itemsWidth     = 0;

			this.$hidden.html( '' );
			this.$hiddenWrap.attr( 'hidden', 'hidden' );

			this.$el.find( '> div[hidden]:not(.jet-smart-listing__filter-more)' ).each( function() {
				$( this ).removeAttr( 'hidden' );
			});

			this.rebuildItems();
		},

		rebuildItems: function() {

			var self            = this,
				$items          = this.$el.find( '> div:not(.jet-smart-listing__filter-more):not([hidden])' ),
				contentWidth    = 0,
				hiddenWrapWidth = parseInt( this.$hiddenWrap.outerWidth(), 10 );

			this.itemsWidth = 0;

			$items.each( function() {

				var $this  = $( this ),
					$clone = null;

				self.itemsWidth += $this.outerWidth();
				contentWidth = self.$heading.outerWidth() + hiddenWrapWidth + self.itemsWidth;

				if ( 0 > self.containerWidth - contentWidth && $this.is( ':visible' ) ) {

					$clone = $this.clone();

					$this.attr( { 'hidden': 'hidden' } );
					self.$hidden.append( $clone );
					self.$hiddenWrap.removeAttr( 'hidden' );
				}

			} );

		},

		/**
		 * Debounce the function call
		 *
		 * @param  {number}   threshold The delay.
		 * @param  {Function} callback  The function.
		 */
		debounce: function ( threshold, callback ) {
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
		}

	};

	$.fn.JetBlogMore = function() {
		return this.each( function() {
			new JetBlogMore( this );
		} );
	};

}( jQuery, window.elementorFrontend, window.JetBlogSettings ) );

if ( 1 === window.hasJetBlogPlaylist ) {

	function onYouTubeIframeAPIReady() {
		jQuery( document ).trigger( 'JetYouTubeIframeAPIReady', [ YT ] );
	}

}
