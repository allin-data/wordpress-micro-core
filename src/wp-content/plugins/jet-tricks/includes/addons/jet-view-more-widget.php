<?php
/**
 * Class: Jet_View_More_Widget
 * Name: View More
 * Slug: jet-view-more
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

class Jet_View_More_Widget extends Jet_Tricks_Base {

	public function get_name() {
		return 'jet-view-more';
	}

	public function get_title() {
		return esc_html__( 'View More', 'jet-tricks' );
	}

	public function get_icon() {
		return 'jettricks-icon-71';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-tricks/view-more/css-scheme',
			array(
				'instance' => '.jet-view-more',
				'button'   => '.jet-view-more__button',
				'icon'     => '.jet-view-more__icon',
				'label'    => '.jet-view-more__label',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Sections', 'jet-tricks' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'section_id',
			array(
				'label'   => esc_html__( 'Section Id', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'section_1',
			)
		);

		$this->add_control(
			'sections',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'section_id' => 'section_1'
					)
				),
				'title_field' => '{{{ section_id }}}',
				'render_type' => 'template',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings_data',
			array(
				'label' => esc_html__( 'Settings', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-tricks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-arrow-circle-right',
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'View More', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'show_all',
			array(
				'label'        => esc_html__( 'Show All Sections', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'show_effect',
			array(
				'label'       => esc_html__( 'Show Effect', 'jet-tricks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'move-up',
				'options' => array(
					'none'             => esc_html__( 'None', 'jet-tricks' ),
					'fade'             => esc_html__( 'Fade', 'jet-tricks' ),
					'zoom-in'          => esc_html__( 'Zoom In', 'jet-tricks' ),
					'zoom-out'         => esc_html__( 'Zoom Out', 'jet-tricks' ),
					'move-up'          => esc_html__( 'Move Up', 'jet-tricks' ),
					'fall-perspective' => esc_html__( 'Fall Perspective', 'jet-tricks' ),
				),
				'render_type' => 'template',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_styles' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_label_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ' ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['button'] . ' ' . $css_scheme['label'],
			)
		);

		$this->add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ' ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ' ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'buttonlabel_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_label_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['label'],
			)
		);

		$this->add_control(
			'button_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_size_hover',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_hover',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$button_settings = $this->get_settings();

		$sections = $button_settings[ 'sections' ];

		foreach ( $sections as $key => $section ) {
			$sections_list[ $section['_id'] ] = $section['section_id'];
		}

		$settings = array(
			'effect'   => $button_settings['show_effect'],
			'sections' => $sections_list,
			'showall'  => filter_var( $button_settings['show_all'], FILTER_VALIDATE_BOOLEAN ),
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-view-more',
			),
			'data-settings' => json_encode( $settings ),
		) );

		$button_icon_html = '';

		if ( ! empty( $button_settings['button_icon'] ) ) {
			$button_icon_html = sprintf( '<div class="jet-view-more__icon"><i class="%1$s"></i></div>', $button_settings['button_icon'] );
		}

		$button_label_html = '';

		if ( ! empty( $button_settings['button_label'] ) ) {
			$button_label_html = sprintf( '<div class="jet-view-more__label">%1$s</div>', $button_settings['button_label'] );
		}

		echo sprintf(
			'<div %1$s><div class="jet-view-more__button">%2$s%3$s</div></div>',
			$this->get_render_attribute_string( 'instance' ),
			$button_icon_html,
			$button_label_html
		);
	}
}
