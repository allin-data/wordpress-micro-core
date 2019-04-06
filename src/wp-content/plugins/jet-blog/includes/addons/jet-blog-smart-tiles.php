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

class Jet_Blog_Smart_Tiles extends Jet_Blog_Base {

	public $__current_post_index = 0;
	public $__current_posts_num  = 0;

	public function get_name() {
		return 'jet-blog-smart-tiles';
	}

	public function get_title() {
		return esc_html__( 'Smart Posts Tiles', 'jet-blog' );
	}

	public function get_icon() {
		return 'jet-blog-42';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jquery-slick' );
	}

	protected function _register_controls() {

		$layout_data       = $this->__layout_data();
		$available_layouts = array();
		$has_rows          = array();

		foreach ( $layout_data as $key => $data ) {
			$available_layouts[ $key ] = array(
				'title' => $data['label'],
				'icon'  => $data['icon'],
			);

			if ( true === $data['has_rows'] ) {
				$has_rows[] = $key;
			}

		}

		$css_scheme = apply_filters(
			'jet-blog/smart-tiles/css-scheme',
			array(
				'slide'      => '.jet-smart-tiles-slide__wrap',
				'box'        => '.jet-smart-tiles__box',
				'title'      => '.jet-smart-tiles__box-title',
				'excerpt'    => '.jet-smart-tiles__box-excerpt',
				'meta'       => '.jet-smart-tiles__meta',
				'meta_item'  => '.jet-smart-tiles__meta-item',
				'terms'      => '.jet-smart-tiles__terms',
				'terms_link' => '.jet-smart-tiles__terms-link',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-blog' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'       => esc_html__( 'Layout', 'jet-blog' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => '2-1-2',
				'options'     => $available_layouts,
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'rows_num',
			array(
				'label'     => esc_html__( 'Rows Number', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => jet_blog_tools()->get_select_range( 3 ),
				'condition' => array(
					'layout' => array( '2-x', '3-x', '4-x' ),
				),
			)
		);

		$this->add_responsive_control(
			'min_height',
			array(
				'label'      => esc_html__( 'Min Height', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'render_type' => 'template',
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['slide'] => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$main_img_selectors = apply_filters( 'jet-blog/smart-tiles/main-image-selectors',array(
			'{{WRAPPER}} .jet-smart-tiles-slide__wrap.layout-2-1-2'   => 'grid-template-columns: 1fr {{SIZE}}{{UNIT}} 1fr; -ms-grid-columns: 1fr {{SIZE}}{{UNIT}} 1fr;',
			'{{WRAPPER}} .jet-smart-tiles-slide__wrap.layout-1-1-2-h' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
			'{{WRAPPER}} .jet-smart-tiles-slide__wrap.layout-1-1-2-v' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
			'{{WRAPPER}} .jet-smart-tiles-slide__wrap.layout-1-2'     => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr',
			'{{WRAPPER}} .jet-smart-tiles-slide__wrap.layout-1-2-2'   => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
		) );

		$main_img_conditions = apply_filters( 'jet-blog/smart-tiles/main-image-conditions',array(
			'2-1-2',
			'1-1-2-h',
			'1-1-2-v',
			'1-2',
			'1-2-2',
		) );

		$this->add_control(
			'main_img_width',
			array(
				'label'      => esc_html__( 'Main Box Width', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => $main_img_selectors,
				'condition' => array(
					'layout' => $main_img_conditions,
				),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'type'      => 'select',
				'label'     => esc_html__( 'Image Size', 'jet-blog' ),
				'default'   => 'full',
				'options'   => jet_blog_tools()->get_image_sizes(),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_terms',
			array(
				'label'        => esc_html__( 'Show Post Terms', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'show_terms_tax',
			array(
				'label'     => esc_html__( 'Show Terms From', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'category',
				'options'   => jet_blog_tools()->get_post_taxonomies(),
				'condition' => array(
					'show_terms' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_terms_num',
			array(
				'label'   => esc_html__( 'Max Terms to Show', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'all' => esc_html__( 'All', 'jet-blog' ),
					'1'   => 1,
					'2'   => 2,
					'3'   => 3,
					'4'   => 4,
				),
				'condition' => array(
					'show_terms' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_length',
			array(
				'label'       => esc_html__( 'Title Max Length (Words)', 'jet-blog' ),
				'description' => esc_html__( 'Set 0 to show full title', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'max'         => 15,
				'step'        => 1,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'       => esc_html__( 'Excerpt Length', 'jet-blog' ),
				'description' => esc_html__( 'Set 0 to hide excerpt or -1 to show full excerpt', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
				'min'         => -1,
				'max'         => 200,
				'step'        => 1,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'excerpt_trimmed_ending',
			array(
				'label'   => esc_html__( 'Excerpt Trimmed Ending', 'jet-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '...',
			)
		);

		$this->add_control(
			'excerpt_on_hover',
			array(
				'label'        => esc_html__( 'Show Excerpt on Small Boxes Only on Hover', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'show_meta',
			array(
				'label'        => esc_html__( 'Post Meta', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
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
				'condition'   => array(
					'show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_author_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Author Icon', 'jet-blog' ),
				'default'   => 'fa fa-user',
				'condition' => array(
					'show_meta'   => 'yes',
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
				'condition'   => array(
					'show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_date_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Date Icon', 'jet-blog' ),
				'default'   => 'fa fa-calendar',
				'condition' => array(
					'show_meta' => 'yes',
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_comments',
			array(
				'label'        => esc_html__( 'Show Post Comments', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'   => array(
					'show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_comments_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Comments Icon', 'jet-blog' ),
				'default'   => 'fa fa-comments',
				'condition' => array(
					'show_meta'     => 'yes',
					'show_comments' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query & Controls', 'jet-blog' ),
			)
		);

		$this->add_control(
			'is_archive_template',
			array(
				'label'        => esc_html__( 'Use as Archive Template', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => jet_blog_tools()->get_archive_control_desc(),
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'use_custom_query',
			array(
				'label'        => esc_html__( 'Use Custom Query', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'   => array(
					'is_archive_template!' => 'yes',
				),
			)
		);

		$custom_query_link = sprintf(
			'<a href="https://crocoblock.com/wp-query-generator/" target="_blank">%s</a>',
			__( 'Generate custom query', 'jet-blog' )
		);

		$this->add_control(
			'custom_query',
			array(
				'type'        => Controls_Manager::TEXTAREA,
				'label'       => esc_html__( 'Set custom query', 'jet-blog' ),
				'default'     => '',
				'description' => $custom_query_link,
				'condition'   => array(
					'is_archive_template!' => 'yes',
					'use_custom_query'     => 'true',
				),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Post Type', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => jet_blog_tools()->get_post_types(),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
				),
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
					'category' => esc_html__( 'Categories', 'jet-blog' ),
					'post_tag' => esc_html__( 'Tags', 'jet-blog' ),
					'ids'      => esc_html__( 'IDs', 'jet-blog' ),
				),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => 'post',
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
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => 'post',
					'query_by'             => 'category',
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
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => 'post',
					'query_by'             => 'post_tag',
				),
			)
		);

		$this->add_control(
			'include_ids',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set comma seprated IDs list (10, 22, 19 etc.)', 'jet-blog' ),
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => 'post',
					'query_by'             => 'ids',
				),
			)
		);

		$this->add_control(
			'custom_query_by',
			array(
				'label'   => esc_html__( 'Query Custom Posts By', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all' => esc_html__( 'All', 'jet-blog' ),
					'ids' => esc_html__( 'IDs', 'jet-blog' ),
				),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type!'           => 'post',
				),
			)
		);

		$this->add_control(
			'post_ids',
			array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set comma seprated IDs list (10, 22, 19 etc.)', 'jet-blog' ),
				'default'   => '',
				'label_block' => true,
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type!'           => 'post',
					'custom_query_by'      => 'ids',
				),
			)
		);

		$this->add_control(
			'exclude_ids',
			array(
				'type'        => 'text',
				'label_block' => true,
				'description' => esc_html__( 'If this is used with query posts by ID, it will be ignored', 'jet-blog' ),
				'label'       => esc_html__( 'Exclude posts by IDs (eg. 10, 22, 19 etc.)', 'jet-blog' ),
				'default'     => '',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
				),
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
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
				),
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
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
				),
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
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'meta_query'           => 'yes',
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
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'meta_query'           => 'yes',
				),
			)
		);

		$this->add_control(
			'carousel_enabled',
			array(
				'label'        => esc_html__( 'Enable Carousel', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'default'      => '',
				'condition' => array(
					'is_archive_template!' => 'yes',
				),
			)
		);

		$this->add_control(
			'slides_num',
			array(
				'label'       => esc_html__( 'Number of Slides', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
				'condition'   => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'carousel_enabled'     => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'condition'    => array(
					'carousel_enabled'     => 'yes',
					'is_archive_template!' => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'jet-blog' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'carousel_enabled'     => 'yes',
					'autoplay'             => 'yes',
					'is_archive_template!' => 'yes',
				),
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
				'default'      => '',
				'condition'   => array(
					'is_archive_template!' => 'yes',
					'carousel_enabled'     => 'yes',
				),
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
					'is_archive_template!' => 'yes',
					'carousel_enabled'     => 'yes',
					'show_arrows'          => 'yes',
				),
			)
		);

		$this->add_control(
			'show_arrows_on_hover',
			array(
				'label'        => esc_html__( 'Show Arrows Only on Hover', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'is_archive_template!' => 'yes',
					'carousel_enabled'     => 'yes',
					'show_arrows'          => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_fields',
			array(
				'label' => esc_html__( 'Custom Fields', 'jet-blog' ),
			)
		);

		$this->__add_meta_controls( 'title_related', esc_html__( 'Before/After Title', 'jet-blog' ) );

		$this->__add_meta_controls( 'content_related', esc_html__( 'Before/After Content', 'jet-blog' ) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label'      => esc_html__( 'Box', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'boxes_gap',
			array(
				'label'      => esc_html__( 'Gap Between Boxes', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['slide'] => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['box'] => 'margin-bottom: {{SIZE}}{{UNIT}};',

				),
			)
		);

		$this->add_responsive_control(
			'boxes_padding',
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
				'name'           => 'boxes_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_responsive_control(
			'boxes_border_radius',
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
				'name'     => 'boxes_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_control(
			'boxes_overlay_styles',
			array(
				'label'     => esc_html__( 'Box Overlay', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'boxes_overlay_background_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'boxes_overlay_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':hover:before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'boxes_title_styles',
			array(
				'label'     => esc_html__( 'Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'boxes_title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-smart-tiles:hover ' . $css_scheme['title']=> 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'boxes_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blog' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blog' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blog' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'boxes_main_title_styles',
			array(
				'label'     => esc_html__( 'Main Box Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_main_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .layout-2-1-2 > div:nth-child( 3 ) ' . $css_scheme['title']   => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-1-2-h > div:nth-child( 1 ) ' . $css_scheme['title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-1-2-v > div:nth-child( 1 ) ' . $css_scheme['title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-2 > div:nth-child( 1 ) ' . $css_scheme['title']     => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-2-2 > div:nth-child( 1 ) ' . $css_scheme['title']   => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'boxes_main_title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .layout-2-1-2 > div:nth-child( 3 ):hover ' . $css_scheme['title']   => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-1-2-h > div:nth-child( 1 ):hover ' . $css_scheme['title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-1-2-v > div:nth-child( 1 ):hover ' . $css_scheme['title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-2 > div:nth-child( 1 ):hover ' . $css_scheme['title']     => 'color: {{VALUE}}',
					'{{WRAPPER}} .layout-1-2-2 > div:nth-child( 1 ):hover ' . $css_scheme['title']   => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_main_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .layout-2-1-2 > div:nth-child( 3 ) ' . $css_scheme['title'] . ', {{WRAPPER}} .layout-1-1-2-h > div:nth-child( 1 ) ' . $css_scheme['title'] . ', {{WRAPPER}} .layout-1-1-2-v > div:nth-child( 1 ) ' . $css_scheme['title'] . ', {{WRAPPER}} .layout-1-2 > div:nth-child( 1 ) ' . $css_scheme['title'] . ', {{WRAPPER}} .layout-1-2-2 > div:nth-child( 1 ) ' . $css_scheme['title'],
			)
		);

		$this->add_control(
			'boxes_text_style',
			array(
				'label'     => esc_html__( 'Post Text', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['excerpt'],
			)
		);

		$this->add_responsive_control(
			'boxes_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blog' ),
						'icon'  => 'fa fa-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blog' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blog' ),
						'icon'  => 'fa fa-arrow-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'      => esc_html__( 'Meta', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'meta_icon_size',
			array(
				'label'      => esc_html__( 'Meta Icon Size', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_item'] . ' .jet-smart-tiles__meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'meta_icon_gap',
			array(
				'label'      => esc_html__( 'Meta Icon Gap', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_item'] . ' .jet-smart-tiles__meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'meta_bg',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
			)
		);

		$this->add_responsive_control(
			'meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'meta_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'meta_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-blog' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blog' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_divider',
			array(
				'label'     => esc_html__( 'Meta Divider', 'jet-blog' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
				),
			)
		);

		$this->add_control(
			'meta_divider_gap',
			array(
				'label'      => esc_html__( 'Divider Gap', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_terms_link_style',
			array(
				'label'      => esc_html__( 'Terms Links', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_terms_link_style' );

		$this->start_controls_tab(
			'tab_terms_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'terms_link_bg_color',
			array(
				'label'     => _x( 'Color', 'Background Control', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => _x( 'Background Color', 'Background Control', 'jet-blog' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'terms_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'terms_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['terms_link'],
			)
		);

		$this->add_control(
			'terms_link_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] . '' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'terms_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'terms_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'terms_link_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['terms_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'terms_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['terms_link'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_terms_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'terms_link_hover_bg_color',
			array(
				'label'     => _x( 'Color', 'Background Control', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => _x( 'Background Color', 'Background Control', 'jet-blog' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'terms_link_hover_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'terms_link_hover_typography',
				'label' => esc_html__( 'Typography', 'jet-blog' ),
				'selector' => '{{WRAPPER}}  ' . $css_scheme['terms_link'] . ':hover',
			)
		);

		$this->add_control(
			'terms_link_hover_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'terms_link_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'terms_link_hover_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'terms_link_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['terms_link'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'terms_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['terms_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'terms_link_alignment_h',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'   => esc_html__( 'Left', 'jet-blog' ),
					'center' => esc_html__( 'Center', 'jet-blog' ),
					'right'  => esc_html__( 'Right', 'jet-blog' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['terms'] => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'terms_link_alignment_v',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'space-between',
				'options' => array(
					'space-between' => esc_html__( 'Top', 'jet-blog' ),
					'flex-end'   => esc_html__( 'Bottom', 'jet-blog' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'align-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_arrows',
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

		$this->start_controls_section(
			'section_custom_fields_styles',
			array(
				'label'      => esc_html__( 'Custom Fields', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_meta_style_controls(
			'title_related',
			esc_html__( 'Before/After Title', 'jet-blog' ),
			'jet-title-fields'
		);

		$this->__add_meta_style_controls(
			'content_related',
			esc_html__( 'Before/After Content', 'jet-blog' ),
			'jet-content-fields'
		);

		$this->end_controls_section();

	}

	/**
	 * Returns information about available layouts
	 *
	 * @return array
	 */
	public function __layout_data() {

		return apply_filters( 'jet-blog/samrt-tiles/available_layouts', array(
			'2-1-2'   => array(
				'label'    => esc_html__( 'Layout 1 (5 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-1',
				'num'      => 5,
				'has_rows' => false,
			),
			'1-1-2-h' => array(
				'label'    => esc_html__( 'Layout 2 (4 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-2',
				'num'      => 4,
				'has_rows' => false,
			),
			'1-1-2-v' => array(
				'label'    => esc_html__( 'Layout 3 (4 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-3',
				'num'      => 4,
				'has_rows' => false,
			),
			'1-2'     => array(
				'label'    => esc_html__( 'Layout 4 (3 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-4',
				'num'      => 3,
				'has_rows' => false,
			),
			'2-3-v'   => array(
				'label'    => esc_html__( 'Layout 5 (5 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-5',
				'num'      => 5,
				'has_rows' => false,
			),
			'1-2-2'   => array(
				'label'    => esc_html__( 'Layout 6 (5 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-6',
				'num'      => 5,
				'has_rows' => false,
			),
			'2-x'   => array(
				'label'    => esc_html__( 'Layout 7 (2, 4, 6 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-7',
				'num'      => 2,
				'has_rows' => false,
			),
			'3-x'   => array(
				'label'    => esc_html__( 'Layout 8 (3, 6, 9 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-8',
				'num'      => 3,
				'has_rows' => false,
			),
			'4-x'   => array(
				'label'    => esc_html__( 'Layout 9 (4, 8, 12 posts)', 'jet-blog' ),
				'icon'     => 'jet-blog jet-blog-layout-9',
				'num'      => 4,
				'has_rows' => false,
			),

		) );

	}

	/**
	 * Get style attribute with post background.
	 *
	 * @return void|null
	 */
	public function __get_post_bg_attr() {

		$settings = $this->get_settings();

		if ( has_post_thumbnail() ) {
			$thumb_size = isset( $settings['image_size'] ) ? $settings['image_size'] : 'full';
			$thumb      = get_the_post_thumbnail_url( null, $thumb_size );
		} else {
			$thumb = sprintf( '//via.placeholder.com/900x600?text=%s', str_replace( ' ', '+', get_the_title() ) );
		}

		printf( 'style="background-image:url(\'%s\')"', $thumb );

	}

	/**
	 * Slider attributes.
	 *
	 * @return void
	 */
	public function __slider_atts() {

		$slider_attributes                  = array( 'adaptiveHeight' => true );
		$settings                           = $this->get_settings();
		$slider_attributes['arrows']        = filter_var( $settings['show_arrows'], FILTER_VALIDATE_BOOLEAN );
		$slider_attributes['prevArrow']     = jet_blog_tools()->get_carousel_arrow( $settings['arrow_type'], 'prev' );
		$slider_attributes['nextArrow']     = jet_blog_tools()->get_carousel_arrow( $settings['arrow_type'], 'next' );
		$slider_attributes['autoplay']      = isset( $settings['autoplay'] ) ? filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ) : false;
		$slider_attributes['autoplaySpeed'] = ! empty( $settings['autoplay_speed'] ) ? absint( $settings['autoplay_speed'] ) : 5000;
		$slider_attributes['rtl']           = is_rtl();

		$slider_attributes = apply_filters( 'jet-blog/smart-tiles/slider-settings', $slider_attributes );

		printf( "data-slider-atts='%s'", json_encode( $slider_attributes ) );

	}

	public function __get_posts_num( $settings ) {

		if ( 0 === $this->__current_posts_num ) {

			$layout         = $settings['layout'];
			$layouts_data   = $this->__layout_data();
			$current_layout = isset( $layouts_data[ $layout ] ) ? $layouts_data[ $layout ] : false;

			if ( ! $current_layout ) {
				return $this->__current_posts_num;
			}

			$this->__current_posts_num = $current_layout['num'];

			if ( $this->is_multirow_layout( $layout ) ) {
				$rows = isset( $settings['rows_num'] ) ? absint( $settings['rows_num'] ) : 1;
				$this->__current_posts_num = $this->__current_posts_num * $rows;
			}

		}

		return $this->__current_posts_num;

	}

	/**
	 * Check if current layout is multirow layout
	 *
	 * @param  string  $layout Layout name.
	 * @return boolean
	 */
	public function is_multirow_layout( $layout ) {
		$multirow_layouts = apply_filters( 'jet-blog/smart-tiles/multirow-layouts', array( '2-x', '3-x', '4-x' ) );
		return in_array( $layout, $multirow_layouts );
	}

	public function __maybe_open_slide_wrapper( $settings ) {

		$num = $this->__get_posts_num( $settings );

		if ( ! $num ) {
			return;
		}

		$classes   = array( 'jet-smart-tiles-slide__wrap' );
		$classes[] = 'layout-' . $settings['layout'];

		if ( $this->is_multirow_layout( $settings['layout'] ) ) {
			$rows      = isset( $settings['rows_num'] ) ? absint( $settings['rows_num'] ) : 1;
			$classes[] = 'rows-' . $rows;
		}

		if ( 0 === ( $this->__current_post_index % $num ) ) {
			printf( '<div class="jet-smart-tiles-slide"><div class="%s">', implode( ' ', $classes ) );
		}

	}

	public function __maybe_close_slide_wrapper( $settings ) {

		$num = $this->__get_posts_num( $settings );

		if ( ! $num ) {
			return;
		}

		if ( 0 === ( ( $this->__current_post_index + 1 ) % $num ) ) {
			echo '</div></div>';
		} elseif ( $this->__current_post_index + 1 === count( $this->__query ) ) {
			echo '</div></div>';
		}

		$this->__current_post_index++;

	}

	public function __reset_data() {
		wp_reset_postdata();
		$this->__current_post_index = 0;
	}

	public function get_default_query_args( $settings = array() ) {

		$num       = $this->__get_posts_num( $settings );
		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';
		$exclude   = ! empty( $settings['exclude_ids'] ) ? $settings['exclude_ids'] : '';
		$include   = ! empty( $settings['include_ids'] ) ? $settings['include_ids'] : '';
		$offset    = ! empty( $settings['posts_offset'] ) ? absint( $settings['posts_offset'] ) : 0;

		if ( 'yes' === $settings['carousel_enabled'] ) {
			$slides = ( 0 !== absint( $settings['slides_num'] ) ) ? absint( $settings['slides_num'] ) : 1;
			$num = $slides * $num;
		}

		if ( ! $num ) {
			return;
		}

		$query_args = array(
			'posts_per_page'      => $num,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'paged'               => 1,
			'post_type'           => $post_type,
		);

		$tax = $settings['query_by'];

		if ( isset( $settings[ $tax . '_ids' ] ) && is_array( $settings[ $tax . '_ids' ] ) ) {
			$ids = array_filter( $settings[ $tax . '_ids' ] );
		} else {
			$ids = array();
		}

		if ( 'all' !== $tax && ! empty( $ids ) && 'post' === $post_type ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $ids,
				),
			);
		}

		if ( 'post' !== $post_type && ! empty( $settings['post_ids'] ) ) {
			$post_ids = explode( ',', str_replace( ' ', '', $settings['post_ids'] ) );
			$query_args['post__in'] = $post_ids;
		}

		if ( 'post' === $post_type && 'ids' === $tax && ! empty( $include ) ) {
			$include_ids = explode( ',', str_replace( ' ', '', $include ) );
			$query_args['post__in'] = $include_ids;
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

		return $query_args;

	}

	/**
	 * Get custom query args
	 *
	 * @return array
	 */
	public function get_custom_query_args( $settings = array() ) {

		$query_args = $settings['custom_query'];
		$query_args = json_decode( $query_args, true );

		if ( ! $query_args ) {
			$query_args = array();
		}

		return $query_args;

	}

	/**
	 * Get posts.
	 *
	 * @return void
	 */
	public function __get_posts() {

		$settings = $this->get_settings();

		if ( isset( $settings['is_archive_template'] ) && 'yes' === $settings['is_archive_template'] ) {

			if ( $this->_is_template_preview() ){
				$this->__set_query( get_posts( array(
					'post_type'   => 'post',
					'numberposts' => get_option( 'posts_per_page', 10 ),
				) ) );
			} else {
				global $wp_query;
				$this->__set_query( $wp_query->posts );
			}

			return;

		}

		if ( isset( $settings['use_custom_query'] ) && 'true' === $settings['use_custom_query'] ) {
			$query_args = $this->get_custom_query_args( $settings );
		} else {
			$query_args = $this->get_default_query_args( $settings );
		}

		/**
		 * Filter query arguments before posts requested
		 *
		 * @var array
		 */
		$query_args = apply_filters( 'jet-blog/smart-tiles/query-args', $query_args, $this );

		$query = new \WP_Query( $query_args );
		$posts = ! empty( $query->posts ) ? $query->posts : array();

		$this->__set_query( $posts );

	}

	/**
	 * Show post categories depends on settings
	 *
	 * @return void|null
	 */
	public function __post_terms() {

		$settings = $this->get_settings();
		$show     = isset( $settings['show_terms'] ) ? $settings['show_terms'] : '';
		$tax      = isset( $settings['show_terms_tax'] ) ? $settings['show_terms_tax'] : '';
		$num      = isset( $settings['show_terms_num'] ) ? $settings['show_terms_num'] : '';

		if ( 'yes' !== $show ) {
			return;
		}

		$terms = wp_get_post_terms( get_the_ID(), esc_attr( $tax ) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		if ( 'all' !== $num ) {
			$num   = absint( $num );
			$terms = array_slice( $terms, 0, $num );
		}

		$format = apply_filters(
			'jet-blog/smart-tiles/post-term-format',
			'<a href="%2$s" class="jet-smart-tiles__terms-link">%1$s</a>'
		);

		$result = '';

		foreach ( $terms as $term ) {
			$result .= sprintf( $format, $term->name, get_term_link( (int) $term->term_id, $tax ) );
		}

		printf( '<div class="jet-smart-tiles__terms">%s</div>', $result );

	}

	/**
	 * Retrieves meta settings ad required data.
	 *
	 * @return array
	 */
	public function __get_meta() {

		$settings = $this->get_settings();

		$show = array(
			'author'   => 'show_author',
			'date'     => 'show_date',
			'comments' => 'show_comments',
		);

		$html = array(
			'author' => '<span class="posted-by post-meta__item jet-smart-tiles__meta-item">%1$s<span %3$s %4$s>%5$s%6$s</span></span>',
			'date' => '<span class="post__date post-meta__item jet-smart-tiles__meta-item">%1$s<span %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></span></span>',
			'comments' => '<span class="post__comments post-meta__item jet-smart-tiles__meta-item">%1$s<span %3$s %4$s>%5$s%6$s</span></span>',
		);

		$icon_format = '<i class="jet-smart-tiles__meta-icon %s"></i>';
		$result      = array();

		foreach ( $show as $key => $setting ) {

			$prefix = ( ! empty( $settings[ $setting . '_icon' ] ) ) ? sprintf( $icon_format, $settings[ $setting . '_icon' ] ) : '';

			$current_html = $html[ $key ];

			$current = array(
				'visible' => $settings[ $setting ],
				'prefix'  => $prefix,
				'html'    => $current_html,
			);

			$result[ $key ] = $current;

		}

		return $result;

	}

	/**
	 * Show post excerpt.
	 * @return [type] [description]
	 */
	public function __post_excerpt( $before = '', $after = '' ) {

		$excerpt  = has_excerpt( get_the_ID() ) ? apply_filters( 'the_excerpt', get_the_excerpt() ) : '';
		$settings = $this->get_settings();
		$length   = $settings['excerpt_length'];
		$trimmed  = $settings['excerpt_trimmed_ending'];

		if ( ! $length ) {
			return;
		}

		if ( ! $excerpt ) {

			$content = get_the_content();
			$excerpt = strip_shortcodes( $content );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );

			if ( -1 === $length ) {
				$excerpt = wp_trim_words( $excerpt, 55, '' );
			}

		}

		if ( -1 !== $length ) {
			$excerpt = wp_trim_words( $excerpt, $length, $trimmed );
		}

		printf( '%2$s%1$s%3$s', $excerpt, $before, $after );
	}

	/**
	 * Print tiles wrapper CSS classes string
	 *
	 * @return void
	 */
	public function __tiles_wrap_classes() {

		$settings = $this->get_settings();
		$classes  = array( 'jet-smart-tiles-wrap' );

		if ( 'yes' === $settings['excerpt_on_hover'] ) {
			$classes[] = 'jet-hide-excerpt';
		}

		if ( 'yes' === $settings['carousel_enabled'] ) {
			$classes[] = 'jet-smart-tiles-carousel';
		}

		if ( 'yes' === $settings['carousel_enabled'] && 'yes' === $settings['show_arrows_on_hover'] ) {
			$classes[] = 'jet-arrows-on-hover';
		}

		echo implode( ' ', $classes );
	}

	public function __trim_title( $title ) {

		$settings = $this->get_settings();

		if ( ! isset( $settings['title_length'] ) ) {
			return $title;
		}

		$length = absint( $settings['title_length'] );

		if ( 0 === $length ) {
			return $title;
		}

		$title_arr = explode( ' ', $title );

		if ( count( $title_arr ) <= $length ) {
			return $title;
		}

		$new_title = array_slice( $title_arr, 0, $length );

		return implode( ' ', $new_title ) . '...';
	}

	protected function render() {

		$this->__context = 'render';

		$this->__get_posts();

		$this->__open_wrap();
		add_filter( 'the_title', array( $this, '__trim_title' ), 999 );
		include $this->__get_global_template( 'index' );
		remove_filter( 'the_title', array( $this, '__trim_title' ), 999 );
		$this->__close_wrap();
	}

}
