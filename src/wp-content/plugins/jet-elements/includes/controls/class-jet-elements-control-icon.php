<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A Font Icon select box.
 *
 * @property array $icons   A list of font-icon classes. [ 'class-name' => 'nicename', ... ]
 *                          Default Font Awesome icons. @see Control_Icon::get_icons().
 * @property array $include list of classes to include form the $icons property
 * @property array $exclude list of classes to exclude form the $icons property
 *
 * @since 1.0.0
 */
class Jet_Elements_Control_Icon extends Elementor\Control_Icon {

	public function get_type() {
		return 'icon';
	}

	/**
	 * Get icons list
	 *
	 * @return array
	 */
	public static function get_icons_set() {

		$icon_data = jet_elements_tools()->get_theme_icons_data();

		// Prepare icons array
		if ( ! empty( $icon_data['icons'] ) ) {
			$icons = $icon_data['icons'];
		} elseif ( file_exists( $icon_data['file'] ) ) {
			$icons = self::get_icons_from_path( $icon_data['file'] );
		} else {
			return self::get_icons();
		}

		$keys = $icons;
		array_walk( $keys, array( __CLASS__, 'prepare_prefixes' ), $icon_data['format'] );
		$icons = array_combine( $keys, $icons );

		return $icons;
	}

	/**
	 * Prepare keys
	 *
	 * @param  [type] &$item  [description]
	 * @param  [type] $index  [description]
	 * @param  [type] $prefix [description]
	 * @return [type]         [description]
	 */
	public static function prepare_prefixes( &$item, $index, $prefix ) {
		$item = sprintf( $prefix, $item );
	}

	/**
	 * Get default icons list
	 *
	 * @return array
	 */
	public static function get_default_icons() {

		if ( ! defined( 'ELEMENTOR_ASSETS_URL' ) ) {
			return array();
		}

		$fileurl  = ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/font-awesome.min.css';
		$filepath = str_replace( home_url(), ABSPATH, $fileurl );

		if ( ! file_exists( $filepath ) ) {
			return array();
		}

		return self::get_icons_from_path( $filepath );

	}

	/**
	 * Get icons set from path
	 *
	 * @param  string $path Path to icons file.
	 * @return array
	 */
	public static function get_icons_from_path( $path ) {

		$key    = md5( $path );
		$cached = wp_cache_get( $key );

		if ( $cached ) {
			return $cached;
		}

		ob_start();
		include $path;
		$result = ob_get_clean();

		preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $icons );

		if ( ! is_array( $icons ) || empty( $icons[1] ) ) {
			return;
		}

		wp_cache_add( $key, $icons[1] );

		return $icons[1];

	}

	protected function get_default_settings() {
		return [
			'options' => self::get_icons_set(),
		];
	}

}
