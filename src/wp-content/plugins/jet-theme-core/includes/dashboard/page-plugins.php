<?php
/**
 * License page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Dashboard_Plugins' ) ) {

	/**
	 * Define Jet_Theme_Core_Dashboard_Plugins class
	 */
	class Jet_Theme_Core_Dashboard_Plugins extends Jet_Theme_Core_Dashboard_Base {

		public $update_plugins = null;
		public $has_license    = null;
		public $all_plugins    = array();

		/**
		 * License key
		 *
		 * @var string
		 */
		public $license_slug = 'jet_theme_core_license';

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'plugins';
		}

		/**
		 * Get icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'dashicons dashicons-admin-plugins';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_attr__( 'Plugins', 'jet-theme-core' );
		}

		/**
		 * Custom initializtion
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_plugins_assets' ), 0 );
		}

		/**
		 * Atach required hooks
		 *
		 * @return [type] [description]
		 */
		public function init_glob() {

			add_action( 'wp_ajax_jet_core_install_plugin', array( $this, 'install_plugin' ) );
			add_action( 'wp_ajax_jet_core_activate_plugin', array( $this, 'activate_plugin' ) );
			add_action( 'wp_ajax_jet_core_update_plugin', array( $this, 'update_plugin' ) );

		}

		/**
		 * Force check for plugins updates
		 *
		 * @return void
		 */
		public function check_updates() {

			if( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			set_site_transient( 'update_plugins', null );

			$redirect = $this->get_current_page_link();

			wp_safe_redirect( $redirect );
			die();

		}

		/**
		 * Clear templates Jet API cache
		 *
		 * @return void
		 */
		public function sync_library() {

			$api_source = jet_theme_core()->templates_manager->get_source( 'jet-api' );
			$redirect   = $this->get_current_page_link();

			if ( ! $api_source ) {
				wp_safe_redirect( $redirect );
				die();
			}

			$api_source->delete_templates_cache();
			$api_source->delete_categories_cache();
			$api_source->delete_keywords_cache();

			wp_safe_redirect( $redirect );
			die();

		}

		/**
		 * Check for new plugins
		 *
		 * @return void
		 */
		public function check_for_plugins() {

			if( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			set_transient( 'jet_plugins_list', null );

			$redirect = $this->get_current_page_link();

			wp_safe_redirect( $redirect );
			die();

		}

		/**
		 * Install plugin callback
		 *
		 * @return void
		 */
		public function install_plugin() {
			$plugin = isset( $_REQUEST['plugin'] ) ? esc_attr( $_REQUEST['plugin'] ) : false;
			Jet_Theme_Core_Utils::install_plugin( $plugin );
		}

		/**
		 * Plugin activation handler
		 *
		 * @return void
		 */
		public function activate_plugin() {
			$plugin = isset( $_REQUEST['plugin'] ) ? esc_attr( $_REQUEST['plugin'] ) : false;
			Jet_Theme_Core_Utils::activate_plugin( $plugin );
		}

		public function update_plugin() {

			if ( empty( $_REQUEST['plugin'] ) ) {
				wp_send_json_error( array(
					'slug'         => '',
					'errorCode'    => 'no_plugin_specified',
					'errorMessage' => __( 'No plugin specified.', 'jet-theme-core' ),
				) );
			}

			$plugin = plugin_basename( sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) );
			$slug   = dirname( $plugin );

			$status = array(
				'update'     => 'plugin',
				'slug'       => $slug,
				'oldVersion' => '',
				'newVersion' => '',
			);

			if ( ! current_user_can( 'update_plugins' ) || 0 !== validate_file( $plugin ) ) {
				$status['errorMessage'] = __( 'Sorry, you are not allowed to update plugins for this site.', 'jet-theme-core' );
				wp_send_json_error( $status );
			}

			$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$status['plugin']     = $plugin;
			$status['pluginName'] = $plugin_data['Name'];

			if ( $plugin_data['Version'] ) {
				/* translators: %s: Plugin version */
				$status['oldVersion'] = sprintf( __( 'Version %s', 'jet-theme-core' ), $plugin_data['Version'] );
			}

			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

			wp_update_plugins();

			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );
			$result   = $upgrader->bulk_upgrade( array( $plugin ) );

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$status['debug'] = $skin->get_upgrade_messages();
			}

			if ( is_wp_error( $skin->result ) ) {
				$status['errorCode']    = $skin->result->get_error_code();
				$status['errorMessage'] = $skin->result->get_error_message();
				wp_send_json_error( $status );
			} elseif ( $skin->get_errors()->get_error_code() ) {
				$status['errorMessage'] = $skin->get_error_messages();
				wp_send_json_error( $status );
			} elseif ( is_array( $result ) && ! empty( $result[ $plugin ] ) ) {
				$plugin_update_data = current( $result );

				/*
				 * If the `update_plugins` site transient is empty (e.g. when you update
				 * two plugins in quick succession before the transient repopulates),
				 * this may be the return.
				 *
				 * Preferably something can be done to ensure `update_plugins` isn't empty.
				 * For now, surface some sort of error here.
				 */
				if ( true === $plugin_update_data ) {
					$status['errorMessage'] = __( 'Plugin update failed.', 'jet-theme-core' );
					wp_send_json_error( $status );
				}

				$plugin_data = get_plugins( '/' . $result[ $plugin ]['destination_name'] );
				$plugin_data = reset( $plugin_data );

				if ( $plugin_data['Version'] ) {
					$status['newVersion'] = $plugin_data['Version'];
				}
				wp_send_json_success( $status );
			} elseif ( false === $result ) {
				global $wp_filesystem;

				$status['errorCode']    = 'unable_to_connect_to_filesystem';
				$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'jet-theme-core' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
					$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				wp_send_json_error( $status );
			}

			// An unhandled error occurred.
			$status['errorMessage'] = __( 'Plugin update failed.', 'jet-theme-core' );
			wp_send_json_error( $status );

		}

		/**
		 * LLicense assets
		 *
		 * @return [type] [description]
		 */
		public function enqueue_plugins_assets() {

			wp_enqueue_script(
				'jet-theme-core-plugins',
				jet_theme_core()->plugin_url( 'assets/js/plugins.js' ),
				array( 'jquery' ),
				jet_theme_core()->get_version(),
				true
			);

			wp_localize_script( 'jet-theme-core-plugins', 'JetPluginsData', array(
				'installing' => __( 'Installing...', 'jet-theme-core' ),
				'activate'   => __( 'Activate', 'jet-theme-core' ),
				'activating' => __( 'Activating...', 'jet-theme-core' ),
				'activated'  => __( 'Activated', 'jet-theme-core' ),
				'updating'   => __( 'Updating...', 'jet-theme-core' ),
				'updated'    => __( 'Updated', 'jet-theme-core' ),
				'failed'     => __( 'Failed', 'jet-theme-core' ),
			) );

		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {

			$this->prepare_data();

			$this->render_actions();
			$this->render_core_block();
			$this->render_jet_plugins_block();

		}

		public function prepare_data() {

			$all_plugins          = apply_filters( 'all_plugins', get_plugins() );
			$this->update_plugins = get_site_transient( 'update_plugins' );
			$this->all_plugins    = $all_plugins;

		}

		/**
		 * Render plugins actions
		 * @return [type] [description]
		 */
		public function render_actions() {

			echo '<div class="jet-plugins-actions">';

			printf(
				'<a href="%2$s" class="cx-button cx-button-success-style">%1$s</a>',
				__( 'Check for updates', 'jet-theme-core' ),
				add_query_arg(
					array(
						'jet_action' => $this->get_slug(),
						'handle'     => 'check_updates',
					),
					admin_url( 'admin.php' )
				)
			);

			printf(
				'<a href="%2$s" class="cx-button cx-button-primary-style">%1$s</a>',
				__( 'Check for new plugins', 'jet-theme-core' ),
				add_query_arg(
					array(
						'jet_action' => $this->get_slug(),
						'handle'     => 'check_for_plugins',
					),
					admin_url( 'admin.php' )
				)
			);

			echo '</div>';

		}

		/**
		 * Return synchronize template library URL
		 *
		 * @return [type] [description]
		 */
		public function sync_library_url() {

			return add_query_arg(
				array(
					'jet_action' => $this->get_slug(),
					'handle'     => 'sync_library',
				),
				admin_url( 'admin.php' )
			);

		}

		/**
		 * Render Jet Theme Core plugin block
		 *
		 * @return void
		 */
		public function render_core_block() {

			$logo           = jet_theme_core()->plugin_url( 'assets/img/logo.png' );
			$slug           = jet_theme_core()->plugin_name;
			$plugin         = array( 'slug' => $slug, 'version' => '1.0.0' );
			$latest_version = $this->get_latest_version( $plugin );
			$plugin_data    = $this->all_plugins[ $slug ];
			$has_update     = $this->is_update_available( $plugin, $plugin_data );
			$docs           = jet_theme_core()->config->get( 'documentation' );
			$license        = $this->manager->get( 'license' );
			$license_key    = $license->get_license();
			$logo          .= '?ver=' . jet_theme_core()->get_version();

			include jet_theme_core()->get_template( 'dashboard/plugins/core.php' );

		}

		/**
		 * Render Jet plugins block.
		 *
		 * @return void
		 */
		public function render_jet_plugins_block() {

			$plugins = get_transient( 'jet_plugins_list' );

			if ( ! $plugins ) {
				$api_url  = jet_theme_core()->api->api_url( 'plugins' );
				$response = wp_remote_get( $api_url, jet_theme_core()->api->request_args() );
				$body     = wp_remote_retrieve_body( $response );
				$body     = json_decode( $body, true );

				if ( empty( $body ) ) {
					return;
				}

				if ( ! isset( $body['success'] ) || ! $body['success'] ) {
					return;
				}

				$plugins = $body['plugins'];
				set_transient( 'jet_plugins_list', $plugins, 12 * HOUR_IN_SECONDS );
			}

			$active_plugins = get_option( 'active_plugins', array() );

			foreach ( $plugins as $plugin ) {

				$slug        = isset( $plugin['slug'] ) ? $plugin['slug'] : '';
				$is_active   = in_array( $slug, $active_plugins );
				$name        = isset( $plugin['name'] ) ? $plugin['name'] : '';
				$thumb       = isset( $plugin['thumb'] ) ? $plugin['thumb'] : '';
				$docs        = isset( $plugin['docs'] ) ? $plugin['docs'] : '';
				$plugin_data = isset( $this->all_plugins[ $slug ] ) ? $this->all_plugins[ $slug ] : array();

				include jet_theme_core()->get_template( 'dashboard/plugins/plugin.php' );

			}

		}

		/**
		 * Get latest version for passed plugin
		 *
		 * @param  [type] $remote_plugin_data [description]
		 * @return [type]                     [description]
		 */
		public function get_latest_version( $plugin ) {

			if ( ! $this->update_plugins ) {
				$this->update_plugins = get_site_transient( 'update_plugins' );
			}

			$slug      = $plugin['slug'];
			$no_update = $this->update_plugins->no_update;
			$to_update = $this->update_plugins->response;

			if ( ! empty( $to_update ) && array_key_exists( $slug, $to_update ) ) {
				$version = $to_update[ $slug ]->new_version;
			} elseif ( array_key_exists( $slug, $no_update ) ) {
				$version = $no_update[ $slug ]->new_version;
			} elseif ( array_key_exists( $slug, $this->all_plugins ) ) {
				$version = $this->all_plugins[ $slug ]['Version'];
			} else {
				$version = $plugin['version'];
			}

			return $version;

		}

		/**
		 * Check if update is available for curren plugin
		 *
		 * @return boolean [description]
		 */
		public function is_update_available( $plugin, $local_plugin_data ) {

			$user_version   = $local_plugin_data['Version'];
			$actual_version = $this->get_latest_version( $plugin );

			return version_compare( $actual_version, $user_version, '>' );
		}

		/**
		 * Install plugin link
		 *
		 * @return [type] [description]
		 */
		public function install_plugin_link( $slug = '' ) {

			$license = $this->manager->get( 'license' );

			if ( null === $this->has_license ) {
				$this->has_license = $license->get_license();
			}

			if ( empty( $this->has_license ) ) {

				printf(
					'<a class="jet-plugin__actions-link" href="%1$s">%2$s</a>',
					$license->get_current_page_link(),
					__( 'Activate License to Install', 'jet-theme-core' )
				);

				return;
			}

			printf(
				'<a data-action="install" data-plugin="%1$s" class="jet-plugin__actions-link" href="#">%2$s</a>',
				$slug,
				__( 'Install', 'jet-theme-core' )
			);

		}

	}

}
