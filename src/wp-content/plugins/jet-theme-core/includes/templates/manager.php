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

if ( ! class_exists( 'Jet_Theme_Core_Templates_Manager' ) ) {

	/**
	 * Define Jet_Theme_Core_Templates_Manager class
	 */
	class Jet_Theme_Core_Templates_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Sources list
		 *
		 * @var array
		 */
		private $_sources = array();

		/**
		 * Sources list
		 *
		 * @var array
		 */
		private $_library_types = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'wp_ajax_jet_theme_get_templates', array( $this, 'get_templates' ) );
			add_action( 'wp_ajax_jet_theme_core_clone_template', array( $this, 'clone_template' ) );

			// Process JetImpext template request
			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.2.8', '>' ) ) {
				add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ), 20 );
			} else {
				add_action( 'wp_ajax_elementor_get_template_data', array( $this, 'get_template_data' ), -1 );
			}

			$this->register_sources();
			$this->register_library_types();

			add_filter( 'jet-theme-core/assets/editor/localize', array( $this, 'localize_tabs' ) );

		}

		/**
		 * Add tabs data to localize object
		 *
		 * @return [type] [description]
		 */
		public function localize_tabs( $data ) {

			$screen  = get_current_screen();
			$tabs    = $this->get_template_tabs();
			$ids     = array_keys( $tabs );
			$default = $ids[0];

			if ( ! empty( $screen ) && isset( $screen->post_type ) ) {
				if ( jet_theme_core()->templates->slug() === $screen->post_type && 'post' === $screen->base ) {
					$type    = get_post_meta( get_the_ID(), '_elementor_template_type', true );
					$default = ! empty( $type ) ? $type : $default;
				}
			}

			$data['tabs']       = $this->get_template_tabs();
			$data['defaultTab'] = $default;

			return $data;

		}

		/**
		 * Register sources list
		 *
		 * @return void
		 */
		public function register_sources() {

			require jet_theme_core()->plugin_path( 'includes/templates/sources/base.php' );

			$sources = array(
				'jet-api'   => 'Jet_Theme_Core_Templates_Source_Api',
				'jet-theme' => 'Jet_Theme_Core_Templates_Source_Theme',
				'jet-local' => 'Jet_Theme_Core_Templates_Source_Local',
			);

			foreach ( $sources as $source => $class ) {
				require jet_theme_core()->plugin_path( 'includes/templates/sources/' . $source . '.php' );
				$this->add_source( $source, $class );
			}

		}

		/**
		 * Register allowed library types
		 *
		 * @return [type] [description]
		 */
		public function register_library_types() {

			$this->_library_types = jet_theme_core()->structures->get_structures_for_post_type();

		}

		/**
		 * Returns template tabs data
		 *
		 * @return [type] [description]
		 */
		public function get_template_tabs() {

			$tabs = jet_theme_core()->structures->get_structures_for_popup();

			$tabs = array_merge( $tabs, array(
				'local' => array(
					'title'    => esc_html__( 'My Library', 'jet-theme-core' ),
					'data'     => array(),
					'sources'  => array( 'jet-local' ),
					'settings' => array(),
				),
			) );

			return $tabs;

		}

		public function get_library_types() {
			return $this->_library_types;
		}

		/**
		 * Register templates source.
		 *
		 * @param [type] $slug       [description]
		 * @param [type] $class_name [description]
		 */
		public function add_source( $slug, $class_name ) {
			$this->_sources[ $slug ] = new $class_name();
		}

		/**
		 * Returns needed source instance
		 *
		 * @return object
		 */
		public function get_source( $slug = null ) {
			return isset( $this->_sources[ $slug ] ) ? $this->_sources[ $slug ] : false;
		}

		/**
		 * Return Templates list from options
		 *
		 * @return void
		 */
		public function get_templates_list() {

			$templates = get_posts( array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => jet_theme_core()->templates->slug(),
			) );

			return $templates;
		}

		/**
		 * Return Templates list from options
		 *
		 * @return void
		 */
		public function get_templates_for_options() {

			$templates = $this->get_templates_list();

			$default = array(
				'0' => esc_html__( 'Not Selected', 'jet-theme-core' ),
			);

			if ( empty( $templates ) ) {
				return $default;
			} else {
				$prepared = wp_list_pluck( $templates, 'post_title', 'ID' );
				return $default + $prepared;
			}

		}

		/**
		 * Get templates callback
		 *
		 * @return void
		 */
		public function get_templates() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$tab     = $_GET['tab'];
			$tabs    = $this->get_template_tabs();
			$sources = $tabs[ $tab ]['sources'];

			$result = array(
				'templates'  => array(),
				'categories' => array(),
				'keywords'   => array(),
			);

			foreach ( $sources as $source_slug ) {

				$source = isset( $this->_sources[ $source_slug ] ) ? $this->_sources[ $source_slug ] : false;

				if ( $source ) {
					$result['templates']  = array_merge( $result['templates'], $source->get_items( $tab ) );
					$result['categories'] = array_merge( $result['categories'], $source->get_categories( $tab ) );
					$result['keywords']   = array_merge( $result['keywords'], $source->get_keywords( $tab ) );
				}

			}

			$all_cats = array(
				array(
					'slug' => '',
					'title' => esc_html__( 'All', 'jet-theme-core' ),
				)
			);

			if ( ! empty( $result['categories'] ) ) {
				$result['categories'] = array_merge( $all_cats, $result['categories'] );
			}

			wp_send_json_success( $result );

		}

		/**
		 * Clone template
		 * @return [type] [description]
		 */
		public function clone_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$template = isset( $_REQUEST['template'] ) ? $_REQUEST['template'] : false;

			if ( ! $template ) {
				wp_send_json_error();
			}

			$template_id = isset( $template['template_id'] ) ? esc_attr( $template['template_id'] ) : false;
			$source_name = isset( $template['source'] ) ? esc_attr( $template['source'] ) : false;
			$source      = isset( $this->_sources[ $source_name ] ) ? $this->_sources[ $source_name ] : false;

			if ( ! $source || ! $template_id ) {
				wp_send_json_error();
			}

			$template_data = $source->get_item( $template_id );

			if ( ! empty( $template_data['content'] ) ) {
				wp_insert_post( array(
					'post_type'   => jet_theme_core()->templates->slug(),
					'post_title'  => $template['title'],
					'post_status' => 'publish',
					'meta_input'  => array(
						'_elementor_data'          => $template_data['content'],
						'_elementor_edit_mode'     => 'builder',
						'_elementor_template_type' => $template_data['type'],
					),
					'tax_input'  => array(
						jet_theme_core()->templates->type_tax => $template_data['type'],
					),
				) );
			}

			wp_send_json_success();

		}

		/**
		 * Register AJAX actions
		 *
		 * @param  [type] $ajax [description]
		 * @return [type]       [description]
		 */
		public function register_ajax_actions( $ajax ) {

			if ( ! isset( $_POST['actions'] ) ) {
				return;
			}

			$actions     = json_decode( stripslashes( $_REQUEST['actions'] ), true );
			$data        = false;

			foreach ( $actions as $id => $action_data ) {
				if ( ! isset( $action_data['get_template_data'] ) ) {
					$data = $action_data;
				}
			}

			if ( ! $data ) {
				return;
			}

			if ( ! isset( $data['data'] ) ) {
				return;
			}

			if ( ! isset( $data['data']['source'] ) ) {
				return;
			}

			$source = $data['data']['source'];

			if ( ! isset( $this->_sources[ $source ] ) ) {
				return;
			}

			$ajax->register_ajax_action( 'get_template_data', function( $data ) {
				return $this->get_template_data_array( $data );
			} );

		}

		/**
		 * Returns template data array
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public function get_template_data_array( $data ) {

			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			if ( empty( $data['template_id'] ) ) {
				return false;
			}

			$source_name = isset( $data['source'] ) ? esc_attr( $data['source'] ) : '';

			if ( ! $source_name ) {
				return false;
			}

			$source = isset( $this->_sources[ $source_name ] ) ? $this->_sources[ $source_name ] : false;

			if ( ! $source ) {
				return false;
			}

			if ( empty( $data['tab'] ) ) {
				return false;
			}

			$template = $source->get_item( $data['template_id'], $data['tab'] );

			return $template;
		}

		/**
		 * Return template data callback for elementor versions
		 * @return [type] [description]
		 */
		public function get_template_data() {

			$template = $this->get_template_data_array( $_REQUEST );

			if ( ! $template ) {
				wp_send_json_error();
			}

			wp_send_json_success( $template );

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
