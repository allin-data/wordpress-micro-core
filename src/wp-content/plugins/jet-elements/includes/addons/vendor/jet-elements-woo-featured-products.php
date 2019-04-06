<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Woo_Featured_Products extends Jet_Elements_Base {

	public function get_name() {
		return 'woo-featured-products';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Featured Products', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-16';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function __tag() {
		return 'featured_products';
	}

	public function __atts() {

		return array(
			'per_page' => array(
				'label'   => esc_html__( 'Products per page', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
			),
			'columns' => array(
				'label'     => esc_html__( 'Columns', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			),
			'orderby' => array(
				'label'   => esc_html__( 'Order By', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => jet_elements_tools()->orderby_arr(),
			),
			'order' => array(
				'label'   => esc_html__( 'Order', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => jet_elements_tools()->order_arr(),
			)

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
