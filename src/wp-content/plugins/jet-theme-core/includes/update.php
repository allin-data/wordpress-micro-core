<?php
/**
 * Plugin update class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Update' ) ) {

	/**
	 * Define Jet_Theme_Core_Update class
	 */
	class Jet_Theme_Core_Update {

		/**
		 * Plugin name in 'dir-name/filename.php' format
		 *
		 * @var sting
		 */
		public $plugin_name = null;

		/**
		 * Plugin slug in 'filename' format
		 *
		 * @var sting
		 */
		public $plugin_slug = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->plugin_name = jet_theme_core()->plugin_name;
			$this->plugin_slug = basename( $this->plugin_name, '.php' );

			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_plugin_update' ), 50 );
			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_theme_update' ), 50 );

		}

		public function package_url( $key ) {
			return add_query_arg(
				array(
					'license'       => $key,
					'url'           => urlencode( home_url( '/' ) ),
					'ct_api_action' => 'get_core_package',
				),
				jet_theme_core()->api->api_base()
			);
		}

		/**
		 * Check theme updates
		 *
		 * @param  array $data
		 * @return array
		 */
		public function check_theme_update( $data ) {

			$theme = jet_theme_core()->dashboard->get( 'theme' );

			if ( ! $theme ) {
				return $data;
			}

			$theme_data = $theme->get_remote_data();

			if ( ! $theme_data['theme_version'] ) {
				return $data;
			}

			$theme_status = $theme->get_theme_status( $theme_data['theme_slug'] );

			if ( ! $theme_status['version'] ) {
				return $data;
			}

			if ( ! version_compare( $theme_data['theme_version'], $theme_status['version'], '>' ) ) {
				return $data;
			}

			$update = array();

			$update['theme']       = $theme_data['theme_slug'];
			$update['new_version'] = $theme_data['theme_version'];
			$update['url']         = '';
			$update['package']     = $theme_data['theme_path'];

			$data->response[ $theme_data['theme_slug'] ] = $update;

			return $data;
		}

		/**
		 * Check Core updates
		 *
		 * @return array
		 */
		public function check_plugin_update( $data ) {

			$remote_version = jet_theme_core()->api->get_info( 'core_version' );

			if ( ! $remote_version ) {
				return $data;
			}

			if ( ! version_compare( $remote_version, jet_theme_core()->get_version(), '>' ) ) {
				return $data;
			}

			$license = jet_theme_core()->dashboard->get( 'license' );
			$key     = $license->get_license();
			$package = '';

			if ( $key ) {
				$status = jet_theme_core()->api->license_status( $key );
				if ( true === $status['success'] ) {
					$package = $this->package_url( $key );
				}
			}

			$update = new stdClass();

			$update->slug        = $this->plugin_slug;
			$update->plugin      = $this->plugin_name;
			$update->new_version = $remote_version;
			$update->url         = false;
			$update->package     = $package;

			$data->response[ $this->plugin_name ] = $update;

			return $data;
		}

	}

}
