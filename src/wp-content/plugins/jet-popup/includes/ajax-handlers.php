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

if ( ! class_exists( 'Jet_Popup_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Popup_Admin_Ajax_Handlers class
	 */
	class Jet_Popup_Ajax_Handlers {

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
		public $sys_messages = [];

		/**
		 * MailChimp API server
		 *
		 * @var string
		 */
		private $api_server = 'https://%s.api.mailchimp.com/3.0/';

		/**
		 * Init Handler
		 */
		public function __construct() {

			$this->sys_messages = [
				'invalid_mail'      => esc_html__( 'Please, provide valid mail', 'jet-popup' ),
				'mailchimp'         => esc_html__( 'Please, set up MailChimp API key and List ID', 'jet-popup' ),
				'internal'          => esc_html__( 'Internal error. Please, try again later', 'jet-popup' ),
				'server_error'      => esc_html__( 'Server error. Please, try again later', 'jet-popup' ),
				'subscribe_success' => esc_html__( 'E-mail %s has been subscribed', 'jet-popup' ),
				'no_data'           => esc_html__( 'No Data Found', 'jet-popup' ),
			];

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				add_action( 'wp_ajax_jet_popup_mailchimp_ajax', [ $this, 'jet_popup_mailchimp_ajax' ] );
				add_action( 'wp_ajax_nopriv_jet_popup_mailchimp_ajax', [ $this, 'jet_popup_mailchimp_ajax' ] );

				add_action( 'wp_ajax_jet_popup_get_content', [ $this, 'jet_popup_get_content' ] );
				add_action( 'wp_ajax_nopriv_jet_popup_get_content', [ $this, 'jet_popup_get_content' ] );
			}
		}

		/**
		 * Proccesing subscribe form ajax
		 *
		 * @return void
		 */
		public function jet_popup_mailchimp_ajax() {
			$data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

			if ( ! $data ) {
				wp_send_json_error( array( 'type' => 'error', 'message' => $this->sys_messages['server_error'] ) );
			}

			$api_key = jet_popup()->settings->get( 'apikey' );

			if ( ! $api_key ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
			}

			$list_id = $data['target_list_id'];

			if ( ! $list_id ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['mailchimp'] ) );
			}

			$mail = $data['email'];

			if ( empty( $mail ) || ! is_email( $mail ) ) {
				wp_send_json( array( 'type' => 'error', 'message' => $this->sys_messages['invalid_mail'] ) );
			}

			$double_opt_in = false;

			$user_lists = jet_popup()->settings->get_user_lists();

			if ( array_key_exists( $list_id, $user_lists ) ) {
				$double_opt_in = $user_lists[ $list_id ][ 'info' ]['double_optin'];
			}

			$args = [
				'email_address' => $mail,
				'status'        => $double_opt_in ? 'pending' : 'subscribed',
			];

			if ( ! empty( $data['additional'] ) ) {

				$additional = $data['additional'];

				foreach ( $additional as $key => $value ) {
					$field_key = strtoupper( $key );

					if ( 'BIRTHDAY' == $field_key ) {
						$date = new DateTime( $value );
						$value = $date->format( 'm/d' );
					}

					$merge_fields[ $field_key ] = $value;
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

				$message = esc_html( $response['detail'] );

				if ( is_array( $response['errors'] ) && ! empty( $response['errors'] ) ) {
					foreach ( $response['errors'] as $key => $error ) {
						$message .= sprintf( ' <b>%s</b> %s', $error['field'], $error['message'] );
					}
				}

				wp_send_json( array( 'type' => 'error', 'message' => $message ) );
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
		 * [jet_popup_get_content description]
		 * @return [type] [description]
		 */
		public function jet_popup_get_content() {
			$data = ( ! empty( $_POST['data'] ) ) ? $_POST['data'] : false;

			if ( ! $data ) {
				wp_send_json_error( [ 'type' => 'error', 'message' => $this->sys_messages['server_error'] ] );
			}

			/**
			 * [$data description]
			 * @var [type]
			 */
			$popup_data = apply_filters( 'jet-popup/ajax-request/post-data', $data );

			$content = apply_filters( 'jet-popup/ajax-request/get-elementor-content', false, $popup_data );

			if ( ! $content ) {
				/**
				 * Get Elementor content
				 */
				$content = $this->get_popup_content( $popup_data );
			}

			if ( empty( $content ) ) {
				wp_send_json( [
					'type'    => 'error',
					'message' => $this->sys_messages['no_data']
				] );
			}

			wp_send_json(
				[
					'type'    => 'success',
					'content' => $content,
				]
			);
		}

		/**
		 * [jet_popup_get_content description]
		 * @return [type] [description]
		 */
		public function get_popup_content( $popup_data ) {

			$popup_id = $popup_data['popup_id'];

			if ( empty( $popup_id ) ) {
				return false;
			}

			$plugin = Elementor\Plugin::instance();

			$content = $plugin->frontend->get_builder_content( $popup_id );

			return $content;
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
