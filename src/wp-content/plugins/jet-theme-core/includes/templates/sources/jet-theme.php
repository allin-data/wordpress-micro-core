<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Jet_Theme_Core_Templates_Source_Theme extends Jet_Theme_Core_Templates_Source_Base {

	private $_object_cache = array();

	/**
	 * Return source slug.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_slug() {
		return 'jet-theme';
	}

	/**
	 * Return source version.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_version() {
		$library = jet_theme_core()->config->get( 'library' );
		return isset( $library['version'] ) ? $library['version'] : '1.0.0';
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_items( $tab = null ) {

		if ( ! $tab ) {
			return array();
		}

		$items = $this->get_templates_cache();

		if ( ! empty( $items[ $tab ] ) ) {
			return array_values( $items[ $tab ] );
		}

		$result = $this->prepare_items_tab( $tab );

		return isset( $result['templates'] ) ? array_values( $result['templates'] ) : array();
	}

	/**
	 * Prepare items tab
	 * @return [type] [description]
	 */
	public function prepare_items_tab( $tab = '' ) {

		if ( ! empty( $this->_object_cache[ $tab ] ) ) {
			return $this->_object_cache[ $tab ];
		}

		$library_config = jet_theme_core()->config->get( 'library' );
		$templates_path = isset( $library_config['tabs'][ $tab ] ) ? $library_config['tabs'][ $tab ] : false;

		if ( ! $templates_path ) {
			return false;
		}

		$result = array(
			'templates'  => array(),
			'categories' => array(),
			'keywords'   => array(),
		);

		foreach ( glob( trailingslashit( $templates_path ) . '*' ) as $category ) {

			$slug = basename( $category );

			$data = get_file_data(
				trailingslashit( $category ) . 'readme.txt',
				array( 'category-name' => 'Category Name' )
			);

			$result['categories'][ $slug ] = array(
				'slug'  => $slug,
				'title' => $data['category-name']
			);

			foreach ( glob( trailingslashit( $category ) . '*', GLOB_ONLYDIR ) as $template ) {

				$template_slug = basename( $template );
				$template_url  = trailingslashit( str_replace( ABSPATH, home_url( '/' ), $template ) );
				$template_data = get_file_data(
					trailingslashit( $template ) . 'readme.txt',
					array(
						'title'    => 'Template Name',
						'keywords' => 'Keywords',
					)
				);

				if ( file_exists( $template . '/thumb.png' ) ) {
					$thumb = $template_url . 'thumb.png';
				} else {
					$thumb = $template_url . 'thumb.jpg';
				}

				$preview = '';

				if ( file_exists( $template . '/preview.png' ) ) {
					$preview = $template_url . 'preview.png';
				}

				if ( file_exists( $template . '/preview.jpg' ) ) {
					$preview = $template_url . 'preview.jpg';
				}

				$template_id = sprintf( '%1$s%2$s/%3$s', $this->id_prefix(), $slug, $template_slug );

				$result['templates'][ $template_slug ] = array(
					'categories'      => array( $slug ),
					'hasPageSettings' => false,
					'keywords'        => array(),
					'source'          => $this->get_slug(),
					'template_id'     => $template_id,
					'thumbnail'       => $thumb,
					'title'           => $template_data['title'],
					'preview'         => $preview,
					'type'            => $tab,
					'keywords'        => $this->get_keywords_from_string( $template_data['keywords'] ),
				);
			}

		}

		$config             = jet_theme_core()->config->get( 'library' );
		$keywords           = isset( $config['keywords'] ) ? $config['keywords'] : array();
		$result['keywords'] = $keywords;

		$templates_cache  = $this->get_templates_cache();
		$categories_cache = $this->get_categories_cache();
		$keywords_cache   = $this->get_keywords_cache();

		if ( empty( $templates_cache ) ) {
			$templates_cache = array();
		}

		if ( empty( $categories_cache ) ) {
			$categories_cache = array();
		}

		if ( empty( $keywords_cache ) ) {
			$keywords_cache = array();
		}

		$templates_cache[ $tab ]  = $result['templates'];
		$categories_cache[ $tab ] = $result['categories'];
		$keywords_cache[ $tab ]   = $result['keywords'];

		$this->set_templates_cache( $templates_cache );
		$this->set_categories_cache( $categories_cache );
		$this->set_keywords_cache( $keywords_cache );

		$this->_object_cache[ $tab ] = $result;

		return $result;
	}

	/**
	 * Get keyword array from string
	 *
	 * @return [type] [description]
	 */
	public function get_keywords_from_string( $string ) {

		if ( empty( $string ) ) {
			return $string;
		}

		$string = str_replace( ' ', '', $string );
		$array  = explode( ',', $string );

		if ( empty( $array ) ) {
			return array();
		}

		return $array;
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_categories( $tab = '' ) {

		if ( ! $tab ) {
			return array();
		}

		$categories = $this->get_categories_cache();

		if ( ! empty( $categories[ $tab ] ) ) {
			return array_values( $categories[ $tab ] );
		}

		$result = $this->prepare_items_tab( $tab );

		return isset( $result['categories'] ) ? array_values( $result['categories'] ) : array();
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_keywords( $tab = '' ) {

		if ( ! $tab ) {
			return array();
		}

		$keywords = $this->get_keywords_cache();

		if ( ! empty( $keywords[ $tab ] ) ) {
			return $keywords[ $tab ];
		}

		$result = $this->prepare_items_tab( $tab );

		return isset( $result['keywords'] ) ? $result['keywords'] : array();
	}

	/**
	 * Return single item
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_item( $template_id, $tab = false ) {

		$id = str_replace( $this->id_prefix(), '', $template_id );

		if ( ! $tab ) {
			$tab = isset( $_REQUEST['tab'] ) ? esc_attr( $_REQUEST['tab'] ) : false;
		}

		if ( ! $tab ) {
			return array();
		}

		$library_config = jet_theme_core()->config->get( 'library' );
		$templates_path = isset( $library_config['tabs'][ $tab ] ) ? $library_config['tabs'][ $tab ] : false;

		if ( ! $templates_path ) {
			return array();
		}

		$template_dir  = trailingslashit( $templates_path ) . $id;
		$template_file = trailingslashit( $template_dir ) . 'template.json';

		if ( ! file_exists( $template_file ) ) {
			return array();
		}

		ob_start();
		include $template_file;
		$content = ob_get_clean();
		$content = json_decode( $content, true );

		if ( ! empty( $content ) ) {
			$content = $this->replace_elements_ids( $content );
			$content = $this->process_export_import_content( $content, 'on_import' );
		}

		return array(
			'page_settings' => array(),
			'type'          => $tab,
			'content'       => $content,
		);
	}

	/**
	 * Return transien lifetime
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function transient_lifetime() {
		return WEEK_IN_SECONDS;
	}
}
