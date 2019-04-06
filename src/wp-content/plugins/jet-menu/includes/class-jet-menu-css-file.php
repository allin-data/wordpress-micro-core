<?php
/**
 * CSS files manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Menu_CSS_File' ) ) {

	/**
	 * Define Jet_Menu_CSS_File class
	 */
	class Jet_Menu_CSS_File {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		private $menu_dir        = null;
		private $menu_url        = null;
		private $enqueued        = array();
		private $presets_to_save = array();

		/**
		 * Constructor for the class
		 */
		public function init() {

			$enbaled = jet_menu_option_page()->get_option( 'jet-menu-cache-css', 'true' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_menu_css' ) );

			add_action( 'jet-menu/options-page/save',  array( $this, 'remove_css_file' ) );
			add_action( 'jet-menu/presets/created',    array( $this, 'remove_css_file' ) );
			add_action( 'jet-menu/presets/updated',    array( $this, 'remove_css_file' ) );
			add_action( 'jet-menu/presets/loaded',     array( $this, 'remove_css_file' ) );
			add_action( 'jet-menu/presets/deleted',    array( $this, 'remove_css_file' ) );
			add_action( 'jet-menu/item-settings/save', array( $this, 'remove_css_file' ) );

			add_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'maybe_create_css_file' ) );
		}

		/**
		 * Maybe create menu CSS file
		 *
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public function maybe_create_css_file( $data ) {

			foreach ( $this->presets_to_save as $preset ) {

				if ( ! empty( $data['css'] ) && ! $this->menu_css_exists( $preset ) ) {
					file_put_contents( $this->menu_css_path( $preset ), htmlspecialchars_decode( $data['css'] ) );
				}

			}

			return $data;

		}

		public function add_preset_to_save( $preset = 0 ) {

			if ( ! $preset ) {
				$preset = 'general';
			}

			if ( ! in_array( $preset, $this->presets_to_save ) ) {
				$this->presets_to_save[] = $preset;
			}

		}

		/**
		 * Remove CSS file on options save
		 *
		 * @return [type] [description]
		 */
		public function remove_css_file() {

			foreach ( glob( $this->menu_dir() . '*.css' ) as $file ) {

				$slug   = basename( $file, '.css' );
				$preset = str_replace( 'jet-menu-', '', $slug );

				if ( $this->menu_css_exists( $preset ) ) {
					unlink( $this->menu_css_path( $preset ) );
				}

			}

		}

		/**
		 * Enqueue menu CSS
		 * @return [type] [description]
		 */
		public function enqueue_menu_css() {

			if ( ! $this->ensure_menu_dir() ) {
				return;
			}

			foreach ( glob( $this->menu_dir() . '*.css' ) as $file ) {

				$slug   = basename( $file, '.css' );
				$preset = str_replace( 'jet-menu-', '', $slug );

				if ( $this->menu_css_exists( $preset ) ) {

					wp_enqueue_style(
						$slug,
						$this->menu_css_url( $preset ),
						array(),
						filemtime( $this->menu_css_path( $preset ) )
					);

					$this->enqueued[] = $preset;
				}

			}

		}

		/**
		 * Check if menu CSS file exists
		 *
		 * @return [type] [description]
		 */
		public function menu_css_exists( $preset = 'general' ) {
			return file_exists( $this->menu_css_path( $preset ) );
		}

		/**
		 * Return path to menu CSS file
		 *
		 * @return [type] [description]
		 */
		public function menu_css_path( $preset = 'general' ) {
			return $this->menu_dir() . 'jet-menu-' . $preset . '.css';
		}

		/**
		 * Return url to menu CSS file
		 *
		 * @return [type] [description]
		 */
		public function menu_css_url( $preset = 'general' ) {
			return $this->menu_url() . 'jet-menu-' . $preset . '.css';
		}

		/**
		 * Check if passed preset is already enqueued
		 *
		 * @param  integer|string $preset Preset to check
		 * @return boolean
		 */
		public function is_enqueued( $preset = 0 ) {

			if ( ! $preset ) {
				$preset = 'general';
			}

			return in_array( $preset, $this->enqueued );
		}

		/**
		 * Returns menu CSS directory URL
		 *
		 * @return [type] [description]
		 */
		public function menu_url() {

			if ( null !== $this->menu_url ) {
				return $this->menu_url;
			}

			$upload_dir      = wp_upload_dir();
			$upload_base_dir = $upload_dir['baseurl'];
			$this->menu_url  = trailingslashit( $upload_base_dir ) . 'jet-menu/';

			if ( is_ssl() ) {
				$this->menu_url = set_url_scheme( $this->menu_url );
			}

			return $this->menu_url;

		}

		/**
		 * Returns menu CSS directory path
		 *
		 * @return [type] [description]
		 */
		public function menu_dir() {

			if ( null !== $this->menu_dir ) {
				return $this->menu_dir;
			}

			$upload_dir      = wp_upload_dir();
			$upload_base_dir = $upload_dir['basedir'];
			$this->menu_dir  = trailingslashit( $upload_base_dir ) . 'jet-menu/';

			return $this->menu_dir;

		}

		/**
		 * Ensure that CSS directory exists and try to create if not.
		 *
		 * @return bool
		 */
		public function ensure_menu_dir() {

			if ( file_exists( $this->menu_dir() ) ) {
				return true;
			} else {
				return mkdir( $this->menu_dir() );
			}

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
 * Returns instance of Jet_Menu_CSS_File
 *
 * @return object
 */
function jet_menu_css_file() {
	return Jet_Menu_CSS_File::get_instance();
}
