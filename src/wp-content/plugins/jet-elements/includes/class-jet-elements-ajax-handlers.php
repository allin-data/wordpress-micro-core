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

if ( ! class_exists( 'Jet_Elements_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Elements_Ajax_Handlers class
	 */
	class Jet_Elements_Ajax_Handlers {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * System message.
		 *
		 * @var array
		 */
		public $sys_messages = array();

		/**
		 * MailChimp API server
		 *
		 * @var string
		 */
		private $api_server = 'https://%s.api.mailchimp.com/2.0/';

		/**
		 * Init Handler
		 */
		public function init() {

			$this->sys_messages = apply_filters( 'cherry_popups_sys_messages', array(
				'invalid_mail'      => esc_html__( 'Please, provide valid mail', 'jet-elements' ),
				'mailchimp'         => esc_html__( 'Please, set up MailChimp API key and List ID', 'jet-elements' ),
				'internal'          => esc_html__( 'Internal error. Please, try again later', 'jet-elements' ),
				'server_error'      => esc_html__( 'Server error. Please, try again later', 'jet-elements' ),
				'subscribe_success' => esc_html__( 'E-mail %s has been subscribed', 'jet-elements' ),
			) );

			add_action( 'wp_ajax_jet_subscribe_form_ajax', array( $this, 'jet_subscribe_form_ajax' ) );
			add_action( 'wp_ajax_nopriv_jet_subscribe_form_ajax', array( $this, 'jet_subscribe_form_ajax' ) );
		}

		/**
		 * Proccesing subscribe form ajax
		 *
		 * @return void
		 */
		public function jet_subscribe_form_ajax() {
			$data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

			if ( ! $data ) {
				wp_send_json_error( array( 'type' => 'error', 'message' => $this->sys_messages['server_error'] ) );
			}

			$api_key = jet_elements_settings()->get( 'mailchimp-api-key' );

			if ( ! $api_key ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
			}

			$list_id = jet_elements_settings()->get( 'mailchimp-list-id', '' );

			if ( isset( $data['use_target_list_id'] ) &&
				filter_var( $data['use_target_list_id'], FILTER_VALIDATE_BOOLEAN ) &&
				! empty( $data['target_list_id'] )
			) {
				$list_id = $data['target_list_id'];
			}

			if ( ! $list_id ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
			}

			$mail = $data['email'];

			if ( empty( $mail ) || ! is_email( $mail ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['invalid_mail'] ) );
			}

			$double_opt_in = filter_var( jet_elements_settings()->get( 'mailchimp-double-opt-in' ), FILTER_VALIDATE_BOOLEAN );

			$args = [
				'email_address' => $mail,
				'status'        => $double_opt_in ? 'pending' : 'subscribed',
			];

			if ( ! empty( $data['additional'] ) ) {

				$additional = $data['additional'];

				foreach ( $additional as $key => $value ) {
					$merge_fields[ strtoupper( $key ) ] = $value;
				}

				$args['merge_fields'] = $merge_fields;

			}

			$response = $this->api_call( $api_key, $list_id, $args );

			if ( false === $response ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
			}

			$response = json_decode( $response, true );

			if ( empty( $response ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['internal'] ) );
			}

			if ( isset( $response['status'] ) && 400 == $response['status'] ) {
				wp_send_json( array( 'type' => 'error', 'message' => esc_html( $response['detail'] ) ) );
			}

			$subscribe_success = sprintf( $this->sys_messages['subscribe_success'], $response['email_address'] );

			wp_send_json( array( 'type' => 'success', 'message' => $subscribe_success ) );
		}

		/**
		 * Make remote request to mailchimp API
		 *
		 * @param  string $method API method to call.
		 * @param  array  $args   API call arguments.
		 * @return array|bool
		 */
		public function api_call( $api_key, $list_id, $args = [] ) {

			$key_data = explode( '-', $api_key );

			if ( empty( $key_data ) || ! isset( $key_data[1] ) ) {
				return false;
			}

			$this->api_server = sprintf( 'https://%s.api.mailchimp.com/3.0/', $key_data[1] );

			$url = esc_url( trailingslashit( $this->api_server . 'lists/' . $list_id . '/members/' ) );

			$data = json_encode( $args );

			$request_args = [
				'method'      => 'POST',
				'timeout'     => 20,
				'headers'     => [
					'Content-Type'  => 'application/json',
					'Authorization' => 'apikey ' . $api_key
				],
				'body'        => $data,
			];

			$request = wp_remote_post( $url, $request_args );

			return wp_remote_retrieve_body( $request );
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

/**
 * Returns instance of Jet_Elements_Ajax_Handlers
 *
 * @return object
 */
function jet_elements_ajax_handlers() {
	return Jet_Elements_Ajax_Handlers::get_instance();
}

jet_elements_ajax_handlers()->init();
