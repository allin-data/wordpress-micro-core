<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Mp_Timetable extends Jet_Elements_Base {

	public function get_name() {
		return 'mp-timetable';
	}

	public function get_title() {
		return esc_html__( 'Timetable by MotoPress', 'jet-elements' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function __tag() {
		return 'mp-timetable';
	}

	public function get_script_depends() {

		if ( ! isset( $_GET['elementor-preview'] ) ) {
			return array();
		}

		$core = \mp_timetable\plugin_core\classes\Core::get_instance();

		wp_register_script(
			'mptt-event-object',
			\Mp_Time_Table::get_plugin_url( 'media/js/events/event' . $core->get_prefix() . '.js' ),
			array( 'jquery' ),
			$core->get_version()
		);

		wp_register_script(
			'mptt-functions',
			\Mp_Time_Table::get_plugin_url( 'media/js/mptt-functions' . $core->get_prefix() . '.js' ),
			array( 'jquery', 'underscore' ),
			$core->get_version()
		);

		wp_localize_script(
			'mptt-event-object',
			'MPTT',
			array(
				'table_class' => apply_filters( 'mptt_shortcode_static_table_class', 'mptt-shortcode-table' ),
			)
		);

		return array( 'mptt-functions', 'mptt-event-object' );
	}

	/**
	 * @param array $data_array
	 * @param string $type
	 *
	 * @return array
	 */
	public function __create_list( $data_array = array(), $type = 'post' ) {
		$list_array = array();
		switch ( $type ) {
			case "post":
				foreach ( $data_array as $item ) {
					$list_array[ $item->ID ] = $item->post_title;
				}
				break;
			case "term":
				foreach ( $data_array as $item ) {
					$list_array[ $item->term_id ] = $item->name;
				}
				break;
			default:
				break;
		}

		return $list_array;
	}

	public function __atts() {

		$columns    = $this->__create_list( \mp_timetable\classes\models\Column::get_instance()->get_all_column() );
		$events     = $this->__create_list( \mp_timetable\classes\models\Events::get_instance()->get_all_events() );
		$categories = get_terms( 'mp-event_category', 'orderby=count&hide_empty=0' );
		$categories = $this->__create_list( $categories, 'term' );

		return array(
			'col' => array(
				'type'     => Controls_Manager::SELECT2,
				'label'    => __( 'Column', 'jet-elements' ),
				'multiple' => true,
				'options'  => $columns,
			),
			'events' => array(
				'type'     => Controls_Manager::SELECT2,
				'label'    => __( 'Events', 'jet-elements' ),
				'multiple' => true,
				'options'  => $events,
			),
			'event_categ'       => array(
				'type'     => Controls_Manager::SELECT2,
				'label'    => __( 'Event categories', 'jet-elements' ),
				'multiple' => true,
				'options'  => $categories,
			),
			'increment' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Hour measure', 'jet-elements' ),
				'default' => '1',
				'options' => array(
					'1'    => __( 'Hour (1h)', 'jet-elements' ),
					'0.5'  => __( 'Half hour (30min)', 'jet-elements' ),
					'0.25' => __( 'Quarter hour (15min)', 'jet-elements' ),
				),
			),
			'view' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Filter style', 'jet-elements' ),
				'default' => 'dropdown_list',
				'options' => array(
					'dropdown_list' => __( 'Dropdown list', 'jet-elements' ),
					'tabs' => __( 'Tabs', 'jet-elements' ),
				),
			),
			'label' => array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Filter label', 'jet-elements' ),
				'default' => __( 'All Events', 'jet-elements' ),
			),
			'hide_label'        => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( "Hide 'All Events' view", 'jet-elements' ),
				'default' => '0',
				'options' => array(
					'0' => __( 'No', 'jet-elements' ),
					'1' => __( 'Yes', 'jet-elements' ),
				),
			),
			'hide_hrs' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Hide first (hours) column', 'jet-elements' ),
				'default' => '0',
				'options' => array(
					'0' => __( 'No', 'jet-elements' ),
					'1' => __( 'Yes', 'jet-elements' ),
				),
			),
			'hide_empty_rows' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Hide empty rows', 'jet-elements' ),
				'default' => '1',
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
				'default' => 1,
			),
			'title' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Title', 'jet-elements' ),
				'default' => 1,
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
			),
			'time' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Time', 'jet-elements' ),
				'default' => 1,
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
			),
			'sub-title' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Subtitle', 'jet-elements' ),
				'default' => 1,
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
			),
			'description' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Description', 'jet-elements' ),
				'default' => 0,
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
			),
			'user' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'User', 'jet-elements' ),
				'default' => 0,
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
			),
			'disable_event_url' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Disable event URL', 'jet-elements' ),
				'default' => '0',
				'options' => array(
					'0' => __( 'No', 'jet-elements' ),
					'1' => __( 'Yes', 'jet-elements' ),
				),
			),
			'text_align' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Text align', 'jet-elements' ),
				'default' => 'center',
				'options' => array(
					'center' => __( 'center', 'jet-elements' ),
					'left'   => __( 'left', 'jet-elements' ),
					'right'  => __( 'right', 'jet-elements' ),
				),
			),
			'css_id' => array(
				'type'  => Controls_Manager::TEXT,
				'label' => __( 'Id', 'jet-elements' )
			),
			'row_height' => array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Row height (in px)', 'jet-elements' ),
				'default' => 45
			),
			'font_size' => array(
				'type'    => Controls_Manager::TEXT,
				'label'   => __( 'Base Font Size', 'jet-elements' ),
				'default' => ''
			),
			'responsive' => array(
				'type'    => Controls_Manager::SELECT,
				'label'   => __( 'Responsive', 'jet-elements' ),
				'default' => '1',
				'options' => array(
					'1' => __( 'Yes', 'jet-elements' ),
					'0' => __( 'No', 'jet-elements' ),
				),
				'default' => 1,
			)
		);
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		foreach ( $this->__atts() as $control => $data ) {
			$this->add_control( $control, $data );
		}

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();

		$this->__context = 'render';

		$this->__open_wrap();

		$attributes = '';

		foreach ( $this->__atts() as $attr => $data ) {

			$attr_val    = $settings[ $attr ];
			$attr_val    = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );

			if ( 'css_id' === $attr ) {
				$attr = 'id';
			}

			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->__tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->__close_wrap();

	}

}
