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

if ( ! class_exists( 'Jet_Theme_Core_Templates_Post_Type' ) ) {

	/**
	 * Define Jet_Theme_Core_Templates_Post_Type class
	 */
	class Jet_Theme_Core_Templates_Post_Type {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'jet-theme-core';

		/**
		 * Template meta cache key
		 *
		 * @var string
		 */
		public $cache_key = '_jet_template_cache';

		/**
		 * Template type arg for URL
		 * @var string
		 */
		public $type_tax = 'jet_library_type';

		/**
		 * Site conditions
		 * @var array
		 */
		private $conditions = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 20 );
				add_action( 'add_meta_boxes_' . $this->slug(), array( $this, 'disable_metaboxes' ), 9999 );
			}

			add_action( 'template_include', array( $this, 'set_editor_template' ), 9999 );
			add_action( 'jet-theme-core/blank-page/before-content', array( $this, 'template_before' ) );
			add_action( 'jet-theme-core/blank-page/after-content', array( $this, 'template_after' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_template_styles' ) );

			add_filter( 'views_edit-' . $this->post_type, array( $this, 'print_type_tabs' ) );

			add_action( 'admin_action_jet_create_new_template', array( $this, 'create_template' ) );

			add_filter( 'manage_' . $this->slug() . '_posts_columns', array( $this, 'set_post_columns' ) );
			add_action( 'manage_' . $this->slug() . '_posts_custom_column', array( $this, 'post_columns' ), 10, 2 );

		}

		/**
		 * Set required post columns
		 *
		 * @param [type] $columns [description]
		 */
		public function set_post_columns( $columns ) {

			unset( $columns['taxonomy-' . $this->type_tax ] );
			unset( $columns['date'] );

			$columns['type']       = __( 'Type', 'jet-theme-core' );
			$columns['conditions'] = __( 'Active Conditions', 'jet-theme-core' );
			$columns['date']       = __( 'Date', 'jet-theme-core' );

			$this->set_conditions();

			return $columns;

		}

		public function set_conditions() {

			$all_conditions = jet_theme_core()->conditions->get_site_conditions();
			$to_store       = $all_conditions;

			foreach ( $all_conditions as $type => $type_conditions ) {

				$entire_found = false;
				$found        = array();

				foreach ( $type_conditions as $post_id => $post_condition ) {

					if ( ! isset( $post_condition['main'] ) ) {
						continue;
					}

					if ( 'entire' === $post_condition['main'] && false === $entire_found ) {
						$entire_found = $post_id;
					} elseif ( 'entire' === $post_condition['main'] && $entire_found ) {
						unset( $to_store[ $type ][ $entire_found ] );
						$entire_found = $post_id;
					}

					if ( 'entire' !== $post_condition['main'] ) {

						$verbosed = jet_theme_core()->conditions->post_conditions_verbose( $post_id );

						if ( ! in_array( $verbosed, $found ) ) {
							$found[] = $verbosed;
						} else {
							unset( $to_store[ $type ][ $post_id ] );
						}

					}
				}

			}

			$this->conditions = $to_store;

		}

		/**
		 * Manage post columns content
		 *
		 * @return [type] [description]
		 */
		public function post_columns( $column, $post_id ) {

			$structure = jet_theme_core()->structures->get_post_structure( $post_id );

			switch ( $column ) {

				case 'type':

					if ( $structure ) {

						$link = add_query_arg( array(
							$this->type_tax => $structure->get_id(),
						) );

						printf( '<a href="%1$s">%2$s</a>', $link, $structure->get_single_label() );

					}

					break;

				case 'conditions':

					echo '<div class="jet-conditions">';

					printf(
						'<div class="jet-conditions-list">%1$s</div>',
						jet_theme_core()->conditions->post_conditions_verbose( $post_id )
					);

					if ( $structure && isset( $this->conditions[ $structure->get_id() ] ) ) {

						if ( ! empty( $this->conditions[ $structure->get_id() ][ $post_id ] ) ) {
							printf(
								'<div class="jet-conditions-active"><span class="dashicons dashicons-yes"></span>%1$s</div>',
								__( 'Active', 'jet-theme-core' )
							);
						}
					}

					echo '</div>';

					break;

			}

		}

		/**
		 * Create new template
		 *
		 * @return [type] [description]
		 */
		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'jet-theme-core' ),
					esc_html__( 'Error', 'jet-theme-core' )
				);
			}

			$type = isset( $_REQUEST['template_type'] ) ? esc_attr( $_REQUEST['template_type'] ) : false;

			if ( ! $type || ! array_key_exists( $type, jet_theme_core()->templates_manager->get_library_types() ) ) {
				wp_die(
					esc_html__( 'Incorrect template type. Please try again.', 'jet-theme-core' ),
					esc_html__( 'Error', 'jet-theme-core' )
				);
			}

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( $type );

			if ( ! $doc_type ) {
				wp_die(
					esc_html__( 'Incorrect template type. Please try again.', 'jet-theme-core' ),
					esc_html__( 'Error', 'jet-theme-core' )
				);
			}

			$post_data = array(
				'post_type'  => $this->slug(),
				'meta_input' => array(
					'_elementor_edit_mode' => 'builder',
				),
				'tax_input'  => array(
					$this->type_tax => $type,
				),
				'meta_input' => array(
					$doc_type::TYPE_META_KEY   => $type,
					'_elementor_page_settings' => array(
						'jet_conditions' => array(),
					),
				),
			);

			$title = isset( $_REQUEST['template_name'] ) ? esc_attr( $_REQUEST['template_name'] ) : '';

			if ( $title ) {
				$post_data['post_title'] = $title;
			}

			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				wp_die(
					esc_html__( 'Can\'t create template. Please try again', 'jet-theme-core' ),
					esc_html__( 'Error', 'jet-theme-core' )
				);
			}

			wp_redirect( Elementor\Utils::get_edit_link( $template_id ) );
			die();

		}

		/**
		 * Enqueue template related styles from Elementor
		 *
		 * @return void
		 */
		public function enqueue_template_styles() {

			$parts = array(
				'header',
			);

			foreach ( $parts as $part ) {

				$template = false;

				if ( $template ) {
					$css = new \Elementor\Post_CSS_File( $template );
					$css->enqueue();
				}

			}
		}

		/**
		 * Templates post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Disable metaboxes from Jet Templates
		 *
		 * @return void
		 */
		public function disable_metaboxes() {
			global $wp_meta_boxes;
			unset( $wp_meta_boxes[ $this->slug() ]['side']['core']['pageparentdiv'] );
		}

		/**
		 * Add opening wrapper if defined in manifes
		 *
		 * @return void
		 */
		public function template_before() {

			$editor = jet_theme_core()->config->get( 'editor' );

			if ( isset( $editor['template_before'] ) ) {
				echo $editor['template_before'];
			}

		}

		/**
		 * Add closing wrapper if defined in manifes
		 *
		 * @return void
		 */
		public function template_after() {

			$editor = jet_theme_core()->config->get( 'editor' );

			if ( isset( $editor['template_after'] ) ) {
				echo $editor['template_after'];
			}

		}

		/**
		 * Register templates post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			$args = array(
				'labels' => array(
					'name'               => esc_html__( 'Theme Parts', 'jet-theme-core' ),
					'singular_name'      => esc_html__( 'Template', 'jet-theme-core' ),
					'add_new'            => esc_html__( 'Add New', 'jet-theme-core' ),
					'add_new_item'       => esc_html__( 'Add New Template', 'jet-theme-core' ),
					'edit_item'          => esc_html__( 'Edit Template', 'jet-theme-core' ),
					'new_item'           => esc_html__( 'Add New Template', 'jet-theme-core' ),
					'view_item'          => esc_html__( 'View Template', 'jet-theme-core' ),
					'search_items'       => esc_html__( 'Search Template', 'jet-theme-core' ),
					'not_found'          => esc_html__( 'No Templates Found', 'jet-theme-core' ),
					'not_found_in_trash' => esc_html__( 'No Templates Found In Trash', 'jet-theme-core' ),
					'menu_name'          => esc_html__( 'My Library', 'jet-theme-core' ),
				),
				'public'              => true,
				'hierarchical'        => false,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'exclude_from_search' => true,
				'capability_type'     => 'post',
				'rewrite'             => false,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'elementor' ),
			);

			register_post_type(
				$this->slug(),
				apply_filters( 'jet-theme-core/templates/post-type/args', $args )
			);

			$tax_args = array(
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_in_nav_menus' => false,
				'show_admin_column' => true,
				'query_var'         => is_admin(),
				'rewrite'           => false,
				'public'            => false,
				'label'             => __( 'Type', 'jet-theme-core' ),
			);

			register_taxonomy(
				$this->type_tax,
				$this->slug(),
				apply_filters( 'jet-theme-core/templates/type-tax/args', $tax_args )
			);

		}

		/**
		 * Menu page
		 */
		public function add_templates_page() {

			add_submenu_page(
				jet_theme_core()->dashboard->slug(),
				esc_html__( 'My Library', 'jet-theme-core' ),
				esc_html__( 'My Library', 'jet-theme-core' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);

		}

		/**
		 * Print library types tabs
		 *
		 * @return [type] [description]
		 */
		public function print_type_tabs( $edit_links ) {

			$tabs = jet_theme_core()->templates_manager->get_library_types();
			$tabs = array_merge(
				array(
					'all' => esc_html__( 'All', 'jet-theme-core' ),
				),
				$tabs
			);

			$active_tab = isset( $_GET[ $this->type_tax ] ) ? $_GET[ $this->type_tax ] : 'all';
			$page_link  = admin_url( 'edit.php?post_type=' . $this->slug() );

			if ( ! array_key_exists( $active_tab, $tabs ) ) {
				$active_tab = 'all';
			}

			include jet_theme_core()->get_template( 'template-types-tabs.php' );

			return $edit_links;
		}

		/**
		 * Editor templates.
		 *
		 * @param  string $template Current template name.
		 * @return string
		 */
		public function set_editor_template( $template ) {

			$found = false;

			if ( is_singular( $this->slug() ) ) {
				$found    = true;
				$template = jet_theme_core()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'jet-theme-core/post-type/editor-template/found' );
			}

			return $template;

		}

	}

}
