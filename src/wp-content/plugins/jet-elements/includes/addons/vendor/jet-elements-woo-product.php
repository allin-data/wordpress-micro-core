<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Woo_Product extends Jet_Elements_Base {

	public function get_name() {
		return 'woo-product';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Product', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-14';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function __tag() {
		return 'product';
	}

	public function __atts() {

		return array(
			'product_id' => array(
				'label'   => esc_html__( 'Product ID', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
			),
			'sku' => array(
				'label'     => esc_html__( 'Product SKU', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
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

			$attr_val = $settings[ $attr ];
			$attr_val = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );

			if ( 'product_id' === $attr ) {
				$attr = 'id';
			}

			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->__tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->__close_wrap();

	}

}
