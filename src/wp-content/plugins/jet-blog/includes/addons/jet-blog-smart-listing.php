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

class Jet_Blog_Smart_Listing extends Jet_Blog_Base {

	private $_allowed_types = false;

	public $query_data = array(
		'max_pages'    => 1,
		'current_page' => 1,
	);

	public function get_name() {
		return 'jet-blog-smart-listing';
	}

	public function get_title() {
		return esc_html__( 'Smart Posts List', 'jet-blog' );
	}

	public function get_icon() {
		return 'jet-blog-41';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-blog/smart-listing/css-scheme',
			array(
				'featured_post'         => '.jet-smart-listing__featured',
				'featured_post_image'   => '.jet-smart-listing__post-thumbnail.post-thumbnail-featured',
				'featured_post_title'   => '.jet-smart-listing__featured .post-title-featured',
				'featured_post_text'    => '.jet-smart-listing__featured .post-excerpt-featured',
				'featured_post_button'  => '.jet-smart-listing__featured .jet-smart-listing__more',
				'featured_post_content' => '.jet-smart-listing__featured-content',
				'posts_list'            => '.jet-smart-listing__posts',
				'post'                  => '.jet-smart-listing__post',
				'post_image'            => '.jet-smart-listing__post-thumbnail.post-thumbnail-simple',
				'post_title'            => '.jet-smart-listing__post .post-title-simple',
				'post_text'             => '.jet-smart-listing__post .post-excerpt-simple',
				'post_button'           => '.jet-smart-listing__post .jet-smart-listing__more',
				'post_content'          => '.jet-smart-listing__post-content',
				'heading'               => '.jet-smart-listing__heading',
				'heading_title'         => '.jet-smart-listing__title',
				'filter'                => '.jet-smart-listing__filter',
				'filter_item'           => '.jet-smart-listing__filter > .jet-smart-listing__filter-item',
				'hidden_item'           => '.jet-smart-listing__filter-more',
				'hidden_wrap'           => '.jet-smart-listing__filter-hidden-items',
				'meta_item'             => '.jet-smart-listing__meta-item',
				'meta'                  => '.jet-smart-listing__meta',
				'terms_link'            => '.jet-smart-listing__post .jet-smart-listing__terms-link',
				'featured_terms_link'   => '.jet-smart-listing__featured .jet-smart-listing__terms-link',
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
				'default'   => 'h2',
				'options'   => array(
					'h1'  => esc_html__( 'H1', 'jet-blog' ),
					'h2'  => esc_html__( 'H2', 'jet-blog' ),
					'h3'  => esc_html__( 'H3', 'jet-blog' ),
					'h4'  => esc_html__( 'H4', 'jet-blog' ),
					'h5'  => esc_html__( 'H5', 'jet-blog' ),
					'h6'  => esc_html__( 'H6', 'jet-blog' ),
					'div' => esc_html__( 'DIV', 'jet-blog' ),
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'featured_post',
			array(
				'label'        => esc_html__( 'Mark First Post as Featured', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'featured_position',
			array(
				'label'   => esc_html__( 'Featured Post Position', 'jet-blog' ),
				'label_block' => true,
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'top'  => esc_html__( 'Top', 'jet-blog' ),
					'left' => esc_html__( 'Left', 'jet-blog' ),
				),
				'condition' => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'featured_width',
			array(
				'label'      => esc_html__( 'Feauted Post Max Width', 'jet-blog' ),
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
				'condition' => array(
					'featured_post'     => 'yes',
					'featured_position' => 'left',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] => 'max-width: {{SIZE}}%; flex: 0 0 {{SIZE}}%;',
					'{{WRAPPER}} .featured-position-left + ' . $css_scheme['posts_list'] => 'max-width: calc( 100% - {{SIZE}}% ); flex-basis: calc( 100% - {{SIZE}}% );',

				),
			)
		);

		$this->add_control(
			'featured_layout',
			array(
				'label'   => esc_html__( 'Featured Post Layout', 'jet-blog' ),
				'label_block' => true,
				'type'    => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => array(
					'simple' => esc_html__( 'Simple', 'jet-blog' ),
					'boxed'  => esc_html__( 'Boxed', 'jet-blog' ),
				),
				'condition' => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_image_size',
			array(
				'label'     => esc_html__( 'Featured Post Image Size', 'jet-blog' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT,
				'default'   => 'full',
				'options'   => jet_blog_tools()->get_image_sizes(),
				'condition' => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_image_position',
			array(
				'label'   => esc_html__( 'Featured Post Image Position', 'jet-blog' ),
				'label_block' => true,
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'  => esc_html__( 'Top', 'jet-blog' ),
					'left' => esc_html__( 'Left', 'jet-blog' ),
				),
				'condition' => array(
					'featured_post'   => 'yes',
					'featured_layout' => 'simple',
				),
			)
		);

		$this->add_responsive_control(
			'featured_image_width',
			array(
				'label'      => esc_html__( 'Featured Post Image Max Width', 'jet-blog' ),
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
				'condition' => array(
					'featured_post'           => 'yes',
					'featured_layout'         => 'simple',
					'featured_image_position' => 'left',
				),
				'selectors' => array(
					'{{WRAPPER}} .featured-img-left ' . $css_scheme['featured_post_image'] => 'max-width: {{SIZE}}%; flex: 0 0 {{SIZE}}%;',

				),
			)
		);

		$this->add_control(
			'featured_title_length',
			array(
				'label'       => esc_html__( 'Featured Post Title Max Length (Words)', 'jet-blog' ),
				'description' => esc_html__( 'Set 0 to show full title', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'max'         => 15,
				'step'        => 1,
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_excerpt_length',
			array(
				'label'       => esc_html__( 'Featured Post Excerpt Length', 'jet-blog' ),
				'label_block' => true,
				'description' => esc_html__( 'Set 0 to hide excerpt or -1 to show full excerpt', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'min'         => -1,
				'max'         => 200,
				'step'        => 1,
				'condition'   => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_excerpt_trimmed_ending',
			array(
				'label'     => esc_html__( 'Featured Excerpt Trimmed Ending', 'jet-blog' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '...',
				'condition' => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_read_more',
			array(
				'label'        => esc_html__( 'Featured Post Read More Button', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'featured_post'   => 'yes',
					'featured_layout' => 'simple',
				),
			)
		);

		$this->add_control(
			'featured_read_more_text',
			array(
				'label'   => esc_html__( 'Read More Button Label', 'jet-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'jet-blog' ),
				'condition'   => array(
					'featured_post'      => 'yes',
					'featured_layout'    => 'simple',
					'featured_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_meta',
			array(
				'label'        => esc_html__( 'Featured Post Meta', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'featured_post'   => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_meta_position',
			array(
				'label'       => esc_html__( 'Featured Meta Position', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'after',
				'options' => array(
					'before'        => esc_html__( 'Before Title', 'jet-blog' ),
					'after'         => esc_html__( 'After Title', 'jet-blog' ),
					'after-excerpt' => esc_html__( 'After Excerpt', 'jet-blog' ),
				),
				'condition'   => array(
					'featured_post'      => 'yes',
					'featured_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_author',
			array(
				'label'        => esc_html__( 'Show Post Author', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'featured_post'      => 'yes',
					'featured_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_author_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Author Icon', 'jet-blog' ),
				'default'   => 'fa fa-user',
				'condition' => array(
					'featured_post'        => 'yes',
					'featured_show_meta'   => 'yes',
					'featured_show_author' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_date',
			array(
				'label'        => esc_html__( 'Show Post Date', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'featured_post'      => 'yes',
					'featured_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_date_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Date Icon', 'jet-blog' ),
				'default'   => 'fa fa-calendar',
				'condition' => array(
					'featured_post'      => 'yes',
					'featured_show_meta' => 'yes',
					'featured_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_comments',
			array(
				'label'        => esc_html__( 'Show Post Comments', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'featured_post'      => 'yes',
					'featured_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_show_comments_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Comments Icon', 'jet-blog' ),
				'default'   => 'fa fa-comments',
				'condition' => array(
					'featured_post'          => 'yes',
					'featured_show_meta'     => 'yes',
					'featured_show_comments' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_featured_terms',
			array(
				'label'        => esc_html__( 'Show Post Terms', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition' => array(
					'featured_post' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_featured_terms_tax',
			array(
				'label'     => esc_html__( 'Show Terms From', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'category',
				'options'   => jet_blog_tools()->get_post_taxonomies(),
				'condition' => array(
					'featured_post'       => 'yes',
					'show_featured_terms' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'posts_columns',
			array(
				'label'     => esc_html__( 'Columns Number', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => jet_blog_tools()->get_select_range( 4, array(), true ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_rows',
			array(
				'label'   => esc_html__( 'Rows Number', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => jet_blog_tools()->get_select_range( 6 ),
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Post Thumbnail', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Post Image Size', 'jet-blog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => jet_blog_tools()->get_image_sizes(),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'   => esc_html__( 'Post Image Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'top'  => esc_html__( 'Top', 'jet-blog' ),
					'left' => esc_html__( 'Left', 'jet-blog' ),
				),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Post Image Max Width', 'jet-blog' ),
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
				'condition' => array(
					'show_image'     => 'yes',
					'image_position' => 'left',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_image'] . '.post-thumbnail-simple' => 'max-width: {{SIZE}}%; flex: 0 0 {{SIZE}}%;',

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
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'       => esc_html__( 'Excerpt Length', 'jet-blog' ),
				'description' => esc_html__( 'Set 0 to hide excerpt or -1 to show full excerpt', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => -1,
				'max'         => 200,
				'step'        => 1,
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
			'read_more',
			array(
				'label'        => esc_html__( 'Read More Button', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'   => esc_html__( 'Read More Button Label', 'jet-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'jet-blog' ),
				'condition' => array(
					'read_more' => 'yes',
				),
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
			)
		);

		$this->add_control(
			'meta_position',
			array(
				'label'       => esc_html__( 'Meta Position', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'after',
				'options' => array(
					'before'        => esc_html__( 'Before Title', 'jet-blog' ),
					'after'         => esc_html__( 'After Title', 'jet-blog' ),
					'after-excerpt' => esc_html__( 'After Excerpt', 'jet-blog' ),
				),
				'condition'   => array(
					'show_meta' => 'yes',
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

		$this->add_control(
			'show_terms',
			array(
				'label'        => esc_html__( 'Show Post Terms', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
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
				'type'        => 'text',
				'label'       => esc_html__( 'Set comma seprated IDs list (10, 22, 19 etc.)', 'jet-blog' ),
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
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
				'condition'   => array(
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
				'condition'   => array(
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
			'show_filter',
			array(
				'label'        => esc_html__( 'Show Filter by Terms', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition'    => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
				),
			)
		);

		$this->add_control(
			'filter_by',
			array(
				'label'       => esc_html__( 'Select Terms Type for Filter', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'default'     => 'category',
				'options'     => $this->get_filter_taxonomies(),
				'condition'   => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
				),
			)
		);

		$this->add_control(
			'show_all_btn',
			array(
				'label'        => esc_html__( 'Show "All" Button in Start of Filter', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
					'show_filter'          => 'yes',
				),
			)
		);

		$this->add_control(
			'all_btn_label',
			array(
				'label'       => esc_html__( '"All" Button Label', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'All', 'jet-blog' ),
				'condition'    => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
					'show_filter'          => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_rollup',
			array(
				'label'        => esc_html__( 'RollUp Extra Terms', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'description'  => esc_html__( 'Enable this option in order to reduce the terms filter size by grouping extra terms items and hiding them under the suspension dots.', 'jet-blog' ),
				'default'      => '',
				'separator'    => 'before',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'show_filter'          => 'yes',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
				),
			)
		);

		$this->add_control(
			'more_terms_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'More Terms Icon', 'jet-blog' ),
				'default'   => 'fa fa-ellipsis-h',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
					'post_type'            => $this->allowed_types_for_filter(),
					'terms_rollup'         => 'yes',
					'show_filter'          => 'yes',
				),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Show Paging Control', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-blog' ),
				'label_off'    => esc_html__( 'Hide', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
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
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'yes',
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
			'section_posts_wrapper_style',
			array(
				'label'      => esc_html__( 'Posts Wrapper', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'posts_wrapper_margin',
			array(
				'label'      => esc_html__( 'Global Wrapper Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 0,
					'right'    => -10,
					'bottom'   => 0,
					'left'     => -10,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-smart-listing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_post_margin',
			array(
				'label'      => esc_html__( 'Featured Post Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'posts_list_margin',
			array(
				'label'      => esc_html__( 'Posts List Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 0,
					'right'    => 10,
					'bottom'   => 0,
					'left'     => 10,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['posts_list'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_style',
			array(
				'label'      => esc_html__( 'Heading', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'heading_block_styles',
			array(
				'label' => esc_html__( 'Heading Box', 'jet-blog' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'heading_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['heading'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'heading_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['heading'],
			)
		);

		$this->add_responsive_control(
			'heading_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'heading_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['heading'],
			)
		);

		$this->add_responsive_control(
			'heading_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_title_color',
			array(
				'label' => esc_html__( 'Title Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading_title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['heading_title'],
			)
		);

		$this->add_responsive_control(
			'heading_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter_style',
			array(
				'label'      => esc_html__( 'Filter', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_item_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['filter_item'] . ' > a',
			)
		);

		$this->start_controls_tabs( 'tabs_filter_style' );

		$this->start_controls_tab(
			'tab_filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'filter_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'filter_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i',
			)
		);

		$this->add_responsive_control(
			'filter_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'filter_color_hover',
			array(
				'label' => esc_html__( 'Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ':hover > i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a:hover, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ':hover > i',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'filter_border_hover',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a:hover, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ':hover > i',
			)
		);

		$this->add_responsive_control(
			'filter_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ':hover > i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a:hover, {{WRAPPER}} ' . $css_scheme['hidden_item'] . ':hover > i',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blog' ),
			)
		);

		$this->add_control(
			'filter_color_active',
			array(
				'label' => esc_html__( 'Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-smart-listing__filter .jet-smart-listing__filter-item.jet-active-item > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_background_active',
				'selector' => '{{WRAPPER}} .jet-smart-listing__filter .jet-smart-listing__filter-item.jet-active-item > a',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'filter_border_active',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-smart-listing__filter .jet-smart-listing__filter-item.jet-active-item > a',
			)
		);

		$this->add_responsive_control(
			'filter_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-smart-listing__filter .jet-smart-listing__filter-item.jet-active-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'filter_box_shadow_active',
				'selector' => '{{WRAPPER}} .jet-smart-listing__filter .jet-smart-listing__filter-item.jet-active-item > a',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'filter_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'filter_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['filter_item'] . ' > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} ' . $css_scheme['filter'] => 'flex-grow: 1; text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hidden_items_styles',
			array(
				'label'     => esc_html__( 'Hidden Terms Box', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hidden_items_icon_size',
			array(
				'label'      => esc_html__( 'Hidden Terms Icon Size', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['hidden_item'] . ' > i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hidden_item_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['hidden_wrap'] . ' a',
			)
		);

		$this->add_control(
			'hidden_item_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['hidden_wrap'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hidden_item_color_hover',
			array(
				'label' => esc_html__( 'Hover/Active Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['hidden_wrap'] . ' a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['hidden_wrap'] . ' .jet-active-item > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hidden_wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hidden_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hidden_item_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hidden_wrap'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hidden_item_border_active',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['hidden_wrap'],
			)
		);

		$this->add_responsive_control(
			'hidden_item_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['hidden_wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hidden_item_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['hidden_wrap'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_featured_style',
			array(
				'label'      => esc_html__( 'Featured', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'featured_post_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_post_content_margin',
			array(
				'label'      => esc_html__( 'Inner Content Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'featured_post_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'featured_post_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['featured_post'],
			)
		);

		$this->add_responsive_control(
			'featured_post_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'featured_post_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post'],
			)
		);

		$this->add_control(
			'featured_post_thumb_styles',
			array(
				'label'     => esc_html__( 'Post Image', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'featured_post_thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_image'] . ' a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'featured_post_thumb_overlay_styles',
			array(
				'label'     => esc_html__( 'Post Image Overlay', 'jet-blog' ),
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
				'name'     => 'overlay_background_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post'] . ' .jet-smart-listing__featured-box-link:before, {{WRAPPER}} ' . $css_scheme['featured_post'] . ' .jet-smart-listing__post-thumbnail a:before',
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
				'name'     => 'overlay_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post'] . ' .jet-smart-listing__featured-box-link:hover:before, {{WRAPPER}} ' . $css_scheme['featured_post'] . ' .jet-smart-listing__post-thumbnail a:hover:before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'featured_post_title_style',
			array(
				'label'     => esc_html__( 'Post Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'featured_post_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] . ' a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['featured_post_title']        => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'featured_post_title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] . ':hover a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] . ':hover'   => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'featured_post_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_post_title'],
			)
		);

		$this->add_control(
			'featured_post_title_text_decoration_hover',
			array(
				'label'       => esc_html__( 'Text Decoration Hover', 'jet-blog' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'label_block' => true,
				'options'     => array(
					''             => __( 'Default', 'jet-blog' ),
					'underline'    => _x( 'Underline', 'Typography Control', 'jet-blog' ),
					'overline'     => _x( 'Overline', 'Typography Control', 'jet-blog' ),
					'line-through' => _x( 'Line Through', 'Typography Control', 'jet-blog' ),
					'none'         => _x( 'None', 'Typography Control', 'jet-blog' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] . ':hover a' => 'text-decoration: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] . ':hover'   => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'featured_post_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_post_title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'justify' => array(
						'title' => esc_html__( 'Justify', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_title'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'featured_post_text_style',
			array(
				'label'     => esc_html__( 'Post Text', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'featured_post_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'featured_post_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_post_text'],
			)
		);

		$this->add_responsive_control(
			'featured_post_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_post_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'justify' => array(
						'title' => esc_html__( 'Justify', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_text'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_featured_meta_style',
			array(
				'label'      => esc_html__( 'Featured Post Meta', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'featured_meta_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta_item'] . ' .jet-smart-listing__meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'featured_meta_icon_gap',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta_item'] . ' .jet-smart-listing__meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'featured_meta_bg',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'featured_meta_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'featured_meta_link_color',
			array(
				'label' => esc_html__( 'Links Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'featured_layout!' => 'boxed',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'featured_meta_link_color_hover',
			array(
				'label' => esc_html__( 'Links Hover Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'featured_layout!' => 'boxed',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] . ' a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'featured_meta_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'],
			)
		);

		$this->add_responsive_control(
			'featured_meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_meta_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'featured_meta_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'featured_meta_divider',
			array(
				'label'     => esc_html__( 'Meta Divider', 'jet-blog' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
				),
			)
		);

		$this->add_responsive_control(
			'featured_meta_divider_gap',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_featured_button_style',
			array(
				'label'      => esc_html__( 'Featured Read More Button', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'add_button_icon',
			array(
				'label'        => esc_html__( 'Customize Icon', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Read More Button Icon', 'jet-blog' ),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'-1' => esc_html__( 'Before Text', 'jet-blog' ),
					'1'  => esc_html__( 'After Text', 'jet-blog' ),
				),
				'default'     => '1',
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ' .jet-smart-listing__more-icon' => 'order: {{VALUE}}',
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-blog' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 7,
						'max' => 90,
					),
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ' .jet-smart-listing__more-icon:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ' .jet-smart-listing__more-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ' .jet-smart-listing__more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'       => _x( 'Background Type', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => _x( 'Classic', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => _x( 'Gradient', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'button_bg_color',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition' => array(
					'button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-blog' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-blog' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{button_bg_color.VALUE}} {{button_bg_color_stop.SIZE}}{{button_bg_color_stop.UNIT}}, {{button_bg_color_b.VALUE}} {{button_bg_color_b_stop.SIZE}}{{button_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'button_bg'               => array( 'gradient' ),
					'button_bg_gradient_type' => 'linear',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-blog' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-blog' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-blog' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-blog' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-blog' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-blog' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-blog' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-blog' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-blog' ),
				),
				'default' => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{button_bg_color.VALUE}} {{button_bg_color_stop.SIZE}}{{button_bg_color_stop.UNIT}}, {{button_bg_color_b.VALUE}} {{button_bg_color_b_stop.SIZE}}{{button_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'button_bg'               => array( 'gradient' ),
					'button_bg_gradient_type' => 'radial',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_post_button'],
			)
		);

		$this->add_control(
			'button_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ' .jet-smart-listing__more-text' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['featured_post_button'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post_button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'button_hover_bg',
			array(
				'label'       => _x( 'Background Type', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => _x( 'Classic', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => _x( 'Gradient', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'button_hover_bg_color',
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
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition' => array(
					'button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-blog' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-blog' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{button_hover_bg_color.VALUE}} {{button_hover_bg_color_stop.SIZE}}{{button_hover_bg_color_stop.UNIT}}, {{button_hover_bg_color_b.VALUE}} {{button_hover_bg_color_b_stop.SIZE}}{{button_hover_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'button_hover_bg'               => array( 'gradient' ),
					'button_hover_bg_gradient_type' => 'linear',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-blog' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-blog' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-blog' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-blog' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-blog' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-blog' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-blog' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-blog' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-blog' ),
				),
				'default' => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{button_hover_bg_color.VALUE}} {{button_hover_bg_color_stop.SIZE}}{{button_hover_bg_color_stop.UNIT}}, {{button_hover_bg_color_b.VALUE}} {{button_hover_bg_color_b_stop.SIZE}}{{button_hover_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'button_hover_bg'               => array( 'gradient' ),
					'button_hover_bg_gradient_type' => 'radial',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'button_hover_typography',
				'label' => esc_html__( 'Typography', 'jet-blog' ),
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_post_button'] . ':hover',
			)
		);

		$this->add_control(
			'button_hover_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover .jet-smart-listing__more-text' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_post_button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_alignment',
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_post'] . ' .jet-smart-listing__more-wrap' => 'justify-content: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_featured_terms_link_style',
			array(
				'label'      => esc_html__( 'Featured Terms Links', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'featured_terms_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_featured_terms_link_style' );

		$this->start_controls_tab(
			'tab_featured_terms_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'featured_terms_link_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => esc_html__( 'Background Color', 'jet-blog' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'featured_terms_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'featured_terms_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_terms_link'],
			)
		);

		$this->add_control(
			'featured_terms_link_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . '' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'featured_terms_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'featured_terms_link_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['featured_terms_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'featured_terms_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_terms_link'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_featured_terms_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'featured_terms_link_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => esc_html__( 'Background Color', 'jet-blog' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'featured_terms_link_hover_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'featured_terms_link_hover_typography',
				'label' => esc_html__( 'Typography', 'jet-blog' ),
				'selector' => '{{WRAPPER}}  ' . $css_scheme['featured_terms_link'] . ':hover',
			)
		);

		$this->add_control(
			'featured_terms_link_hover_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'featured_terms_link_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'featured_terms_link_hover_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'featured_terms_link_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['featured_terms_link'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'featured_terms_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['featured_terms_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_style',
			array(
				'label'      => esc_html__( 'Post', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'post_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'device_args' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['post'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; max-width: calc( 100%/{{posts_columns.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'(tablet){{WRAPPER}} ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_tablet.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'(mobile){{WRAPPER}} ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_mobile.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
						),
					),
					Controls_Stack::RESPONSIVE_TABLET => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['post'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; max-width: calc( 100%/{{posts_columns.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'{{WRAPPER}} [class*="columns-tablet-"] ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_tablet.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'(mobile){{WRAPPER}} [class*="columns-tablet-"] ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_mobile.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
						),
					),
					Controls_Stack::RESPONSIVE_MOBILE => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['post'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; max-width: calc( 100%/{{posts_columns.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'{{WRAPPER}} [class*="columns-tablet-"] ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_tablet.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
							'{{WRAPPER}} [class*="columns-mobile-"] ' . $css_scheme['post'] => 'max-width: calc( 100%/{{posts_columns_mobile.VALUE}} - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'post_content_margin',
			array(
				'label'      => esc_html__( 'Inner Content Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'post_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'post_border',
				'label'          => esc_html__( 'Border', 'jet-blog' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['post'],
			)
		);

		$this->add_responsive_control(
			'post_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post'],
			)
		);

		$this->add_control(
			'post_thumb_styles',
			array(
				'label'     => esc_html__( 'Post Image', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'post_thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_image'] . ' a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_thumb_overlay_styles',
			array(
				'label'     => esc_html__( 'Post Image Overlay', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_post_overlay_style' );

		$this->start_controls_tab(
			'tab_post_overlay_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'post_overlay_background_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post'] . ' .jet-smart-listing__post-thumbnail a:before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_overlay_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'post_overlay_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post'] . ' .jet-smart-listing__post-thumbnail a:hover:before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_title_style',
			array(
				'label'     => esc_html__( 'Post Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'post_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_title'] . ' a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['post_title']        => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'post_title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_title'] . ':hover a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['post_title'] . ':hover'   => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'post_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['post_title'],
			)
		);

		$this->add_control(
			'post_title_text_decoration_hover',
			array(
				'label'       => esc_html__( 'Text Decoration Hover', 'jet-blog' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'label_block' => true,
				'options'     => array(
					''             => __( 'Default', 'jet-blog' ),
					'underline'    => _x( 'Underline', 'Typography Control', 'jet-blog' ),
					'overline'     => _x( 'Overline', 'Typography Control', 'jet-blog' ),
					'line-through' => _x( 'Line Through', 'Typography Control', 'jet-blog' ),
					'none'         => _x( 'None', 'Typography Control', 'jet-blog' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_title'] . ':hover a' => 'text-decoration: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['post_title'] . ':hover'   => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'post_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'justify' => array(
						'title' => esc_html__( 'Justify', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_title'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_text_style',
			array(
				'label'     => esc_html__( 'Post Text', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'post_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'post_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['post_text'],
			)
		);

		$this->add_responsive_control(
			'post_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blog' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'justify' => array(
						'title' => esc_html__( 'Justify', 'jet-blog' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_text'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'      => esc_html__( 'Post Meta', 'jet-blog' ),
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta_item'] . ' .jet-smart-listing__meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta_item'] . ' .jet-smart-listing__meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'meta_bg',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'meta_link_color',
			array(
				'label' => esc_html__( 'Links Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'meta_link_color_hover',
			array(
				'label' => esc_html__( 'Links Hover Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] . ' a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'],
			)
		);

		$this->add_responsive_control(
			'meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta'] => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
				),
			)
		);

		$this->add_responsive_control(
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
					'{{WRAPPER}} ' . $css_scheme['post'] . ' ' . $css_scheme['meta_item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_post_button_style',
			array(
				'label'      => esc_html__( 'Post Read More Button', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'post_add_button_icon',
			array(
				'label'        => esc_html__( 'Customize Icon', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'post_button_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Read More Button Icon', 'jet-blog' ),
				'condition' => array(
					'post_add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'post_button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'-1' => esc_html__( 'Before Text', 'jet-blog' ),
					'1'  => esc_html__( 'After Text', 'jet-blog' ),
				),
				'default'     => '1',
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ' .jet-smart-listing__more-icon' => 'order: {{VALUE}}',
				),
				'condition' => array(
					'post_add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'post_button_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-blog' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 7,
						'max' => 90,
					),
				),
				'condition' => array(
					'post_add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ' .jet-smart-listing__more-icon:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'post_button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'post_add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ' .jet-smart-listing__more-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'post_button_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ' .jet-smart-listing__more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_post_button_style' );

		$this->start_controls_tab(
			'tab_post_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'post_button_bg',
			array(
				'label'       => _x( 'Background Type', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => _x( 'Classic', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => _x( 'Gradient', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'post_button_bg_color',
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
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_button_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition' => array(
					'post_button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-blog' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-blog' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{post_button_bg_color.VALUE}} {{post_button_bg_color_stop.SIZE}}{{post_button_bg_color_stop.UNIT}}, {{post_button_bg_color_b.VALUE}} {{post_button_bg_color_b_stop.SIZE}}{{post_button_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'post_button_bg'               => array( 'gradient' ),
					'post_button_bg_gradient_type' => 'linear',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-blog' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-blog' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-blog' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-blog' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-blog' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-blog' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-blog' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-blog' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-blog' ),
				),
				'default' => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{post_button_bg_color.VALUE}} {{post_button_bg_color_stop.SIZE}}{{post_button_bg_color_stop.UNIT}}, {{post_button_bg_color_b.VALUE}} {{post_button_bg_color_b_stop.SIZE}}{{post_button_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'post_button_bg'               => array( 'gradient' ),
					'post_button_bg_gradient_type' => 'radial',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'post_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['post_button'],
			)
		);

		$this->add_control(
			'post_button_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ' .jet-smart-listing__more-text' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'post_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'post_button_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['post_button'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post_button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'post_button_hover_bg',
			array(
				'label'       => _x( 'Background Type', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'color' => array(
						'title' => _x( 'Classic', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-paint-brush',
					),
					'gradient' => array(
						'title' => _x( 'Gradient', 'Background Control', 'jet-blog' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'default'     => 'color',
				'label_block' => false,
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'post_button_hover_bg_color',
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
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_button_hover_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition' => array(
					'post_button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-blog' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-blog' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-blog' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'post_button_hover_bg' => array( 'gradient' ),
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{post_button_hover_bg_color.VALUE}} {{post_button_hover_bg_color_stop.SIZE}}{{post_button_hover_bg_color_stop.UNIT}}, {{post_button_hover_bg_color_b.VALUE}} {{post_button_hover_bg_color_b_stop.SIZE}}{{post_button_hover_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'post_button_hover_bg'               => array( 'gradient' ),
					'post_button_hover_bg_gradient_type' => 'linear',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-blog' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-blog' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-blog' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-blog' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-blog' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-blog' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-blog' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-blog' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-blog' ),
				),
				'default' => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{post_button_hover_bg_color.VALUE}} {{post_button_hover_bg_color_stop.SIZE}}{{post_button_hover_bg_color_stop.UNIT}}, {{post_button_hover_bg_color_b.VALUE}} {{post_button_hover_bg_color_b_stop.SIZE}}{{post_button_hover_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'post_button_hover_bg'               => array( 'gradient' ),
					'post_button_hover_bg_gradient_type' => 'radial',
				),
				'of_type' => 'gradient',
			)
		);

		$this->add_control(
			'post_button_hover_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blog' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'post_button_hover_typography',
				'label' => esc_html__( 'Typography', 'jet-blog' ),
				'selector' => '{{WRAPPER}}  ' . $css_scheme['post_button'] . ':hover',
			)
		);

		$this->add_control(
			'post_button_hover_text_decor',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'jet-blog' ),
					'underline' => esc_html__( 'Underline', 'jet-blog' ),
				),
				'default' => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover .jet-smart-listing__more-text' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'post_button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'post_button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['post_button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'post_button_alignment',
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['post'] . ' .jet-smart-listing__more-wrap' => 'justify-content: {{VALUE}};',
				),
				'separator' => 'before',
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
				'label'     => esc_html__( 'Background Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => esc_html__( 'Background Color', 'jet-blog' ),
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
				'label'     => esc_html__( 'Background Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'title'     => esc_html__( 'Background Color', 'jet-blog' ),
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
				'selector'       => '{{WRAPPER}} .jet-smart-listing__arrow',
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
				'selector'       => '{{WRAPPER}} .jet-smart-listing__arrow:hover',
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
				'label'   => esc_html__( 'Vertical Position by', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-prev' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-prev' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'prev_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-prev' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-prev' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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
				'label'   => esc_html__( 'Vertical Position by', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-next' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-next' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			)
		);

		$this->add_control(
			'next_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-blog' ),
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-next' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
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
					'{{WRAPPER}} .jet-smart-listing__arrow.jet-arrow-next' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
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

		$this->start_controls_section(
			'section_loader_styles',
			array(
				'label'      => esc_html__( 'Loader Styles', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'loader_opacity',
			array(
				'label'      => esc_html__( 'Loader Opacity', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0.5,
				),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-processing' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'loader_dash_color',
			array(
				'label'     => esc_html__( 'Loader Dash Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-smart-listing-loading' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'loader_track_color',
			array(
				'label'     => esc_html__( 'Loader Track Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-smart-listing-loading' => 'border-left-color: {{VALUE}}; border-bottom-color: {{VALUE}}; border-right-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Export widget settings into data attribute
	 *
	 * @return [type] [description]
	 */
	public function __export_settings() {

		$settings = $this->get_settings();

		$allowed = apply_filters( 'jet-blog/smart-listing/exported-options', array(
			'block_title',
			'title_tag',
			'featured_post',
			'featured_position',
			'featured_width',
			'featured_layout',
			'featured_image_size',
			'featured_image_position',
			'featured_image_width',
			'featured_excerpt_length',
			'featured_excerpt_trimmed_ending',
			'featured_read_more',
			'featured_read_more_text',
			'featured_show_meta',
			'featured_show_author',
			'featured_show_author_icon',
			'featured_show_date',
			'featured_show_date_icon',
			'featured_show_comments',
			'featured_show_comments_icon',
			'posts_columns',
			'posts_rows',
			'image_size',
			'image_position',
			'image_width',
			'excerpt_length',
			'excerpt_trimmed_ending',
			'read_more',
			'read_more_text',
			'show_meta',
			'show_author',
			'show_author_icon',
			'show_date',
			'show_date_icon',
			'show_comments',
			'show_comments_icon',
			'query_by',
			'category_ids',
			'post_tag_ids',
			'include_ids',
			'exclude_ids',
			'meta_query',
			'meta_key',
			'meta_value',
			'show_filter',
			'filter_by',
			'show_all_btn',
			'all_btn_label',
			'more_terms_icon',
			'show_arrows',
			'arrow_type',
			'show_featured_terms',
			'show_featured_terms_tax',
			'show_terms',
			'show_terms_tax',
			'featured_meta_position',
			'meta_position',
			'show_image',
			'post_type',
			'post_ids',
			'content_related_meta',
			'show_content_related_meta',
			'meta_content_related_position',
			'title_related_meta',
			'show_title_related_meta',
			'meta_title_related_position',
			'featured_title_length',
			'title_length',
			'add_button_icon',
			'button_icon',
			'post_add_button_icon',
			'post_button_icon',
			'use_custom_query',
			'custom_query',
		) );

		$result = array();

		foreach ( $allowed as $setting ) {
			$result[ $setting ] = isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
		}

		echo esc_attr( json_encode( $result ) );
	}

	/**
	 * Show filters by categories or tags.
	 *
	 * @return void
	 */
	public function __get_filters() {

		$settings  = $this->__get_widget_settings();
		$enabled   = $settings['show_filter'];
		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';

		if ( 'yes' !== $enabled ) {
			return;
		}

		if ( 'post' === $post_type ) {

			$query_by = $settings['query_by'];
			$args     = array(
				'taxonomy' => $query_by,
			);

			if ( 'all' === $query_by ) {
				$args['taxonomy'] = $settings['filter_by'];
			} elseif ( ! empty( $settings[ $query_by . '_ids' ] ) ) {
				$args['include'] = $settings[ $query_by . '_ids' ];
			}

		} else {

			$args = array(
				'taxonomy' => $settings['filter_by'],
			);

		}

		$args  = apply_filters( 'jet-blog/smart-listing/filter-terms/args', $args, $this );
		$terms = get_terms( $args );

		$item_format = apply_filters(
			'jet-blog/smart-listing/filter-item-format',
			'<div class="jet-smart-listing__filter-item"><a href="#" data-term="%1$s">%2$s</a></div>'
		);

		$show_all = $settings['show_all_btn'];
		$items    = '';

		if ( 'yes' === $show_all ) {
			$items .= sprintf( $item_format, 0, $settings['all_btn_label'] );
		}

		foreach ( $terms as $term ) {
			$items .= sprintf( $item_format, $term->term_id, $term->name );
		}

		$more_atts = array(
			'icon'      => $settings['more_terms_icon'],
			'tag'       => 'i',
			'className' => 'jet-smart-listing__filter-item jet-smart-listing__filter-more',
		);

		$rollup = filter_var( $settings['terms_rollup'], FILTER_VALIDATE_BOOLEAN );

		printf(
			'<div class="jet-smart-listing__filter" data-more=\'%2$s\' data-rollup="%3$s">%1$s</div>',
			$items,
			json_encode( $more_atts ),
			$rollup
		);
	}

	/**
	 * render paging control arrows
	 *
	 * @return void
	 */
	public function __get_arrows() {

		$settings = $this->__get_widget_settings();
		$enabled  = $settings['show_arrows'];

		if ( 'yes' !== $enabled ) {
			return;
		}

		$disabled = array(
			'prev' => false,
			'next' => false,
		);

		if ( 1 >= absint( $this->query_data['max_pages'] ) ) {
			$disabled['prev'] = true;
			$disabled['next'] = true;
		}

		if ( 1 === absint( $this->query_data['current_page'] ) ) {
			$disabled['prev'] = true;
		}

		if ( absint( $this->query_data['current_page'] ) === absint( $this->query_data['max_pages'] ) ) {
			$disabled['next'] = true;
		}

		$arrow_format = apply_filters(
			'jet-blog/smart-listing/paging-arrows-format',
			'<div class="jet-smart-listing__arrow jet-arrow-%1$s%3$s" data-dir="%1$s"><i class="fa %2$s"></i></div>'
		);

		$arrows = '';

		foreach ( array( 'prev', 'next' ) as $type ) {
			$arrows .= sprintf(
				$arrow_format,
				$type,
				isset( $settings['arrow_type'] ) ? $settings['arrow_type'] : 'fa-angle-left',
				( true === $disabled[ $type ] ) ? ' jet-arrow-disabled' : ''
			);
		}

		$custom_controls = apply_filters( 'jet-blog/smart-list/custom-controls', null, $this );

		printf( '<div class="jet-smart-listing__arrows">%1$s%2$s</div>', $arrows, $custom_controls );

	}

	protected function render() {

		$this->__context = 'render';
		$this->__get_posts();

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	/**
	 * Render posts list
	 *
	 * @return void
	 */
	public function __render_posts() {
		$posts = $this->__get_global_template( 'posts' );
		include $posts;
	}

	public function get_default_query_args( $settings = array() ) {

		$cols      = isset( $settings['posts_columns'] ) ? absint( $settings['posts_columns'] ) : 1;
		$rows      = ! empty( $settings['posts_rows'] ) ? absint( $settings['posts_rows'] ) : 3;
		$num       = $cols * $rows;
		$featured  = ! empty( $settings['featured_post'] ) ? true : false;
		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';
		$exclude   = ! empty( $settings['exclude_ids'] ) ? $settings['exclude_ids'] : '';
		$include   = ! empty( $settings['include_ids'] ) ? $settings['include_ids'] : '';

		if ( $featured ) {
			$num++;
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
					'terms'    => $settings[ $tax . '_ids' ],
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

		if ( isset( $settings['meta_query'] ) && 'yes' === $settings['meta_query'] ) {

			$meta_key   = ! empty( $settings['meta_key'] ) ? esc_attr( $settings['meta_key'] ) : false;
			$meta_value = ! empty( $settings['meta_value'] ) ? esc_attr( $settings['meta_value'] ) : '';

			if ( ! empty( $meta_key ) ) {
				$query_args['meta_key']   = $meta_key;
				$query_args['meta_value'] = $meta_value;
			}

		}

		if ( isset( $_REQUEST['jet_request_data'] ) ) {
			$query_args = array_merge( $query_args, $this->__add_request_data() );
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
		$query_args = json_decode( wp_unslash( $query_args ), true );

		if ( ! $query_args ) {
			$query_args = array();
		}

		if ( isset( $_REQUEST['jet_request_data'] ) ) {
			$query_args = array_merge( $query_args, $this->__add_request_data() );
		}

		return $query_args;

	}

	/**
	 * Get posts.
	 *
	 * @return void
	 */
	public function __get_posts() {

		$settings = $this->__get_widget_settings();

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
		$query_args = apply_filters( 'jet-blog/smart-listing/query-args', $query_args, $this );

		$query = new \WP_Query( $query_args );
		$posts = ! empty( $query->posts ) ? $query->posts : array();
		$paged = isset( $query_args['paged'] ) ? absint( $query_args['paged'] ) : 1;

		$this->query_data['max_pages']    = $query->max_num_pages;
		$this->query_data['current_page'] = $paged;

		$this->__set_query( $posts );

	}

	/**
	 * Add request data to query
	 *
	 * @return [type] [description]
	 */
	public function __add_request_data() {

		$data = isset( $_REQUEST['jet_request_data'] ) ? $_REQUEST['jet_request_data'] : array();

		if ( empty( $data ) ) {
			return array();
		}

		$result    = array();
		$settings  = $this->__get_widget_settings();
		$post_type = isset( $settings['post_type'] ) ? $settings['post_type'] : 'post';

		if ( isset( $data['term'] ) && 'yes' === $settings['show_filter'] && 0 !== absint( $data['term'] ) ) {

			if ( 'post' === $post_type ) {

				$tax = $settings['query_by'];

				if ( 'all' === $tax ) {
					$tax = $settings['filter_by'];
				}

			} else {

				$tax = $settings['filter_by'];

			}

			$result['tax_query'] = array(
				array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => absint( $data['term'] ),
				),
			);
		}

		if ( isset( $data['paged'] ) ) {
			$result['paged'] = absint( $data['paged'] );
		}

		return apply_filters( 'jet-blog/smart-listing/request-args', $result, $this );

	}

	/**
	 * Returns widget settings from elementor settings or from $_REQUEST request.
	 *
	 * @param  string $setting Setting name.
	 * @return mixed
	 */
	public function __get_widget_settings( $setting = null ) {

		if ( isset( $_REQUEST['jet_widget_settings'] ) ) {
			$settings = $_REQUEST['jet_widget_settings'];
		} else {
			$settings = $this->get_settings();
		}

		if ( ! empty( $setting ) ) {
			return isset( $settings[ $setting ] ) ? $settings[ $setting ] : false;
		} else {
			return $settings;
		}

	}

	/**
	 * Read More button
	 *
	 * @return void
	 */
	public function __read_more( $context = 'featured' ) {

		$settings = $this->__get_widget_settings();
		$allowed  = false;
		$label    = '';
		$icon     = '';

		$icon_format = apply_filters(
			'jet-blog/smart-listing/more-button-icon-format',
			'<i class="jet-smart-listing__more-icon %1$s"></i>'
		);

		switch ( $context ) {
			case 'featured':

				$allowed = isset( $settings['featured_read_more'] ) ? $settings['featured_read_more'] : '';
				$label   = isset( $settings['featured_read_more_text'] ) ? $settings['featured_read_more_text'] : '';
				$layout  = $settings['featured_layout'];

				if ( 'boxed' === $layout ) {
					$allowed = false;
				}

				if ( 'yes' === $settings['add_button_icon'] && ! empty( $settings['button_icon'] ) ) {
					$icon = sprintf( $icon_format, esc_attr( $settings['button_icon'] ) );
				}

				break;

			default:
				$allowed = isset( $settings['read_more'] ) ? $settings['read_more'] : '';
				$label   = isset( $settings['read_more_text'] ) ? $settings['read_more_text'] : '';

				if ( 'yes' === $settings['post_add_button_icon'] && ! empty( $settings['post_button_icon'] ) ) {
					$icon = sprintf( $icon_format, esc_attr( $settings['post_button_icon'] ) );
				}

				break;
		}

		if ( ! $allowed ) {
			return;
		}

		$format = apply_filters(
			'jet-blog/smart-listing/read-more-format',
			'<div class="jet-smart-listing__more-wrap"><a href="%1$s" class="jet-smart-listing__more %3$s-more elementor-button elementor-size-md"><span class="jet-smart-listing__more-text">%2$s</span>%4$s</a></div>'
		);

		printf( $format, get_permalink(), $label, $context, $icon );
	}

	/**
	 * Featured image
	 *
	 * @param  string $context Where image will be shown.
	 * @return void
	 */
	public function __featured_image( $context = 'simple' ) {

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$settings = $this->__get_widget_settings();

		switch ( $context ) {
			case 'featured':
				$size = $settings['featured_image_size'];
				break;

			default:
				$size = $settings['image_size'];
				break;
		}

		$show_image = isset( $settings['show_image'] ) ? $settings['show_image'] : '';

		if ( 'simple' === $context && 'yes' !== $show_image ) {
			return;
		}

		$class  = 'jet-smart-listing__post-thumbnail-img post-thumbnail-img-' . $context;
		$img    = get_the_post_thumbnail(
			get_the_ID(),
			$size,
			array(
				'class' => $class,
				'alt'   => esc_attr( get_the_title() ),
				'title' => esc_attr( get_the_title() ),
			)
		);

		$format = apply_filters(
			'jet-blog/smart-listing/post-thumbnail-format',
			'<div class="jet-smart-listing__post-thumbnail post-thumbnail-%2$s"><a href="%3$s">%1$s</a></div>'
		);

		printf( $format, $img, $context, get_permalink() );
	}

	/**
	 * Show post excerpt.
	 * @return [type] [description]
	 */
	public function __post_excerpt( $context = 'simple' ) {

		$excerpt  = has_excerpt( get_the_ID() ) ? apply_filters( 'the_excerpt', get_the_excerpt() ) : '';
		$settings = $this->__get_widget_settings();

		switch ( $context ) {
			case 'featured':
				$length = $settings['featured_excerpt_length'];
				$trimmed = $settings['featured_excerpt_trimmed_ending'];
				break;

			default:
				$length = $settings['excerpt_length'];
				$trimmed = $settings['excerpt_trimmed_ending'];
				break;
		}

		if ( ! $length ) {
			$this->__render_meta( 'content_related', 'jet-content-fields', array( 'before', 'after' ), $settings );
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

		$this->__render_meta( 'content_related', 'jet-content-fields', array( 'before' ), $settings );

		printf( '<div class="jet-smart-listing__post-excerpt post-excerpt-%2$s">%1$s</div>', $excerpt, $context );

		$this->__render_meta( 'content_related', 'jet-content-fields', array( 'after' ), $settings );
	}

	/**
	 * Returns post thumbnail as backgroud if boxed layout was selected
	 *
	 * @param  string $context 'featured' or 'simple' (currently is not used, just for possible future updates).
	 * @return void
	 */
	public function __get_item_thumbnail_bg( $context = 'featured' ) {

		$settings = $this->__get_widget_settings();
		$layout   = $settings['featured_layout'];
		$size     = $settings['featured_image_size'];

		if ( 'boxed' !== $layout ) {
			return;
		}

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$url = wp_get_attachment_image_url( get_post_thumbnail_id(), $size );

		printf( ' style="background-image: url(\'%s\')"', $url );

	}

	/**
	 * Post title.
	 *
	 * @return [type] [description]
	 */
	public function __post_title( $context = 'simple' ) {

		$settings = $this->__get_widget_settings();
		$layout   = $settings['featured_layout'];

		$format = apply_filters(
			'jet-blog/smart-listing/post-title-format',
			'<div class="jet-smart-listing__post-title post-title-%2$s">%1$s</div>',
			$context
		);

		$title_text = $this->__trim_title( get_the_title(), $context );

		switch ( $context ) {
			case 'featured':

				if ( 'boxed' === $layout ) {
					$title = sprintf( '%1$s', $title_text, get_permalink() );
				} else {
					$title = sprintf( '<a href="%2$s">%1$s</a>', $title_text, get_permalink() );
				}

				break;

			default:
				$title = sprintf( '<a href="%2$s">%1$s</a>', $title_text, get_permalink() );
				break;
		}

		$this->__render_meta( 'title_related', 'jet-title-fields', array( 'before' ), $settings );

		printf( $format, $title, $context );

		$this->__render_meta( 'title_related', 'jet-title-fields', array( 'after' ), $settings );
	}

	/**
	 * Trim post title.
	 *
	 * @param string $title   Post title.
	 * @param string $context Context.
	 *
	 * @return string
	 */
	public function __trim_title( $title, $context ) {

		$settings = $this->__get_widget_settings();

		switch ( $context ) {
			case 'featured':

				if ( ! isset( $settings['featured_title_length'] ) ) {
					return $title;
				}

				$length = absint( $settings['featured_title_length'] );

				break;

			default:
				if ( ! isset( $settings['title_length'] ) ) {
					return $title;
				}

				$length = absint( $settings['title_length'] );

				break;
		}

		if ( 0 === $length ) {
			return $title;
		}

		return wp_trim_words( $title, $length, '...' );
	}

	/**
	 * Print string of posts wrapper classes
	 *
	 * @return void
	 */
	public function __listing_classes( $classes = array() ) {

		$settings = $this->__get_widget_settings();
		$classes  = array_merge(
			array(
				'jet-smart-listing',
			),
			$classes
		);

		$classes   = apply_filters( 'jet-blog/smart-listing/listing-wrapper/classes', $classes, $settings );
		$columns   = $settings['posts_columns'];
		$classes[] = 'columns-' . $columns;
		$featured  = $settings['featured_post'];
		$rows      = ! empty( $settings['posts_rows'] ) ? absint( $settings['posts_rows'] ) : 3;
		$classes[] = 'rows-' . $rows;

		if ( ! empty( $settings['posts_columns_tablet'] ) ) {
			$classes[] = 'columns-tablet-' . $settings['posts_columns_tablet'];
		}

		if ( ! empty( $settings['posts_columns_mobile'] ) ) {
			$classes[] = 'columns-mobile-' . $settings['posts_columns_mobile'];
		}

		if ( 'yes' === $featured ) {
			$classes[] = 'has-featured-position-' . $settings['featured_position'];
		} else {
			$classes[] = 'no-featured';
		}

		echo implode( ' ', $classes );
	}

	/**
	 * Post classes list
	 *
	 * @return void
	 */
	public function __post_classes( $classes = array() ) {

		$settings = $this->__get_widget_settings();
		$classes  = array_merge(
			array(
				'jet-smart-listing__post',
			),
			$classes
		);

		$show_image = isset( $settings['show_image'] ) ? $settings['show_image'] : '';

		if ( 'yes' === $show_image && has_post_thumbnail() ) {
			$classes[] = 'has-post-thumb';
		}

		$classes   = apply_filters( 'jet-blog/smart-listing/post/classes', $classes, $settings );
		$thumb_pos = $settings['image_position'];
		$classes[] = 'has-thumb-postion-' . $thumb_pos;

		echo implode( ' ', $classes );
	}

	/**
	 * Print string of featured post item classes
	 *
	 * @return void
	 */
	public function __featured_post_classes( $classes = array() ) {

		$settings = $this->__get_widget_settings();
		$layout   = $settings['featured_layout'];
		$position = $settings['featured_position'];
		$img_pos  = $settings['featured_image_position'];

		$classes =  array_merge(
			array(
				'jet-smart-listing__featured',
				'featured-layout-' . $layout,
				'featured-position-' . $position,
			),
			$classes
		);

		if ( 'simple' === $layout ) {
			$classes[] = 'featured-img-' . $img_pos;
		}

		if ( has_post_thumbnail() ) {
			$classes[] = 'has-post-thumb';
		}

		$classes = apply_filters( 'jet-blog/smart-listing/featured-post/classes', $classes, $settings );

		echo implode( ' ', $classes );
	}

	/**
	 * Check if first post must be removed from query and include custom template for it.
	 *
	 * @return void
	 */
	public function __maybe_adjust_query() {

		$query    = $this->__get_query();
		$featured = $this->__get_widget_settings( 'featured_post' );

		if ( 'yes' === $featured && isset( $query[0] ) ) {
			$this->__set_query( array( $query[0] ) );
			$template = $this->__get_global_template( 'featured-post' );
			include $template;
			unset( $query[ 0 ] );
			$this->__set_query( $query );
		}

	}

	/**
	 * Show post categories depends on settings
	 *
	 * @return void|null
	 */
	public function __post_terms( $is_featured = false ) {

		$settings = $this->__get_widget_settings();

		if ( $is_featured ) {
			$show_key = 'show_featured_terms';
			$tax_key  = 'show_featured_terms_tax';
		} else {
			$show_key = 'show_terms';
			$tax_key  = 'show_terms_tax';
		}

		$show     = isset( $settings[ $show_key ] ) ? $settings[ $show_key ] : '';
		$tax      = isset( $settings[ $tax_key ] ) ? $settings[ $tax_key ] : '';

		if ( 'yes' !== $show ) {
			return;
		}

		$terms = wp_get_post_terms( get_the_ID(), esc_attr( $tax ) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$num   = 1;
		$terms = array_slice( $terms, 0, $num );

		$format = apply_filters(
			'jet-blog/smart-listing/post-term-format',
			'<a href="%2$s" class="jet-smart-listing__terms-link">%1$s</a>'
		);

		$result = '';

		foreach ( $terms as $term ) {
			$result .= sprintf( $format, $term->name, get_term_link( (int) $term->term_id, $tax ) );
		}

		printf( '<div class="jet-smart-listing__terms">%s</div>', $result );

	}

	/**
	 * Get meta data array
	 *
	 * @return [type] [description]
	 */
	public function __get_meta( $is_featured = false ) {

		$settings = $this->__get_widget_settings();

		$show = array(
			'author'   => ( true === $is_featured ) ? 'featured_show_author' : 'show_author',
			'date'     => ( true === $is_featured ) ? 'featured_show_date' : 'show_date',
			'comments' => ( true === $is_featured ) ? 'featured_show_comments' : 'show_comments',
		);

		$html = array(
			'author' => array(
				'featured_boxed' => '<span class="posted-by post-meta__item jet-smart-listing__meta-item">%1$s<span %3$s %4$s>%5$s%6$s</span></span>',
				'simple' => '<span class="posted-by post-meta__item jet-smart-listing__meta-item">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
			),
			'date' => array(
				'featured_boxed' => '<span class="post__date post-meta__item jet-smart-listing__meta-item">%1$s<span %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></span></span>',
				'simple' => '<span class="post__date post-meta__item jet-smart-listing__meta-item">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a></span>',
			),
			'comments' => array(
				'featured_boxed' => '<span class="post__comments post-meta__item jet-smart-listing__meta-item">%1$s<span %3$s %4$s>%5$s%6$s</span></span>',
				'simple' => '<span class="post__comments post-meta__item jet-smart-listing__meta-item">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
			),
		);

		$icon_format     = '<i class="jet-smart-listing__meta-icon %s"></i>';
		$is_featured_box = false;
		$result          = array();

		if ( true === $is_featured && 'boxed' === $settings['featured_layout'] ) {
			$is_featured_box = true;
		}

		foreach ( $show as $key => $setting ) {

			$prefix = ( ! empty( $settings[ $setting . '_icon' ] ) ) ? sprintf( $icon_format, $settings[ $setting . '_icon' ] ) : '';

			if ( $is_featured_box ) {
				$current_html = $html[ $key ]['featured_boxed'];
			} else {
				$current_html = $html[ $key ]['simple'];
			}

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
	 * Return taxonomies list for filter
	 *
	 * @return array
	 */
	public function get_filter_taxonomies() {

		$allowed_types = $this->allowed_types_for_filter();
		$result        = array();

		foreach ( $allowed_types as $type ) {

			$taxonomies = get_object_taxonomies( $type, 'objects' );

			if ( ! empty( $taxonomies ) ) {
				foreach ( $taxonomies as $tax ) {
					if ( $tax->public ) {
						$result[ $tax->name ] = $tax->label;
					}
				}
			}

		}

		if ( isset( $result['product_shipping_class'] ) ) {
			unset( $result['product_shipping_class'] );
		}

		return $result;

	}

	/**
	 * Return allowed post types for filters
	 *
	 * @return array|boolean
	 */
	public function allowed_types_for_filter() {

		if ( false !== $this->_allowed_types ) {
			return $this->_allowed_types;
		}

		$allowed_types = jet_blog_settings()->get( 'allow_filter_for', 'post' );

		if ( ! $allowed_types ) {
			$allowed_types = array( 'post' );
		}

		if ( ! is_array( $allowed_types ) ) {
			$allowed_types = array( $allowed_types );
		}

		$this->_allowed_types = $allowed_types;

		return $allowed_types;

	}

}
