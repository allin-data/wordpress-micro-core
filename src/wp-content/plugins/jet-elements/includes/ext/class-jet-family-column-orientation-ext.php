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

if ( ! class_exists( 'Jet_Family_Column_Orientation_Ext' ) ) {

	/**
	 * Define Jet_Family_Column_Orientation_Ext class
	 */
	class Jet_Family_Column_Orientation_Ext {

		/**
		 * [$parallax_sections description]
		 * @var array
		 */
		public $parallax_sections = array();

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

			add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'column_tab_advanced_add_section' ], 10, 2 );

			add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'widget_tab_advanced_add_section' ), 10, 2 );
		}

		/**
		 * After column_layout callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function column_tab_advanced_add_section( $element, $args ) {

			$element->start_controls_section(
				'jet_family_column_orientaion_section',
				[
					'label' => esc_html__( 'Jet Advanced', 'jet-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'jet_family_column_orientaion',
				[
					'label'        => esc_html__( 'Content Orientaion', 'jet-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
					'label_off'    => esc_html__( 'No', 'jet-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
				]
			);

			$element->add_responsive_control(
				'jet_family_column_flex_orientation',
				[
					'label'   => esc_html__( 'Orientation Direction', 'jet-elements' ),
					'type'    => 'choose',
					'default' => '',
					'options' => [
						'horizontal' => [
							'title' => esc_html__( 'Horizontal', 'jet-elements' ),
							'icon' => 'eicon-navigation-horizontal',
						],
						'vertical' => [
							'title' => esc_html__( 'Vertical', 'jet-elements' ),
							'icon' => 'eicon-navigation-vertical',
						],
					],
					'label_block'  => false,
					'prefix_class' => 'jet-family-column%s-flex-',
					'condition'    => [
						'jet_family_column_orientaion' => 'true'
					],
				]
			);

			$element->add_responsive_control(
				'jet_family_column_flex_row_align',
				[
					'label'   => esc_html__( 'Content Align', 'jet-elements' ),
					'type'    => 'select',
					'options' => [
						''              => esc_html__( 'Default', 'jet-elements' ),
						'start'         => esc_html__( 'Left', 'jet-elements' ),
						'center'        => esc_html__( 'Middle', 'jet-elements' ),
						'end'           => esc_html__( 'Right', 'jet-elements' ),
						'space-between' => esc_html__( 'Space Between', 'jet-elements' ),
						'space-evenly'  => esc_html__( 'Space Evenly', 'jet-elements' ),
						'space-around'  => esc_html__( 'Space Around', 'jet-elements' ),
					],
					'default' => '',
					'prefix_class' => 'jet-family-column%s-flex-',
					'condition'    => [
						'jet_family_column_orientaion'       => 'true',
						'jet_family_column_flex_orientation' => 'horizontal',
					],
				]
			);

			$element->add_responsive_control(
				'jet_family_column_flex_column_align',
				[
					'label'   => esc_html__( 'Content Align', 'jet-elements' ),
					'type'    => 'select',
					'options' => [
						''              => esc_html__( 'Default', 'jet-elements' ),
						'start'         => esc_html__( 'Top', 'jet-elements' ),
						'center'        => esc_html__( 'Middle', 'jet-elements' ),
						'end'           => esc_html__( 'Bottom', 'jet-elements' ),
						'space-between' => esc_html__( 'Space Between', 'jet-elements' ),
						'space-evenly'  => esc_html__( 'Space Evenly', 'jet-elements' ),
						'space-around'  => esc_html__( 'Space Around', 'jet-elements' ),
					],
					'default' => '',
					'prefix_class' => 'jet-family-column%s-flex-',
					'condition' => [
						'jet_family_column_orientaion'       => 'true',
						'jet_family_column_flex_orientation' => 'vertical',
					],
				]
			);

			$element->add_control(
				'jet_family_column_orientaion_wrap',
				[
					'label'        => esc_html__( 'Content Wrap', 'jet-elements' ),
					'description'  => esc_html__( 'Specifies that the flexible items will wrap if necessary', 'jet-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
					'label_off'    => esc_html__( 'No', 'jet-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'prefix_class' => 'jet-family-column-flex-wrap-',
					'condition' => [
						'jet_family_column_orientaion' => 'true',
					],
				]
			);

			$element->add_control(
				'jet_family_use_column_order',
				[
					'label'        => esc_html__( 'Content Order', 'jet-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
					'label_off'    => esc_html__( 'No', 'jet-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'separator'    => 'before',
				]
			);

			$element->add_responsive_control(
				'jet_family_column_order',
				[
					'label'       => esc_html__( 'Column Order', 'jet-elements' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 100,
					'selectors'   => [
						'{{WRAPPER}}.elementor-column' => 'order: {{VALUE}}',
					],
					'condition' => [
						'jet_family_use_column_order' => 'true'
					],
				]
			);

			$element->end_controls_section();
		}

		/**
		 * [widget_tab_advanced_add_section description]
		 * @param  [type] $element [description]
		 * @param  [type] $args    [description]
		 * @return [type]          [description]
		 */
		public function widget_tab_advanced_add_section( $element, $args ) {

			$element->start_controls_section(
				'jet_family_widget_orientaion_section',
				[
					'label' => esc_html__( 'Jet Advanced', 'jet-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'jet_family_widget_is_order_orientaion',
				[
					'label'        => esc_html__( 'Use Order', 'jet-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
					'label_off'    => esc_html__( 'No', 'jet-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
				]
			);

			$element->add_responsive_control(
				'jet_family_widget_order_orientaion',
				[
					'label'       => esc_html__( 'Column Order', 'jet-elements' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 100,
					'selectors'   => [
						'{{WRAPPER}}' => 'order: {{VALUE}}',
					],
					'condition' => [
						'jet_family_widget_is_order_orientaion' => 'true',
					],
				],
				[
					'position' => [
						'at' => 'before',
						'of' => 'content_position',
					],
				]
			);

			$element->add_control(
				'jet_family_widget_is_flex_basis',
				[
					'label'        => esc_html__( 'Use Flex Basis Width', 'jet-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
					'label_off'    => esc_html__( 'No', 'jet-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
				]
			);

			$element->add_responsive_control(
				'jet_family_widget_flex_basis',
				[
					'label'      => esc_html__( 'Flex Basis Width', 'jet-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%', 'px' ],
					'range'      => [
						'%' => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 10,
							'max' => 1000,
						],
					],
					'default' => [
						'size' => 0,
						'unit' => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}}' => 'flex-basis: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'jet_family_widget_is_flex_basis' => 'true',
					],
				]
			);

			$element->end_controls_section();
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
 * Returns instance of Jet_Family_Column_Orientation_Ext
 *
 * @return object
 */
function jet_family_column_orientation_ext() {
	return Jet_Family_Column_Orientation_Ext::get_instance();
}
