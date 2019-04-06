<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Popup_Group_Control_Transform_Style extends Elementor\Group_Control_Base {

	/**
	 * Prepare fields.
	 *
	 * Process css_filter control fields before adding them to `add_control()`.
	 *
	 * @since 2.1.0
	 * @access protected
	 *
	 * @param array $fields CSS Filter control fields.
	 *
	 * @return array Processed fields.
	 */
	protected static $fields;

	/**
	 * [get_type description]
	 * @return [type] [description]
	 */
	public static function get_type() {
		return 'jet-popup-transform-style';
	}

	/**
	 * [init_fields description]
	 * @return [type] [description]
	 */
	protected function init_fields() {
		$controls = [];

		$controls['translate_x'] = [
			'label' => _x( 'Translate X', 'Transform Control', 'jet-popup' ),
			'type' => Controls_Manager::SLIDER,
			'responsive' => true,
			'required' => 'true',
			'range' => [
				'px' => [
					'min' => -300,
					'max' => 300,
				],
			],
			'default' => [
				'size' => 0,
			],
			'selectors' => [
				'{{SELECTOR}}' => 'transform: translateX({{translate_x.SIZE}}px) translateY({{translate_y.SIZE}}px); -webkit-transform: translateX({{translate_x.SIZE}}px) translateY({{translate_y.SIZE}}px);',
				'(tablet){{SELECTOR}}' => 'transform: translateX({{translate_x_tablet.SIZE}}px) translateY({{translate_y_tablet.SIZE}}px); -webkit-transform: translateX({{translate_x_tablet.SIZE}}px) translateY({{translate_y_tablet.SIZE}}px);',
				'(mobile){{SELECTOR}}' => 'transform: translateX({{translate_x_mobile.SIZE}}px) translateY({{translate_y_mobile.SIZE}}px); -webkit-transform: translateX({{translate_x_mobile.SIZE}}px) translateY({{translate_y_mobile.SIZE}}px);',
			],
		];

		$controls['translate_y'] = [
			'label' => _x( 'Translate Y', 'Transform Control', 'jet-popup' ),
			'type' => Controls_Manager::SLIDER,
			'render_type' => 'ui',
			'required' => 'true',
			'responsive' => true,
			'range' => [
				'px' => [
					'min' => -300,
					'max' => 300,
				],
			],
			'default' => [
				'size' => 0,
			],
			'separator' => 'none',
		];

		return $controls;
	}

}
