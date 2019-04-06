<?php
/**
 * Handles custom post meta boxes for the projects post type.
 *
 * @package   Cherry_Projects
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2014 Cherry Team
 */

/**
 * Class for metabox rendering.
 *
 * @since 1.0.0
 */
class Cherry_Popups_Meta_Boxes {

	/**
	 * Holds the instances of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Metaboxes rendering.
		add_action( 'load-post.php',     array( $this, 'init' ), 10 );
		add_action( 'load-post-new.php', array( $this, 'init' ), 10 );
	}

	/**
	 * Run initialization of modules.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$prefix = 'cherry-';

		cherry_popups()->get_core()->init_module( 'cherry-post-meta', array(
			'id'            => 'popup-settings',
			'title'         => esc_html__( 'Popup settings', 'cherry-popups' ),
			'page'          => array( CHERRY_POPUPS_NAME ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
				'popup_meta_data' => array(
					'type'   => 'settings',
					'element' => 'settings',
				),
				'tab_vertical' => array(
					'type'    => 'component-tab-vertical',
					'element' => 'component',
					'parent'  => 'popup_meta_data',
					'class'   => 'cherry-popup-tabs-wrapper',
				),
				'general_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'General', 'cherry-popups' ),
					'description' => esc_html__( 'General popup settings', 'cherry-popups' ),
				),
				'overlay_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Overlay', 'cherry-popups' ),
					'description' => esc_html__( 'Overlay popup settings', 'cherry-popups' ),
				),
				'style_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Style', 'cherry-popups' ),
					'description' => esc_html__( 'Styles popup settings', 'cherry-popups' ),
				),
				'open_page_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( '"Open" page settings', 'cherry-popups' ),
					'description' => esc_html__( '"Open" page settings', 'cherry-popups' ),
				),
				'close_page_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( '"Close" page settings', 'cherry-popups' ),
					'description' => esc_html__( '"Close" page settings', 'cherry-popups' ),
				),
				'custom_opening_event' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Custom openning event', 'cherry-popups' ),
					'description' => esc_html__( 'Custom popup openning event', 'cherry-popups' ),
				),
				'advanced_tab' => array(
					'element'     => 'settings',
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Advanced settings', 'cherry-popups' ),
					'description' => esc_html__( 'Popup advanced settings', 'cherry-popups' ),
				),

				$prefix . 'layout-type' => array(
					'type'          => 'radio',
					'parent'        => 'general_tab',
					'title'         => esc_html__( 'Popup layout type', 'cherry-popups' ),
					'description'   => esc_html__( 'Choose popup layout type', 'cherry-popups' ),
					'value'         => 'center',
					'class'         => '',
					'display_input' => false,
					'options'       => array(
						'center' => array(
							'label'   => esc_html__( 'Center', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/layout-type-center.svg',
						),
						'center-fullwidth' => array(
							'label'   => esc_html__( 'Center & fullwidth', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/layout-type-center-fullwidth.svg',
						),
						'bottom' => array(
							'label'   => esc_html__( 'Bottom & fullwidth', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/layout-type-bottom.svg',
						),
						'fullwidth' => array(
							'label'   => esc_html__( 'Fullwidth', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/layout-type-fullwidth.svg',
						),
					),
				),
				$prefix . 'show-hide-animation' => array(
					'type'          => 'radio',
					'parent'        => 'general_tab',
					'title'         => esc_html__( 'Show/Hide animation', 'cherry-popups' ),
					'description'   => esc_html__( 'Choose popup show/hide animation', 'cherry-popups' ),
					'value'         => 'simple-fade',
					'class'         => '',
					'display_input' => false,
					'options'       => array(
						'simple-fade' => array(
							'label'   => esc_html__( 'Fade', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/hover-fade.svg',
						),
						'simple-scale' => array(
							'label'   => esc_html__( 'Scale', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/hover-scale.svg',
						),
						'move-up' => array(
							'label'   => esc_html__( 'Move Up', 'cherry-popups' ),
							'img_src' => CHERRY_POPUPS_URI . 'assets/img/svg/hover-move-up.svg',
						),
					),
				),
				$prefix . 'popup-base-style' => array(
					'type'        => 'select',
					'parent'      => 'general_tab',
					'title'       => esc_html__( 'Base style preset', 'cherry-popups' ),
					'description' => esc_html__( 'Select base style preset', 'cherry-popups' ),
					'multiple'    => false,
					'filter'      => true,
					'value'       => 'default',
					'options'     => array(
						'default' => esc_html__( 'Default', 'cherry-popups' ),
						'light'   => esc_html__( 'Light', 'cherry-popups' ),
						'dark'    => esc_html__( 'Dark', 'cherry-popups' ),
						'blue'    => esc_html__( 'Blue', 'cherry-popups' ),
						'red'     => esc_html__( 'Red', 'cherry-popups' ),
					),
					'placeholder' => 'Select',
					'label'       => '',
					'class'       => '',
				),
				$prefix . 'popup-type' => array(
					'type'        => 'select',
					'parent'      => 'general_tab',
					'title'       => esc_html__( 'Popup content type', 'cherry-popups' ),
					'description' => esc_html__( 'Select popup type', 'cherry-popups' ),
					'multiple'    => false,
					'filter'      => true,
					'value'       => 'default',
					'options'     => array(
						'default' => esc_html__( 'Default(Content + Subscribe form)', 'cherry-popups' ),
						'simple'    => esc_html__( 'Simple(Title + Content)', 'cherry-popups' ),
						'content'   => esc_html__( 'Content Only', 'cherry-popups' ),
						'login'     => esc_html__( 'Login Form', 'cherry-popups' ),
						'signup'    => esc_html__( 'New User Register Form', 'cherry-popups' ),
						'subscribe' => esc_html__( 'Subscribe Form', 'cherry-popups' ),
					),
					'placeholder' => 'Select',
					'label'       => '',
					'class'       => '',
				),
				$prefix . 'show-once' => array(
					'type'         => 'switcher',
					'parent'       => 'general_tab',
					'title'        => esc_html__( 'Show once', 'cherry-popups' ),
					'description'  => esc_html__( 'PopUp will appears just once and won’t come up when you close it.', 'cherry-popups' ),
					'value'        => 'false',
					'toggle'       => array(
						'true_toggle'  => 'Enable',
						'false_toggle' => 'Disable',
					),
					'style'        => 'normal',
					'class'        => '',
					'label'        => '',
				),

				$prefix . 'overlay-type' => array(
					'type'          => 'radio',
					'parent'        => 'overlay_tab',
					'title'         => esc_html__( 'Type of overlay', 'cherry-popups' ),
					'description'   => esc_html__( 'Select type of overlay', 'cherry-popups' ),
					'value'         => 'fill-color',
					'display-input' => true,
					'options'       => array(
						'disabled' => array(
							'label' => esc_html__( 'Disabled', 'cherry-popups' ),
							'slave' => 'overlay-type-disabled',
						),
						'fill-color' => array(
							'label' => esc_html__( 'Fill color', 'cherry-popups' ),
							'slave' => 'overlay-type-fill-color',
						),
						'image' => array(
							'label' => esc_html__( 'Image', 'cherry-popups' ),
							'slave' => 'overlay-type-image',
						),
					),
				),

				$prefix . 'overlay-color' => array(
					'type'        => 'colorpicker',
					'parent'      => 'overlay_tab',
					'title'       => esc_html__( 'Overlay background color', 'cherry-popups' ),
					'description' => esc_html__( 'Set the color of popup overlay', 'cherry-popups' ),
					'value'       => '#000',
					'master'      => 'overlay-type-fill-color',
				),
				$prefix . 'overlay-opacity' => array(
					'type'        => 'slider',
					'parent'      => 'overlay_tab',
					'title'       => esc_html__( 'Overlay opacity', 'cherry-popups' ),
					'description' => esc_html__( 'Set the opacity(%) of popup overlay', 'cherry-popups' ),
					'max_value'   => 100,
					'min_value'   => 0,
					'value'       => 50,
					'master'      => 'overlay-type-fill-color',
				),
				$prefix . 'overlay-image' => array(
					'type'               => 'media',
					'parent'             => 'overlay_tab',
					'title'              => esc_html__( 'Overlay background image', 'cherry-popups' ),
					'description'        => esc_html__( 'Set image for popup overlay background', 'cherry-popups' ),
					'value'              => '',
					'multi_upload'       => false,
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Choose Image', 'cherry-popups' ),
					'class'              => '',
					'label'              => '',
					'master'             => 'overlay-type-image',
				),
				$prefix . 'overlay-close-area' => array(
					'type'         => 'switcher',
					'parent'       => 'overlay_tab',
					'title'        => esc_html__( 'Use Overlay as close button', 'cherry-popups' ),
					'description'  => esc_html__( 'Clicking on the overlay closes the popup', 'cherry-popups' ),
					'value'        => 'true',
					'toggle'       => array(
						'true_toggle'  => 'Enable',
						'false_toggle' => 'Disable',
					),
					'style'        => 'normal',
					'class'        => '',
					'label'        => '',
				),

				$prefix . 'container-bg-type' => array(
					'type'          => 'radio',
					'parent'        => 'style_tab',
					'title'         => esc_html__( 'Container background type', 'cherry-popups' ),
					'description'   => esc_html__( 'Select container background type', 'cherry-popups' ),
					'value'         => 'fill-color',
					'display-input' => true,
					'options'       => array(
						'fill-color' => array(
							'label' => esc_html__( 'Fill color', 'cherry-popups' ),
							'slave' => 'container-bg-type-fill-color',
						),
						'image' => array(
							'label' => esc_html__( 'Image', 'cherry-popups' ),
							'slave' => 'container-bg-type-image',
						),
					),
				),
				$prefix . 'container-color' => array(
					'type'        => 'colorpicker',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Container background color', 'cherry-popups' ),
					'description' => esc_html__( 'Set the color of popup container', 'cherry-popups' ),
					'value'       => '#fff',
					'master'      => 'container-bg-type-fill-color',
				),
				$prefix . 'container-opacity' => array(
					'type'        => 'slider',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Container opacity', 'cherry-popups' ),
					'description' => esc_html__( 'Set the opacity(%) of popup container', 'cherry-popups' ),
					'max_value'   => 100,
					'min_value'   => 0,
					'value'       => 100,
					'master'      => 'container-bg-type-fill-color',
				),
				$prefix . 'container-image' => array(
					'type'               => 'media',
					'parent'             => 'style_tab',
					'title'              => esc_html__( 'Container background image', 'cherry-popups' ),
					'description'        => esc_html__( 'Set image for container background', 'cherry-popups' ),
					'value'              => '',
					'multi_upload'       => false,
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Choose Image', 'cherry-popups' ),
					'class'              => '',
					'label'              => '',
					'master'             => 'container-bg-type-image',
				),
				$prefix . 'popup-width' => array(
					'type'        => 'slider',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Popup width', 'cherry-popups' ),
					'description' => esc_html__( 'Input Popup width(px)', 'cherry-popups' ),
					'max_value'   => 1000,
					'min_value'   => 300,
					'value'       => 620,
				),
				$prefix . 'popup-height' => array(
					'type'        => 'slider',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Popup height', 'cherry-popups' ),
					'description' => esc_html__( 'Input Popup height(px)', 'cherry-popups' ),
					'max_value'   => 800,
					'min_value'   => 200,
					'value'       => 560,
				),
				$prefix . 'popup-padding' => array(
					'type'        => 'slider',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Popup padding', 'cherry-popups' ),
					'description' => esc_html__( 'Input Popup padding(px)', 'cherry-popups' ),
					'max_value'   => 500,
					'min_value'   => 0,
					'value'       => 40,
				),

				$prefix . 'border-radius' => array(
					'type'        => 'slider',
					'parent'      => 'style_tab',
					'title'       => esc_html__( 'Popup border radius', 'cherry-popups' ),
					'description' => esc_html__( 'Define popup border radius(px)', 'cherry-popups' ),
					'max_value'   => 50,
					'min_value'   => 0,
					'value'       => 3,
				),

				$prefix . 'content-position' => array(
					'type'          => 'radio',
					'parent'        => 'style_tab',
					'title'         => esc_html__( 'Content Position', 'cherry-popups' ),
					'value'         => 'center',
					'display-input' => true,
					'options'       => array(
						'flex-start' => array(
							'label' => esc_html__( 'Top', 'cherry-popups' ),
						),
						'center' => array(
							'label' => esc_html__( 'Center', 'cherry-popups' ),
						),
						'flex-end' => array(
							'label' => esc_html__( 'Bottom', 'cherry-popups' ),
						),
						'stretch' => array(
							'label' => esc_html__( 'Stretch', 'cherry-popups' ),
						),
					),
				),

				$prefix . 'popup-open-appear-event' => array(
					'type'          => 'radio',
					'parent'        => 'open_page_tab',
					'title'         => esc_html__( '"Open page" popup appear event', 'cherry-popups' ),
					'description'   => esc_html__( 'Event to which will be popup open', 'cherry-popups' ),
					'value'         => 'page-load',
					'display-input' => true,
					'options'       => array(
						'page-load' => array(
							'label' => esc_html__( 'On page load', 'cherry-popups' ),
							'slave' => 'popup-open-appear-event-page-load',
						),
						'user-inactive' => array(
							'label' => esc_html__( 'Inactivity time after', 'cherry-popups' ),
							'slave' => 'popup-open-appear-event-user-inactive',
						),
						'scroll-page' => array(
							'label' => esc_html__( 'On page scrolling', 'cherry-popups' ),
							'slave' => 'popup-open-appear-event-scroll-page',
						),
					),
				),
				$prefix . 'page-load-open-delay' => array(
					'type'        => 'slider',
					'parent'      => 'open_page_tab',
					'title'       => esc_html__( 'Open delay', 'cherry-popups' ),
					'description' => esc_html__( 'Set the time delay(s) when the page loads', 'cherry-popups' ),
					'max_value'   => 60,
					'min_value'   => 0,
					'value'       => 1,
					'master'      => 'popup-open-appear-event-page-load',
				),
				$prefix . 'user-inactive-time' => array(
					'type'        => 'slider',
					'parent'      => 'open_page_tab',
					'title'       => esc_html__( 'User inactivity time', 'cherry-popups' ),
					'description' => esc_html__( 'Set user inactivity time delay(s)', 'cherry-popups' ),
					'max_value'   => 60,
					'min_value'   => 1,
					'value'       => 1,
					'master'      => 'popup-open-appear-event-user-inactive',
				),
				$prefix . 'page-scrolling-value' => array(
					'type'        => 'slider',
					'parent'      => 'open_page_tab',
					'title'       => esc_html__( 'Page scrolling value', 'cherry-popups' ),
					'description' => esc_html__( 'Open when user scroll % of page', 'cherry-popups' ),
					'max_value'   => 100,
					'min_value'   => 0,
					'value'       => 5,
					'master'      => 'popup-open-appear-event-scroll-page',
				),

				$prefix . 'popup-close-appear-event' => array(
					'type'          => 'radio',
					'parent'        => 'close_page_tab',
					'title'         => esc_html__( '"Close page" popup appear event', 'cherry-popups' ),
					'description'   => esc_html__( 'Event to which will be popup open', 'cherry-popups' ),
					'value'         => 'outside-viewport',
					'display-input' => true,
					'options'       => array(
						'outside-viewport' => array(
							'label' => esc_html__( 'Outside viewport', 'cherry-popups' ),
						),
						'page-focusout' => array(
							'label' => esc_html__( 'Page unfocus', 'cherry-popups' ),
						),
					),
				),

				$prefix . 'custom-event-type' => array(
					'type'          => 'radio',
					'parent'        => 'custom_opening_event',
					'title'         => esc_html__( 'Custom event type', 'cherry-popups' ),
					'description'   => esc_html__( 'Define custom event type', 'cherry-popups' ),
					'value'         => 'click',
					'display-input' => true,
					'options'       => array(
						'click' => array(
							'label' => esc_html__( 'Click', 'cherry-popups' ),
						),
						'hover' => array(
							'label' => esc_html__( 'Hover', 'cherry-popups' ),
						),
					),
				),

				$prefix . 'popup-selector' => array(
					'type'          => 'text',
					'parent'        => 'custom_opening_event',
					'title'         => esc_html__( 'Selector', 'cherry-popups' ),
					'description'   => esc_html__( 'jQuery selector for custom event', 'cherry-popups' ),
					'value'         => '',
				),

				$prefix . 'custom-class' => array(
					'type'          => 'text',
					'parent'        => 'advanced_tab',
					'title'         => esc_html__( 'Custom class', 'cherry-popups' ),
					'description'   => esc_html__( 'Popup custom class', 'cherry-popups' ),
					'value'         => '',
				),

			),
		) );

		cherry_popups()->get_core()->init_module( 'cherry-post-meta', array(
			'id'            => 'page-popup-settings',
			'title'         => esc_html__( 'Cherry Popups', 'cherry-popups' ),
			'page'          => array( 'page', 'post' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
				$prefix . 'open-page-popup' => array(
					'type'             => 'select',
					'parent'           => '',
					'title'            => esc_html__( 'Open Page Popup', 'cherry-popups' ),
					'description'      => esc_html__( 'Assign one of the popup that is displayed when you open the page.', 'cherry-popups' ),
					'multiple'         => false,
					'filter'           => true,
					'value'            => 'disable',
					'options'          => array(),
					'options_callback' => array( cherry_popups_init(), 'get_avaliable_popups' ),
					'placeholder'      => 'Select popup',
					'label'            => '',
					'class'            => '',
				),
				$prefix . 'close-page-popup' => array(
					'type'             => 'select',
					'parent'           => '',
					'title'            => esc_html__( 'Close Page Popup', 'cherry-popups' ),
					'description'      => esc_html__( 'Assign one of the popup that is displayed when you close the page', 'cherry-popups' ),
					'multiple'         => false,
					'filter'           => true,
					'value'            => 'disable',
					'options'          => array(),
					'options_callback' => array( cherry_popups_init(), 'get_avaliable_popups' ),
					'placeholder'      => 'Select popup',
					'label'            => '',
					'class'            => '',
				),
			),
		) );

	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

Cherry_Popups_Meta_Boxes::get_instance();
