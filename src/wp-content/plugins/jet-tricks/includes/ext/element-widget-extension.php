<?php

/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tricks_Elementor_Widget_Extension' ) ) {

	/**
	 * Define Jet_Tricks_Elementor_Widget_Extension class
	 */
	class Jet_Tricks_Elementor_Widget_Extension {

		/**
		 * Widgets Data
		 *
		 * @var array
		 */
		public $widgets_data = array();

		/**
		 * [$default_widget_settings description]
		 * @var array
		 */
		public $default_widget_settings = array(
			'jet_tricks_widget_parallax'           => 'false',
			'jet_tricks_widget_parallax_invert'    => 'false',
			'jet_tricks_widget_parallax_speed'     => array(
				'unit' => '%',
				'size' => 50
			),
			'jet_tricks_widget_parallax_on'        => array(
				'desktop',
				'tablet',
				'mobile',
			),
			'jet_tricks_widget_satellite'          => 'false',
			'jet_tricks_widget_satellite_type'     => 'text',
			'jet_tricks_widget_satellite_position' => 'top-center',
			'jet_tricks_widget_satellite_icon'     => 'fa fa-plus',
			'jet_tricks_widget_satellite_image'    => array(
				'url' => '',
				'id' => '',
			),
			'jet_tricks_widget_tooltip'             => 'false',
			'jet_tricks_widget_tooltip_description' => 'Lorem Ipsum',
			'jet_tricks_widget_tooltip_placement'   => 'top',
			'jet_tricks_widget_tooltip_x_offset'    => 0,
			'jet_tricks_widget_tooltip_y_offset'    => 0,
			'jet_tricks_widget_tooltip_animation'   => 'shift-toward',
			'jet_tricks_widget_tooltip_z_index'     => 999,
		);

		/**
		 * [$avaliable_extensions description]
		 * @var array
		 */
		public $avaliable_extensions = array();

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {

			$this->avaliable_extensions = jet_tricks_settings()->get( 'avaliable_extensions', jet_tricks_settings()->default_avaliable_extensions );

			if ( ! in_array( 'true', $this->avaliable_extensions ) ) {
				return false;
			}

			add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'after_common_section_responsive' ), 10, 2 );

			add_action( 'elementor/frontend/widget/before_render', array( $this, 'widget_before_render' ) );

			add_action( 'elementor/widget/before_render_content', array( $this, 'widget_before_render_content' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}

		/**
		 * After section_layout callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_common_section_responsive( $obj, $args ) {

			$obj->start_controls_section(
				'widget_jet_tricks',
				array(
					'label' => esc_html__( 'Jet Tricks', 'jet-tricks' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$this->register_parallax_ext_settings( $obj );

			$this->register_satellite_ext_settings( $obj );

			$this->register_tooltip_ext_settings( $obj );

			$obj->end_controls_section();
		}

		/**
		 * [register_parallax_settings description]
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function register_parallax_ext_settings( $obj ) {

			if ( ! filter_var( $this->avaliable_extensions['widget_parallax'], FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			$obj->add_control(
				'parallax_heading',
				array(
					'label' => esc_html__( 'Parallax', 'jet-tricks' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);

			$obj->add_control(
				'jet_tricks_widget_parallax',
				array(
					'label'        => esc_html__( 'Use Parallax?', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
					'label_off'    => esc_html__( 'No', 'jet-tricks' ),
					'return_value' => 'true',
					'default'      => 'false',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_parallax_speed',
				array(
					'label'      => esc_html__( 'Parallax Speed(%)', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => array( '%' ),
					'range'      => array(
						'%' => array(
							'min'  => 1,
							'max'  => 100,
						),
					),
					'default' => array(
						'size' => 50,
						'unit' => '%',
					),
					'condition' => array(
						'jet_tricks_widget_parallax' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_parallax_invert',
				array(
					'label'        => esc_html__( 'Invert', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
					'label_off'    => esc_html__( 'No', 'jet-tricks' ),
					'return_value' => 'true',
					'default'      => 'false',
					'condition' => array(
						'jet_tricks_widget_parallax' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_parallax_on',
				array(
					'label'       => __( 'Active On', 'jet-tricks' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => 'true',
					'default'     => array(
						'desktop',
						'tablet',
					),
					'options'     => array(
						'desktop' => __( 'Desktop', 'jet-tricks' ),
						'tablet'  => __( 'Tablet', 'jet-tricks' ),
						'mobile'  => __( 'Mobile', 'jet-tricks' ),
					),
					'condition' => array(
						'jet_tricks_widget_parallax' => 'true',
					),
					'render_type' => 'template',
				)
			);
		}

		/**
		 * [register_satellite_ext_settings description]
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function register_satellite_ext_settings( $obj ) {

			if ( ! filter_var( $this->avaliable_extensions['widget_satellite'], FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			$obj->add_control(
				'satellite_heading',
				array(
					'label'     => esc_html__( 'Satellite', 'jet-tricks' ),
					'type'      => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite',
				array(
					'label'        => esc_html__( 'Use Satellite?', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
					'label_off'    => esc_html__( 'No', 'jet-tricks' ),
					'return_value' => 'true',
					'default'      => 'false',
					'render_type'  => 'template',
				)
			);

			$obj->start_controls_tabs( 'jet_tricks_widget_satellite_tabs' );

			$obj->start_controls_tab(
				'jet_tricks_widget_satellite_settings_tab',
				array(
					'label' => esc_html__( 'Settings', 'jet-tricks' ),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_type',
				array(
					'label'   => esc_html__( 'Type', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'text',
					'options' => array(
						'text'  => esc_html__( 'Text', 'jet-tricks' ),
						'icon'  => esc_html__( 'Icon', 'jet-tricks' ),
						'image' => esc_html__( 'Image', 'jet-tricks' ),
					),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_text',
				array(
					'label'       => esc_html__( 'Text', 'jet-tricks' ),
					'type'        => Elementor\Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => 'Lorem Ipsum',
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'text',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_icon',
				array(
					'label'       => esc_html__( 'Icon', 'jet-tricks' ),
					'type'        => Elementor\Controls_Manager::ICON,
					'label_block' => true,
					'file'        => '',
					'default'     => 'fa fa-plus',
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'icon',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_image',
				array(
					'label'   => esc_html__( 'Image', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::MEDIA,
					'default' => array(
						'url' => Elementor\Utils::get_placeholder_image_src(),
					),
					'condition' => array(
						'jet_tricks_widget_satellite'     => 'true',
						'jet_tricks_widget_satellite_type' => 'image',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_position',
				array(
					'label'   => esc_html__( 'Position', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'top-center',
					'options' => array(
						'top-left'      => esc_html__( 'Top Left', 'jet-tricks' ),
						'top-center'    => esc_html__( 'Top Center', 'jet-tricks' ),
						'top-right'     => esc_html__( 'Top Right', 'jet-tricks' ),
						'middle-left'   => esc_html__( 'Middle Left', 'jet-tricks' ),
						'middle-center' => esc_html__( 'Middle Center', 'jet-tricks' ),
						'middle-right'  => esc_html__( 'Middle Right', 'jet-tricks' ),
						'bottom-left'   => esc_html__( 'Bottom Left', 'jet-tricks' ),
						'bottom-center' => esc_html__( 'Bottom Center', 'jet-tricks' ),
						'bottom-right'  => esc_html__( 'Bottom Right', 'jet-tricks' ),
					),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_x_offset',
				array(
					'label'      => esc_html__( 'x-Offset', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => -500,
							'max' => 500,
						),
					),
					'default' => array(
						'size' => 0,
						'unit' => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite' => 'transform: translateX({{SIZE}}px);',
					),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_y_offset',
				array(
					'label'      => esc_html__( 'y-Offset', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => -500,
							'max' => 500,
						),
					),
					'default' => array(
						'size' => 0,
						'unit' => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite__inner' => 'transform: translateY({{SIZE}}px);',
					),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_rotate',
				array(
					'label'      => esc_html__( 'Rotate', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => array( 'deg' ),
					'range'      => array(
						'deg' => array(
							'min' => -180,
							'max' => 180,
						),
					),
					'default' => array(
						'size' => 0,
						'unit' => 'deg',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__text span' => 'transform: rotate({{SIZE}}deg)',
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__icon .jet-tricks-satellite__icon-instance' => 'transform: rotate({{SIZE}}deg)',
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__image .jet-tricks-satellite__image-instance' => 'transform: rotate({{SIZE}}deg)',
					),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_z',
				array(
					'label'   => esc_html__( 'z-Index', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 2,
					'min'     => 0,
					'max'     => 999,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite' => 'z-index:{{VALUE}}',
					),
				)
			);

			$obj->end_controls_tab();

			$obj->start_controls_tab(
				'jet_tricks_widget_satellite_styles_tab',
				array(
					'label' => esc_html__( 'Styles', 'jet-tricks' ),
					'condition' => array(
						'jet_tricks_widget_satellite' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_satellite_text_color',
				array(
					'label'  => esc_html__( 'Color', 'jet-tricks' ),
					'type'   => Elementor\Controls_Manager::COLOR,
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'text',
					),
					'selectors' => array(
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__text' => 'color: {{VALUE}}',
					),
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'jet_tricks_widget_satellite_text_typography',
					'selector' => '{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__text',
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'text',
					),
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Text_Shadow::get_type(),
				array(
					'name'     => 'jet_tricks_widget_satellite_text_shadow',
					'selector' => '{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__text',
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'text',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_image_width',
				array(
					'label'   => esc_html__( 'Width', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 200,
					'min'     => 10,
					'max'     => 1000,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'image',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__image' => 'width:{{VALUE}}px',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_satellite_image_height',
				array(
					'label'   => esc_html__( 'Height', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 200,
					'min'     => 10,
					'max'     => 1000,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'image',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__image' => 'height:{{VALUE}}px',
					),
				)
			);

			$obj->add_group_control(
				\Jet_Tricks_Group_Control_Box_Style::get_type(),
				array(
					'label'     => esc_html__( 'Icon Box', 'jet-tricks' ),
					'name'      => 'jet_tricks_widget_satellite_icon_box',
					'selector'  => '{{WRAPPER}} .jet-tricks-satellite .jet-tricks-satellite__icon-instance',
					'condition' => array(
						'jet_tricks_widget_satellite'      => 'true',
						'jet_tricks_widget_satellite_type' => 'icon',
					),
				)
			);

			if ( class_exists( 'Elementor\Group_Control_Css_Filter' ) ) {
				$obj->add_group_control(
					Elementor\Group_Control_Css_Filter::get_type(),
					array(
						'name'     => 'jet_tricks_widget_satellite_css_filters',
						'selector' => '{{WRAPPER}} .jet-tricks-satellite',
						'condition' => array(
							'jet_tricks_widget_satellite'      => 'true',
						),
					)
				);
			}

			$obj->end_controls_tab();

			$obj->end_controls_tabs();
		}

		/**
		 * [register_tooltip_ext_settings description]
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function register_tooltip_ext_settings( $obj ) {

			if ( ! filter_var( $this->avaliable_extensions['widget_tooltip'], FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			$obj->add_control(
				'tooltip_heading',
				array(
					'label'     => esc_html__( 'Tooltip', 'jet-tricks' ),
					'type'      => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip',
				array(
					'label'        => esc_html__( 'Use Tooltip?', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
					'label_off'    => esc_html__( 'No', 'jet-tricks' ),
					'return_value' => 'true',
					'default'      => 'false',
					'render_type'  => 'template',
				)
			);

			$obj->start_controls_tabs( 'jet_tricks_widget_tooltip_tabs' );

			$obj->start_controls_tab(
				'jet_tricks_widget_tooltip_settings_tab',
				array(
					'label' => esc_html__( 'Settings', 'jet-tricks' ),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_description',
				array(
					'label'       => esc_html__( 'Description', 'jet-tricks' ),
					'type'        => Elementor\Controls_Manager::TEXTAREA,
					'render_type' => 'template',
					'default'     => '',
					'placeholder' => 'Lorem Ipsum',
					'condition'   => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_placement',
				array(
					'label'   => esc_html__( 'Placement', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'top',
					'options' => array(
						'top'    => esc_html__( 'Top', 'jet-tricks' ),
						'bottom' => esc_html__( 'Bottom', 'jet-tricks' ),
						'left'   => esc_html__( 'Left', 'jet-tricks' ),
						'right'  => esc_html__( 'Right', 'jet-tricks' ),
					),
					'render_type'  => 'template',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_animation',
				array(
					'label'   => esc_html__( 'Animation', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'shift-toward',
					'options' => array(
						'shift-away'   => esc_html__( 'Shift-Away', 'jet-tricks' ),
						'shift-toward' => esc_html__( 'Shift-Toward', 'jet-tricks' ),
						'fade'         => esc_html__( 'Fade', 'jet-tricks' ),
						'scale'        => esc_html__( 'Scale', 'jet-tricks' ),
						'perspective'  => esc_html__( 'Perspective', 'jet-tricks' ),
					),
					'render_type'  => 'template',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_x_offset',
				array(
					'label'   => esc_html__( 'Offset', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 0,
					'min'     => -1000,
					'max'     => 1000,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_y_offset',
				array(
					'label'   => esc_html__( 'Distance', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 0,
					'min'     => -1000,
					'max'     => 1000,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_z_index',
				array(
					'label'   => esc_html__( 'z-Index', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 999,
					'min'     => 0,
					'max'     => 999,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->end_controls_tab();

			$obj->start_controls_tab(
				'jet_tricks_widget_tooltip_styles_tab',
				array(
					'label' => esc_html__( 'Style', 'jet-tricks' ),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_tooltip_width',
				array(
					'label'      => esc_html__( 'Width', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
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
						'{{WRAPPER}} > .tippy-popper .tippy-tooltip' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
					'render_type'  => 'template',
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'jet_tricks_widget_tooltip_typography',
					'selector' => '{{WRAPPER}} > .tippy-popper .tippy-tooltip .tippy-content',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_color',
				array(
					'label'  => esc_html__( 'Text Color', 'jet-tricks' ),
					'type'   => Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} > .tippy-popper .tippy-tooltip' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_text_align',
				array(
					'label'   => esc_html__( 'Text Alignment', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::CHOOSE,
					'default' => 'center',
					'options' => array(
						'left'    => array(
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
					'selectors'  => array(
						'{{WRAPPER}} > .tippy-popper .tippy-tooltip .tippy-content' => 'text-align: {{VALUE}};',
					),
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				array(
					'name'     => 'jet_tricks_widget_tooltip_background',
					'selector' => '{{WRAPPER}} > .tippy-popper .tippy-tooltip',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_widget_tooltip_arrow_color',
				array(
					'label'  => esc_html__( 'Arrow Color', 'jet-tricks' ),
					'type'   => Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} > .tippy-popper[x-placement^=left] .tippy-tooltip .tippy-arrow'=> 'border-left-color: {{VALUE}}',
						'{{WRAPPER}} > .tippy-popper[x-placement^=right] .tippy-tooltip .tippy-arrow'=> 'border-right-color: {{VALUE}}',
						'{{WRAPPER}} > .tippy-popper[x-placement^=top] .tippy-tooltip .tippy-arrow'=> 'border-top-color: {{VALUE}}',
						'{{WRAPPER}} > .tippy-popper[x-placement^=bottom] .tippy-tooltip .tippy-arrow'=> 'border-bottom-color: {{VALUE}}',
					),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_tooltip_padding',
				array(
					'label'      => __( 'Padding', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} > .tippy-popper .tippy-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'render_type'  => 'template',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				array(
					'name'        => 'jet_tricks_widget_tooltip_border',
					'label'       => esc_html__( 'Border', 'jet-tricks' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} > .tippy-popper .tippy-tooltip',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_responsive_control(
				'jet_tricks_widget_tooltip_border_radius',
				array(
					'label'      => __( 'Border Radius', 'jet-tricks' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} > .tippy-popper .tippy-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				array(
					'name' => 'jet_tricks_widget_tooltip_box_shadow',
					'selector' => '{{WRAPPER}} > .tippy-popper .tippy-tooltip',
					'condition' => array(
						'jet_tricks_widget_tooltip' => 'true',
					),
				)
			);

			$obj->end_controls_tab();

			$obj->end_controls_tabs();
		}

		/**
		 * [widget_before_render description]
		 * @param  [type] $widget [description]
		 * @return [type]         [description]
		 */
		public function widget_before_render( $widget ) {
			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );

			$widget_settings = array();

			if ( filter_var( $settings['jet_tricks_widget_parallax'], FILTER_VALIDATE_BOOLEAN ) ) {

				$widget_settings['parallax'] = filter_var( $settings['jet_tricks_widget_parallax'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
				$widget_settings['invert']   = filter_var( $settings['jet_tricks_widget_parallax_invert'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
				$widget_settings['speed']    = $settings['jet_tricks_widget_parallax_speed'];
				$widget_settings['stickyOn'] = $settings['jet_tricks_widget_parallax_on'];

				$widget->add_render_attribute( '_wrapper', array(
					'class' => 'jet-parallax-widget',
				) );
			}

			if ( filter_var( $settings['jet_tricks_widget_satellite'], FILTER_VALIDATE_BOOLEAN ) ) {
				$widget_settings['satellite'] = filter_var( $settings['jet_tricks_widget_satellite'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
				$widget_settings['satelliteType'] = $settings['jet_tricks_widget_satellite_type'];
				$widget_settings['satellitePosition'] = $settings['jet_tricks_widget_satellite_position'];

				$widget->add_render_attribute( '_wrapper', array(
					'class' => 'jet-satellite-widget',
				) );
			}

			if ( filter_var( $settings['jet_tricks_widget_tooltip'], FILTER_VALIDATE_BOOLEAN ) ) {
				$widget_settings['tooltip'] = filter_var( $settings['jet_tricks_widget_tooltip'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
				$widget_settings['tooltipDescription'] = $settings['jet_tricks_widget_tooltip_description'];
				$widget_settings['tooltipPlacement'] = $settings['jet_tricks_widget_tooltip_placement'];
				$widget_settings['xOffset'] = $settings['jet_tricks_widget_tooltip_x_offset'];
				$widget_settings['yOffset'] = $settings['jet_tricks_widget_tooltip_y_offset'];
				$widget_settings['tooltipAnimation'] = $settings['jet_tricks_widget_tooltip_animation'];
				$widget_settings['zIndex'] = $settings['jet_tricks_widget_tooltip_z_index'];

				$widget->add_render_attribute( '_wrapper', array(
					'class' => 'jet-tooltip-widget',
				) );
			}

			$widget_settings = apply_filters(
				'jet-tricks/frontend/widget/settings',
				$widget_settings,
				$widget,
				$this
			);

			if ( ! empty( $widget_settings ) ) {
				$widget->add_render_attribute( '_wrapper', array(
					'data-jet-tricks-settings' => json_encode( $widget_settings ),
				) );
			}

			$this->widgets_data[ $data['id'] ] = $widget_settings;
		}

		/**
		 * [widget_before_render_content description]
		 * @return [type] [description]
		 */
		public function widget_before_render_content( $widget ) {

			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );

			foreach ( array( 'jet_tricks_widget_tooltip_description', 'jet_tricks_widget_satellite_text' ) as $setting_name ) {
				if ( empty( $settings[ $setting_name ] ) ) {
					$settings[ $setting_name ] = 'Lorem Ipsum';
				}
			}

			$settings = apply_filters( 'jet-tricks/frontend/widget-content/settings', $settings, $widget, $this );

			if ( filter_var( $settings['jet_tricks_widget_satellite'], FILTER_VALIDATE_BOOLEAN ) ) {
				switch ( $settings['jet_tricks_widget_satellite_type'] ) {
					case 'text':

						if ( ! empty( $settings['jet_tricks_widget_satellite_text'] ) ) {
							echo sprintf( '<div class="jet-tricks-satellite jet-tricks-satellite--%1$s"><div class="jet-tricks-satellite__inner"><div class="jet-tricks-satellite__text"><span>%2$s</span></div></div></div>', $settings['jet_tricks_widget_satellite_position'], $settings['jet_tricks_widget_satellite_text'] );
						}
					break;

					case 'icon':

						if ( ! empty( $settings['jet_tricks_widget_satellite_icon'] ) ) {
							echo sprintf( '<div class="jet-tricks-satellite jet-tricks-satellite--%1$s"><div class="jet-tricks-satellite__inner"><div class="jet-tricks-satellite__icon"><div class="jet-tricks-satellite__icon-instance"><i class="%2$s"></i></div></div></div></div>', $settings['jet_tricks_widget_satellite_position'], $settings['jet_tricks_widget_satellite_icon'] );
						}
					break;

					case 'image':

						if ( ! empty( $settings['jet_tricks_widget_satellite_image']['url'] ) ) {
							echo sprintf( '<div class="jet-tricks-satellite jet-tricks-satellite--%1$s"><div class="jet-tricks-satellite__inner"><div class="jet-tricks-satellite__image"><img class="jet-tricks-satellite__image-instance" src="%2$s" alt=""></div></div></div>', $settings['jet_tricks_widget_satellite_position'], $settings['jet_tricks_widget_satellite_image']['url'] );
						}
					break;
				}
			}

			if ( filter_var( $settings['jet_tricks_widget_tooltip'], FILTER_VALIDATE_BOOLEAN ) && ! empty( $settings['jet_tricks_widget_tooltip_description'] ) ) {
				echo sprintf( '<div id="jet-tricks-tooltip-content-%1$s" class="jet-tooltip-widget__content">%2$s</div>', $data['id'], $settings['jet_tricks_widget_tooltip_description'] );
			}
		}

		/**
		 * [enqueue_scripts description]
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( 'jet-tricks-tippy' );

			jet_tricks_assets()->elements_data['widgets'] = $this->widgets_data;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of Jet_Tricks_Elementor_Widget_Extension
 *
 * @return object
 */
function jet_tricks_elementor_widget_extension() {
	return Jet_Tricks_Elementor_Widget_Extension::get_instance();
}
