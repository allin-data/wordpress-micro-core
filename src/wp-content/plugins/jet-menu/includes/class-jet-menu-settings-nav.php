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

if ( ! class_exists( 'Jet_Menu_Settings_Nav' ) ) {

	/**
	 * Define Jet_Menu_Settings_Nav class
	 */
	class Jet_Menu_Settings_Nav {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Holder for current menu ID
		 * @var integer
		 */
		protected $current_menu_id = null;

		/**
		 * Jet Menu settings page
		 *
		 * @var string
		 */
		protected $meta_key = 'jet_menu_settings';

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'admin_head-nav-menus.php', array( $this, 'register_nav_meta_box' ), 9 );
			add_filter( 'jet-menu/assets/admin/localize', array( $this, 'add_current_menu_id_to_localize' ) );
			add_action( 'wp_ajax_jet_save_settings', array( $this, 'save_menu_settings' ) );
			add_filter( 'get_user_option_metaboxhidden_nav-menus', array( $this, 'force_metabox_visibile' ), 10 );
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

			$menu_id = isset( $_POST['current_menu'] ) ? absint( $_POST['current_menu'] ) : false;

			if ( ! $menu_id ) {
				wp_send_json_error( array(
					'message' => esc_html__( 'Incorrect input data', 'jet-menu' ),
				) );
			}

			$settings = $_POST;

			unset( $settings['current_menu'] );
			unset( $settings['action'] );

			$old_settings = $this->get_settings( $menu_id );

			if ( ! $old_settings ) {
				$old_settings = array();
			}

			$new_settings = array_merge( $old_settings, $settings );

			$this->update_settings( $menu_id, $new_settings );

			wp_send_json_success( array(
				'message' => esc_html__( 'Success!', 'jet-menu' ),
			) );
		}

		/**
		 * Get settings from DB
		 *
		 * @param  [type] $menu_id [description]
		 * @return [type]          [description]
		 */
		public function get_settings( $menu_id ) {
			return get_term_meta( $menu_id, $this->meta_key, true );
		}

		/**
		 * Update menu item settings
		 *
		 * @param integer $id       [description]
		 * @param array   $settings [description]
		 */
		public function update_settings( $menu_id = 0, $settings = array() ) {
			update_term_meta( $menu_id, $this->meta_key, $settings );
		}

		/**
		 * Register nav menus page metabox with mega menu settings.
		 *
		 * @return void
		 */
		public function register_nav_meta_box() {

			global $pagenow;

			if ( 'nav-menus.php' !== $pagenow ) {
				return;
			}

			add_meta_box(
				'jet-menu-settings',
				esc_html__( 'JetMenu Settings', 'jet-menu' ),
				array( $this, 'render_metabox' ),
				'nav-menus',
				'side',
				'high'
			);

		}

		/**
		 * Force nav menu metabox with JetMenu settings to be allways visible.
		 *
		 * @param  array $result
		 * @return array
		 */
		public function force_metabox_visibile( $result ) {

			if ( ! is_array( $result ) ) {
				return $result;
			}

			if ( in_array( 'jet-menu-settings', $result ) ) {
				$result = array_diff( $result, array( 'jet-menu-settings' ) );
			}
			return $result;
		}

		/**
		 * Add curretn menu ID to localized data
		 *
		 * @param  array
		 * @return array
		 */
		public function add_current_menu_id_to_localize( $data ) {
			$data['currentMenuId'] = $this->get_selected_menu_id();
			return $data;
		}

		/**
		 * Render nav menus metabox
		 *
		 * @return void
		 */
		public function render_metabox() {

			$menu_id               = $this->get_selected_menu_id();
			$tagged_menu_locations = $this->get_tagged_theme_locations_for_menu_id( $menu_id );
			$theme_locations       = get_registered_nav_menus();
			$saved_settings        = $this->get_settings( $menu_id );

			if ( ! count( $theme_locations ) ) {
				$this->no_locations_message();
			} else if ( ! count ( $tagged_menu_locations ) ) {
				$this->empty_location_message();
			} else {
				$wrapper        = jet_menu()->get_template( 'admin/settings-nav.php' );
				$settings_list  = jet_menu()->get_template( 'admin/settings-nav-list.php' );
				$settings_stack = $this->get_registered_nav_settings();
				include $wrapper;
			}

		}

		/**
		 * Returns list of registered navigation settings
		 *
		 * @return [type] [description]
		 */
		public function get_registered_nav_settings() {

			return apply_filters( 'jet-menu/nav-settings/registered', array(
				'enabled' => array(
					'type'   => 'switcher',
					'id'     => 'jet-enabled',
					'name'   => 'enabled',
					'value'  => '',
					'style'  => 'small',
					'toggle' => array(
						'true_toggle'  => esc_html__( 'Yes', 'jet-menu' ),
						'false_toggle' => esc_html__( 'No', 'jet-menu' ),
					),
					'label'  => esc_html__( 'Enable JetMenu for current location', 'jet-menu' ),
				),
			) );

		}

		/**
		 * Notice if no menu locations registered in theme
		 *
		 * @return void
		 */
		public function no_locations_message() {
			printf( '<p>%s</p>', esc_html__( 'This theme does not register any menu locations.', 'jet-menu' ) );
			printf( '<p>%s</p>', esc_html__( 'You will need to create a new menu location to use the JetMenu on your site.', 'jet-menu' ) );
		}

		/**
		 * Notice if no menu locations registered in theme
		 *
		 * @return void
		 */
		public function empty_location_message() {
			printf( '<p>%s</p>', esc_html__( 'Please assign this menu to a theme location to enable the JetMenu settings.', 'jet-menu' ) );
			printf( '<p>%s</p>', esc_html__( 'To assign this menu to a theme location, scroll to the bottom of this page and tag the menu to a \'Display location\'.', 'jet-menu' ) );
		}

		/**
		 * Return the locations that a specific menu ID has been tagged to.
		 *
		 * @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
		 * @param  $menu_id    int
		 * @return array
		 */
		public function get_tagged_theme_locations_for_menu_id( $menu_id ) {

			$locations          = array();
			$nav_menu_locations = get_nav_menu_locations();

			foreach ( get_registered_nav_menus() as $id => $name ) {

				if ( isset( $nav_menu_locations[ $id ] ) && $nav_menu_locations[$id] == $menu_id )
					$locations[$id] = $name;
				}

				return $locations;
			}

		/**
		 * Get the current menu ID.
		 *
		 * @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
		 * @return int
		 */
		public function get_selected_menu_id() {

			if ( null !== $this->current_menu_id ) {
				return $this->current_menu_id;
			}

			$nav_menus            = wp_get_nav_menus( array('orderby' => 'name') );
			$menu_count           = count( $nav_menus );
			$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
			$add_new_screen       = ( isset( $_GET['menu'] ) && 0 == $_GET['menu'] ) ? true : false;

			$this->current_menu_id = $nav_menu_selected_id;

			// If we have one theme location, and zero menus, we take them right into editing their first menu
			$page_count = wp_count_posts( 'page' );
			$one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

			// Get recently edited nav menu
			$recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
			if ( empty( $recently_edited ) && is_nav_menu( $this->current_menu_id ) ) {
				$recently_edited = $this->current_menu_id;
			}

			// Use $recently_edited if none are selected
			if ( empty( $this->current_menu_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
				$this->current_menu_id = $recently_edited;
			}

			// On deletion of menu, if another menu exists, show it
			if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
				$this->current_menu_id = $nav_menus[0]->term_id;
			}

			// Set $this->current_menu_id to 0 if no menus
			if ( $one_theme_location_no_menus ) {
				$this->current_menu_id = 0;
			} elseif ( empty( $this->current_menu_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
				// if we have no selection yet, and we have menus, set to the first one in the list
				$this->current_menu_id = $nav_menus[0]->term_id;
			}

			return $this->current_menu_id;

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
 * Returns instance of Jet_Menu_Settings_Nav
 *
 * @return object
 */
function jet_menu_settings_nav() {
	return Jet_Menu_Settings_Nav::get_instance();
}
