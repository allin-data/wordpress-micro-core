<?php
/**
 * Class: Jet_Blocks_Search
 * Name: Search
 * Slug: jet-search
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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Blocks_Search extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-search';
	}

	public function get_title() {
		return esc_html__( 'Search', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-7';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_search_general_settings',
			array(
				'label' => esc_html__( 'General Settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'search_placeholder',
			array(
				'label'   => esc_html__( 'Search Placeholder', 'jet-blocks' ),
				'default' => esc_html__( 'Search &hellip;', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'show_search_submit',
			array(
				'label'        => esc_html__( 'Show Submit Button', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'search_submit_label',
			array(
				'label'     => esc_html__( 'Submit Button Label', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'show_search_submit' => 'true',
				),
			)
		);

		$this->add_control(
			'search_submit_icon',
			array(
				'label'     => esc_html__( 'Submit Button Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-search',
				'condition' => array(
					'show_search_submit' => 'true',
				),
			)
		);

		$this->add_control(
			'show_search_in_popup',
			array(
				'label'        => esc_html__( 'Show Search Form in Popup', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'full_screen_popup',
			array(
				'label'        => esc_html__( 'Full Screen Popup', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => '',
				'condition' => array(
					'show_search_in_popup' => 'true',
				),
			)
		);

		$this->add_control(
			'search_popup_trigger_icon',
			array(
				'label'       => esc_html__( 'Popup Trigger Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-search',
				'condition' => array(
					'show_search_in_popup' => 'true',
				),
			)
		);

		$this->add_control(
			'search_close_icon',
			array(
				'label'     => esc_html__( 'Popup Close Button Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-times',
				'condition' => array(
					'show_search_in_popup' => 'true',
				),
			)
		);

		// TODO: add condition
		$this->add_control(
			'popup_show_effect',
			array(
				'label'   => esc_html__( 'Show Effect', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blocks' ),
					'fade'      => esc_html__( 'Fade', 'jet-blocks' ),
					'scale'     => esc_html__( 'Scale', 'jet-blocks' ),
					'move-up'   => esc_html__( 'Move Up', 'jet-blocks' ),
					'move-down' => esc_html__( 'Move Down', 'jet-blocks' ),
				),
				'default' => 'move-up',
			)
		);

		$this->add_control(
			'is_product_search',
			array(
				'label'        => esc_html__( 'Is Product Search', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-blocks/search/css-scheme',
			array(
				'form'                    => '.jet-search__form',
				'form_input'              => '.jet-search__field',
				'form_submit'             => '.jet-search__submit',
				'form_submit_icon'        => '.jet-search__submit-icon',
				'popup'                   => '.jet-search__popup',
				'popup_full_screen'       => '.jet-search__popup--full-screen',
				'popup_content'           => '.jet-search__popup-content',
				'popup_close'             => '.jet-search__popup-close',
				'popup_close_icon'        => '.jet-search__popup-close-icon',
				'popup_trigger_container' => '.jet-search__popup-trigger-container',
				'popup_trigger'           => '.jet-search__popup-trigger',
				'popup_trigger_icon'      => '.jet-search__popup-trigger-icon',
			)
		);

		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => esc_html__( 'Form', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'form_input_style',
			array(
				'label'     => esc_html__( 'Input Field', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'form_input_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['form_input'],
			)
		);

		$this->start_controls_tabs( 'form_input_tabs' );

		$this->start_controls_tab(
			'form_input_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_input_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_input_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_input_placeholder_color',
			array(
				'label'  => esc_html__( 'Placeholder Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] . '::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['form_input'] . '::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':-ms-input-placeholder'      => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_input_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_input_tab_focus',
			array(
				'label' => esc_html__( 'Focus', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_input_bg_color_focus',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_input_color_focus',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_input_placeholder_color_focus',
			array(
				'label'  => esc_html__( 'Placeholder Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_input_border_color_focus',
			array(
				'label'  => esc_html__( 'Border Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'form_input_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_input_box_shadow_focus',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form_input'] . ':focus',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'form_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form_input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'form_input_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form_input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'form_input_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['form_input'],
			)
		);

		$this->add_responsive_control(
			'form_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form_input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_submit_style',
			array(
				'label'     => esc_html__( 'Submit Button', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_submit_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
			)
		);

		$this->add_responsive_control(
			'form_submit_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_form_submit_style' );

		$this->start_controls_tab(
			'tab_form_submit_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_submit_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_submit_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_form_submit_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'form_submit_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_submit_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'form_submit_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'form_submit_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'form_submit_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form_submit'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'form_submit_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form_submit'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'form_submit_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['form_submit'],
			)
		);

		$this->add_responsive_control(
			'form_submit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form_submit'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_submit_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form_submit'],
			)
		);

		$this->add_control(
			'form_style',
			array(
				'label'     => esc_html__( 'Form Style', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'form_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'form_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['form'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'form_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['form'],
			)
		);

		$this->add_responsive_control(
			'form_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['form'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['form'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_style',
			array(
				'label'      => esc_html__( 'Popup Box', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'popup_width',
			array(
				'label' => esc_html__( 'Popup Content Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] . ':not(' . $css_scheme['popup_full_screen'] . ')' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['popup_full_screen'] . ' ' . $css_scheme['popup_content'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'popup_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'popup_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'popup_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'popup_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['popup'],
			)
		);

		$this->add_responsive_control(
			'popup_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['popup'],
			)
		);

		$this->add_control(
			'popup_position',
			array(
				'label'     => esc_html__( 'Popup Position', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'full_screen_popup' => '',
				),
			)
		);

		$this->add_control(
			'popup_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Postition by', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-blocks' ),
					'bottom' => esc_html__( 'Bottom', 'jet-blocks' ),
				),
				'condition' => array(
					'full_screen_popup' => '',
				),
			)
		);

		$this->add_responsive_control(
			'popup_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'popup_vert_position' => 'top',
					'full_screen_popup'   => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'popup_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'popup_vert_position' => 'bottom',
					'full_screen_popup'   => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'popup_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-blocks' ),
					'right' => esc_html__( 'Right', 'jet-blocks' ),
				),
				'condition' => array(
					'full_screen_popup' => '',
				),
			)
		);

		$this->add_responsive_control(
			'popup_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'popup_hor_position' => 'left',
					'full_screen_popup'  => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'popup_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'popup_hor_position' => 'right',
					'full_screen_popup'  => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_trigger_style',
			array(
				'label'      => esc_html__( 'Popup Trigger', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_popup_trigger_style' );

		$this->start_controls_tab(
			'tab_popup_trigger_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'popup_trigger_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_trigger_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_popup_trigger_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'popup_trigger_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_trigger_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_trigger_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'popup_trigger_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'popup_trigger_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'popup_trigger_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} '  . $css_scheme['popup_trigger_container'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'popup_trigger_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'popup_trigger_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup_trigger'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'popup_trigger_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
			)
		);

		$this->add_responsive_control(
			'popup_trigger_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup_trigger'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_trigger_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['popup_trigger'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_close_style',
			array(
				'label'      => esc_html__( 'Popup Close', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_popup_close_style' );

		$this->start_controls_tab(
			'tab_popup_close_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'popup_close_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_close_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_popup_close_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'popup_close_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_close_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'popup_close_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'popup_close_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'popup_close_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'popup_close_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup_close'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'popup_close_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['popup_close'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'popup_close_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['popup_close'],
			)
		);

		$this->add_responsive_control(
			'popup_close_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['popup_close'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_close_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['popup_close'],
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

}
