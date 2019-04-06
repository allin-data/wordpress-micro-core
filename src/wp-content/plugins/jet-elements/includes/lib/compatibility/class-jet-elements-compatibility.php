<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Compatibility' ) ) {

	/**
	 * Define Jet_Elements_Compatibility class
	 */
	class Jet_Elements_Compatibility {

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

			// Jetpack compatibility
			if ( class_exists( 'Jetpack_Photon' ) ) {
				add_filter( 'jetpack_photon_skip_image', array( $this, 'filter_jetpack_photon_skip_image' ), 10, 3 );
			}

			// WPML String Translation plugin exist check
			if ( defined( 'WPML_ST_VERSION' ) ) {

				if ( class_exists( 'WPML_Elementor_Module_With_Items' ) ) {
					$this->load_wpml_modules();
				}

				add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
			}
		}

		/**
		 * Instagram images to be skipped by Jetpack Photon.
		 */
		public function filter_jetpack_photon_skip_image( $val, $src, $tag ) {

			if ( ! is_array( $tag ) && strpos( $tag, 'jet-instagram-gallery__image' ) ) {
				return true;
			}

			return $val;
		}

		/**
		 * Load wpml required files.
		 *
		 * @return void
		 */
		public function load_wpml_modules() {
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-advanced-carousel.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-map.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-animated-text.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-brands.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-images-layout.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-pricing-table.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-slider.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-team-member.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-testimonials.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-image-comparison.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-scroll-navigation.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-portfolio.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-price-list.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-subscribe-form.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-timeline.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-table-header.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-table.php' );
			require jet_elements()->plugin_path( 'includes/lib/compatibility/modules/class-wpml-jet-elements-horizontal-timeline.php' );
		}

		/**
		 * Add jet elements translation nodes
		 *
		 * @param array $nodes_to_translate
		 *
		 * @return array
		 */
		public function add_translatable_nodes( $nodes_to_translate ) {

			$nodes_to_translate[ 'jet-animated-box' ] = array(
				'conditions' => array( 'widgetType' => 'jet-animated-box' ),
				'fields'     => array(
					array(
						'field'       => 'front_side_title',
						'type'        => esc_html__( 'Jet Animated Box: Front Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'front_side_subtitle',
						'type'        => esc_html__( 'Jet Animated Box: Front SubTitle', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'front_side_description',
						'type'        => esc_html__( 'Jet Animated Box: Front Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'back_side_title',
						'type'        => esc_html__( 'Jet Animated Box: Back Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'back_side_subtitle',
						'type'        => esc_html__( 'Jet Animated Box: Back SubTitle', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'back_side_description',
						'type'        => esc_html__( 'Jet Animated Box: Back Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'back_side_button_text',
						'type'        => esc_html__( 'Jet Animated Box: Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					'back_side_button_link' => array(
						'field'       => 'url',
						'type'        => esc_html__( 'Jet Animated Box: Button Link', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
			);

			$nodes_to_translate[ 'jet-banner' ] = array(
				'conditions' => array( 'widgetType' => 'jet-banner' ),
				'fields'     => array(
					array(
						'field'       => 'banner_title',
						'type'        => esc_html__( 'Jet Banner: Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'banner_text',
						'type'        => esc_html__( 'Jet Banner: Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'banner_link',
						'type'        => esc_html__( 'Jet Banner: Link', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
			);

			$nodes_to_translate[ 'jet-countdown-timer' ] = array(
				'conditions' => array( 'widgetType' => 'jet-countdown-timer' ),
				'fields'     => array(
					array(
						'field'       => 'label_days',
						'type'        => esc_html__( 'Jet Countdown Timer: Label Days', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'label_hours',
						'type'        => esc_html__( 'Jet Countdown Timer: Label Hours', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'label_min',
						'type'        => esc_html__( 'Jet Countdown Timer: Label Min', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'label_sec',
						'type'        => esc_html__( 'Jet Countdown Timer: Label Sec', 'jet-elements' ),
						'editor_type' => 'LINE',
					),

				),
			);

			$nodes_to_translate[ 'jet-download-button' ] = array(
				'conditions' => array( 'widgetType' => 'jet-download-button' ),
				'fields'     => array(
					array(
						'field'       => 'download_label',
						'type'        => esc_html__( 'Jet Download Button: Label', 'jet-elements' ),
						'editor_type' => 'LINE',
					),

				),
			);

			$nodes_to_translate[ 'jet-circle-progress' ] = array(
				'conditions' => array( 'widgetType' => 'jet-circle-progress' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => esc_html__( 'Jet Circle Progress: Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'subtitle',
						'type'        => esc_html__( 'Jet Circle Progress: Subtitle', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'jet-posts' ] = array(
				'conditions' => array( 'widgetType' => 'jet-posts' ),
				'fields'     => array(
					array(
						'field'       => 'more_text',
						'type'        => esc_html__( 'Jet Posts: Read More Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'jet-animated-text' ] = array(
				'conditions' => array( 'widgetType' => 'jet-animated-text' ),
				'fields'     => array(
					array(
						'field'       => 'before_text_content',
						'type'        => esc_html__( 'Jet Animated Text: Before Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'after_text_content',
						'type'        => esc_html__( 'Jet Animated Text: After Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Animated_Text',
			);

			$nodes_to_translate[ 'jet-carousel' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-carousel' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Advanced_Carousel',
			);

			$nodes_to_translate[ 'jet-map' ] = array(
				'conditions' => array( 'widgetType' => 'jet-map' ),
				'fields'     => array(
					array(
						'field'       => 'map_center',
						'type'        => esc_html__( 'Jet Map: Map Center', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Map',
			);

			$nodes_to_translate[ 'jet-brands' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-brands' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Brands',
			);

			$nodes_to_translate[ 'jet-images-layout' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-images-layout' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Images_Layout',
			);

			$nodes_to_translate[ 'jet-pricing-table' ] = array(
				'conditions' => array( 'widgetType' => 'jet-pricing-table' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => esc_html__( 'Jet Pricing Table: Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'subtitle',
						'type'        => esc_html__( 'Jet Pricing Table: Subtitle', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'price_prefix',
						'type'        => esc_html__( 'Jet Pricing Table: Price Prefix', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'price',
						'type'        => esc_html__( 'Jet Pricing Table: Price', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'price_suffix',
						'type'        => esc_html__( 'Jet Pricing Table: Price Suffix', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'price_desc',
						'type'        => esc_html__( 'Jet Pricing Table: Price Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'button_before',
						'type'        => esc_html__( 'Jet Pricing Table: Button Before', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_text',
						'type'        => esc_html__( 'Jet Pricing Table: Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_url',
						'type'        => esc_html__( 'Jet Pricing Table: Button URL', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
					array(
						'field'       => 'button_after',
						'type'        => esc_html__( 'Jet Pricing Table: Button After', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Pricing_Table',
			);

			$nodes_to_translate[ 'jet-slider' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-slider' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Slider',
			);

			$nodes_to_translate[ 'jet-services' ] = array(
				'conditions' => array( 'widgetType' => 'jet-services' ),
				'fields'     => array(
					array(
						'field'       => 'services_title',
						'type'        => esc_html__( 'Jet Services: Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'services_description',
						'type'        => esc_html__( 'Jet Services: Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'button_text',
						'type'        => esc_html__( 'Jet Services: Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					'button_url' => array(
						'field'       => 'url',
						'type'        => esc_html__( 'Jet Services: Button Link', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
			);

			$nodes_to_translate[ 'jet-team-member' ] = array(
				'conditions' => array( 'widgetType' => 'jet-team-member' ),
				'fields'     => array(
					array(
						'field'       => 'member_first_name',
						'type'        => esc_html__( 'Jet Team Member: First Name', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'member_last_name',
						'type'        => esc_html__( 'Jet Team Member: Last Name', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'member_position',
						'type'        => esc_html__( 'Jet Team Member: Position', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'member_description',
						'type'        => esc_html__( 'Jet Team Member: Description', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
					array(
						'field'       => 'button_text',
						'type'        => esc_html__( 'Jet Team Member: Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					'button_url' => array(
						'field'       => 'url',
						'type'        => esc_html__( 'Jet Team Member: Button URL', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Team_Member',
			);

			$nodes_to_translate[ 'jet-testimonials' ] = array(
				'conditions' => array( 'widgetType' => 'jet-testimonials' ),
				'fields'     => array(),
				'integration-class' => 'WPML_Jet_Elements_Testimonials',
			);

			$nodes_to_translate[ 'jet-button' ] = array(
				'conditions' => array( 'widgetType' => 'jet-button' ),
				'fields'     => array(
					array(
						'field'       => 'button_label_normal',
						'type'        => esc_html__( 'Jet Button: Normal Label', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'button_label_hover',
						'type'        => esc_html__( 'Jet Button: Hover Label', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					'button_url' => array(
						'field'       => 'url',
						'type'        => esc_html__( 'Jet Button: Link', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
			);

			$nodes_to_translate[ 'jet-image-comparison' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-image-comparison' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Image_Comparison',
			);

			$nodes_to_translate[ 'jet-headline' ] = array(
				'conditions' => array( 'widgetType' => 'jet-headline' ),
				'fields'     => array(
					array(
						'field'       => 'first_part',
						'type'        => esc_html__( 'Jet Headline: First Part', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'second_part',
						'type'        => esc_html__( 'Jet Headline: Second Part', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					'link' => array(
						'field'       => 'url',
						'type'        => esc_html__( 'Jet Headline: Link', 'jet-elements' ),
						'editor_type' => 'LINK',
					),
				),
			);

			$nodes_to_translate[ 'jet-scroll-navigation' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-scroll-navigation' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Scroll_Navigation',
			);

			$nodes_to_translate[ 'jet-subscribe-form' ] = array(
				'conditions' => array( 'widgetType' => 'jet-subscribe-form' ),
				'fields'     => array(
					array(
						'field'       => 'submit_button_text',
						'type'        => esc_html__( 'Jet Subscribe Form: Submit Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'submit_placeholder',
						'type'        => esc_html__( 'Jet Subscribe Form: Input Placeholder', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Subscribe_Form',
			);

			$nodes_to_translate[ 'jet-dropbar' ] = array(
				'conditions' => array( 'widgetType' => 'jet-dropbar' ),
				'fields'     => array(
					array(
						'field'       => 'button_text',
						'type'        => esc_html__( 'Jet Dropbar: Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'simple_content',
						'type'        => esc_html__( 'Jet Dropbar: Simple Text', 'jet-elements' ),
						'editor_type' => 'AREA',
					),
				),
			);

			$nodes_to_translate[ 'jet-portfolio' ] = array(
				'conditions' => array( 'widgetType' => 'jet-portfolio' ),
				'fields'     => array(
					array(
						'field'       => 'all_filter_label',
						'type'        => esc_html__( 'Jet Portfolio: `All` Filter Label', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'view_more_button_text',
						'type'        => esc_html__( 'Jet Portfolio: View More Button Text', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
				'integration-class' => 'WPML_Jet_Elements_Portfolio',
			);

			$nodes_to_translate[ 'jet-price-list' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-price-list' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Price_List',
			);

			$nodes_to_translate[ 'jet-progress-bar' ] = array(
				'conditions' => array( 'widgetType' => 'jet-progress-bar' ),
				'fields'     => array(
					array(
						'field'       => 'title',
						'type'        => esc_html__( 'Jet Progress Bar: Title', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'jet-timeline' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-timeline' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Timeline',
			);

			$nodes_to_translate[ 'jet-weather' ] = array(
				'conditions' => array( 'widgetType' => 'jet-weather' ),
				'fields'     => array(
					array(
						'field'       => 'location',
						'type'        => esc_html__( 'Jet Weather: Location', 'jet-elements' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate[ 'jet-table' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-table' ),
				'fields'            => array(),
				'integration-class' => array(
					'WPML_Jet_Elements_Table_Header',
					'WPML_Jet_Elements_Table',
				),
			);

			$nodes_to_translate[ 'jet-horizontal-timeline' ] = array(
				'conditions'        => array( 'widgetType' => 'jet-horizontal-timeline' ),
				'fields'            => array(),
				'integration-class' => 'WPML_Jet_Elements_Horizontal_Timeline',
			);

			return $nodes_to_translate;
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
 * Returns instance of Jet_Elements_Compatibility
 *
 * @return object
 */
function jet_elements_compatibility() {
	return Jet_Elements_Compatibility::get_instance();
}
