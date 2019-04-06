<?php
/**
 * Class: Jet_Elements_Video
 * Name: Video Player
 * Slug: jet-video
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Video extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-video';
	}

	public function get_title() {
		return esc_html__( 'Video Player', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-41';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		if ( jet_elements_integration()->in_elementor() ) {
			return array( 'mediaelement' );
		} elseif ( 'mejs' === $this->get_settings_for_display( 'self_hosted_player' ) ) {
			return array( 'mediaelement' );
		}

		return array();
	}

	public function get_style_depends() {
		if ( jet_elements_integration()->in_elementor() ) {
			return array( 'mediaelement' );
		} elseif ( 'mejs' === $this->get_settings_for_display( 'self_hosted_player' ) ) {
			return array( 'mediaelement' );
		}

		return array();
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/video/css-scheme',
			array(
				'wrapper'        => '.jet-video',
				'overlay'        => '.jet-video__overlay',
				'play_btn'       => '.jet-video__play-button',
				'play_btn_icon'  => '.jet-video__play-button-icon',
				'play_btn_image' => '.jet-video__play-button-image',

				'mejs_controls'            => '.jet-video .mejs-controls',
				'mejs_play_pause_btn_wrap' => '.jet-video .mejs-playpause-button',
				'mejs_play_pause_btn'      => '.jet-video .mejs-playpause-button > button',
				'mejs_time'                => '.jet-video .mejs-time',
				'mejs_current_time'        => '.jet-video .mejs-currenttime',
				'mejs_duration_time'       => '.jet-video .mejs-duration',
				'mejs_rail_progress'       => '.jet-video .mejs-time-rail',
				'mejs_total_progress'      => '.jet-video .mejs-time-total',
				'mejs_current_progress'    => '.jet-video .mejs-time-current',
				'mejs_volume_btn_wrap'     => '.jet-video .mejs-volume-button',
				'mejs_volume_btn'          => '.jet-video .mejs-volume-button > button',
				'mejs_volume_slider_hor'   => '.jet-video .mejs-horizontal-volume-slider',
				'mejs_total_volume_hor'    => '.jet-video .mejs-horizontal-volume-total',
				'mejs_current_volume_hor'  => '.jet-video .mejs-horizontal-volume-current',
				'mejs_fullscreen_btn_wrap' => '.jet-video .mejs-fullscreen-button',
				'mejs_fullscreen_btn'      => '.jet-video .mejs-fullscreen-button > button',
			)
		);

		/**
		 * `Video` Section
		 */
		$this->start_controls_section(
			'section_video',
			array(
				'label' => esc_html__( 'Video', 'jet-elements' ),
			)
		);

		$this->add_control(
			'video_type',
			array(
				'label'   => esc_html__( 'Video Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => array(
					'youtube'     => esc_html__( 'YouTube', 'jet-elements' ),
					'vimeo'       => esc_html__( 'Vimeo', 'jet-elements' ),
					'self_hosted' => esc_html__( 'Self Hosted', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'youtube_url',
			array(
				'label'       => esc_html__( 'YouTube URL', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your URL', 'jet-elements' ),
				'default'     => 'https://www.youtube.com/watch?v=KQ_tqgnvJdQ&list=PLdaVCVrkty71kPjyLIW8qBl93jP8tgoLJ',
				'condition' => array(
					'video_type' => 'youtube',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'vimeo_url',
			array(
				'label'       => esc_html__( 'Vimeo URL', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your URL', 'jet-elements' ),
				'default'     => 'https://vimeo.com/235215203',
				'condition' => array(
					'video_type' => 'vimeo',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'self_hosted_player',
			array(
				'label'   => esc_html__( 'Player', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'html5',
				'options' => array(
					'html5' => esc_html__( 'Default HTML5', 'jet-elements' ),
					'mejs'  => esc_html__( 'MediaElement Player', 'jet-elements' ),
				),
				'condition' => array(
					'video_type' => 'self_hosted',
				),
			)
		);

		$this->add_control(
			'mejs_player_desc',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'MediaElement Player support MP4 and WebM video formats', 'jet-elements' ),
				'content_classes' => 'elementor-descriptor',
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
				),
			)
		);

		$this->add_control(
			'self_hosted_url',
			array(
				'label'      => esc_html__( 'Self Hosted URL', 'jet-elements' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'condition' => array(
					'video_type' => 'self_hosted',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::MEDIA_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'start_time',
			array(
				'label'       => esc_html__( 'Start Time (in seconds)', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'condition' => array(
					'loop' => '',
				),
			)
		);

		$this->add_control(
			'end_time',
			array(
				'label'       => esc_html__( 'End Time (in seconds)', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'condition' => array(
					'loop' => '',
					'video_type' => array( 'youtube', 'self_hosted' ),
				),
			)
		);

		$this->add_control(
			'aspect_ratio',
			array(
				'label'   => esc_html__( 'Aspect Ratio', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '16-9',
				'options' => array(
					'16-9' => '16:9',
					'21-9' => '21:9',
					'4-3'  => '4:3',
					'3-2'  => '3:2',
					'1-1'  => '1:1',
				),
				'condition' => array(
					'video_type' => array( 'youtube', 'vimeo' ),
				),
			)
		);
		
		$this->add_control(
			'video_options_heading',
			array(
				'label'     => esc_html__( 'Video Options', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'   => esc_html__( 'Autoplay', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'mute',
			array(
				'label'   => esc_html__( 'Mute', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'   => esc_html__( 'Loop', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'controls',
			array(
				'label'     => esc_html__( 'Player Controls', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'jet-elements' ),
				'label_off' => esc_html__( 'Hide', 'jet-elements' ),
				'default'   => 'yes',
				'condition' => array(
					'video_type!' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'yt_modestbranding',
			array(
				'label'     => esc_html__( 'Modest Branding', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'video_type' => 'youtube',
					'controls'   => 'yes',
				),
			)
		);

		$this->add_control(
			'yt_suggested_videos',
			array(
				'label'   => esc_html__( 'Suggested Videos', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''    => esc_html__( 'Current Video Channel', 'jet-elements' ),
					'yes' => esc_html__( 'Any Video', 'jet-elements' ),
				),
				'condition' => array(
					'video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'yt_privacy_mode',
			array(
				'label'       => esc_html__( 'Privacy Mode', 'jet-elements' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'jet-elements' ),
				'default'     => '',
				'condition'   => array(
					'video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'vimeo_controls_color',
			array(
				'label' => esc_html__( 'Controls Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'alpha' => false,
				'condition' => array(
					'video_type' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'mejs_controls',
			array(
				'label'       => esc_html__( 'Controls Visibility', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options' => array(
					'current'    => esc_html__( 'Current Time', 'jet-elements' ),
					'duration'   => esc_html__( 'Duration Time', 'jet-elements' ),
					'volume'     => esc_html__( 'Volume', 'jet-elements' ),
					'fullscreen' => esc_html__( 'Fullscreen Button', 'jet-elements' ),
				),
				'default'   => array( 'current', 'duration', 'volume', 'fullscreen' ),
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'download_button',
			array(
				'label'       => esc_html__( 'Download Button', 'jet-elements' ),
				'description' => esc_html__( 'If browser supports', 'jet-elements' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'jet-elements' ),
				'label_off'   => esc_html__( 'Hide', 'jet-elements' ),
				'default'     => '',
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'html5',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'poster',
			array(
				'label' => esc_html__( 'Poster', 'jet-elements' ),
				'type'  => Controls_Manager::MEDIA,
				'condition' => array(
					'video_type' => 'self_hosted',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumbnail_overlay',
			array(
				'label' => esc_html__( 'Thumbnail Overlay', 'jet-elements' ),
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'   => esc_html__( 'Show Custom Thumbnail', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'thumbnail',
			array(
				'label' => esc_html__( 'Thumbnail', 'jet-elements' ),
				'type'  => Controls_Manager::MEDIA,
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label' => esc_html__( 'Overlay Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['overlay'] . ':before' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_play_button',
			array(
				'label' => esc_html__( 'Play Button', 'jet-elements' ),
			)
		);

		$this->add_control(
			'show_play_button',
			array(
				'label' => esc_html__( 'Show Play Button', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'play_button_type',
			array(
				'label'   => esc_html__( 'Play Button Type', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'icon',
				'toggle'  => false,
				'options' => array(
					'icon' => array(
						'title' => esc_html__( 'Icon', 'jet-elements' ),
						'icon'  => 'fa fa-play',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'jet-elements' ),
						'icon'  => 'fa fa-picture-o',
					)
				),
				'condition' => array(
					'show_play_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'play_button_icon',
			array(
				'label'   => esc_html__( 'Icon', 'jet-elements' ),
				'type'    => Controls_Manager::ICON,
				'default' => 'fa fa-play',
				'condition' => array(
					'show_play_button' => 'yes',
					'play_button_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'play_button_image',
			array(
				'label' => esc_html__( 'Image', 'jet-elements' ),
				'type'  => Controls_Manager::MEDIA,
				'condition' => array(
					'show_play_button' => 'yes',
					'play_button_type' => 'image',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			array(
				'label' => esc_html__( 'Video', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'render_type' => 'template',
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 2000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-jet-video' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
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
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto; margin-right: 0;',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-jet-video' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'video_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'video_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrapper'],
			)
		);
		
		$this->add_control(
			'video_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'video_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrapper'],
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'video_css_filters',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrapper'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_play_button_style',
			array(
				'label' => esc_html__( 'Play Button', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_play_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'play_button_size',
			array(
				'label' => esc_html__( 'Icon/Image Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'play_button_image_border_radius',
			array(
				'label'      => esc_html__( 'Image Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn_image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'play_button_type' => 'image',
				),
			)
		);

		$this->start_controls_tabs( 'play_button_tabs' );

		$this->start_controls_tab( 'play_button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'play_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'play_button_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['play_btn'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'play_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['play_btn'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'play_button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'play_button_color_hover',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['overlay'] . ':hover ' . $css_scheme['play_btn'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'play_button_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['overlay'] . ':hover ' . $css_scheme['play_btn'],
			)
		);

		$this->add_control(
			'play_button_border_color_hover',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['overlay'] . ':hover ' . $css_scheme['play_btn'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'play_button_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'play_button_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['overlay'] . ':hover ' . $css_scheme['play_btn'],
			)
		);

		$this->add_control(
			'play_button_hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'jet-elements' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'play_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'play_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'play_button_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['play_btn'],
			)
		);

		$this->add_control(
			'play_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Player Controls` Style Section
		 */
		$this->start_controls_section(
			'section_controls_style',
			array(
				'label' => esc_html__( 'Player Controls Container', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'controls_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_controls'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'controls_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_controls'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'controls_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_controls'],
			)
		);

		$this->end_controls_section();

		/**
		 * `Player Play-Pause Button and Time` Style Section
		 */
		$this->start_controls_section(
			'section_play_button_and_time_style',
			array(
				'label' => esc_html__( 'Player Play-Pause Button and Time', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'play_pause_button_heading',
			array(
				'label' => esc_html__( 'Play-Pause Button', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'play_pause_button_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'play_pause_button_style' );

		$this->start_controls_tab(
			'play_pause_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'play_pause_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_pause_button_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'play_pause_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'play_pause_button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_pause_button_hover_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'play_pause_button_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] . ':hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'play_pause_button_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'play_pause_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'play_pause_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'play_pause_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'play_pause_button_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'play_pause_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_play_pause_btn'],
			)
		);

		$this->add_control(
			'time_heading',
			array(
				'label'     => esc_html__( 'Time', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'time_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_time'],
			)
		);

		$this->add_control(
			'time_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_time'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'current_time_margin',
			array(
				'label'      => esc_html__( 'Current Time Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_current_time'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'duration_time_margin',
			array(
				'label'      => esc_html__( 'Duration Time Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_duration_time'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Player Progress` Style Section
		 */
		$this->start_controls_section(
			'section_progress_style',
			array(
				'label' => esc_html__( 'Player Progress', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'total_progress_heading',
			array(
				'label'     => esc_html__( 'Total Progress Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'total_progress_height',
			array(
				'label' => esc_html__( 'Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_total_progress'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'total_progress_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_total_progress'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'total_progress_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_total_progress'],
			)
		);

		$this->add_control(
			'total_progress_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_total_progress'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rail_progress_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_rail_progress'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'current_progress_heading',
			array(
				'label'     => esc_html__( 'Current Progress Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'current_progress_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_current_progress'],
			)
		);

		$this->add_control(
			'current_progress_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_current_progress'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Player Volume` Style Section
		 */
		$this->start_controls_section(
			'section_volume_style',
			array(
				'label' => esc_html__( 'Player Volume', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'volume_button_style_heading',
			array(
				'label' => esc_html__( 'Volume Button', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'volume_button_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'volume_button_style' );

		$this->start_controls_tab(
			'volume_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'volume_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'volume_button_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'volume_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'volume_button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'volume_button_hover_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'volume_button_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] . ':hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'volume_button_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'volume_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'volume_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'volume_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'volume_button_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'volume_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_volume_btn'],
			)
		);

		$this->add_control(
			'volume_slider_style_heading',
			array(
				'label' => esc_html__( 'Volume Slider', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'volume_slider_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_volume_slider_hor'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'total_volume_bar_style_heading',
			array(
				'label' => esc_html__( 'Total Volume Bar', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'total_volume_hor_width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_total_volume_hor'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'total_volume_hor_height',
			array(
				'label' => esc_html__( 'Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_total_volume_hor'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'total_volume_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_total_volume_hor'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'total_volume_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_total_volume_hor'],
			)
		);

		$this->add_control(
			'total_volume_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_total_volume_hor'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'current_volume_heading',
			array(
				'label'     => esc_html__( 'Current Volume Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'current_volume_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mejs_current_volume_hor'],
			)
		);

		$this->add_control(
			'current_volume_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_current_volume_hor'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Player Fullscreen` Style Section
		 */
		$this->start_controls_section(
			'section_fullscreen_button_style',
			array(
				'label' => esc_html__( 'Player Fullscreen Button', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'video_type' => 'self_hosted',
					'self_hosted_player' => 'mejs',
					'controls' => 'yes',
				),
			)
		);

		$this->add_control(
			'fullscreen_button_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_fullscreen_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'fullscreen_button_style' );

		$this->start_controls_tab(
			'fullscreen_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'fullscreen_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_fullscreen_btn'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'fullscreen_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'fullscreen_button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_fullscreen_btn'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'fullscreen_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mejs_fullscreen_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	public function get_video_url() {
		$settings = $this->get_settings_for_display();
		$video_url = '';

		if ( 'self_hosted' === $settings['video_type'] ) {
			$video_url = is_array( $settings['self_hosted_url'] ) ? $settings['self_hosted_url']['url'] : $settings['self_hosted_url'];

			if ( ! $video_url ) {
				return '';
			}

			if ( empty( $settings['start_time'] ) && empty( $settings['end_time'] ) ) {
				return esc_url( $video_url );
			}

			$video_url .= '#t=';

			if ( $settings['start_time'] ) {
				$video_url .= $settings['start_time'];
			}

			if ( $settings['end_time'] ) {
				$video_url .= ',' . $settings['end_time'];
			}
		} else {
			$video_url = $settings[ $settings['video_type'] . '_url' ];
		}

		return esc_url( $video_url );
	}

	public function get_video_html() {
		$settings   = $this->get_settings_for_display();
		$video_url  = $this->get_video_url();
		$video_html = '';

		if ( 'self_hosted' === $settings['video_type'] ) {
			$self_hosted_params = $this->get_self_hosted_params();

			$this->add_render_attribute( 'video_player', 'class', 'jet-video-player' );
			$this->add_render_attribute( 'video_player', 'class', sprintf( 'jet-video-%s-player', esc_attr( $settings['self_hosted_player'] ) ) );
			$this->add_render_attribute( 'video_player', 'src', $video_url );
			$this->add_render_attribute( 'video_player', $self_hosted_params );

			if ( filter_var( $settings['show_play_button'], FILTER_VALIDATE_BOOLEAN ) ) {
				$this->add_render_attribute( 'video_player', 'class', 'jet-video-custom-play-button' );
			}

			$video_html = '<video ' . $this->get_render_attribute_string( 'video_player' ) . '></video>';
		} else {
			$embed_params  = $this->get_embed_params();
			$embed_options = $this->get_embed_options();

			$embed_attr = array(
				'class' => 'jet-video-iframe',
				'allow' => 'autoplay;encrypted-media',
			);

			$video_html = Embed::get_embed_html( $video_url, $embed_params, $embed_options, $embed_attr );
		}

		return $video_html;
	}

	public function get_self_hosted_params() {
		$settings = $this->get_settings_for_display();

		$params = array();

		foreach ( array( 'autoplay', 'loop', 'controls' ) as $param_name ) {
			if ( filter_var( $settings[ $param_name ], FILTER_VALIDATE_BOOLEAN ) ) {
				$params[ $param_name ] = '';
			}
		}

		if ( filter_var( $settings['mute'], FILTER_VALIDATE_BOOLEAN ) ) {
			$params['muted'] = '';
		}

		if ( isset( $settings['download_button'] ) && ! filter_var( $settings['download_button'], FILTER_VALIDATE_BOOLEAN ) ) {
			$params['controlsList'] = 'nodownload';
		}

		if ( ! empty( $settings['poster']['url'] ) ) {
			$params['poster'] = esc_url( $settings['poster']['url'] );
		}

		if ( 'mejs' === $settings['self_hosted_player'] ) {
			$params['style'] = 'max-width: 100%;';

			$default_controls    = array( 'playpause', 'progress' );
			$additional_controls = isset( $settings['mejs_controls'] ) ? $settings['mejs_controls'] : array();

			$controls = array_merge( $default_controls, $additional_controls );

			if ( in_array( 'current', $controls ) ) {
				$controls[1] = 'current';
				$controls[2] = 'progress';
			}

			$params['data-controls'] = esc_attr( json_encode( $controls ) );
		}

		return $params;
	}

	public function get_embed_params() {
		$settings = $this->get_settings_for_display();

		$params = array();
		$params_dictionary = array();

		switch ( $settings['video_type'] ) :
			case 'youtube':
				$params_dictionary = array(
					'autoplay' => 'autoplay',
					'loop' => 'loop',
					'controls' => 'controls',
					'mute' => 'mute',
					'yt_suggested_videos' =>'rel',
					'yt_modestbranding' => 'modestbranding',
				);

				if ( $settings['loop'] ) {
					$video_properties = Embed::get_video_properties( esc_url( $settings['youtube_url'] ) );

					$params['playlist'] = $video_properties['video_id'];
				}

				$params['start'] = $settings['start_time'];
				$params['end']   = $settings['end_time'];
				$params['wmode'] = 'opaque';

				break;

			case 'vimeo':
				$params_dictionary = array(
					'autoplay' => 'autoplay',
					'loop' => 'loop',
					'mute' => 'muted',
				);

				if ( ! empty( $settings['vimeo_controls_color'] ) ) {
					$params['color'] = str_replace( '#', '', $settings['vimeo_controls_color'] );
				}

				$params['autopause'] = '0';

				break;

		endswitch;

		foreach ( $params_dictionary as $setting_name => $param_name ) {

			$param_value = filter_var( $settings[ $setting_name ], FILTER_VALIDATE_BOOLEAN ) ? '1' : '0';

			$params[ $param_name ] = $param_value;
		}

		return $params;
	}

	public function get_embed_options() {
		$settings = $this->get_settings_for_display();

		$embed_options = array();

		switch ( $settings['video_type'] ) :
			case 'youtube':
				$embed_options['privacy'] = filter_var( $settings['yt_privacy_mode'], FILTER_VALIDATE_BOOLEAN );

				break;

			case 'vimeo':
				$embed_options['start'] = $settings['start_time'];

				break;

		endswitch;

		$thumb_url = $this->get_thumbnail_url();

		$embed_options['lazy_load'] = ! empty( $thumb_url );

		return $embed_options;
	}

	public function has_custom_thumbnail() {
		$settings = $this->get_settings_for_display();

		return ! empty( $settings['thumbnail']['url'] ) && filter_var( $settings['show_thumbnail'], FILTER_VALIDATE_BOOLEAN );
	}

	public function get_iframe_thumbnail_url( $url ) {
		$settings  = $this->get_settings_for_display();

		$oembed = _wp_oembed_get_object();
		$data   = $oembed->get_data( $url );

		$thumb_url = $data->thumbnail_url;

		if ( 'youtube' === $settings['video_type'] ) {
			$thumb_url = str_replace( '/hqdefault.', '/maxresdefault.', $thumb_url );
		}

		return esc_url( $thumb_url );
	}

	public function get_thumbnail_url() {
		$settings  = $this->get_settings_for_display();
		$thumb_url = '';

		if ( $this->has_custom_thumbnail() ) {
			$thumb_url = Group_Control_Image_Size::get_attachment_image_src( $settings['thumbnail']['id'], 'thumbnail', $settings );
		} elseif( in_array( $settings['video_type'], array( 'youtube', 'vimeo' ) ) ) {
			$thumb_url = $this->get_iframe_thumbnail_url( $this->get_video_url() );
		}

		if ( empty( $thumb_url ) ) {
			return '';
		}

		return esc_url( $thumb_url );
	}
}
