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

if ( ! class_exists( 'Jet_Menu_Dynamic_CSS' ) ) {

	/**
	 * Define Jet_Menu_Dynamic_CSS class
	 */
	class Jet_Menu_Dynamic_CSS {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Builder instance
		 *
		 * @var null
		 */
		public $builder = null;

		/**
		 * Fonts holder.
		 *
		 * @var array
		 */
		private $fonts = null;

		/**
		 * Initialize builde rinstance
		 *
		 * @param  [type] $builder [description]
		 * @return [type]          [description]
		 */
		public function init_builder( $builder ) {
			$this->builder= $builder;
		}

		/**
		 * Register typography options.
		 *
		 * @param array $args [description]
		 */
		public function add_typography_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'   => '',
				'name'    => '',
				'parent'  => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s typography', 'jet-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-font-size' => array(
						'type'       => 'slider',
						'max_value'  => 70,
						'min_value'  => 8,
						'value'      => $this->get_option(
							$args['name'] . '-font-size',
							isset( $args['defaults']['font-size'] ) ? $args['defaults']['font-size'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s font size', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-font-family' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font family', 'jet-menu' ), $args['label'] ),
						'filter'           => true,
						'value'            => $this->get_option(
							$args['name'] . '-font-family',
							isset( $args['defaults']['font-family'] ) ? $args['defaults']['font-family'] : false
						),
						'options'          => $this->get_fonts_list(),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-font-weight' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font weight', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-font-weight',
							isset( $args['defaults']['font-weight'] ) ? $args['defaults']['font-weight'] : false
						),
						'options'          => array(
							''       => esc_html__( 'Default', 'jet-menu' ),
							'100'    => '100',
							'200'    => '200',
							'300'    => '300',
							'400'    => '400',
							'500'    => '500',
							'600'    => '600',
							'700'    => '700',
							'800'    => '800',
							'900'    => '900',
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-text-transform' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s text transform', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-text-transform',
							isset( $args['defaults']['text-transform'] ) ? $args['defaults']['text-transform'] : false
						),
						'options'          => array(
							''           => esc_html__( 'Default', 'jet-menu' ),
							'uppercase'  => esc_html__( 'Uppercase', 'jet-menu' ),
							'lowercase'  => esc_html__( 'Lowercase', 'jet-menu' ),
							'capitalize' => esc_html__( 'Capitalize', 'jet-menu' ),
							'none'       => esc_html__( 'Normal', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-font-style' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s font style', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-font-style',
							isset( $args['defaults']['font-style'] ) ? $args['defaults']['font-style'] : false
						),
						'options'          => array(
							''           => esc_html__( 'Default', 'jet-menu' ),
							'normal' => esc_html__( 'Normal', 'jet-menu' ),
							'italic' => esc_html__( 'Italic', 'jet-menu' ),
							'oblique' => esc_html__( 'Oblique', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-line-height' => array(
						'type'       => 'slider',
						'max_value'  => 10,
						'min_value'  => 0.1,
						'value'      => $this->get_option(
							$args['name'] . '-line-height',
							isset( $args['defaults']['line-height'] ) ? $args['defaults']['line-height'] : false
						),
						'step_value' => 0.1,
						'title'      => sprintf( esc_html__( '%s line height', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-letter-spacing' => array(
						'type'       => 'slider',
						'max_value'  => 5,
						'min_value'  => -5,
						'value'      => $this->get_option(
							$args['name'] . '-letter-spacing',
							isset( $args['defaults']['letter-spacing'] ) ? $args['defaults']['letter-spacing'] : false
						),
						'step_value' => 0.1,
						'title'      => sprintf( esc_html__( '%s letter spacing', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-show',
					),
					$args['name'] . '-subset' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s subset', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-subset',
							isset( $args['defaults']['subset'] ) ? $args['defaults']['subset'] : false
						),
						'options'          => array(
							'latin'    => esc_html__( 'Latin', 'jet-menu' ),
							'greek'    => esc_html__( 'Greek', 'jet-menu' ),
							'cyrillic' => esc_html__( 'Cyrillic', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
				)
			);

		}

		/**
		 * Background options array
		 *
		 * @param array $args [description]
		 */
		public function add_background_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s background', 'jet-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s background color', 'jet-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-color',
							isset( $args['defaults']['color'] ) ? $args['defaults']['color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-gradient-switch' => array(
						'type'   => 'switcher',
						'title'  => esc_html__( 'Gradient background', 'jet-menu' ),
						'value'  => $this->get_option( $args['name'] . '-gradient-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => $args['parent'],
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-second-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s background second color', 'jet-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-second-color',
							isset( $args['defaults']['second-color'] ) ? $args['defaults']['second-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-show',
					),
					$args['name'] . '-direction' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background gradient direction', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-direction',
							isset( $args['defaults']['direction'] ) ? $args['defaults']['direction'] : false
						),
						'options'          => array(
							'right'  => esc_html__( 'From Left to Right', 'jet-menu' ),
							'left'   => esc_html__( 'From Right to Left', 'jet-menu' ),
							'bottom' => esc_html__( 'From Top to Bottom', 'jet-menu' ),
							'top'    => esc_html__( 'From Bottom to Top', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-image' => array(
						'type'           => 'media',
						'parent'         => $args['parent'],
						'title'          => sprintf( esc_html__( '%s background image', 'jet-menu' ), $args['label'] ),
						'value'          => $this->get_option(
							$args['name'] . '-image',
							isset( $args['defaults']['image'] ) ? $args['defaults']['image'] : false
						),
						'multi_upload'       => false,
						'library_type'       => 'image',
						'upload_button_text' => esc_html__( 'Choose Image', 'jet-menu' ),
						'class'              => '',
						'label'              => '',
						'master'             => $args['name'] . '-show',
					),
					$args['name'] . '-position' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background position', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-position',
							isset( $args['defaults']['position'] ) ? $args['defaults']['position'] : false
						),
						'options'          => array(
							''              => esc_html__( 'Default', 'jet-menu' ),
							'top left'      => esc_html__( 'Top Left', 'jet-menu' ),
							'top center'    => esc_html__( 'Top Center', 'jet-menu' ),
							'top right'     => esc_html__( 'Top Right', 'jet-menu' ),
							'center left'   => esc_html__( 'Center Left', 'jet-menu' ),
							'center center' => esc_html__( 'Center Center', 'jet-menu' ),
							'center right'  => esc_html__( 'Center Right', 'jet-menu' ),
							'bottom left'   => esc_html__( 'Bottom Left', 'jet-menu' ),
							'bottom center' => esc_html__( 'Bottom Center', 'jet-menu' ),
							'bottom right'  => esc_html__( 'Bottom Right', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-attachment' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background attachment', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-attachment',
							isset( $args['defaults']['attachment'] ) ? $args['defaults']['attachment'] : false
						),
						'options'          => array(
							''       => esc_html__( 'Default', 'jet-menu' ),
							'scroll' => esc_html__( 'Scroll', 'jet-menu' ),
							'fixed'  => esc_html__( 'Fixed', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-repeat' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background repeat', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-repeat',
							isset( $args['defaults']['repeat'] ) ? $args['defaults']['repeat'] : false
						),
						'options'          => array(
							''          => esc_html__( 'Default', 'jet-menu' ),
							'no-repeat' => esc_html__( 'No Repeat', 'jet-menu' ),
							'repeat'    => esc_html__( 'Repeat', 'jet-menu' ),
							'repeat-x'  => esc_html__( 'Repeat X', 'jet-menu' ),
							'repeat-y'  => esc_html__( 'Repeat Y', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
					$args['name'] . '-size' => array(
						'type'             => 'select',
						'parent'           => $args['parent'],
						'title'            => sprintf( esc_html__( '%s background size', 'jet-menu' ), $args['label'] ),
						'value'            => $this->get_option(
							$args['name'] . '-size',
							isset( $args['defaults']['size'] ) ? $args['defaults']['size'] : false
						),
						'options'          => array(
							''        => esc_html__( 'Default', 'jet-menu' ),
							'auto'    => esc_html__( 'Auto', 'jet-menu' ),
							'cover'   => esc_html__( 'Cover', 'jet-menu' ),
							'contain' => esc_html__( 'Contain', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-show',
					),
				)
			);

		}

		/**
		 * Register border options
		 *
		 * @param array $args [description]
		 */
		public function add_border_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-border-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s border', 'jet-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-border-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-border-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-border-style' => array(
						'type'    => 'select',
						'parent'  => $args['parent'],
						'title'   => sprintf( esc_html__( '%s border style', 'jet-menu' ), $args['label'] ),
						'value'   => $this->get_option(
							$args['name'] . '-border-style',
							isset( $args['defaults']['border-style'] ) ? $args['defaults']['border-style'] : false
						),
						'options' => array(
							'solid'  => esc_html__( 'Solid', 'jet-menu' ),
							'double' => esc_html__( 'Double', 'jet-menu' ),
							'dotted' => esc_html__( 'Dotted', 'jet-menu' ),
							'dashed' => esc_html__( 'Dashed', 'jet-menu' ),
							'none'   => esc_html__( 'None', 'jet-menu' ),
						),
						'master'           => $args['name'] . '-border-show',
					),
					$args['name'] . '-border-width' => array(
						'type'        => 'dimensions',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s border width', 'jet-menu' ), $args['label'] ),
						'range'       => array(
							'px' => array(
								'min'  => 0,
								'max'  => 30,
								'step' => 1,
							),
						),
						'value' => $this->get_option(
							$args['name'] . '-border-width',
							isset( $args['defaults']['border-width'] ) ? $args['defaults']['border-width'] : false
						),
						'master' => $args['name'] . '-border-show',
					),
					$args['name'] . '-border-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s border color', 'jet-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-border-color',
							isset( $args['defaults']['border-color'] ) ? $args['defaults']['border-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-border-show',
					),
				)
			);

		}

		/**
		 * Register box-shadow options
		 *
		 * @param array $args [description]
		 */
		public function add_box_shadow_options( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'label'    => '',
				'name'     => '',
				'parent'   => '',
				'defaults' => array(),
			) );

			$this->builder->register_control(
				array(
					$args['name'] . '-box-shadow-switch' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s box shadow', 'jet-menu' ), $args['label'] ),
						'value'  => $this->get_option( $args['name'] . '-box-shadow-switch', 'false' ),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
							'true_slave'   => $args['name'] . '-box-shadow-show',
						),
						'parent' => $args['parent'],
					),
					$args['name'] . '-box-shadow-h' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-h',
							isset( $args['defaults']['box-shadow-h'] ) ? $args['defaults']['box-shadow-h'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - position of the horizontal shadow', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-v' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-v',
							isset( $args['defaults']['box-shadow-v'] ) ? $args['defaults']['box-shadow-v'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - position of the vertical shadow', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-blur' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-blur',
							isset( $args['defaults']['box-shadow-blur'] ) ? $args['defaults']['box-shadow-blur'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - shadow blur distance', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-spread' => array(
						'type'       => 'slider',
						'max_value'  => 50,
						'min_value'  => -50,
						'value'      => $this->get_option(
							$args['name'] . '-box-shadow-spread',
							isset( $args['defaults']['box-shadow-spread'] ) ? $args['defaults']['box-shadow-spread'] : false
						),
						'step_value' => 1,
						'title'      => sprintf( esc_html__( '%s - shadow size', 'jet-menu' ), $args['label'] ),
						'parent'     => $args['parent'],
						'master'     => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-color' => array(
						'type'        => 'colorpicker',
						'parent'      => $args['parent'],
						'title'       => sprintf( esc_html__( '%s shadow color', 'jet-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-box-shadow-color',
							isset( $args['defaults']['box-shadow-color'] ) ? $args['defaults']['box-shadow-color'] : false
						),
						'alpha'       => true,
						'master'      => $args['name'] . '-box-shadow-show',
					),
					$args['name'] . '-box-shadow-inset' => array(
						'type'   => 'switcher',
						'title'  => sprintf( esc_html__( '%s shadow inset', 'jet-menu' ), $args['label'] ),
						'value'       => $this->get_option(
							$args['name'] . '-box-shadow-color',
							isset( $args['defaults']['box-shadow-inset'] ) ? $args['defaults']['box-shadow-inset'] : 'false'
						),
						'toggle' => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
						'parent' => $args['parent'],
						'master' => $args['name'] . '-box-shadow-show',
					),
				)
			);

		}

		/**
		 * Returns google fonts list.
		 *
		 * @return array
		 */
		public function get_fonts_list() {

			if ( empty( $this->fonts ) ) {
				$this->fonts = jet_menu()->customizer()->get_fonts();
				$this->fonts = array_merge( array( '0' => esc_html__( 'Select Font...', 'jet-menu' ) ), $this->fonts );
			}

			return $this->fonts;
		}

		/**
		 * Add font-related styles.
		 */
		public function add_fonts_styles( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.jet-menu';

			$fonts_options = apply_filters( 'jet-menu/menu-css/fonts', array(
				'jet-top-menu'       => '.jet-menu-item .top-level-link',
				'jet-top-menu-desc'  => '.jet-menu-item-desc.top-level-desc',
				'jet-sub-menu'       => '.jet-menu-item .sub-level-link',
				'jet-sub-menu-desc'  => '.jet-menu-item-desc.sub-level-desc',
				'jet-menu-top-badge' => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'jet-menu-sub-badge' => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
			) );

			foreach ( $fonts_options as $font => $selector ) {
				$this->add_single_font_styles( $font, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add backgound styles.
		 */
		public function add_backgrounds( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.jet-menu';

			$bg_options = apply_filters( 'jet-menu/menu-css/backgrounds', array(
				'jet-menu-container'        => '',
				'jet-menu-item'             => '.jet-menu-item .top-level-link',
				'jet-menu-item-hover'       => '.jet-menu-item:hover > .top-level-link',
				'jet-menu-item-active'      => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'jet-menu-top-badge-bg'     => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'jet-menu-sub-badge-bg'     => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'jet-menu-sub-panel-simple' => 'ul.jet-sub-menu',
				'jet-menu-sub-panel-mega'   => 'div.jet-sub-mega-menu',
				'jet-menu-sub'              => 'li.jet-sub-menu-item .sub-level-link',
				'jet-menu-sub-hover'        => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'jet-menu-sub-active'       => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
			) );

			foreach ( $bg_options as $option => $selector ) {
				$this->add_single_bg_styles( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add border styles.
		 */
		public function add_borders( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.jet-menu';

			$options = apply_filters( 'jet-menu/menu-css/borders', array(
				'jet-menu-container'         => '',
				'jet-menu-item'              => '.jet-menu-item .top-level-link',
				'jet-menu-first-item'        => '> .jet-regular-item:first-child .top-level-link',
				'jet-menu-last-item'         => array(
					'> .jet-regular-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
					'> .jet-regular-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
					'> .jet-responsive-menu-available-items:last-child .top-level-link',
				),
				'jet-menu-item-hover'        => '.jet-menu-item:hover > .top-level-link',
				'jet-menu-first-item-hover'  => '> .jet-regular-item:first-child:hover > .top-level-link',
				'jet-menu-last-item-hover'   => array(
					'> .jet-regular-item.jet-has-roll-up:nth-last-child(2):hover .top-level-link',
					'> .jet-regular-item.jet-no-roll-up:nth-last-child(1):hover .top-level-link',
					'> .jet-responsive-menu-available-items:last-child:hover .top-level-link',
				),
				'jet-menu-item-active'       => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'jet-menu-first-item-active' => '> .jet-regular-item:first-child.jet-current-menu-item .top-level-link',
				'jet-menu-last-item-active'  => array(
					'> .jet-regular-item.jet-current-menu-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
					'> .jet-regular-item.jet-current-menu-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
					'> .jet-responsive-menu-available-items.jet-current-menu-item:last-child .top-level-link',
				),
				'jet-menu-top-badge'         => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'jet-menu-sub-badge'         => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'jet-menu-sub-panel-simple'  => 'ul.jet-sub-menu',
				'jet-menu-sub-panel-mega'    => 'div.jet-sub-mega-menu',
				'jet-menu-sub'               => 'li.jet-sub-menu-item .sub-level-link',
				'jet-menu-sub-hover'         => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'jet-menu-sub-active'        => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'jet-menu-sub-first'         => '.jet-sub-menu > li.jet-sub-menu-item:first-child > .sub-level-link',
				'jet-menu-sub-first-hover'   => '.jet-sub-menu > li.jet-sub-menu-item:first-child:hover > .sub-level-link',
				'jet-menu-sub-first-active'  => '.jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:first-child > .sub-level-link',
				'jet-menu-sub-last'          => '.jet-sub-menu > li.jet-sub-menu-item:last-child > .sub-level-link',
				'jet-menu-sub-last-hover'    => '.jet-sub-menu > li.jet-sub-menu-item:last-child:hover > .sub-level-link',
				'jet-menu-sub-last-active'   => '.jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:last-child > .sub-level-link',
			) );

			foreach ( $options as $option => $selector ) {

				if ( is_array( $selector ) ) {

					$final_selector = '';
					$delimiter      = '';

					foreach ( $selector as $part ) {
						$final_selector .= sprintf(
							'%3$s%1$s %2$s',
							$wrapper,
							$part,
							$delimiter
						);
						$delimiter = ', ';
					}
				} else {
					$final_selector = $wrapper . ' ' . $selector;
				}

				$this->add_single_border_styles( $option, $final_selector );
			}

		}

		/**
		 * Add shadows styles.
		 */
		public function add_shadows( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.jet-menu';

			$options = apply_filters( 'jet-menu/menu-css/shadows', array(
				'jet-menu-container'        => '',
				'jet-menu-item'             => '.jet-menu-item .top-level-link',
				'jet-menu-item-hover'       => '.jet-menu-item:hover > .top-level-link',
				'jet-menu-item-active'      => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'jet-menu-top-badge'        => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'jet-menu-sub-badge'        => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'jet-menu-sub-panel-simple' => 'ul.jet-sub-menu',
				'jet-menu-sub-panel-mega'   => 'div.jet-sub-mega-menu',
				'jet-menu-sub'              => 'li.jet-sub-menu-item .sub-level-link',
				'jet-menu-sub-hover'        => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'jet-menu-sub-active'       => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
			) );

			foreach ( $options as $option => $selector ) {
				$this->add_single_shadow_styles( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * Add single font styles
		 */
		public function add_single_font_styles( $font, $selector ) {

			$enbaled = $this->get_option( $font . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$font_settings = array(
				'font-size'      => 'px',
				'font-family'    => '',
				'font-weight'    => '',
				'text-transform' => '',
				'font-style'     => '',
				'line-height'    => 'em',
				'letter-spacing' => 'em',
			);

			foreach ( $font_settings as $setting => $units ) {

				$value = $this->get_option( $font . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'font-family' === $setting && 0 === $value ) {
					continue;
				}

				jet_menu()->dynamic_css()->add_style(
					$selector,
					array(
						$setting => $value . $units,
					)
				);
			}

		}

		/**
		 * Add single background option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_bg_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'color',
				'image',
				'position',
				'attachment',
				'repeat',
				'size',
			);

			$is_gradient = $this->get_option( $option . '-gradient-switch' );

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'image' === $setting && 'true' !== $is_gradient ) {
					$value = wp_get_attachment_image_url( $value, 'full' );
					$value = sprintf( 'url("%s")', esc_url( $value ) );
				}

				jet_menu()->dynamic_css()->add_style(
					$selector,
					array(
						'background-' . $setting => $value,
					)
				);

			}

			if ( 'true' === $is_gradient ) {
				$color_start = $this->get_option( $option . '-color' );
				$color_end   = $this->get_option( $option . '-second-color' );
				$direction   = $this->get_option( $option . '-direction', 'horizontal' );

				if ( ! $color_start || ! $color_end ) {
					return;
				}

				jet_menu()->dynamic_css()->add_style(
					$selector,
					array(
						'background-image' => sprintf(
							'linear-gradient( to %1$s, %2$s, %3$s )',
							$direction, $color_start, $color_end
						),
					)
				);

			}

		}

		public function add_dimensions_css( $args = array() ) {

			$defaults = array(
				'selector'  => '',
				'rule'      => '',
				'values'    => array(),
				'important' => false,
			);

			$args = wp_parse_args( $args, $defaults );

			$value     = $args['values'];
			$selector  = $args['selector'];
			$rule      = $args['rule'];
			$important = ( true === $args['important'] ) ? ' !important' : '';

			$properties = array(
				'top'    => 'top-left',
				'right'  => 'top-right',
				'bottom' => 'bottom-right',
				'left'   => 'bottom-left',
			);

			foreach ( $properties as $position => $radius_position ) {

				if ( isset( $value[ $position ] ) && '' !== $value[ $position ] ) {

					$prop = $value[ $position ] . $value['units'] . $important;

					if ( false !== strpos( $rule, 'radius' ) ) {
						jet_menu()->dynamic_css()->add_style(
							$selector,
							array(
								sprintf( $rule, $radius_position ) => $prop,
							)
						);
					} else {
						jet_menu()->dynamic_css()->add_style(
							$selector,
							array(
								sprintf( $rule, $position ) => $prop,
							)
						);
					}

				}

			}
		}

		/**
		 * Add single border option.
		 *
		 * @param [type] $options  [description]
		 * @param [type] $selector [description]
		 */
		public function add_single_border_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-border-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$type = $this->get_option( $option . '-bg-type' );

			$settings = array(
				'border-style',
				'border-width',
				'border-color',
			);

			foreach ( $settings as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					continue;
				}

				if ( 'border-width' === $setting ) {

					jet_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector' => $selector,
							'rule'     => 'border-%s-width',
							'values'   => $value,
						)
					);

					continue;
				}

				jet_menu()->dynamic_css()->add_style(
					$selector,
					array(
						$setting => $value,
					)
				);

			}

		}

		public function add_single_shadow_styles( $option, $selector ) {

			$enbaled = $this->get_option( $option . '-box-shadow-switch' );

			if ( 'true' !== $enbaled ) {
				return;
			}

			$result = '';

			foreach ( array( 'box-shadow-h', 'box-shadow-v', 'box-shadow-blur' ) as $setting ) {

				$value = $this->get_option( $option . '-' . $setting );

				if ( '' === $value || false === $value ) {
					$value = 0;
				}

				$result .= $value . 'px ';
			}

			$spread = $this->get_option( $option . '-box-shadow-spread' );

			if ( '' !== $spread && false !== $spread ) {
				$result .= $spread . 'px ';
			}

			$color = $this->get_option( $option . '-box-shadow-color' );

			if ( '' !== $color && false !== $color ) {
				$result .= $color;
			}

			$inset = $this->get_option( $option . '-box-shadow-inset' );

			if ( 'true' === $inset ) {
				$result .= ' inset';
			}

			jet_menu()->dynamic_css()->add_style(
				$selector,
				array(
					'box-shadow' => $result,
				)
			);

		}

		/**
		 * Process position styles
		 */
		public function add_positions( $wrapper = '' ) {

			$wrapper = ( ! empty( $wrapper ) ) ? $wrapper : '.jet-menu';

			$options = apply_filters( 'jet-menu/menu-css/positions', array(
				'jet-menu-top-icon-%s-position'  => '.jet-menu-item .top-level-link .jet-menu-icon',
				'jet-menu-sub-icon-%s-position'  => '.jet-menu-item .sub-level-link .jet-menu-icon',
				'jet-menu-top-badge-%s-position' => '.jet-menu-item .top-level-link .jet-menu-badge',
				'jet-menu-sub-badge-%s-position' => '.jet-menu-item .sub-level-link .jet-menu-badge',
				'jet-menu-top-arrow-%s-position' => '.jet-menu-item .top-level-link .jet-dropdown-arrow',
				'jet-menu-sub-arrow-%s-position' => '.jet-menu-item .sub-level-link .jet-dropdown-arrow',
			) );

			foreach ( $options as $option => $selector ) {
				$this->add_single_position( $option, $wrapper . ' ' . $selector );
			}

		}

		/**
		 * add single position
		 */
		public function add_single_position( $option, $selector ) {

			$v_pos = $this->get_option( sprintf( $option, 'ver' ) );
			$h_pos = $this->get_option( sprintf( $option, 'hor' ) );

			$order_map = array(
				'left'  => -1,
				'right' => 2,
			);

			$styles = array();

			switch ( $v_pos ) {

				case 'top':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => -2,
					);
					break;

				case 'center':
					$styles = array(
						'align-self' => 'center',
					);
					break;

				case 'bottom':
					$styles = array(
						'flex'  => '0 0 100%',
						'width' => 0,
						'order' => 2,
					);
					break;
			}

			switch ( $h_pos ) {

				case 'left':
				case 'right':

					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = $h_pos;
					} else {
						$styles['order'] = $order_map[ $h_pos ];
					}
					break;

				case 'center':
					if ( in_array( $v_pos, array( 'top', 'bottom' ) ) ) {
						$styles['text-align'] = 'center';
					}
					break;

			}

			if ( 'jet-menu-sub-arrow-%s-position' === $option && 'right' === $h_pos ) {
				$styles['margin-left'] = 'auto !important';
			}

			if ( ! empty( $styles ) ) {
				jet_menu()->dynamic_css()->add_style( $selector, $styles );
			}

		}

		/**
		 * Get option wrapper
		 *
		 * @param  string  $option  [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get_option( $option = '', $default = false ) {
			return jet_menu_option_page()->get_option( $option, $default );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
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
 * Returns instance of Jet_Menu_Dynamic_CSS
 *
 * @return object
 */
function jet_menu_dynmic_css() {
	return Jet_Menu_Dynamic_CSS::get_instance();
}
