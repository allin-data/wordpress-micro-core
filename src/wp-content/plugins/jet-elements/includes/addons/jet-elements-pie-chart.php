<?php
/**
 * Class: Jet_Elements_Pie_Chart
 * Name: Pie Chart
 * Slug: jet-pie-chart
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

/**
 * Jet Pie Chart Widget.
 */
class Jet_Elements_Pie_Chart extends Jet_Elements_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'jet-pie-chart';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Pie Chart', 'jet-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'jetelements-icon-3';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'chart-js' );
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/pie-chart/css-scheme',
			array(
				'container' => '.jet-pie-chart-container',
				'title'     => '.jet-pie-chart-title',
			)
		);

		/**
		 * `Chart Data` Section
		 */
		$this->start_controls_section(
			'section_chart_data',
			array(
				'label' => esc_html__( 'Chart Data', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label' => esc_html__( 'Label', 'jet-elements' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label' => esc_html__( 'Value', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->add_control(
			'chart_data',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => array_values( $repeater->get_controls() ),
				'default' => array(
					array(
						'label' => esc_html__( 'Google', 'jet-elements' ),
						'value' => 50,
						'color' => '#dd4b39',
					),
					array(
						'label' => esc_html__( 'Facebook', 'jet-elements' ),
						'value' => 50,
						'color' => '#3b5998',
					),
					array(
						'label' => esc_html__( 'Twitter', 'jet-elements' ),
						'value' => 50,
						'color' => '#55acee',
					),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->add_control(
			'chart_title',
			array(
				'label'     => esc_html__( 'Chart Title', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Pie Chart', 'jet-elements' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'chart_title_size',
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
				'default' => 'h5',
			)
		);

		$this->add_control(
			'chart_title_position',
			array(
				'label'   => esc_html__( 'Title Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'before' => esc_html__( 'Before Chart', 'jet-elements' ),
					'after'  => esc_html__( 'After Chart', 'jet-elements' ),
				),
				'default' => 'after',
			)
		);

		$this->end_controls_section();

		/**
		 * `Settings` Section
		 */
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'chart_height',
			array(
				'label' => esc_html__( 'Chart Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'chart_cutout_percentage',
			array(
				'label'       => esc_html__( 'Cutout Percentage', 'jet-elements' ),
				'description' => esc_html__( 'The percentage of the chart that is cut out of the middle.', 'jet-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range' => array(
					'%' => array(
						'min' => 0,
						'max' => 99,
					),
				),
				'default' => array(
					'unit' => '%',
				),
			)
		);

		$this->add_control(
			'chart_animation_heading',
			array(
				'label'     => esc_html__( 'Animations', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'chart_animation_duration',
			array(
				'label'       => esc_html__( 'Duration', 'jet-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'ms' ),
				'range' => array(
					'ms' => array(
						'min' => 100,
						'max' => 3000,
					),
				),
				'default' => array(
					'unit' => 'ms',
				),
			)
		);

		$animation_easing = array(
			'linear',
			'easeInQuad',
			'easeOutQuad',
			'easeInOutQuad',
			'easeInCubic',
			'easeOutCubic',
			'easeInOutCubic',
			'easeInQuart',
			'easeOutQuart',
			'easeInOutQuart',
			'easeInQuint',
			'easeOutQuint',
			'easeInOutQuint',
			'easeInSine',
			'easeOutSine',
			'easeInOutSine',
			'easeInExpo',
			'easeOutExpo',
			'easeInOutExpo',
			'easeInCirc',
			'easeOutCirc',
			'easeInOutCirc',
			'easeInElastic',
			'easeOutElastic',
			'easeInOutElastic',
			'easeInBack',
			'easeOutBack',
			'easeInOutBack',
			'easeInBounce',
			'easeOutBounce',
			'easeInOutBounce',
		);

		$this->add_control(
			'chart_animation_easing',
			array(
				'label'   => esc_html__( 'Easing', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'easeOutQuart',
				'options' => array_combine( $animation_easing, $animation_easing )
			)
		);

		$this->add_control(
			'chart_animate_scale',
			array(
				'label'        => esc_html__( 'Animate Scale', 'jet-elements' ),
				'description'  => esc_html__( 'If yes, will animate scaling the chart from the center outwards.', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'chart_legend_heading',
			array(
				'label'     => esc_html__( 'Legend', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'chart_legend_display',
			array(
				'label'        => esc_html__( 'Display', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'chart_legend_position',
			array(
				'label'   => esc_html__( 'Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'left'   => esc_html__( 'Left', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
					'right'  => esc_html__( 'Right', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'chart_legend_reverse',
			array(
				'label'        => esc_html__( 'Revers', 'jet-elements' ),
				'description'  => esc_html__( 'Legend will show datasets in reverse order.', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'chart_tooltips_heading',
			array(
				'label'     => esc_html__( 'Tooltips', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'chart_tooltip_enabled',
			array(
				'label'        => esc_html__( 'Enabled', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->end_controls_section();

		/**
		 * `Chart` Style Section
		 */
		$this->start_controls_section(
			'section_chart_style',
			array(
				'label' => esc_html__( 'Chart', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'chart_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
			)
		);

		$this->add_control(
			'chart_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->end_controls_section();

		/**
		 * `Title` Style Section
		 */
		$this->start_controls_section(
			'section_chart_title_style',
			array(
				'label' => esc_html__( 'Title', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'chart_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);
		
		$this->add_control(
			'chart_title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'chart_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_control(
			'chart_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'chart_title_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->end_controls_section();

		/**
		 * `Legend` Style Section
		 */
		$this->start_controls_section(
			'section_chart_legend_style',
			array(
				'label' => esc_html__( 'Legend', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'chart_legend_display' => 'true',
				),
			)
		);

		$this->add_control(
			'chart_legend_box_width',
			array(
				'label' => esc_html__( 'Box Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
			)
		);

		$this->add_control(
			'chart_legend_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			)
		);

		$this->add_control(
			'chart_legend_font_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			)
		);

		$typo_weight_options = array(
			'' => esc_html__( 'Default', 'jet-elements' ),
		);

		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[ $weight ] = ucfirst( $weight );
		}

		$this->add_control(
			'chart_legend_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			)
		);

		$this->add_control(
			'chart_legend_font_style',
			array(
				'label' => esc_html__( 'Font Style', 'jet-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'chart_legend_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->end_controls_section();

		/**
		 * `Tooltips` Style Section
		 */
		$this->start_controls_section(
			'section_chart_tooltips_style',
			array(
				'label' => esc_html__( 'Tooltips', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'chart_tooltip_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'chart_tooltip_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->add_control(
			'chart_tooltip_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			)
		);

		$this->add_control(
			'chart_tooltip_font_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			)
		);

		$this->add_control(
			'chart_tooltip_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			)
		);

		$this->add_control(
			'chart_tooltip_font_style',
			array(
				'label' => esc_html__( 'Font Style', 'jet-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'chart_tooltip_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$this->__context = 'render';
		$this->__open_wrap();

		$settings     = $this->get_settings_for_display();
		$data_chart   = $this->get_chart_data();
		$data_options = $this->get_chart_options();

		$this->add_render_attribute( 'container', 'class', 'jet-pie-chart-container' );
		$this->add_render_attribute( 'container', 'data-chart', esc_attr( json_encode( $data_chart ) ) );
		$this->add_render_attribute( 'container', 'data-options', esc_attr( json_encode( $data_options ) ) );

		$this->add_render_attribute( 'canvas', 'class', 'jet-pie-chart' );
		$this->add_render_attribute( 'canvas', 'role', 'img' );

		if ( ! empty( $settings['chart_title'] ) ) {
			$this->add_render_attribute( 'canvas', 'aria-label', esc_attr( $settings['chart_title'] ) );
		}

		if ( 'before' === $settings['chart_title_position'] ) {
			echo $this->get_chart_title();
		}
		?>

		<div <?php $this->print_render_attribute_string( 'container' ); ?>>
			<canvas <?php $this->print_render_attribute_string( 'canvas' ); ?>></canvas>
		</div>

		<?php

		if ( 'after' === $settings['chart_title_position'] ) {
			echo $this->get_chart_title();
		}

		$this->__close_wrap();
	}

	/**
	 * Get prepare chart data.
	 *
	 * @return array
	 */
	public function get_chart_data() {
		$settings = $this->get_settings_for_display();

		$data = array(
			'datasets' => array(
				array(
					'data'             => array(),
					'backgroundColor'  => array(),
				),
			),
			'labels' => array()
		);

		$chart_data = $settings['chart_data'];

		foreach ( $chart_data as $item_data ) {
			$data['datasets'][0]['data'][]            = ! empty( $item_data['value'] ) ? $item_data['value'] : '';
			$data['datasets'][0]['backgroundColor'][] = ! empty( $item_data['color'] ) ? $item_data['color'] : '';
			$data['labels'][]                         = ! empty( $item_data['label'] ) ? $item_data['label'] : '';
		}

		$data['datasets'][0]['borderWidth']      = ( '' !== $settings['chart_border_width']['size'] ) ? $settings['chart_border_width']['size'] : 1;
		$data['datasets'][0]['borderColor']      = ! empty( $settings['chart_border_color'] ) ? $settings['chart_border_color'] : '#ffffff';
		$data['datasets'][0]['hoverBorderColor'] = ! empty( $settings['chart_border_color'] ) ? $settings['chart_border_color'] : '#ffffff';

		return $data;
	}

	/**
	 * Get prepare chart options.
	 *
	 * @return array
	 */
	public function get_chart_options() {
		$settings = $this->get_settings_for_display();

		$legend_display   = filter_var( $settings['chart_legend_display'], FILTER_VALIDATE_BOOLEAN );
		$tooltips_enabled = filter_var( $settings['chart_tooltip_enabled'], FILTER_VALIDATE_BOOLEAN );

		$options = array(
			'animation' => array(
				'duration'     => ! empty( $settings['chart_animation_duration']['size'] ) ? $settings['chart_animation_duration']['size'] : 1000,
				'easing'       => ! empty( $settings['chart_animation_easing'] ) ? $settings['chart_animation_easing'] : 'easeOutQuart',
				'animateScale' => filter_var( $settings['chart_animate_scale'], FILTER_VALIDATE_BOOLEAN ),
			),
			'legend' => array(
				'display'  => $legend_display,
				'position' => ! empty( $settings['chart_legend_position'] ) ? $settings['chart_legend_position'] : 'top',
				'reverse'  => filter_var( $settings['chart_legend_reverse'], FILTER_VALIDATE_BOOLEAN ),
			),
			'tooltips' => array(
				'enabled' => $tooltips_enabled,
			),
		);

		if ( ! empty( $settings['chart_cutout_percentage']['size'] ) ) {
			$options['cutoutPercentage'] = $settings['chart_cutout_percentage']['size'];
		}

		$legend_style = array();

		$legend_style_dictionary = array(
			'boxWidth'   => 'chart_legend_box_width',
			'fontFamily' => 'chart_legend_font_family',
			'fontSize'   => 'chart_legend_font_size',
			'fontStyle'  => array( 'chart_legend_font_style', 'chart_legend_font_weight' ),
			'fontColor'  => 'chart_legend_font_color',
		);

		if ( $legend_display ) {

			foreach ( $legend_style_dictionary as $style_property => $setting_name ) {

				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );

					if ( ! empty( $style_value ) ) {
						$legend_style[ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$legend_style[ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$legend_style[ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}

			if ( ! empty( $legend_style ) ) {
				$options['legend']['labels'] = $legend_style;
			}
		}

		$tooltip_style_dictionary = array(
			'backgroundColor' => 'chart_tooltip_bg_color',
			'bodyFontFamily'  => 'chart_tooltip_font_family',
			'bodyFontSize'    => 'chart_tooltip_font_size',
			'bodyFontStyle'   => array( 'chart_tooltip_font_style', 'chart_tooltip_font_weight' ),
			'bodyFontColor'   => 'chart_tooltip_font_color',
		);

		if ( $tooltips_enabled ) {

			foreach ( $tooltip_style_dictionary as $style_property => $setting_name ) {

				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );

					if ( ! empty( $style_value ) ) {
						$options['tooltips'][ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$options['tooltips'][ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$options['tooltips'][ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Get font style string.
	 *
	 * @param array $settings_names Settings names.
	 *
	 * @return string
	 */
	public function get_chart_font_style_string( $settings_names = array() ) {
		if ( ! is_array( $settings_names ) ) {
			return '';
		}

		$settings = $this->get_settings_for_display();

		$font_styles = array();

		foreach ( $settings_names as $setting_name ) {
			if ( ! empty( $settings[ $setting_name ] ) ) {
				$font_styles[] = $settings[ $setting_name ];
			}
		}

		if ( empty( $font_styles ) ) {
			return '';
		}

		$font_styles = array_unique( $font_styles );

		return join( ' ', $font_styles );
	}

	/**
	 * Get chart title.
	 *
	 * @return string
	 */
	public function get_chart_title() {
		$settings = $this->get_settings_for_display();

		$title_format = apply_filters(
			'jet-elements/pie-chart/title-format',
			'<div class="jet-pie-chart-title-container"><%1$s class="jet-pie-chart-title">%2$s</%1$s></div>'
		);

		return sprintf( $title_format, $settings['chart_title_size'], $settings['chart_title'] );
	}
}
