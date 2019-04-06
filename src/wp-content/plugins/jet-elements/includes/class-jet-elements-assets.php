<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Assets' ) ) {

	/**
	 * Define Jet_Elements_Assets class
	 */
	class Jet_Elements_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Localize data array
		 *
		 * @var array
		 */
		public $localize_data = array();

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );

			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			$this->localize_data['ajaxUrl'] = admin_url( 'admin-ajax.php' );
			// Frontend messages
			$this->localize_data['messages'] = array(
				'invalidMail' => esc_html__( 'Please specify a valid e-mail', 'jet-elements' ),
			);
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
				'jet-elements',
				jet_elements()->plugin_url( 'assets/css/jet-elements.css' ),
				false,
				jet_elements()->get_version()
			);

			if ( is_rtl() ) {
				wp_enqueue_style(
					'jet-elements-rtl',
					jet_elements()->plugin_url( 'assets/css/jet-elements-rtl.css' ),
					false,
					jet_elements()->get_version()
				);
			}

			$default_theme_enabled = apply_filters( 'jet-elements/assets/css/default-theme-enabled', true );

			if ( $default_theme_enabled ) {
				wp_enqueue_style(
					'jet-elements-skin',
					jet_elements()->plugin_url( 'assets/css/jet-elements-skin.css' ),
					false,
					jet_elements()->get_version()
				);
			}

			// Register vendor slider-pro.css styles (https://github.com/bqworks/slider-pro)
			wp_register_style(
				'jet-slider-pro-css',
				jet_elements()->plugin_url( 'assets/css/lib/slider-pro/slider-pro.min.css' ),
				false,
				'1.3.0'
			);

			// Register vendor juxtapose-css styles
			wp_register_style(
				'jet-juxtapose-css',
				jet_elements()->plugin_url( 'assets/css/lib/juxtapose/juxtapose.css' ),
				false,
				'1.3.0'
			);
		}

		/**
		 * Enqueue admin styles
		 *
		 * @return void
		 */
		public function admin_enqueue_styles() {
			$screen = get_current_screen();

			// Jet setting page check
			if ( 'elementor_page_jet-elements-settings' === $screen->base ) {
				wp_enqueue_style(
					'jet-elements-admin-css',
					jet_elements()->plugin_url( 'assets/css/jet-elements-admin.css' ),
					false,
					jet_elements()->get_version()
				);
			}
		}

		/**
		 * Enqueue preview styles.
		 *
		 * @return void
		 */
		public function enqueue_preview_styles() {
			$avaliable_widgets = jet_elements_settings()->get( 'avaliable_widgets' );

			$styles_map = array(
				'jet-elements-video'            => array( 'mediaelement' ),
				'jet-elements-audio'            => array( 'mediaelement' ),
				'jet-elements-slider'           => array( 'jet-slider-pro-css' ),
				'jet-elements-image-comparison' => array( 'jet-juxtapose-css' ),
			);

			foreach ( $styles_map as $widget => $styles_list ) {
				$enabled = isset( $avaliable_widgets[ $widget ] ) ? $avaliable_widgets[ $widget ] : '';

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {

					foreach ( $styles_list as $style ) {
						wp_enqueue_style( $style );
					}
				}
			}
		}

		/**
		 * Register plugin scripts
		 *
		 * @return void
		 */
		public function register_scripts() {

			$api_disabled = jet_elements_settings()->get( 'disable_api_js', array() );
			$key          = jet_elements_settings()->get( 'api_key' );

			if ( ! empty( $key ) && ( empty( $api_disabled ) || 'true' !== $api_disabled['disable'] ) ) {

				wp_register_script(
					'google-maps-api',
					add_query_arg(
						array( 'key' => jet_elements_settings()->get( 'api_key' ), ),
						'https://maps.googleapis.com/maps/api/js'
					),
					false,
					false,
					true
				);
			}

			// Register vendor anime.js script (https://github.com/juliangarnier/anime)
			wp_register_script(
				'jet-anime-js',
				jet_elements()->plugin_url( 'assets/js/lib/anime-js/anime.min.js' ),
				array(),
				'2.2.0',
				true
			);

			wp_register_script(
				'jet-tween-js',
				jet_elements()->plugin_url( 'assets/js/lib/tweenjs/tweenjs.min.js' ),
				array(),
				'2.0.2',
				true
			);


			// Register vendor salvattore.js script (https://github.com/rnmp/salvattore)
			wp_register_script(
				'jet-salvattore',
				jet_elements()->plugin_url( 'assets/js/lib/salvattore/salvattore.min.js' ),
				array(),
				'1.0.9',
				true
			);

			// Register vendor masonry.pkgd.min.js script
			wp_register_script(
				'jet-masonry-js',
				jet_elements()->plugin_url( 'assets/js/lib/masonry-js/masonry.pkgd.min.js' ),
				array(),
				'4.2.1',
				true
			);

			// Register vendor slider-pro.js script (https://github.com/bqworks/slider-pro)
			wp_register_script(
				'jet-slider-pro',
				jet_elements()->plugin_url( 'assets/js/lib/slider-pro/jquery.sliderPro.min.js' ),
				array(),
				'1.3.0',
				true
			);

			// Register vendor juxtapose.js script
			wp_register_script(
				'jet-juxtapose',
				jet_elements()->plugin_url( 'assets/js/lib/juxtapose/juxtapose.min.js' ),
				array(),
				'1.3.0',
				true
			);

			// Register vendor tablesorter.js script (https://github.com/Mottie/tablesorter)
			wp_register_script(
				'jquery-tablesorter',
				jet_elements()->plugin_url( 'assets/js/lib/tablesorter/jquery.tablesorter.min.js' ),
				array( 'jquery' ),
				'2.30.7',
				true
			);

			// Register vendor chart.js script (http://www.chartjs.org)
			wp_register_script(
				'chart-js',
				jet_elements()->plugin_url( 'assets/js/lib/chart-js/chart.min.js' ),
				array(),
				'2.7.3',
				true
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			wp_enqueue_script(
				'jet-elements',
				jet_elements()->plugin_url( 'assets/js/jet-elements.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_elements()->get_version(),
				true
			);

			wp_localize_script(
				'jet-elements',
				'jetElements',
				apply_filters( 'jet-elements/frontend/localize-data', $this->localize_data )
			);
		}

		/**
		 * Enqueue editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-elements-font',
				jet_elements()->plugin_url( 'assets/css/lib/jetelements-font/css/jetelements.css' ),
				array(),
				jet_elements()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {
			wp_enqueue_script(
				'jet-elements-editor',
				jet_elements()->plugin_url( 'assets/js/jet-elements-editor.js' ),
				array( 'jquery' ),
				jet_elements()->get_version(),
				true
			);
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
 * Returns instance of Jet_Elements_Assets
 *
 * @return object
 */
function jet_elements_assets() {
	return Jet_Elements_Assets::get_instance();
}
