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

if ( ! class_exists( 'Jet_Blog_Video_Data' ) ) {

	/**
	 * Define Jet_Blog_Video_Data class
	 */
	class Jet_Blog_Video_Data {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Holder for YouTube API key
		 *
		 * @var string
		 */
		private $youtube_api_key = null;

		/**
		 * Youtube API base URL
		 *
		 * @var string
		 */
		private $youtube_base = 'https://www.googleapis.com/youtube/v3/videos';

		/**
		 * Vimeo API base URL
		 *
		 * @var string
		 */
		private $vimeo_base = 'https://vimeo.com/api/v2/video/%1$s.json';

		/**
		 * Current provider
		 *
		 * @var sting
		 */
		private $current_provider = null;

		/**
		 * Initialize hadler
		 *
		 * @return [type] [description]
		 */
		public function init() {
			$this->youtube_api_key = jet_blog_settings()->get( 'youtube_api_key' );
		}

		/**
		 * Get data for passed video.
		 *
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		public function get( $url = '', $caching = true ) {

			$key = $this->transient_key( $url );

			$cached = get_transient( $key );

			if ( $cached && true === $caching ) {
				return $cached;
			}

			$data = $this->fetch_embed_data( $url );
			$data = $this->merge_api_data( $data );

			if ( ! empty( $data ) ) {
				set_transient( $key, $data, 3 * DAY_IN_SECONDS );
			}

			return $data;
		}

		/**
		 * Fetch data from oembed provider
		 *
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		public function fetch_embed_data( $url ) {

			$oembed  = _wp_oembed_get_object();
			$data    = $oembed->get_data( $url );
			$pattern = '/[\'\"](http[s]?:\/\/.*?)[\'\"]/';

			$this->current_provider = $data->provider_name;
			$html = preg_replace_callback( $pattern, array( $this, 'add_embed_args' ), $data->html );
			$this->current_provider = null;

			return array(
				'url'               => $url,
				'title'             => $data->title,
				'video_id'          => $this->get_id_from_html( $html ),
				'provider_name'     => $data->provider_name,
				'html'              => $html,
				'thumbnail_default' => $data->thumbnail_url,
			);
		}

		/**
		 * Add data from main provider API to already fetched data.
		 *
		 * @return array
		 */
		public function merge_api_data( $data ) {

			$id = $data['video_id'];

			if ( ! $id ) {
				return $data;
			}

			$provider = $data['provider_name'];
			$api_data = array();

			switch ( $provider ) {
				case 'YouTube':
					$api_data = $this->get_youtube_data( $id );
					break;

				case 'Vimeo':
					$api_data = $this->get_vimeo_data( $id );
					break;
			}

			return array_merge( $data, $api_data );
		}

		/**
		 * Fetches YouTube specific data
		 *
		 * @return void
		 */
		public function get_youtube_data( $id ) {

			if ( empty( $this->youtube_api_key ) ) {
				return array();
			}

			$response = wp_remote_get( add_query_arg(
				array(
					'id'   => $id,
					'part' => 'contentDetails',
					'key'  => $this->youtube_api_key,
				),
				$this->youtube_base
			) );

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body, true );

			if ( ! isset( $body['items'] ) || ! isset( $body['items'][0]['contentDetails']['duration'] ) ) {
				return array();
			}

			return array(
				'duration' => $this->convert_duration( $body['items'][0]['contentDetails']['duration'] ),
			);
		}

		/**
		 * Fetches Vimeo specific data
		 *
		 * @param  [type] $id [description]
		 * @return [type]     [description]
		 */
		public function get_vimeo_data( $id ) {

			$response = wp_remote_get( sprintf( $this->vimeo_base, $id ) );

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body, true );

			if ( ! isset( $body[0] ) ) {
				return array();
			}

			$result = array(
				'thumbnail_small'  => isset( $body[0]['thumbnail_small'] ) ? $body[0]['thumbnail_small'] : false,
				'thumbnail_medium' => isset( $body[0]['thumbnail_medium'] ) ? $body[0]['thumbnail_medium'] : false,
				'duration'         => isset( $body[0]['duration'] ) ? $body[0]['duration'] : false,
			);

			$result = array_filter( $result );

			if ( ! empty( $result['duration'] ) ) {
				$result['duration'] = $this->convert_duration( $result['duration'] );
			}

			return $result;

		}

		public function convert_duration( $duration ) {

			if ( 0 < absint( $duration ) ) {
				$items = array(
					zeroise( floor( $duration / 60 ), 2 ),
					zeroise( ( $duration % 60 ), 2 ),
				);
			} else {
				$interval = new DateInterval( $duration );
				$items    = array(
					( 0 < $interval->h ) ? zeroise( $interval->h, 2 ) : false,
					( 0 < $interval->i ) ? zeroise( $interval->i, 2 ) : false,
					( 0 < $interval->s ) ? zeroise( $interval->s, 2 ) : false,
				);
			}

			return implode( ':', array_filter( $items ) );
		}

		/**
		 * Find in passed embed string video ID.
		 *
		 * @return [type] [description]
		 */
		public function get_id_from_html( $html ) {
			preg_match( '/http[s]?:\/\/[a-zA-Z0-9\.\/]+(video|embed)\/([a-zA-Z0-9\-_]+)/', $html, $matches );
			return ! empty( $matches[2] ) ? $matches[2] : false;
		}

		/**
		 * Callback to add required argumnets to passed video
		 *
		 * @param [type] $matches [description]
		 */
		public function add_embed_args( $matches ) {

			$args = array();

			switch ( $this->current_provider ) {
				case 'YouTube':
					$args = array(
						'enablejsapi' => 1,
					);
					break;

				case 'Vimeo':
					$args = array(
						'api'    => 1,
						'byline' => 0,
						'title'  => 0,
					);
					break;
			}

			return sprintf( '"%s"', add_query_arg( $args, $matches[1] ) );
		}

		/**
		 * Returns apropriate transient key for passed URL
		 *
		 * @param  string $url [description]
		 * @return [type]      [description]
		 */
		public function transient_key( $url ) {
			return 'video_data_' . jet_blog()->get_version() . md5( $url );
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
 * Returns instance of Jet_Blog_Video_Data
 *
 * @return object
 */
function jet_blog_video_data() {
	return Jet_Blog_Video_Data::get_instance();
}
