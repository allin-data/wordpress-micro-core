<?php
/**
 * Class: Jet_Blocks_Nav_Menu
 * Name: Nav Menu
 * Slug: jet-nav-menu
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

class Jet_Blocks_Nav_Menu extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-6';
	}

	public function get_script_depends() {
		return array( 'hoverIntent' );
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_menu',
			array(
				'label' => esc_html__( 'Menu', 'jet-blocks' ),
			)
		);

		$menus   = $this->get_available_menus();
		$default = '';

		if ( ! empty( $menus ) ) {
			$ids     = array_keys( $menus );
			$default = $ids[0];
		}

		$this->add_control(
			'nav_menu',
			array(
				'label'   => esc_html__( 'Select Menu', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default,
				'options' => $menus,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'jet-blocks' ),
					'vertical'   => esc_html__( 'Vertical', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'dropdown_position',
			array(
				'label'   => esc_html__( 'Dropdown Placement', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right-side',
				'options' => array(
					'left-side'  => esc_html__( 'Left Side', 'jet-blocks' ),
					'right-side' => esc_html__( 'Right Side', 'jet-blocks' ),
					'bottom'     => esc_html__( 'At the bottom', 'jet-blocks' ),
				),
				'condition' => array(
					'layout' => 'vertical',
				)
			)
		);

		$this->add_control(
			'dropdown_icon',
			array(
				'label'   => esc_html__( 'Dropdown Icon', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fa fa-angle-down',
				'options' => $this->dropdown_arrow_icons_list(),
			)
		);

		$this->add_responsive_control(
			'menu_alignment',
			array(
				'label'   => esc_html__( 'Menu Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
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
					'space-between' => array(
						'title' => esc_html__( 'Justified', 'jet-blocks' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors_dictionary' => array(
					'flex-start'    => 'justify-content: flex-start; text-align: left;',
					'center'        => 'justify-content: center; text-align: center;',
					'flex-end'      => 'justify-content: flex-end; text-align: right;',
					'space-between' => 'justify-content: space-between; text-align: left;',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav--horizontal' => '{{VALUE}}',
					'{{WRAPPER}} .jet-nav--vertical .menu-item-link-top' => '{{VALUE}}',
					'{{WRAPPER}} .jet-nav--vertical-sub-bottom .menu-item-link-sub' => '{{VALUE}}',

					'(mobile){{WRAPPER}} .jet-mobile-menu .menu-item-link' => '{{VALUE}}',
				),
				'prefix_class' => 'jet-nav%s-align-',
			)
		);

		$this->add_control(
			'mobile_trigger_visible',
			array(
				'label'     => esc_html__( 'Enable Mobile Trigger', 'jet-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'mobile_trigger_alignment',
			array(
				'label'   => esc_html__( 'Mobile Trigger Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_icon',
			array(
				'label'       => esc_html__( 'Mobile Trigger Icon', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-bars',
				'condition'   => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_close_icon',
			array(
				'label'       => esc_html__( 'Mobile Trigger Close Icon', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICON,
				'default'     => 'fa fa-times',
				'condition'   => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_menu_layout',
			array(
				'label' => esc_html__( 'Mobile Menu Layout', 'jet-blocks' ),
				'label_block' => true,
				'type'  => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'    => esc_html__( 'Default', 'jet-blocks' ),
					'full-width' => esc_html__( 'Full Width', 'jet-blocks' ),
					'left-side'  => esc_html__( 'Slide From The Left Side ', 'jet-blocks' ),
					'right-side' => esc_html__( 'Slide From The Right Side ', 'jet-blocks' ),
				),
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'nav_items_style',
			array(
				'label'      => esc_html__( 'Top Level Items', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'nav_vertical_menu_width',
			array(
				'label' => esc_html__( 'Vertical Menu Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav-wrap' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'layout' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
				'nav_vertical_menu_align',
				array(
					'label' => esc_html__( 'Vertical Menu Alignment', 'jet-blocks' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'jet-blocks' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-blocks' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'jet-blocks' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'margin-left: 0; margin-right: auto;',
						'center' => 'margin-left: auto; margin-right: auto;',
						'right'  => 'margin-left: auto; margin-right: 0;',
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-nav-wrap' => '{{VALUE}}',
					),
					'condition' => array(
						'layout' => 'vertical',
					),
				)
			);

		$this->start_controls_tabs( 'tabs_nav_items_style' );

		$this->start_controls_tab(
			'nav_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'nav_items_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_text_bg_color',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_text_icon_color',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-top .jet-nav-link-text',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'nav_items_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'nav_items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_items_text_bg_color_hover',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_text_icon_color_hover',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography_hover',
				'selector' => '{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-link-text',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'nav_items_bg_color_active',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_active_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'nav_items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_items_text_bg_color_active',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_items_text_icon_color_active',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography_active',
				'selector' => '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-link-text',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'nav_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'nav_items_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav > .jet-nav__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'nav_items_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .menu-item-link-top',
			)
		);

		$this->add_responsive_control(
			'nav_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_items_icon_size',
			array(
				'label'      => esc_html__( 'Dropdown Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'condition' => array(
					'dropdown_icon!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Before Dropdown Icon', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'condition' => array(
					'dropdown_icon!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .menu-item-link-top .jet-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'(mobile){{WRAPPER}} .jet-mobile-menu .jet-nav--vertical-sub-left-side .menu-item-link-top .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				),
			)
		);

		$this->add_control(
			'nav_items_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_desc_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-top .jet-nav-item-desc',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sub_items_style',
			array(
				'label'      => esc_html__( 'Dropdown', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'sub_items_container_style_heading',
			array(
				'label' => esc_html__( 'Container Styles', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'sub_items_container_width',
			array(
				'label'      => esc_html__( 'Container Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'sub_items_container_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_items_container_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-nav__sub',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_items_container_box_shadow',
				'selector' => '{{WRAPPER}} .jet-nav__sub',
			)
		);

		$this->add_responsive_control(
			'sub_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav__sub > .menu-item:first-child > .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .jet-nav__sub > .menu-item:last-child > .menu-item-link' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_container_top_gap',
			array(
				'label'      => esc_html__( 'Gap Before 1st Level Sub', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav--horizontal .jet-nav-depth-0' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .jet-nav-depth-0' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-right-side .jet-nav-depth-0' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_container_left_gap',
			array(
				'label'      => esc_html__( 'Gap Before 2nd Level Sub', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav-depth-0 .jet-nav__sub' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .jet-nav-depth-0 .jet-nav__sub' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'sub_items_style_heading',
			array(
				'label'     => esc_html__( 'Items Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-sub .jet-nav-link-text',
			)
		);

		$this->start_controls_tabs( 'tabs_sub_items_style' );

		$this->start_controls_tab(
			'sub_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'sub_items_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'sub_items_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sub_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'sub_items_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'sub_items_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'sub_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'sub_items_bg_color_active',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'sub_items_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'sub_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'sub_items_icon_size',
			array(
				'label'      => esc_html__( 'Dropdown Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'condition' => array(
					'dropdown_icon!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub .jet-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Before Dropdown Icon', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'condition' => array(
					'dropdown_icon!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-sub .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .menu-item-link-sub .jet-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'(mobile){{WRAPPER}} .jet-mobile-menu .jet-nav--vertical-sub-left-side .menu-item-link-sub .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				),
			)
		);

		$this->add_control(
			'sub_items_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'sub_items_divider',
				'selector' => '{{WRAPPER}} .jet-nav__sub > .jet-nav-item-sub:not(:last-child)',
				'exclude'  => array( 'width' ),
			)
		);

		$this->add_control(
			'sub_items_divider_width',
			array(
				'label' => esc_html__( 'Border Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub > .jet-nav-item-sub:not(:last-child)' => 'border-width: 0; border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'sub_items_divider_border!' => '',
				),
			)
		);

		$this->add_control(
			'sub_items_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_desc_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-sub .jet-nav-item-desc',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mobile_trigger_styles',
			array(
				'label'      => esc_html__( 'Mobile Trigger', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_mobile_trigger_style' );

		$this->start_controls_tab(
			'mobile_trigger_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'mobile_trigger_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mobile_trigger_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'mobile_trigger_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'mobile_trigger_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'mobile_trigger_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-nav__mobile-trigger',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'mobile_trigger_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_icon_size',
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
					'{{WRAPPER}} .jet-nav__mobile-trigger i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mobile_menu_styles',
			array(
				'label' => esc_html__( 'Mobile Menu', 'jet-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mobile_menu_width',
			array(
				'label' => esc_html__( 'Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 150,
						'max' => 400,
					),
					'%' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors' => array(
					'(mobile){{WRAPPER}} .jet-nav' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			)
		);

		$this->add_control(
			'mobile_menu_max_height',
			array(
				'label' => esc_html__( 'Max Height', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'(mobile){{WRAPPER}} .jet-nav' => 'max-height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => 'full-width',
				),
			)
		);

		$this->add_control(
			'mobile_menu_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'(mobile){{WRAPPER}} .jet-nav' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mobile_menu_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'(mobile){{WRAPPER}} .jet-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mobile_menu_box_shadow',
				'selector' => '(mobile){{WRAPPER}} .jet-mobile-menu-active .jet-nav',
			)
		);

		$this->add_control(
			'mobile_close_icon_heading',
			array(
				'label' => esc_html__( 'Close icon', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			)
		);

		$this->add_control(
			'mobile_close_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-close-btn' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			)
		);

		$this->add_control(
			'mobile_close_icon_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-close-btn' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Returns available icons for dropdown list
	 *
	 * @return array
	 */
	public function dropdown_arrow_icons_list() {

		return apply_filters( 'jet-blocks/nav-menu/dropdown-icons', array(
			'fa fa-angle-down'          => esc_html__( 'Angle', 'jet-blocks' ),
			'fa fa-angle-double-down'   => esc_html__( 'Angle Double', 'jet-blocks' ),
			'fa fa-chevron-down'        => esc_html__( 'Chevron', 'jet-blocks' ),
			'fa fa-chevron-circle-down' => esc_html__( 'Chevron Circle', 'jet-blocks' ),
			'fa fa-caret-down'          => esc_html__( 'Caret', 'jet-blocks' ),
			'fa fa-plus'                => esc_html__( 'Plus', 'jet-blocks' ),
			'fa fa-plus-square-o'       => esc_html__( 'Plus Square', 'jet-blocks' ),
			'fa fa-plus-circle'         => esc_html__( 'Plus Circle', 'jet-blocks' ),
			''                          => esc_html__( 'None', 'jet-blocks' ),
		) );

	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );

		return $menus;
	}

	protected function render() {

		$settings = $this->get_settings();

		if ( ! $settings['nav_menu'] ) {
			return;
		}

		$trigger_visible    = filter_var( $settings['mobile_trigger_visible'], FILTER_VALIDATE_BOOLEAN );
		$trigger_align      = $settings['mobile_trigger_alignment'];
		$trigger_icon       = isset( $settings['mobile_trigger_icon'] ) ? $settings['mobile_trigger_icon'] : 'fa fa-bars';
		$trigger_close_icon = isset( $settings['mobile_trigger_close_icon'] ) ? $settings['mobile_trigger_close_icon'] : 'fa fa-times';

		require_once jet_blocks()->plugin_path( 'includes/class-jet-blocks-nav-walker.php' );

		$this->add_render_attribute( 'nav-wrapper', 'class', 'jet-nav-wrap' );

		if ( $trigger_visible ) {
			$this->add_render_attribute( 'nav-wrapper', 'class', 'jet-mobile-menu' );

			if ( isset( $settings['mobile_menu_layout'] ) ) {
				$this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'jet-mobile-menu--%s', esc_attr( $settings['mobile_menu_layout'] ) ) );
				$this->add_render_attribute( 'nav-wrapper', 'data-mobile-layout', esc_attr( $settings['mobile_menu_layout'] ) );
			}
		}

		$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav' );

		if ( isset( $settings['layout'] ) ) {
			$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav--' . esc_attr( $settings['layout'] ) );

			if ( 'vertical' === $settings['layout'] && isset( $settings['dropdown_position'] ) ) {
				$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav--vertical-sub-' . esc_attr( $settings['dropdown_position'] ) );
			}
		}

		$menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s</div>';

		if ( $trigger_visible && in_array( $settings['mobile_menu_layout'], array( 'left-side', 'right-side' ) ) ) {
			$close_btn = sprintf( '<div class="jet-nav__mobile-close-btn"><i class="%s"></i></div>', $trigger_close_icon );

			$menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s' . $close_btn . '</div>';
		}

		$args = array(
			'menu'            => $settings['nav_menu'],
			'fallback_cb'     => '',
			'items_wrap'      => $menu_html,
			'walker'          => new \Jet_Blocks_Nav_Walker,
			'widget_settings' => array(
				'dropdown_icon' => $settings['dropdown_icon'],
			),
		);

		echo '<div ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>';
			if ( $trigger_visible ) {
				include $this->__get_global_template( 'mobile-trigger' );
			}
			wp_nav_menu( $args );
		echo '</div>';

	}
}
