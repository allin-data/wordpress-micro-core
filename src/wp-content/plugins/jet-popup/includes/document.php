<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Popup_Document extends Elementor\Core\Base\Document {

	/**
	 * @access public
	 */
	public function get_name() {
		return 'jet-popup';
	}

	/**
	 * @access public
	 * @static
	 */
	public static function get_title() {
		return __( 'Jet Popup', 'jet-popup' );
	}

	/**
	 * [_register_controls description]
	 * @return [type] [description]
	 */
	protected function _register_controls() {

		parent::_register_controls();

		$uniq_popup_id = '#' . $this->get_unique_name();

		$roles = Jet_Popup_Utils::get_roles_list();

		$this->start_controls_section(
			'jet_popup_settings',
			[
				'label' => __( 'Settings', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_control(
			'jet_popup_animation',
			[
				'label'   => __( 'Animation', 'jet-popup' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade'           => esc_html__( 'Fade', 'jet-popup' ),
					'zoom-in'        => esc_html__( 'ZoomIn', 'jet-popup' ),
					'zoom-out'       => esc_html__( 'ZoomOut', 'jet-popup' ),
					'rotate'         => esc_html__( 'Rotate', 'jet-popup' ),
					'move-up'        => esc_html__( 'MoveUp', 'jet-popup' ),
					'flip-x'         => esc_html__( 'Horizontal Flip', 'jet-popup' ),
					'flip-y'         => esc_html__( 'Vertical Flip', 'jet-popup' ),
					'bounce-in'      => esc_html__( 'BounceIn', 'jet-popup' ),
					'bounce-out'     => esc_html__( 'BounceOut', 'jet-popup' ),
					'slide-in-up'    => esc_html__( 'SlideInUp', 'jet-popup' ),
					'slide-in-right' => esc_html__( 'SlideInRight', 'jet-popup' ),
					'slide-in-down'  => esc_html__( 'SlideInDown', 'jet-popup' ),
					'slide-in-left'  => esc_html__( 'SlideInLeft', 'jet-popup' ),
				],
			]
		);

		$this->add_control(
			'jet_popup_open_trigger',
			[
				'label'   => __( 'Open event', 'jet-popup' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'attach',
				'options' => [
					'attach'           => esc_html__( 'Not Selected', 'jet-popup' ),
					'page-load'        => esc_html__( 'On page load(s)', 'jet-popup' ),
					'user-inactive'    => esc_html__( 'Inactivity time after(s)', 'jet-popup' ),
					'scroll-trigger'   => esc_html__( 'Page Scrolled(%)', 'jet-popup' ),
					'try-exit-trigger' => esc_html__( 'Try exit', 'jet-popup' ),
					'on-date'          => esc_html__( 'On Date', 'jet-popup' ),
					'custom-selector'  => esc_html__( 'Custom Selector Click', 'jet-popup' ),
				],
			]
		);

		$this->add_control(
			'jet_popup_page_load_delay',
			[
				'label'       => esc_html__( 'Open delay', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'max'         => 60,
				'condition'   => [
					'jet_popup_open_trigger' => 'page-load',
				]
			]
		);

		$this->add_control(
			'jet_popup_user_inactivity_time',
			[
				'label'       => esc_html__( 'User inactivity time', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'max'         => 60,
				'condition'   => [
					'jet_popup_open_trigger' => 'user-inactive',
				]
			]
		);

		$this->add_control(
			'jet_popup_scrolled_to_value',
			[
				'label'       => esc_html__( 'Scroll Page Progress(%)', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::NUMBER,
				'default'     => 10,
				'min'         => 0,
				'max'         => 100,
				'condition'   => [
					'jet_popup_open_trigger' => 'scroll-trigger',
				]
			]
		);

		$this->add_control(
			'jet_popup_on_date_value',
			[
				'label'       => esc_html__( 'Open Date', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::DATE_TIME,
				'default'     => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'picker_options' => [
					'enableTime' => true,
				],
				'condition'   => [
					'jet_popup_open_trigger' => 'on-date',
				],
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'jet-popup' ), Elementor\Utils::get_timezone_string() ),
			]
		);

		$this->add_control(
			'jet_popup_custom_selector',
			array(
				'label'       => esc_html__( 'Custom Selector', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Custom Selector', 'jet-popup' ),
				'default'     => '.custom',
				'condition'   => array(
					'jet_popup_open_trigger' => 'custom-selector',
				),
			)
		);

		$this->add_control(
			'jet_popup_show_once',
			[
				'label'        => esc_html__( 'Show once', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'jet_popup_show_again_delay',
			[
				'label'       => __( 'Repeat showing popup in', 'jet-popup' ),
				'description' => __( 'Set the timeout caching and a popup will be displayed again', 'jet-popup' ),
				'type'    => Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'      => esc_html__( 'None', 'jet-popup' ),
					'minute'    => esc_html__( 'Minute', 'jet-popup' ),
					'10minutes' => esc_html__( '10 Minutes', 'jet-popup' ),
					'30minutes' => esc_html__( '30 Minutes', 'jet-popup' ),
					'hour'      => esc_html__( '1 Hour', 'jet-popup' ),
					'3hours'    => esc_html__( '3 Hours', 'jet-popup' ),
					'6hours'    => esc_html__( '6 Hours', 'jet-popup' ),
					'12hours'   => esc_html__( '12 Hours', 'jet-popup' ),
					'day'       => esc_html__( 'Day', 'jet-popup' ),
					'3days'     => esc_html__( '3 Days', 'jet-popup' ),
					'week'      => esc_html__( 'Week', 'jet-popup' ),
					'month'     => esc_html__( 'Month', 'jet-popup' ),
				],
				'condition'   => array(
					'jet_popup_show_once' => 'yes',
				),
			]
		);

		$this->add_control(
			'jet_popup_use_ajax',
			[
				'label'        => esc_html__( 'Loading content with Ajax', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'jet_popup_force_ajax',
			[
				'label'        => esc_html__( 'Force Loading', 'jet-popup' ),
				'description'        => esc_html__( 'Force Loading every time you open the popup', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'   => array(
					'jet_popup_use_ajax' => 'yes',
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jet_popup_conditions',
			[
				'label' => __( 'Display Settings', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		jet_popup()->conditions->register_condition_button( $this );

		if ( ! empty( $roles ) ) {
			$this->add_control(
				'jet_role_condition',
				[
					'label'     => __( 'Available For Roles', 'jet-popup' ),
					'type'      => Elementor\Controls_Manager::SELECT2,
					'multiple'  => true,
					'options'   => $roles,
					'separator' => 'before',
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'jet_popup_general_style',
			[
				'label' => __( 'General Styles', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'jet_popup_z_index',
			[
				'label'       => esc_html__( 'Z-Index', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::NUMBER,
				'min'         => -1,
				'max'         => 50000,
				'selectors' => [
					$uniq_popup_id => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jet_popup_container_style',
			[
				'label' => __( 'Popup Container', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'positions_size',
			array(
				'label' => esc_html__( 'Size', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => esc_html__( 'Width', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::SLIDER,
				'size_units' => [
					'px', '%'
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 2000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 500,
					'unit' => 'px',
				],
				'selectors' => [
					$uniq_popup_id . ' .jet-popup__container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_custom_height',
			[
				'label'        => esc_html__( 'Custom Height', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label' => esc_html__( 'Height', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::SLIDER,
				'size_units' => [
					'px', '%'
				],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 500,
					'unit' => 'px',
				],
				'selectors' => [
					$uniq_popup_id . ' .jet-popup__container' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'container_custom_height' => 'yes',
				],
			]
		);

		$this->add_control(
			'position_heading',
			array(
				'label' => esc_html__( 'Position', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'jet_popup_horizontal_position',
			[
				'label'       => __( 'Horizontal Position', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'jet-popup' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jet-popup' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'jet-popup' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__inner' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'jet_popup_vertical_position',
			[
				'label'       => __( 'Vertical Position', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'center',
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'jet-popup' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'jet-popup' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'jet-popup' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__inner' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'jet_popup_content_position',
			[
				'label'       => __( 'Content Position', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'flex-start',
				'options'     => [
					'flex-start' => [
						'title' => __( 'Top', 'jet-popup' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'jet-popup' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => __( 'Bottom', 'jet-popup' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__container-inner' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'container_background_heading',
			array(
				'label' => esc_html__( 'Container Background', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'container_background',
				'selector' => $uniq_popup_id . ' .jet-popup__container-inner',
			]
		);

		$this->add_control(
			'container_background_overlay_heading',
			array(
				'label' => esc_html__( 'Container Overlay', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::HEADING,
				'condition' => [
					'container_background_background' => [ 'classic', 'gradient' ],
				],
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			[
				'name'      => 'container_background_overlay',
				'selector'  => $uniq_popup_id . ' .jet-popup__container-overlay',
				'condition' => [
					'container_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_control(
			'container_background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'jet-popup' ),
				'type' => Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					$uniq_popup_id . ' .jet-popup__container-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'container_background_overlay' => [ 'classic', 'gradient' ],
					'container_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'container_background_overlay_css_filters',
				'selector' => $uniq_popup_id . ' .jet-popup__container-overlay',
				'condition' => [
					'container_background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_control(
			'container_background_overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'jet-popup' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'jet-popup' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					$uniq_popup_id . ' .jet-popup__container-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [
					'container_background_background' => [ 'classic', 'gradient' ],
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'other_heading',
			array(
				'label' => esc_html__( 'Other Styles', 'jet-popup' ),
				'type'  => Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label'      => __( 'Padding', 'jet-popup' ),
				'type'       => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label'      => __( 'Margin', 'jet-popup' ),
				'type'       => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__container-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'jet-popup' ),
				'type'       => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					$uniq_popup_id . ' .jet-popup__container-inner'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$uniq_popup_id . ' .jet-popup__container-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jet-popup' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => $uniq_popup_id . ' .jet-popup__container-inner',
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'container_shadow',
				'selector' => $uniq_popup_id . ' .jet-popup__container-inner',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jet_popup_close_button_style',
			[
				'label' => __( 'Close Button', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'use_close_button',
			[
				'label'        => esc_html__( 'Use Close Button', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'close_button_icon',
			[
				'label'       => esc_html__( 'Icon', 'jet-popup' ),
				'type'        => Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-times',
			]
		);

		$this->add_group_control(
			\Jet_Popup_Group_Control_Transform_Style::get_type(),
			[
				'name'     => 'close_button_box_transform',
				'label'    => esc_html__( 'Icon Transform', 'jet-popup' ),
				'selector' => $uniq_popup_id . ' .jet-popup__close-button',
			]
		);

		$this->start_controls_tabs( 'close_button_style_tabs' );

		$this->start_controls_tab(
			'close_button_control_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jet-popup' ),
			]
		);

		$this->add_group_control(
			\Jet_Popup_Group_Control_Box_Style::get_type(),
			[
				'name'     => 'close_button_box_style_normal',
				'label'    => esc_html__( 'Icon Styles', 'jet-popup' ),
				'selector' => $uniq_popup_id . ' .jet-popup__close-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'close_button_control_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jet-popup' ),
			]
		);

		$this->add_group_control(
			\Jet_Popup_Group_Control_Box_Style::get_type(),
			[
				'name'     => 'close_button_box_style_hover',
				'label'    => esc_html__( 'Icon Styles', 'jet-popup' ),
				'selector' => $uniq_popup_id . ' .jet-popup__close-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'jet_popup_overlay_style',
			[
				'label' => __( 'Popup Overlay', 'jet-popup' ),
				'tab'   => Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'use_overlay',
			[
				'label'        => esc_html__( 'Use Overlay', 'jet-popup' ),
				'type'         => Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			[
				'name'      => 'overlay_background',
				'selector'  => $uniq_popup_id . ' .jet-popup__overlay',
				'condition' => [
					'use_overlay' => 'yes',
				]
			]
		);

		$this->end_controls_section();

	}
}
