<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Jet_Blog_Base extends Widget_Base {

	public $__context         = 'render';
	public $__processed_item  = false;
	public $__processed_index = 0;
	public $__query           = array();

	/**
	 * Get globaly affected template
	 *
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_global_template( $name = null ) {

		$template = call_user_func( array( $this, sprintf( '__get_%s_template', $this->__context ) ), $name );

		if ( ! $template ) {
			$template = jet_blog()->get_template( $this->get_name() . '/global/' . $name . '.php' );
		}

		return $template;
	}

	/**
	 * Get front-end template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_render_template( $name = null ) {
		return jet_blog()->get_template( $this->get_name() . '/render/' . $name . '.php' );
	}

	/**
	 * Get editor template
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get_edit_template( $name = null ) {
		return jet_blog()->get_template( $this->get_name() . '/edit/' . $name . '.php' );
	}

	/**
	 * Get global looped template for settings
	 * Required only to process repeater settings.
	 *
	 * @param  string $name    Base template name.
	 * @param  string $setting Repeater setting that provide data for template.
	 * @return void
	 */
	public function __get_global_looped_template( $name = null, $setting = null ) {

		$templates = array(
			'start' => $this->__get_global_template( $name . '-loop-start' ),
			'loop'  => $this->__get_global_template( $name . '-loop-item' ),
			'end'   => $this->__get_global_template( $name . '-loop-end' ),
		);

		call_user_func(
			array( $this, sprintf( '__get_%s_looped_template', $this->__context ) ), $templates, $setting
		);

	}

	/**
	 * Get render mode looped template
	 *
	 * @param  array  $templates [description]
	 * @param  [type] $setting   [description]
	 * @return [type]            [description]
	 */
	public function __get_render_looped_template( $templates = array(), $setting = null ) {

		$loop = $this->get_settings( $setting );

		if ( empty( $loop ) ) {
			return;
		}

		if ( ! empty( $templates['start'] ) ) {
			include $templates['start'];
		}

		foreach ( $loop as $item ) {

			$this->__processed_item = $item;
			if ( ! empty( $templates['start'] ) ) {
				include $templates['loop'];
			}
			$this->__processed_index++;
		}

		$this->__processed_item = false;
		$this->__processed_index = 0;

		if ( ! empty( $templates['end'] ) ) {
			include $templates['end'];
		}

	}

	/**
	 * Get edit mode looped template
	 *
	 * @param  array  $templates [description]
	 * @param  [type] $setting   [description]
	 * @return [type]            [description]
	 */
	public function __get_edit_looped_template( $templates = array(), $setting = null ) {
		?>
		<# if ( settings.<?php echo $setting; ?> ) { #>
		<?php
			if ( ! empty( $templates['start'] ) ) {
				include $templates['start'];
			}
		?>
			<# _.each( settings.<?php echo $setting; ?>, function( item ) { #>
			<?php
				if ( ! empty( $templates['loop'] ) ) {
					include $templates['loop'];
				}
			?>
			<# } ); #>
		<?php
			if ( ! empty( $templates['end'] ) ) {
				include $templates['end'];
			}
		?>
		<# } #>
		<?php
	}

	/**
	 * Get current looped item dependends from context.
	 *
	 * @param  string $key Key to get from processed item
	 * @return mixed
	 */
	public function __loop_item( $keys = array(), $format = '%s' ) {

		return call_user_func( array( $this, sprintf( '__%s_loop_item', $this->__context ) ), $keys, $format );

	}

	/**
	 * Loop edit item
	 *
	 * @param  [type]  $keys       [description]
	 * @param  string  $format     [description]
	 * @param  boolean $nested_key [description]
	 * @return [type]              [description]
	 */
	public function __edit_loop_item( $keys = array(), $format = '%s' ) {

		$settings = $keys[0];

		if ( isset( $keys[1] ) ) {
			$settings .= '.' . $keys[1];
		}

		ob_start();

		echo '<# if ( item.' . $settings . ' ) { #>';
		printf( $format, '{{{ item.' . $settings . ' }}}' );
		echo '<# } #>';

		return ob_get_clean();
	}

	/**
	 * Loop render item
	 *
	 * @param  string  $format     [description]
	 * @param  [type]  $key        [description]
	 * @param  boolean $nested_key [description]
	 * @return [type]              [description]
	 */
	public function __render_loop_item( $keys = array(), $format = '%s' ) {

		$item = $this->__processed_item;

		$key        = $keys[0];
		$nested_key = isset( $keys[1] ) ? $keys[1] : false;

		if ( empty( $item ) || ! isset( $item[ $key ] ) ) {
			return false;
		}

		if ( false === $nested_key || ! is_array( $item[ $key ] ) ) {
			$value = $item[ $key ];
		} else {
			$value = isset( $item[ $key ][ $nested_key ] ) ? $item[ $key ][ $nested_key ] : false;
		}

		if ( ! empty( $value ) ) {
			return sprintf( $format, $value );
		}

	}

	/**
	 * Include global template if any of passed settings is defined
	 *
	 * @param  [type] $name     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __glob_inc_if( $name = null, $settings = array() ) {

		$template = $this->__get_global_template( $name );

		call_user_func( array( $this, sprintf( '__%s_inc_if', $this->__context ) ), $template, $settings );

	}

	/**
	 * Include render template if any of passed setting is not empty
	 *
	 * @param  [type] $file     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __render_inc_if( $file = null, $settings = array() ) {

		foreach ( $settings as $setting ) {
			$val = $this->get_settings( $setting );

			if ( ! empty( $val ) ) {
				include $file;
				return;
			}

		}

	}

	/**
	 * Include render template if any of passed setting is not empty
	 *
	 * @param  [type] $file     [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function __edit_inc_if( $file = null, $settings = array() ) {

		$condition = null;
		$sep       = null;

		foreach ( $settings as $setting ) {
			$condition .= $sep . 'settings.' . $setting;
			$sep = ' || ';
		}

		?>

		<# if ( <?php echo $condition; ?> ) { #>

			<?php include $file; ?>

		<# } #>

		<?php
	}

	/**
	 * Open standard wrapper
	 *
	 * @return void
	 */
	public function __open_wrap() {
		printf( '<div class="elementor-%s jet-blog">', $this->get_name() );
	}

	/**
	 * Close standard wrapper
	 *
	 * @return void
	 */
	public function __close_wrap() {
		echo '</div>';
	}

	/**
	 * Print HTML markup if passed setting not empty.
	 *
	 * @param  string $setting Passed setting.
	 * @param  string $format  Required markup.
	 * @param  array  $args    Additional variables to pass into format string.
	 * @param  bool   $echo    Echo or return.
	 * @return string|void
	 */
	public function __html( $setting = null, $format = '%s' ) {

		call_user_func( array( $this, sprintf( '__%s_html', $this->__context ) ), $setting, $format );

	}

	/**
	 * Returns HTML markup if passed setting not empty.
	 *
	 * @param  string $setting Passed setting.
	 * @param  string $format  Required markup.
	 * @param  array  $args    Additional variables to pass into format string.
	 * @param  bool   $echo    Echo or return.
	 * @return string|void
	 */
	public function __get_html( $setting = null, $format = '%s' ) {

		ob_start();
		$this->__html( $setting, $format );
		return ob_get_clean();

	}

	/**
	 * Print HTML template
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $format  [description]
	 * @return [type]          [description]
	 */
	public function __render_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$key     = $setting[1];
			$setting = $setting[0];
		}

		$val = $this->get_settings( $setting );

		if ( ! is_array( $val ) && '0' === $val ) {
			printf( $format, $val );
		}

		if ( is_array( $val ) && empty( $val[ $key ] ) ) {
			return '';
		}

		if ( ! is_array( $val ) && empty( $val ) ) {
			return '';
		}

		if ( is_array( $val ) ) {
			printf( $format, $val[ $key ] );
		} else {
			printf( $format, $val );
		}

	}

	/**
	 * Render meta for passed position
	 *
	 * @param  string $position [description]
	 * @return [type]           [description]
	 */
	public function __render_meta( $position = '', $base = '', $context = array( 'before' ), $settings = array() ) {

		$settings      = ! empty( $settings ) ? $settings : $this->get_settings();
		$config_key    = $position . '_meta';
		$show_key      = 'show_' . $position . '_meta';
		$position_key  = 'meta_' . $position . '_position';
		$meta_show     = ! empty( $settings[ $show_key ] ) ? $settings[ $show_key ] : false;
		$meta_position = ! empty( $settings[ $position_key ] ) ? $settings[ $position_key ] : false;
		$meta_config   = ! empty( $settings[ $config_key ] ) ? $settings[ $config_key ] : false;

		if ( 'yes' !== $meta_show ) {
			return;
		}

		if ( ! $meta_position || ! in_array( $meta_position, $context ) ) {
			return;
		}

		if ( empty( $meta_config ) ) {
			return;
		}

		$result = '';

		foreach ( $meta_config as $meta ) {

			if ( empty( $meta['meta_key'] ) ) {
				continue;
			}

			$key      = $meta['meta_key'];
			$callback = ! empty( $meta['meta_callback'] ) ? $meta['meta_callback'] : false;
			$value    = get_post_meta( get_the_ID(), $key, false );

			if ( ! $value ) {
				continue;
			}

			$callback_args = array( $value[0] );

			if ( $callback && 'wp_get_attachment_image' === $callback ) {
				$callback_args[] = 'full';
			}

			if ( ! empty( $callback ) && is_callable( $callback ) ) {
				$meta_val = call_user_func_array( $callback, $callback_args );
			} else {
				$meta_val = $value[0];
			}

			$meta_val = sprintf( $meta['meta_format'], $meta_val );

			$label = ! empty( $meta['meta_label'] )
				? sprintf( '<div class="%1$s__item-label">%2$s</div>', $base, $meta['meta_label'] )
				: '';

			$result .= sprintf(
				'<div class="%1$s__item">%2$s<div class="%1$s__item-value">%3$s</div></div>',
				$base, $label, $meta_val
			);

		}

		printf( '<div class="%1$s">%2$s</div>', $base, $result );

	}

	/**
	 * Add meta controls for selected poition
	 *
	 * @param  [type] $position [description]
	 * @return [type]           [description]
	 */
	public function __add_meta_controls( $position_slug, $position_name ) {

		$this->add_control(
			'show_' . $position_slug . '_meta',
			array(
				'label'        => sprintf( esc_html__( 'Show Meta %s', 'jet-blog' ), $position_name ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blog' ),
				'label_off'    => esc_html__( 'No', 'jet-blog' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'meta_' . $position_slug . '_position',
			array(
				'label'   => esc_html__( 'Meta Fields Position', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => array(
					'before' => esc_html__( 'Before', 'jet-blog' ),
					'after'  => esc_html__( 'After', 'jet-blog' ),
				),
				'condition'   => array(
					'show_' . $position_slug . '_meta' => 'yes',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'meta_key',
			array(
				'label'       => esc_html__( 'Key', 'jet-blog' ),
				'description' => esc_html__( 'Meta key from postmeta table in database', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);

		$repeater->add_control(
			'meta_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-blog' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'meta_format',
			array(
				'label'       => esc_html__( 'Value Format', 'jet-blog' ),
				'description' => esc_html__( 'Value format string, accepts HTML markup. %s - is meta value', 'jet-blog' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '%s',
			)
		);

		$repeater->add_control(
			'meta_callback',
			array(
				'label'   => esc_html__( 'Prepare meta value with callback', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                        => esc_html__( 'Clean', 'jet-blog' ),
					'get_permalink'           => 'get_permalink',
					'get_the_title'           => 'get_the_title',
					'wp_get_attachment_url'   => 'wp_get_attachment_url',
					'wp_get_attachment_image' => 'wp_get_attachment_image',
				),
			)
		);

		$this->add_control(
			$position_slug . '_meta',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ meta_key }}}',
				'default'     => array(
					array(
						'meta_label' => esc_html__( 'Label', 'jet-blog' ),
					)
				),
				'condition'   => array(
					'show_' . $position_slug . '_meta' => 'yes',
				),
			)
		);

	}

	/**
	 * Add meta controls for selected poition
	 *
	 * @param  [type] $position [description]
	 * @return [type]           [description]
	 */
	public function __add_meta_style_controls( $position_slug, $position_name, $base ) {

		$this->add_control(
			$position_slug . '_meta_styles',
			array(
				'label'     => sprintf( esc_html__( 'Meta Styles %s', 'jet-blog' ), $position_name ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			$position_slug . '_meta_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .' . $base => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			$position_slug . '_meta_label_heading',
			array(
				'label'     => esc_html__( 'Meta Label', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			$position_slug . '_meta_label_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .' . $base . '__item-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $position_slug . '_meta_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .' . $base . '__item-label',
			)
		);

		$this->add_control(
			$position_slug . '_meta_label_display',
			array(
				'label'   => esc_html__( 'Dispaly Meta Label and Value', 'jet-blog' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'inline-block' => esc_html__( 'Inline', 'jet-blog' ),
					'block'        => esc_html__( 'As Blocks', 'jet-blog' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .' . $base . '__item-label' => 'display: {{VALUE}}',
					'{{WRAPPER}} .' . $base . '__item-value' => 'display: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			$position_slug . '_meta_label_gap',
			array(
				'label'       => esc_html__( 'Horizontal Gap Between Label and Value', 'jet-blog' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => 0,
				'max'         => 20,
				'step'        => 1,
				'selectors' => array(
					'{{WRAPPER}} .' . $base . '__item-label' => 'margin-right: {{VALUE}}px',
				),
			)
		);

		$this->add_control(
			$position_slug . '_meta_value_heading',
			array(
				'label'     => esc_html__( 'Meta Value', 'jet-blog' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			$position_slug . '_meta_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .' . $base . '__item-value' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $position_slug . '_meta_typography',
				'selector' => '{{WRAPPER}} .' . $base . '__item-value',
			)
		);

		$this->add_responsive_control(
			$position_slug . '_meta_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . $base => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$position_slug . '_meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . $base => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$position_slug . '_meta_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .' . $base => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Print underscore template
	 *
	 * @param  [type] $setting [description]
	 * @param  [type] $format  [description]
	 * @return [type]          [description]
	 */
	public function __edit_html( $setting = null, $format = '%s' ) {

		if ( is_array( $setting ) ) {
			$setting = $setting[0] . '.' . $setting[1];
		}

		echo '<# if ( settings.' . $setting . ' ) { #>';
		printf( $format, '{{{ settings.' . $setting . ' }}}' );
		echo '<# } #>';
	}

	/**
	 * Set posts query results
	 */
	public function __set_query( $posts ) {
		$this->__query = $posts;
	}

	/**
	 * Return posts query results
	 */
	public function __get_query() {
		return $this->__query;
	}

	/**
	 * Check if is Library template preview.
	 *
	 * @return boolean [description]
	 */
	public function _is_template_preview() {

		$is_preview = false;

		if ( isset( $_GET['elementor_library'] ) && isset( $_GET['preview'] ) ) {
			$is_preview = true;
		}

		return apply_filters( 'jet-blog/base/is-template-preview', $is_preview );

	}

}
