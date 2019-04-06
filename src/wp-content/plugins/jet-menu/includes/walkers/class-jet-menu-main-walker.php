<?php
/**
 * Walker class
 */
class Jet_Menu_Main_Walker extends Walker_Nav_Menu {

	protected $item_type   = 'simple';
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
		$classes = array( 'jet-sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
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
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
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
		$classes  = empty( $item->classes ) ? array() : (array) $item->classes;

		if ( isset( $args->roll_up ) && $args->roll_up ) {
			$classes[] = 'has-roll-up';
		} else {
			$classes[] = 'no-roll-up';
		}

		if ( 'mega' === $this->get_item_type() ) {
			$classes[] = 'mega-menu-item';

			if ( ! empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) {
				$classes[] = 'mega-menu-position-' . esc_attr( $settings['custom_mega_menu_position'] );
			}

		} else {
			$classes[] = 'simple-menu-item';
		}

		$classes[] = 'regular-item';

		if ( $this->is_mega_enabled( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
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

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */

		$classes = array_filter( $classes );

		array_walk( $classes, array( $this, 'modify_menu_item_classes' ) );

		$classes[] = 'jet-menu-item-' . $item->ID;

		if ( 0 < $depth ) {
			$classes[] = 'jet-sub-menu-item';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', $classes, $item, $args, $depth ) );

		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'jet-menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$link_classes = array();

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$link_classes[] = ( 0 === $depth ) ? 'top-level-link'  : 'sub-level-link';

		if ( isset( $settings['hide_item_text'] ) && 'true' === $settings['hide_item_text'] ) {
			$link_classes[] = 'label-hidden';
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

		$icon  = '';
		$desc  = '';
		$badge = '';
		$arrow = '';

		if ( ! empty( $settings['menu_icon'] ) ) {
			$icon = jet_menu_tools()->get_icon_html( $settings['menu_icon'] );
		}

		if ( ! empty( $item->description ) ) {
			$desc = sprintf(
				'<div class="jet-menu-item-desc %2$s">%1$s</div>',
				$item->description,
				( 0 === $depth ) ? 'top-level-desc'  : 'sub-level-desc'
			);
		}

		$desc_allowed = jet_menu_option_page()->get_option( 'jet-show-top-menu-desc', 'true' );
		$desc_allowed = filter_var( $desc_allowed, FILTER_VALIDATE_BOOLEAN );

		if ( 0 === $depth && ! $desc_allowed ) {
			$desc = '';
		}

		$sub_desc_allowed = jet_menu_option_page()->get_option( 'jet-show-sub-menu-desc', 'true' );
		$sub_desc_allowed = filter_var( $sub_desc_allowed, FILTER_VALIDATE_BOOLEAN );

		if ( 0 < $depth && ! $sub_desc_allowed ) {
			$desc = '';
		}

		if ( ! empty( $settings['menu_badge'] ) ) {
			$badge = jet_menu_tools()->get_badge_html( $settings['menu_badge'], $depth );
		}

		if ( isset( $settings['hide_item_text'] ) && 'true' === $settings['hide_item_text'] ) {
			$title = '';
		}

		if ( 0 === $depth ) {
			$level = 'top';
		} else {
			$level = 'sub';
		}

		if ( in_array( 'menu-item-has-children', $item->classes ) || $this->is_mega_enabled( $item->ID ) ) {
			$default_arrow = ( 0 === $depth ) ? 'fa-angle-down' : 'fa-angle-right';
			$arrow_icon    = jet_menu_option_page()->get_option( 'jet-menu-' . $level . '-arrow', $default_arrow );
			$arrow         = jet_menu_tools()->get_dropdown_arrow_html( $arrow_icon );
		}

		$title = sprintf(
			'<div class="jet-menu-item-wrapper">%1$s<div class="jet-menu-title">%2$s%3$s</div>%4$s%5$s</div>',
			$icon,
			$title,
			$desc,
			$badge,
			$arrow
		);

		$item_output .= $args->link_before . $title . $args->link_after;

		$item_output .= '</a>';
		$item_output .= $args->after;

		$is_elementor = ( isset( $_GET['elementor-preview'] ) ) ? true : false;

		$mega_item = get_post_meta( $item->ID, jet_menu_post_type()->meta_key(), true );

		if ( $this->is_mega_enabled( $item->ID ) && ! $is_elementor ) {

			$content = '';

			do_action( 'jet-menu/mega-sub-menu/before-render', $item->ID );

			if ( class_exists( 'Elementor\Plugin' ) ) {
				$elementor = Elementor\Plugin::instance();
				$content   = $elementor->frontend->get_builder_content_for_display( $mega_item );
			}

			do_action( 'jet-menu/mega-sub-menu/after-render', $item->ID );

			$item_output .= sprintf( '<div class="jet-sub-mega-menu">%s</div>', do_shortcode( $content ) );

		}

		jet_menu_tools()->add_menu_css( $item->ID, '.jet-menu-item-' . $item->ID );

		$item_output = apply_filters( 'jet-menu/main-walker/start-el', $item_output, $item, $this, $depth, $args );

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

		$item_output = "</li>{$n}";
		$item_output = apply_filters( 'jet-menu/main-walker/end-el', $item_output, $item, $this, $depth, $args );

		$output .= $item_output;

	}

	/**
	 * Modify menu item classes list
	 *
	 * @param  string &$item
	 * @return void
	 */
	public function modify_menu_item_classes( &$item, $index ) {

		if ( 0 === $index && 'menu-item' !== $item ) {
			return;
		}

		$item = 'jet-' . $item;
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

		wp_cache_set( 'item-type', $item_type, 'jet-menu' );

	}

	/**
	 * Returns current item (for top level items) or parent item (for subs) type.
	 * @return [type] [description]
	 */
	public function get_item_type() {
		return wp_cache_get( 'item-type', 'jet-menu' );
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
