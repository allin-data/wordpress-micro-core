<?php
/**
 * Class: Jet_Elements_Testimonials
 * Name: Testimonials
 * Slug: jet-testimonials
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

class Jet_Elements_Testimonials extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-testimonials';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-27';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jquery-slick' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/testimonials/css-scheme',
			array(
				'instance'   => '.jet-testimonials__instance',
				'item'       => '.jet-testimonials__item',
				'item_inner' => '.jet-testimonials__item-inner',
				'image'      => '.jet-testimonials__figure',
				'image_tag'  => '.jet-testimonials__tag-img',
				'content'    => '.jet-testimonials__content',
				'icon'       => '.jet-testimonials__icon',
				'icon_inner' => '.jet-testimonials__icon-inner',
				'title'      => '.jet-testimonials__title',
				'comment'    => '.jet-testimonials__comment',
				'name'       => '.jet-testimonials__name',
				'position'   => '.jet-testimonials__position',
				'date'       => '.jet-testimonials__date',
				'arrow'      => '.jet-testimonials__instance .jet-arrow',
				'dots'       => '.jet-testimonials__instance .jet-slick-dots',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'   => esc_html__( 'Slides to Show', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => jet_elements_tools()->get_select_range( 10 ),
			)
		);

		$this->add_control(
			'slides_to_scroll',
			array(
				'label'     => esc_html__( 'Slides to Scroll', 'jet-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => jet_elements_tools()->get_select_range( 10 ),
				'condition' => array(
					'slides_to_show!' => '1',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => esc_html__( 'Pause on Hover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'true',
				),
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'        => esc_html__( 'Infinite Loop', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'adaptive_height',
			array(
				'label'        => esc_html__( 'Adaptive Height', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'   => esc_html__( 'Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'jet-elements' ),
					'fade'  => esc_html__( 'Fade', 'jet-elements' ),
				),
				'condition' => array(
					'slides_to_show' => '1',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Animation Speed', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => esc_html__( 'Show Arrows Navigation', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'prev_arrow',
			array(
				'label'   => esc_html__( 'Prev Arrow Icon', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fa fa-angle-left',
				'options' => jet_elements_tools()->get_available_prev_arrows_list(),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->add_control(
			'next_arrow',
			array(
				'label'   => esc_html__( 'Next Arrow Icon', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fa fa-angle-right',
				'options' => jet_elements_tools()->get_available_next_arrows_list(),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => esc_html__( 'Show Dots Navigation', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-quote-left',
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_comment',
			array(
				'label'   => esc_html__( 'Comment', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_name',
			array(
				'label'   => esc_html__( 'Name', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_position',
			array(
				'label'   => esc_html_x( 'Position', 'Position at work', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_date',
			array(
				'label'   => esc_html__( 'Date', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'Mary Scott', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'John Borthwick', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'Jane Bill', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'David Anderson', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'Susan O’Neill', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
					array(
						'item_comment'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'jet-elements' ),
						'item_name'     => esc_html__( 'Ryan King', 'jet-elements' ),
						'item_position' => esc_html__( 'Founder & CEO', 'jet-elements' ),
						'item_date'     => esc_html__( 'Thursday, August 31, 2017', 'jet-elements' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => esc_html__( 'Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'equal_height_cols',
			array(
				'label'        => esc_html__( 'Equal Items Height', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item_inner'],
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'],
			)
		);

		$this->add_control(
			'item_margin',
			array(
				'label'       => esc_html__( 'Item Margin', 'jet-elements' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px' ),
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['item_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					//'{{WRAPPER}} ' . $css_scheme['instance']   => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Image section
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'      => esc_html__( 'Image', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 150,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image_tag'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 800,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 150,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image_tag'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['image'],
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
			)
		);

		$this->add_responsive_control(
			'image_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Arrows section
		 */
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'      => esc_html__( 'Arrows', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_style',
				'label'          => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['arrow'],
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

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_hover_style',
				'label'          => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['arrow'] . ':hover',
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

		$this->end_controls_tabs();

		$this->add_control(
			'prev_arrow_position',
			array(
				'label'     => esc_html__( 'Prev Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'prev_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'prev_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-elements' ),
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
					'prev_vert_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'prev_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-elements' ),
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
					'prev_vert_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'prev_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'prev_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
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
					'prev_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'prev_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
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
					'prev_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->add_control(
			'next_arrow_position',
			array(
				'label'     => esc_html__( 'Next Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'next_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'next_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-elements' ),
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
					'next_vert_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'next_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-elements' ),
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
					'next_vert_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'next_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'next_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
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
					'next_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'next_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
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
					'next_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['arrow'] . '.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * Dots section
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
				'selector'       => '{{WRAPPER}} ' . $css_scheme['dots'] .' li span',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
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
				'selector'       => '{{WRAPPER}} ' . $css_scheme['dots'] . ' li span:hover',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
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
				'selector'       => '{{WRAPPER}} ' . $css_scheme['dots'] .' li.slick-active span',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dots_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 5,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['dots'] . ' li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dots_margin',
			array(
				'label'      => esc_html__( 'Dots Box Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dots'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dots_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['dots'] => 'justify-content: {{VALUE}};',
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
				'label'      => esc_html__( 'Icon', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Font Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'],
			)
		);

		$this->add_control(
			'icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' ' . $css_scheme['icon_inner'],
			)
		);

		$this->add_responsive_control(
			'icon_box_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'title_custom_width',
			array(
				'label'        => esc_html__( 'Custom width', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'title_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'title_custom_width' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Comment Style Section
		 */
		$this->start_controls_section(
			'section_comment_style',
			array(
				'label'      => esc_html__( 'Comment', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'comment_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'comment_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'],
			)
		);

		$this->add_responsive_control(
			'comment_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'use_comment_corner',
			array(
				'label'        => esc_html__( 'Use comment corner', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'comment_corner_color',
			array(
				'label'   => esc_html__( 'Color', 'jet-elements' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
				'condition' => array(
					'use_comment_corner' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'comment_corner_position',
			array(
				'label'      => esc_html__( 'Corner Position', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'use_comment_corner' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'comment_corner_width',
			array(
				'label'      => esc_html__( 'Corner Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-right-width: {{SIZE}}{{UNIT}}; margin-left: calc({{SIZE}}{{UNIT}}/-2);',
				),
				'condition' => array(
					'use_comment_corner' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'comment_corner_height',
			array(
				'label'      => esc_html__( 'Corner Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-top-width: {{SIZE}}{{UNIT}}; bottom: -{{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'use_comment_corner' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'comment_corner_skew',
			array(
				'label'      => esc_html__( 'Corner Skew', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] . ':after' => 'border-left-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'use_comment_corner' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'comment_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'],
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

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'comment_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['comment'],
			)
		);

		$this->add_control(
			'comment_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default' => array(
					'top'    => 5,
					'right'  => 5,
					'bottom' => 5,
					'left'   => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'comment_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['comment'],
			)
		);

		$this->add_responsive_control(
			'comment_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default' => array(
					'top'    => 25,
					'right'  => 15,
					'bottom' => 25,
					'left'   => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'comment_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'comment_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'comment_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['comment'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Name Style Section
		 */
		$this->start_controls_section(
			'section_name_style',
			array(
				'label'      => esc_html__( 'Name', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'name_custom_width',
			array(
				'label'        => esc_html__( 'Custom width', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'name_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'name_custom_width' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['name'],
			)
		);

		$this->add_responsive_control(
			'name_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'name_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'name_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Position Style Section
		 */
		$this->start_controls_section(
			'section_position_style',
			array(
				'label'      => esc_html_x( 'Position', 'Position at work', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'position_custom_width',
			array(
				'label'        => esc_html__( 'Custom width', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'position_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'position_custom_width' => 'yes',
				),
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'position_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['position'],
			)
		);

		$this->add_responsive_control(
			'position_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Date Style Section
		 */
		$this->start_controls_section(
			'section_date_style',
			array(
				'label'      => esc_html__( 'Date', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'date_custom_width',
			array(
				'label'        => esc_html__( 'Custom width', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'date_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 350,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'date_custom_width' => 'yes',
				),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['date'],
			)
		);

		$this->add_responsive_control(
			'date_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['date'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Order Style Section
		 */
		$this->start_controls_section(
			'section_order_style',
			array(
				'label'      => esc_html__( 'Content Order and Alignment', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'image_order',
			array(
				'label'   => esc_html__( 'Image Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['image'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_order',
			array(
				'label'   => esc_html__( 'Icon Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['icon'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'comment_order',
			array(
				'label'   => esc_html__( 'Description Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['comment'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_order',
			array(
				'label'   => esc_html__( 'Name Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['name'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'position_order',
			array(
				'label'   => esc_html__( 'Position Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['position'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_order',
			array(
				'label'   => esc_html__( 'Date Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 7,
				'min'     => 1,
				'max'     => 7,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['date'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'content_alignment',
			array(
				'label'   => esc_html__( 'Cover Content Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start'    => esc_html__( 'Top', 'jet-elements' ),
					'center'        => esc_html__( 'Center', 'jet-elements' ),
					'flex-end'      => esc_html__( 'Bottom', 'jet-elements' ),
					'space-between' => esc_html__( 'Space between', 'jet-elements' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content'] => 'justify-content: {{VALUE}};',
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
			'slidesToShow'   => array(
				'desktop' => absint( $settings['slides_to_show'] ),
				'tablet'  => absint( $settings['slides_to_show_tablet'] ),
				'mobile'  => absint( $settings['slides_to_show_mobile'] ),
			),
			'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
			'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
			'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
			'adaptiveHeight' => filter_var( $settings['adaptive_height'], FILTER_VALIDATE_BOOLEAN ),
			'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
			'speed'          => absint( $settings['speed'] ),
			'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
			'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
			'slidesToScroll' => absint( $settings['slides_to_scroll'] ),
			'prevArrow'      => jet_elements_tools()->get_carousel_arrow(
				array( $settings['prev_arrow'], 'prev-arrow' )
			),
			'nextArrow'      => jet_elements_tools()->get_carousel_arrow(
				array( $settings['next_arrow'], 'next-arrow' )
			),
			'rtl' => is_rtl(),
		);

		if ( 'fade' === $settings['effect'] ) {
			$instance_settings['fade'] = true;
		}

		$instance_settings = json_encode( $instance_settings );

		return sprintf( 'data-settings=\'%1$s\'', $instance_settings );
	}
}
