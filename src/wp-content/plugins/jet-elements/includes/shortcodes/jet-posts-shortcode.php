<?php
/**
 * Posts shortcode class
 */
class Jet_Posts_Shortcode extends Jet_Elements_Shortcode_Base {

	/**
	 * Shortocde tag
	 *
	 * @return string
	 */
	public function get_tag() {
		return 'jet-posts';
	}

	/**
	 * Shortocde attributes
	 *
	 * @return array
	 */
	public function get_atts() {

		$columns           = jet_elements_tools()->get_select_range( 6 );
		$custom_query_link = sprintf(
			'<a href="https://crocoblock.com/wp-query-generator/" target="_blank">%s</a>',
			__( 'Generate custom query', 'jet-elements' )
		);

		return apply_filters( 'jet-elements/shortcodes/jet-posts/atts', array(
			'number' => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Posts Number', 'jet-elements' ),
				'default'   => 3,
				'min'       => -1,
				'max'       => 30,
				'step'      => 1,
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'columns' => array(
				'type'       => 'select',
				'responsive' => true,
				'label'      => esc_html__( 'Columns', 'jet-elements' ),
				'default'    => 3,
				'options'    => $columns,
			),
			'columns_tablet' => array(
				'default' => 2,
			),
			'columns_mobile' => array(
				'default' => 1,
			),
			'equal_height_cols' => array(
				'label'        => esc_html__( 'Equal Columns Height', 'jet-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'is_archive_template' => array(
				'label'        => esc_html__( 'Is archive template', 'jet-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'post_type'   => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Post Type', 'jet-elements' ),
				'default'   => 'post',
				'options'   => jet_elements_tools()->get_post_types(),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'posts_query' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Query posts by', 'jet-elements' ),
				'default'    => 'latest',
				'options'    => array(
					'latest'   => esc_html__( 'Latest Posts', 'jet-elements' ),
					'category' => esc_html__( 'From Category (for Posts only)', 'jet-elements' ),
					'ids'      => esc_html__( 'By Specific IDs', 'jet-elements' ),
					'related'  => esc_html__( 'Related to current', 'jet-elements' ),
				),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'related_by' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Query related by', 'jet-elements' ),
				'default'    => 'taxonomy',
				'options'    => array(
					'taxonomy' => esc_html__( 'Taxonomy', 'jet-elements' ),
					'keyword'  => esc_html__( 'Keyword', 'jet-elements' ),
				),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => 'related',
					'is_archive_template!' => 'true',
				),
			),
			'related_tax' => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Select taxonomy to get related from', 'jet-elements' ),
				'default'   => '',
				'options'   => jet_elements_tools()->get_taxonomies_for_options(),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => 'related',
					'related_by'           => 'taxonomy',
					'is_archive_template!' => 'true',
				),
			),
			'related_keyword' => array(
				'type'        => 'text',
				'label_block' => true,
				'label'       => esc_html__( 'Keyword for related search', 'jet-elements' ),
				'description' => esc_html__( 'Use macros %meta_field_key% to get keyword from specific meta field', 'jet-elements' ),
				'default'     => '',
				'condition'   => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => 'related',
					'related_by'           => 'keyword',
					'is_archive_template!' => 'true',
				),
			),
			'post_ids' => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'jet-elements' ),
				'default'   => '',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => array( 'ids' ),
					'is_archive_template!' => 'true',
				),
			),
			'post_cat' => array(
				'type'       => 'select2',
				'label'      => esc_html__( 'Category', 'jet-elements' ),
				'default'    => '',
				'multiple'   => true,
				'options'    => jet_elements_tools()->get_categories(),
				'condition' => array(
					'use_custom_query!'    => 'true',
					'posts_query'          => array( 'category' ),
					'post_type'            => 'post',
					'is_archive_template!' => 'true',
				),
			),
			'post_offset' => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Post offset', 'jet-elements' ),
				'default'   => 0,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'separator' => 'before',
				'condition' => array(
					'use_custom_query!'    => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'use_custom_query_heading' => array(
				'label'     => esc_html__( 'Custom Query', 'jet-elements' ),
				'type'      => 'heading',
				'separator' => 'before',
				'condition' => array(
					'is_archive_template!' => 'true',
				),

			),
			'use_custom_query' => array(
				'label'        => esc_html__( 'Use Custom Query', 'jet-elements' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
				'condition' => array(
					'is_archive_template!' => 'true',
				),
			),
			'custom_query' => array(
				'type'        => 'textarea',
				'label'       => esc_html__( 'Set custom query', 'jet-elements' ),
				'default'     => '',
				'description' => $custom_query_link,
				'condition'   => array(
					'use_custom_query' => 'true',
					'is_archive_template!' => 'true',
				),
			),
			'show_title' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Title', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			),

			'title_trimmed' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Title Word Trim', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition' => array(
					'show_title' => 'yes',
				),
			),

			'title_length' => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Title Length', 'jet-elements' ),
				'default'   => 5,
				'min'       => 1,
				'max'       => 50,
				'step'      => 1,
				'condition' => array(
					'title_trimmed' => 'yes',
				),
			),

			'title_trimmed_ending_text' => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Title Trimmed Ending', 'jet-elements' ),
				'default'   => '...',
				'condition' => array(
					'title_trimmed' => 'yes',
				),
			),

			'show_image' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Featured Image', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_image_as' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Show Featured Image As', 'jet-elements' ),
				'default'     => 'image',
				'label_block' => true,
				'options'     => array(
					'image'      => esc_html__( 'Simple Image', 'jet-elements' ),
					'background' => esc_html__( 'Box Background', 'jet-elements' ),
				),
				'condition' => array(
					'show_image' => array( 'yes' ),
				),
			),
			'bg_size' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Background Image Size', 'jet-elements' ),
				'label_block' => true,
				'default'     => 'cover',
				'options'     => array(
					'cover'   => esc_html__( 'Cover', 'jet-elements' ),
					'contain' => esc_html__( 'Contain', 'jet-elements' ),
				),
				'condition'   => array(
					'show_image'    => array( 'yes' ),
					'show_image_as' => array( 'background' ),
				),
			),
			'bg_position' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Background Image Position', 'jet-elements' ),
				'label_block' => true,
				'default'     => 'center center',
				'options'     => array(
					'center center' => esc_html_x( 'Center Center', 'Background Control', 'jet-elements' ),
					'center left'   => esc_html_x( 'Center Left', 'Background Control', 'jet-elements' ),
					'center right'  => esc_html_x( 'Center Right', 'Background Control', 'jet-elements' ),
					'top center'    => esc_html_x( 'Top Center', 'Background Control', 'jet-elements' ),
					'top left'      => esc_html_x( 'Top Left', 'Background Control', 'jet-elements' ),
					'top right'     => esc_html_x( 'Top Right', 'Background Control', 'jet-elements' ),
					'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'jet-elements' ),
					'bottom left'   => esc_html_x( 'Bottom Left', 'Background Control', 'jet-elements' ),
					'bottom right'  => esc_html_x( 'Bottom Right', 'Background Control', 'jet-elements' ),
				),
				'condition'   => array(
					'show_image'    => array( 'yes' ),
					'show_image_as' => array( 'background' ),
				),
			),
			'thumb_size' => array(
				'type'       => 'select',
				'label'      => esc_html__( 'Featured Image Size', 'jet-elements' ),
				'default'    => 'post-thumbnail',
				'options'    => jet_elements_tools()->get_image_sizes(),
				'condition' => array(
					'show_image'    => array( 'yes' ),
					'show_image_as' => array( 'image' ),
				),
			),
			'show_excerpt' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Excerpt', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'excerpt_length' => array(
				'type'       => 'number',
				'label'      => esc_html__( 'Excerpt Length', 'jet-elements' ),
				'default'    => 20,
				'min'        => 1,
				'max'        => 300,
				'step'       => 1,
				'condition' => array(
					'show_excerpt' => array( 'yes' ),
				),
			),
			'show_meta' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Meta', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_author' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Author', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_meta' => array( 'yes' ),
				),
			),
			'show_date' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Date', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_meta' => array( 'yes' ),
				),
			),
			'show_comments' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Posts Comments', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'show_meta' => array( 'yes' ),
				),
			),
			'show_more' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Read More Button', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'more_text' => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Read More Button Text', 'jet-elements' ),
				'default'   => esc_html__( 'Read More', 'jet-elements' ),
				'condition' => array(
					'show_more' => array( 'yes' ),
				),
			),
			'more_icon' => array(
				'type'      => 'icon',
				'label'     => esc_html__( 'Read More Button Icon', 'jet-elements' ),
				'condition' => array(
					'show_more' => array( 'yes' ),
				),
			),
			'columns_gap' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between columns', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'rows_gap' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between rows', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_title_related_meta'       => array( 'default' => false ),
			'show_content_related_meta'     => array( 'default' => false ),
			'meta_title_related_position'   => array( 'default' => false ),
			'meta_content_related_position' => array( 'default' => false ),
			'title_related_meta'            => array( 'default' => false ),
			'content_related_meta'          => array( 'default' => false ),
		) );

	}

	/**
	 * Get default query args
	 *
	 * @return array
	 */
	public function get_default_query_args() {

		$query_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => intval( $this->get_attr( 'number' ) ),
		);

		$post_type = $this->get_attr( 'post_type' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		$query_args['post_type'] = $post_type;

		$offset = $this->get_attr( 'post_offset' );
		$offset = ! empty( $offset ) ? absint( $offset ) : 0;

		if ( $offset ) {
			$query_args['offset'] = $offset;
		}

		switch ( $this->get_attr( 'posts_query' ) ) {

			case 'category':

				if ( '' !== $this->get_attr( 'post_cat' ) ) {
					$query_args['category__in'] = explode( ',', $this->get_attr( 'post_cat' ) );
				}

				break;

			case 'ids':

				if ( '' !== $this->get_attr( 'post_ids' ) ) {
					$query_args['post__in'] = explode(
						',',
						str_replace( ' ', '', $this->get_attr( 'post_ids' ) )
					);
				}
				break;

			case 'related':

				$query_args = array_merge( $query_args, $this->get_related_query_args() );

				break;
		}

		return $query_args;

	}

	/**
	 * Get related query arguments
	 *
	 * @return [type] [description]
	 */
	public function get_related_query_args() {

		$args = array(
			'post__not_in' => array( get_the_ID() ),
		);

		$related_by = $this->get_attr( 'related_by' );

		switch ( $related_by ) {

			case 'taxonomy':

				$related_tax = $this->get_attr( 'related_tax' );

				if ( $related_tax ) {

					$terms = wp_get_post_terms( get_the_ID(), $related_tax, array( 'fields' => 'ids' ) );

					if ( $terms ) {
						$args['tax_query'] = array(
							array(
								'taxonomy' => $related_tax,
								'field'    => 'term_id',
								'terms'    => $terms,
								'operator' => 'IN',
							),
						);
					}

				}

				break;

			case 'keyword':

				$keyword = $this->get_attr( 'related_keyword' );

				preg_match( '/%(.*?)%/', $keyword, $matches );

				if ( empty( $matches ) ) {
					$args['s'] = $keyword;
				} else {
					$args['s'] = get_post_meta( get_the_ID(), $matches[1], true );
				}

				break;
		}

		return $args;

	}

	/**
	 * Get custom query args
	 *
	 * @return array
	 */
	public function get_custom_query_args() {

		$query_args = $this->get_attr( 'custom_query' );
		$query_args = json_decode( $query_args, true );

		if ( ! $query_args ) {
			$query_args = array();
		}

		return $query_args;
	}

	/**
	 * Query posts by attributes
	 *
	 * @return object
	 */
	public function query() {

		if ( 'true' === $this->get_attr( 'is_archive_template' ) ) {
			global $wp_query;
			$query = $wp_query;
			return $query;
		}

		if ( 'true' === $this->get_attr( 'use_custom_query' ) ) {
			$query_args = $this->get_custom_query_args();
		} else {
			$query_args = $this->get_default_query_args();
		}

		$query = new WP_Query( $query_args );

		return $query;
	}

	/**
	 * Posts shortocde function
	 *
	 * @param  array  $atts Attributes array.
	 * @return string
	 */
	public function _shortcode( $content = null ) {

		$query = $this->query();

		if ( ! $query->have_posts() ) {
			$not_found = $this->get_template( 'not-found' );
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		global $post;

		ob_start();

		/**
		 * Hook before loop start template included
		 */
		do_action( 'jet-elements/shortcodes/jet-posts/loop-start' );

		include $loop_start;

		while ( $query->have_posts() ) {

			$query->the_post();
			$post = $query->post;

			setup_postdata( $post );

			/**
			 * Hook before loop item template included
			 */
			do_action( 'jet-elements/shortcodes/jet-posts/loop-item-start' );

			include $loop_item;

			/**
			 * Hook after loop item template included
			 */
			do_action( 'jet-elements/shortcodes/jet-posts/loop-item-end' );

		}

		include $loop_end;

		/**
		 * Hook after loop end template included
		 */
		do_action( 'jet-elements/shortcodes/jet-posts/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}

	/**
	 * Add box backgroud image
	 */
	public function add_box_bg() {

		if ( 'yes' !== $this->get_attr( 'show_image' ) ) {
			return;
		}

		if ( 'background' !== $this->get_attr( 'show_image_as' ) ) {
			return;
		}

		if ( ! has_post_thumbnail() ) {
			return;
		}

		$thumb_id  = get_post_thumbnail_id();
		$thumb_url = wp_get_attachment_image_url( $thumb_id, 'full' );

		printf(
			' style="background-image: url(\'%1$s\');background-repeat:no-repeat;background-size: %2$s;background-position: %3$s;"',
			$thumb_url,
			$this->get_attr( 'bg_size' ),
			$this->get_attr( 'bg_position' )
		);

	}

	/**
	 * Render meta for passed position
	 *
	 * @param  string $position [description]
	 * @return [type]           [description]
	 */
	public function render_meta( $position = '', $base = '', $context = array( 'before' ) ) {

		$config_key    = $position . '_meta';
		$show_key      = 'show_' . $position . '_meta';
		$position_key  = 'meta_' . $position . '_position';
		$meta_show     = $this->get_attr( $show_key );
		$meta_position = $this->get_attr( $position_key );
		$meta_config   = $this->get_attr( $config_key );

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

}
