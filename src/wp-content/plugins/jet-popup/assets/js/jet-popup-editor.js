( function( $ ) {

	'use strict';

	var JetPopupEditor,
		JetPopupControlsViews,
		JetPopupConditionsManager;

	JetPopupControlsViews = {

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

			window.elementor.addControlView( 'jet_popup_search', self.JetSearchView );

		}

	};

	JetPopupConditionsManager = {

		modal: false,

		conditionsManager: false,

		conditionManagerData: window.jetPopupData.conditionManager,

		conditionsData: window.jetPopupData.conditionManager.conditionsData,

		popupConditions: window.jetPopupData.conditionManager.popupConditions,

		eventBus: new Vue(),

		init: function() {
			var self  = this,
				panel = jQuery( '#elementor-editor-wrapper' );

			Vue.config.devtools = true;
			iview.lang('en-US');

			JetPopupConditionsManager.createModal();

			panel.on( 'click', 'button[data-event="jet-popup-conditions-manager"]', function( event ) {
				var $button = $( event.currentTarget );

				JetPopupConditionsManager.getModal().show();
			} );
		},

		createModal: function() {

			JetPopupConditionsManager.modal = elementor.dialogsManager.createWidget( 'lightbox', {
				id: 'jet-popup-conditions-manager-modal',
				message: '<div id="jet-popup-conditions-manager"><conditions-manager/></div>',
				closeButton: true,
				closeButtonClass: 'eicon-close',
				hide: {
					onBackgroundClick: true
				},
				onShow: function() {

					if ( ! JetPopupConditionsManager.conditionsManager ) {
						JetPopupConditionsManager.render();
					}
				}
			} );
		},

		getModal: function() {

			return JetPopupConditionsManager.modal;
		},

		render: function() {

			Vue.component( 'conditions-item', {
				template: '#tmpl-jet-popup-condition-item',

				props: {
					id: String,
					rawCondition: Object
				},

				data: function() {
					return ( {
						сondition: this.rawCondition,
						ajaxAction: false,
						requestLoading: false,
						requestList: [],
						groupVisible: true,
						subGroupVisible: false,
						subGroupOptionsVisible: false

					} )
				},

				computed: {

					groupList: function() {
						var groupList = [],
							groups    = JetPopupConditionsManager.conditionsData;

						for ( var group in groups ) {
							groupList.push( {
								value: group,
								label: groups[group]['label']
							} );
						}

						return groupList;
					},

					subGroupList: function() {

						var subGroupList = [],
							subGroups    = JetPopupConditionsManager.conditionsData[this.сondition.group]['sub-groups'];

						for ( var subGroup in subGroups ) {
							subGroupList.push( {
								value: subGroup,
								label: subGroups[subGroup]['label']
							} );
						}

						this.сondition.subGroup = subGroupList[0]['value'];

						return subGroupList;
					},

					subGroupOptionsList: function() {
						return this.requestList;
					}
				},

				methods: {

					groupChange: function( option ) {
						this.changeConditionView();
					},

					subGroupChange: function( option ) {
						this.changeConditionView();
					},

					deleteCondition: function() {
						JetPopupConditionsManager.eventBus.$emit( 'removeCondition', this.id );
					},

					changeConditionView: function() {

						if ( JetPopupConditionsManager.conditionsData[this.сondition.group].hasOwnProperty( 'sub-groups' ) ) {
							this.subGroupVisible = true;
						} else {
							this.ajaxAction = false;
							this.subGroupVisible = false;
							this.subGroupOptionsVisible = false;
							this.requestList = [];

							return false;
						}

						var subGroupData = JetPopupConditionsManager.conditionsData[this.сondition.group]['sub-groups'][this.сondition.subGroup];

						if ( subGroupData && subGroupData.hasOwnProperty( 'action' ) && subGroupData['action'] ) {
							this.subGroupVisible = true;
							this.subGroupOptionsVisible = true;
							this.ajaxAction = subGroupData['action'];

							this.getRemoteItems('');
						} else {
							this.ajaxAction = false;
							this.subGroupOptionsVisible = false;
							this.requestList = [];
						}

					},

					getRemoteItems: function( query ) {
						var vueInstance = this;

						if ( ! this.ajaxAction ) {
							return false;
						}

						var libraryPresetsUrl = ajaxurl + '?action=' + this.ajaxAction + '&q=' + query;

						vueInstance.requestLoading = true;

						axios.get( libraryPresetsUrl ).then( function ( response ) {
							var requestData = response.data.results,
								requestList = [];

							vueInstance.requestLoading = false;

							for ( var item in requestData ) {
								var itemValue = requestData[item]['id'];

								requestList.push( {
									value: itemValue.toString(),
									label: requestData[item]['text']
								} );
							}

							vueInstance.requestList = requestList;

						}).catch(function (error) {
							vueInstance.requestLoading = false;
						});
					}
				},

				created: function() {
					this.changeConditionView();
				}

			} );

			Vue.component( 'conditions-manager', {
				template: '#tmpl-jet-popup-condition-manager',

				data: function() {
					return ( {
						conditions: [],
						saveStatusLoading: false
					} )
				},

				computed: {
					emptyConditions: function() {
						return ( 0 === this.conditions.length ) ? true : false;
					},

					popupConditions: function() {
						return this.conditions;
					}
				},

				methods: {
					addCondition: function() {
						var newCond = {
							id: JetPopupConditionsManager.genetateUniqId(),
							include: 'true',
							group: 'entire',
							subGroup: '',
							subGroupValue: ''
						};

						this.conditions.unshift( newCond );
					},

					saveCondition: function() {
						var vueInstance = this;

						jQuery.ajax( {
							url: ajaxurl,
							type: 'POST',
							data: {
								'action': 'jet_popup_save_conditions',
								'popup_id': JetPopupConditionsManager.conditionManagerData['popupId'],
								'conditions': vueInstance.conditions
							},
							beforeSend: function( jqXHR, ajaxSettings ) {
								vueInstance.saveStatusLoading = true;
							},
							error: function( jqXHR, ajaxSettings ) {},
							success: function( data, textStatus, jqXHR ) {
								vueInstance.saveStatusLoading = false;

								switch( data['type'] ) {
									case 'success':
										vueInstance.$Notice.success( {
											title: data['message']['title'],
											desc: data['message']['desc']
										} );
										break;
									case 'error':
										vueInstance.$Notice.error( {
											title: data['message']['title'],
											desc: data['message']['desc']
										} );
										break;
								}

							}
						} );
					}
				},

				created: function() {

					var vueInstance     = this,
						popupConditions = JetPopupConditionsManager.popupConditions || [];

					popupConditions.map( function( condition ) {
						condition['id'] = JetPopupConditionsManager.genetateUniqId();
						//condition['include'] = ( 'true' === condition['include'] || true === condition['include'] ) ? true : false;
						//condition['include'] = ( 'true' === condition['include'] || true === condition['include'] ) ? true : false;

						return condition;
					} );

					this.conditions = popupConditions;

					JetPopupConditionsManager.eventBus.$on( 'removeCondition', function ( id ) {

						var popupConditions = vueInstance.conditions;

						vueInstance.conditions = popupConditions.filter( function( condition ) {
							return condition['id'] !== id;
						} );
					});
				}
			} );

			var eventBus = new Vue();

			JetPopupConditionsManager.conditionsManager = new Vue( {
				el: '#jet-popup-conditions-manager'
			} );
		},

		genetateUniqId: function() {
			return '_' + Math.random().toString(36).substr(2, 9);
		}
	};

	JetPopupEditor = {
		init: function() {

			elementor.settings.page.model.on( 'change', JetPopupEditor.onPageSettingsChange );

			JetPopupControlsViews.init();

			JetPopupConditionsManager.init();
		},

		onPageSettingsChange: function( model ) {
			var iframe   = document.getElementById( 'elementor-preview-iframe' ),
				innerDoc = iframe.contentDocument || iframe.contentWindow.document;

			if ( model.changed.hasOwnProperty( 'close_button_icon' ) ) {
				var closeButton = $( '.jet-popup__close-button', innerDoc ),
					icon        = model.changed['close_button_icon'];

				$( 'i', closeButton ).attr( 'class', icon );
			}

			if ( model.changed.hasOwnProperty( 'use_close_button' ) ) {
				var useCloseButton = ( 'yes' === model.changed['use_close_button'] ) ? true : false,
					closeButton    = $( '.jet-popup__close-button', innerDoc );

				if ( useCloseButton ) {
					closeButton.removeClass( 'hidden' );
				} else {
					closeButton.addClass( 'hidden' );
				}
			}

			if ( model.changed.hasOwnProperty( 'use_overlay' ) ) {
				var useOverlay = ( 'yes' === model.changed['use_overlay'] ) ? true : false,
					$overlay   = $( '.jet-popup__overlay', innerDoc );

				if ( useOverlay ) {
					$overlay.removeClass( 'hidden' );
				} else {
					$overlay.addClass( 'hidden' );
				}
			}

			if ( model.changed.hasOwnProperty( 'jet_popup_animation' ) ) {
				var $popup = $( '.jet-popup', innerDoc );

				JetPopupEditor.animationPopup( $popup, model.changed['jet_popup_animation'] );
			}

		},

		animationPopup: function( $popup, effect ) {
			var animeContainer         = null,
				animeContainerSettings = jQuery.extend(
					{
						targets: $( '.jet-popup__container', $popup )[0]
					},
					JetPopupEditor.avaliableEffects[ effect ][ 'show' ]
				);

			animeContainer = anime( animeContainerSettings );
		},

		avaliableEffects: {
			'fade' : {
				'show': {
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					duration: 1000,
					delay: 200,
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
					duration: 1000,
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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
					delay: 200,
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

		}

	};

	$( window ).on( 'elementor:init', JetPopupEditor.init );

}( jQuery ) );


