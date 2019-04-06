<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Menu Widget
 */
class Jet_Widget_Custom_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jet-custom-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Vertical Mega Menu', 'jet-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'jetmenu-icon-87';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-menu/custom-menu/css-scheme',
			array(
				'instance'                 => '> .elementor-widget-container > div > .jet-custom-nav',
				'main_items'               => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item',
				'main_items_hover'         => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.hover-state',
				'main_items_active'        => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.current-menu-item',
				'main_items_link'          => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > a',
				'main_items_link_hover'    => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.hover-state > a',
				'main_items_link_active'   => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.current-menu-item > a',
				'mega_menu'                => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__mega-sub',
				'mega_menu_right_pos'      => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-right-side > .jet-custom-nav__item > .jet-custom-nav__mega-sub',
				'mega_menu_left_pos'       => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-left-side > .jet-custom-nav__item > .jet-custom-nav__mega-sub',
				'sub_menu'                 => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub',
				'sub_menu_right_pos'       => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-right-side > .jet-custom-nav__item > .jet-custom-nav__sub',
				'sub_menu_left_pos'        => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-left-side > .jet-custom-nav__item > .jet-custom-nav__sub',
				'sub_menu_level'           => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__sub',
				'sub_menu_level_right_pos' => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-right-side > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__sub',
				'sub_menu_level_left_pos'  => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-left-side > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__sub',
				'sub_items'                => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item',
				'sub_items_hover'          => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.hover-state',
				'sub_items_active'         => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.current-menu-item',
				'sub_items_link'           => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item > a',
				'sub_items_link_hover'     => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.hover-state > a',
				'sub_items_link_active'    => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.current-menu-item > a',
				'badge'                    => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > a .jet-menu-badge',
				'badge_sub'                => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub a .jet-menu-badge',
				'icon'                     => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > a .jet-menu-icon',
				'icon_sub'                 => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub a .jet-menu-icon',
				'icon_hover'               => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.hover-state > a .jet-menu-icon',
				'icon_sub_hover'           => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.hover-state > a .jet-menu-icon',
				'icon_active'              => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.current-menu-item > a .jet-menu-icon',
				'icon_sub_active'          => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.current-menu-item > a .jet-menu-icon',
				'dropdown_icon'            => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > a .jet-dropdown-arrow',
				'dropdown_icon_left'       => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-left-side > .jet-custom-nav__item > a .jet-dropdown-arrow',
				'dropdown_icon_sub'        => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub a .jet-dropdown-arrow',
				'dropdown_icon_sub_left'   => '> .elementor-widget-container > div > .jet-custom-nav--dropdown-left-side > .jet-custom-nav__item > .jet-custom-nav__sub a .jet-dropdown-arrow',
				'dropdown_icon_hover'      => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.hover-state > a .jet-dropdown-arrow',
				'dropdown_icon_sub_hover'  => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.hover-state > a .jet-dropdown-arrow',
				'dropdown_icon_active'     => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item.current-menu-item > a .jet-dropdown-arrow',
				'dropdown_icon_sub_active' => '> .elementor-widget-container > div > .jet-custom-nav > .jet-custom-nav__item > .jet-custom-nav__sub .jet-custom-nav__item.current-menu-item > a .jet-dropdown-arrow',
			)
		);

		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Menu', 'jet-menu' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'   => esc_html__( 'Select Menu', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_menus(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_position',
			array(
				'label'   => esc_html__( 'Sub Menu Position', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right-side',
				'options' => array(
					'right-side' => esc_html__( 'Right Side', 'jet-menu' ),
					'left-side'  => esc_html__( 'Left Side', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'animation_type',
			array(
				'label'   => esc_html__( 'Animation', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => array(
					'none'       => esc_html__( 'None', 'jet-menu' ),
					'fade'       => esc_html__( 'Fade', 'jet-menu' ),
					'move-up'    => esc_html__( 'Move Up', 'jet-menu' ),
					'move-down'  => esc_html__( 'Move Down', 'jet-menu' ),
					'move-left'  => esc_html__( 'Move Left', 'jet-menu' ),
					'move-right' => esc_html__( 'Move Right', 'jet-menu' ),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Main Menu Style Section
		 */
		$this->start_controls_section(
			'section_custom_main_menu_style',
			array(
				'label'      => esc_html__( 'Main Menu', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'menu_width',
			array(
				'label' => esc_html__( 'Main Menu Width', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'%', 'px',
				),
				'range' => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 250,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_menu_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'main_menu_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_menu_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_menu_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_responsive_control(
			'main_menu_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto;',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => '{{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Mega Menu Style Section
		 */
		$this->start_controls_section(
			'section_custom_mega_menu_style',
			array(
				'label'      => esc_html__( 'Mega Menu', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'mega_menu_width',
			array(
				'label' => esc_html__( 'Mega Menu Width', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%', 'vw',
				),
				'range' => array(
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
					'vw' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mega_menu'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'mega_menu_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mega_menu'],
			)
		);

		$this->add_responsive_control(
			'mega_menu_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mega_menu'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mega_menu_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mega_menu'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['mega_menu_right_pos'] . ':before' => 'width: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['mega_menu_left_pos'] . ':before' => 'width: {{RIGHT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mega_menu_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mega_menu'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'mega_menu_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['mega_menu'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mega_menu_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mega_menu'],
			)
		);

		$this->end_controls_section();

		/**
		 * Sub Menu Style Section
		 */
		$this->start_controls_section(
			'section_custom_sub_menu_style',
			array(
				'label'      => esc_html__( 'Sub Menu', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'sub_menu_width',
			array(
				'label' => esc_html__( 'Sub Menu Width', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'%', 'px',
				),
				'range' => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 250,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_menu'] => 'min-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level'] => 'min-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sub_menu_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_menu'] . ', {{WRAPPER}} ' . $css_scheme['sub_menu_level'],
			)
		);

		$this->add_responsive_control(
			'sub_menu_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_menu'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_menu_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_menu'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_right_pos'] . ':before' => 'width: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_left_pos'] . ':before' => 'width: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level_right_pos'] . ':before' => 'width: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level_left_pos'] . ':before' => 'width: {{RIGHT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_menu_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_menu'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['sub_menu_level'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_menu_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_menu'] . ', {{WRAPPER}} ' . $css_scheme['sub_menu_level'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_menu_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_menu'] . ', {{WRAPPER}} ' . $css_scheme['sub_menu_level'],
			)
		);

		$this->end_controls_section();

		/**
		 * Main Menu Items
		 */
		$this->start_controls_section(
			'section_main_items_style',
			array(
				'label'      => esc_html__( 'Main Menu Items', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_main_items_style' );

		$this->start_controls_tab(
			'tab_main_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_items_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'main_items_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_items_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['main_items_link'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'main_items_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'main_items_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link'] . ' .jet-custom-item-desc.top-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_items_desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['main_items_link'] . ' .jet-custom-item-desc.top-level-desc',
			)
		);

		$this->add_responsive_control(
			'main_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'main_items_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_items_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['main_items_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_items_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_main_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_items_hover_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_hover'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'main_items_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] . ' .jet-menu-icon:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_items_hover_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'main_items_hover_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'main_items_hover_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] . ' .jet-custom-item-desc.top-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'main_items_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'main_items_hover_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_items_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_items_hover_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['main_items_link_hover'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_items_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_hover'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_main_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'main_items_active_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_active'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'main_items_active_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_active'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['main_items_link_active'] . ' .jet-menu-icon:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'main_items_active_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_active'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'main_items_active_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'main_items_active_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_active'] . ' .jet-custom-item-desc.top-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'main_items_active_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_active'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'main_items_active_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_active'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_items_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items_link_active'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_items_active_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['main_items_link_active'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_items_active_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['main_items_link_active'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'main_first_item_custom_styles',
			array(
				'label'        => esc_html__( 'First item custom styles', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-menu' ),
				'return_value' => 'yes',
				'default'      => 'false',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'main_first_item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items'] . ':first-child > a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'main_first_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_first_item_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['main_items'] . ':first-child > a',
				'condition' => array(
					'main_first_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_control(
			'main_last_item_custom_styles',
			array(
				'label'        => esc_html__( 'Last item custom styles', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-menu' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'main_last_item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['main_items'] . ':last-child > a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'main_last_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'main_last_item_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['main_items'] . ':last-child > a',
				'condition' => array(
					'main_last_item_custom_styles' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Sub Menu Items
		 */
		$this->start_controls_section(
			'section_sub_items_style',
			array(
				'label'      => esc_html__( 'Sub Menu Items', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_sub_items_style' );

		$this->start_controls_tab(
			'tab_sub_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sub_items_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'sub_items_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['sub_items_link'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'sub_items_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_items_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link'] . ' .jet-custom-item-desc.sub-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['sub_items_link'] . ' .jet-custom-item-desc.sub-level-desc',
			)
		);

		$this->add_responsive_control(
			'sub_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'sub_items_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_items_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_items_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_items_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sub_items_hover_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'sub_items_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] . ' .jet-menu-icon:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_hover_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'sub_items_hover_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_items_hover_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] . ' .jet-custom-item-desc.sub-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'sub_items_hover_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_items_hover_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_items_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_hover'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'sub_items_active_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_active'],
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			)
		);

		$this->add_control(
			'sub_items_active_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] . ' .jet-menu-link-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] . ' .jet-menu-icon:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_active_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] . ' .jet-menu-link-text',
			)
		);

		$this->add_control(
			'sub_items_active_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_items_active_desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] . ' .jet-custom-item-desc.sub-level-desc' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_active_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'sub_items_active_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_active'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sub_items_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items_link_active'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_items_active_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_items_link_active'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_items_active_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_items_link_active'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sub_first_item_custom_styles',
			array(
				'label'        => esc_html__( 'First item custom styles', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-menu' ),
				'return_value' => 'yes',
				'default'      => 'false',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'sub_first_item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items'] . ':first-child > a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'sub_first_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_first_item_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_items'] . ':first-child > a',
				'condition' => array(
					'sub_first_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_control(
			'sub_last_item_custom_styles',
			array(
				'label'        => esc_html__( 'Last item custom styles', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-menu' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'sub_last_item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sub_items'] . ':last-child > a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'sub_last_item_custom_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_last_item_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['sub_items'] . ':last-child > a',
				'condition' => array(
					'sub_last_item_custom_styles' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Icon Style Section
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub_hover'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub_hover'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'icon_color_active',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_active'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub_active'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_active',
			array(
				'label'     => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_active'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub_active'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Font Size', 'jet-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default' => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ':before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] . ':before' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before'
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default' => array(
					'size' => 22,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ', {{WRAPPER}} ' . $css_scheme['icon_sub'],
			)
		);

		$this->add_control(
			'icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ', {{WRAPPER}} ' . $css_scheme['icon_sub'],
			)
		);

		$this->add_responsive_control(
			'icon_box_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-up',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-down',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'align-self: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['icon_sub'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Badge Style Section
		 */
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'      => esc_html__( 'Badge', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-menu' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'badge_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' . ', {{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' . ', {{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'badge_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' . ', {{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner',
			)
		);

		$this->add_control(
			'badge_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] . ' .jet-menu-badge__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['badge_sub'] . ' .jet-menu-badge__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_margin',
			array(
				'label'      => __( 'Margin', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['badge_sub'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-up',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-down',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'align-self: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['badge_sub'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Drop down icon Style Section
		 */
		$this->start_controls_section(
			'section_dropdown_icon_style',
			array(
				'label'      => esc_html__( 'Drop-down Icon', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'dropdown_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-menu' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-chevron-right',
			)
		);

		$this->add_responsive_control(
			'dropdown_icon_offset',
			array(
				'label'      => esc_html__( 'Offset', 'jet-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_left'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub_left'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dropdown_style' );

		$this->start_controls_tab(
			'tab_dropdown_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_icon_color_hover',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_hover'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub_hover'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_icon_bg_color_hover',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_hover'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub_hover'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_icon_color_active',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_active'] . ':before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub_active'] . ':before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_icon_bg_color_active',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-menu' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_active'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub_active'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dropdown_icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Font Size', 'jet-menu' ),
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
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] . ':before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] . ':before' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'dropdown_icon_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dropdown_icon_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['dropdown_icon'] . ', {{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'],
			)
		);

		$this->add_control(
			'dropdown_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['dropdown_icon'] . ', {{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'],
			)
		);

		$this->add_responsive_control(
			'dropdown_icon_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-up',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-menu' ),
						'icon'  => 'fa fa-arrow-down',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon'] => 'align-self: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['dropdown_icon_sub'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
		$parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
			unset( $menus[ $parent ] );
		}

		return $menus;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( ! $settings['menu'] ) {
			return;
		}

		$this->add_render_attribute( 'nav-wrapper', 'class', 'jet-custom-nav' );

		if ( isset( $settings['dropdown_position'] ) ) {
			$this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'jet-custom-nav--dropdown-%s', $settings['dropdown_position'] ) );
		}

		if ( isset( $settings['animation_type'] ) ) {
			$this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'jet-custom-nav--animation-%s', $settings['animation_type'] ) );
		}

		$args = array(
			'menu'            => $settings['menu'],
			'fallback_cb'     => '',
			'items_wrap'      => '<div ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>%3$s</div>',
			'walker'          => new \Jet_Menu_Widget_Walker,
			'widget_settings' => array(
				'dropdown_icon' => $settings['dropdown_icon'],
			),
		);

		wp_nav_menu( $args );

		if ( $this->is_css_required() ) {
			$dynamic_css = jet_menu()->dynamic_css();
			add_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
			$dynamic_css::$collector->print_style();
			remove_filter( 'cherry_dynamic_css_collector_localize_object', array( $this, 'fix_preview_css' ) );
		}

	}

	/**
	 * Check if need to insert custom CSS
	 * @return boolean
	 */
	public function is_css_required() {

		$allowed_actions = array( 'elementor_render_widget', 'elementor' );

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $allowed_actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Fix preview styles
	 *
	 * @return array
	 */
	public function fix_preview_css( $data ) {

		if ( ! empty( $data['css'] ) ) {
			printf( '<style>%s</style>', html_entity_decode( $data['css'] ) );
		}

		return $data;
	}

}
