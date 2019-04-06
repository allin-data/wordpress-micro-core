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

class Jet_Blog_Video_Playlist extends Jet_Blog_Base {

	public function get_name() {
		return 'jet-blog-video-playlist';
	}

	public function get_title() {
		return esc_html__( 'Video Playlist', 'jet-blog' );
	}

	public function get_icon() {
		return 'jet-blog-40';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'youtube-iframe-api', 'vimeo-iframe-api' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-blog/video-playlist/css-scheme',
			array(
				'canvas'          => '.jet-blog-playlist__canvas',
				'thumb_list'      => '.jet-blog-playlist__items',
				'thumb_item'      => '.jet-blog-playlist__item',
				'thumb_title'     => '.jet-blog-playlist__item-title',
				'thumb_duration'  => '.jet-blog-playlist__item-duration',
				'thumb_index'     => '.jet-blog-playlist__item-index',
				'heading'         => '.jet-blog-playlist__heading',
				'heading_icon'    => '.jet-blog-playlist__heading-icon',
				'heading_text'    => '.jet-blog-playlist__heading-title',
				'heading_counter' => '.jet-blog-playlist__counter',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Items', 'jet-blog' ),
			)
		);

		$this->add_control(
			'set_key',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => sprintf(
					esc_html__( 'Please set Google API key to correctly get data for YouTube videos. You can create own API key  %1$s. Paste created key on %2$s', 'jet-blog' ),
					'<a target="_blank" href="https://console.developers.google.com/apis/dashboard">' . esc_html__( 'here', 'jet-blog' ) . '</a>',
					'<a target="_blank" href="' . jet_blog_settings()->get_settings_page_link() . '">' . esc_html__( 'settings page', 'jet-blog' ) . '</a>'
				)
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Leave empty to automatically get title from video', 'jet-blog' ),
			)
		);

		$repeater->add_control(
			'url',
			array(
				'label'       => esc_html__( 'URL', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'YouTube or Vimeo video URL', 'jet-blog' ),
			)
		);

		$this->add_control(
			'videos_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'title' => '',
						'url'   => 'https://www.youtube.com/watch?v=kB4U67tiQLA',
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-blog' ),
			)
		);

		$this->add_responsive_control(
			'playlist_height',
			array(
				'label'       => esc_html__( 'Playlist Height', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 450,
				'min'         => 150,
				'max'         => 1000,
				'step'        => 1,
				'devices'     => array( 'desktop', 'tablet' ),
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['canvas']              => 'height: {{VALUE}}px;',
					'{{WRAPPER}} .jet-blog-playlist.jet-tumbs-vertical' => 'height: {{VALUE}}px;',
					'{{WRAPPER}} .jet-blog-playlist__embed-wrap'        => 'padding-bottom: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'thumbnails_orientation',
			array(
				'label'   => esc_html__( 'Thumbnails Orientation', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'jet-blog' ),
					'horizontal' => esc_html__( 'Horizontal', 'jet-blog' ),
				),
			)
		);

		$this->add_control(
			'thumbnails_v_position',
			array(
				'label'   => esc_html__( 'Thumbnails Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-blog' ),
					'right' => esc_html__( 'Right', 'jet-blog' ),
				),
				'condition' => array(
					'thumbnails_orientation' => 'vertical',
				),
			)
		);

		$this->add_control(
			'thumbnails_h_position',
			array(
				'label'   => esc_html__( 'Thumbnails Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-blog' ),
					'bottom' => esc_html__( 'Bottom', 'jet-blog' ),
				),
				'condition' => array(
					'thumbnails_orientation' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnails_width',
			array(
				'label'       => esc_html__( 'Thumbnails List Width (%)', 'jet-blog' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'devices'     => array( 'desktop', 'tablet' ),
				'default'     => array(
					'unit' => '%',
					'size' => 33,
				),
				'range'     => array(
					'%' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 0.1,
					),
				),
				'condition' => array(
					'thumbnails_orientation' => 'vertical',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-tumbs-vertical ' . $css_scheme['thumb_list'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_item_width',
			array(
				'label'      => esc_html__( 'Thumbnail Column Width (in pixels)', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
				),
				'condition' => array(
					'thumbnails_orientation' => 'horizontal',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-tumbs-horizontal ' . $css_scheme['thumb_item'] => 'width: {{SIZE}}px; flex: 0 0 {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => esc_html__( 'Show Thumbnails Heading', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'heading_width',
			array(
				'label'      => esc_html__( 'Heading Column Width (in pixels)', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 195,
				),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
				),
				'condition' => array(
					'thumbnails_orientation' => 'horizontal',
					'show_heading'           => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-tumbs-horizontal ' . $css_scheme['heading'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_text',
			array(
				'label'     => esc_html__( 'Heading Text', 'jet-blog' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Video Playlist', 'jet-blog' ),
				'condition' => array(
					'show_heading' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_icon',
			array(
				'type'      => Controls_Manager::ICON,
				'label'     => esc_html__( 'Heading Icon', 'jet-blog' ),
				'default'   => 'fa fa-play',
				'condition' => array(
					'show_heading' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_counter',
			array(
				'label'        => esc_html__( 'Show Videos Counter', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_heading' => 'yes',
				),
			)
		);

		$this->add_control(
			'counter_suffix',
			array(
				'label'     => esc_html__( 'Counter Suffix', 'jet-blog' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'videos', 'jet-blog' ),
				'condition' => array(
					'show_heading' => 'yes',
					'show_counter' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_item_index',
			array(
				'label'        => esc_html__( 'Show Item Number and Status', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'hide_index_tablet',
			array(
				'label'        => esc_html__( 'Hide Index On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_index_mobile',
			array(
				'label'        => esc_html__( 'Hide Index On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_item_duration',
			array(
				'label'        => esc_html__( 'Show Item Duration', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'hide_duration_tablet',
			array(
				'label'        => esc_html__( 'Hide Duration On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_duration_mobile',
			array(
				'label'        => esc_html__( 'Hide Duration On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_image_tablet',
			array(
				'label'        => esc_html__( 'Hide Thumbnail Image On Tablets', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'hide_image_mobile',
			array(
				'label'        => esc_html__( 'Hide Thumbnail Image On Mobile', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_scroll_on_hover',
			array(
				'label'        => esc_html__( 'Show Scroll Only on Hover', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'thumbnails_orientation' => 'vertical',
				),
			)
		);

		$this->add_control(
			'disable_caching',
			array(
				'label'        => esc_html__( 'Disable Caching', 'jet-blog' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Force getting videos data from API on each widget refresh. Not recommended, only for debugging.', 'jet-blog' ),
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General Styles', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'canvas_bg',
			array(
				'label'  => esc_html__( 'Canvas Background', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['canvas'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'thumbs_bg',
			array(
				'label'  => esc_html__( 'Thumbnails Background', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_list'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_bg',
			array(
				'label'  => esc_html__( 'Heading Background', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'playlist_border_radius',
			array(
				'label'      => esc_html__( 'Wrapper Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-blog-playlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_padding',
			array(
				'label'      => esc_html__( 'Heading Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_icon_styles',
			array(
				'label'     => esc_html__( 'Heading Icon', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_icon_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading_icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 90,
					),
				),
				'default' => array(
					'units' => 'px',
					'size'  => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_icon_padding',
			array(
				'label'      => esc_html__( 'Icon Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['heading_icon'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title_styles',
			array(
				'label'     => esc_html__( 'Heading Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_title_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['heading_text'],
			)
		);

		$this->add_control(
			'heading_counter_styles',
			array(
				'label'     => esc_html__( 'Heading Counter', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_counter_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-blog' ),
				'type'   => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['heading_counter'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_counter_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['heading_counter'],
				'fields_options' => array(
					'typography' => array(
						'default' => 'custom',
					),
					'font_size' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 12,
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumb_item_style',
			array(
				'label'      => esc_html__( 'Thumbnail Styles', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'thumb_item_padding',
			array(
				'label'      => esc_html__( 'Thumbnail Row Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumb_title_border',
				'label'       => esc_html__( 'Border', 'jet-blog' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['thumb_item'],
			)
		);

		$this->add_control(
			'thumb_img_gap',
			array(
				'label'      => esc_html__( 'Thumbnail Image Gap', 'jet-blog' ),
				'label_block' => true,
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-tumbs-vertical ' . $css_scheme['thumb_list'] . ' .jet-blog-playlist__item-thumb' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-tumbs-horizontal ' . $css_scheme['thumb_list'] . ' .jet-blog-playlist__item-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumb_title_styles',
			array(
				'label'     => esc_html__( 'Title', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'thumb_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['thumb_title'],
				'fields_options' => array(
					'typography' => array(
						'default' => 'custom',
					),
					'font_size' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 14,
						),
					),
					'line_height' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 18,
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_item_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumb_duration_styles',
			array(
				'label'     => esc_html__( 'Duration', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'thumb_duration_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['thumb_duration'],
				'fields_options' => array(
					'typography' => array(
						'default' => 'custom',
					),
					'font_size' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 12,
						),
					),
					'line_height' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 16,
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_item_duration_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_duration'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_thumb_item_style' );

		$this->start_controls_tab(
			'tab_thumb_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_control(
			'thumb_item_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'thumb_item_duration_color',
			array(
				'label'     => esc_html__( 'Duration Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_duration'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumb_item_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_item'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_control(
			'thumb_item_title_color_hover',
			array(
				'label'     => esc_html__( 'Title Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_item'] . ':hover ' . $css_scheme['thumb_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'thumb_item_duration_color_hover',
			array(
				'label'     => esc_html__( 'Duration Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_item'] . ':hover ' . $css_scheme['thumb_duration'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumb_item_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_item'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_item_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blog' ),
			)
		);

		$this->add_control(
			'thumb_item_title_color_active',
			array(
				'label'     => esc_html__( 'Title Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_item'] . '.jet-blog-active ' . $css_scheme['thumb_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'thumb_item_duration_color_active',
			array(
				'label'     => esc_html__( 'Duration Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_item'] . '.jet-blog-active ' . $css_scheme['thumb_duration'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumb_item_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_item'] . '.jet-blog-active',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumb_index_style',
			array(
				'label'      => esc_html__( 'Thumbnails Numbers and Status Icons', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'thumb_index_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['thumb_index'],
				'fields_options' => array(
					'typography' => array(
						'default' => 'custom',
					),
					'font_size' => array(
						'default' => array(
							'units' => 'px',
							'size'  => 12,
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_index_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb_index'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_thumb_index_style' );

		$this->start_controls_tab(
			'tab_thumb_index_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			\Jet_Blog_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'thumb_index',
				'fields_options' => array(
					'box_font_color' => array(
						'default' => '#fff',
					),
					'box_font_size' => array(
						'default'    => array(
							'unit' => 'px',
							'size' => 10,
						),
						'selectors' => array(
							'{{WRAPPER}} '. $css_scheme['thumb_index'] .' .jet-blog-playlist__item-status' => 'font-size: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_index'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_index_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			\Jet_Blog_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'thumb_index_hover',
				'fields_options' => array(
					'box_font_size' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['thumb_item'] . ':hover ' . $css_scheme['thumb_index'] . ' .jet-blog-playlist__item-status' => 'font-size: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_item'] . ':hover ' . $css_scheme['thumb_index'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_index_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blog' ),
			)
		);

		$this->add_group_control(
			\Jet_Blog_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'thumb_index_active',
				'fields_options' => array(
					'box_font_size' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['thumb_item'] . '.jet-blog-active ' . $css_scheme['thumb_index'] . ' .jet-blog-playlist__item-status' => 'font-size: {{SIZE}}{{UNIT}}',
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb_item'] . '.jet-blog-active ' . $css_scheme['thumb_index'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_scroll_style',
			array(
				'label'      => esc_html__( 'Scrollbar Styles', 'jet-blog' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'non_webkit_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Currently works only in -webkit- browsers', 'jet-blog' )
			)
		);

		$this->add_control(
			'scroll_thumb',
			array(
				'label'     => esc_html__( 'Scrollbar Thumb', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ::-webkit-scrollbar-thumb' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'scroll_track',
			array(
				'label'     => esc_html__( 'Scrollbar Track', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ::-webkit-scrollbar-track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		add_filter( 'no_texturize_tags', array( $this, 'prevent_playlist_from_texturizing' ) );
		add_filter( 'elementor/widget/render_content', array( $this, 'clear_notexturize_filter' ), 10, 2 );

		jet_blog_integration()->set_playlist_trigger();

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	/**
	 * Remove div tags from texturizing for video playlist.
	 *
	 * @param  array $tags No texturized tags array
	 * @return array
	 */
	public function prevent_playlist_from_texturizing( $tags ) {
		$tags[] = 'div';
		return $tags;
	}

	/**
	 * Remove $this->prevent_playlist_from_texturizing() from no_texturize_tags hook after tabs content processing to avoid unnecessary firing.
	 * @param  string $widget_content  Rendered widget content.
	 * @param  object $widget_instance Processed widget instance.
	 * @return string
	 */
	public function clear_notexturize_filter( $widget_content, $widget_instance ) {

		if ( 'tabs' === $widget_instance->get_name() ) {
			remove_filter( 'no_texturize_tags', array( $this, 'prevent_playlist_from_texturizing' ) );
		}

		return $widget_content;
	}

	/**
	 * Change height in iframe to new value from settings
	 *
	 * @param  [type] $html [description]
	 * @return [type]       [description]
	 */
	public function adjust_height( $html ) {

		$settings = $this->get_settings();
		$height   = $settings['playlist_height'];
		$html     = preg_replace( '/width=[\'\"]\d+[\'\"]/', 'width="100%"', $html );

		return preg_replace( '/height=[\'\"]\d+[\'\"]/', 'height="' . $height . '"', $html );
	}

	/**
	 * Print playlist container classes list
	 *
	 * @return [type] [description]
	 */
	public function __container_classes( $settings ) {

		$classes     = array( 'jet-blog-playlist' );
		$orientation = $settings['thumbnails_orientation'];
		$v_position  = $settings['thumbnails_v_position'];
		$h_position  = $settings['thumbnails_h_position'];

		$classes[] = 'jet-tumbs-' . $orientation;
		$classes[] = 'jet-tumbs-v-pos-' . $v_position;
		$classes[] = 'jet-tumbs-h-pos-' . $h_position;

		if ( 'yes' === $settings['show_scroll_on_hover'] ) {
			$classes[] = 'jet-scroll-on-hover';
		} else {
			$classes[] = 'jet-scroll-regular';
		}

		$classes = apply_filters( 'jet-blog/video-playlist/container-classes', $classes );

		echo implode( ' ', $classes );
	}

	public function __get_video_data_atts( $video_data, $item, $settings, $index ) {

		$data = array(
			'data-id'          => $item['_id'],
			'data-video_id'    => $video_data['video_id'],
			'data-provider'    => strtolower( $video_data['provider_name'] ),
			'data-html'        => json_encode( $this->adjust_height( $video_data['html'] ) ),
			'data-height'      => $settings['playlist_height'],
			'data-video_index' => absint( $index ) + 1,
		);

		$result = '';

		foreach ( $data as $key => $value ) {
			$result .= sprintf( ' %1$s=\'%2$s\'', $key, $value );
		}

		return $result;
	}

	public function __video_counter( $settings, $list ) {

		if ( 'yes' !== $settings['show_counter'] ) {
			return;
		}

		$suffix = '';

		if ( ! empty( $settings['counter_suffix'] ) ) {
			$suffix = sprintf(
				' <span class="jet-blog-playlist__counter-suffix">%s</span>',
				$settings['counter_suffix']
			);
		}

		printf(
			'<div class="jet-blog-playlist__counter"><span class="jet-blog-playlist__counter-val">1</span>/%1$s%2$s</div>',
			count( $list ),
			$suffix
		);

	}

	public function __get_hide_classes( $settings ) {

		$keys   = array( 'index', 'duration', 'image' );
		$result = array();

		foreach ( $keys as $key ) {

			$hide_this = '';
			$tablet    = 'hide_' . $key . '_tablet';
			$mobile    = 'hide_' . $key . '_mobile';

			if ( ! empty( $settings[ $tablet ] ) && 'yes' === $settings[ $tablet ] ) {
				$hide_this .= ' jet-blog-hidden-tablet';
			}

			if ( ! empty( $settings[ $mobile ] ) && 'yes' === $settings[ $mobile ] ) {
				$hide_this .= ' jet-blog-hidden-mobile';
			}

			$result[ $key ] = $hide_this;

		}

		return $result;
	}

}
