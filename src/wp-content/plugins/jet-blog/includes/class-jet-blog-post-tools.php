<?php
/**
 * JetBlog post tools class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blog_Post_Tools' ) ) {

	/**
	 * Define Jet_Blog_Post_Tools class
	 */
	class Jet_Blog_Post_Tools {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

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
		 * Get post author
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_author( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible' => 'true',
				'icon'    => '',
				'prefix'  => '',
				'html'    => '%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a>',
				'title'   => '',
				'class'   => 'post-author',
				'echo'    => false,
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
		public function get_date( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible'     => true,
				'icon'        => '',
				'prefix'      => '',
				'html'        => '%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a>',
				'title'       => '',
				'class'       => 'post-date',
				'date_format' => '',
				'human_time'  => false,
				'echo'        => false,
			);
			$args = wp_parse_args( $args, $default_args );
			$html = '' ;

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				$html_class       = ( $args['class'] ) ? 'class="' . esc_attr( $args['class'] ) . '"' : '';
				$title            = ( $args['title'] ) ? 'title="' . esc_attr( $args['title'] ) . '"' : '';
				$date_post_format = ( $args['date_format'] ) ? esc_attr( $args['date_format'] ) : get_option( 'date_format' );
				$date             = ( $args['human_time'] ) ? human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) : get_the_date( $date_post_format );
				$time             = get_the_time( 'Y-m-d\TH:i:sP' );

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
		public function get_comment_count( $args = array(), $id = 0 ) {
			$object = $this->get_post_object( $id );

			if ( empty( $object->ID ) ) {
				return false;
			}

			$default_args = array(
				'visible' => true,
				'icon'    => '',
				'prefix'  => '',
				'suffix'  => '%s',
				'html'    => '%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a>',
				'title'   => '',
				'class'   => 'post-comments-count',
				'echo'    => false,
			);

			$args = wp_parse_args( $args, $default_args );

			$args['suffix'] = ( isset( $args['sufix'] ) ) ? $args['sufix'] : $args['suffix'];

			$html  = '';
			$count = '';

			if ( filter_var( $args['visible'], FILTER_VALIDATE_BOOLEAN ) ) {
				$post_type = get_post_type( $object->ID );
				if ( post_type_supports( $post_type, 'comments' ) ) {
					$suffix = is_string( $args['suffix'] ) ? $args['suffix'] : translate_nooped_plural( $args['suffix'], $object->comment_count, 'jet-blog' );
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
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Blog_Post_Tools
 *
 * @return object
 */
function jet_blog_post_tools() {
	return Jet_Blog_Post_Tools::get_instance();
}
