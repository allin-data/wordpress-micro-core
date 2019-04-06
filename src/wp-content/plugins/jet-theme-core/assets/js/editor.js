(function( $ ) {

	'use strict';

	var JetThemeCoreData = window.JetThemeCoreData || {},
		JetThemeEditor,
		JetThemeViews,
		JetThemeControlsViews,
		JetThemeModules;

	JetThemeViews = {

		LibraryLayoutView: null,
		LibraryHeaderView: null,
		LibraryLoadingView: null,
		LibraryErrorView: null,
		LibraryBodyView: null,
		LibraryCollectionView: null,
		FiltersCollectionView: null,
		LibraryTabsCollectionView: null,
		LibraryTabsItemView: null,
		FiltersItemView: null,
		LibraryTemplateItemView: null,
		LibraryInsertTemplateBehavior: null,
		LibraryTabsCollection: null,
		LibraryCollection: null,
		CategoriesCollection: null,
		LibraryTemplateModel: null,
		CategoryModel: null,
		TabModel: null,
		KeywordsModel: null,
		KeywordsView: null,
		LibraryPreviewView: null,
		LibraryHeaderBack: null,
		LibraryHeaderInsertButton: null,

		init: function() {

			var self = this;

			self.LibraryTemplateModel = Backbone.Model.extend( {
				defaults: {
					template_id: 0,
					name: '',
					title: '',
					thumbnail: '',
					preview: '',
					source: '',
					categories: [],
					keywords: []
				}
			} );

			self.CategoryModel = Backbone.Model.extend( {
				defaults: {
					slug: '',
					title: ''
				}
			} );

			self.CategoryModel = Backbone.Model.extend( {
				defaults: {
					slug: '',
					title: ''
				}
			} );

			self.TabModel = Backbone.Model.extend( {
				defaults: {
					slug: '',
					title: ''
				}
			} );

			self.KeywordsModel = Backbone.Model.extend( {
				defaults: {
					keywords: {}
				}
			} );

			self.LibraryCollection = Backbone.Collection.extend( {
				model: self.LibraryTemplateModel
			} );

			self.CategoriesCollection = Backbone.Collection.extend( {
				model: self.CategoryModel
			} );

			self.LibraryTabsCollection = Backbone.Collection.extend( {
				model: self.TabModel
			} );

			self.LibraryLoadingView = Marionette.ItemView.extend( {
				id: 'jet-template-library-loading',
				template: '#tmpl-jet-template-library-loading'
			} );

			self.LibraryErrorView = Marionette.ItemView.extend( {
				id: 'jet-template-library-error',
				template: '#tmpl-jet-template-library-error'
			} );

			self.KeywordsView = Marionette.ItemView.extend( {
				id: 'jet-template-library-keywords',
				template: '#tmpl-jet-template-library-keywords',
				ui: {
					keywords: '.jet-library-keywords'
				},

				events: {
					'change @ui.keywords': 'onSelectKeyword'
				},

				onSelectKeyword: function( event ) {
					var selected = event.currentTarget.selectedOptions[0].value;
					JetThemeEditor.setFilter( 'keyword', selected );
				}
			} );

			self.LibraryHeaderView = Marionette.LayoutView.extend( {

				id: 'jet-template-library-header',
				template: '#tmpl-jet-template-library-header',

				ui: {
					closeModal: '#jet-template-library-header-close-modal'
				},

				events: {
					'click @ui.closeModal': 'onCloseModalClick'
				},

				regions: {
					headerTabs: '#jet-template-library-header-tabs',
					headerActions: '#jet-template-library-header-actions'
				},

				onCloseModalClick: function() {
					JetThemeEditor.closeModal();
				}

			} );

			self.LibraryPreviewView = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-preview',

				id: 'jet-template-library-preview',

				ui: {
					img: 'img'
				},

				onRender: function() {
					this.ui.img.attr( 'src', this.getOption( 'preview' ) );
				}
			} );

			self.LibraryHeaderBack = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-header-back',

				id: 'jet-template-library-header-back',

				ui: {
					button: 'button'
				},

				events: {
					'click @ui.button': 'onBackClick',
				},

				onBackClick: function() {
					JetThemeEditor.setPreview( 'back' );
				}

			} );

			self.LibraryInsertTemplateBehavior = Marionette.Behavior.extend( {
				ui: {
					insertButton: '.jet-template-library-template-insert'
				},

				events: {
					'click @ui.insertButton': 'onInsertButtonClick'
				},

				onInsertButtonClick: function() {

					var templateModel = this.view.model,
						options       = {};

					JetThemeEditor.layout.showLoadingView();

					elementor.templates.requestTemplateContent(
						templateModel.get( 'source' ),
						templateModel.get( 'template_id' ),
						{
							data: {
								tab: JetThemeEditor.getTab(),
								page_settings: true
							},
							success: function( data ) {

								if ( data.licenseError ) {
									JetThemeEditor.layout.showLicenseError();
									return;
								}

								JetThemeEditor.closeModal();

								elementor.channels.data.trigger( 'template:before:insert', templateModel );

								if ( null !== JetThemeEditor.atIndex ) {
									options.at = JetThemeEditor.atIndex;
								}

								elementor.sections.currentView.addChildModel( data.content, options );

								if ( data.page_settings ) {
									elementor.settings.page.model.set( data.page_settings );
								}

								elementor.channels.data.trigger( 'template:after:insert', templateModel );

								JetThemeEditor.atIndex = null;

							}
						}
					);
				}
			} );

			self.LibraryHeaderInsertButton = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-insert-button',

				id: 'jet-template-library-insert-button',

				behaviors: {
					insertTemplate: {
						behaviorClass: self.LibraryInsertTemplateBehavior
					}
				}

			} );

			self.LibraryTemplateItemView = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-item',

				className: function() {

					var urlClass    = ' jet-template-has-url',
						sourceClass = ' elementor-template-library-template-';

					if ( '' === this.model.get( 'preview' ) ) {
						urlClass = ' jet-template-no-url';
					}

					if ( 'jet-local' === this.model.get( 'source' ) ) {
						sourceClass += 'local';
					} else {
						sourceClass += 'remote';
					}

					return 'elementor-template-library-template' + sourceClass + urlClass;
				},

				ui: function() {
					return {
						previewButton: '.elementor-template-library-template-preview',
						cloneButton: '.jet-clone-to-library',
					};
				},

				events: function() {
					return {
						'click @ui.previewButton': 'onPreviewButtonClick',
						'click @ui.cloneButton': 'onCloneButtonClick'
					};
				},

				onPreviewButtonClick: function() {

					if ( '' === this.model.get( 'preview' ) ) {
						return;
					}

					JetThemeEditor.setPreview( this.model );
				},

				onCloneButtonClick: function() {

					JetThemeEditor.layout.showLoadingView();

					$.ajax({
						url: ajaxurl,
						type: 'post',
						dataType: 'json',
						data: {
							action:  'jet_theme_core_clone_template',
							template: this.model.attributes,
							tab: JetThemeEditor.getTab()
						}
					}).done( function( response ) {
						if ( true === response.success ) {
							JetThemeEditor.channels.layout.trigger( 'template:cloned' );
							JetThemeEditor.tabs.local.data = {};
							JetThemeEditor.setTab( 'local' );
						} else {
							JetThemeEditor.setTab( JetThemeEditor.getTab() );
						}
					});

				},

				behaviors: {
					insertTemplate: {
						behaviorClass: self.LibraryInsertTemplateBehavior
					}
				}
			} );

			self.FiltersItemView = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-filters-item',

				className: function() {
					return 'jet-filter-item';
				},

				ui: function() {
					return {
						filterLabels: '.jet-template-library-filter-label'
					};
				},

				events: function() {
					return {
						'click @ui.filterLabels': 'onFilterClick'
					};
				},

				onFilterClick: function( event ) {

					var $clickedInput = jQuery( event.target );

					JetThemeEditor.setFilter( 'category', $clickedInput.val() );
				}

			} );

			self.LibraryTabsItemView = Marionette.ItemView.extend( {

				template: '#tmpl-jet-template-library-tabs-item',

				className: function() {
					return 'elementor-template-library-menu-item';
				},

				ui: function() {
					return {
						tabsLabels: 'label',
						tabsInput: 'input'
					};
				},

				events: function() {
					return {
						'click @ui.tabsLabels': 'onTabClick'
					};
				},

				onRender: function() {
					if ( this.model.get( 'slug' ) === JetThemeEditor.getTab() ) {
						this.ui.tabsInput.attr( 'checked', 'checked' );
					}
				},

				onTabClick: function( event ) {

					var $clickedInput = jQuery( event.target );
					JetThemeEditor.setTab( $clickedInput.val() );
					JetThemeEditor.setFilter( 'keyword', '' );
				}

			} );

			self.LibraryCollectionView = Marionette.CompositeView.extend( {

				template: '#tmpl-jet-template-library-templates',

				id: 'jet-template-library-templates',

				childViewContainer: '#jet-template-library-templates-container',

				initialize: function() {
					this.listenTo( JetThemeEditor.channels.templates, 'filter:change', this._renderChildren );
				},

				filter: function( childModel ) {

					var filter  = JetThemeEditor.getFilter( 'category' ),
						keyword = JetThemeEditor.getFilter( 'keyword' );

					if ( ! filter && ! keyword ) {
						return true;
					}

					if ( keyword && ! filter ) {
						return _.contains( childModel.get( 'keywords' ), keyword );
					}

					if ( filter && ! keyword ) {
						return _.contains( childModel.get( 'categories' ), filter );
					}

					return _.contains( childModel.get( 'categories' ), filter ) && _.contains( childModel.get( 'keywords' ), keyword );

				},

				getChildView: function( childModel ) {
					return self.LibraryTemplateItemView;
				},

				onRenderCollection: function() {

					var container = this.$childViewContainer,
						items     = this.$childViewContainer.children(),
						tab       = JetThemeEditor.getTab();

					if ( 'jet_page' === tab || 'local' === tab ) {
						return;
					}

					setTimeout( function() {
						self.masonry.init({
							container: container,
							items: items,
						});
					}, 200 );

				}

			} );

			self.LibraryTabsCollectionView = Marionette.CompositeView.extend( {

				template: '#tmpl-jet-template-library-tabs',

				childViewContainer: '#jet-template-library-tabs-items',

				initialize: function() {
					this.listenTo( JetThemeEditor.channels.layout, 'tamplate:cloned', this._renderChildren );
				},

				getChildView: function( childModel ) {
					return self.LibraryTabsItemView;
				}

			} );

			self.FiltersCollectionView = Marionette.CompositeView.extend( {

				id: 'jet-template-library-filters',

				template: '#tmpl-jet-template-library-filters',

				childViewContainer: '#jet-template-library-filters-container',

				getChildView: function( childModel ) {
					return self.FiltersItemView;
				}

			} );

			self.LibraryBodyView = Marionette.LayoutView.extend( {

				id: 'jet-template-library-content',

				className: function() {
					return 'library-tab-' + JetThemeEditor.getTab();
				},

				template: '#tmpl-jet-template-library-content',

				regions: {
					contentTemplates: '.jet-templates-list',
					contentFilters: '.jet-filters-list',
					contentKeywords: '.jet-keywords-list'
				}

			} );

			self.LibraryLayoutView = Marionette.LayoutView.extend( {

				el: '#jet-template-library-modal',

				regions: JetThemeCoreData.modalRegions,

				initialize: function() {

					this.getRegion( 'modalHeader' ).show( new self.LibraryHeaderView() );
					this.listenTo( JetThemeEditor.channels.tabs, 'filter:change', this.switchTabs );
					this.listenTo( JetThemeEditor.channels.layout, 'preview:change', this.switchPreview );

				},

				switchTabs: function() {
					this.showLoadingView();
					JetThemeEditor.setFilter( 'keyword', '' );
					JetThemeEditor.requestTemplates( JetThemeEditor.getTab() );
				},

				switchPreview: function() {

					var header  = this.getHeaderView(),
						preview = JetThemeEditor.getPreview();

					if ( 'back' === preview ) {

						header.headerTabs.show( new self.LibraryTabsCollectionView( {
							collection: JetThemeEditor.collections.tabs
						} ) );

						header.headerActions.empty();

						JetThemeEditor.setTab( JetThemeEditor.getTab() );
						return;
					}

					if ( 'initial' === preview ) {
						header.headerActions.empty();
						return;
					}

					this.getRegion( 'modalContent' ).show( new self.LibraryPreviewView( {
						'preview': preview.get( 'preview' )
					} ) );

					header.headerTabs.show( new self.LibraryHeaderBack() );
					header.headerActions.show( new self.LibraryHeaderInsertButton( {
						model: preview
					} ) );

				},

				getHeaderView: function() {
					return this.getRegion( 'modalHeader' ).currentView;
				},

				getContentView: function() {
					return this.getRegion( 'modalContent' ).currentView;
				},

				showLoadingView: function() {
					this.modalContent.show( new self.LibraryLoadingView() );
				},

				showLicenseError: function() {
					this.modalContent.show( new self.LibraryErrorView() );
				},

				showTemplatesView: function( templatesCollection, categoriesCollection, keywords ) {

					this.getRegion( 'modalContent' ).show( new self.LibraryBodyView() );

					var contentView   = this.getContentView(),
						header        = this.getHeaderView(),
						keywordsModel = new self.KeywordsModel( {
							keywords: keywords
						} );

					JetThemeEditor.collections.tabs = new self.LibraryTabsCollection( JetThemeEditor.getTabs() );

					header.headerTabs.show( new self.LibraryTabsCollectionView( {
						collection: JetThemeEditor.collections.tabs
					} ) );

					contentView.contentTemplates.show( new self.LibraryCollectionView( {
						collection: templatesCollection
					} ) );

					contentView.contentFilters.show( new self.FiltersCollectionView( {
						collection: categoriesCollection
					} ) );

					contentView.contentKeywords.show( new self.KeywordsView( { model: keywordsModel } ) );

				}

			} );
		},

		masonry: {

			self: {},
			elements: {},

			init: function( settings ) {

				var self = this;
				self.settings = $.extend( self.getDefaultSettings(), settings );
				self.elements = self.getDefaultElements();

				self.run();
			},

			getSettings: function( key ) {
				if ( key ) {
					return this.settings[ key ];
				} else {
					return this.settings;
				}
			},

			getDefaultSettings: function() {
				return {
					container: null,
					items: null,
					columnsCount: 3,
					verticalSpaceBetween: 30
				};
			},

			getDefaultElements: function() {
				return {
					$container: jQuery( this.getSettings( 'container' ) ),
					$items: jQuery( this.getSettings( 'items' ) )
				};
			},

			run: function() {
				var heights = [],
					distanceFromTop = this.elements.$container.position().top,
					settings = this.getSettings(),
					columnsCount = settings.columnsCount;

				distanceFromTop += parseInt( this.elements.$container.css( 'margin-top' ), 10 );

				this.elements.$container.height( '' );

				this.elements.$items.each( function( index ) {
					var row = Math.floor( index / columnsCount ),
						indexAtRow = index % columnsCount,
						$item = jQuery( this ),
						itemPosition = $item.position(),
						itemHeight = $item[0].getBoundingClientRect().height + settings.verticalSpaceBetween;

					if ( row ) {
						var pullHeight = itemPosition.top - distanceFromTop - heights[ indexAtRow ];
						pullHeight -= parseInt( $item.css( 'margin-top' ), 10 );
						pullHeight *= -1;
						$item.css( 'margin-top', pullHeight + 'px' );
						heights[ indexAtRow ] += itemHeight;
					} else {
						heights.push( itemHeight );
					}
				} );

				this.elements.$container.height( Math.max.apply( Math, heights ) );
			}
		}

	};

	JetThemeControlsViews = {

		JetSearchView: null,

		init: function() {

			var self = this;

			self.JetSearchView = window.elementor.modules.controls.BaseData.extend( {

				onReady: function() {

					var action      = this.model.attributes.action,
						queryParams = this.model.attributes.query_params;

					this.ui.select.find( 'option' ).each(function(index, el) {
						$( this ).attr( 'selected', true );
					});

					this.ui.select.select2( {
						ajax: {
							url: function() {

								var query = '';

								if ( queryParams.length > 0 ) {
									$.each( queryParams, function( index, param ) {

										if ( window.elementor.settings.page.model.attributes[ param ] ) {
											query += '&' + param + '=' + window.elementor.settings.page.model.attributes[ param ];
										}
									});
								}

								return ajaxurl + '?action=' + action + query;
							},
							dataType: 'json'
						},
						placeholder: 'Please enter 3 or more characters',
						minimumInputLength: 3
					} );

				},

				onBeforeDestroy: function() {

					if ( this.ui.select.data( 'select2' ) ) {
						this.ui.select.select2( 'destroy' );
					}

					this.$el.remove();
				}

			} );

			window.elementor.addControlView( 'jet_search', self.JetSearchView );

		}

	};

	JetThemeModules = {

		getDataToSave: function( data ) {
			data.id = window.elementor.config.post_id;
			return data;
		},

		init: function() {

			if ( window.elementor.settings.jet_template ) {
				window.elementor.settings.jet_template.getDataToSave = this.getDataToSave;
			}

			if ( window.elementor.settings.jet_page ) {
				window.elementor.settings.jet_page.getDataToSave = this.getDataToSave;
				window.elementor.settings.jet_page.changeCallbacks = {
					custom_header: function() {
						this.save( function() {
							elementor.reloadPreview();

							elementor.once( 'preview:loaded', function() {
								elementor.getPanelView().setPage( 'jet_page_settings' );
							} );
						} );
					},
					custom_footer: function() {
						this.save( function() {
							elementor.reloadPreview();

							elementor.once( 'preview:loaded', function() {
								elementor.getPanelView().setPage( 'jet_page_settings' );
							} );
						} );
					}
				};
			}

		}

	};

	JetThemeEditor = {

		modal: false,
		layout: false,
		collections: {},
		tabs: {},
		defaultTab: '',
		channels: {},
		atIndex: null,

		init: function() {

			window.elementor.on(
				'preview:loaded',
				window._.bind( JetThemeEditor.onPreviewLoaded, JetThemeEditor )
			);

			JetThemeViews.init();
			JetThemeControlsViews.init();
			//JetThemeModules.init();

		},

		onPreviewLoaded: function() {

			this.initMagicButton();

			window.elementor.$previewContents.on(
				'click.addJetTemplate',
				'.add-jet-template',
				_.bind( this.showTemplatesModal, this )
			);

			this.channels = {
				templates: Backbone.Radio.channel( 'JET_THEME_EDITOR:templates' ),
				tabs: Backbone.Radio.channel( 'JET_THEME_EDITOR:tabs' ),
				layout: Backbone.Radio.channel( 'JET_THEME_EDITOR:layout' ),
			};

			this.tabs       = JetThemeCoreData.tabs;
			this.defaultTab = JetThemeCoreData.defaultTab;

		},

		initMagicButton: function() {

			var $addNewSection = window.elementor.$previewContents.find( '.elementor-add-new-section' ),
				addJetTemplate = '<button class="add-jet-template" type="button">' + JetThemeCoreData.libraryButton + '</button>',
				$addJetTemplate;

			if ( $addNewSection.length && JetThemeCoreData.libraryButton ) {
				$addJetTemplate = $( addJetTemplate ).prependTo( $addNewSection );
			}

			window.elementor.$previewContents.on(
				'click.addJetTemplate',
				'.elementor-editor-section-settings .elementor-editor-element-add',
				function() {

					var $this    = $( this ),
						$section = $this.closest( '.elementor-top-section' ),
						modelID  = $section.data( 'model-cid' );

					if ( window.elementor.sections.currentView.collection.length ) {
						$.each( window.elementor.sections.currentView.collection.models, function( index, model ) {
							if ( modelID === model.cid ) {
								JetThemeEditor.atIndex = index;
							}
						});
					}

					if ( JetThemeCoreData.libraryButton ) {
						setTimeout( function() {
							var $addNew = $section.prev( '.elementor-add-section' ).find( '.elementor-add-new-section' );
							$addNew.prepend( addJetTemplate );
						}, 100 );
					}

				}
			);
		},

		getFilter: function( name ) {
			return this.channels.templates.request( 'filter:' + name );
		},

		setFilter: function( name, value ) {
			this.channels.templates.reply( 'filter:' + name, value );
			this.channels.templates.trigger( 'filter:change' );
		},

		getTab: function() {
			return this.channels.tabs.request( 'filter:tabs' );
		},

		setTab: function( value, silent ) {

			this.channels.tabs.reply( 'filter:tabs', value );

			if ( ! silent ) {
				this.channels.tabs.trigger( 'filter:change' );
			}

		},

		getTabs: function() {

			var tabs = [];

			_.each( this.tabs, function( item, slug ) {
				tabs.push({
					slug: slug,
					title: item.title
				});
			} );

			return tabs;
		},

		getPreview: function( name ) {
			return this.channels.layout.request( 'preview' );
		},

		setPreview: function( value, silent ) {

			this.channels.layout.reply( 'preview', value );

			if ( ! silent ) {
				this.channels.layout.trigger( 'preview:change' );
			}
		},

		getKeywords: function() {

			var keywords = [];

			_.each( this.keywords, function( title, slug ) {
				tabs.push({
					slug: slug,
					title: title
				});
			} );

			return keywords;
		},

		showTemplatesModal: function() {

			this.getModal().show();

			if ( ! this.layout ) {
				this.layout = new JetThemeViews.LibraryLayoutView();
				this.layout.showLoadingView();
			}

			this.setTab( this.defaultTab, true );
			this.requestTemplates( this.defaultTab );
			this.setPreview( 'initial' );

		},

		requestTemplates: function( tabName ) {

			var self = this,
				tab  = self.tabs[ tabName ];

			self.setFilter( 'category', false );

			if ( tab.data.templates && tab.data.categories ) {
				self.layout.showTemplatesView( tab.data.templates, tab.data.categories, tab.data.keywords );
			} else {
				$.ajax({
					url: ajaxurl,
					type: 'get',
					dataType: 'json',
					data: {
						action: 'jet_theme_get_templates',
						tab: tabName,
					},
					success: function( response ) {

						var templates  = new JetThemeViews.LibraryCollection( response.data.templates ),
							categories = new JetThemeViews.CategoriesCollection( response.data.categories );

						self.tabs[ tabName ].data = {
							templates: templates,
							categories: categories,
							keywords: response.data.keywords
						};

						self.layout.showTemplatesView( templates, categories, response.data.keywords );
					}
				});
			}

		},

		closeModal: function() {
			this.getModal().hide();
		},

		getModal: function() {

			if ( ! this.modal ) {
				this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
					id: 'jet-template-library-modal',
					closeButton: false
				} );
			}

			return this.modal;

		}

	};

	$( window ).on( 'elementor:init', JetThemeEditor.init );

})( jQuery );
