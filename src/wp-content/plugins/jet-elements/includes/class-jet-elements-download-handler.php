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

if ( ! class_exists( 'Jet_Elements_Download_Handler' ) ) {

	/**
	 * Define Jet_Elements_Download_Handler class
	 */
	class Jet_Elements_Download_Handler {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Dwonload hook
		 *
		 * @var string
		 */
		private $hook = 'jet_download';

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'init', array( $this, 'process_download' ), 99 );
		}

		/**
		 * Returns download hook name
		 *
		 * @return string
		 */
		public function hook() {
			return $this->hook;
		}

		/**
		 * Get download link for passed ID.
		 *
		 * @param  integer $id Media post ID.
		 * @return string
		 */
		public function get_download_link( $id = 0 ) {
			return add_query_arg(
				array( $this->hook() => absint( $id ) ),
				esc_url( home_url( '/' ) )
			);
		}

		/**
		 * Get file size by attachment ID
		 * @param  integer $id [description]
		 * @return [type]      [description]
		 */
		public function get_file_size( $id = 0 ) {

			$file_path = get_attached_file( $id );

			if ( ! $file_path ) {
				return;
			}

			$file_size = filesize( $file_path );

			return size_format( $file_size );
		}

		/**
		 * Check if is download request and handle it.
		 */
		public function process_download() {

			if ( empty( $_GET[ $this->hook() ] ) ) {
				return;
			}

			$id = absint( $_GET[ $this->hook() ] );

			if ( ! $id ) {
				return;
			}

			$post = get_post( $id );

			if ( 'attachment' !== $post->post_type ) {
				return;
			}

			$file_path = get_attached_file( $id );

			if ( ! is_file( $file_path ) ) {
				return;
			}

			if ( ini_get( 'zlib.output_compression' ) ) {
				ini_set('zlib.output_compression', 'Off');
			}

			// get the file mime type using the file extension
			switch( strtolower( substr( strrchr( $file_path, '.' ), 1 ) ) ) {
				case 'pdf':
					$mime = 'application/pdf';
					break;
				case 'zip':
					$mime = 'application/zip';
					break;
				case 'jpeg':
				case 'jpg':
					$mime = 'image/jpg';
					break;
				default:
					$mime = 'application/force-download';
					break;
			}

			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', @filemtime( $file_path ) ) . ' GMT' );
			header( 'Cache-Control: private', false );
			header( 'Content-Type: ' . $mime );
			header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Length: ' . @filesize( $file_path ) );
			header( 'Connection: close' );

			$this->readfile_chunked( $file_path );

			die();

		}

		/**
		 * Process chuncked download
		 *
		 * @param  string $file     Filepath
		 * @param  bool   $retbytes
		 * @return mixed
		 */
		public function readfile_chunked( $filename, $retbytes = true ) {

			$chunksize = 1 * ( 1024 * 1024 );
			$buffer    = '';
			$cnt       = 0;
			$handle    = fopen( $filename, 'rb' );

			if ( false === $handle ) {
				return false;
			}

			while ( ! feof( $handle ) ) {
				$buffer = fread( $handle, $chunksize );
				echo $buffer;
				ob_flush();
				flush();
				if ( $retbytes ) {
					$cnt += strlen( $buffer );
				}
			}

			$status = fclose($handle);

			if ( $retbytes && $status ) {
				return $cnt; // return num. bytes delivered like readfile() does.
			}

			return $status;
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
 * Returns instance of Jet_Elements_Download_Handler
 *
 * @return object
 */
function jet_elements_download_handler() {
	return Jet_Elements_Download_Handler::get_instance();
}
