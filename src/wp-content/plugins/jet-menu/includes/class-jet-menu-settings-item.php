<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Menu_Settings_Item' ) ) {

	/**
	 * Define Jet_Menu_Settings_Item class
	 */
	class Jet_Menu_Settings_Item {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Templates stack
		 *
		 * @var array
		 */
		protected $templates = array();

		protected $meta_key = 'jet_menu_settings';

		/**
		 * Constructor for the class
		 */
		public function init() {

			foreach ( $this->get_tabs() as $tab ) {

				if ( ! empty( $tab['template'] ) && ! empty( $tab['templateFile'] ) ) {
					$this->templates[ $tab['template'] ] = $tab['templateFile'];
				}

				if ( ! empty( $tab['data'] ) ) {

				}

				if ( empty( $tab['action'] ) || empty( $tab['callback'] ) ) {
					continue;
				}

				if ( ! is_callable( $tab['callback'] ) ) {
					continue;
				}

				add_action( 'wp_ajax_' . $tab['action'], $tab['callback'] );
			}

			add_action( 'admin_footer', array( $this, 'print_tabs_templates' ) );
			add_action( 'wp_ajax_jet_save_menu', array( $this, 'save_menu_settings' ) );

		}

		/**
		 * Print tabs templates
		 *
		 * @return void
		 */
		public function print_tabs_templates() {

			$screen = get_current_screen();

			if ( 'nav-menus' !== $screen->base ) {
				return;
			}

			jet_menu_assets()->print_templates_array( $this->templates );
		}

		/**
		 * Returns list of available settings tabs
		 *
		 * @return array
		 */
		public function get_tabs() {

			return apply_filters( 'jet-menu/settings/tabs', array(
				'content' => array(
					'label'        => esc_html__( 'Content', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_content',
					'callback'     => array( $this, 'get_tab_content' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 1,
				),
				'settings' => array(
					'label'        => esc_html__( 'Settings', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_settings',
					'callback'     => array( $this, 'get_tab_settings' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 1,
				),
				'icon' => array(
					'label'        => esc_html__( 'Icon', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_icon',
					'callback'     => array( $this, 'get_tab_icon' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 100,
				),
				'badges' => array(
					'label'        => esc_html__( 'Badges', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_badges',
					'callback'     => array( $this, 'get_tab_badges' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 100,
				),
				'misc' => array(
					'label'        => esc_html__( 'Misc', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_misc',
					'callback'     => array( $this, 'get_tab_misc' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 100,
				),
				'vertical_menu' => array(
					'label'        => esc_html__( 'Vertical Menu', 'jet-menu' ),
					'template'     => false,
					'templateFile' => false,
					'action'       => 'jet_menu_tab_vertical_menu',
					'callback'     => array( $this, 'get_tab_vertical_menu' ),
					'data'         => array(),
					'depthFrom'    => 0,
					'depthTo'      => 1,
				),
			) );

		}

		/**
		 * Get content tab
		 *
		 * @return [type] [description]
		 */
		public function get_tab_content() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id  = $this->get_requested_menu_id();
			$settings = $this->get_settings( $menu_id );
			$builder  = jet_menu()->get_core()->init_module( 'cherry-ui-elements', array() );
			$template = jet_menu()->get_template( 'admin/tab-content.php' );
			$instance = $builder->get_ui_element_instance( 'switcher', array(
				'type'   => 'switcher',
				'id'     => 'enabled_' . $menu_id,
				'name'   => 'enabled',
				'value'  => isset( $settings['enabled'] ) ? $settings['enabled'] : '',
				'toggle' => array(
					'true_toggle'  => esc_html__( 'Yes', 'jet-menu' ),
					'false_toggle' => esc_html__( 'No', 'jet-menu' ),
				),
				'label'  => esc_html__( 'Mega Submenu Enabled', 'jet-menu' ),
			) );

			$enabled = $instance->render();

			ob_start();
			include $template;
			$content = ob_get_clean();

			wp_send_json_success( array(
				'content' => $content,
			) );
		}

		/**
		 * Settings tab icon
		 *
		 * @return [type] [description]
		 */
		public function get_tab_icon() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id = $this->get_requested_menu_id();

			$elements = array(
				'menu_icon' => array(
					'type'        => 'iconpicker',
					'id'          => 'menu_icon',
					'name'        => 'menu_icon',
					'label'       => esc_html__( 'Menu icon', 'jet-menu' ),
					'auto_parse'  => true,
					'value'       => '',
					'icon_data'   => array(
						'icon_set'    => 'jetMenuIcons',
						'icon_css'    => jet_menu()->plugin_url( 'assets/public/css/font-awesome.min.css' ),
						'icon_base'   => 'fa',
						'icon_prefix' => '',
						'icons'       => false,
					),
				),
				'icon_color' => array(
					'type'   => 'colorpicker',
					'id'     => 'icon_color',
					'name'   => 'icon_color',
					'alpha'  => true,
					'value'  => '',
					'label'  => esc_html__( 'Icon color', 'jet-menu' ),
				),
			);

			wp_send_json_success( array(
				'content' => $this->render_ui_elements( $elements, $menu_id ),
			) );
		}

		/**
		 * Settings tab badges
		 *
		 * @return [type] [description]
		 */
		public function get_tab_badges() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id = $this->get_requested_menu_id();

			$elements = array(
				'menu_badge' => array(
					'type'        => 'text',
					'id'          => 'menu_badge',
					'name'        => 'menu_badge',
					'label'       => esc_html__( 'Menu badge', 'jet-menu' ),
					'value'       => '',
				),
				'badge_color' => array(
					'type'   => 'colorpicker',
					'id'     => 'badge_color',
					'name'   => 'badge_color',
					'alpha'  => true,
					'value'  => '',
					'label'  => esc_html__( 'Badge color', 'jet-menu' ),
				),
				'badge_bg_color' => array(
					'type'   => 'colorpicker',
					'id'     => 'badge_bg_color',
					'name'   => 'badge_bg_color',
					'alpha'  => true,
					'value'  => '',
					'label'  => esc_html__( 'Badge background color', 'jet-menu' ),
				),
			);

			wp_send_json_success( array(
				'content' => $this->render_ui_elements( $elements, $menu_id ),
			) );
		}

		/**
		 * Settings tab misc
		 *
		 * @return [type] [description]
		 */
		public function get_tab_misc() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id = $this->get_requested_menu_id();

			$elements = array(
				'hide_item_text' => array(
					'type'  => 'switcher',
					'id'    => 'hide_item_text',
					'name'  => 'hide_item_text',
					'label' => esc_html__( 'Hide item navigation label', 'jet-menu' ),
					'value' => '',
					'toggle' => array(
						'true_toggle'  => 'On',
						'false_toggle' => 'Off',
					),
				),
				'item_padding' => array(
					'type'  => 'dimensions',
					'id'    => 'item_padding',
					'name'  => 'item_padding',
					'label' => esc_html__( 'Set custom padding for this item', 'jet-menu' ),
					'range' => array(
						'px' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
					),
					'value' => '',
				),
			);

			wp_send_json_success( array(
				'content' => $this->render_ui_elements( $elements, $menu_id ),
			) );
		}

		/**
		 * Settings tab vertical menu
		 *
		 * @return [type] [description]
		 */
		public function get_tab_vertical_menu() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id = $this->get_requested_menu_id();

			$elements = array(
				'mega_menu_width' => array(
					'type'       => 'slider',
					'id'         => 'mega_menu_width',
					'name'       => 'mega_menu_width',
					'value'      => '',
					'max_value'  => 2000,
					'min_value'  => 200,
					'step_value' => 1,
					'label'      => esc_html__( 'Set custom mega menu width for this item (px)', 'jet-menu' ),
				),
				'vertical_mega_menu_position' => array(
					'type'    => 'radio',
					'id'      => 'vertical_mega_menu_position',
					'name'    => 'vertical_mega_menu_position',
					'value'   => 'default',
					'options' => array(
						'default' => array(
							'label' => esc_html__( 'Relative the menu item', 'jet-menu' ),
						),
						'top'     => array(
							'label' => esc_html__( 'Relative the menu container', 'jet-menu' ),
						),
					),
					'label'   => esc_html__( 'Vertical mega menu position', 'jet-menu' ),
				),
			);

			wp_send_json_success( array(
				'content' => $this->render_ui_elements( $elements, $menu_id ),
			) );
		}

		/**
		 * Settings tab custom settings
		 *
		 * @return [type] [description]
		 */
		public function get_tab_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id = $this->get_requested_menu_id();

			$elements = array(
				'custom_mega_menu_position' => array(
					'type'    => 'radio',
					'id'      => 'custom_mega_menu_position',
					'name'    => 'custom_mega_menu_position',
					'value'   => 'default',
					'options' => array(
						'default' => array(
							'label' => esc_html__( 'Default', 'jet-menu' ),
						),
						'relative-item' => array(
							'label' => esc_html__( 'Relative the menu item', 'jet-menu' ),
						),
					),
					'label'   => esc_html__( 'Mega menu position', 'jet-menu' ),
				),
				'custom_mega_menu_width' => array(
					'type'       => 'slider',
					'id'         => 'custom_mega_menu_width',
					'name'       => 'custom_mega_menu_width',
					'value'      => '',
					'max_value'  => 2000,
					'min_value'  => 200,
					'step_value' => 1,
					'label'      => esc_html__( 'Set custom mega menu width for this item (px)', 'jet-menu' ),
				),
			);

			wp_send_json_success( array(
				'content' => $this->render_ui_elements( $elements, $menu_id ),
			) );
		}

		/**
		 * Save menu settings
		 *
		 * @return void
		 */
		public function save_menu_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
				) );
			}

			$menu_id  = $this->get_requested_menu_id();
			$settings = $_POST;

			unset( $settings['menu_id'] );
			unset( $settings['action'] );

			$sanitized_settings = array();

			foreach ( $settings as $key => $value ) {
				$sanitized_settings[ $key ] = $this->sanitize_field( $key, $value );
			}

			$old_settings = $this->get_settings( $menu_id );

			if ( ! $old_settings ) {
				$old_settings = array();
			}

			$new_settings = array_merge( $old_settings, $sanitized_settings );

			$this->set_item_settings( $menu_id, $new_settings );

			do_action( 'jet-menu/item-settings/save' );

			wp_send_json_success( array(
				'message' => esc_html__( 'Success!', 'jet-menu' ),
			) );

		}

		/**
		 * Sanitize field
		 *
		 * @param  [type] $key   [description]
		 * @param  [type] $value [description]
		 * @return [type]        [description]
		 */
		public function sanitize_field( $key, $value ) {

			$default            = 'esc_attr';
			$specific_callbacks = apply_filters( 'jet-menu/settings/tabs/sanitize-callbacks', array(
				'icon_size'    => 'absint',
				'menu_badge'   => 'wp_kses_post',
				'item_padding' => array( $this, 'sanitize_dimensions' ),
			) );

			$callback = isset( $specific_callbacks[ $key ] ) ? $specific_callbacks[ $key ] : $default;

			return call_user_func( $callback, $value );
		}

		/**
		 * Sanitize dimensions
		 *
		 * @param  [type] $value [description]
		 * @return [type]        [description]
		 */
		public function sanitize_dimensions( $value ) {
			return $value;
		}

		/**
		 * Get menu ID from request data
		 *
		 * @return [type] [description]
		 */
		public function get_requested_menu_id() {

			$menu_id = isset( $_REQUEST['menu_id'] ) ? absint( $_REQUEST['menu_id'] ) : false;

			if ( ! $menu_id ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Incorrect input data', 'jet-menu' ),
				) );
			}

			return $menu_id;

		}

		/**
		 * Render UI eements
		 *
		 * @return [type] [description]
		 */
		public function render_ui_elements( $elements = array(), $menu_id = null ) {

			$builder  = jet_menu()->get_core()->init_module( 'cherry-ui-elements', array() );
			$content  = '';
			$settings = $this->get_settings( $menu_id );

			add_filter( 'cherry_ui_add_data_to_element', '__return_true' );

			foreach ( $elements as $key => $field ) {
				$field['value'] = isset( $settings[ $key ] ) ? $settings[ $key ] : $field['value'];
				$instance       = $builder->get_ui_element_instance( $field['type'], $field );
				$content       .= $instance->render();
			}

			return $content;
		}

		/**
		 * Returns menu item settings
		 *
		 * @param  [type] $id [description]
		 * @return [type]     [description]
		 */
		public function get_settings( $id ) {
			return get_post_meta( $id, $this->meta_key, true );
		}

		/**
		 * Update menu item settings
		 *
		 * @param integer $id       [description]
		 * @param array   $settings [description]
		 */
		public function set_item_settings( $id = 0, $settings = array() ) {
			update_post_meta( $id, $this->meta_key, $settings );
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

}

/**
 * Returns instance of Jet_Menu_Settings_Item
 *
 * @return object
 */
function jet_menu_settings_item() {
	return Jet_Menu_Settings_Item::get_instance();
}
