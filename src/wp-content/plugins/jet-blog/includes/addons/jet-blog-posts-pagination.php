<?php
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

class Jet_Blog_Posts_Pagination extends Jet_Blog_Base {

	public function get_name() {
		return 'jet-blog-posts-pagination';
	}

	public function get_title() {
		return esc_html__( 'Posts Pagination', 'jet-blog' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$arrows_list = array( '' => esc_html__( 'None', 'jet-blog' ) ) + jet_blog_tools()->get_available_prev_arrows_list();

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Items', 'jet-blog' ),
			)
		);

		$this->add_control(
			'info_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Works only with main Query object.', 'jet-blog' )
			)
		);

		$this->add_control(
			'prev_next',
			array(
				'label'        => esc_html__( 'Add the previous and next page links.', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'prev_text',
			array(
				'label'       => esc_html__( 'The previous page link text', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Previous', 'jet-blog' ),
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);

		$this->add_control(
			'prev_icon',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'The next page link icon', 'jet-blog' ),
				'default'   => 'fa fa-angle-left',
				'options'   => $arrows_list,
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);

		$this->add_control(
			'next_text',
			array(
				'label'       => esc_html__( 'The next page text', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Next', 'jet-blog' ),
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);

		$this->add_control(
			'next_icon',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'The next page link icon', 'jet-blog' ),
				'default'   => 'fa fa-angle-left',
				'options'   => $arrows_list,
				'condition' => array(
					'prev_next' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'items_style',
			array(
				'label'      => esc_html__( 'Items', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'items_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-blog' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blog' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_items_style' );

		$this->start_controls_tab(
			'items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'items_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'items_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'items_active',
			array(
				'label' => esc_html__( 'Current', 'jet-blog' ),
			)
		);

		$this->add_control(
			'items_bg_color_active',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links span.page-numbers.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links span.page-numbers.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'items_active_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links span.page-numbers.current' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .navigation.pagination .nav-links .page-numbers',
			)
		);

		$this->add_responsive_control(
			'items_min_width',
			array(
				'label'      => esc_html__( 'Item Min Width', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .page-numbers' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_margin',
			array(
				'label'       => esc_html__( 'Gap Between Items', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 4,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .page-numbers' => 'margin-left: calc( {{SIZE}}px / 2 ); margin-right: calc( {{SIZE}}px / 2 );',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'items_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .navigation.pagination .nav-links .page-numbers:not(.dots)',
			)
		);

		$this->add_responsive_control(
			'items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'icons_style',
			array(
				'label'      => esc_html__( 'Prev/Next Icons', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_icons_style' );

		$this->start_controls_tab(
			'icons_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'icons_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .jet-blog-arrow' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icons_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .jet-blog-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icons_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'icons_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover .jet-blog-arrow' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icons_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover .jet-blog-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icons_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers:hover .jet-blog-arrow' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'items_icon_size',
			array(
				'label'      => esc_html__( 'Dropdown Icon Size', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers .jet-blog-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icons_box_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 18,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers .jet-blog-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'icons_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .navigation.pagination .nav-links .jet-blog-arrow',
			)
		);

		$this->add_responsive_control(
			'icons_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links .jet-blog-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Between Text and Icon', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers .jet-blog-arrow.jet-arrow-prev' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .navigation.pagination .nav-links a.page-numbers .jet-blog-arrow.jet-arrow-next' => 'margin-left: {{SIZE}}{{UNIT}};',
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

}
