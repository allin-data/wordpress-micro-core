<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Generator' ) ) {

	/**
	 * Define Jet_Popup_Generator class
	 */
	class Jet_Popup_Generator {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * [$popup_default_settings description]
		 * @var [type]
		 */
		public $popup_default_settings = [
			'jet_popup_type'                 => 'default',
			'jet_popup_animation'            => 'fade',
			'jet_popup_open_trigger'         => 'attach',
			'jet_popup_page_load_delay'      => 1,
			'jet_popup_user_inactivity_time' => 3,
			'jet_popup_scrolled_to_value'    => 10,
			'jet_popup_on_date_value'        => '',
			'jet_popup_custom_selector'      => '',
			'jet_popup_show_once'            => false,
			'jet_popup_show_again_delay'     => 'none',
			'jet_popup_use_ajax'             => false,
			'jet_popup_force_ajax'           => false,
			'jet_role_condition'             => [],
			'close_button_icon'              => 'fa fa-times',
		];

		/**
		 * [$popup_id_list description]
		 * @var array
		 */
		public $popup_id_list = [];

		/**
		 * [$ajax_popup_id_list description]
		 * @var array
		 */
		public $ajax_popup_id_list = [];

		/**
		 * [$depended_scripts description]
		 * @var array
		 */
		public $depended_scripts = [];

		/**
		 * [$fonts_to_enqueue description]
		 * @var array
		 */
		public $fonts_to_enqueue = [];

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// Popup initialization
			add_action( 'wp_footer', array( $this, 'page_popups_init' ), 9 );

			// Popups render
			add_action( 'wp_footer', array( $this, 'page_popups_render' ), 10 );

			// Before Enqueue Scripts
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'page_popups_before_enqueue_scripts' ) );
		}

		/**
		 * Page popup initialization
		 *
		 * @since 1.0.0
		 * @return void|boolean
		 */
		public function page_popups_init() {

			$this->define_page_popups();
		}

		/**
		 * [get_page_popups description]
		 * @return [type] [description]
		 */
		public function define_page_popups() {

			if ( ! jet_popup()->has_elementor() || ! empty( $_GET['elementor-preview'] ) ) {
				return false;
			}

			$condition_popups = jet_popup()->conditions->find_matched_conditions( 'jet-popup' );

			if ( ! empty( $condition_popups ) && is_array( $condition_popups ) ) {
				$this->popup_id_list = array_merge( $this->popup_id_list, $condition_popups );
			}

			$attached_popups = jet_popup()->conditions->get_attached_popups();

			if ( ! empty( $attached_popups ) && is_array( $attached_popups ) ) {
				$this->popup_id_list = array_merge( $this->popup_id_list, $attached_popups );
			}

			if ( ! $this->popup_id_list || empty( $this->popup_id_list ) || ! is_array( $this->popup_id_list ) ) {
				return false;
			}

			$this->popup_id_list = array_unique( $this->popup_id_list );

			$this->define_popups_assets();
		}

		/**
		 * [define_popup_assets description]
		 * @return [type] [description]
		 */
		public function define_popups_assets() {

			if ( ! empty( $this->popup_id_list ) ) {

				foreach ( $this->popup_id_list as $key => $popup_id ) {
					$meta_settings = get_post_meta( $popup_id, '_elementor_page_settings', true );

					$popup_settings_main = wp_parse_args( $meta_settings, $this->popup_default_settings );

					if ( filter_var( $popup_settings_main['jet_popup_use_ajax'], FILTER_VALIDATE_BOOLEAN ) ) {
						$popup_id = apply_filters( 'jet-popup/popup-generator/before-define-popup-assets/popup-id', $popup_id, $popup_settings_main );

						$document = Elementor\Plugin::$instance->documents->get( $popup_id );

						$elements_data = $document->get_elements_raw_data();

						$this->find_widgets_script_handlers( $elements_data );

						$this->find_popup_fonts( $popup_id );
					}
				}
			}
		}

		/**
		 * [page_popups_render description]
		 * @return [type] [description]
		 */
		public function page_popups_render() {
			if ( ! empty( $this->popup_id_list ) ) {

				foreach ( $this->popup_id_list as $key => $popup_id ) {
					$this->popup_render( $popup_id );
				}

				// Init Elementor frontend essets if popup loaded using ajax
				if ( ! empty( $this->ajax_popup_id_list ) && ! Elementor\Plugin::$instance->frontend->has_elementor_in_page() ) {
					Elementor\Plugin::$instance->frontend->enqueue_styles();
					Elementor\Plugin::$instance->frontend->enqueue_scripts();
				}

				//Print popup google fonts link
				$this->print_fonts_links();
			}
		}

		/**
		 * [popup_render description]
		 * @param  [type] $popup_id [description]
		 * @return [type]           [description]
		 */
		public function popup_render( $popup_id ) {

			$meta_settings = get_post_meta( $popup_id, '_elementor_page_settings', true );

			$popup_settings_main = wp_parse_args( $meta_settings, $this->popup_default_settings );

			// Is Avaliable For User Check
			if ( ! $this->is_avaliable_for_user( $popup_settings_main['jet_role_condition'] ) ) {
				return false;
			}

			$close_button_html = '';

			$use_close_button = isset( $popup_settings_main['use_close_button'] ) ? filter_var( $popup_settings_main['use_close_button'], FILTER_VALIDATE_BOOLEAN ) : true;

			if ( isset( $popup_settings_main['close_button_icon'] ) && $use_close_button ) {
				$close_button_icon = $popup_settings_main['close_button_icon'];
				$close_button_html = sprintf( '<div class="jet-popup__close-button"><i class="%s"></i></div>', $close_button_icon );
			}

			$overlay_html = '';

			$use_overlay = isset( $popup_settings_main['use_overlay'] ) ? filter_var( $popup_settings_main['use_overlay'], FILTER_VALIDATE_BOOLEAN ) : true;

			if ( $use_overlay ) {
				$use_ajax = filter_var( $popup_settings_main['jet_popup_use_ajax'], FILTER_VALIDATE_BOOLEAN );

				$overlay_html = sprintf(
					'<div class="jet-popup__overlay">%s</div>',
					$use_ajax ? '<div class="jet-popup-loader"></div>' : ''
				);
			}

			$jet_popup_show_again_delay = Jet_Popup_Utils::get_milliseconds_by_tag( $popup_settings_main['jet_popup_show_again_delay'] );

			$popup_json = [
				'id'                   => $popup_id,
				'jet-popup-id'         => 'jet-popup-' . $popup_id,
				'type'                 => $popup_settings_main['jet_popup_type'],
				'animation'            => $popup_settings_main['jet_popup_animation'],
				'open-trigger'         => $popup_settings_main['jet_popup_open_trigger'],
				'page-load-delay'      => $popup_settings_main['jet_popup_page_load_delay'],
				'user-inactivity-time' => $popup_settings_main['jet_popup_user_inactivity_time'],
				'scrolled-to'          => $popup_settings_main['jet_popup_scrolled_to_value'],
				'on-date'              => $popup_settings_main['jet_popup_on_date_value'],
				'custom-selector'      => $popup_settings_main['jet_popup_custom_selector'],
				'show-once'            => filter_var( $popup_settings_main['jet_popup_show_once'], FILTER_VALIDATE_BOOLEAN ),
				'show-again-delay'     => $jet_popup_show_again_delay,
				'use-ajax'             => filter_var( $popup_settings_main['jet_popup_use_ajax'], FILTER_VALIDATE_BOOLEAN ),
				'force-ajax'           => filter_var( $popup_settings_main['jet_popup_force_ajax'], FILTER_VALIDATE_BOOLEAN ),
			];

			if ( filter_var( $popup_settings_main['jet_popup_use_ajax'], FILTER_VALIDATE_BOOLEAN ) ) {
				$this->ajax_popup_id_list[] = $popup_id;
			}

			$popup_json_data = htmlspecialchars( json_encode( $popup_json ) );

			include jet_popup()->get_template( 'popup-container.php' );
		}

		/**
		 * [page_popups_before_enqueue_scripts description]
		 * @return [type] [description]
		 */
		public function page_popups_before_enqueue_scripts() {

			$script_depends = $this->get_script_depends();

			$script_depends = array_unique( $script_depends );

			foreach ( $script_depends as $script ) {
				wp_enqueue_script( $script );
			}
		}

		/**
		 * [get_popup_widgets description]
		 * @param  [type] $elements_data [description]
		 * @return [type]                [description]
		 */
		public function find_widgets_script_handlers( $elements_data ) {

			foreach ( $elements_data as $element_data ) {

				if ( 'widget' === $element_data['elType'] ) {
					$widget = Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

					$widget_script_depends = $widget->get_script_depends();

					if ( ! empty( $widget_script_depends ) ) {
						foreach ( $widget_script_depends as $key => $script_handler ) {
							$this->depended_scripts[] = $script_handler;
						}
					}

				} else {
					$element = Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

					$childrens = $element->get_children();

					foreach ( $childrens as $key => $children ) {
						$children_data[$key] = $children->get_raw_data();

						$this->find_widgets_script_handlers( $children_data );
					}
				}
			}
		}

		/**
		 * [jet_popup_get_content description]
		 * @return [type] [description]
		 */
		public function find_popup_fonts( $popup_id ) {

			$post_css = new Elementor\Core\Files\CSS\Post( $popup_id );

			$post_meta = $post_css->get_meta();

			if ( ! isset( $post_meta['fonts'] ) ) {
				return false;
			}

			$google_fonts = $post_meta['fonts'];

			$this->fonts_to_enqueue = array_merge( $this->fonts_to_enqueue, $google_fonts );
		}

		/**
		 * [print_fonts_links description]
		 * @return [type] [description]
		 */
		public function print_fonts_links() {

			if ( empty( $this->fonts_to_enqueue ) ) {
				return false;
			}

			$this->fonts_to_enqueue = array_unique( $this->fonts_to_enqueue );

			foreach ( $this->fonts_to_enqueue as &$font ) {
				$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
			}

			$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $this->fonts_to_enqueue ) );

			$subsets = [
				'ru_RU' => 'cyrillic',
				'bg_BG' => 'cyrillic',
				'he_IL' => 'hebrew',
				'el'    => 'greek',
				'vi'    => 'vietnamese',
				'uk'    => 'cyrillic',
				'cs_CZ' => 'latin-ext',
				'ro_RO' => 'latin-ext',
				'pl_PL' => 'latin-ext',
			];

			$locale = get_locale();

			if ( isset( $subsets[ $locale ] ) ) {
				$fonts_url .= '&subset=' . $subsets[ $locale ];
			}

			wp_enqueue_style( 'jet-popup-google-fonts', $fonts_url );
		}

		/**
		 * Get script dependencies.
		 *
		 * Retrieve the list of script dependencies the element requires.
		 *
		 * @since 1.3.0
		 * @access public
		 *
		 * @return array Element scripts dependencies.
		 */
		public function get_script_depends() {

			return $this->depended_scripts;
		}

		/**
		 * [is_avaliable_for_user description]
		 * @param  [type]  $popup_roles [description]
		 * @return boolean              [description]
		 */
		public function is_avaliable_for_user( $popup_roles ) {

			if ( empty( $popup_roles ) ) {
				return true;
			}

			$user     = wp_get_current_user();
			$is_guest = empty( $user->roles ) ? true : false;

			if ( ! $is_guest ) {
				$user_role = $user->roles[0];
			} else {
				$user_role = 'guest';
			}

			if ( in_array( $user_role, $popup_roles ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Output location content by template ID
		 * @param  integer $template_id [description]
		 * @param  string  $location    [description]
		 * @return [type]               [description]
		 */
		public function print_location_content( $template_id = 0 ) {

			$plugin    = Elementor\Plugin::instance();

			$content   = $plugin->frontend->get_builder_content( $template_id, false );

			if ( empty( $_GET['elementor-preview'] ) ) {
				echo $content;
			} else {
				printf(
					'<div class="jet-popup-edit">
						%1$s
						<a href="%2$s" target="_blank" class="jet-popup-edit__link elementor-clickable">
							<div class="jet-popup-edit__link-content"><span class="dashicons dashicons-edit"></span>%3$s</div>
						</a>
					</div>',
					$content,
					Elementor\Utils::get_edit_link( $template_id ),
					esc_html__( 'Edit Popup', 'jet-popup' )
				);
			}

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
