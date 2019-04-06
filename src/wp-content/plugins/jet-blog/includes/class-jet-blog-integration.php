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

if ( ! class_exists( 'Jet_Blog_Integration' ) ) {

	/**
	 * Define Jet_Blog_Integration class
	 */
	class Jet_Blog_Integration {

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

		private $has_playlist = false;

		/**
		 * Initalize integration hooks
		 *
		 * @return void
		 */
		public function init() {

			add_action( 'elementor/init', array( $this, 'register_category' ) );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );
			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, -1 );

			add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			add_action( 'wp_footer', array( $this, 'print_playlist_trigger' ), 0 );

		}

		/**
		 * Set playlist trigger
		 */
		public function set_playlist_trigger() {
			$this->has_playlist = true;
		}

		/**
		 * Print playlist trigger
		 * @return [type] [description]
		 */
		public function print_playlist_trigger() {


			$has_playlist = $this->has_playlist ? 1 : 0;

			echo "<script type='text/javascript'>\n";
			echo "/* <![CDATA[ */\n";
			echo "var hasJetBlogPlaylist = $has_playlist;\n";
			echo "/* ]]> */\n";
			echo "</script>\n";

		}

		/**
		 * Enqueue editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-blog-icons-font',
				jet_blog()->plugin_url( 'assets/css/lib/jet-blog-icons/jet-blog-icons.css' ),
				array(),
				jet_blog()->get_version()
			);

			wp_enqueue_style(
				'jet-blog-editor',
				jet_blog()->plugin_url( 'assets/css/editor.css' ),
				array(),
				jet_blog()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'jet-blog',
				jet_blog()->plugin_url( 'assets/js/jet-blog.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_blog()->get_version(),
				true
			);

			wp_localize_script( 'jet-blog', 'JetBlogSettings', array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			) );

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
			return apply_filters( 'jet-blog/in-elementor', $result );
		}

		/**
		 * Add new controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

			$grouped = array(
				'jet-blog-box-style' => 'Jet_Blog_Group_Control_Box_Style',
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

			if ( ! file_exists( jet_blog()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_blog()->plugin_path( $filename );

			return true;
		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			require jet_blog()->plugin_path( 'includes/base/class-jet-blog-base.php' );

			foreach ( glob( jet_blog()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
				$this->register_addon( $file, $widgets_manager );
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
				$controls_manager::ICON => 'Jet_Blog_Control_Icon',
			);

			foreach ( $controls as $control_id => $class_name ) {

				if ( $this->include_control( $class_name ) ) {
					$controls_manager->unregister_control( $control_id );
					$controls_manager->register_control( $control_id, new $class_name() );
				}
			}

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
					'title' => esc_html__( 'Jet Elements', 'jet-blog' ),
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
 * Returns instance of Jet_Blog_Integration
 *
 * @return object
 */
function jet_blog_integration() {
	return Jet_Blog_Integration::get_instance();
}
