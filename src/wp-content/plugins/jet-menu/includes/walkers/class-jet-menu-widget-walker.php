<?php
/**
 * Walker class
 */
class Jet_Menu_Widget_Walker extends Walker_Nav_Menu {

	protected $item_type = 'simple';
	private $item_settings = null;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( 'mega' === $this->get_item_type() ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes     = array( 'jet-custom-nav__sub' );
		$class_names = join( ' ', $classes );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<div $class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( 'mega' === $this->get_item_type() ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</div>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// Don't put any code before this!
		$this->item_settings = null;
		$this->set_item_type( $item->ID, $depth );

		if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$settings = $this->get_settings( $item->ID );
		$indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'jet-custom-nav__item';
		$classes[] = 'jet-custom-nav__item-' . $item->ID;

		jet_menu_tools()->add_menu_css( $item->ID, '.jet-custom-nav__item-' . $item->ID );

		if ( $this->is_mega_enabled( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';

			if ( ! empty( $settings['vertical_mega_menu_position'] ) ) {
				$classes[] = 'jet-custom-nav-mega-sub-position-' . esc_attr( $settings['vertical_mega_menu_position'] );
			}
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', array_filter( $classes ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<div' . $class_names .'>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$link_classes = array( 'jet-custom-nav__item-link' );

		if ( isset( $settings['hide_item_text'] ) && 'true' === $settings['hide_item_text'] ) {
			$link_classes[] = 'jet-custom-nav__item-link--hidden-label';
		}

		$atts['class'] = implode( ' ', $link_classes );

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		if ( isset( $settings['hide_item_text'] ) && 'true' === $settings['hide_item_text'] ) {
			$title = '';
		}

		$desc  = '';

		if ( ! empty( $item->description ) ) {
			$desc = sprintf(
				'<span class="jet-custom-item-desc %2$s">%1$s</span>',
				$item->description,
				( 0 === $depth ) ? 'top-level-desc' : 'sub-level-desc'
			);
		}

		$title = sprintf( '<span class="jet-menu-link-text">%s%s</span>', $title, $desc );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';

		if ( ! empty( $settings['menu_icon'] ) ) {
			$title = jet_menu_tools()->get_icon_html( $settings['menu_icon'] ) . $title;
		}

		if ( ! empty( $settings['menu_badge'] ) ) {
			$title = $title . jet_menu_tools()->get_badge_html( $settings['menu_badge'], $depth );
		}

		if ( in_array( 'menu-item-has-children', $item->classes ) || $this->is_mega_enabled( $item->ID ) ) {
			$arrow_icon = isset( $args->widget_settings['dropdown_icon'] ) ? $args->widget_settings['dropdown_icon'] : 'fa fa-chevron-right';

			$title = $title . jet_menu_tools()->get_dropdown_arrow_html( $arrow_icon, null );
		}

		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$is_elementor = ( isset( $_GET['elementor-preview'] ) ) ? true : false;

		$mega_item = get_post_meta( $item->ID, jet_menu_post_type()->meta_key(), true );

		if ( $this->is_mega_enabled( $item->ID ) && ! $is_elementor ) {

			$content = '';

			do_action( 'jet-menu/widgets/custom-menu/mega-sub-menu/before-render', $item->ID );

			if ( class_exists( 'Elementor\Plugin' ) ) {
				$elementor = Elementor\Plugin::instance();
				$content   = $elementor->frontend->get_builder_content_for_display( $mega_item );
			}

			do_action( 'jet-menu/widgets/custom-menu/mega-sub-menu/after-render', $item->ID );

			$item_output .= sprintf( '<div class="jet-custom-nav__mega-sub">%s</div>', do_shortcode( $content ) );

			// Fixed displaying mega and sub menu together.
			$this->set_item_type( $item->ID, $depth );
		}

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$output .= "</div>{$n}";
	}

	/**
	 * Store in WP Cache processed item type
	 *
	 * @param integer $item_id Current menu Item ID
	 * @param integer $depth   Current menu Item depth
	 */
	public function set_item_type( $item_id = 0, $depth = 0 ) {

		if ( 0 < $depth ) {
			return;
		}

		$item_type = 'simple';

		if ( $this->is_mega_enabled( $item_id ) ) {
			$item_type = 'mega';
		}

		wp_cache_set( 'item-type', $item_type, 'jet-custom-menu' );

	}

	/**
	 * Returns current item (for top level items) or parent item (for subs) type.
	 * @return [type] [description]
	 */
	public function get_item_type() {
		return wp_cache_get( 'item-type', 'jet-custom-menu' );
	}

	/**
	 * Check if mega menu enabled for passed item
	 *
	 * @param  int  $item_id Item ID
	 * @return boolean
	 */
	public function is_mega_enabled( $item_id = 0 ) {

		$item_settings = $this->get_settings( $item_id );
		$menu_post     = jet_menu_post_type()->get_related_menu_post( $item_id );

		return ( isset( $item_settings['enabled'] ) && 'true' == $item_settings['enabled'] && ! empty( $menu_post ) );
	}

	/**
	 * Get item settings
	 *
	 * @param  integer $item_id Item ID
	 * @return array
	 */
	public function get_settings( $item_id = 0 ) {

		if ( null === $this->item_settings ) {
			$this->item_settings = jet_menu_settings_item()->get_settings( $item_id );
		}

		return $this->item_settings;
	}

}
