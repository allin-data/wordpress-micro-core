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

class Jet_Blog_Text_Ticker extends Jet_Blog_Base {

	public function get_name() {
		return 'jet-blog-text-ticker';
	}

	public function get_title() {
		return esc_html__( 'Text Ticker', 'jet-blog' );
	}

	public function get_icon() {
		return 'jet-blog-43';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jquery-slick' );
	}

	protected function _register_controls() {


		$css_scheme = apply_filters(
			'jet-blog/text-ticker/css-scheme',
			array(
				'box'           => '.jet-text-ticker',
				'widget_title'  => '.jet-text-ticker__title',
				'current_date'  => '.jet-text-ticker__date',
				'typing_cursor' => '.jet-use-typing .jet-text-ticker__item-typed:after',
				'posts'         => '.jet-text-ticker__posts',
				'posts_thumb'   => '.jet-text-ticker__post-thumb',
				'posts_author'  => '.jet-text-ticker__post-author',
				'posts_date'    => '.jet-text-ticker__post-date',
				'posts_link'    => '.jet-text-ticker__item-typed',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-blog' ),
			)
		);

		$this->add_control(
			'block_title',
			array(
				'label'       => esc_html__( 'Widget Title', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title Tag', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'div',
				'options'   => array(
					'h1'  => esc_html__( 'H1', 'jet-blog' ),
					'h2'  => esc_html__( 'H2', 'jet-blog' ),
					'h3'  => esc_html__( 'H3', 'jet-blog' ),
					'h4'  => esc_html__( 'H4', 'jet-blog' ),
					'h5'  => esc_html__( 'H5', 'jet-blog' ),
					'h6'  => esc_html__( 'H6', 'jet-blog' ),
					'div' => esc_html__( 'DIV', 'jet-blog' ),
				),
			)
		);

		$this->add_control(
			'hide_title_tablet',
			array(
				'label'        => esc_html__( 'Hide Title On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_title_mobile',
			array(
				'label'        => esc_html__( 'Hide Title On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_current_date',
			array(
				'label'        => esc_html__( 'Show Current Date', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Date Format', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'l, j F, Y',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'jet-blog' ) ),
				'condition' => array(
					'show_current_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'date_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Date Icon', 'jet-blog' ),
				'default'   => 'fa fa-clock-o',
				'condition' => array(
					'show_current_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_date_tablet',
			array(
				'label'        => esc_html__( 'Hide Date On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition' => array(
					'show_current_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_date_mobile',
			array(
				'label'        => esc_html__( 'Hide Date On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_current_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'posts_num',
			array(
				'label'       => esc_html__( 'Posts Number to Show', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 4,
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'query_by',
			array(
				'label'   => esc_html__( 'Query Posts By', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => array(
					'all'      => esc_html__( 'All', 'jet-blog' ),
					'sticky'   => esc_html__( 'Sticky Posts', 'jet-blog' ),
					'category' => esc_html__( 'Categories', 'jet-blog' ),
					'post_tag' => esc_html__( 'Tags', 'jet-blog' ),
				),
			)
		);

		$this->add_control(
			'category_ids',
			array(
				'label'       => esc_html__( 'Get posts from categories:', 'jet-blog' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => jet_blog_tools()->get_terms( 'category' ),
				'label_block' => true,
				'multiple'    => true,
				'condition'   => array(
					'query_by' => 'category',
				),
			)
		);

		$this->add_control(
			'post_tag_ids',
			array(
				'label'       => esc_html__( 'Get posts from tags:', 'jet-blog' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => jet_blog_tools()->get_terms( 'post_tag' ),
				'label_block' => true,
				'multiple'    => true,
				'condition'   => array(
					'query_by' => 'post_tag',
				),
			)
		);

		$this->add_control(
			'exclude_ids',
			array(
				'type'        => 'text',
				'label_block' => true,
				'description' => esc_html__( 'If this is used with query posts by sticky, it will be ignored', 'jet-blog' ),
				'label'       => esc_html__( 'Exclude posts by IDs (eg. 10, 22, 19 etc.)', 'jet-blog' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'posts_offset',
			array(
				'label'       => esc_html__( 'Posts Offset', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
			)
		);

		$this->add_control(
			'meta_query',
			array(
				'label'        => esc_html__( 'Filter by Custom Field', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'meta_key',
			array(
				'type'        => 'text',
				'label_block' => true,
				'label'       => esc_html__( 'Custom Field Key', 'jet-blog' ),
				'default'     => '',
				'condition'   => array(
					'meta_query' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_value',
			array(
				'type'        => 'text',
				'label_block' => true,
				'label'       => esc_html__( 'Custom Field Value', 'jet-blog' ),
				'default'     => '',
				'condition'   => array(
					'meta_query' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => esc_html__( 'Show Post Thumbnail', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'thumb_size',
			array(
				'label'       => esc_html__( 'Thumbnail Size', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 50,
				'min'         => 40,
				'max'         => 100,
				'step'        => 1,
				'condition'   => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_thumbnail_tablet',
			array(
				'label'        => esc_html__( 'Hide Thumbnail On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_thumbnail_mobile',
			array(
				'label'        => esc_html__( 'Hide Thumbnail On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_author',
			array(
				'label'        => esc_html__( 'Show Post Author', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'show_author_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Author Icon', 'jet-blog' ),
				'default'   => 'fa fa-user',
				'condition' => array(
					'show_author' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_author_tablet',
			array(
				'label'        => esc_html__( 'Hide Author On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_author' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_author_mobile',
			array(
				'label'        => esc_html__( 'Hide Author On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_author' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => esc_html__( 'Show Post Date', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'post_date_format',
			array(
				'label'       => esc_html__( 'Post Date Format', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'H:s',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'jet-blog' ) ),
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_date_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Date Icon', 'jet-blog' ),
				'default'   => '',
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_post_date_tablet',
			array(
				'label'        => esc_html__( 'Hide Date On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_post_date_mobile',
			array(
				'label'        => esc_html__( 'Hide Date On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider',
			array(
				'label' => esc_html__( 'Slider Settings', 'jet-blog' ),
			)
		);

		$this->add_control(
			'typing_effect',
			array(
				'label'        => esc_html__( 'Typing Effect', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'typing_cursor',
			array(
				'label'       => esc_html__( 'Typing Cursor Char', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '_',
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['typing_cursor'] => 'content: "{{VALUE}}";',
				),
				'condition' => array(
					'typing_effect' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_autoplay',
			array(
				'label'        => esc_html__( 'Autoplay Posts', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'slider_autoplay_speed',
			array(
				'label'       => esc_html__( 'Autoplay Speed', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5000,
				'min'         => 1000,
				'max'         => 15000,
				'step'        => 1000,
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Show Controls Arrows', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'arrow_type',
			array(
				'label'       => esc_html__( 'Select Control Arrows Type', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'fa fa-angle-left',
				'options'     => jet_blog_tools()->get_available_prev_arrows_list(),
				'condition'   => array(
					'show_arrows'      => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label'      => esc_html__( 'Container', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'container_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Widget Title', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['widget_title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['widget_title'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'title_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['widget_title'],
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['widget_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'title_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['widget_title'],
			)
		);

		$this->add_responsive_control(
			'title_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['widget_title'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'title_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['widget_title'],
			)
		);

		$this->add_control(
			'show_title_pointer',
			array(
				'label'        => esc_html__( 'Show Title Pointer', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'title_pointer_color',
			array(
				'label'     => esc_html__( 'Pointer Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'render_type' => 'ui',
				'condition'   => array(
					'show_title_pointer' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_pointer_height',
			array(
				'label'      => esc_html__( 'Pointer Height', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 4,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'show_title_pointer' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_pointer_width',
			array(
				'label'      => esc_html__( 'Pointer Width', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 4,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'show_title_pointer' => 'yes',
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['widget_title'] . ':after' => 'position:absolute;content:"";width: 0; height: 0; border-style: solid; border-width: {{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}} 0 {{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}} {{SIZE}}{{UNIT}}; border-color: transparent transparent transparent {{title_pointer_color.VALUE}};left: 100%;top:50%;margin-top:-{{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}};z-index: 999;',
					'.rtl {{WRAPPER}} ' . $css_scheme['widget_title'] . ':after' => 'position:absolute;content:"";width: 0; height: 0; border-style: solid; border-width: {{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}} {{SIZE}}{{UNIT}} {{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}} 0; border-color: transparent {{title_pointer_color.VALUE}} transparent transparent;right: 100%;top:50%;margin-top:-{{title_pointer_height.SIZE}}{{title_pointer_height.UNIT}};z-index: 999;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_date_style',
			array(
				'label'      => esc_html__( 'Current Date', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'current_date_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_date'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_date_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['current_date'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'current_date_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_date'],
			)
		);

		$this->add_responsive_control(
			'current_date_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_date'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'current_date_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['current_date'],
			)
		);

		$this->add_responsive_control(
			'current_date_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_date'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'current_date_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_date'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_posts_style',
			array(
				'label'      => esc_html__( 'Posts', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'posts_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['posts'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'posts_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['posts'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'posts_thumb',
			array(
				'label'     => esc_html__( 'Thumbnail', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'thumb_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['posts_thumb'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'thumb_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['posts_thumb'],
			)
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['posts_thumb'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['posts_thumb'],
			)
		);

		$this->add_control(
			'posts_author',
			array(
				'label'     => esc_html__( 'Author', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_author_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['posts_author'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'posts_author_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['posts_author'],
			)
		);

		$this->add_control(
			'posts_date',
			array(
				'label'     => esc_html__( 'Date', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_date_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['posts_date'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'posts_date_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['posts_date'],
			)
		);

		$this->add_control(
			'posts_link',
			array(
				'label'     => esc_html__( 'Link', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_link_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['posts_link'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'posts_link_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['posts_link'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'posts_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['posts_link'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_paging_arrows',
			array(
				'label'      => esc_html__( 'Paging Arrows', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_prev',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			\Jet_Blog_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_style',
				'selector'       => '{{WRAPPER}} .jet-blog-arrow',
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
			'tab_next_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			\Jet_Blog_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_style_hover',
				'selector'       => '{{WRAPPER}} .jet-blog-arrow:hover',
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

		$this->add_control(
			'prev_arrow_position',
			array(
				'label'     => esc_html__( 'Prev Arrow Position', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'prev_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Postition by', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-blog' ),
					'bottom' => esc_html__( 'Bottom', 'jet-blog' ),
				),
			)
		);

		$this->add_responsive_control(
			'prev_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'prev_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'prev_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Postition by', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-blog' ),
					'right' => esc_html__( 'Right', 'jet-blog' ),
				),
			)
		);

		$this->add_responsive_control(
			'prev_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-prev' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'prev_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->add_control(
			'next_arrow_position',
			array(
				'label'     => esc_html__( 'Next Arrow Position', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'next_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Postition by', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-blog' ),
					'bottom' => esc_html__( 'Bottom', 'jet-blog' ),
				),
			)
		);

		$this->add_responsive_control(
			'next_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-next' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'next_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-next' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'next_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Postition by', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-blog' ),
					'right' => esc_html__( 'Right', 'jet-blog' ),
				),
			)
		);

		$this->add_responsive_control(
			'next_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'next_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-blog-arrow.jet-arrow-next' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';
		$this->__get_posts();

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	/**
	 * Query posts
	 *
	 * @return [type] [description]
	 */
	public function __get_posts() {

		$settings = $this->get_settings();
		$num      = $settings['posts_num'];
		$exclude  = ! empty( $settings['exclude_ids'] ) ? $settings['exclude_ids'] : '';
		$offset   = ! empty( $settings['posts_offset'] ) ? absint( $settings['posts_offset'] ) : 0;

		$query_args = array(
			'posts_per_page'      => $num,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'paged'               => 1,
		);

		$tax = $settings['query_by'];

		if ( isset( $settings[ $tax . '_ids' ] ) && is_array( $settings[ $tax . '_ids' ] ) ) {
			$ids = array_filter( $settings[ $tax . '_ids' ] );
		} else {
			$ids = array();
		}

		if ( 'sticky' === $tax ) {
			$query_args['post__in'] = get_option( 'sticky_posts' );
		} elseif ( 'all' !== $tax && ! empty( $ids ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $ids,
				),
			);
		}

		if ( ! empty( $exclude ) && empty( $query_args['post__in'] ) ) {
			$exclude_ids                = explode( ',', str_replace( ' ', '', $exclude ) );
			$query_args['post__not_in'] = $exclude_ids;
		}

		if ( $offset ) {
			$query_args['offset'] = $offset;
		}

		if ( isset( $settings['meta_query'] ) && 'yes' === $settings['meta_query'] ) {

			$meta_key   = ! empty( $settings['meta_key'] ) ? esc_attr( $settings['meta_key'] ) : false;
			$meta_value = ! empty( $settings['meta_value'] ) ? esc_attr( $settings['meta_value'] ) : '';

			if ( ! empty( $meta_key ) ) {
				$query_args['meta_key']   = $meta_key;
				$query_args['meta_value'] = $meta_value;
			}

		}

		/**
		 * Filter query arguments before posts requested
		 *
		 * @var array
		 */
		$query_args = apply_filters( 'jet-blog/text-ticker/query-args', $query_args, $this );

		$query = new \WP_Query( $query_args );
		$posts = ! empty( $query->posts ) ? $query->posts : array();

		$this->__set_query( $posts );

	}

	/**
	 * Slider attributes.
	 *
	 * @return void
	 */
	public function __slider_atts() {

		$slider_attributes = array(
			'slidesToShow'   => 1,
			'slidesToScroll' => 1,
			'fade'           => true,
		);

		$settings                           = $this->get_settings();
		$slider_attributes['arrows']        = filter_var( $settings['show_arrows'], FILTER_VALIDATE_BOOLEAN );
		$slider_attributes['prevArrow']     = jet_blog_tools()->get_carousel_arrow( $settings['arrow_type'], 'prev' );
		$slider_attributes['nextArrow']     = jet_blog_tools()->get_carousel_arrow( $settings['arrow_type'], 'next' );
		$autoplay                           = isset( $settings['slider_autoplay'] ) ? $settings['slider_autoplay'] : true;
		$slider_attributes['autoplay']      = filter_var( $settings['slider_autoplay'], FILTER_VALIDATE_BOOLEAN );
		$slider_attributes['autoplaySpeed'] = ! empty( $settings['slider_autoplay_speed'] ) ? absint( $settings['slider_autoplay_speed'] ) : 5000;

		$slider_attributes = apply_filters( 'jet-blog/text-ticker/slider-settings', $slider_attributes );

		printf( "data-slider-atts='%s'", json_encode( $slider_attributes ) );

	}

	/**
	 * Show post meta
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __post_author( $settings ) {

		$show = isset( $settings['show_author'] ) ? $settings['show_author'] : '';

		if ( 'yes' !== $show ) {
			return;
		}

		$icon        = isset( $settings['show_author_icon'] ) ? $settings['show_author_icon'] : '';
		$icon_html   = '';
		$icon_format = apply_filters(
			'jet-blog/text-ticker/post-author/icon-format',
			'<i class="jet-text-ticker__post-author-icon %s"></i>'
		);

		if ( $icon ) {
			$icon_html = sprintf( $icon_format, $icon );
		}

		$hide_classes = '';

		if ( ! empty( $settings['hide_author_tablet'] ) && 'yes' === $settings['hide_author_tablet'] ) {
			$hide_classes .= ' jet-blog-hidden-tablet';
		}

		if ( ! empty( $settings['hide_author_mobile'] ) && 'yes' === $settings['hide_author_mobile'] ) {
			$hide_classes .= ' jet-blog-hidden-mobile';
		}

		printf(
			'<div class="jet-text-ticker__post-author%3$s">%1$s %2$s</div>',
			$icon_html,
			get_the_author(),
			$hide_classes
		);

	}

	/**
	 * Show post meta
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __post_date( $settings ) {

		$show = isset( $settings['show_date'] ) ? $settings['show_date'] : '';

		if ( 'yes' !== $show ) {
			return;
		}

		$icon        = isset( $settings['show_date_icon'] ) ? $settings['show_date_icon'] : '';
		$icon_html   = '';
		$icon_format = apply_filters(
			'jet-blog/text-ticker/post-date/icon-format',
			'<i class="jet-text-ticker__post-date-icon %s"></i>'
		);

		if ( $icon ) {
			$icon_html = sprintf( $icon_format, $icon );
		}

		$date_format = isset( $settings['post_date_format'] ) ? $settings['post_date_format'] : 'H:i';

		$hide_classes = '';

		if ( ! empty( $settings['hide_post_date_tablet'] ) && 'yes' === $settings['hide_post_date_tablet'] ) {
			$hide_classes .= ' jet-blog-hidden-tablet';
		}

		if ( ! empty( $settings['hide_post_date_mobile'] ) && 'yes' === $settings['hide_post_date_mobile'] ) {
			$hide_classes .= ' jet-blog-hidden-mobile';
		}

		printf(
			'<div class="jet-text-ticker__post-date%3$s">%1$s %2$s</div>',
			$icon_html,
			get_the_time( $date_format ),
			$hide_classes
		);

	}

	/**
	 * Get widget title
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __get_widget_title( $settings ) {

		if ( empty( $settings['block_title'] ) ) {
			return;
		}

		$tag = ! empty( $settings['title_tag'] ) ? esc_attr( $settings['title_tag'] ) : 'div';

		$hide_classes = '';

		if ( ! empty( $settings['hide_title_tablet'] ) && 'yes' === $settings['hide_title_tablet'] ) {
			$hide_classes .= ' jet-blog-hidden-tablet';
		}

		if ( ! empty( $settings['hide_title_mobile'] ) && 'yes' === $settings['hide_title_mobile'] ) {
			$hide_classes .= ' jet-blog-hidden-mobile';
		}

		printf(
			'<%1$s class="jet-text-ticker__title%3$s">%2$s</%1$s>',
			$tag, $settings['block_title'], $hide_classes
		);

	}

	/**
	 * Show current date id allowed
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __get_current_date( $settings ) {

		if ( empty( $settings['show_current_date'] ) ) {
			return;
		}

		$format = ! empty( $settings['date_format'] ) ? esc_attr( $settings['date_format'] ) : 'l, j F, Y';
		$icon   = ! empty( $settings['date_icon'] ) ? esc_attr( $settings['date_icon'] ) : '';

		$icon_html   = '';
		$icon_format = apply_filters(
			'jet-blog/text-ticker/current-date/icon-format',
			'<i class="jet-text-ticker__date-icon %s"></i>'
		);

		if ( $icon ) {
			$icon_html = sprintf( $icon_format, $icon );
		}

		$result_format = apply_filters(
			'jet-blog/text-ticker/current-date/format',
			'<div class="jet-text-ticker__date%3$s">%1$s%2$s</div>'
		);

		$hide_classes = '';

		if ( ! empty( $settings['hide_date_tablet'] ) && 'yes' === $settings['hide_date_tablet'] ) {
			$hide_classes .= ' jet-blog-hidden-tablet';
		}

		if ( ! empty( $settings['hide_date_mobile'] ) && 'yes' === $settings['hide_date_mobile'] ) {
			$hide_classes .= ' jet-blog-hidden-mobile';
		}

		printf( $result_format, $icon_html, date_i18n( $format ), $hide_classes );
	}

	/**
	 * Show post thumbnail if allowed
	 *
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __post_thumbnail( $settings ) {

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$show_thumbnail = isset( $settings['show_thumbnail'] ) ? $settings['show_thumbnail'] : '';

		if ( 'yes' !== $show_thumbnail ) {
			return;
		}

		$size = isset( $settings['thumb_size'] ) ? absint( $settings['thumb_size'] ) : 50;

		$class = 'jet-text-ticker__post-thumb';

		if ( ! empty( $settings['hide_thumbnail_tablet'] ) && 'yes' === $settings['hide_thumbnail_tablet'] ) {
			$class .= ' jet-blog-hidden-tablet';
		}

		if ( ! empty( $settings['hide_thumbnail_mobile'] ) && 'yes' === $settings['hide_thumbnail_mobile'] ) {
			$class .= ' jet-blog-hidden-mobile';
		}

		the_post_thumbnail(
			array( $size, $size ),
			array(
				'class' => $class,
				'alt'   => esc_attr( get_the_title() ),
				'title' => esc_attr( get_the_title() ),
			)
		);

	}

}
