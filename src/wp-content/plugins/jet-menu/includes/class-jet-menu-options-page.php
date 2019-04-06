<?php
/**
 * Option page Class
 */

// If class `Popups_Options_Page` doesn't exists yet.
if ( ! class_exists( 'Jet_Menu_Options_Page' ) ) {

	/**
	 * Jet_Menu_Options_Page class.
	 */
	class Jet_Menu_Options_Page {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Instance of the class Cherry_Interface_Builder.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $builder = null;

		/**
		 * HTML spinner.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $spinner = '<span class="loader-wrapper"><span class="loader"></span></span>';

		/**
		 * Dashicons.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $button_icon = '<span class="dashicons dashicons-yes icon"></span>';

		/**
		 * Fonts loader instance
		 *
		 * @var object
		 */
		protected $fonts_loader = null;

		/**
		 * Default options
		 *
		 * @var array
		 */
		public $default_options = array(
			'jet-menu-animation'             => 'fade',
			'jet-menu-mega-bg-type'          => 'fill-color',
			'jet-menu-mega-bg-color'         => '#fff',
			'jet-menu-mega-bg-color-opacity' => 100,
			'jet-menu-mega-bg-image'         => '',
			'jet-menu-mega-padding'          => array(
				'units'     => 'px',
				'is_linked' => true,
				'size'      => array(
					'top'       => '10',
					'right'     => '10',
					'bottom'    => '10',
					'left'      => '10',
				),
			),
		);

		/**
		 * Options cache
		 *
		 * @var boolean
		 */
		private $options = false;

		/**
		 * Slug DB option field.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $options_slug = 'jet_menu_options';

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			$this->fonts_loader = jet_menu()->get_core()->init_module(
				'cherry-google-fonts-loader',
				array(
					'prefix'  => $this->options_slug,
					'type'    => 'option',
					'single'  => true,
					'options' => array(
						'main' => array(
							'family'  => 'jet-top-menu-font-family',
							'style'   => 'jet-top-menu-font-style',
							'weight'  => 'jet-top-menu-font-weight',
							'charset' => 'jet-top-menu-subset',
						),
						'main-desc' => array(
							'family'  => 'jet-top-menu-desc-font-family',
							'style'   => 'jet-top-menu-desc-font-style',
							'weight'  => 'jet-top-menu-desc-font-weight',
							'charset' => 'jet-top-menu-desc-subset',
						),
						'sub' => array(
							'family'  => 'jet-sub-menu-font-family',
							'style'   => 'jet-sub-menu-font-style',
							'weight'  => 'jet-sub-menu-font-weight',
							'charset' => 'jet-sub-menu-subset',
						),
						'sub-desc' => array(
							'family'  => 'jet-sub-menu-desc-font-family',
							'style'   => 'jet-sub-menu-desc-font-style',
							'weight'  => 'jet-sub-menu-desc-font-weight',
							'charset' => 'jet-sub-menu-desc-subset',
						),
						'top-badge' => array(
							'family'  => 'jet-menu-top-badge-font-family',
							'style'   => 'jet-menu-top-badge-font-style',
							'weight'  => 'jet-menu-top-badge-font-weight',
							'charset' => 'jet-menu-top-badge-subset',
						),
						'sub-badge' => array(
							'family'  => 'jet-menu-sub-badge-font-family',
							'style'   => 'jet-menu-sub-badge-font-style',
							'weight'  => 'jet-menu-sub-badge-font-weight',
							'charset' => 'jet-menu-sub-badge-subset',
						),
					),
				)
			);

			$sys_messages = array(
				'invalid_base_data' => esc_html__( 'Unable to process the request without nonce or server error', 'jet-menu' ),
				'no_right'          => esc_html__( 'No right for this action', 'jet-menu' ),
				'invalid_nonce'     => esc_html__( 'Stop CHEATING!!!', 'jet-menu' ),
				'access_is_allowed' => '',
				'wait_processing'   => esc_html__( 'Please wait, processing the previous request', 'jet-menu' ),
			);

			jet_menu()->get_core()->init_module(
				'cherry-handler',
				array(
					'id'           => 'jet_menu_save_options_ajax',
					'action'       => 'jet_menu_save_options_ajax',
					'capability'   => 'manage_options',
					'callback'     => array( $this , 'jet_menu_save_options_callback' ),
					'sys_messages' => $sys_messages,
				)
			);

			jet_menu()->get_core()->init_module(
				'cherry-handler',
				array(
					'id'           => 'jet_menu_restore_options_ajax',
					'action'       => 'jet_menu_restore_options_ajax',
					'capability'   => 'manage_options',
					'callback'     => array( $this , 'jet_menu_restore_options_callback' ),
					'sys_messages' => $sys_messages,
				)
			);

			if ( is_admin() ) {
				// Load the admin menu.
				add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
				add_action( 'current_screen', array( $this, 'interface_builder_init' ) );

				add_action( 'admin_init', array( $this, 'process_export' ) );
				add_action( 'admin_init', array( $this, 'process_reset' ) );

			}

			add_action( 'wp_ajax_jet_menu_import_options', array( $this, 'process_import' ) );
			add_filter( 'jet-data-importer/export/options-to-export', array( $this, 'export_menu_options' ) );

		}

		/**
		 * Pass menu options key into exported options array
		 *
		 * @param  [type] $options [description]
		 * @return [type]          [description]
		 */
		public function export_menu_options( $options ) {
			$options[] = $this->options_slug;
			return $options;
		}

		/**
		 * Interface Builder Init
		 *
		 * @return void
		 */
		public function interface_builder_init() {
			$screen = get_current_screen();

			if ( 'toplevel_page_jet-menu' === $screen->base ) {
				$this->builder = jet_menu()->get_core()->init_module( 'cherry-interface-builder', array() );
			}
		}

		/**
		 * Register the admin menu.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function add_menu_item() {
			add_menu_page(
				esc_html__( 'JetMenu', 'jet-menu' ),
				esc_html__( 'JetMenu', 'jet-menu' ),
				'manage_options',
				jet_menu()->plugin_slug,
				array( $this, 'render_options_page' ),
				'',
				100
			);

		}

		/**
		 * Save options
		 *
		 * @since 1.0.0
		 */
		public function jet_menu_save_options_callback() {

			if ( ! empty( $_REQUEST['data'] ) ) {
				$data = $_REQUEST['data'];

				$this->save_options( $this->options_slug, $data );

			}
		}

		/**
		 * Restore options
		 *
		 * @since 1.0.0
		 */
		public function jet_menu_restore_options_callback() {
			$default_options = get_option( $this->options_slug . '_default' );
			$this->save_options( $this->options_slug, $default_options );
		}

		/**
		 * Render plugin options page.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function render_options_page() {

			$default_mobile_breakpoint = $this->get_default_mobile_breakpoint();

			jet_menu_dynmic_css()->init_builder( $this->builder );

			$this->builder->register_section(
				array(
					'jet_menu_options_section' => array(
						'type'        => 'section',
						'scroll'      => false,
						'title'       => esc_html__( 'JetMenu', 'jet-menu' ),
						'description' => esc_html__( 'General JetMenu Settings', 'jet-menu' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'jet-menu-options-form' => array(
						'type'    => 'form',
						'enctype' => 'multipart/form-data',
						'parent'  => 'jet_menu_options_section',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'option_page_content' => array(
						'type'   => 'settings',
						'parent' => 'jet-menu-options-form',
					),
					'option_page_footer' => array(
						'type'   => 'settings',
						'parent' => 'jet-menu-options-form',
						'class'  => 'option-page-footer',
					),
				)
			);

			$this->builder->register_component(
				array(
					'tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'option_page_content',
						'view'   => jet_menu()->get_template( 'admin/component-tab-vertical.php' ),
					),
				)
			);

			$tabs = apply_filters( 'jet-menu/options-page/tabs', array(
				'general_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'General', 'jet-menu' ),
				),
				'styles_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Styles', 'jet-menu' ),
				),
				'main_items_styles_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Main Menu Styles', 'jet-menu' ),
				),
				'sub_items_styles_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Sub Menu Styles', 'jet-menu' ),
				),
				'mobile_menu_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Mobile Menu', 'jet-menu' ),
				),
				'advanced_tab' => array(
					'parent'      => 'tab_vertical',
					'title'       => esc_html__( 'Advanced', 'jet-menu' ),
				),
			) );

			$this->builder->register_settings( $tabs );

			$import_template = jet_menu()->get_template( 'admin/settings-import-export.php' );

			ob_start();
			include $import_template;
			$import_export = ob_get_clean();

			$this->builder->register_html(
				array(
					'jet-menu-import-export' => array(
						'type'   => 'html',
						'parent' => 'option_page_footer',
						'class'  => 'jet-menu-import-export',
						'html'   => $import_export,
					),
				)
			);

			$this->builder->register_control(
				array(
					'jet-menu-reset-options' => array(
						'type'          => 'button',
						'parent'        => 'option_page_footer',
						'style'         => 'normal',
						'view_wrapping' => false,
						'class'         => 'jet-reset-options',
						'content'       => esc_html__( 'Reset', 'jet-menu' ),
					),
					'jet-menu-save-options' => array(
						'type'          => 'button',
						'parent'        => 'option_page_footer',
						'style'         => 'success',
						'class'         => 'custom-class',
						'view_wrapping' => false,
						'content'       => '<span class="text">' . esc_html__( 'Save', 'jet-menu' ) . '</span>' . $this->spinner . $this->button_icon,
					),
				)
			);

			$this->section_start( 'general_tab' );

			$this->builder->register_control(
				array(
					'jet-menu-animation' => array(
						'type'             => 'select',
						'parent'           => 'general_tab',
						'title'            => esc_html__( 'Animation', 'jet-menu' ),
						'multiple'         => false,
						'filter'           => true,
						'value'            => $this->get_option( 'jet-menu-animation' ),
						'options'          => array(
							'none'      => esc_html__( 'None', 'jet-menu' ),
							'fade'      => esc_html__( 'Fade', 'jet-menu' ),
							'move-up'   => esc_html__( 'Move Up', 'jet-menu' ),
							'move-down' => esc_html__( 'Move Down', 'jet-menu' ),
						),
						'placeholder'      => 'Select',
						'label'            => '',
						'class'            => '',
					),

					'jet-menu-roll-up' => array(
						'type'        => 'switcher',
						'parent'      => 'general_tab',
						'title'       => esc_html__( 'Menu rollUp', 'jet-menu' ),
						'description' => esc_html__( 'Enable this option in order to reduce the menu size by grouping extra menu items  and hiding them under the suspension dots.', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-roll-up', 'false' ),
						'toggle'      => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),

					'jet-menu-mouseleave-delay' => array(
						'type'       => 'slider',
						'parent'     => 'general_tab',
						'title'      => esc_html__( 'Mouse Leave Delay', 'jet-menu' ),
						'max_value'  => 10000,
						'min_value'  => 0,
						'value'      => $this->get_option( 'jet-menu-mouseleave-delay', 500 ),
						'step_value' => 100,
					),

					'jet-mega-menu-width-type' => array(
						'type'     => 'radio',
						'parent'   => 'general_tab',
						'title'    => esc_html__( 'Mega menu base width', 'jet-menu' ),
						'value'    => $this->get_option( 'jet-mega-menu-width-type', 'container' ),
						'options'  => array(
							'container' => array(
								'label' => esc_html__( 'Width same as main container width', 'jet-menu' ),
							),
							'items'     => array(
								'label' => esc_html__( 'Width same as total items width', 'jet-menu' ),
							),
							'selector'  => array(
								'label' =>  esc_html__( 'Width same as Custom css selector width', 'jet-menu' ),
								'slave' => 'jet-mega-menu-selector-width-type',
							)
						),
						'label'    => '',
						'class'    => '',
					),

					'jet-mega-menu-selector-width-type' => array(
						'type'     => 'text',
						'parent'   => 'general_tab',
						'title'    => esc_html__( 'Mega menu width selector', 'jet-menu' ),
						'value'    => $this->get_option( 'jet-mega-menu-selector-width-type', '' ),
						'label'    => '',
						'class'    => '',
						'master'   => 'jet-mega-menu-selector-width-type',
					),

					'jet-menu-open-sub-type' => array(
						'type'        => 'select',
						'parent'      => 'general_tab',
						'title'       => esc_html__( 'Sub menu open event', 'jet-menu' ),
						'multiple'    => false,
						'filter'      => true,
						'value'       => $this->get_option( 'jet-menu-open-sub-type', 'hover' ),
						'options'     => array(
							'hover'       => esc_html__( 'Hover', 'jet-menu' ),
							'click'       => esc_html__( 'Click', 'jet-menu' ),
						),
						'placeholder' => 'Select',
						'label'       => '',
						'class'       => '',
					),

					'jet-menu-mobile-breakpoint' => array(
						'type'        => 'slider',
						'parent'      => 'general_tab',
						'title'       => esc_html__( 'Mobile breakpoint (px)', 'jet-menu' ),
						'description' => esc_html__( 'Sets the breakpoint between desktop menu and mobile menu. Below this breakpoint mobile menu will appear.', 'jet-menu' ),
						'max_value'   => 1200,
						'min_value'   => 480,
						'value'       => $this->get_option( 'jet-menu-mobile-breakpoint', $default_mobile_breakpoint ),
						'step_value'  => 1,
					),
				)
			);

			$template = get_template();

			if ( file_exists( jet_menu()->plugin_path( "integration/themes/{$template}" ) ) ) {

				$this->builder->register_control(
					array(
						'jet-menu-disable-integration-' . $template => array(
							'type'        => 'switcher',
							'parent'      => 'general_tab',
							'title'       => esc_html__( 'Disable default theme integration file', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-disable-integration-' . $template, 'false' ),
							'toggle'      => array(
								'true_toggle'  => 'On',
								'false_toggle' => 'Off',
							),
						),
					)
				);

			}

			$this->builder->register_control(
				array(
					'jet-menu-cache-css' => array(
						'type'        => 'switcher',
						'parent'      => 'general_tab',
						'title'       => esc_html__( 'Cache menu CSS', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-cache-css', 'true' ),
						'toggle'      => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),
				)
			);

			$this->section_end( 'general_tab' );

			$this->section_start( 'styles_tab' );

			$this->builder->register_control(
				array(
					'jet-menu-container-alignment' => array(
						'type'     => 'select',
						'parent'   => 'styles_tab',
						'title'    => esc_html__( 'Menu items alignment', 'jet-menu' ),
						'multiple' => false,
						'value'    => $this->get_option( 'jet-menu-container-alignment' ),
						'options'  => array(
							'flex-end'   => esc_html__( 'Right', 'jet-menu' ),
							'center'     => esc_html__( 'Center', 'jet-menu' ),
							'flex-start' => esc_html__( 'Left', 'jet-menu' ),
						),
						'label'    => '',
						'class'    => '',
					),
				)
			);

			jet_menu_dynmic_css()->add_background_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
				'parent'   => 'styles_tab',
				'defaults' => array(
					'color' => '#ffffff',
				),
			) );

			jet_menu_dynmic_css()->add_border_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
				'parent'   => 'styles_tab',
				'defaults' => array(
					'top'    => '1',
					'right'  => '1',
					'bottom' => '1',
					'left'   => '1',
				),
			) );

			jet_menu_dynmic_css()->add_box_shadow_options( array(
				'name'     => 'jet-menu-container',
				'label'    => esc_html__( 'Menu container', 'jet-menu' ),
				'parent'   => 'styles_tab',
			) );

			$this->builder->register_control(
				array(
					'jet-menu-mega-border-radius' => array(
						'type'        => 'dimensions',
						'parent'      => 'styles_tab',
						'title'       => esc_html__( 'Menu container border radius', 'jet-menu' ),
						'range'       => array(
							'px' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'%' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'value' => $this->get_option( 'jet-menu-mega-border-radius' ),
					),
					'jet-menu-inherit-first-radius' => array(
						'type'   => 'switcher',
						'title'  => esc_html__( 'Inherit border radius for the first menu item from main container', 'jet-menu' ),
						'value'  => $this->get_option( 'jet-menu-inherit-first-radius' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => 'styles_tab',
					),
					'jet-menu-inherit-last-radius' => array(
						'type'   => 'switcher',
						'title'  => esc_html__( 'Inherit border radius for the last menu item from main container', 'jet-menu' ),
						'value'  => $this->get_option( 'jet-menu-inherit-last-radius' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => 'styles_tab',
					),
					'jet-menu-mega-padding' => array(
						'type'        => 'dimensions',
						'parent'      => 'styles_tab',
						'title'       => esc_html__( 'Menu container padding', 'jet-menu' ),
						'range'       => array(
							'px' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'value' => $this->get_option( 'jet-menu-mega-padding' ),
					),
					'jet-menu-min-width' => array(
						'type'        => 'slider',
						'parent'      => 'styles_tab',
						'title'       => esc_html__( 'Menu container min width (px)', 'jet-menu' ),
						'description' => esc_html__( 'Set 0 to automatic width detection', 'jet-menu' ),
						'max_value'   => 900,
						'min_value'   => 0,
						'value'       => $this->get_option( 'jet-menu-min-width', 0 ),
						'step_value'  => 1,
					),
				)
			);

			$this->section_end( 'styles_tab' );

			$this->section_start( 'main_items_styles_tab' );

			jet_menu_dynmic_css()->add_typography_options(
				array(
					'label'   => esc_html__( 'Top level menu', 'jet-menu' ),
					'name'    => 'jet-top-menu',
					'parent'  => 'main_items_styles_tab',
				)
			);

			$this->builder->register_control(
				array(
					'jet-show-top-menu-desc' => array(
						'type'   => 'switcher',
						'parent' => 'main_items_styles_tab',
						'title'  => esc_html__( 'Show Item Description', 'jet-menu' ),
						'value'  => $this->get_option( 'jet-show-top-menu-desc', 'true' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),
				)
			);

			jet_menu_dynmic_css()->add_typography_options(
				array(
					'label'   => esc_html__( 'Top level menu description', 'jet-menu' ),
					'name'    => 'jet-top-menu-desc',
					'parent'  => 'main_items_styles_tab',
				)
			);

			$this->builder->register_control(
				array(
					'jet-menu-item-max-width' => array(
						'type'        => 'slider',
						'parent'      => 'main_items_styles_tab',
						'title'       => esc_html__( 'Top level item max width (%)', 'jet-menu' ),
						'description' => esc_html__( 'Set 0 to automatic width detection', 'jet-menu' ),
						'max_value'   => 100,
						'min_value'   => 0,
						'value'       => $this->get_option( 'jet-menu-item-max-width', 0 ),
						'step_value'  => 1,
					),
				)
			);

			$this->builder->register_component(
				array(
					'menu_items_tabs' => array(
						'type'   => 'component-tab-horizontal',
						'parent' => 'main_items_styles_tab',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'item_default_tab' => array(
						'parent' => 'menu_items_tabs',
						'title'  => esc_html__( 'Default', 'jet-menu' ),
					),
					'item_hover_tab' => array(
						'parent' => 'menu_items_tabs',
						'title'  => esc_html__( 'Hover', 'jet-menu' ),
					),
					'item_active_tab' => array(
						'parent' => 'menu_items_tabs',
						'title'  => esc_html__( 'Active', 'jet-menu' ),
					),
				)
			);

			$tabs = array(
				'default' => '',
				'hover'   => '-hover',
				'active'  => '-active',
			);

			foreach ( $tabs as $tab => $opt ) {

				$this->section_start( 'item_' . $tab . '_tab' );

				$this->builder->register_control(
					array(
						'jet-menu-item-text-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item text color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-item-text-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-item-desc-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item description color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-item-desc-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-top-icon-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item Icon Color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-top-icon-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-top-arrow-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item drop-down arrow color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-top-arrow-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				jet_menu_dynmic_css()->add_background_options( array(
					'name'     => 'jet-menu-item' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'item_' . $tab . '_tab',
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-item' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'item_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-first-item' . $opt,
					'label'    => esc_html__( 'First item', 'jet-menu' ),
					'parent'   => 'item_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-last-item' . $opt,
					'label'    => esc_html__( 'Last item', 'jet-menu' ),
					'parent'   => 'item_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_box_shadow_options( array(
					'name'     => 'jet-menu-item' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'item_' . $tab . '_tab',
				) );

				$this->builder->register_control(
					array(
						'jet-menu-item-border-radius' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item border radius', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-item-border-radius' . $opt ),
						),
						'jet-menu-item-padding' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item padding', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-item-padding' . $opt ),
						),
						'jet-menu-item-margin' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'item_' . $tab . '_tab',
							'title'       => esc_html__( 'Item margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-item-margin' . $opt ),
						),
					)
				);

				$this->section_start( 'item_' . $tab . '_tab' );

			}

			$this->builder->register_component(
				array(
					'menu_sub_panel_tabs' => array(
						'type'   => 'component-tab-horizontal',
						'parent' => 'sub_items_styles_tab',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'sub_panel_simple_tab' => array(
						'parent' => 'menu_sub_panel_tabs',
						'title'  => esc_html__( 'Simple Submenu Panel', 'jet-menu' ),
					),
					'sub_panel_mega_tab' => array(
						'parent' => 'menu_sub_panel_tabs',
						'title'  => esc_html__( 'Mega Submenu Panel', 'jet-menu' ),
					),
				)
			);

			$tabs = array(
				'simple' => '-simple',
				'mega'   => '-mega',
			);

			foreach ( $tabs as $tab => $opt ) {

				$this->section_start( 'sub_panel_' . $tab . '_tab' );

				if ( 'simple' === $tab ) {
					$this->builder->register_control(
						array(
							'jet-menu-sub-panel-width-simple' => array(
								'type'       => 'slider',
								'max_value'  => 400,
								'min_value'  => 100,
								'value'      => $this->get_option( 'jet-menu-sub-panel-width-simple', 200 ),
								'step_value' => 1,
								'title'      => esc_html__( 'Panel Width', 'jet-menu' ),
								'parent'     => 'sub_panel_simple_tab',
							),
						)
					);
				}

				jet_menu_dynmic_css()->add_background_options( array(
					'name'     => 'jet-menu-sub-panel' . $opt,
					'label'    => esc_html__( 'Panel', 'jet-menu' ),
					'parent'   => 'sub_panel_' . $tab . '_tab',
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-sub-panel' . $opt,
					'label'    => esc_html__( 'Panel', 'jet-menu' ),
					'parent'   => 'sub_panel_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_box_shadow_options( array(
					'name'     => 'jet-menu-sub-panel' . $opt,
					'label'    => esc_html__( 'Panel', 'jet-menu' ),
					'parent'   => 'sub_panel_' . $tab . '_tab',
				) );

				$this->builder->register_control(
					array(
						'jet-menu-sub-panel-border-radius' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_panel_' . $tab . '_tab',
							'title'       => esc_html__( 'Panel border radius', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-panel-border-radius' . $opt ),
						),
						'jet-menu-sub-panel-padding' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_panel_' . $tab . '_tab',
							'title'       => esc_html__( 'Panel padding', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-panel-padding' . $opt ),
						),
						'jet-menu-sub-panel-margin' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_panel_' . $tab . '_tab',
							'title'       => esc_html__( 'Panel margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-panel-margin' . $opt ),
						),
					)
				);

				$this->section_end( 'sub_panel_' . $tab . '_tab' );

			}

			$this->section_start( 'sub_items_styles_tab' );

			jet_menu_dynmic_css()->add_typography_options(
				array(
					'label'   => esc_html__( 'Submenu', 'jet-menu' ),
					'name'    => 'jet-sub-menu',
					'parent'  => 'sub_items_styles_tab',
				)
			);

			$this->builder->register_control(
				array(
					'jet-show-sub-menu-desc' => array(
						'type'   => 'switcher',
						'parent' => 'sub_items_styles_tab',
						'title'  => esc_html__( 'Show Submenu Item Description', 'jet-menu' ),
						'value'  => $this->get_option( 'jet-show-sub-menu-desc', 'true' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),
				)
			);

			jet_menu_dynmic_css()->add_typography_options(
				array(
					'label'   => esc_html__( 'Submenu descriptions', 'jet-menu' ),
					'name'    => 'jet-sub-menu-desc',
					'parent'  => 'sub_items_styles_tab',
				)
			);

			$this->builder->register_component(
				array(
					'menu_sub_tabs' => array(
						'type'   => 'component-tab-horizontal',
						'parent' => 'sub_items_styles_tab',
					),
				)
			);

			$this->section_end( 'sub_items_styles_tab' );

			$this->builder->register_settings(
				array(
					'sub_default_tab' => array(
						'parent' => 'menu_sub_tabs',
						'title'  => esc_html__( 'Default', 'jet-menu' ),
					),
					'sub_hover_tab' => array(
						'parent' => 'menu_sub_tabs',
						'title'  => esc_html__( 'Hover', 'jet-menu' ),
					),
					'sub_active_tab' => array(
						'parent' => 'menu_sub_tabs',
						'title'  => esc_html__( 'Active', 'jet-menu' ),
					),
				)
			);

			$tabs = array(
				'default' => '',
				'hover'   => '-hover',
				'active'  => '-active',
			);

			foreach ( $tabs as $tab => $opt ) {

				$this->section_start( 'sub_' . $tab . '_tab' );

				$this->builder->register_control(
					array(
						'jet-menu-sub-text-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item text color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-sub-text-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-sub-desc-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item descriptions color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-sub-desc-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-sub-icon-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item icon color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-sub-icon-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-sub-arrow-color' . $opt => array(
							'type'        => 'colorpicker',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item drop-down arrow color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-sub-arrow-color' . $opt ),
							'alpha'       => true,
						),
					)
				);

				jet_menu_dynmic_css()->add_background_options( array(
					'name'     => 'jet-menu-sub' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'sub_' . $tab . '_tab',
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-sub' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'sub_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-sub-first' . $opt,
					'label'    => esc_html__( 'First item', 'jet-menu' ),
					'parent'   => 'sub_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-sub-last' . $opt,
					'label'    => esc_html__( 'Last item', 'jet-menu' ),
					'parent'   => 'sub_' . $tab . '_tab',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_box_shadow_options( array(
					'name'     => 'jet-menu-sub' . $opt,
					'label'    => esc_html__( 'Item', 'jet-menu' ),
					'parent'   => 'sub_' . $tab . '_tab',
				) );

				$this->builder->register_control(
					array(
						'jet-menu-sub-border-radius' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item border radius', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-border-radius' . $opt ),
						),
						'jet-menu-sub-padding' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item padding', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-padding' . $opt ),
						),
						'jet-menu-sub-margin' . $opt => array(
							'type'        => 'dimensions',
							'parent'      => 'sub_' . $tab . '_tab',
							'title'       => esc_html__( 'Item margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-sub-margin' . $opt ),
						),
					)
				);

				$this->section_end( 'sub_' . $tab . '_tab' );

			}

			$this->builder->register_component(
				array(
					'menu_advanced_tabs' => array(
						'type'   => 'component-tab-horizontal',
						'parent' => 'advanced_tab',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'advanced_icon' => array(
						'parent' => 'menu_advanced_tabs',
						'title'  => esc_html__( 'Icon', 'jet-menu' ),
					),
					'advanced_badge' => array(
						'parent' => 'menu_advanced_tabs',
						'title'  => esc_html__( 'Badge', 'jet-menu' ),
					),
					'advanced_arrow' => array(
						'parent' => 'menu_advanced_tabs',
						'title'  => esc_html__( 'Drop-down Arrow', 'jet-menu' ),
					),
				)
			);

			$this->builder->register_component( array(
				'icons_accordion' => array(
					'type'        => 'component-accordion',
					'parent'      => 'advanced_icon',
				)
			) );

			$this->builder->register_settings(
				array(
					'top_icon' => array(
						'type'   => 'settings',
						'parent' => 'icons_accordion',
						'title'  => esc_html__( 'Top Level Icon', 'jet-menu' ),
					),
					'sub_icon' => array(
						'type'   => 'settings',
						'parent' => 'icons_accordion',
						'title'  => esc_html__( 'Sub Level Icon', 'jet-menu' ),
					),
				)
			);

			$icons = array( 'top', 'sub' );

			foreach ( $icons as $level ) {

				$this->section_start( $level . '_icon' );

				$this->builder->register_control(
					array(
						'jet-menu-' . $level . '-icon-size' => array(
							'type'       => 'slider',
							'max_value'  => 150,
							'min_value'  => 10,
							'value'      => $this->get_option( 'jet-menu-' . $level . '-icon-size' ),
							'step_value' => 1,
							'title'      => esc_html__( 'Icon size', 'jet-menu' ),
							'parent'     => $level . '_icon',
						),
						'jet-menu-' . $level . '-icon-margin' => array(
							'type'        => 'dimensions',
							'parent'      => $level . '_icon',
							'title'       => esc_html__( 'Icon margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-' . $level . '-icon-margin' ),
						),
						'jet-menu-' . $level . '-icon-ver-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_icon',
							'title'    => esc_html__( 'Icon vertical position', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-icon-ver-position' ),
							'options'  => array(
								'center' => esc_html__( 'Center', 'jet-menu' ),
								'top'    => esc_html__( 'Top', 'jet-menu' ),
								'bottom' => esc_html__( 'Bottom', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-icon-hor-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_icon',
							'title'    => esc_html__( 'Icon horizontal position', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-icon-hor-position' ),
							'options'  => array(
								'left'   => esc_html__( 'Left', 'jet-menu' ),
								'right'  => esc_html__( 'Right', 'jet-menu' ),
								'center' => esc_html__( 'Center', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-icon-order' => array(
							'type'       => 'slider',
							'max_value'  => 10,
							'min_value'  => -10,
							'value'      => $this->get_option( 'jet-menu-' . $level . '-icon-order' ),
							'step_value' => 1,
							'title'      => esc_html__( 'Icon order', 'jet-menu' ),
							'parent'     => $level . '_icon',
						),
					)
				);

				$this->section_end( $level . '_icon' );

			}

			$this->builder->register_component( array(
				'badges_accordion' => array(
					'type'        => 'component-accordion',
					'parent'      => 'advanced_badge',
				)
			) );

			$this->builder->register_settings(
				array(
					'top_badge' => array(
						'type'   => 'settings',
						'parent' => 'badges_accordion',
						'title'  => esc_html__( 'Top Level Badge', 'jet-menu' ),
					),
					'sub_badge' => array(
						'type'   => 'settings',
						'parent' => 'badges_accordion',
						'title'  => esc_html__( 'Sub Level Badge', 'jet-menu' ),
					),
				)
			);

			$badges = array( 'top', 'sub' );

			foreach ( $badges as $level ) {

				$this->section_start( $level . '_badge' );

				jet_menu_dynmic_css()->add_typography_options(
					array(
						'label'   => esc_html__( 'Badge', 'jet-menu' ),
						'name'    => 'jet-menu-' . $level . '-badge',
						'parent'  => $level . '_badge',
					)
				);

				$this->builder->register_control(
					array(
						'jet-menu-' . $level . '-badge-text-color' => array(
							'type'        => 'colorpicker',
							'parent'      => $level . '_badge',
							'title'       => esc_html__( 'Badge text color', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-' . $level . '-badge-text-color' ),
							'alpha'       => true,
						),
					)
				);

				jet_menu_dynmic_css()->add_background_options( array(
					'name'     => 'jet-menu-' . $level . '-badge-bg',
					'label'    => esc_html__( 'Badge', 'jet-menu' ),
					'parent'   => $level . '_badge',
				) );

				jet_menu_dynmic_css()->add_border_options( array(
					'name'     => 'jet-menu-' . $level . '-badge',
					'label'    => esc_html__( 'Badge', 'jet-menu' ),
					'parent'   => $level . '_badge',
					'defaults' => array(
						'top'    => '1',
						'right'  => '1',
						'bottom' => '1',
						'left'   => '1',
					),
				) );

				jet_menu_dynmic_css()->add_box_shadow_options( array(
					'name'     => 'jet-menu-' . $level . '-badge',
					'label'    => esc_html__( 'Badge', 'jet-menu' ),
					'parent'   => $level . '_badge',
				) );

				$this->builder->register_control(
					array(
						'jet-menu-' . $level . '-badge-border-radius' => array(
							'type'        => 'dimensions',
							'parent'      => $level . '_badge',
							'title'       => esc_html__( 'Badge border radius', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-' . $level . '-badge-border-radius' ),
						),
						'jet-menu-' . $level . '-badge-padding' => array(
							'type'        => 'dimensions',
							'parent'      => $level . '_badge',
							'title'       => esc_html__( 'Badge padding', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-' . $level . '-badge-padding' ),
						),
						'jet-menu-' . $level . '-badge-margin' => array(
							'type'        => 'dimensions',
							'parent'      => $level . '_badge',
							'title'       => esc_html__( 'Badge margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-' . $level . '-badge-margin' ),
						),
						'jet-menu-' . $level . '-badge-ver-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_badge',
							'title'    => esc_html__( 'Badge vertical position (may be overridden with order)', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-badge-ver-position' ),
							'options'  => array(
								'top'    => esc_html__( 'Top', 'jet-menu' ),
								'center' => esc_html__( 'Center', 'jet-menu' ),
								'bottom' => esc_html__( 'Bottom', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-badge-hor-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_badge',
							'title'    => esc_html__( 'Badge horizontal position', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-badge-hor-position' ),
							'options'  => array(
								'right'  => esc_html__( 'Right', 'jet-menu' ),
								'center' => esc_html__( 'Center', 'jet-menu' ),
								'left'   => esc_html__( 'Left', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-badge-order' => array(
							'type'       => 'slider',
							'max_value'  => 10,
							'min_value'  => -10,
							'value'      => $this->get_option( 'jet-menu-' . $level . '-badge-order' ),
							'step_value' => 1,
							'title'      => esc_html__( 'Badge order', 'jet-menu' ),
							'parent'     => $level . '_badge',
						),
						'jet-menu-' . $level . '-badge-hide' => array(
							'type'        => 'switcher',
							'parent'     => $level . '_badge',
							'title'       => esc_html__( 'Hide badge on mobile', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-' . $level . '-badge-hide', 'false' ),
							'toggle'      => array(
								'true_toggle'  => 'Yes',
								'false_toggle' => 'No',
							),
						),
					)
				);

				$this->section_end( $level . '_badge' );

			}

			$this->builder->register_component( array(
				'arrows_accordion' => array(
					'type'        => 'component-accordion',
					'parent'      => 'advanced_arrow',
				)
			) );

			$this->builder->register_settings(
				array(
					'top_arrow' => array(
						'type'   => 'settings',
						'parent' => 'arrows_accordion',
						'title'  => esc_html__( 'Top Level Arrow', 'jet-menu' ),
					),
					'sub_arrow' => array(
						'type'   => 'settings',
						'parent' => 'arrows_accordion',
						'title'  => esc_html__( 'Sub Level Arrow', 'jet-menu' ),
					),
				)
			);

			$arrows = array( 'top' => 'fa-angle-down', 'sub' => 'fa-angle-right' );

			foreach ( $arrows as $level => $default ) {

				$this->section_start( $level . '_arrow' );

				$this->builder->register_control(
					array(
						'jet-menu-' . $level . '-arrow' => array(
							'type'        => 'iconpicker',
							'label'       => esc_html__( 'Arrow icon', 'jet-menu' ),
							'value'       => $this->get_option( 'jet-menu-' . $level . '-arrow', $default ),
							'parent'      => $level . '_arrow',
							'icon_data'   => array(
								'icon_set'    => 'jetMenuIcons',
								'icon_css'    => jet_menu()->plugin_url( 'assets/public/css/font-awesome.min.css' ),
								'icon_base'   => 'fa',
								'icon_prefix' => '',
								'icons'       => $this->get_arrows_icons(),
							),
						),
						'jet-menu-' . $level . '-arrow-size' => array(
							'type'       => 'slider',
							'max_value'  => 150,
							'min_value'  => 10,
							'value'      => $this->get_option( 'jet-menu-' . $level . '-arrow-size' ),
							'step_value' => 1,
							'title'      => esc_html__( 'Arrow size', 'jet-menu' ),
							'parent'     => $level . '_arrow',
						),
						'jet-menu-' . $level . '-arrow-margin' => array(
							'type'        => 'dimensions',
							'parent'      => $level . '_arrow',
							'title'       => esc_html__( 'Arrow margin', 'jet-menu' ),
							'range'       => array(
								'px' => array(
									'min'  => -50,
									'max'  => 100,
									'step' => 1,
								),
							),
							'value' => $this->get_option( 'jet-menu-' . $level . '-arrow-margin' ),
						),
						'jet-menu-' . $level . '-arrow-ver-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_arrow',
							'title'    => esc_html__( 'Arrow vertical position', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-arrow-ver-position' ),
							'options'  => array(
								'center' => esc_html__( 'Center', 'jet-menu' ),
								'top'    => esc_html__( 'Top', 'jet-menu' ),
								'bottom' => esc_html__( 'Bottom', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-arrow-hor-position' => array(
							'type'     => 'select',
							'parent'   => $level . '_arrow',
							'title'    => esc_html__( 'Arrow horizontal position', 'jet-menu' ),
							'multiple' => false,
							'filter'   => false,
							'value'    => $this->get_option( 'jet-menu-' . $level . '-arrow-hor-position' ),
							'options'  => array(
								'right'  => esc_html__( 'Right', 'jet-menu' ),
								'center' => esc_html__( 'Center', 'jet-menu' ),
								'left'   => esc_html__( 'Left', 'jet-menu' ),
							),
						),
						'jet-menu-' . $level . '-arrow-order' => array(
							'type'       => 'slider',
							'max_value'  => 10,
							'min_value'  => -10,
							'value'      => $this->get_option( 'jet-menu-' . $level . '-arrow-order' ),
							'step_value' => 1,
							'title'      => esc_html__( 'Arrow order', 'jet-menu' ),
							'parent'     => $level . '_arrow',
						),
					)
				);

				$this->section_end( $level . '_arrow' );

			}

			$this->section_start( 'mobile_menu_tab' );

			$this->builder->register_control(
				array(
					'jet-menu-mobile-toggle-color' => array(
						'type'        => 'colorpicker',
						'parent'      => 'mobile_menu_tab',
						'title'       => esc_html__( 'Toggle text color', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-mobile-toggle-color' ),
						'alpha'       => true,
					),
					'jet-menu-mobile-toggle-bg' => array(
						'type'        => 'colorpicker',
						'parent'      => 'mobile_menu_tab',
						'title'       => esc_html__( 'Toggle background color', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-mobile-toggle-bg' ),
						'alpha'       => true,
					),
					'jet-menu-mobile-container-bg' => array(
						'type'        => 'colorpicker',
						'parent'      => 'mobile_menu_tab',
						'title'       => esc_html__( 'Container background color', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-mobile-container-bg' ),
						'alpha'       => true,
					),
					'jet-menu-mobile-cover-bg' => array(
						'type'        => 'colorpicker',
						'parent'      => 'mobile_menu_tab',
						'title'       => esc_html__( 'Cover background color', 'jet-menu' ),
						'value'       => $this->get_option( 'jet-menu-mobile-cover-bg' ),
						'alpha'       => true,
					),
				)
			);

			$this->section_end( 'mobile_menu_tab' );

			/**
			 * Hook fires before page render
			 */
			do_action( 'jet-menu/options-page/before-render', $this->builder, $this );

			$this->builder->render();
		}

		/**
		 * Section start trigger
		 *
		 * @param  string $section Section name.
		 * @return void
		 */
		public function section_start( $section ) {
			do_action( 'jet-menu/options-page/section-start/' . $section, $this->builder, $this );
		}

		/**
		 * Section start trigger
		 *
		 * @param  string $section Section name.
		 * @return void
		 */
		public function section_end( $section ) {
			do_action( 'jet-menu/options-page/section-end/' . $section, $this->builder, $this );
		}

		public function get_arrows_icons() {
			return apply_filters( 'jet-menu/arrow-icons', array(
				'fa-angle-down',
				'fa-angle-double-down',
				'fa-arrow-circle-down',
				'fa-arrow-down',
				'fa-caret-down',
				'fa-chevron-circle-down',
				'fa-chevron-down',
				'fa-long-arrow-down',
				'fa-angle-right',
				'fa-angle-double-right',
				'fa-arrow-circle-right',
				'fa-arrow-right',
				'fa-caret-right',
				'fa-chevron-circle-right',
				'fa-chevron-right',
				'fa-long-arrow-right',
				'fa-angle-left',
				'fa-angle-double-left',
				'fa-arrow-circle-left',
				'fa-arrow-left',
				'fa-caret-left',
				'fa-chevron-circle-left',
				'fa-chevron-left',
				'fa-long-arrow-left',
			) );
		}

		/**
		 * Build export URL
		 *
		 * @return string
		 */
		public function export_url() {
			return add_query_arg(
				array(
					'jet-action' => 'export-options',
				),
				esc_url( admin_url( 'admin.php' ) )
			);
		}

		/**
		 * Process options reset
		 *
		 * @return void
		 */
		public function process_reset() {

			if ( ! isset( $_GET['jet-action'] ) || 'reset-options' !== $_GET['jet-action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				die();
			}

			$this->save_options( $this->options_slug, $this->default_options );

			wp_redirect(
				add_query_arg(
					array( 'page' => jet_menu()->plugin_slug ),
					esc_url( admin_url( 'admin.php' ) )
				)
			);

			die();
		}

		/**
		 * Process settings export
		 *
		 * @return void
		 */
		public function process_export() {

			if ( ! isset( $_GET['jet-action'] ) || 'export-options' !== $_GET['jet-action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				die();
			}

			$options = $this->get_option();

			if ( ! $options ) {
				$options = array();
			}

			$file = 'jet-menu-options-' . date( 'm-d-Y' ) . '.json';
			$data = json_encode( array(
				'jet_menu' => true,
				'options'  => $options,
			) );

			session_write_close();

			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Cache-Control: public' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="' . $file . '"' );
			header( 'Content-Transfer-Encoding: binary' );

			echo $data;

			die();
		}

		/**
		 * Process settings import
		 *
		 * @return void
		 */
		public function process_import() {

			if ( ! current_user_can( 'manage_options' ) ) {
				die();
			}

			$options = isset( $_POST['data'] ) ? $_POST['data'] : array();

			if ( empty( $options['jet_menu'] ) || empty( $options['options'] ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Incorrect data in options file', 'jet-menu' ),
				) );
			}

			$this->save_options( $this->options_slug, $options['options'] );

			wp_send_json_success( array(
				'message' => esc_html__( 'Options successfully imported. Page will be reloaded.', 'jet-menu' ),
			) );

		}

		/**
		 * Options field exist DB check
		 *
		 * @since 1.0.0
		 */
		public function is_db_options_exist( $option_name ) {

			( false == get_option( $option_name ) ) ? $is_exist = false : $is_exist = true;

			return $is_exist;
		}

		/**
		 *
		 * Save options to DB
		 *
		 * @since 1.0.0
		 */
		public function save_options( $option_name, $options ) {

			$options = array_merge( $this->default_options, $options );

			update_option( $option_name, $options );
			$this->fonts_loader->reset_fonts_cache();

			do_action( 'jet-menu/options-page/save' );
		}

		/**
		 * Set options externaly.
		 *
		 * @param  array  $options Options array to set.
		 * @return void
		 */
		public function pre_set_options( $options = array() ) {

			if ( empty( $options ) ) {
				$this->options = false;
			} else {
				$this->options = $options;
			}

		}

		/**
		 * Get option value
		 *
		 * @param string $options Option name.
		 * @since 1.0.0
		 */
		public function get_option( $option_name = null, $default = false ) {

			if ( empty( $this->options ) ) {
				$this->options = get_option( $this->options_slug, $this->default_options );
			}

			if ( ! $option_name && ! empty( $this->options ) ) {
				return $this->options;
			}

			return isset( $this->options[ $option_name ] ) ? $this->options[ $option_name ] : $default;
		}

		/**
		 * Get default mobile breakpoint value.
		 *
		 * @since 1.4.1
		 * @return int
		 */
		public function get_default_mobile_breakpoint() {
			return get_option( 'elementor_viewport_md' ) ? (int) get_option( 'elementor_viewport_md' ) : 768;
		}

		/**
		 * Create db options field if this is not exist
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function create_db_options_field() {
			if ( ! $this->is_db_options_exist( $this->options_slug ) ) {
				$this->save_options( $this->options_slug, $this->default_options );
			}

			if ( ! $this->is_db_options_exist( $this->options_slug . '_default' ) ) {
				$this->save_options( $this->options_slug . '_default', $this->default_options );
			}
		}

		/**
		 * Return options db key.
		 *
		 * @return string
		 */
		public function options_slug() {
			return $this->options_slug;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
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
}

if ( ! function_exists( 'jet_menu_option_page' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_menu_option_page() {
		return Jet_Menu_Options_Page::get_instance();
	}
}
