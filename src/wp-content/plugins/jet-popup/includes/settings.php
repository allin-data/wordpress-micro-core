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

if ( ! class_exists( 'Jet_Popup_Settings' ) ) {

	/**
	 * Define Jet_Popup_Settings class
	 */
	class Jet_Popup_Settings {

		/**
		 * [$post_type description]
		 * @var string
		 */
		protected $post_type = 'jet-popup';

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'jet-popup-settings';

		/**
		 * [$localize_data description]
		 * @var array
		 */
		public $localize_data = [];

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Init page
		 */
		public function __construct() {

			add_action( 'admin_menu', [ $this, 'register_page' ], 91 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_settings_assets' ), 11 );

			add_action( 'admin_footer', [ $this, 'render_vue_template' ] );

			add_action( 'wp_ajax_jet_popup_save_settings', [ $this, 'save_settings' ] );

			add_action( 'wp_ajax_get_mailchimp_user_data', [ $this, 'get_mailchimp_user_data' ] );

			add_action( 'wp_ajax_get_mailchimp_lists', [ $this, 'get_mailchimp_lists' ] );

			add_action( 'wp_ajax_get_mailchimp_list_merge_fields', [ $this, 'get_mailchimp_list_merge_fields' ] );

			$this->generate_localize_data();
		}

		/**
		 * [generate_localize_data description]
		 * @return [type] [description]
		 */
		public function generate_localize_data() {

			$mailchimp_api_data = get_option( $this->key . '_mailchimp', [] );

			$this->localize_data = [
				'settings' => [
					'apikey' => $this->get( 'apikey', '' ),
				],
				'mailchimpApiData'  => $mailchimp_api_data,
			];
		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, [] );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {
			add_submenu_page(
				'edit.php?post_type=' . $this->slug(),
				__( 'Settings', 'jet-popup' ),
				__( 'Settings', 'jet-popup' ),
				'edit_pages',
				'jet-popup-settings',
				[ $this, 'settings_page_render']
			);
		}

		/**
		 * [settings_page_render description]
		 * @return [type] [description]
		 */
		public function settings_page_render() {
			$crate_action = add_query_arg(
				array(
					'action' => 'jet_popup_save_settings',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			require jet_popup()->plugin_path( 'templates/vue-templates/settings-page.php' );
		}

		/**
		 * [enqueue_admin_settings_assets description]
		 * @return [type] [description]
		 */
		public function enqueue_admin_settings_assets() {
			$screen = get_current_screen();

			if ( $screen->id == jet_popup()->post_type->slug() . '_page_jet-popup-settings' ) {
				wp_enqueue_style(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/css/jet-popup-admin.css' ),
					[ 'jet-iview' ],
					jet_popup()->get_version()
				);

				wp_enqueue_script(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/js/jet-popup-admin' . $this->suffix() . '.js' ),
					[
						'jquery',
						'jet-popup-vue',
						'jet-axios',
						'jet-iview',
					],
					jet_popup()->get_version(),
					true
				);

				$localize_data = apply_filters( 'jet-popup/admin/settings-page/localized-data', $this->localize_data );

				wp_localize_script(
					'jet-popup-admin',
					'jetPopupAdminData',
					$localize_data
				);
			}
		}

		/**
		 * [render_vue_template description]
		 * @return [type] [description]
		 */
		public function render_vue_template() {

			$vue_templates = [
				'settings-form',
				'mailchimp-list-item',
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
		 * [save_settings description]
		 * @return [type] [description]
		 */
		public function save_settings() {

			$data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

			if ( ! $data ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, try again later', 'jet-popup' ),
				] );
			}

			$current = get_option( $this->key, [] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			wp_send_json( [
				'type'  => 'success',
				'title' => __( 'Success', 'jet-popup' ),
				'desc'  => __( 'Settings have been saved!', 'jet-popup' ),
			] );
		}

		/**
		 * [get_mailchimp_lists description]
		 * @return [type] [description]
		 */
		public function get_mailchimp_user_data() {

			if ( empty( $_POST['apikey'] ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, try again later', 'jet-popup' ),
				] );
			}

			$api_key = $_POST['apikey'];

			$key_data = explode( '-', $api_key );

			$api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

			$url = esc_url( trailingslashit( $api_server ) );

			$request = wp_remote_post( $url, [
				'method'      => 'GET',
				'timeout'     => 20,
				'headers'     => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'apikey ' . $api_key
				],
			] );

			if ( is_wp_error( $request ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'MailChimp Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, check your apikey status or format', 'jet-popup' ),
				] );
			}

			$request = json_decode( wp_remote_retrieve_body( $request ), true );

			$current = get_option( $this->key . '_mailchimp', [] );

			$current[ $api_key ]['account'] = $request;

			update_option( $this->key . '_mailchimp', $current );

			wp_send_json( [
				'type'     => 'success',
				'title'    => __( 'Success', 'jet-popup' ),
				'desc'     => __( 'Account Data were received', 'jet-popup' ),
				'request'  => $request,
			] );
		}

		/**
		 * [get_mailchimp_lists description]
		 * @return [type] [description]
		 */
		public function get_mailchimp_lists() {

			if ( empty( $_POST['apikey'] ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, try again later', 'jet-popup' ),
				] );
			}

			$api_key = $_POST['apikey'];

			$key_data = explode( '-', $api_key );

			$api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

			$url = esc_url( trailingslashit( $api_server . 'lists' ) );

			$request = wp_remote_post( $url, [
				'method'      => 'GET',
				'timeout'     => 20,
				'headers'     => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'apikey ' . $api_key
				],
			] );

			if ( is_wp_error( $request ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'MailChimp Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, check your apikey status or format', 'jet-popup' ),
				] );
			}

			$request = json_decode( wp_remote_retrieve_body( $request ), true );

			$current = get_option( $this->key . '_mailchimp', [] );

			if ( array_key_exists( 'lists', $request ) ) {
				$lists = $request['lists'];
				$temp_lists = [];

				if ( ! empty( $lists ) ) {
					foreach ( $lists as $key => $list_data ) {
						$temp_lists[ $list_data[ 'id' ] ]['info'] = $list_data;
					}

					$current[ $api_key ]['lists'] = $temp_lists;
				}

				update_option( $this->key . '_mailchimp', $current );
			}

			wp_send_json( [
				'type'     => 'success',
				'title'    => __( 'Success', 'jet-popup' ),
				'desc'     => __( 'Lists were received', 'jet-popup' ),
				'request'  => $request,
			] );
		}

		/**
		 * [get_mailchimp_lists description]
		 * @return [type] [description]
		 */
		public function get_mailchimp_list_merge_fields() {

			if ( empty( $_POST['apikey'] ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, try again later', 'jet-popup' ),
				] );
			}

			$api_key = $_POST['apikey'];

			$key_data = explode( '-', $api_key );

			$list_id = $_POST['listid'];

			$api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

			$url = esc_url( trailingslashit( $api_server . 'lists/' . $list_id . '/merge-fields' ) );

			$request = wp_remote_post( $url, [
				'method'      => 'GET',
				'timeout'     => 20,
				'headers'     => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'apikey ' . $api_key
				],
			] );

			if ( is_wp_error( $request ) ) {
				wp_send_json( [
					'type' => 'error',
					'title' => __( 'MailChimp Error', 'jet-popup' ),
					'desc'  => __( 'Server error. Please, check your apikey status or format', 'jet-popup' ),
				] );
			}

			$request = json_decode( wp_remote_retrieve_body( $request ), true );

			$current = get_option( $this->key . '_mailchimp', [] );

			if ( array_key_exists( 'merge_fields', $request ) ) {
				$current[ $api_key ]['lists'][ $list_id ]['merge_fields'] = $request['merge_fields'];
				update_option( $this->key . '_mailchimp', $current );
			}

			wp_send_json( [
				'type'     => 'success',
				'title'    => __( 'Success', 'jet-popup' ),
				'desc'     => __( 'Merge Fields were received', 'jet-popup' ),
				'request'  => $request,
			] );
		}

		/**
		 * [get_user_lists description]
		 * @return [type] [description]
		 */
		public function get_user_lists() {
			$current = get_option( jet_popup()->settings->key . '_mailchimp', [] );

			$current_api = $this->get( 'apikey', '' );

			if ( empty( $current_api ) || ! array_key_exists( $current_api, $current ) ) {
				return false;
			}

			$apikey_data = $current[ $current_api ];

			if ( ! array_key_exists( 'lists', $apikey_data ) ) {
				return false;
			}

			$lists = $apikey_data['lists'];

			return $lists;
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
		 * [suffix description]
		 * @return [type] [description]
		 */
		public function suffix() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}

		/**
		 * [get_settings_page description]
		 * @return [type] [description]
		 */
		public function get_settings_page_url() {
			return admin_url( 'edit.php?post_type=' . $this->slug() . '&page=' . $this->slug() . '-settings' );
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
