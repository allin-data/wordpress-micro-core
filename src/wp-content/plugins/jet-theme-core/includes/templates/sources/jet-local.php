<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Jet_Theme_Core_Templates_Source_Local extends Jet_Theme_Core_Templates_Source_Base {

	/**
	 * Return source slug.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_slug() {
		return 'jet-local';
	}

	/**
	 * Return source version.
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_version() {
		return '1.0.0';
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_items() {

		$templates = jet_theme_core()->templates_manager->get_templates_list();

		if ( empty( $templates ) ) {
			return array();
		}

		foreach ( $templates as $template ) {

			$template_id = sprintf( '%1$s%2$s', $this->id_prefix(), $template->ID );
			$type        = get_post_meta( $template->ID, '_elementor_template_type', true );

			if ( ! $type ) {
				$type = 'jet_page';
			}

			$structure = jet_theme_core()->structures->get_structure( $type );

			if ( $structure ) {
				$type_label = $structure->get_single_label();
			} else {
				$type_label = '';
			}

			$result[] = array(
				'categories'      => array(),
				'hasPageSettings' => false,
				'keywords'        => array(),
				'source'          => $this->get_slug(),
				'template_id'     => $template_id,
				'thumbnail'       => '',
				'title'           => $template->post_title,
				'preview'         => '',
				'type'            => $type,
				'typeLabel'       => $type_label,
			);
		}

		return $result;
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_categories() {
		return array();
	}

	/**
	 * Return source item list
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_keywords() {
		return array();
	}

	/**
	 * Return single item
	 *
	 * @since 1.0.0
	 * @access public
	*/
	public function get_item( $template_id ) {

		$id      = str_replace( $this->id_prefix(), '', $template_id );
		$db      = Elementor\Plugin::$instance->db;
		$content = $db->get_builder( $id );

		if ( ! empty( $content ) ) {
			$content = $this->replace_elements_ids( $content );
		}

		return array(
			'page_settings' => array(),
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
		return 0;
	}
}