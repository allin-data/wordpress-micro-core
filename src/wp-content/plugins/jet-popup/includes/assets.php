<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Assets' ) ) {

	/**
	 * Define Jet_Popup_Assets class
	 */
	class Jet_Popup_Assets {

		/**
		 * [$localize_data description]
		 * @var [type]
		 */
		public $localize_data = [
			'elements_data' => [
				'sections' => [],
				'columns'  => [],
				'widgets'  => [],
			]
		];

		/**
		 * [$editor_localize_data description]
		 * @var array
		 */
		public $editor_localize_data = [];

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_popup_edit_assets' ), 11 );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_library_assets' ), 11 );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'jet-popup-frontend',
				jet_popup()->plugin_url( 'assets/css/jet-popup-frontend.css' ),
				false,
				jet_popup()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'jet-anime-js',
				jet_popup()->plugin_url( 'assets/js/lib/anime-js/anime.min.js' ),
				array( 'jquery' ),
				'2.0.2',
				true
			);

			wp_enqueue_script(
				'jet-popup-frontend',
				jet_popup()->plugin_url( 'assets/js/jet-popup-frontend' . $this->suffix() . '.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_popup()->get_version(),
				true
			);

			$this->localize_data['version'] = jet_popup()->get_version();
			$this->localize_data['ajax_url'] = esc_url( admin_url( 'admin-ajax.php' ) );

			wp_localize_script(
				'jet-popup-frontend',
				'jetPopupData',
				$this->localize_data
			);

		}

		/**
		 * Enqueue admin styles
		 *
		 * @return void
		 */
		public function enqueue_admin_assets() {

			wp_register_script(
				'jet-popup-vue',
				'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',
				[],
				'2.5.17',
				true
			);

			wp_register_script(
				'jet-axios',
				jet_popup()->plugin_url( 'assets/js/lib/axios/axios.min.js' ),
				[],
				'0.19.0-beta',
				true
			);

			wp_register_script(
				'jet-iview-locale-en-us',
				jet_popup()->plugin_url( 'assets/js/lib/iview/locale/en-US.js' ),
				[],
				'3.2.2',
				true
			);

			wp_register_script(
				'jet-iview',
				jet_popup()->plugin_url( 'assets/js/lib/iview/iview.min.js' ),
				[],
				'3.2.2',
				true
			);

			wp_register_script(
				'jet-popup-tippy',
				jet_popup()->plugin_url( 'assets/js/lib/tippy/tippy.all.min.js' ),
				array(),
				'2.5.4',
				true
			);

			wp_register_script(
				'jet-popup-tippy',
				jet_popup()->plugin_url( 'assets/js/lib/tippy/tippy.all.min.js' ),
				array(),
				'2.5.4',
				true
			);

			wp_register_style(
				'jet-iview',
				jet_popup()->plugin_url( 'assets/css/lib/iview/iview.css' ),
				[],
				'3.2.2'
			);

		}

		/**
		 * [enqueue_admin_popup_edit_assets description]
		 * @return [type] [description]
		 */
		public function enqueue_admin_popup_edit_assets() {
			$screen = get_current_screen();

			if ( $screen->id == 'edit-' . jet_popup()->post_type->slug() ) {
				wp_enqueue_style(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/css/jet-popup-admin.css' ),
					[],
					jet_popup()->get_version()
				);

				wp_enqueue_script(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/js/jet-popup-admin' . $this->suffix() . '.js' ),
					[ 'jquery', 'jet-popup-tippy' ],
					jet_popup()->get_version(),
					true
				);
			}
		}

		/**
		 * [enqueue_admin_library_assets description]
		 * @return [type] [description]
		 */
		public function enqueue_admin_library_assets() {
			$screen = get_current_screen();

			if ( $screen->id == jet_popup()->post_type->slug() . '_page_jet-popup-library' ) {

				wp_enqueue_style(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/css/jet-popup-admin.css' ),
					[ 'jet-iview' ],
					jet_popup()->get_version()
				);

				wp_enqueue_script(
					'jet-popup-admin',
					jet_popup()->plugin_url( 'assets/js/jet-popup-admin' . $this->suffix() . '.js' ),
					[
						'jquery',
						'jet-popup-vue',
						'jet-axios',
						'jet-iview',
						'jet-iview-locale-en-us'
					],
					jet_popup()->get_version(),
					true
				);

				$localize_data['version'] = jet_popup()->get_version();
				$localize_data['requiredPluginData'] = [
					'jet-elements' => [
						'badge' => jet_popup()->plugin_url( 'assets/image/jet-elements-badge.png' ),
						'link'  => 'http://jetelements.zemez.io/',
					],
					'jet-blocks'   => [
						'badge' => jet_popup()->plugin_url( 'assets/image/jet-blocks-badge.png' ),
						'link'  => 'http://jetblocks.zemez.io/',
					],
					'jet-tricks'   => [
						'badge' => jet_popup()->plugin_url( 'assets/image/jet-tricks-badge.png' ),
						'link'  => 'http://jettricks.zemez.io/',
					],
					'cf7'          => [
						'badge' => jet_popup()->plugin_url( 'assets/image/cf7-badge.png' ),
						'link'  => 'https://wordpress.org/plugins/contact-form-7/',
					],
				];

				$localize_data['libraryPresetsUrl'] = 'https://jetpopup.zemez.io/wp-json/croco/v1/presets';
				$localize_data['libraryPresetsCategoryUrl'] = 'https://jetpopup.zemez.io/wp-json/croco/v1/presets-categories';

				$localize_data = apply_filters( 'jet-popup/admin/localized-data', $localize_data );

				wp_localize_script(
					'jet-popup-admin',
					'jetPopupData',
					$localize_data
				);
			}
		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			$screen = get_current_screen();

			if ( 'jet-popup' !== $screen->post_type ) {
				return;
			}

			wp_enqueue_style(
				'jet-iview',
				jet_popup()->plugin_url( 'assets/css/lib/iview/iview.css' ),
				[],
				'3.2.2'
			);

			wp_enqueue_style(
				'jet-popup-editor',
				jet_popup()->plugin_url( 'assets/css/jet-popup-editor.css' ),
				array(),
				jet_popup()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			$screen = get_current_screen();

			if ( 'jet-popup' !== $screen->post_type ) {
				return;
			}

			wp_enqueue_script(
				'jet-popup-vue',
				'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',
				[],
				'2.5.17',
				true
			);

			wp_enqueue_script(
				'jet-axios',
				jet_popup()->plugin_url( 'assets/js/lib/axios/axios.min.js' ),
				[],
				'0.19.0-beta',
				true
			);

			wp_enqueue_script(
				'jet-iview',
				jet_popup()->plugin_url( 'assets/js/lib/iview/iview.min.js' ),
				[],
				'3.2.2',
				true
			);

			wp_enqueue_script(
				'jet-iview-locale-en-us',
				jet_popup()->plugin_url( 'assets/js/lib/iview/locale/en-US.js' ),
				[],
				'3.2.2',
				true
			);

			wp_enqueue_script(
				'jet-anime-js',
				jet_popup()->plugin_url( 'assets/js/lib/anime-js/anime.min.js' ),
				array( 'jquery' ),
				'2.0.2',
				true
			);

			wp_enqueue_script(
				'jet-popup-editor',
				jet_popup()->plugin_url( 'assets/js/jet-popup-editor' . $this->suffix() . '.js' ),
				array(
					'jquery',
					'underscore',
					'backbone-marionette',
				),
				jet_popup()->get_version(),
				true
			);

			$this->editor_localize_data = apply_filters( 'jet-popups/assets/editor_localize_data', [
				'version'          => jet_popup()->get_version(),
				'conditionManager' => jet_popup()->conditions->prepare_data_for_localize(),
			] );

			wp_localize_script( 'jet-popup-editor', 'jetPopupData', $this->editor_localize_data );
		}

		/**
		 * Load preview assets
		 *
		 * @return void
		 */
		public function preview_styles() {

			wp_enqueue_style(
				'jet-popup-preview',
				jet_popup()->plugin_url( 'assets/css/jet-popup-preview.css' ),
				array(),
				jet_popup()->get_version()
			);
		}

		/**
		 * [suffix description]
		 * @return [type] [description]
		 */
		public function suffix() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
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
