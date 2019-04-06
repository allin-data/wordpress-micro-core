<?php
/**
 * Cherry addons tools class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elementst_Post_Tools' ) ) {

	/**
	 * Define Jet_Elements_Post_Tools class
	 */
	class Jet_Elements_Post_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Get post title.
		 *
		 * @since  1.0.0
		 * @param array  $args array of arguments.
		 * @param [type] $type - post, term.
		 * @param int    $id ID of post.
		 * @return string
		 */
		public function get_post_title( $args = array(), $type = 'post', $id = 0 ) {
			$object = call_user_func( array( $this, 'get_' . $type . '_object' ), $id );

			if ( 'post' === $type && empty( $object->ID ) || 'term' === $type && empty( $object->term_id ) ) {
				return '';
			}

			$default_args = array(
				'visible'      => true,
				'length'       => -1,
				'trimmed_type' => 'word',
				'ending'       => '&hellip;',
				'html'         => '<h3 %1$s><a href="%2$s" %3$s rel="bookmark">%4$s</a></h3>',
				'class'        => '',
				'title'        => '',
				'echo'         => false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) && 0 !== $args['length'] ) {
				$title     = ( 'post' === $type ) ? $object->post_title : $object->name;
				$title_cut = $title;

				$title     = ( $args['title'] ) ? 'title="' . $args['title'] . '"' : 'title="' . $title . '"';
				$title_cut = $this->cut_text( $title_cut, $args['length'], $args['trimmed_type'], $args['ending'] );

				$link       = ( 'post' === $type ) ? $this->get_post_permalink() : $this->get_term_permalink( $object->term_id );
				$html_class = ( $args['class'] ) ? 'class="' . $args['class'] . '"' : '' ;

				$html = sprintf( $args['html'], $html_class, $link, $title, $title_cut );
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get post excerpt
		 *
		 * @since  1.0.0
		 * @param array  $args array of arguments.
		 * @param [type] $type - post, term.
		 * @param int    $id ID of post.
		 * @return string
		 */
		public function get_post_content( $args = array(), $type = 'post', $id = 0 ) {
			$object = call_user_func( array( $this, 'get_' . $type . '_object' ), $id );

			if ( 'post' === $type && empty( $object->ID ) || 'term' === $type && empty( $object->term_id ) ) {
				return '';
			}

			$default_args = array(
				'visible'      => true,
				'content_type' => 'post_content',
				'length'       => -1,
				'trimmed_type' => 'word',
				'ending'       => '&hellip;',
				'html'         => '<p %1$s>%2$s</p>',
				'class'        => '',
				'echo'         => false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;
			$content_type = $args['content_type'];

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				if ( 'term' === $type ) {
					$text = $object->description;
				} elseif ( 'post_content' === $content_type || 'post_excerpt' === $content_type && empty( $object->$content_type ) ) {
					$text = get_the_content();
				} else {
					$text = get_the_excerpt();
				}

				$text = $this->cut_text( $text, $args['length'], $args['trimmed_type'], $args['ending'], true );

				if ( $text ) {
					$html_class = ( $args['class'] ) ? 'class="' . $args['class'] . '"' : '' ;

					$html = sprintf( $args['html'], $html_class, $text );
				}
			}

			$html = apply_filters( 'the_content', $html );

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get post author
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_post_author( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible'	=> 'true',
				'icon'		=> '',
				'prefix'	=> '',
				'html'		=> '%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a>',
				'title'		=> '',
				'class'		=> 'post-author',
				'echo'		=> false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				$html_class = ( $args['class'] ) ? 'class="' . $args['class'] . '"' : '';
				$title      = ( $args['title'] ) ? 'title="' . $args['title'] . '"' : '';
				$author     = get_the_author();
				$link       = get_author_posts_url( $object->post_author );

				$html = sprintf( $args['html'], $args['prefix'], $link, $title, $html_class, $args['icon'], $author );
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get post date.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_post_date( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible'		=> true,
				'icon'			=> '',
				'prefix'		=> '',
				'html'			=> '%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a>',
				'title'			=> '',
				'class'			=> 'post-date',
				'date_format'	=> '',
				'human_time'	=> false,
				'echo'			=> false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				$html_class			= ( $args['class'] ) ? 'class="' . esc_attr( $args['class'] ) . '"' : '' ;
				$title				= ( $args['title'] ) ? 'title="' . esc_attr( $args['title'] ) . '"' : '' ;
				$date_post_format	= ( $args['date_format'] ) ? esc_attr( $args['date_format'] ) : get_option( 'date_format' );
				$date				= ( $args['human_time'] ) ? human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) : get_the_date( $date_post_format );
				$time				= get_the_time( 'Y-m-d\TH:i:sP' );

				preg_match_all( '/(\d+)/mi', $time, $date_array );
				$link = get_day_link( (int) $date_array[0][0], (int) $date_array[0][1], (int) $date_array[0][2] );

				$html = sprintf( $args['html'], $args['prefix'], $link, $title, $html_class, $time, $args['icon'], $date );
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get comment count
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_post_comment_count( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible'		=> true,
				'icon'			=> '',
				'prefix'		=> '',
				'suffix'		=> '%s',
				'html'			=> '%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a>',
				'title'			=> '',
				'class'			=> 'post-comments-count',
				'echo'			=> false,
			);

			$args = wp_parse_args( $args, $default_args );

			$args['suffix'] = ( isset( $args['sufix'] ) ) ? $args['sufix'] : $args['suffix'];

			$html  = '';
			$count = '';

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				$post_type = get_post_type( $object->ID );
				if ( post_type_supports( $post_type, 'comments' ) ) {
					$suffix = is_string( $args['suffix'] ) ? $args['suffix'] : translate_nooped_plural( $args['suffix'], $object->comment_count, $args['suffix']['domain'] );
					$count = sprintf( $suffix, $object->comment_count );
				}

				$html_class = ( $args['class'] ) ? 'class="' . $args['class'] . '"' : '';
				$title = ( $args['title'] ) ? 'title="' . $args['title'] . '"' : '';
				$link = get_comments_link();

				$html = sprintf( $args['html'], $args['prefix'], $link, $title, $html_class, $args['icon'], $count );
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get post more button
		 *
		 * @since  1.0.0
		 * @param array  $args array of arguments.
		 * @param [type] $type - post, term.
		 * @param int    $id ID of post.
		 * @return string
		 */
		public function get_post_button( $args = array(), $type = 'post', $id = 0 ) {
			$object = call_user_func( array( $this, 'get_' . $type . '_object' ), $id );

			if ( 'post' === $type && empty( $object->ID ) || 'term' === $type && empty( $object->term_id ) ) {
				return false;
			}

			$default_args = array(
				'visible' => true,
				'text'    => '',
				'icon'    => '',
				'html'    => '<a href="%1$s" %2$s %3$s><span class="btn__text">%4$s</span>%5$s</a>',
				'class'   => 'btn',
				'title'   => '',
				'echo'    => false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {

				if ( $args['text'] || $args['icon'] ) {

					$html_class = ( $args['class'] ) ? 'class="' . $args['class'] . '"' : '' ;
					$text = esc_html( $args['text'] );

					if ( 'term' === $type ) {

						$title = $object->name;
						$link = esc_url( get_category_link( $object->term_id ) );
					} else {
						$title = $object->post_title;
						$link = esc_url( get_the_permalink() );
					}

					$title = ( $args['title'] ) ? 'title="' . $args['title'] . '"' : 'title="' . $title . '"' ;
					$html = sprintf( $args['html'], $link, $title, $html_class, wp_kses( $text, wp_kses_allowed_html( 'post' ) ), $args['icon'] );
				}
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * [get_image description]
		 * @param  array   $args [description]
		 * @param  string  $type [description]
		 * @param  integer $id   [description]
		 * @return [type]        [description]
		 */
		public function get_post_image( $args = array(), $type = 'post', $id = 0 ) {

			if ( is_callable( array( $this, 'get_' . $type . '_object' ) ) ) {
				$object = call_user_func( array( $this, 'get_' . $type . '_object' ), $id );

				if ( 'post' === $type && empty( $object->ID ) || 'term' === $type && empty( $object->term_id ) ) {
					return '';
				}
			}

			$default_args = array(
				'visible'                => true,
				'size'                   => apply_filters( 'cherry_normal_image_size', 'post-thumbnail' ),
				'mobile_size'            => apply_filters( 'cherry_mobile_image_size', 'post-thumbnail' ),
				'html'                   => '<a href="%1$s" %2$s ><img src="%3$s" alt="%4$s" %5$s ></a>',
				'class'                  => 'wp-image',
				'placeholder'            => true,
				'placeholder_background' => '000',
				'placeholder_foreground' => 'fff',
				'placeholder_title'      => '',
				'html_tag_suze'          => true,
				'echo'                   => false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '';

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {

				$intermediate_image_sizes   = get_intermediate_image_sizes();
				$intermediate_image_sizes[] = 'full';

				$size = wp_is_mobile() ? $args['mobile_size'] : $args['size'];
				$size = in_array( $size, $intermediate_image_sizes ) ? $size : 'post-thumbnail';

				// Placeholder defaults attr.
				$size_array = $this->get_thumbnail_size_array( $size );

				switch ( $type ) {
					case 'post':
						$id           = $object->ID;
						$thumbnail_id = get_post_thumbnail_id( $id );
						$alt          = esc_attr( $object->post_title );
						$link         = $this->get_post_permalink();
					break;

					case 'term':
						$id           = $object->term_id;
						$thumbnail_id = get_term_meta( $id, $this->args['meta_key']['term_thumb'] , true );
						$alt          = esc_attr( $object->name );
						$link         = $this->get_term_permalink( $id );
					break;

					case 'attachment':
						$thumbnail_id = $id;
						$alt          = get_the_title( $thumbnail_id );
						$link         = wp_get_attachment_image_url( $thumbnail_id, $size );
					break;
				}

				if ( $thumbnail_id ) {
					$image_data = wp_get_attachment_image_src( $thumbnail_id, $size );
					$src        = $image_data[0];

					$size_array['width']  = $image_data[1];
					$size_array['height'] = $image_data[2];

				} elseif ( filter_var( $args['placeholder'], FILTER_VALIDATE_BOOLEAN ) ) {
					$title = ( $args['placeholder_title'] ) ? $args['placeholder_title'] : $size_array['width'] . 'x' . $size_array['height'];
					$attr = array(
						'width'      => $size_array['width'],
						'height'     => $size_array['height'],
						'background' => $args['placeholder_background'],
						'foreground' => $args['placeholder_foreground'],
						'title'      => $title,
					);

					$attr = array_map( 'esc_attr', $attr );

					$width  = ( 4000 < intval( $attr['width'] ) )  ? 4000 : intval( $attr['width'] );
					$height = ( 4000 < intval( $attr['height'] ) ) ? 4000 : intval( $attr['height'] );

					$src = $this->get_placeholder_url( array(
						'width'      => $width,
						'height'     => $height,
						'background' => $attr['background'],
						'foreground' => $attr['foreground'],
						'title'      => $attr['title'],
					) );
				}

				$class         = ( $args['class'] ) ? 'class="' . esc_attr( $args['class'] ) . '"' : '';
				$html_tag_suze = ( filter_var( $args['html_tag_suze'], FILTER_VALIDATE_BOOLEAN ) ) ? 'width="' . $size_array['width'] . '" height="' . $size_array['height'] . '"' : '';

				if ( isset( $src ) ) {
					$html = sprintf( $args['html'], esc_url( $link ), $class, esc_url( $src ), esc_attr( $alt ), $html_tag_suze );
				}
			}

			return $this->output_method( $html, $args['echo'] );
		}

		/**
		 * Get placeholder image URL
		 *
		 * @param array $args Image argumnets.
		 * @return string
		 */
		public function get_placeholder_url( $args = array() ) {

			$args = wp_parse_args( $args, array(
				'width'      => 300,
				'height'     => 300,
				'background' => '000',
				'foreground' => 'fff',
				'title'      => '',
			) );

			$args      = array_map( 'urlencode', $args );
			$base_url  = 'http://fakeimg.pl';
			$format    = '%1$s/%2$sx%3$s/%4$s/%5$s/?text=%6$s';
			$image_url = sprintf(
				$format,
				$base_url, $args['width'], $args['height'], $args['background'], $args['foreground'], $args['title']
			);

			/**
			 * Filter image placeholder URL
			 *
			 * @param string $image_url Default URL.
			 * @param string $args      Image arguments.
			 */
			return apply_filters( 'cherry_utility_placeholder_image_url', esc_url( $image_url ), $args );
		}

		/**
		 * Get post
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public function get_post_object( $id = 0 ) {
			return get_post( $id );
		}

		/**
		 * Get term
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public function get_term_object( $id = 0 ) {
			return get_term( $id );
		}

		/**
		 * Get post permalink
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_post_permalink() {
			return esc_url( get_the_permalink() );
		}

		/**
		 * Get post permalink.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_term_permalink( $id = 0 ) {
			return esc_url( get_category_link( $id ) );
		}

		/**
		 * Output content method.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function output_method( $content = '', $echo = false ) {
			if ( ! filter_var( $echo, FILTER_VALIDATE_BOOLEAN ) ) {
				return $content;
			} else {
				echo $content;
			}
		}

		/**
		 * Get array image size
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_thumbnail_size_array( $size ) {
			$sizes = $this->get_image_sizes();

			if ( isset( $sizes[ $size ] ) ) {
				$size_array = $sizes[ $size ];

			} else if ( isset( $sizes['post-thumbnail'] ) ) {
				$size_array = $sizes['post-thumbnail'];

			} else {
				$size_array = $sizes['thumbnail'];
			}

			return $size_array;
		}

		/**
		 * Get size information for all currently-registered image sizes.
		 *
		 * @global $_wp_additional_image_sizes
		 * @uses   get_intermediate_image_sizes()
		 * @link   https://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
		 * @since  1.1.6
		 * @return array $sizes Data for all currently-registered image sizes.
		 */
		function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
					$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
					$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );

				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = array(
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					);
				}
			}

			return $sizes;
		}


		/**
		 * Cut text
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function cut_text( $text = '', $length = -1, $trimmed_type = 'word', $after, $content = false ) {

			if ( -1 !== $length ) {

				if ( $content ) {
					$text = strip_shortcodes( $text );
					$text = apply_filters( 'the_content', $text );
					$text = str_replace( ']]>', ']]&gt;', $text );
				}

				if ( 'word' === $trimmed_type ) {
					$text = wp_trim_words( $text, $length, $after );
				} else {
					$text = wp_html_excerpt( $text, $length, $after );
				}
			}

			return $text;
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
 * Returns instance of Jet_Elements_Post_Tools
 *
 * @return object
 */
function jet_elements_post_tools() {
	return Jet_Elements_Post_Tools::get_instance();
}
