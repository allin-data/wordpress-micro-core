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

if ( ! class_exists( 'Jet_Elements_Ext_Section' ) ) {

	/**
	 * Define Jet_Elements_Ext_Section class
	 */
	class Jet_Elements_Ext_Section {

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

			$avaliable_extensions = jet_elements_settings()->get( 'avaliable_extensions', jet_elements_settings()->default_avaliable_extensions );

			if ( ! filter_var( $avaliable_extensions['section_parallax'], FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			add_action( 'elementor/element/section/section_layout/after_section_end', array( $this, 'after_section_end' ), 10, 2 );

			add_action( 'elementor/frontend/element/before_render', array( $this, 'section_before_render' ) );

			add_action( 'elementor/frontend/section/before_render', array( $this, 'section_before_render' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}

		/**
		 * After section_layout callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_section_end( $obj, $args ) {

			if ( class_exists( 'Jet_Parallax' ) ) {
				return false;
			}

			$obj->start_controls_section(
				'section_parallax',
				array(
					'label' => esc_html__( 'Jet Parallax', 'jet-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_LAYOUT,
				)
			);

			$obj->add_control(
				'jet_parallax_items_heading',
				array(
					'label'     => esc_html__( 'Layouts', 'jet-elements' ),
					'type'      => Elementor\Controls_Manager::HEADING,
				)
			);

			$repeater = new Elementor\Repeater();

			$repeater->add_responsive_control(
				'jet_parallax_layout_image',
				array(
					'label'   => esc_html__( 'Image', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::MEDIA,
					'dynamic' => array( 'active' => true ),
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.jet-parallax-section__layout .jet-parallax-section__image' => 'background-image: url("{{URL}}") !important;'
					),
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_speed',
				array(
					'label'      => esc_html__( 'Parallax Speed(%)', 'jet-elements' ),
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
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_type',
				array(
					'label'   => esc_html__( 'Parallax Type', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'scroll',
					'options' => array(
						'none'   => esc_html__( 'None', 'jet-elements' ),
						'scroll' => esc_html__( 'Scroll', 'jet-elements' ),
						'mouse'  => esc_html__( 'Mouse Move', 'jet-elements' ),
						'zoom'   => esc_html__( 'Scrolling Zoom', 'jet-elements' ),
					),
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_z_index',
				array(
					'label'    => esc_html__( 'z-Index', 'jet-elements' ),
					'type'     => Elementor\Controls_Manager::NUMBER,
					'min'      => 0,
					'max'      => 99,
					'step'     => 1,
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_bg_x',
				array(
					'label'   => esc_html__( 'Background X Position(%)', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => -200,
					'max'     => 200,
					'step'    => 1,
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_bg_y',
				array(
					'label'   => esc_html__( 'Background Y Position(%)', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => -200,
					'max'     => 200,
					'step'    => 1,
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_bg_size',
				array(
					'label'   => esc_html__( 'Background Size', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'auto',
					'options' => array(
						'auto'    => esc_html__( 'Auto', 'jet-elements' ),
						'cover'   => esc_html__( 'Cover', 'jet-elements' ),
						'contain' => esc_html__( 'Contain', 'jet-elements' ),
					),
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_animation_prop',
				array(
					'label'   => esc_html__( 'Animation Property', 'jet-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'transform',
					'options' => array(
						'bgposition'  => esc_html__( 'Background Position', 'jet-elements' ),
						'transform'   => esc_html__( 'Transform', 'jet-elements' ),
						'transform3d' => esc_html__( 'Transform 3D', 'jet-elements' ),
					),
				)
			);

			$repeater->add_control(
				'jet_parallax_layout_on',
				array(
					'label'       => __( 'Enable On Device', 'jet-elements' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => 'true',
					'default'     => array(
						'desktop',
						'tablet',
					),
					'options'     => array(
						'desktop' => __( 'Desktop', 'jet-elements' ),
						'tablet'  => __( 'Tablet', 'jet-elements' ),
						'mobile'  => __( 'Mobile', 'jet-elements' ),
					),
				)
			);

			$obj->add_control(
				'jet_parallax_layout_list',
				array(
					'type'    => Elementor\Controls_Manager::REPEATER,
					'fields'  => array_values( $repeater->get_controls() ),
					'default' => array(
						array(
							'jet_parallax_layout_image' => array(
								'url' => '',
							),
						)
					),
				)
			);

			$obj->end_controls_section();
		}

		/**
		 * Elementor before section render callback
		 *
		 * @param  object $obj
		 * @return void
		 */
		public function section_before_render( $obj ) {
			$data     = $obj->get_data();
			$type     = isset( $data['elType'] ) ? $data['elType'] : 'section';
			$settings = $data['settings'];

			if ( 'section' === $type ) {

				if ( isset( $settings['jet_parallax_layout_list'] ) ) {
					$parallax_layout_list = method_exists( $obj, 'get_settings_for_display' ) ? $obj->get_settings_for_display( 'jet_parallax_layout_list' ) : $settings['jet_parallax_layout_list'];

					if ( is_array( $parallax_layout_list ) && ! empty( $parallax_layout_list ) ) {

						foreach ( $parallax_layout_list as $key => $layout ) {
							if ( empty( $layout['jet_parallax_layout_image']['url'] )
								&& empty( $layout['jet_parallax_layout_image_tablet']['url'] )
								&& empty( $layout['jet_parallax_layout_image_mobile']['url'] )
							) {
								continue;
							}

							if ( ! in_array( $data['id'], $this->parallax_sections ) ) {
								$this->parallax_sections[ $data['id'] ] = $parallax_layout_list;
							}
						}
					}

				}
			}
		}

		/**
		 * [enqueue_scripts description]
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			if ( ! empty( $this->parallax_sections ) || jet_elements()->elementor()->preview->is_preview_mode() ) {
				wp_enqueue_script( 'jet-tween-js' );
				jet_elements_assets()->localize_data['jetParallaxSections'] = $this->parallax_sections;
			}
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
 * Returns instance of Jet_Elements_Ext_Section
 *
 * @return object
 */
function jet_elements_ext_section() {
	return Jet_Elements_Ext_Section::get_instance();
}
