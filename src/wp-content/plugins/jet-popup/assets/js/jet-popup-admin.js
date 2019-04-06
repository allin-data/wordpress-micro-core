( function( $ ) {

	'use strict';

	var JetPopupAdmin = {

		init: function() {

			JetPopupAdmin.importInit();

			if ( $( '#jet-popup-library-page')[0] ) {
				JetPopupAdmin.presetLibraryInit();
			}

			if ( $( '#jet-popup-settings-page')[0] ) {
				JetPopupAdmin.settingPageInit();
			}

		},

		importInit: function() {
			var $importButton = $( '#jet-popup-import-trigger' ),
				$createButton = $( '#jet-popup-create-trigger' );

			if ( ! $( '#wpbody-content .page-title-action' )[0] ) {
				return false;
			}

			$( '#wpbody-content' ).find( '.page-title-action:last' ).after( $importButton );
			$( '#wpbody-content' ).find( '.page-title-action:last' ).after( $createButton );

			var importTippy = tippy( [ $importButton[0] ], {
					html: document.querySelector( '#jet-popup-import-form' ),
					//appendTo: $importButton[0],
					arrow: true,
					placement: 'bottom',
					flipBehavior: 'clockwise',
					trigger: 'click',
					interactive: true,
					hideOnClick: true,
					theme: 'jet-popup-light'
				}
			);

			var createTippy = tippy( [ $createButton[0] ], {
				html: document.querySelector( '#jet-popup-create-form' ),
				//appendTo: $importButton[0],
				arrow: true,
				placement: 'bottom',
				flipBehavior: 'clockwise',
				trigger: 'click',
				interactive: true,
				hideOnClick: true,
				theme: 'jet-popup-light'
			} );
		},

		presetLibraryInit: function() {
			Vue.config.devtools = true;

			Vue.component( 'preset-filters', {
				template: '#preset-filters-template',

				props: {
					categories: Array,
					activeCategories: Array
				},

				data: function(){
					return {
						filterByValue: 'date'
					}
				},

				methods: {
					filterByCategory: function( state, category ) {
						eventBus.$emit( 'filterBycategory', state, category );
					},

					filterBy: function( option ) {
						this.filterByValue = option;

						eventBus.$emit( 'filterBy', option );
					}
				}
			});

			Vue.component( 'preset-list', {
				template: '#preset-list-template',

				props: {
					presets: Array
				},

				methods: {
					changePage: function( page ) {
						console.log(changePage);
					}
				}
			});

			Vue.component( 'preset-item', {
				template: '#preset-item-template',

				props: {
					presetId: Number,
					title: String,
					thumbUrl: String,
					category: Array,
					categoryNames: Array,
					install: Number,
					required: Array,
					excerpt: String,
					details: Array,
					permalink: String
				},

				data: function() {
					return {
						modalShow: false,
						requiredPluginData: window.jetPopupData.requiredPluginData
					}
				},

				computed: {
					categoryName: function() {
						var name = 'None';

						if ( 0 !== this.categoryNames.length ) {
							name = '';

							this.categoryNames.forEach( function( item, i ) {
								name += item;
							} );

							name = this.categoryNames.join( ', ' );
						}

						return name;
					},

					requiredPlugins: function() {
						var plugins            = [],
							requiredPluginData = this.requiredPluginData;

						this.required.forEach( function( item, i ) {
							if ( requiredPluginData.hasOwnProperty( item ) ) {
								plugins.push( requiredPluginData[item] );
							}
						} );

						return plugins;
					}
				},

				methods: {
					openModal: function() {
						this.modalShow = true;
					},

					createPopup: function(){
						eventBus.$emit( 'createPopup', this.presetId );
					}
				}
			});

			Vue.component( 'presetlibrary', {
				template: '#preset-library-template',

				data: function() {
					return ({
						spinnerShow: true,
						presetsLoaded: false,
						presetsLoadedError: false,
						categoriesLoaded: false,
						presetsData: [],
						categoryData: [],
						activeCategories: [],
						presetsLength: false,
						page: 1,
						perPage: 8,
						preset: false,
					})
				},

				computed: {
					presetList: function() {
						var currentCategories = this.activeCategories,
							currentPage       = this.page,
							perPage           = this.perPage,
							filteredData      = [];

						filteredData = this.presetsData.filter( function( preset, index ) {
							var flag = false;

							for ( var category in currentCategories ) {

								if ( -1 !== preset.category.indexOf( currentCategories[ category ] ) ) {
									flag = true;
									break;
								}
							}

							if ( 0 === currentCategories.length ) {
								flag = true;
							}

							return flag;
						} );

						this.presetsLength = filteredData.length;

						filteredData = filteredData.filter( function( preset, index ) {
							var flag  = false,
								left  = ( currentPage - 1 ) * perPage,
								right = left + perPage;

							if ( index >= left && index < right ) {
								flag = true;
							}

							return flag;
						} );

						return filteredData;
					},

					isShowPagination: function() {
						return this.presetsLength > this.perPage;
					}
				},

				methods: {
					filterByCategory: function( state, category ) {

						this.page = 1;

						if ( state ) {
							if ( -1 == this.activeCategories.indexOf( category ) ) {
								this.activeCategories.push( category );
							}
						} else {
							var index = this.activeCategories.indexOf( category );

							this.activeCategories.splice( index, 1 );
						}
					},

					filterBy: function( option ) {

						this.page = 1;

						switch( option ) {
							case 'date':
								this.presetsData.sort( function ( a, b ) {

									return a.order - b.order;
								});

								break;

							case 'name':
								this.presetsData.sort( function ( a, b ) {
									var aTitle = a.title.toLowerCase(),
										bTitle = b.title.toLowerCase();

									if ( aTitle > bTitle ) {
										return 1;
									}

									if ( aTitle < bTitle ) {
										return -1;
									}

									return 0;
								});

								break;

							case 'popular':
								this.presetsData.sort( function ( a, b ) {

									return b.install - a.install;
								});

								break;
						}

					},

					changePage: function( page ) {
						this.page = page;
					}
				},

				created: function() {
					var libraryPresetsUrl         = window.jetPopupData.libraryPresetsUrl,
						libraryPresetsCategoryUrl = window.jetPopupData.libraryPresetsCategoryUrl,
						categories                = [],
						presets                   = [],
						vueInstance               = this;

					axios.get( libraryPresetsUrl ).then( function ( response ) {
						var data = response.data;

						if ( data.success ) {
							for ( var preset in data.presets ) {
								var presetData = data.presets[ preset ];

								presets.push( {
									id: presetData['id'],
									title: presetData['title'],
									thumb: presetData['thumb'],
									category: presetData['category'],
									categoryNames: presetData['category_names'],
									order: presetData['order'],
									install: +presetData['install'],
									required: presetData['required'],
									excerpt: presetData['excerpt'],
									details: presetData['details'],
									permalink: presetData['permalink'],
								} );

							}

							vueInstance.presetsData = presets;
							vueInstance.spinnerShow = false;
							vueInstance.presetsLoaded = true;
						} else {
							vueInstance.spinnerShow = false;
							vueInstance.presetsLoadedError = true;
						}

					}).catch(function (error) {
						// handle error
						vueInstance.spinnerShow = false;
						vueInstance.presetsLoadedError = true;
						vueInstance.presetsData = [];
					});

					axios.get( libraryPresetsCategoryUrl ).then( function ( response ) {
						var data = response.data;

						if ( data.success ) {
							for ( var category in data.categories ) {
								categories.push( {
									id: category,
									label: data.categories[category],
									state: false
								} );
							}

							vueInstance.categoryData = categories;
						}

						vueInstance.categoriesLoaded = true;

					}).catch( function ( error ) {
						vueInstance.categoryData = [];
					});

					// Bus Events
					eventBus.$on( 'createPopup', function ( presetId ) {
						vueInstance.preset = presetId;

						setTimeout( function(){
							vueInstance.$refs.jetPopupLibraryForm.submit();
						}, 100 );

					});

					eventBus.$on( 'filterBycategory', function ( state, category ) {
						vueInstance.filterByCategory( state, category );
					});

					eventBus.$on( 'filterBy', function ( option ) {
						vueInstance.filterBy( option );
					});

				},
			});

			var eventBus = new Vue();

			var libraryPage = new Vue( {
				el: '#jet-popup-library-page',
			} );
		},

		settingPageInit: function() {
			Vue.config.devtools = true;

			Vue.component( 'mailchimp-list-item', {
				template: '#mailchimp-list-item-template',

				props: {
					list: Object,
					apikey: String
				},

				data: function() {
					return {
						mergeFieldsStatusLoading: false
					}
				},

				computed: {
					isMergeFields: function() {

						return this.list.hasOwnProperty( 'mergeFields' ) && ! jQuery.isEmptyObject( this.list[ 'mergeFields' ] ) ? true : false;
					}
				},

				methods: {
					getMergeFields: function() {
						var vueInstance = this;

						jQuery.ajax( {
							type: 'POST',
							url: ajaxurl,
							data: {
								'action': 'get_mailchimp_list_merge_fields',
								'apikey': this.apikey,
								'listid': this.list.id
							},
							beforeSend: function( jqXHR, ajaxSettings ) {
								vueInstance.mergeFieldsStatusLoading = true;
							},
							error: function( data, jqXHR, ajaxSettings ) {

							},
							success: function( data, textStatus, jqXHR ) {
								vueInstance.mergeFieldsStatusLoading = false;

								switch ( data.type ) {
									case 'success':
										vueInstance.$Notice.success( {
											title: data.title,
											desc: data.desc
										} );

										eventBus.$emit( 'updateListMergeFields', data.request );
										break;
									case 'error':
										vueInstance.$Notice.error( {
											title: data.title,
											desc: data.desc
										} );
										break;
								}
							}
						} );
					}
				}
			});

			Vue.component( 'settingsform', {
				template: '#settings-form-template',

				data: function() {
					return ( {
						modalSave: false,
						saveStatusLoading: false,
						syncStatusLoading: false,
						mergeFieldsStatusLoading: false,
						collapse: 'mailChimpPanel',
						settingsData: {
							'apikey': ''
						},
						mailchimpAccountData: {},
						mailchimpListsData: {}
					} )
				},

				computed: {
					isMailchimpAccountData: function() {
						return ! jQuery.isEmptyObject( this.mailchimpAccountData ) ? true : false;
					},

					isMailchimpListsData: function() {
						return ! jQuery.isEmptyObject( this.mailchimpListsData ) ? true : false;
					}
				},

				created: function() {
					var vueInstance = this,
						settings    = window.jetPopupAdminData.settings,
						mailchimpApiData = window.jetPopupAdminData.mailchimpApiData;

					this.settingsData = settings;

					if ( mailchimpApiData.hasOwnProperty( settings['apikey'] ) ) {
						var user = mailchimpApiData[ settings['apikey'] ];

						if ( user.hasOwnProperty( 'account' ) ) {
							var account = user.account;

							this.mailchimpAccountData = {
								account_id: account.account_id,
								username: account.username || '-',
								first_name: account.first_name || '-',
								last_name: account.last_name || '-',
								avatar_url: account.avatar_url
							};
						}

						if ( user.hasOwnProperty( 'lists' ) ) {
							var lists     = user.lists,
								tempLists = {};

							if ( ! jQuery.isEmptyObject( lists ) ) {
								for ( var key in lists ) {
									var listInfo    = lists[ key ]['info'],
										mergeFields = lists[ key ]['merge_fields'] || [],
										mergeFieldsTemp = {};

									mergeFields.forEach( function( field, i, arr ) {
										mergeFieldsTemp[ field['tag'] ] = field['name'];
									} );

									tempLists[ key ] = {
										id: listInfo.id,
										name: listInfo.name,
										memberCount: listInfo.stats.member_count,
										doubleOptin: listInfo.double_optin,
										dateCreated: listInfo.date_created,
										mergeFields: mergeFieldsTemp
									};
								}
							}

							this.mailchimpListsData = tempLists;

						}
					}

					// Bus Events
					eventBus.$on( 'updateListMergeFields', function ( request ) {
						var listid          = request.list_id,
							mergeFields     = request.merge_fields,
							mergeFieldsTemp = {};

						for ( key in mergeFields ) {
							var fieldData = mergeFields[ key ];

							mergeFieldsTemp[ fieldData['tag'] ] = fieldData['name'];
						}

						Vue.set( vueInstance.mailchimpListsData[ listid ], 'mergeFields', mergeFieldsTemp );
					});
				},

				methods: {
					mailchimpSync: function() {
						var vueInstance = this;

						jQuery.ajax( {
							type: 'POST',
							url: ajaxurl,
							data: {
								'action': 'get_mailchimp_user_data',
								'apikey': this.settingsData.apikey
							},
							beforeSend: function( jqXHR, ajaxSettings ) {
								vueInstance.syncStatusLoading = true;
							},
							error: function( jqXHR, ajaxSettings ) {},
							success: function( data, textStatus, jqXHR ) {
								switch ( data.type ) {
									case 'success':
										var dataRequest = data.request;

										vueInstance.$Notice.success( {
											title: data.title,
											desc: data.desc
										} );

										vueInstance.mailchimpAccountData = {
											account_id: dataRequest.account_id,
											username: dataRequest.username || '-',
											first_name: dataRequest.first_name || '-',
											last_name: dataRequest.last_name || '-',
											avatar_url: dataRequest.avatar_url
										};

										vueInstance.mailchimpSyncLists();

										break;
									case 'error':
										vueInstance.syncStatusLoading = false;

										vueInstance.$Notice.error( {
											title: data.title,
											desc: data.desc
										} );
										break;
								}
							}
						} );

					},

					mailchimpSyncLists: function() {
						var vueInstance = this;

						jQuery.ajax( {
							type: 'POST',
							url: ajaxurl,
							data: {
								'action': 'get_mailchimp_lists',
								'apikey': this.settingsData.apikey
							},
							beforeSend: function( jqXHR, ajaxSettings ) {
								vueInstance.syncStatusLoading = true;
							},
							error: function( jqXHR, ajaxSettings ) {

							},
							success: function( data, textStatus, jqXHR ) {

								vueInstance.syncStatusLoading = false;

								switch ( data.type ) {
									case 'success':
										vueInstance.$Notice.success( {
											title: data.title,
											desc: data.desc
										} );

										var request = data.request;

										if ( request.hasOwnProperty( 'lists' ) ) {
											var lists     = request['lists'],
												tempLists = {};

											for ( var key in lists ) {
												var listData = lists[ key ];

												tempLists[ listData.id ] = {
													id: listData.id,
													name: listData.name,
													memberCount: listData.stats.member_count,
													doubleOptin: listData.double_optin,
													dateCreated: listData.date_created
												}
											}

											vueInstance.mailchimpListsData = tempLists;
										}

										break;
									case 'error':
										vueInstance.$Notice.error( {
											title: data.title,
											desc: data.desc
										} );
										break;
								}
							}
						} );
					},

					saveSettings: function() {
						var vueInstance = this,
							data = {
								'action': 'jet_popup_save_settings',
								'data': this.settingsData
							};

						jQuery.ajax( {
							type: 'POST',
							url: ajaxurl,
							data: data,
							beforeSend: function( jqXHR, ajaxSettings ) {
								vueInstance.saveStatusLoading = true;
							},
							error: function( data, jqXHR, ajaxSettings ) {

							},
							success: function( data, textStatus, jqXHR ) {
								vueInstance.saveStatusLoading = false;

								switch ( data.type ) {
									case 'success':
										vueInstance.$Notice.success( {
											title: data.title,
											desc: data.desc
										} );
										break;
									case 'error':
										vueInstance.$Notice.error( {
											title: data.title,
											desc: data.desc
										} );
										break;
								}
							}
						} );
					},

					openSaveModal: function() {
						this.modalSave = true;
					}
				}

			});

			var eventBus = new Vue();

			var settingsPage = new Vue( {
				el: '#jet-popup-settings-page',
			} );
		}

	};

	JetPopupAdmin.init();

}( jQuery ) );
