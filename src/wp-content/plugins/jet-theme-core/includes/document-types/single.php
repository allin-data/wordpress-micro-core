<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Single_Document extends Jet_Document_Base {

	public function get_name() {
		return 'jet_single';
	}

	public static function get_title() {
		return __( 'Single', 'jet-theme-core' );
	}

	public function get_preview_as_query_args() {

		$post_type = $this->get_settings( 'preview_post_type' );
		$post_id   = $this->get_settings( 'preview_post_id' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		$args = array(
			'post_type'           => $post_type,
			'numberposts'         => 1,
			'ignore_sticky_posts' => true,
		);

		if ( ! empty( $post_id ) ) {

			$pid = is_array( $post_id ) ? $post_id[0] : $post_id;

			if ( get_post_type( $pid ) === $post_type ) {
				unset( $args['numberposts'] );
				$args['p'] = absint( $pid );
			}

		}

		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {
			return array(
				'p'         => $posts[0]->ID,
				'post_type' => $post_type,
			);
		} else {
			return false;
		}

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
			'preview_post_id',
			array(
				'label'        => __( 'Select Post', 'jet-theme-core' ),
				'type'         => 'jet_search',
				'action'       => 'jet_theme_search_posts',
				'query_params' => array( 'preview_post_type' ),
				'label_block'  => true,
				'multiple'     => true,
				'saved'        => $this->get_preview_post_id_for_settings(),
				'description'  => __( 'Please remove selected post after changing preview post type', 'jet-theme-core' ),
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

	/**
	 * [get_preview_post_id_for_settings description]
	 * @return [type] [description]
	 */
	public function get_preview_post_id_for_settings() {

		$settings  = $this->get_main_meta( '_elementor_page_settings' );
		$post_type = ! empty( $settings['preview_post_type'] ) ? $settings['preview_post_type'] : 'post';

		if ( ! empty( $settings['preview_post_id'] ) ) {

			$pid = is_array( $settings['preview_post_id'] ) ? $settings['preview_post_id'] : array( $settings['preview_post_id'] );

			$posts = get_posts( array(
				'post_type'           => $post_type,
				'post__in'            => $pid,
				'ignore_sticky_posts' => true,
			) );

			if ( empty( $posts ) ) {
				return array();
			} else {
				return wp_list_pluck( $posts, 'post_title', 'ID' );
			}

		} else {
			return array();
		}

	}

}
