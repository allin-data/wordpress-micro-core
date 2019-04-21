# Jet Popup For Elementor

Build popup with any layout in drag&drop way, change its position and trigger event in few clicks.

# ChangeLog

## [1.2.6](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.6)
* ADD: Jet Engine Compatibility

## [1.2.5](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.5)
* ADD: Condition manager
* ADD: Action button widget / link navigation and close option

## [1.2.4](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.4)
* ADD: z index option
* ADD: ajax loading content

## [1.2.2](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.2)
* ADD: RU localization
* UPD: Popup generator
* UPD: Popups library / item install count banner

## [1.2.1](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.1)
* ADD: attached popup / click to widget / cursor style
* ADD: Mailchimp widget
* ADD: custom selector trigger
* FIX: action button shadow styles
* FIX: import preset

## [1.2.0](https://github.com/ZemezLab/jet-popup/releases/tag/1.2.0)

* Add MailChimp widget
* Add "Close Popup When Success" option
* Add "Custom Selector" open event trigger

## [1.0.0](https://github.com/ZemezLab/jet-popup/releases/tag/1.0.0)

* Init


# Dev Api

## JS Api

### Open Popup Trigger

For popup opening use jq trigger with type "jet-popup-open-trigger". For attaching additional data use popupData param.
'popupId' is required param which defines popup for opening, for example 'jet-popup-656'. If you need to make a request every time set forceLoad: true

```
$( window ).trigger( {
	type: 'jet-popup-open-trigger',
	popupData: {
		popupId: jet-popup-656,
		forceLoad: true,
		postId: 3,
		customClass: 'my-class'
	}
} );
```

### Close Popup Trigger

For popup opening use jq trigger with type "jet-popup-close-trigger". 'popupId' is required param which defines popup for closing, for example 'jet-popup-656'

```
$( window ).trigger( {
	type: 'jet-popup-close-trigger',
	popupData: {
		popupId: jet-popup-656,
		constantly: true
	}
} );
```

### Using Example

```
elementorWidget: function( $scope ) {
	var widget_id   = $scope.data( 'id' ),
		widgetType  = $scope.data( 'element_type' ),
		widgetsData = jetPopupData.elements_data.widgets;

	if ( widgetsData.hasOwnProperty( widget_id ) ) {
		var widgetData     = widgetsData[ widget_id ];

		$scope.on( 'click.JetPopup', function( event ) {
			event.preventDefault();

			var $target = $( this );

			$( window ).trigger( {
				type: 'jet-popup-open-trigger',
				popupData: {
					popupId: widgetData[ 'attached-popup' ],
				}
			} );

			return false;
		} );
	}
}
```

## PHP Api

## Filters

'jet-popup/ajax-request/post-data' - ajax request sended data

'jet-popup/ajax-request/get-elementor-content' - it is necessary to redifine the  $content value to return it as a result of the query

```
add_filter( 'jet-popup/ajax-request/get-elementor-content', 'jet_popup_get_elementor_content', 10, 2 );

function jet_popup_get_elementor_content( $content, $popup_data ) {

	$popup_id = $popup_data['popup_id'];

	if ( empty( $popup_id ) ) {
		return false;
	}

	$plugin = Elementor\Plugin::instance();

	global $post;
	$post = get_post( $popup_data['postId'] );
	setup_postdata( $post, null, false );

	$content = $plugin->frontend->get_builder_content( $popup_id );

	wp_reset_postdata( $post );

	return $content;
}
```
