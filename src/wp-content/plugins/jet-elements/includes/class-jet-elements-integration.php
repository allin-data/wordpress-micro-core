<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Integration' ) ) {

	/**
	 * Define Jet_Elements_Integration class
	 */
	class Jet_Elements_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Check if processing elementor widget
		 *
		 * @var boolean
		 */
		private $is_elementor_ajax = false;

		/**
		 * Initalize integration hooks
		 *
		 * @return void
		 */
		public function init() {

			add_action( 'elementor/init', array( $this, 'register_category' ) );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_vendor_addons' ), 20 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'rewrite_controls' ), 10 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, -1 );
		}

		/**
		 * Set $this->is_elementor_ajax to true on Elementor AJAX processing
		 *
		 * @return  void
		 */
		public function set_elementor_ajax() {
			$this->is_elementor_ajax = true;
		}

		/**
		 * Check if we currently in Elementor mode
		 *
		 * @return void
		 */
		public function in_elementor() {

			$result = false;

			if ( wp_doing_ajax() ) {
				$result = $this->is_elementor_ajax;
			} elseif ( Elementor\Plugin::instance()->editor->is_edit_mode()
				|| Elementor\Plugin::instance()->preview->is_preview_mode() ) {
				$result = true;
			}

			/**
			 * Allow to filter result before return
			 *
			 * @var bool $result
			 */
			return apply_filters( 'jet-elements/in-elementor', $result );
		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			$avaliable_widgets = jet_elements_settings()->get( 'avaliable_widgets' );

			require jet_elements()->plugin_path( 'includes/base/class-jet-elements-base.php' );

			foreach ( glob( jet_elements()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
				$slug = basename( $file, '.php' );

				$enabled = isset( $avaliable_widgets[ $slug ] ) ? $avaliable_widgets[ $slug ] : false;

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {
					$this->register_addon( $file, $widgets_manager );
				}
			}
		}

		/**
		 * Register vendor addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_vendor_addons( $widgets_manager ) {

			$woo_conditional = array(
				'cb'  => 'class_exists',
				'arg' => 'WooCommerce',
			);

			$allowed_vendors = apply_filters(
				'jet-elements/allowed-vendor-addons',
				array(
					'smartslider3' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-smartslider3.php'
						),
						'conditional' => array(
							'cb'  => 'class_exists',
							'arg' => 'SmartSlider3',
						),
					),
					'woo_recent_products' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-recent-products.php'
						),
						'conditional' => $woo_conditional,
					),
					'woo_featured_products' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-featured-products.php'
						),
						'conditional' => $woo_conditional,
					),
					'woo_sale_products' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-sale-products.php'
						),
						'conditional' => $woo_conditional,
					),
					'woo_best_selling_products' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-best-selling-products.php'
						),
						'conditional' => $woo_conditional,
					),
					'woo_top_rated_products' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-top-rated-products.php'
						),
						'conditional' => $woo_conditional,
					),
					'woo_product' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-woo-product.php'
						),
						'conditional' => $woo_conditional,
					),
					'contact_form7' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-contact-form7.php'
						),
						'conditional' => array(
							'cb'  => 'defined',
							'arg' => 'WPCF7_PLUGIN_URL',
						),
					),
					'mp_timetable' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-mp-timetable.php'
						),
						'conditional' => array(
							'cb'  => 'defined',
							'arg' => 'MP_TT_PLUGIN_NAME',
						),
					),
					'booked_calendar' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-booked-calendar.php'
						),
						'conditional' => array(
							'cb'  => 'class_exists',
							'arg' => 'booked_plugin',
						),
					),
					'booked_appointments' => array(
						'file' => jet_elements()->plugin_path(
							'includes/addons/vendor/jet-elements-booked-appointments.php'
						),
						'conditional' => array(
							'cb'  => 'class_exists',
							'arg' => 'booked_plugin',
						),
					),
				)
			);

			foreach ( $allowed_vendors as $vendor ) {
				if ( is_callable( $vendor['conditional']['cb'] )
					&& true === call_user_func( $vendor['conditional']['cb'], $vendor['conditional']['arg'] ) ) {
					$this->register_addon( $vendor['file'], $widgets_manager );
				}
			}
		}

		/**
		 * Rewrite core controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function rewrite_controls( $controls_manager ) {

			$controls = array(
				$controls_manager::ICON => 'Jet_Elements_Control_Icon',
			);

			foreach ( $controls as $control_id => $class_name ) {

				if ( $this->include_control( $class_name ) ) {
					$controls_manager->unregister_control( $control_id );
					$controls_manager->register_control( $control_id, new $class_name() );
				}
			}

		}

		/**
		 * Add new controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

			$grouped = array(
				'jet-box-style' => 'Jet_Group_Control_Box_Style',
			);

			foreach ( $grouped as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, true ) ) {
					$controls_manager->add_group_control( $control_id, new $class_name() );
				}
			}

		}

		/**
		 * Include control file by class name.
		 *
		 * @param  [type] $class_name [description]
		 * @return [type]             [description]
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( jet_elements()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_elements()->plugin_path( $filename );

			return true;
		}

		/**
		 * Register addon by file name
		 *
		 * @param  string $file            File name.
		 * @param  object $widgets_manager Widgets manager instance.
		 * @return void
		 */
		public function register_addon( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;
			$cherry_cat       = 'cherry';

			$elements_manager->add_category(
				$cherry_cat,
				array(
					'title' => esc_html__( 'Jet Elements', 'jet-elements' ),
					'icon'  => 'font',
				),
				1
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Elements_Integration
 *
 * @return object
 */
function jet_elements_integration() {
	return Jet_Elements_Integration::get_instance();
}
