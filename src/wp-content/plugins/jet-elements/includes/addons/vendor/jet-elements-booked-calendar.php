<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Booked_Calendar extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-booked-calendar';
	}

	public function get_title() {
		return esc_html__( 'Booked Calendar', 'jet-elements' );
	}

	public function get_icon() {
		return 'eicon-date';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function __tag() {
		return 'booked-calendar';
	}

	public function __atts() {

		return array(
			'calendar' => array(
				'label'   => esc_html__( 'Calendar ID', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'year' => array(
				'label'   => esc_html__( 'Year', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'month' => array(
				'label'   => esc_html__( 'Month', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'switcher' => array(
				'label'   => esc_html__( 'Show calendar switcher?', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'on'  => esc_html__( 'Yes', 'jet-elements' ),
					'off' => esc_html__( 'No', 'jet-elements' ),
				),
			),
			'size' => array(
				'label'   => esc_html__( 'Calendar size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'large' => esc_html__( 'Large', 'jet-elements' ),
					'small' => esc_html__( 'Small', 'jet-elements' ),
				),
			),

		);
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		foreach ( $this->__atts() as $control => $data ) {
			$this->add_control( $control, $data );
		}

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();

		$this->__context = 'render';

		$this->__open_wrap();

		$attributes = '';

		foreach ( $this->__atts() as $attr => $data ) {

			$attr_val    = $settings[ $attr ];
			$attr_val    = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );
			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->__tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->__close_wrap();

	}

}
