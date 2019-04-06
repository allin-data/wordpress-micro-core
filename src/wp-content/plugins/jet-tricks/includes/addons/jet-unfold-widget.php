<?php
/**
 * Class: Jet_Unfold_Widget
 * Name: Unfold
 * Slug: jet-unfold
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

class Jet_Unfold_Widget extends Jet_Tricks_Base {

	public function get_name() {
		return 'jet-unfold';
	}

	public function get_title() {
		return esc_html__( 'Unfold', 'jet-tricks' );
	}

	public function get_icon() {
		return 'jettricks-icon-69';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jet-anime-js' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-tricks/unfond/css-scheme',
			array(
				'instance'  => '.jet-unfold',
				'inner'     => '.jet-unfold__inner',
				'mask'      => '.jet-unfold__mask',
				'separator' => '.jet-unfold__separator',
				'content'   => '.jet-unfold__content',
				'button'    => '.jet-unfold__button',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'fold',
			array(
				'label'        => esc_html__( 'Fold', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'mask_height',
			array(
				'label'      => esc_html__( 'Closed Height', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 1000,
					),
				),
				'default' => array(
					'size' => 50,
					'unit' => 'px',
				),
				'render_type' => 'template',
			)
		);

		$this->start_controls_tabs( 'tabs_settings' );

		$this->start_controls_tab(
			'tab_unfold_settings',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'unfold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 500,
					'unit' => 'ms',
				),
			)
		);

		$this->add_control(
			'unfold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-tricks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutBack',
				'options' => array(
					'linear'        => esc_html__( 'Linear', 'jet-tricks' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-tricks' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-tricks' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-tricks' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-tricks' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-tricks' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-tricks' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-tricks' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-tricks' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fold_settings',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'fold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'ms',
				),
			)
		);

		$this->add_control(
			'fold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-tricks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutSine',
				'options' => array(
					'linear'        => esc_html__( 'Linear', 'jet-tricks' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-tricks' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-tricks' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-tricks' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-tricks' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-tricks' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-tricks' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-tricks' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-tricks' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'editor',
			array(
				'label'   => '',
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array(
					'active' => true,
				),
				'default'  => esc_html__( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jet-tricks' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'jet-tricks' ),
			)
		);

		$this->start_controls_tabs( 'tabs_button' );

		$this->start_controls_tab(
			'tab_fold_button',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_fold_icon',
			array(
				'label'       => esc_html__( 'Fold Icon', 'jet-tricks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-chevron-down',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'button_fold_text',
			array(
				'label'   => esc_html__( 'Fold Text', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hide', 'jet-tricks' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_unfold_button',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_unfold_icon',
			array(
				'label'       => esc_html__( 'Unfold Icon', 'jet-tricks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-chevron-up',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'button_unfold_text',
			array(
				'label'   => esc_html__( 'Unfold Text', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show', 'jet-tricks' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Container Style Section
		 */
		$this->start_controls_section(
			'section_container_style',
			array(
				'label'      => esc_html__( 'Container', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_container_style' );

		$this->start_controls_tab(
			'tab_fold_container',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_fold_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_fold_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_fold_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_unfold_container',
			array(
				'label' => esc_html__( 'UnFold', 'jet-tricks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_unfold_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_unfold_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_unfold_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'container_margin',
			array(
				'label'      => __( 'Margin', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Separator Style Section
		 */
		$this->start_controls_section(
			'section_separator_style',
			array(
				'label'      => esc_html__( 'Separator', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'separator_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['separator'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'separator_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['separator'],
			)
		);

		$this->end_controls_section();

		/**
		 * Content Style Section
		 */
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_content_style' );

		$this->start_controls_tab(
			'tab_fold_content',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'fold_content_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fold_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_unfold_content',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'unfold_content_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-unfold-state ' . $css_scheme['content'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'unfold_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .jet-unfold-state ' . $css_scheme['content'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		/**
		 * Button Style Section
		 */
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'      => esc_html__( 'Button', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tricks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-tricks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tricks' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-tricks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-tricks' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
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
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_hover_margin',
			array(
				'label'      => __( 'Margin', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
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

		$settings = $this->get_settings();

		$json_settings = array(
			'height'          => $settings['mask_height'],
			'heightTablet'    => $settings['mask_height_tablet'],
			'heightMobile'    => $settings['mask_height_mobile'],
			'separatorHeight' => $settings['separator_height'],
			'unfoldDuration'  => $settings['unfold_duration'],
			'foldDuration'    => $settings['fold_duration'],
			'unfoldEasing'    => $settings['unfold_easing'],
			'foldEasing'      => $settings['fold_easing'],
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-unfold',
				filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? 'jet-unfold-state' : '',
			),
			'data-settings' => json_encode( $json_settings ),
		) );

		$this->add_render_attribute( 'button', array(
			'class' => array(
				'jet-unfold__button',
				'elementor-button',
				'elementor-size-md',
			),
			'data-unfold-text' => $settings['button_unfold_text'],
			'data-fold-text'   => $settings['button_fold_text'],
			'data-fold-icon'   => $settings['button_fold_icon'],
			'data-unfold-icon' => $settings['button_unfold_icon'],
		) );

		$editor_content = $this->get_settings_for_display( 'editor' );

		$editor_content = $this->parse_text_editor( $editor_content );

		$this->add_render_attribute( 'editor', 'class', array( 'elementor-text-editor', 'elementor-clearfix' ) );

		$this->add_inline_editing_attributes( 'editor', 'advanced' );

		$button_icon_html = '';

		if ( ! empty( $settings['button_unfold_icon'] ) && ! empty( $settings['button_fold_icon'] ) ) {
			$button_icon = ! filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? $settings['button_unfold_icon'] : $settings['button_fold_icon'];

			$button_icon_html = sprintf( '<span class="jet-unfold__button-icon"><i class="%1$s"></i></span>', $button_icon );
		}

		$button_text_html = '';

		if ( ! empty( $settings['button_unfold_text'] ) && ! empty( $settings['button_fold_text'] ) ) {
			$button_text = ! filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? $settings['button_unfold_text'] : $settings['button_fold_text'];

			$button_text_html = sprintf( '<span class="jet-unfold__button-text">%1$s</span>', $button_text );
		}

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-unfold__inner">
				<div class="jet-unfold__mask">
					<div class="jet-unfold__content">
						<div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
					</div>
					<div class="jet-unfold__separator"></div>
				</div>
				<div class="jet-unfold__trigger"><?php
					echo sprintf( '<div %1$s>%2$s%3$s</div>',
						$this->get_render_attribute_string( 'button' ),
						$button_icon_html,
						$button_text_html
					);?>
				</div>
			</div>
		</div>
		<?php
	}
}
