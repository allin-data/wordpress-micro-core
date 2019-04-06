<?php
/**
 * Cherry addons tools class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blog_Tools' ) ) {

	/**
	 * Define Jet_Blog_Tools class
	 */
	class Jet_Blog_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Returns columns classes string
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function col_classes( $columns = array() ) {

			$columns = wp_parse_args( $columns, array(
				'desk' => 1,
				'tab'  => 1,
				'mob'  => 1,
			) );

			$classes = array();

			foreach ( $columns as $device => $cols ) {
				if ( ! empty( $cols ) ) {
					$classes[] = sprintf( 'col-%1$s-%2$s', $device, $cols );
				}
			}

			return implode( ' ' , $classes );
		}

		/**
		 * Returns disable columns gap nad rows gap classes string
		 *
		 * @param  string $use_cols_gap [description]
		 * @param  string $use_rows_gap [description]
		 * @return [type]               [description]
		 */
		public function gap_classes( $use_cols_gap = 'yes', $use_rows_gap = 'yes' ) {

			$result = array();

			foreach ( array( 'cols' => $use_cols_gap, 'rows' => $use_rows_gap ) as $element => $value ) {
				if ( 'yes' !== $value ) {
					$result[] = sprintf( 'disable-%s-gap', $element );
				}
			}

			return implode( ' ', $result );

		}

		/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'jet-blog' ), ), $result );
		}

		/**
		 * Get categories list.
		 *
		 * @return array
		 */
		public function get_categories() {

			$categories = get_categories();

			if ( empty( $categories ) || ! is_array( $categories ) ) {
				return array();
			}

			return wp_list_pluck( $categories, 'name', 'term_id' );

		}

		/**
		 * Returns icons data list.
		 *
		 * @return array
		 */
		public function get_theme_icons_data() {

			$default = array(
				'icons'  => false,
				'format' => 'fa %s',
				'file'   => false,
			);

			/**
			 * Filter default icon data before useing
			 *
			 * @var array
			 */
			$icon_data = apply_filters( 'jet-blog/controls/icon/data', $default );
			$icon_data = array_merge( $default, $icon_data );

			return $icon_data;
		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function orderby_arr() {
			return array(
				'none'          => esc_html__( 'None', 'jet-blog' ),
				'ID'            => esc_html__( 'ID', 'jet-blog' ),
				'author'        => esc_html__( 'Author', 'jet-blog' ),
				'title'         => esc_html__( 'Title', 'jet-blog' ),
				'name'          => esc_html__( 'Name (slug)', 'jet-blog' ),
				'date'          => esc_html__( 'Date', 'jet-blog' ),
				'modified'      => esc_html__( 'Modified', 'jet-blog' ),
				'rand'          => esc_html__( 'Rand', 'jet-blog' ),
				'comment_count' => esc_html__( 'Comment Count', 'jet-blog' ),
				'menu_order'    => esc_html__( 'Menu Order', 'jet-blog' ),
			);
		}

		/**
		 * Returns allowed order fields for options
		 *
		 * @return array
		 */
		public function order_arr() {

			return array(
				'desc' => esc_html__( 'Descending', 'jet-blog' ),
				'asc'  => esc_html__( 'Ascending', 'jet-blog' ),
			);

		}

		/**
		 * Returns allowed order by fields for options
		 *
		 * @return array
		 */
		public function verrtical_align_attr() {
			return array(
				'baseline'    => esc_html__( 'Baseline', 'jet-blog' ),
				'top'         => esc_html__( 'Top', 'jet-blog' ),
				'middle'      => esc_html__( 'Middle', 'jet-blog' ),
				'bottom'      => esc_html__( 'Bottom', 'jet-blog' ),
				'sub'         => esc_html__( 'Sub', 'jet-blog' ),
				'super'       => esc_html__( 'Super', 'jet-blog' ),
				'text-top'    => esc_html__( 'Text Top', 'jet-blog' ),
				'text-bottom' => esc_html__( 'Text Bottom', 'jet-blog' ),
			);
		}

		/**
		 * Returns array with numbers in $index => $name format for numeric selects
		 *
		 * @param  integer $to Max numbers
		 * @return array
		 */
		public function get_select_range( $to = 10, $exclude = array(), $from_zero = false ) {

			$from  = ( true === $from_zero ) ? 0 : 1;
			$range = range( $from, $to );

			if ( ! empty( $exclude ) ) {
				$range = array_diff( $range, $exclude );
			}

			return array_combine( $range, $range );
		}

		/**
		 * Returns badge placeholder URL
		 *
		 * @return void
		 */
		public function get_badge_placeholder() {
			return jet_blog()->plugin_url( 'assets/images/placeholder-badge.svg' );
		}

		/**
		 * Rturns image tag or raw SVG
		 *
		 * @param  string $url  image URL.
		 * @param  array  $attr [description]
		 * @return string
		 */
		public function get_image_by_url( $url = null, $attr = array() ) {

			$url = esc_url( $url );

			if ( empty( $url ) ) {
				return;
			}

			$ext  = pathinfo( $url, PATHINFO_EXTENSION );
			$attr = array_merge( array( 'alt' => '' ), $attr );

			if ( 'svg' !== $ext ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			$base_url = network_site_url( '/' );
			$svg_path = str_replace( $base_url, ABSPATH, $url );
			$key      = md5( $svg_path );
			$svg      = get_transient( $key );

			if ( ! $svg ) {
				$svg = file_get_contents( $svg_path );
			}

			if ( ! $svg ) {
				return sprintf( '<img src="%1$s"%2$s>', $url, $this->get_attr_string( $attr ) );
			}

			set_transient( $key, $svg, DAY_IN_SECONDS );

			unset( $attr['alt'] );

			return sprintf( '<div%2$s>%1$s</div>', $svg, $this->get_attr_string( $attr ) ); ;
		}

		/**
		 * Return attributes string from attributes array.
		 *
		 * @param  array  $attr Attributes string.
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			if ( empty( $attr ) || ! is_array( $attr ) ) {
				return;
			}

			$result = '';

			foreach ( $attr as $key => $value ) {
				$result .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}

			return $result;
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'jet-blog/post-types-list/deprecated',
				array( 'attachment', 'elementor_library' )
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Get post taxonomies options list
		 *
		 * @return array
		 */
		public function get_post_taxonomies() {

			$post_types = $this->get_post_types();
			$result     = array();

			$deprecated = apply_filters(
				'jet-blog/post-taxonomies-list/deprecated',
				array( 'product_shipping_class' )
			);

			foreach ( $post_types as $type => $label ) {

				$taxonomies = get_object_taxonomies( $type, 'objects' );

				if ( ! empty( $taxonomies ) ) {
					foreach ( $taxonomies as $tax ) {

						if ( $tax->public && ! in_array( $tax->name, $deprecated ) ) {
							$result[ $tax->name ] = $tax->label;
						}
					}
				}

			}

			return $result;

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_prev_arrows_list() {

			return apply_filters(
				'jet-blog/carousel/available-arrows/prev',
				array(
					'fa fa-angle-left'          => __( 'Angle', 'jet-blog' ),
					'fa fa-chevron-left'        => __( 'Chevron', 'jet-blog' ),
					'fa fa-angle-double-left'   => __( 'Angle Double', 'jet-blog' ),
					'fa fa-arrow-left'          => __( 'Arrow', 'jet-blog' ),
					'fa fa-caret-left'          => __( 'Caret', 'jet-blog' ),
					'fa fa-long-arrow-left'     => __( 'Long Arrow', 'jet-blog' ),
					'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'jet-blog' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'jet-blog' ),
					'fa fa-caret-square-o-left' => __( 'Caret Square', 'jet-blog' ),
				)
			);

		}

		/**
		 * Return availbale arrows list
		 * @return [type] [description]
		 */
		public function get_available_next_arrows_list() {

			return apply_filters(
				'jet-blog/carousel/available-arrows/next',
				array(
					'fa fa-angle-right'          => __( 'Angle', 'jet-blog' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'jet-blog' ),
					'fa fa-angle-double-right'   => __( 'Angle Double', 'jet-blog' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'jet-blog' ),
					'fa fa-caret-right'          => __( 'Caret', 'jet-blog' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'jet-blog' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'jet-blog' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'jet-blog' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'jet-blog' ),
				)
			);

		}

		/**
		 * Get terms from passed taxonomy
		 *
		 * @param  string $tax Taxonomy name.
		 * @return array.
		 */
		public function get_terms( $tax = 'category' ) {

			$terms   = get_terms( array( 'taxonomy' => $tax ) );
			$default = array( esc_html__( 'From All', 'jet-blog' ) );

			if ( empty( $terms ) ) {
				return $default;
			}

			$filtered_terms    = wp_list_pluck( $terms, 'name', 'term_id' );
			$filtered_terms[0] = $default[0];

			ksort( $filtered_terms );

			return $filtered_terms;
		}

		/**
		 * Returns carousel arrow
		 *
		 * @param  array $classes Arrow additional classes list.
		 * @return string
		 */
		public function get_carousel_arrow( $icon = '', $arrow = 'next' ) {

			$format = apply_filters(
				'jet-blog/carousel/arrows-format',
				'<i class="%1$s jet-arrow-%2$s jet-blog-arrow"></i>'
			);

			return sprintf( $format, $icon, $arrow );
		}

		public function get_archive_control_desc() {

			$link = sprintf(
				'<a href="%1$s" target="_blank">%2$s</a>',
				esc_url( admin_url( 'options-reading.php#posts_per_page' ) ),
				esc_html__( 'here', 'jet-blog' )
			);

			return sprintf( esc_html__( 'Note: posts number per page will be inherited from Reading Settings. You can change it %s', 'jet-blog' ), $link );

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Blog_Tools
 *
 * @return object
 */
function jet_blog_tools() {
	return Jet_Blog_Tools::get_instance();
}
