<?php
/**
 * Class: Jet_Elements_Scroll_Navigation
 * Name: Scroll Navigation
 * Slug: jet-scroll-navigation
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Scroll_Navigation extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-scroll-navigation';
	}

	public function get_title() {
		return esc_html__( 'Scroll Navigation', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-32';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/scroll-navigation/css-scheme',
			array(
				'instance' => '.jet-scroll-navigation',
				'item'     => '.jet-scroll-navigation__item',
				'hint'     => '.jet-scroll-navigation__item-hint',
				'icon'     => '.jet-scroll-navigation__icon',
				'label'    => '.jet-scroll-navigation__label',
				'dots'     => '.jet-scroll-navigation__dot',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Hint Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-arrow-circle-right',
			)
		);

		$repeater->add_control(
			'item_dot_icon',
			array(
				'label'       => esc_html__( 'Dot Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
			)
		);

		$repeater->add_control(
			'item_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'item_section_id',
			array(
				'label'   => esc_html__( 'Section Id', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'item_section_invert',
			array(
				'label'        => esc_html__( 'Invert Under This Section', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'item_label'      => esc_html__( 'Section 1', 'jet-elements' ),
						'item_section_id' => 'section_1',
					),
					array(
						'item_label'      => esc_html__( 'Section 2', 'jet-elements' ),
						'item_section_id' => 'section_2',
					),
					array(
						'item_label'      => esc_html__( 'Section 3', 'jet-elements' ),
						'item_section_id' => 'section_3',
					),
				),
				'title_field' => '{{{ item_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'   => esc_html__( 'Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Scroll Speed', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'   => esc_html__( 'Scroll Offset', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$this->add_control(
			'full_section_switch',
			array(
				'label'        => esc_html__( 'Full Section Switch', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'instance_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'instance_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'instance_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'instance_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'instance_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'instance_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->end_controls_section();

		/**
		 * Hint Style Section
		 */
		$this->start_controls_section(
			'section_hint_style',
			array(
				'label'      => esc_html__( 'Hint', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hint_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hint_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_responsive_control(
			'hint_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hint'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hint_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hint'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hint_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hint'],
			)
		);

		$this->add_control(
			'hint_icon_style',
			array(
				'label'     => esc_html__( 'Hint Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hint_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_icon_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hint_label_style',
			array(
				'label'     => esc_html__( 'Hint Label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hint_label_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hint_label_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hint_label_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hint_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			)
		);

		$this->add_control(
			'hint_visible',
			array(
				'label'     => esc_html__( 'Hint Visible', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desktop_hint_hide',
			array(
				'label'        => esc_html__( 'Hide On Desktop', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Hide', 'jet-elements' ),
				'label_off'    => esc_html__( 'Show', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'tablet_hint_hide',
			array(
				'label'        => esc_html__( 'Hide On Tablet', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Hide', 'jet-elements' ),
				'label_off'    => esc_html__( 'Show', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'mobile_hint_hide',
			array(
				'label'        => esc_html__( 'Hide On Mobile', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Hide', 'jet-elements' ),
				'label_off'    => esc_html__( 'Show', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		/**
		 * Dots Style Section
		 */
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'      => esc_html__( 'Dots', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['dots'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_invert',
			array(
				'label' => esc_html__( 'Invert', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style_invert',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . '.invert ' . $css_scheme['dots'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style_hover',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['dots'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_active',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style_active',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['item'] . '.active ' . $css_scheme['dots'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_padding',
			array(
				'label'      => __( 'Dots Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dots'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => __( 'Dots Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$settings = $this->get_settings();

		$instance_settings = array(
			'position'      => $settings['position'],
			'speed'         => absint( $settings['speed'] ),
			'offset'        => absint( $settings['offset'] ),
			'sectionSwitch' => filter_var( $settings['full_section_switch'], FILTER_VALIDATE_BOOLEAN ),
		);

		$instance_settings = json_encode( $instance_settings );

		return sprintf( 'data-settings=\'%1$s\'', $instance_settings );
	}
}
