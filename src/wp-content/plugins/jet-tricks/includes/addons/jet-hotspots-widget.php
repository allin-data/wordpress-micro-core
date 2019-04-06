<?php
/**
 * Class: Jet_Hotspots_Widget
 * Name: Hotspots
 * Slug: jet-hotspots
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

class Jet_Hotspots_Widget extends Jet_Tricks_Base {

	public function get_name() {
		return 'jet-hotspots';
	}

	public function get_title() {
		return esc_html__( 'Hotspots', 'jet-tricks' );
	}

	public function get_icon() {
		return 'jettricks-icon-70';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'imagesloaded', 'jet-tricks-tippy' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-tricks/hotspots/css-scheme',
			array(
				'instance'   => '.jet-hotspots',
				'inner'      => '.jet-hotspots__inner',
				'item'       => '.jet-hotspots__item',
				'item_inner' => '.jet-hotspots__item-inner',
				'tooltip'    => '.tippy-tooltip',
			)
		);

		$this->start_controls_section(
			'section_image',
			array(
				'label' => esc_html__( 'Image', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose Image', 'jet-tricks' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hotspots',
			array(
				'label' => esc_html__( 'Hotspots', 'jet-tricks' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_hotspot' );

		$repeater->start_controls_tab(
			'tabs_hotspot_content',
			array(
				'label' => esc_html__( 'Content', 'jet-tricks' ),
			)
		);

		$repeater->add_control(
			'hotspot_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-tricks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-plus',
			)
		);

		$repeater->add_control(
			'hotspot_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'hotspot_description',
			array(
				'label' => esc_html__( 'Description', 'jet-tricks' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);

		$repeater->add_control(
			'hotspot_url',
			array(
				'label'       => esc_html__( 'Link', 'jet-tricks' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tabs_hotspot_position',
			array(
				'label' => esc_html__( 'Position', 'jet-tricks' ),
			)
		);

		$repeater->add_control(
			'horizontal_position',
			array(
				'label' => esc_html__( 'Horizontal Position(%)', 'jet-tricks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
			)
		);

		$repeater->add_control(
			'vertical_position',
			array(
				'label' => esc_html__( 'Vertical Position(%)', 'jet-tricks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hotspots',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ hotspot_text }}}',
				'default'     => array(
					array(
						'hotspot_text'        => '',
						'horizontal_position' => array(
							'size' => 50,
							'unit' => '%',
						),
						'vertical_position'   => array(
							'size' => 50,
							'unit' => '%',
						),
					)
				),
			)
		);

		$this->add_control(
			'hotspots_animation',
			array(
				'label'   => esc_html__( 'Animation', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'pulse',
				'options' => array(
					'none'   => esc_html__( 'None', 'jet-tricks' ),
					'flash'  => esc_html__( 'Flash', 'jet-tricks' ),
					'pulse'  => esc_html__( 'Pulse', 'jet-tricks' ),
					'shake'  => esc_html__( 'Shake', 'jet-tricks' ),
					'tada'   => esc_html__( 'Tada', 'jet-tricks' ),
					'rubber' => esc_html__( 'Rubber', 'jet-tricks' ),
					'swing'  => esc_html__( 'Swing', 'jet-tricks' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltip',
			array(
				'label' => esc_html__( 'Tooltip', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'tooltip_show_on_init',
			array(
				'label'        => esc_html__( 'Show On Init', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'tooltip_placement',
			array(
				'label'   => esc_html__( 'Placement', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-tricks' ),
					'bottom' => esc_html__( 'Bottom', 'jet-tricks' ),
					'left'   => esc_html__( 'Left', 'jet-tricks' ),
					'right'  => esc_html__( 'Right', 'jet-tricks' ),
				),
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'        => esc_html__( 'Use Arrow', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'tooltip_arrow_type',
			array(
				'label'   => esc_html__( 'Arrow Type', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sharp',
				'options' => array(
					'sharp' => esc_html__( 'Sharp', 'jet-tricks' ),
					'round' => esc_html__( 'Round', 'jet-tricks' ),
				),
				'condition' => array(
					'tooltip_arrow' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_arrow_size',
			array(
				'label'   => esc_html__( 'Arrow Size', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scale(1)',
				'options' => array(
					'scale(1)'     => esc_html__( 'Normal', 'jet-tricks' ),
					'scale(0.75)'  => esc_html__( 'Small', 'jet-tricks' ),
					'scaleX(0.75)' => esc_html__( 'Skinny', 'jet-tricks' ),
					'scale(1.5)'   => esc_html__( 'Large', 'jet-tricks' ),
					'scaleX(1.5)'  => esc_html__( 'Wide', 'jet-tricks' ),
				),
				'condition' => array(
					'tooltip_arrow' => 'yes',
				),
			)
		);

		$this->add_control(
			'tooltip_trigger',
			array(
				'label'   => esc_html__( 'Trigger', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mouseenter',
				'options' => array(
					'mouseenter' => esc_html__( 'Mouseenter', 'jet-tricks' ),
					'click'      => esc_html__( 'Click', 'jet-tricks' ),
					'manual'     => esc_html__( 'None', 'jet-tricks' ),
				),
			)
		);

		$this->add_control(
			'tooltip_trigger_none_desc',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Always show tooltips.', 'jet-tricks' ),
				'content_classes' => 'elementor-descriptor',
				'condition' => array(
					'tooltip_trigger' => 'manual',
				),
			)
		);

		$this->add_control(
			'tooltip_show_duration',
			array(
				'label'      => esc_html__( 'Show Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 500,
					'unit' => 'ms',
				),
				'condition' => array(
					'tooltip_trigger!' => 'manual',
				),
			)
		);

		$this->add_control(
			'tooltip_hide_duration',
			array(
				'label'      => esc_html__( 'Hide Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'ms',
				),
				'condition' => array(
					'tooltip_trigger!' => 'manual',
				),
			)
		);

		$this->add_control(
			'tooltip_delay',
			array(
				'label'      => esc_html__( 'Delay', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range'      => array(
					'ms' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 0,
					'unit' => 'ms',
				),
				'condition' => array(
					'tooltip_trigger!' => 'manual',
				),
			)
		);

		$this->add_control(
			'tooltip_distance',
			array(
				'label'      => esc_html__( 'Distance', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
					),
				),
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
			)
		);

		$this->add_control(
			'tooltip_animation',
			array(
				'label'   => esc_html__( 'Animation', 'jet-tricks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shift-toward',
				'options' => array(
					'shift-away'   => esc_html__( 'Shift-Away', 'jet-tricks' ),
					'shift-toward' => esc_html__( 'Shift-Toward', 'jet-tricks' ),
					'fade'         => esc_html__( 'Fade', 'jet-tricks' ),
					'scale'        => esc_html__( 'Scale', 'jet-tricks' ),
					'perspective'  => esc_html__( 'Perspective', 'jet-tricks' ),
				),
				'condition' => array(
					'tooltip_trigger!' => 'manual',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Hotspots Style Section
		 */
		$this->start_controls_section(
			'section_hotspot_style',
			array(
				'label'      => esc_html__( 'Hotspot', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'hotspot_padding',
			array(
				'label'      => __( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hotspot_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['item_inner'],
			)
		);

		$this->add_responsive_control(
			'hotspot_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-tricks' ),
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
				'name' => 'hotspot_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hotspot_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_inner'] . ' span',
			)
		);

		$this->start_controls_tabs( 'tabs_hotspot' );

		$this->start_controls_tab(
			'tabs_hotspot_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-tricks' ),
			)
		);

		$this->add_responsive_control(
			'hotspot_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_inner'] . ' i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hotspot_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_inner'] . ' i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hotspot_text_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_inner'] . ' span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hotspot_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_inner'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tabs_hotspot_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-tricks' ),
			)
		);

		$this->add_responsive_control(
			'hotspot_icon_size_hover',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_inner'] . ' i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hotspot_icon_color_hover',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_inner'] . ' i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hotspot_text_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_inner'] . ' span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hotspot_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_inner'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Tooltips Style Section
		 */
		$this->start_controls_section(
			'section_tooltips_style',
			array(
				'label'      => esc_html__( 'Tooltip', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'tooltip_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tooltip_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] . ' .tippy-content',
			)
		);

		$this->add_control(
			'tooltip_text_align',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-tricks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-tricks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tricks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-tricks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] . ' .tippy-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tooltip_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'],
			)
		);

		$this->add_control(
			'tooltip_arrow_color',
			array(
				'label'  => esc_html__( 'Arrow Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .tippy-popper[x-placement^=left] ' . $css_scheme['tooltip'] .' .tippy-arrow'=> 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .tippy-popper[x-placement^=right] ' . $css_scheme['tooltip'] .' .tippy-arrow'=> 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .tippy-popper[x-placement^=top] ' . $css_scheme['tooltip'] .' .tippy-arrow'=> 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' .tippy-popper[x-placement^=bottom] ' . $css_scheme['tooltip'] .' .tippy-arrow'=> 'border-bottom-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => __( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tooltip_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'],
			)
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'tooltip_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['tooltip'],
			)
		);

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$id_int = substr( $this->get_id_int(), 0, 3 );

		$settings = $this->get_settings();

		$hotspots = $settings['hotspots'];

		$json_settings = array(
			'tooltipPlacement'    => $settings['tooltip_placement'],
			'tooltipArrow'        => filter_var( $settings['tooltip_arrow'], FILTER_VALIDATE_BOOLEAN ),
			'tooltipArrowType'    => $settings['tooltip_arrow_type'],
			'tooltipArrowSize'    => $settings['tooltip_arrow_size'],
			'tooltipTrigger'      => $settings['tooltip_trigger'],
			'tooltipShowOnInit'   => filter_var( $settings['tooltip_show_on_init'], FILTER_VALIDATE_BOOLEAN ),
			'tooltipShowDuration' => $settings['tooltip_show_duration'],
			'tooltipHideDuration' => $settings['tooltip_hide_duration'],
			'tooltipDelay'        => $settings['tooltip_delay'],
			'tooltipDistance'     => $settings['tooltip_distance'],
			'tooltipAnimation'    => $settings['tooltip_animation'],
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-hotspots',
				'jet-hotspots__hotspots-' . $settings['hotspots_animation'] . '-animation',
			),
			'data-settings' => json_encode( $json_settings ),
		) );

		if ( empty( $settings['image']['id'] ) ) {
			echo sprintf( '<h3>%s</h3>', esc_html__( 'Image not defined', 'jet-tricks' ) );

			return false;
		}

		$image = sprintf( '<img class="jet-hotspots__image" src="%s" srcset="%s" alt="">',
			wp_get_attachment_image_url( $settings['image']['id'] ),
			wp_get_attachment_image_srcset( $settings['image']['id'], 'full' )
		);

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-hotspots__inner"><?php
				echo $image;?>
				<div class="jet-hotspots__container"><?php
					foreach ( $hotspots as $index => $hotspot ) {
						$hotspot_count = $index + 1;

						$is_link = ! empty( $hotspot['hotspot_url']['url'] ) ? true : false;

						$hotspot_setting_key = $this->get_repeater_setting_key( 'jet_hotspot_control', 'hotspots', $index );

						$this->add_render_attribute( $hotspot_setting_key, array(
							'id'                       => 'jet-hotspot-' . $id_int . $hotspot_count,
							'class'                    => array(
								'jet-hotspots__item',
							),
							'title'                    => $hotspot['hotspot_description'],
							'data-horizontal-position' => $hotspot['horizontal_position']['size'],
							'data-vertical-position'   => $hotspot['vertical_position']['size'],
						) );

						if ( $is_link ) {
							$this->add_render_attribute( $hotspot_setting_key, array(
								'href' => $hotspot['hotspot_url']['url'],
							) );

							if ( $hotspot['hotspot_url']['is_external'] ) {
								$this->add_render_attribute( $hotspot_setting_key, 'target', '_blank' );
							}

							if ( ! empty( $hotspot['hotspot_url']['nofollow'] ) ) {
								$this->add_render_attribute( $hotspot_setting_key, 'rel', 'nofollow' );
							}
						}

						$icon_html = '';

						if ( ! empty( $hotspot['hotspot_icon'] ) ) {
							$icon_html = sprintf( '<i class="%1$s"></i>', $hotspot['hotspot_icon'] );
						}

						$text_html = '';

						if ( ! empty( $hotspot['hotspot_text'] ) ) {
							$text_html = sprintf( '<span>%1$s</span>', $hotspot['hotspot_text'] );
						}

						$tag = ! $is_link ? 'div' : 'a';

						echo sprintf( '<%1$s %2$s><div class="jet-hotspots__item-inner">%3$s%4$s</div></%1$s>', $tag, $this->get_render_attribute_string( $hotspot_setting_key ), $icon_html, $text_html );
					}?>
				</div>
			</div>
		</div>
		<?php
	}
}
