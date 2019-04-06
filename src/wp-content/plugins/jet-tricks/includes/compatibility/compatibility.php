<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tricks_Compatibility' ) ) {

	/**
	 * Define Jet_Tricks_Compatibility class
	 */
	class Jet_Tricks_Compatibility {

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
		public function init() {

			// WPML String Translation plugin exist check
			if ( defined( 'WPML_ST_VERSION' ) ) {
				$this->load_wpml_modules();
				add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
			}
		}

		/**
		 * Load required files.
		 *
		 * @return void
		 */
		public function load_wpml_modules() {
			require jet_tricks()->plugin_path( 'includes/compatibility/wpml/modules/class-wpml-jet-tricks-hotspots.php' );
		}

		/**
		 * Add jet-tricks translation nodes
		 *
		 * @param array $nodes_to_translate
		 *
		 * @return mixed
		 */
		public function add_translatable_nodes( $nodes_to_translate ) {

			$nodes_to_translate['jet-hotspots'] = array(
				'conditions'        => array( 'widgetType' => 'jet-hotspots' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Tricks_Hotspots',
			);

			$nodes_to_translate['jet-unfold'] = array(
				'conditions' => array( 'widgetType' => 'jet-unfold' ),
				'fields'     => array(
					array(
						'field'       => 'editor',
						'type'        => esc_html__( 'Jet Unfold: Content', 'jet-tricks' ),
						'editor_type' => 'VISUAL',
					),
					array(
						'field'       => 'button_fold_text',
						'type'        => esc_html__( 'Jet Unfold: Fold Text', 'jet-tricks' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_unfold_text',
						'type'        => esc_html__( 'Jet Unfold: Unfold Text', 'jet-tricks' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-view-more'] = array(
				'conditions' => array( 'widgetType' => 'jet-view-more' ),
				'fields'     => array(
					array(
						'field'       => 'button_label',
						'type'        => esc_html__( 'Jet View More: Button Label', 'jet-tricks' ),
						'editor_type' => 'LINE',
					),
				),
			);

			/**
			 * @var Elementor\Widgets_Manager $widgets_manager
			 */
			$widgets_manager = jet_tricks()->elementor()->widgets_manager;
			$widgets = $widgets_manager->get_widget_types();

			foreach ( $widgets as $widget ) {
				$widget_name = $widget->get_name();

				if ( 'common' === $widget_name ) {
					continue;
				}

				if ( isset( $nodes_to_translate[ $widget_name ] ) ) {

					if ( isset( $nodes_to_translate[ $widget_name ]['fields'] ) && is_array( $nodes_to_translate[ $widget_name ]['fields'] ) ) {
						$nodes_to_translate[ $widget_name ]['fields'] = array_merge(
							$nodes_to_translate[ $widget_name ]['fields'],
							$this->get_advanced_translate_fields( $widget->get_title() )
						);
					} else {
						$nodes_to_translate[ $widget_name ]['fields'] = $this->get_advanced_translate_fields( $widget->get_title() );
					}

				} else {
					$nodes_to_translate[ $widget_name ] = array(
						'conditions' => array( 'widgetType' => $widget_name ),
						'fields'     => $this->get_advanced_translate_fields( $widget->get_title() ),
					);
				}
			}

			return $nodes_to_translate;
		}

		/**
		 * Get advanced translate fields.
		 *
		 * @param string $widget_title Widget title
		 *
		 * @return array
		 */
		public function get_advanced_translate_fields( $widget_title ) {
			return array(
				array(
					'field'       => 'jet_tricks_widget_tooltip_description',
					'type'        =>  $widget_title . ': ' . esc_html__( 'JetTricks - Tooltip Description', 'jet-tricks' ),
					'editor_type' => 'AREA',
				),
				array(
					'field'       => 'jet_tricks_widget_satellite_text',
					'type'        => $widget_title . ': ' .esc_html__( 'JetTricks - Satellite Text', 'jet-tricks' ),
					'editor_type' => 'LINE',
				),
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
 * Returns instance of Jet_Tricks_Compatibility
 *
 * @return object
 */
function jet_tricks_compatibility() {
	return Jet_Tricks_Compatibility::get_instance();
}
