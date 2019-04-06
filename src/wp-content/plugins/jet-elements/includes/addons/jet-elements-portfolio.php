<?php
/**
 * Class: Jet_Elements_Portfolio
 * Name: Portfolio
 * Slug: jet-portfolio
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

class Jet_Elements_Portfolio extends Jet_Elements_Base {

	/**
	 * [$item_counter description]
	 * @var integer
	 */
	public $item_counter = 0;

	public function get_name() {
		return 'jet-portfolio';
	}

	public function get_title() {
		return esc_html__( 'Portfolio', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-36';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'imagesloaded', 'jet-masonry-js', 'jet-anime-js' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/portfolio/css-scheme',
			array(
				'instance'         => '.jet-portfolio',
				'list_container'   => '.jet-portfolio__list',
				'item'             => '.jet-portfolio__item',
				'inner'            => '.jet-portfolio__inner',
				'image_wrap'       => '.jet-portfolio__image',
				'image_instance'   => '.jet-portfolio__image-instance',
				'content_wrap'     => '.jet-portfolio__content',
				'content_inner'    => '.jet-portfolio__content-inner',
				'cover'            => '.jet-portfolio__cover',
				'title'            => '.jet-portfolio__title',
				'desc'             => '.jet-portfolio__desc',
				'category'         => '.jet-portfolio__category',
				'button'           => '.jet-portfolio__button',
				'view_more'        => '.jet-portfolio__view-more-button',
				'filters_wrap'     => '.jet-portfolio__filter',
				'filters'          => '.jet-portfolio__filter-list',
				'filter'           => '.jet-portfolio__filter-item',
				'filter_separator' => '.jet-portfolio__filter-item-separator',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'label'   => esc_html__( 'Layout type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => array(
					'masonry' => esc_html__( 'Masonry', 'jet-elements' ),
					'grid'    => esc_html__( 'Grid', 'jet-elements' ),
					'justify' => esc_html__( 'Justify', 'jet-elements' ),
					'list'    => esc_html__( 'List', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'preset',
			array(
				'label'   => esc_html__( 'Preset', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'type-1',
				'options' => array(
					'type-1' => esc_html__( 'Type-1', 'jet-elements' ),
					'type-2' => esc_html__( 'Type-2', 'jet-elements' ),
					'type-3' => esc_html__( 'Type-3', 'jet-elements' ),
					'type-4' => esc_html__( 'Type-4', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => jet_elements_tools()->get_select_range( 6 ),
				'condition' => array(
					'layout_type' => array( 'masonry', 'grid' ),
				),
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label' => esc_html__( 'Item Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default' => [
					'size' => 300,
				],
				'condition' => array(
					'layout_type' => array(
						'grid',
						'justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['image_instance'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'all_filter_label',
			array(
				'label'   => esc_html__( '`All` Filter Label', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'All', 'jet-elements' ),
			)
		);

		$this->add_control(
			'view_more_button',
			array(
				'label'        => esc_html__( 'View More Button', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => false,
			)
		);

		$this->add_control(
			'view_more_button_text',
			array(
				'label'     => esc_html__( 'View More Button Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'View More', 'jet-elements' ),
				'condition' => array(
					'view_more_button' => 'true',
				),
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => esc_html__( 'Item Per Page', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
				'condition' => array(
					'view_more_button' => 'true',
				),
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
			'item_category',
			array(
				'label'   => esc_html__( 'Category', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_image_size',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Image Size', 'jet-elements' ),
				'default' => 'full',
				'options' => jet_elements_tools()->get_image_sizes(),
			)
		);

		/**
		 * Use Retina Image
		 * @var boolean
		 */
		$use_retina = apply_filters( 'jet-elements/portfolio/use-retina-image', false );

		if ( $use_retina ) {
			$repeater->add_control(
				'item_image_2x',
				array(
					'label'   => esc_html__( 'Retina Image', 'jet-elements' ),
					'type'    => Controls_Manager::MEDIA,
					'default' => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'dynamic' => array( 'active' => true ),
				)
			);
		}

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_button_text',
			array(
				'label'   => esc_html__( 'Link Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'More', 'jet-elements' ),
			)
		);

		$repeater->add_control(
			'item_button_url',
			array(
				'label'       => esc_html__( 'Link Url', 'jet-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '',
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'image_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #1', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #2', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #3', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #4', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #5', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #6', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => esc_html__( 'H1', 'jet-elements' ),
					'h2'   => esc_html__( 'H2', 'jet-elements' ),
					'h3'   => esc_html__( 'H3', 'jet-elements' ),
					'h4'   => esc_html__( 'H4', 'jet-elements' ),
					'h5'   => esc_html__( 'H5', 'jet-elements' ),
					'h6'   => esc_html__( 'H6', 'jet-elements' ),
					'div'  => esc_html__( 'div', 'jet-elements' ),
					'span' => esc_html__( 'span', 'jet-elements' ),
					'p'    => esc_html__( 'p', 'jet-elements' ),
				),
				'default' => 'h4',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->start_controls_section(
			'section_portfolio_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label' => esc_html__( 'Items Margin', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => [
					'size' => 10,
				],
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner']          => 'margin: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['list_container'] => 'margin: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			)
		);

		$this->end_controls_section();

		/**
		 * Filter Style Section
		 */
		$this->start_controls_section(
			'section_portfolio_overlay_style',
			array(
				'label'      => esc_html__( 'Filters', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'filters_container_styles_heading',
			array(
				'label'     => esc_html__( 'Filters Container Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filters_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
			)
		);

		$this->add_responsive_control(
			'filters_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filters_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['filters'],
			)
		);

		$this->add_responsive_control(
			'filters_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filters_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
			)
		);

		$this->add_control(
			'filters_items_styles_heading',
			array(
				'label'     => esc_html__( 'Filters Items Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'filters_items_separator_icon',
			array(
				'label'       => esc_html__( 'Separator Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-circle',
			)
		);

		$this->add_control(
			'filter_items_separator_color',
			array(
				'label' => esc_html__( 'Separator Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'filter_items_separator_size',
			array(
				'label' => esc_html__( 'Separator Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter_separator'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_items_aligment',
			array(
				'label'       => esc_html__( 'Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filters_wrap'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_filter_item' );

		$this->start_controls_tab(
			'tab_filter_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'filter_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
			)
		);

		$this->add_responsive_control(
			'filter_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filter_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['filter'],
			)
		);

		$this->add_responsive_control(
			'filter_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'filter_color_hover',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'filter_padding_hover',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_margin_hover',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filter_border_hover',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'filter_border_radius_hover',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_item_active',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'filter_color_active',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_typography_active',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['filter'] . '.active',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
			)
		);

		$this->add_responsive_control(
			'filter_padding_active',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_margin_active',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'filter_border_active',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
			)
		);

		$this->add_responsive_control(
			'filter_border_radius_active',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter'] . '.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter'] . '.active',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Content Style Section
		 */
		$this->start_controls_section(
			'section_portfolio_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'section_portfolio_content_wrapper_heading',
			array(
				'label'     => esc_html__( 'Container', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'content_container_alignment',
			array(
				'label'       => esc_html__( 'Content Position', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'preset' => array(
						'type-4',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'content_container_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			)
		);

		$this->add_responsive_control(
			'content_wrapper_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_wrapper_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'content_wrapper_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			)
		);

		$this->add_responsive_control(
			'content_wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			)
		);

		$this->add_control(
			'section_portfolio_image_heading',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['image_instance'],
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image_instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'section_portfolio_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
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
				'label'       => esc_html__( 'Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
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

		$this->add_control(
			'section_portfolio_category_heading',
			array(
				'label'     => esc_html__( 'Category', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_category_list',
			array(
				'label'        => esc_html__( 'Show Category list', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['category'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['category'],
			)
		);

		$this->add_responsive_control(
			'category_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['category'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['category'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_alignment',
			array(
				'label'       => esc_html__( 'Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
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
					'{{WRAPPER}} ' . $css_scheme['category'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'section_portfolio_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
			)
		);

		$this->add_responsive_control(
			'desc_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'desc_alignment',
			array(
				'label'       => esc_html__( 'Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
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
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'section_portfolio_button_heading',
			array(
				'label'     => esc_html__( 'Button', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
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
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
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
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
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
				'label'      => __( 'Margin', 'jet-elements' ),
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
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
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
				'label'       => esc_html__( 'Border', 'jet-elements' ),
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
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
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
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
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
				'label'      => __( 'Margin', 'jet-elements' ),
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
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
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
				'label'       => esc_html__( 'Border', 'jet-elements' ),
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

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'       => esc_html__( 'Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
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
					'{{WRAPPER}} ' . $css_scheme['button'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_text_alignment',
			array(
				'label'       => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => false,
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
					'{{WRAPPER}} ' . $css_scheme['button'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'section_portfolio_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_order',
			array(
				'label'   => esc_html__( 'Category Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['category'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_order',
			array(
				'label'   => esc_html__( 'Description Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_order',
			array(
				'label'   => esc_html__( 'Button Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['button'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_portfolio_more_button_style',
			array(
				'label'      => esc_html__( 'More Button', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_more_button_style' );

		$this->start_controls_tab(
			'tab_more_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'more_button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'more_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'more_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['view_more'],
			)
		);

		$this->add_responsive_control(
			'more_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'more_button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'more_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'more_button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['view_more'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'more_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['view_more'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_more_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'more_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'more_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'more_button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['view_more'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'more_button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'more_button_hover_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'more_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'more_button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'more_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['view_more'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Overlay Style Section
		 */
		$this->start_controls_section(
			'section_portfolio_cover_style',
			array(
				'label'      => esc_html__( 'Cover', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => array(
					'preset' => array(
						'type-2',
						'type-3',
					),
				),
			)
		);

		$this->add_control(
			'cover_icon',
			array(
				'label'       => esc_html__( 'Cover Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-search',
				'condition' => array(
					'preset' => array(
						'type-2',
						'type-3',
					),
				),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Cover Icon Style', 'jet-elements' ),
				'name'     => 'cover_icon_style',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cover'] . ' i',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'cover_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cover'],
			)
		);

		$this->add_responsive_control(
			'cover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cover_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$module_settings = $this->get_settings();

		$settings = array(
			'layoutType'    => $module_settings['layout_type'],
			'columns'       => $module_settings['columns'],
			'columnsTablet' => $module_settings['columns_tablet'],
			'columnsMobile' => $module_settings['columns_mobile'],
			'perPage'       => $module_settings['per_page'],
		);

		$settings = json_encode( $settings );

		return $settings;
	}

	/**
	 * Get loop image html
	 *
	 * @return html
	 */
	protected function __loop_image_item() {
		$item = $this->__processed_item;
		$params = [];

		if ( ! array_key_exists( 'item_image', $item ) ) {
			return false;
		}

		$image_item      = $item['item_image'];
		$image_item_size = isset( $item['item_image_size'] ) ? $item['item_image_size'] : 'full';
		$alt             = esc_attr( Control_Media::get_image_alt( $image_item ) );

		if ( ! empty( $image_item['id'] ) ) {
			$image_data = wp_get_attachment_image_src( $image_item['id'], $image_item_size );

			$params[] = $image_data[0];
			$params[] = $image_data[1];
			$params[] = $image_data[2];
		} else {
			$params[] = $image_item['url'];
			$params[] = 1200;
			$params[] = 800;
		}

		$srcset = '';

		if ( ! empty( $item[ 'item_image_2x' ] ) && ! empty( $item[ 'item_image_2x' ][ 'url' ] ) ) {
			$srcset = 'srcset="' . $item['item_image_2x'][ 'url' ] . ' 2x"';
		}

		return sprintf( '<img class="jet-portfolio__image-instance" src="%1$s" width="%2$s" height="%3$s" %4$s alt="%5$s">', $params[0], $params[1], $params[2], $srcset, $alt );
	}

	/**
	 * [get_justify_item_layout description]
	 * @return [type] [description]
	 */
	protected function get_justify_item_layout() {
		$item = $this->__processed_item;

		if ( ! array_key_exists( 'item_image', $item ) ) {
			return false;
		}

		$image_item      = $item['item_image'];
		$image_item_size = isset( $item['item_image_size'] ) ? $item['item_image_size'] : 'full';

		if ( ! empty( $item[ 'item_image_2x' ] ) && ! empty( $item[ 'item_image_2x' ][ 'url' ] ) ) {
			$image_item      = $item['item_image_2x'];
			$image_item_size = 'full';
		}

		$url = $image_item['url'];
		$width = 1200;
		$height = 800;
		$size = 'justify-size-1-4';

		if ( ! empty( $image_item['id'] ) ) {
			$image_data = wp_get_attachment_image_src( $image_item['id'], $image_item_size );

			$url = $image_data[0];
			$width = ! empty( $image_data[1] ) ? $image_data[1] : 1200;
			$height = ! empty( $image_data[2] ) ? $image_data[2] : 800;
		}

		$ratio = $width / $height;

		if ( $this->range_check( $ratio, 0, 1 ) ) {
			$size = 'justify-size-1-4';
		}

		if ( $this->range_check( $ratio, 1, 1.5 ) ) {
			$size = 'justify-size-2-4';
		}

		if ( $this->range_check( $ratio, 1.5, 2 ) ) {
			$size = 'justify-size-3-4';
		}

		if ( $this->range_check( $ratio, 2, 5 ) ) {
			$size = 'justify-size-4-4';
		}

		return $size;
	}

	/**
	 * [range_check description]
	 * @param  [type] $val [description]
	 * @param  [type] $min [description]
	 * @param  [type] $max [description]
	 * @return [type]      [description]
	 */
	public function range_check( $val, $min, $max ) {
		return ( $val >= $min && $val <= $max );
	}

	/**
	 * Get filters html
	 *
	 * @return html
	 */
	public function render_filters() {
		$html = '';

		$separator_html = '';

		$separator_icon = $this->get_settings_for_display( 'filters_items_separator_icon' );

		$category_list = $this->generate_category_data();

		if ( empty( $category_list ) ) {
			return false;
		}

		$all_label = $this->get_settings_for_display( 'all_filter_label' );
		$all_label = ( ! empty( $all_label ) ) ? $all_label : esc_html__( 'All', 'jet-elements' );

		$html .= sprintf( '<div class="jet-portfolio__filter-item active" data-slug="%1$s"><span>%2$s</span></div>', 'all', $all_label );

		if ( ! empty( $separator_icon ) ) {
			$separator_html = sprintf( '<i class="jet-portfolio__filter-item-separator %s"></i>', $separator_icon );
		}

		foreach ( $category_list as $slug => $category_name ) {
			$html .= sprintf( '%3$s<div class="jet-portfolio__filter-item" data-slug="%1$s"><span>%2$s</span></div>', $slug, $category_name, $separator_html );
		}

		echo sprintf( '<div class="jet-portfolio__filter"><div class="jet-portfolio__filter-list">%s</div></div>', $html );
	}

	/**
	 * [generate_category_data description]
	 * @return [type] [description]
	 */
	public function generate_category_data() {
		$category_list = [];

		$image_items = $this->get_settings_for_display( 'image_list' );
		$image_items = apply_filters( 'jet-elements/widget/loop-items', $image_items, 'image_list', $this );

		foreach ( $image_items as $key => $item ) {
			if ( ! empty( $item['item_category'] ) ) {
				$categories = explode( ',', $item['item_category'] );

				foreach ( $categories as $key => $category ) {
					$slug = sanitize_title( $category );

					if ( ! array_key_exists( $slug, $category_list ) ) {
						$category_list[ $slug ] = $category;
					}
				}
			}
		}

		return $category_list;
	}

	/**
	 * [get_item_slug description]
	 *
	 * @param  [type] $item_data [description]
	 * @return [type]            [description]
	 */
	public function get_item_slug( $item_data ) {
		$slug_list = array( 'all' );

		if ( empty( $item_data ) || empty( $item_data['item_category'] ) ) {
			return $slug_list;
		}

		$categories = explode( ',', $item_data['item_category'] );

		foreach ( $categories as $key => $category ) {
			$slug_list[] = sanitize_title( $category );
		}

		return $slug_list;
	}

	/**
	 * Get filters html
	 *
	 * @return html
	 */
	public function render_view_more_button() {
		$module_settings = $this->get_settings_for_display();
		$html = '';

		if ( ! filter_var( $module_settings['view_more_button'], FILTER_VALIDATE_BOOLEAN ) ) {
			return false;
		}

		$button_text = $module_settings['view_more_button_text'];

		$this->add_render_attribute( 'view_more_button', 'class', array(
			'elementor-button',
			'elementor-size-md',
			'jet-portfolio__view-more-button',
		) );

		$format = apply_filters( 'jet-elements/portfolio/more-button-format', '<div class="jet-portfolio__view-more hidden-status"><div %1$s>%2$s</div></div>' );

		echo sprintf( $format, $this->get_render_attribute_string( 'view_more_button' ), $button_text );
	}

	/**
	 * [get_item_category description]
	 *
	 * @return [type] [description]
	 */
	public function get_item_slug_list() {
		$item = $this->__processed_item;

		$slug = $this->get_item_slug( $item );

		return json_encode( $slug, JSON_FORCE_OBJECT );
	}

	/**
	 * [__generate_item_button description]
	 * @return [type] [description]
	 */
	public function __generate_item_button() {

		$settings = $this->__processed_item;

		$button_url  = $settings[ 'item_button_url' ];
		$button_text = $settings[ 'item_button_text' ];

		if ( empty( $button_text ) || empty( $button_url['url'] ) ) {
			return false;
		}

		$button_instance = 'button-instance-' . $this->item_counter;

		$this->add_render_attribute( $button_instance, 'class', array(
			'elementor-button',
			'elementor-size-md',
			'jet-portfolio__button',
		) );

		$this->add_render_attribute( $button_instance, 'href', $button_url['url'] );

		if ( $button_url['is_external'] ) {
			$this->add_render_attribute( $button_instance, 'target', '_blank' );
		}

		if ( ! empty( $button_url['nofollow'] ) ) {
			$this->add_render_attribute( $button_instance, 'rel', 'nofollow' );
		}

		$format = apply_filters( 'jet-elements/portfolio/action-button-format', '<a %1$s><span class="jet-portfolio__button-text">%2$s</span></a>' );

		return sprintf( $format, $this->get_render_attribute_string( $button_instance ), $button_text );
	}

	/**
	 * [__get_item_category description]
	 * @return [type] [description]
	 */
	public function __get_item_category() {
		$settings = $this->get_settings_for_display();
		$processed_item = $this->__processed_item;
		$category_html = '';

		if ( filter_var( $settings['show_category_list'], FILTER_VALIDATE_BOOLEAN ) ) {
			$category_html = $processed_item['item_category'];
		}

		return sprintf( '<h6 class="jet-portfolio__category">%s</h6>', $category_html );
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	protected function _content_template() {}

}
