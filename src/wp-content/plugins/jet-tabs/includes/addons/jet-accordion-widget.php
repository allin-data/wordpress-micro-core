<?php
/**
 * Class: Jet_Accordion_Widget
 * Name: Accordion Widget
 * Slug: jet-accordion
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

class Jet_Accordion_Widget extends Jet_Tabs_Base {

	public function get_name() {
		return 'jet-accordion';
	}

	public function get_title() {
		return esc_html__( 'Classic Accordion', 'jet-tabs' );
	}

	public function get_icon() {
		return 'jettabs-icon-47';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-tabs/accordion/css-scheme',
			array(
				'instance' => '> .elementor-widget-container > .jet-accordion',
				'toggle'   => '> .elementor-widget-container > .jet-accordion > .jet-accordion__inner > .jet-toggle',
				'control'  => '> .elementor-widget-container > .jet-accordion > .jet-accordion__inner > .jet-toggle > .jet-toggle__control',
				'content'  => '> .elementor-widget-container > .jet-accordion > .jet-accordion__inner > .jet-toggle > .jet-toggle__content',
				'label'    => '.jet-toggle__label-text',
				'icon'     => '.jet-toggle__label-icon',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-tabs' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_active',
			array(
				'label'        => esc_html__( 'Active', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tabs' ),
				'label_off'    => esc_html__( 'No', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$repeater->add_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-tabs' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-plus',
			)
		);

		$repeater->add_control(
			'item_active_icon',
			array(
				'label'       => esc_html__( 'Active Icon', 'jet-tabs' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-minus',
			)
		);

		$repeater->add_control(
			'item_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Tab', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
			)
		);

		$templates = jet_tabs()->elementor()->templates_manager->get_source( 'local' )->get_items();

		if ( empty( $templates ) ) {

			$this->add_control(
				'no_templates',
				array(
					'label' => false,
					'type'  => Controls_Manager::RAW_HTML,
					'raw'   => $this->empty_templates_message(),
				)
			);

			return;
		}

		$options = [
			'0' => '— ' . esc_html__( 'Select', 'jet-tabs' ) . ' —',
		];

		$types = [];

		foreach ( $templates as $template ) {
			$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			$types[ $template['template_id'] ] = $template['type'];
		}

		$repeater->add_control(
			'content_type',
			[
				'label'       => esc_html__( 'Content Type', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Template', 'jet-tabs' ),
					'editor'   => esc_html__( 'Editor', 'jet-tabs' ),
				],
				'label_block' => 'true',
			]
		);

		$repeater->add_control(
			'item_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => $options,
				'types'       => $types,
				'label_block' => 'true',
				'condition'   => [
					'content_type' => 'template',
				]
			)
		);

		$repeater->add_control(
			'item_editor_content',
			[
				'label'      => __( 'Content', 'jet-tabs' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Tab Item Content', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'   => [
					'content_type' => 'editor',
				]
			]
		);

		$this->add_control(
			'toggles',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'item_label'  => esc_html__( 'Toggle #1', 'jet-tabs' ),
					),
					array(
						'item_label'  => esc_html__( 'Toggle #2', 'jet-tabs' ),
					),
					array(
						'item_label'  => esc_html__( 'Toggle #3', 'jet-tabs' ),
					),
				),
				'title_field' => '{{{ item_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings_data',
			array(
				'label' => esc_html__( 'Settings', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'collapsible',
			array(
				'label'        => esc_html__( 'Collapsible', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tabs' ),
				'label_off'    => esc_html__( 'No', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'show_effect',
			array(
				'label'       => esc_html__( 'Show Effect', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'move-up',
				'options' => array(
					'none'             => esc_html__( 'None', 'jet-tabs' ),
					'fade'             => esc_html__( 'Fade', 'jet-tabs' ),
					//'column-fade'      => esc_html__( 'Column Fade', 'jet-tabs' ),
					'zoom-in'          => esc_html__( 'Zoom In', 'jet-tabs' ),
					'zoom-out'         => esc_html__( 'Zoom Out', 'jet-tabs' ),
					'move-up'          => esc_html__( 'Move Up', 'jet-tabs' ),
					//'column-move-up'   => esc_html__( 'Column Move Up', 'jet-tabs' ),
					'fall-perspective' => esc_html__( 'Fall Perspective', 'jet-tabs' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_accordion_container_style',
			array(
				'label'      => esc_html__( 'Accordion Container', 'jet-tabs' ),
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
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_control(
			'instance_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'instance_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'instance_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Toggle Style Section
		 */
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'      => esc_html__( 'Toggle', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_control(
			'toggle_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->end_controls_section();

		/**
		 * Toggle Control Style Section
		 */
		$this->start_controls_section(
			'section_toggle_control_style',
			array(
				'label'      => esc_html__( 'Toggle Control', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'toggle_label_aligment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-tabs' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'fa fa-align-center',
					),
					'space-between' => array(
						'title' => esc_html__( 'Justify', 'jet-tabs' ),
						'icon'  => 'fa fa-align-justify',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-tabs' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'toggle_general_styles' );

		$this->start_controls_tab(
			'toggle_control_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'toggle_label_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['control'] . ' ' . $css_scheme['label'],
			)
		);

		$this->add_group_control(
			\Jet_Tabs_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Icon', 'jet-tabs' ),
				'name'     => 'toggle_icon_box',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] . ' .icon-normal',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_control_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_control_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['control'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_control_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'toggle_label_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['control'] . ':hover  ' . $css_scheme['label'],
			)
		);

		$this->add_group_control(
			\Jet_Tabs_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Icon', 'jet-tabs' ),
				'name'     => 'toggle_icon_box_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['icon'] . ' .icon-normal',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_control_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_control_border_hover',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_control_active',
			array(
				'label' => esc_html__( 'Active', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'toggle_label_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] . '.active-toggle ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography_active',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['toggle'] . '.active-toggle ' . $css_scheme['label'],
			)
		);

		$this->add_group_control(
			\Jet_Tabs_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Icon', 'jet-tabs' ),
				'name'     => 'toggle_icon_box_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . '.active-toggle ' . $css_scheme['icon'] . ' .icon-active',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_control_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . '.active-toggle > .jet-toggle__control',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_control_border_active',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['toggle'] . '.active-toggle > .jet-toggle__control',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_control_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'toggle_control_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_control_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
			)
		);

		$this->add_control(
			'toggle_icon_heading',
			array(
				'label' => esc_html__( 'Icon ', 'jet-tabs' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'toggle_icon_position',
			array(
				'label'   => esc_html__( 'Position', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default' => 'left',
				'label_block' => false,
			)
		);

		$this->add_responsive_control(
			'toggle_icon_margin',
			array(
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Toggle Content Style Section
		 */
		$this->start_controls_section(
			'section_tabs_content_style',
			array(
				'label'      => esc_html__( 'Toggle Content', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_content_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_responsive_control(
			'tabs_content_margin',
			array(
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tabs_content_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] . ' > .jet-toggle__content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_content_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_responsive_control(
			'tabs_content_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_content_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_control(
			'tabs_content_text_color',
			array(
				'label' => esc_html__( 'Text color', 'jet-tabs' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$toggles = $this->get_settings_for_display( 'toggles' );

		$id_int = substr( $this->get_id_int(), 0, 3 );

		$show_effect = $this->get_settings( 'show_effect' );

		$settings = array(
			'collapsible' => filter_var( $this->get_settings( 'collapsible' ), FILTER_VALIDATE_BOOLEAN ),
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-accordion',
			),
			'data-settings' => json_encode( $settings ),
		) );

		$toggle_icon_position = $this->get_settings( 'toggle_icon_position' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-accordion__inner">
				<?php
					foreach ( $toggles as $index => $item ) {
						$toggle_count = $index + 1;

						$toggle_setting_key         = $this->get_repeater_setting_key( 'jet_toggle', 'toggles', $index );
						$toggle_control_setting_key = $this->get_repeater_setting_key( 'jet_toggle_control', 'toggles', $index );
						$toggle_content_setting_key = $this->get_repeater_setting_key( 'jet_toggle_content', 'toggles', $index );

						$this->add_render_attribute( $toggle_control_setting_key, array(
							'id'            => 'jet-toggle-control-' . $id_int . $toggle_count,
							'class'         => array(
								'jet-toggle__control',
								'elementor-menu-anchor',
							),
							'data-toggle'   => $toggle_count,
						) );

						$toggle_control_icon_html = '';

						if ( ! empty( $item['item_icon'] ) && ! empty( $item['item_active_icon'] ) ) {
							$toggle_control_icon_html .= sprintf( '<div class="jet-toggle__label-icon jet-toggle-icon-position-%3$s"><i class="jet-toggle__icon icon-normal %1$s"></i><i class="jet-toggle__icon icon-active %2$s"></i></div>', $item['item_icon'], $item['item_active_icon'], $toggle_icon_position );
						}

						$toggle_control_label_html = '';

						if ( ! empty( $item['item_label'] ) ) {
							$toggle_control_label_html = sprintf( '<div class="jet-toggle__label-text">%1$s</div>', $item['item_label'] );
						}

						$this->add_render_attribute( $toggle_content_setting_key, array(
							'id'          => 'jet-toggle-content-' . $id_int . $toggle_count,
							'class'       => array(
								'jet-toggle__content'
							),
							'data-toggle' => $toggle_count,
						) );

						$content_html = '';

						switch ( $item[ 'content_type' ] ) {
							case 'template':

								if ( '0' !== $item['item_template_id'] ) {

									$template_content = jet_tabs()->elementor()->frontend->get_builder_content_for_display( $item['item_template_id'] );

									if ( ! empty( $template_content ) ) {

										$content_html .= $template_content;

										if ( jet_tabs_integration()->is_edit_mode() ) {
											$link = add_query_arg(
												array(
													'elementor'       => '',
													'jet-tabs-canvas' => '',
												),
												get_permalink( $item['item_template_id'] )
											);

											$content_html .= sprintf( '<div class="jet-toggle__edit-cover" data-template-edit-link="%s"><i class="fa fa-pencil"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'jet-tabs' ) );
										}
									} else {
										$content_html = $this->no_template_content_message();
									}

								} else {
									$content_html = $this->no_templates_message();
								}
							break;

							case 'editor':
								$content_html = $this->parse_text_editor( $item['item_editor_content'] );
							break;
						}

						$this->add_render_attribute( $toggle_setting_key, array(
							'class'         => array(
								'jet-accordion__item',
								'jet-toggle',
								'jet-toggle-' . $show_effect . '-effect',
								filter_var( $item['item_active'], FILTER_VALIDATE_BOOLEAN ) ? 'active-toggle' : '',
							),
						) );

						?><div <?php echo $this->get_render_attribute_string( $toggle_setting_key ); ?>>
							<div <?php echo $this->get_render_attribute_string( $toggle_control_setting_key ); ?>>
								<?php echo $toggle_control_icon_html;
									echo $toggle_control_label_html;?>
							</div>
							<div <?php echo $this->get_render_attribute_string( $toggle_content_setting_key ); ?>>
								<div class="jet-toggle__content-inner"><?php echo $content_html; ?></div>
							</div>
						</div><?php
					}?>
			</div>
		</div>
		<?php
	}

	/**
	 * [empty_templates_message description]
	 * @return [type] [description]
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'jet-tabs' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'jet-tabs' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'jet-tabs' ) . '</a></div>
				</div>';
	}

	/**
	 * [no_templates_message description]
	 * @return [type] [description]
	 */
	public function no_templates_message() {
		$message = '<span>' . esc_html__( 'Template is not defined. ', 'jet-tabs' ) . '</span>';

		$link = add_query_arg(
			array(
				'post_type'     => 'elementor_library',
				'action'        => 'elementor_new_post',
				'_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
				'template_type' => 'section',
			),
			esc_url( admin_url( '/edit.php' ) )
		);

		$new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'jet-tabs' ) . '</span><a class="jet-toogle-new-template-link elementor-clickable" target="_blank" href="' . $link . '">' . esc_html__( 'new one', 'jet-tabs' ) . '</a>' ;

		return sprintf(
			'<div class="jet-toogle-no-template-message">%1$s%2$s</div>',
			$message,
			jet_tabs_integration()->in_elementor() ? $new_link : ''
		);
	}

	/**
	 * [no_template_content_message description]
	 * @return [type] [description]
	 */
	public function no_template_content_message() {
		$message = '<span>' . esc_html__( 'The toggles are working. Please, note, that you have to add a template to the library in order to be able to display it inside the toggles.', 'jet-tabs' ) . '</span>';

		return sprintf( '<div class="jet-toogle-no-template-message">%1$s</div>', $message );
	}

	/**
	 * [get_template_edit_link description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function get_template_edit_link( $template_id ) {

		$link = add_query_arg( 'elementor', '', get_permalink( $template_id ) );

		return '<a target="_blank" class="elementor-edit-template elementor-clickable" href="' . $link .'"><i class="fa fa-pencil"></i> ' . esc_html__( 'Edit Template', 'jet-tabs' ) . '</a>';
	}

}
