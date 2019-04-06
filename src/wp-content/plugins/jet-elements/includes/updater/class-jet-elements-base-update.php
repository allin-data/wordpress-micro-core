<?php
/**
 * Class for the base update.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Base updater class.
 *
 * @since 1.0.0
 */
class Jet_Elements_Base_Update {

	/**
	 * Api parameters.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var array
	 */
	protected $api = array(
		'version' => '',
		'slug'    => '',
		'api_url' => 'http://jetelements.zemez.io/updates/%s.json',
	);

	/**
	 * Init class parameters.
	 *
	 * @since  1.0.0
	 * @param  array $attr Input attributes array.
	 * @return void
	 */
	protected function base_init( $attr = array() ) {
		$this->api = array_merge( $this->api, $attr );
	}

	/**
	 * Check if update are avaliable.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	protected function check_update() {

		$response = $this->remote_query();

		if ( ! $response ) {
			return array( 'version' => false );
		}

		if ( version_compare( $this->api['version'], $response->version, '<' ) ) {
			return array(
				'version' => $response->version,
				'package' => $response->package,
			);
		}

		return array( 'version' => false );
	}

	/**
	 * Remote request to updater API.
	 *
	 * @since  1.0.0
	 * @return array|bool
	 */
	protected function remote_query() {

		$response = wp_remote_get( sprintf( $this->api['api_url'], $this->api['slug'] ) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' ) {
			return false;
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

}
