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

if ( ! class_exists( 'Jet_Menu_Post_Type' ) ) {

	/**
	 * Define Jet_Menu_Post_Type class
	 */
	class Jet_Menu_Post_Type {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		protected $post_type = 'jet-menu';
		protected $meta_key  = 'jet-menu-item';

		/**
		 * Constructor for the class
		 */
		public function init() {

			$this->register_post_type();
			$this->edit_redirect();

			add_filter( 'option_elementor_cpt_support', array( $this, 'set_option_support' ) );
			add_filter( 'default_option_elementor_cpt_support', array( $this, 'set_option_support' ) );

			add_filter( 'jet-menu/assets/admin/localize', array( $this, 'localize_edit_url' ) );

			add_action( 'template_include', array( $this, 'set_post_type_template' ), 9999 );
		}

		/**
		 * Returns post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Returns Mega Menu meta key
		 *
		 * @return string
		 */
		public function meta_key() {
			return $this->meta_key;
		}

		/**
		 * Add elementor support for mega menu items.
		 */
		public function set_option_support( $value ) {

			if ( empty( $value ) ) {
				$value = array();
			}

			return array_merge( $value, array( $this->slug() ) );
		}

		/**
		 * Add edit URL to JS settings
		 *
		 * @param  array $settings Default settings.
		 * @return aray
		 */
		public function localize_edit_url( $settings ) {
			$settings['editURL'] = add_query_arg(
				array(
					'jet-open-editor' => 1,
					'item'            => '%id%',
					'menu'            => '%menuid%',
				),
				esc_url( admin_url( '/' ) )
			);

			return $settings;
		}

		/**
		 * Register post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			$labels = array(
				'name'          => esc_html__( 'Mega Menu Items', 'jet-menu' ),
				'singular_name' => esc_html__( 'Mega Menu Item', 'jet-menu' ),
				'add_new'       => esc_html__( 'Add New Mega Menu Item', 'jet-menu' ),
				'add_new_item'  => esc_html__( 'Add New Mega Menu Item', 'jet-menu' ),
				'edit_item'     => esc_html__( 'Edit Mega Menu Item', 'jet-menu' ),
				'menu_name'     => esc_html__( 'Mega Menu Items', 'jet-menu' ),
			);

			$args = array(
				'labels'              => $labels,
				'hierarchical'        => false,
				'description'         => 'description',
				'taxonomies'          => array(),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => null,
				'menu_icon'           => null,
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => true,
				'capability_type'     => 'post',
				'supports'            => array( 'title', 'editor' ),
			);

			register_post_type( $this->slug(), $args );

		}

		/**
		 * Edit redirect
		 *
		 * @return void
		 */
		public function edit_redirect() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( empty( $_REQUEST['jet-open-editor'] ) ) {
				return;
			}

			if ( empty( $_REQUEST['item'] ) ) {
				return;
			}

			if ( empty( $_REQUEST['menu'] ) ) {
				return;
			}

			$menu_id      = intval( $_REQUEST['menu'] );
			$menu_item_id = intval( $_REQUEST['item'] );
			$mega_menu_id = get_post_meta( $menu_item_id, $this->meta_key(), true );

			if ( ! $mega_menu_id ) {

				$mega_menu_id = wp_insert_post( array(
					'post_title'  => 'mega-item-' . $menu_item_id,
					'post_status' => 'publish',
					'post_type'   => $this->slug(),
				) );

				update_post_meta( $menu_item_id, $this->meta_key(), $mega_menu_id );

			}

			$edit_link = add_query_arg(
				array(
					'post'        => $mega_menu_id,
					'action'      => 'elementor',
					'context'     => 'jet-menu',
					'parent_menu' => $menu_id,
				),
				admin_url( 'post.php' )
			);

			wp_redirect( $edit_link );
			die();

		}

		/**
		 * Returns related mega menu post
		 *
		 * @param  int $menu_id Menu ID
		 * @return [type]          [description]
		 */
		public function get_related_menu_post( $menu_id ) {
			return get_post_meta( $menu_id, $this->meta_key(), true );
		}

		/**
		 * Set blank template for editor
		 */
		public function set_post_type_template( $template ) {

			$found = false;

			if ( is_singular( $this->slug() ) ) {
				$found    = true;
				$template = jet_menu()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'jet-menu/template-include/found' );
			}

			return $template;

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
 * Returns instance of Jet_Menu_Post_Type
 *
 * @return object
 */
function jet_menu_post_type() {
	return Jet_Menu_Post_Type::get_instance();
}
