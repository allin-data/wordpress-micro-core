<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Archive_Document extends Jet_Document_Base {

	public function get_name() {
		return 'jet_archive';
	}

	public static function get_title() {
		return __( 'Archive', 'jet-theme-core' );
	}

	public function get_preview_as_query_args() {

		$post_type = $this->get_settings( 'preview_post_type' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		return array(
			'post_type'   => $post_type,
			'numberposts' => get_option( 'posts_per_page', 10 ),
		);
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		parent::_register_controls();

		$this->start_controls_section(
			'jet_template_preview',
			array(
				'label' => __( 'Preview', 'jet-theme-core' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'preview_post_type',
			array(
				'label'    => esc_html__( 'Post Type', 'jet-theme-core' ),
				'type'     => Elementor\Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => Jet_Theme_Core_Utils::get_post_types(),
			)
		);

		$this->add_control(
			'preview_notice',
			array(
				'type'      => Elementor\Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'raw'       => __( 'Please reload page after applying preview settings', 'jet-theme-core' ),
			)
		);

		$this->end_controls_section();

	}

}
