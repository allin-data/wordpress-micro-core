<?php
/**
 * Cherry PopUps Data
 *
 * @package   Cherry_Popups
 * @author    Cherry Team
 * @license   GPL-2.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2014 Cherry Team
 */

/**
 * Class for Popups data.
 *
 * @since 1.0.0
 */
class Cherry_Popups_Data {

	/**
	 * Default options array
	 *
	 * @var array
	 */
	public $default_options = array();

	/**
	 * Current options array
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Post query object.
	 *
	 * @var null
	 */
	private $posts_query = null;

	/**
	 * Current popup meta data
	 *
	 * @var null
	 */
	public $popup_settings = null;

	/**
	 * Sets up our actions/filters.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $options = array() ) {
		$this->default_options = array(
			'id'       => null,
			'use'      => false,
			'template' => 'default-theme-popup.tmpl',
			'echo'     => true,
		);

		/**
		 * Filter the array of default options.
		 *
		 * @since 1.0.0
		 * @param array options.
		 */
		$this->default_options = apply_filters( 'cherry_popups_default_options', $this->default_options );

		$this->options = wp_parse_args( $options, $this->default_options );

		$auto_height = $this->get_popup_meta_field( 'cherry-popup-auto-height', 'auto' );

		$this->popup_settings = array(
			'id'                   => $this->options['id'],
			'use'                  => $this->options['use'],
			'layout-type'          => $this->get_popup_meta_field( 'cherry-layout-type', 'center' ),
			'show-hide-animation'  => $this->get_popup_meta_field( 'cherry-show-hide-animation', 'simple-fade' ),
			'base-style'           => $this->get_popup_meta_field( 'cherry-popup-base-style', 'light' ),
			'width'                => $this->get_popup_meta_field( 'cherry-popup-width', 600 ),
			'height'               => $this->get_popup_meta_field( 'cherry-popup-height', 400 ),
			'padding'              => $this->get_popup_meta_field( 'cherry-popup-padding', 0 ),
			'container-bg-type'    => $this->get_popup_meta_field( 'cherry-container-bg-type', 'fill-color' ),
			'container-color'      => $this->get_popup_meta_field( 'cherry-container-color', '#fff' ),
			'container-opacity'    => $this->get_popup_meta_field( 'cherry-container-opacity', 100 ),
			'container-image'      => $this->get_popup_meta_field( 'cherry-container-image', '' ),
			'border-radius'        => $this->get_popup_meta_field( 'cherry-border-radius', 3 ),
			'content-position'     => $this->get_popup_meta_field( 'cherry-content-position', 'center' ),
			'show-once'            => $this->get_popup_meta_field( 'cherry-show-once', 'false' ),
			'overlay-type'         => $this->get_popup_meta_field( 'cherry-overlay-type', 'fill-color' ),
			'overlay-color'        => $this->get_popup_meta_field( 'cherry-overlay-color', '#000' ),
			'overlay-opacity'      => $this->get_popup_meta_field( 'cherry-overlay-opacity', 50 ),
			'overlay-image'        => $this->get_popup_meta_field( 'cherry-overlay-image', '' ),
			'overlay-close-area'   => $this->get_popup_meta_field( 'cherry-overlay-close-area', 'true' ),
			'open-appear-event'    => $this->get_popup_meta_field( 'cherry-popup-open-appear-event', 'page-load' ),
			'load-open-delay'      => $this->get_popup_meta_field( 'cherry-page-load-open-delay', 1 ),
			'inactive-time'        => $this->get_popup_meta_field( 'cherry-user-inactive-time', 1 ),
			'page-scrolling-value' => $this->get_popup_meta_field( 'cherry-page-scrolling-value', 5 ),
			'close-appear-event'   => $this->get_popup_meta_field( 'cherry-popup-close-appear-event', 'outside-viewport' ),
			'custom-event-type'    => $this->get_popup_meta_field( 'cherry-custom-event-type', 'click' ),
			'popup-selector'       => $this->get_popup_meta_field( 'cherry-popup-selector', '' ),
			'template'             => $this->get_popup_meta_field( 'cherry-popup-template', 'default-popup.tmpl' ),
			'popup-type'           => $this->get_popup_meta_field( 'cherry-popup-type', 'default' ),
			'custom-class'         => $this->get_popup_meta_field( 'cherry-custom-class', '' ),
		);

		$this->generate_dynamic_styles();
	}

	/**
	 * Render PopUp
	 *
	 * @return string html string
	 */
	public function render_popup() {
		$this->enqueue_styles();
		$this->enqueue_scripts();

		// Item template.
		if ( isset( $this->popup_settings['popup-type'] ) ) {
			switch ( $this->popup_settings['popup-type'] ) {
				case 'default':
					$template_name = 'default-popup.tmpl';
					break;

				case 'simple':
					$template_name = 'default-simple-popup.tmpl';
					break;

				case 'content':
					$template_name = 'default-content-only-popup.tmpl';
					break;

				case 'login':
					$template_name = 'default-login-popup.tmpl';
					break;

				case 'signup':
					$template_name = 'default-signup-popup.tmpl';
					break;

				case 'subscribe':
					$template_name = 'default-subscribe-popup.tmpl';
					break;
			}
		} else {
			$template_name = $this->popup_settings['template'];
		}


		$template = $this->get_template_by_name( $template_name, 'cherry-popups' );

		$macros = '/%%.+?%%/';
		$callbacks = $this->setup_template_data( $this->options );
		$callbacks->popup_id = $this->options['id'];

		$container_class = sprintf(
			'cherry-popup cherry-popup-wrapper cherry-popup-%1$s %2$s-animation overlay-%3$s-type layout-type-%4$s %5$s-style %6$s-type %7$s popup-type-%8$s',
			$this->options['id'],
			$this->popup_settings['show-hide-animation'],
			$this->popup_settings['overlay-type'],
			$this->popup_settings['layout-type'],
			$this->popup_settings['base-style'],
			$this->popup_settings['use'],
			$this->popup_settings['custom-class'],
			$this->popup_settings['popup-type']
		);

		$popup_settings_encode = json_encode( $this->popup_settings );

		$html = sprintf( '<div class="%1$s" data-popup-settings=\'%2$s\'>', $container_class, $popup_settings_encode );
			$html .= '<div class="cherry-popup-overlay"></div>';
			$html .= sprintf( '<div class="%1$s container-%2$s-type">', 'cherry-popup-container', $this->popup_settings['container-bg-type'] );
				$html .= '<div class="cherry-popup-container__inner">';
					$html .= '<div class="cherry-popup-container__dynamic">';
						$template_content = preg_replace_callback( $macros, array( $this, 'replace_callback' ), $template );
						$html .= $template_content;
					$html .= '</div>';
				$html .= '</div>';

				if ( ! filter_var( $this->popup_settings['show-once'], FILTER_VALIDATE_BOOLEAN ) && empty( $this->popup_settings['popup-selector'] ) ) {
					$html .= sprintf( '<div class="cherry-popup-check cherry-popup-show-again-check"><div class="marker"><span class="dashicons dashicons-yes"></span></div><span class="label">%1$s</span></div>', esc_html__( 'Don\'t show again' , 'cherry-popups' ) );
				}

				$html .= '<div class="cherry-popup-close-button"><span class="dashicons dashicons-no"></span></div>';
			$html .= '</div>';
		// Close wrapper.
		$html .= '</div>';

		if ( ! filter_var( $this->options['echo'], FILTER_VALIDATE_BOOLEAN ) ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Generate dynamic CSS styles for popup instance.
	 *
	 * @return void
	 */
	public function generate_dynamic_styles() {

		$container_styles = array();

		// Container styles
		switch ( $this->popup_settings['layout-type'] ) {
			case 'center-fullwidth':
			case 'bottom':
				$container_styles['width'] = '100%';
				break;
			case 'center':
				$container_styles['width'] = $this->popup_settings['width'] . 'px';
				break;
		}

		$container_styles['height'] = $this->popup_settings['height'] . 'px';

		switch ( $this->popup_settings['container-bg-type'] ) {
			case 'fill-color':
				$rgb_array = $this->hex_to_rgb( $this->popup_settings['container-color'] );
				$opacity = intval( $this->popup_settings['container-opacity'] ) / 100;
				$container_styles['background-color'] = sprintf( 'rgba(%1$s,%2$s,%3$s,%4$s);', $rgb_array[0], $rgb_array[1], $rgb_array[2], $opacity );
			break;
			case 'image':
				$image_data = wp_get_attachment_image_src( $this->popup_settings['container-image'], 'full' );
				$container_styles['background-image'] = sprintf( 'url(%1$s);', $image_data[0] );
			break;
		}

		$container_styles['border-radius'] = $this->popup_settings['border-radius'] . 'px';

		cherry_popups()->dynamic_css->add_style(
			sprintf( '.cherry-popup-%1$s .cherry-popup-container', $this->options['id'] ),
			$container_styles
		);

		cherry_popups()->dynamic_css->add_style(
			sprintf( '.cherry-popup-%1$s .cherry-popup-container__inner', $this->options['id'] ),
			array(
				'padding' => $this->popup_settings['padding'] . 'px',
			)
		);

		cherry_popups()->dynamic_css->add_style(
			sprintf( '.cherry-popup-%1$s .cherry-popup-container__inner', $this->options['id'] ),
			array(
				'justify-content' => $this->popup_settings['content-position'],
			)
		);

		// Overlay styles
		switch ( $this->popup_settings['overlay-type'] ) {
			case 'fill-color':
				$rgb_array = $this->hex_to_rgb( $this->popup_settings['overlay-color'] );
				$opacity = intval( $this->popup_settings['overlay-opacity'] ) / 100;

				cherry_popups()->dynamic_css->add_style(
					sprintf( '.cherry-popup-%1$s .cherry-popup-overlay', $this->options['id'] ),
					array(
						'background-color' => sprintf( 'rgba(%1$s,%2$s,%3$s,%4$s);', $rgb_array[0], $rgb_array[1], $rgb_array[2], $opacity ),
					)
				);
				break;
			case 'image':
				if ( ! empty( $this->popup_settings['overlay-image'] ) ) {
					$image_data = wp_get_attachment_image_src( $this->popup_settings['overlay-image'], 'full' );
				}

				cherry_popups()->dynamic_css->add_style(
					sprintf( '.cherry-popup-%1$s .cherry-popup-overlay', $this->options['id'] ),
					array(
						'background-image' => sprintf( 'url(%1$s);', $image_data[0] ),
					)
				);
				break;
		}
	}

	/**
	 * Get meta field data.
	 *
	 * @param  string  $name    Field name.
	 * @param  boolean $default Default value.
	 * @return mixed
	 */
	private function get_popup_meta_field( $name = '', $default = false ) {

		$data = get_post_meta( $this->options['id'], $name, true );

		if ( empty( $data ) ) {
			return $default;
		}

		return $data;
	}

	/**
	 * Prepare template data to replace.
	 *
	 * @since 1.0.0
	 * @param array $atts Output attributes.
	 */
	function setup_template_data( $atts ) {
		require_once( CHERRY_POPUPS_DIR . 'includes/public/class-popups-template-callbacks.php' );

		$callbacks = new Cherry_Popups_Template_Callbacks( $atts );

		$data = array(
			'title'         => array( $callbacks, 'get_title' ),
			'content'       => array( $callbacks, 'get_content' ),
			'subscribeform' => array( $callbacks, 'get_subscribe_form' ),
			'loginform'     => array( $callbacks, 'get_login_form' ),
			'registerform'  => array( $callbacks, 'get_register_form' ),
			'closelabel'    => array( $callbacks, 'get_close_label' ),
		);

		/**
		 * Filters item data.
		 *
		 * @since 1.0.0
		 * @param array $data Item data.
		 * @param array $atts Attributes.
		 */
		$this->post_data = apply_filters( 'cherry_popups_data_callbacks', $data, $atts );

		return $callbacks;
	}

	/**
	 * Retrieve a *.tmpl file content.
	 *
	 * @since  1.0.0
	 * @param  string $template  File name.
	 * @param  string $shortcode Shortcode name.
	 * @return string
	 */
	public function get_template_by_name( $template, $shortcode ) {
		$file       = '';
		$default    = CHERRY_POPUPS_DIR . 'templates/shortcodes/' . $shortcode . '/default-popup.tmpl';
		$upload_dir = wp_upload_dir();
		$upload_dir = trailingslashit( $upload_dir['basedir'] );
		$subdir     = 'templates/shortcodes/' . $shortcode . '/' . $template;

		/**
		 * Filters a default fallback-template.
		 *
		 * @since 1.0.0
		 * @param string $content.
		 */
		$content = apply_filters( 'cherry_popups_fallback_template', '%%TITLE%%%%CONTENT%%%%SUBSCRIBEFORM%%' );

		if ( file_exists( $upload_dir . $subdir ) ) {
			$file = $upload_dir . $subdir;
		} elseif ( $theme_template = locate_template( array( 'cherry-popups/' . $template ) ) ) {
			$file = $theme_template;
		} elseif ( file_exists( CHERRY_POPUPS_DIR . $subdir ) ) {
			$file = CHERRY_POPUPS_DIR . $subdir;
		} else {
			$file = $default;
		}

		$file = wp_normalize_path( $file );

		if ( ! empty( $file ) ) {
			$content = self::get_contents( $file );
		}

		return $content;
	}

	/**
	 * Read template (static).
	 *
	 * @since  1.0.0
	 * @return bool|WP_Error|string - false on failure, stored text on success.
	 */
	public static function get_contents( $template ) {

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once( ABSPATH . '/wp-admin/includes/file.php' );
		}

		WP_Filesystem();
		global $wp_filesystem;

		// Check for existence.
		if ( ! $wp_filesystem->exists( $template ) ) {
			return false;
		}

		// Read the file.
		$content = $wp_filesystem->get_contents( $template );

		if ( ! $content ) {
			// Return error object.
			return new WP_Error( 'reading_error', 'Error when reading file' );
		}

		return $content;
	}

	/**
	 * Callback to replace macros with data.
	 *
	 * @since 1.0.0
	 * @param array $matches Founded macros.
	 */
	public function replace_callback( $matches ) {

		if ( ! is_array( $matches ) ) {
			return;
		}

		if ( empty( $matches ) ) {
			return;
		}

		$item   = trim( $matches[0], '%%' );
		$arr    = explode( ' ', $item, 2 );
		$macros = strtolower( $arr[0] );
		$attr   = isset( $arr[1] ) ? shortcode_parse_atts( $arr[1] ) : array();

		$callback = $this->post_data[ $macros ];

		if ( ! is_callable( $callback ) || ! isset( $this->post_data[ $macros ] ) ) {
			return;
		}

		if ( ! empty( $attr ) ) {

			// Call a WordPress function.
			return call_user_func( $callback, $attr );
		}

		return call_user_func( $callback );
	}

	/**
	 * Hex to rgb converter.
	 *
	 * @param  string $hex Hex color.
	 * @return array
	 */
	public function hex_to_rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( 3 == strlen( $hex ) ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb;
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'cherry-popups-styles' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'cherry-popups-scripts' );
	}

}
