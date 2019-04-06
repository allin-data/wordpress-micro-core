<?php
namespace Elementor;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Smartslider3 extends Jet_Elements_Base {

	public function get_name() {
		return 'smartslider3';
	}

	public function get_title() {
		return esc_html__( 'Smart Slider', 'jet-elements' );
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_slider',
			array(
				'label' => esc_html__( 'Slider', 'jet-elements' ),
			)
		);

		$this->add_control(
			'slider',
			array(
				'label'   => esc_html__( 'Select slider', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_sliders()
			)
		);

		$this->add_control(
			'set_key',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( '<strong>Note:</strong> In Editor mode slider will be appended only as preview image to speed up the editing. On frontend slider will be shown as usual slider.', 'jet-elements' ),
			)
		);

		$this->end_controls_section();
	}

	protected function get_sliders() {

		global $wpdb;

		$tablename = $wpdb->prefix . 'nextend2_smartslider3_sliders';
		$results   = $wpdb->get_results( "SELECT * FROM $tablename" );

		if ( empty( $results ) ) {
			return array();
		}

		$slides = wp_list_pluck( $results, 'title', 'id' );
		array_walk( $slides, array( $this, 'map_slides' ) );

		return $slides;
	}

	public function map_slides( &$item, $key ) {
		$item = sprintf( '%s (id:%s)', $item, $key );
	}

	/**
	 * Returns slider module wrapper format
	 */
	protected function get_slider_format() {
		return apply_filters(
			'cherry-elemetor/addons/smartslider3/format',
			'<div class="elementor-smartslider">%s</div>'
		);
	}

	protected function render() {

		$slider = $this->get_settings( 'slider' );

		if ( ! jet_elements_integration()->in_elementor() ) {

			if ( ! $slider ) {
				return;
			}

			printf(
				$this->get_slider_format(),
				do_shortcode( '[smartslider3 slider=' . $slider . ']' )
			);

			return;

		}

		global $wpdb;

		$sliderstable = $wpdb->prefix . 'nextend2_smartslider3_sliders';
		$slidestable  = $wpdb->prefix . 'nextend2_smartslider3_slides';
		$placeholder  = esc_html__( 'Please, select slider to show', 'jet-elements' );
		$format       = '<div class="elementor-smartslider">%s</div>';

		if ( ! $slider ) {
			printf( $format, $placeholder );
			return;
		}

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $sliderstable WHERE id=%d", $slider ) );

		if ( null === $row ) {
			printf( $format, $placeholder );
			return;
		}

		$params = json_decode( $row->params, ARRAY_A );

		if ( 'fullwidth' === $params['responsive-mode'] ) {
			$height = isset( $params['responsiveSliderHeightMax'] )
				? $params['responsiveSliderHeightMax'] . 'px'
				: ( isset( $params['height'] ) ? $params['height'] . 'px' : 'auto' );
		} else {
			$height = isset( $params['height'] ) ? $params['height'] . 'px' : 'auto';
		}

		$slide = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM $slidestable WHERE slider = %d AND ordering = 0", $slider )
		);

		if ( null === $slide ) {
			printf( $format, $placeholder );
			return;
		}

		$slide_params = json_decode( $slide->params, ARRAY_A );

		$image = sprintf(
			'<div style="overflow:hidden;height:%1$s;display:flex;">
				<img style="width: 100%;object-fit: cover;" src="%2$s" alt="">
			</div>',
			$height,
			$this->get_placeholder_image( $slide_params )
		);

		printf( $format, $image );
	}

	public function get_placeholder_image( $slide_params ) {

		$uploads = wp_upload_dir();
		$base    = $uploads['baseurl'];

		if ( ! empty( $slide_params['backgroundImage'] ) ) {
			$img = str_replace( '$upload$', $base, $slide_params['backgroundImage'] );
		} else {
			$img = Utils::get_placeholder_image_src();
		}

		return $img;
	}

	public function render_plain_content() {

		// In plain mode, render without shortcode
		$slider = $this->get_settings( 'slider' );

		printf( '[smartslider3 slider="%s"]', $slider );

	}

	protected function _content_template() {}
}
