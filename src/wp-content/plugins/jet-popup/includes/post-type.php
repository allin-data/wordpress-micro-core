<?php
/**
 * Jet Popup post type template
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Post_Type' ) ) {

	/**
	 * Define Jet_Popup_Post_Type class
	 */
	class Jet_Popup_Post_Type {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * [$post_type description]
		 * @var string
		 */
		protected $post_type = 'jet-popup';

		/**
		 * [$meta_key description]
		 * @var string
		 */
		protected $meta_key = 'jet-popup-item';

		/**
		 * Site conditions
		 * @var array
		 */
		private $conditions = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			self::register_post_type();

			add_filter( 'option_elementor_cpt_support', [ $this, 'set_option_support' ] );

			add_filter( 'default_option_elementor_cpt_support', [ $this, 'set_option_support' ] );

			add_action( 'elementor/documents/register', [ $this, 'register_elementor_document_type' ] );

			add_action( 'wp_insert_post', [ $this, 'set_document_type_on_post_create' ], 10, 2 );

			add_action( 'template_include', [ $this, 'set_post_type_template' ], 9999 );

			add_filter( 'manage_' . $this->slug() . '_posts_columns', [ $this, 'set_post_columns' ] );

			add_action( 'manage_' . $this->slug() . '_posts_custom_column', [ $this, 'post_columns' ], 10, 2 );

			add_action( 'admin_footer', [ $this, 'render_vue_template' ] );

			if ( is_admin() ) {
				add_action( 'admin_menu', [ $this, 'add_popup_sub_page' ], 90 );
			}

		}

		/**
		 * Set required post columns
		 *
		 * @param [type] $columns [description]
		 */
		public function set_post_columns( $columns ) {

			unset( $columns['date'] );

			//$columns['type']       = __( 'Type', 'jet-popup' );
			$columns['conditions'] = __( 'Active Conditions', 'jet-popup' );
			$columns['date']       = __( 'Date', 'jet-popup' );

			return $columns;

		}

		/**
		 * Manage post columns content
		 *
		 * @return [type] [description]
		 */
		public function post_columns( $column, $post_id ) {

			$all_conditions = jet_popup()->conditions->get_site_conditions();

			switch ( $column ) {

				case 'conditions':

					echo '<div class="jet-popup-conditions">';

					if ( isset( $all_conditions[ 'jet-popup' ] ) ) {

						if ( ! empty( $all_conditions[ 'jet-popup' ][ $post_id ] ) ) {

							/*printf(
								'<div class="jet-popup-conditions-active"><span class="dashicons dashicons-yes"></span>%1$s</div>',
								__( 'Active', 'jet-popup' )
							);*/

							printf(
								'<div class="jet-popup-conditions-list">%1$s</div>',
								jet_popup()->conditions->post_conditions_verbose( $post_id )
							);
						} else {
							printf(
								'<div class="jet-popup-conditions-undefined"><span class="dashicons dashicons-warning"></span>%1$s</div>',
								__( 'Conditions not selected', 'jet-popup' )
							);

						}
					} else {
						printf(
							'<div class="jet-popup-conditions-undefined"><span class="dashicons dashicons-warning"></span>%1$s</div>',
							__( 'Conditions not selected', 'jet-popup' )
						);
					}

					echo '</div>';

					break;

			}

		}

		/**
		 * Set apropriate document type on post creation
		 *
		 * @param int     $post_id Created post ID.
		 * @param WP_Post $post    Created post object.
		 */
		public function set_document_type_on_post_create( $post_id, $post ) {

			if ( $post->post_type !== $this->slug() ) {
				return;
			}

			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return;
			}

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( $this->slug() );

			update_post_meta( $post_id, $doc_type::TYPE_META_KEY, $this->slug() );

		}

		/**
		 * Register apropriate document type for 'jet-woo-builder' post type
		 *
		 * @param  Elementor\Core\Documents_Manager $documents_manager [description]
		 * @return void
		 */
		public function register_elementor_document_type( $documents_manager ) {
			require jet_popup()->plugin_path( 'includes/document.php' );

			$documents_manager->register_document_type( $this->slug(), 'Jet_Popup_Document' );
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
		 * Register post type
		 *
		 * @return void
		 */
		static public function register_post_type() {

			$labels = array(
				'name'          => esc_html__( 'JetPopup', 'jet-popup' ),
				'singular_name' => esc_html__( 'Jet Popup', 'jet-popup' ),
				'all_items'     => esc_html__( 'All Popups', 'jet-popup' ),
				'add_new'       => esc_html__( 'Add New Popup', 'jet-popup' ),
				'add_new_item'  => esc_html__( 'Add New Popup', 'jet-popup' ),
				'edit_item'     => esc_html__( 'Edit Popup', 'jet-popup' ),
				'menu_name'     => esc_html__( 'JetPopup', 'jet-popup' ),
			);

			$supports = apply_filters( 'jet-popups/post-type/register/supports', [ 'title' ] );

			$args = array(
				'labels'              => $labels,
				'hierarchical'        => false,
				'description'         => 'description',
				'taxonomies'          => [],
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => null,
				'menu_icon'           => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDIwIDIwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTE5LDdoLTNWNVYxYzAtMC42LTAuNC0xLTEtMUgxQzAuNCwwLDAsMC40LDAsMXY0djE0YzAsMC42LDAuNCwxLDEsMWgxNGMwLjYsMCwxLTAuNCwxLTF2LTNoM2MwLjYsMCwxLTAuNCwxLTFWOEMyMCw3LjQsMTkuNiw3LDE5LDd6IE02LjUsMkM2LjgsMiw3LDIuMiw3LDIuNVM2LjgsMyw2LjUsM1M2LDIuOCw2LDIuNVM2LjIsMiw2LjUsMnogTTQuNSwyQzQuOCwyLDUsMi4yLDUsMi41UzQuOCwzLDQuNSwzUzQsMi44LDQsMi41UzQuMiwyLDQuNSwyeiBNMi41LDJDMi44LDIsMywyLjIsMywyLjVTMi44LDMsMi41LDNTMiwyLjgsMiwyLjVTMi4yLDIsMi41LDJ6IE0xNCw3SDVDNC40LDcsNCw3LjQsNCw4djdjMCwwLjYsMC40LDEsMSwxaDl2MkgyVjVoMTJWN3ogTTE5LDloLTF2MWgxdjFoLTF2LTFoLTF2MWgtMXYtMWgxVjloLTFWOGgxdjFoMVY4aDFWOXoiLz48L3N2Zz4=',
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => true,
				'capability_type'     => 'post',
				'supports'            => $supports,
			);

			register_post_type( 'jet-popup', $args );

		}

		/**
		 * Set blank template for editor
		 */
		public function set_post_type_template( $template ) {

			if ( is_singular( $this->slug() ) ) {

				$template = jet_popup()->plugin_path( 'templates/single.php' );

				if ( jet_popup()->elementor()->preview->is_preview_mode() ) {
					$template = jet_popup()->plugin_path( 'templates/editor.php' );
				}

				do_action( 'jet-popups/template-include/found' );

				return $template;
			}

			return $template;
		}

		/**
		 * Menu page
		 */
		public function add_popup_sub_page() {

			add_submenu_page(
				'edit.php?post_type=' . $this->slug(),
				__( 'Library', 'jet-popup' ),
				__( 'Popup Library', 'jet-popup' ),
				'edit_pages',
				'jet-popup-library',
				[ $this, 'library_page_render']
			);
		}

		/**
		 * [library_page_render description]
		 * @return [type] [description]
		 */
		public function library_page_render() {
			$crate_action = add_query_arg(
				array(
					'action' => 'jet_popup_create_from_preset',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			require jet_popup()->plugin_path( 'templates/vue-templates/preset-page.php' );
		}

		/**
		 * [render_vue_template description]
		 * @return [type] [description]
		 */
		public function render_vue_template() {

			$vue_templates = [
				'preset-library',
				'preset-filters',
				'preset-list',
				'preset-item',
			];

			foreach ( glob( jet_popup()->plugin_path( 'templates/vue-templates/' ) . '*.php' ) as $file ) {
				$path_info = pathinfo( $file );
				$template_name = $path_info['filename'];

				if ( in_array( $template_name, $vue_templates ) ) {?>
					<script type="text/x-template" id="<?php echo $template_name; ?>-template"><?php
						require $file; ?>
					</script><?php
				}
			}
		}

		/**
		 * [get_settings_page description]
		 * @return [type] [description]
		 */
		public function get_library_page_url() {
			return admin_url( 'edit.php?post_type=' . $this->slug() . '&page=' . $this->slug() . '-library' );
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
