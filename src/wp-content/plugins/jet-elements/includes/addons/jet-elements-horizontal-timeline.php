<?php
/**
 * Class: Jet_Elements_Horizontal_Timeline
 * Name: Horizontal Timeline
 * Slug: jet-horizontal-timeline
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Elements_Horizontal_Timeline extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-horizontal-timeline';
	}

	public function get_title() {
		return esc_html__( 'Horizontal Timeline', 'jet-elements' );
	}

	public function get_icon() {
		return 'jetelements-icon-45';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/horizontal-timeline/css-scheme',
			array(
				'track'              => '.jet-hor-timeline-track',
				'line'               => '.jet-hor-timeline__line',
				'progress'           => '.jet-hor-timeline__line-progress',
				'item'               => '.jet-hor-timeline-item',
				'item_point'         => '.jet-hor-timeline-item__point',
				'item_point_content' => '.jet-hor-timeline-item__point-content',
				'item_meta'          => '.jet-hor-timeline-item__meta',
				'card'               => '.jet-hor-timeline-item__card',
				'card_inner'         => '.jet-hor-timeline-item__card-inner',
				'card_img'           => '.jet-hor-timeline-item__card-img',
				'card_title'         => '.jet-hor-timeline-item__card-title',
				'card_desc'          => '.jet-hor-timeline-item__card-desc',
				'card_arrow'         => '.jet-hor-timeline-item__card-arrow',
				'arrow'              => '.jet-hor-timeline .jet-arrow',
				'prev_arrow'         => '.jet-hor-timeline .jet-arrow.jet-prev-arrow',
				'next_arrow'         => '.jet-hor-timeline .jet-arrow.jet-next-arrow',
			)
		);

		$this->start_controls_section(
			'section_items',
			array(
				'label' => esc_html__( 'Items', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'is_item_active',
			array(
				'label'   => esc_html__( 'Active', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater->add_control(
			'show_item_image',
			array(
				'label'   => esc_html__( 'Show Image', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'show_item_image' => 'yes'
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'item_image',
				'default'   => 'medium',
				'condition' => array(
					'show_item_image' => 'yes'
				),
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_meta',
			array(
				'label'   => esc_html__( 'Meta', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_point',
			array(
				'label'     => esc_html__( 'Point', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'item_point_type',
			array(
				'label'   => esc_html__( 'Point Content Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon' => esc_html__( 'Icon', 'jet-elements' ),
					'text' => esc_html__( 'Text', 'jet-elements' ),
				),
			)
		);

		$repeater->add_control(
			'item_point_icon',
			array(
				'label'       => esc_html__( 'Point Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-calendar',
				'condition'   => array(
					'item_point_type' => 'icon'
				)
			)
		);

		$repeater->add_control(
			'item_point_text',
			array(
				'label'     => esc_html__( 'Point Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'A',
				'condition' => array(
					'item_point_type' => 'text'
				)
			)
		);

		$this->add_control(
			'cards_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'is_item_active'  => 'yes',
						'item_title'      => esc_html__( 'Card #1', 'jet-elements' ),
						'item_title_icon' => 'fa fa-birthday-cake',
						'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_point_icon' => 'fa fa-calendar',
						'item_meta'       => esc_html__( 'Thursday, August 31, 2018', 'jet-elements' ),
					),
					array(
						'item_title'      => esc_html__( 'Card #2', 'jet-elements' ),
						'item_title_icon' => 'fa fa-birthday-cake',
						'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_point_icon' => 'fa fa-calendar',
						'item_meta'       => esc_html__( 'Thursday, August 29, 2018', 'jet-elements' ),
					),
					array(
						'item_title'      => esc_html__( 'Card #3', 'jet-elements' ),
						'item_title_icon' => 'fa fa-birthday-cake',
						'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_point_icon' => 'fa fa-calendar',
						'item_meta'       => esc_html__( 'Thursday, August 28, 2018', 'jet-elements' ),
					),
					array(
						'item_title'      => esc_html__( 'Card #4', 'jet-elements' ),
						'item_title_icon' => 'fa fa-birthday-cake',
						'item_desc'       => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_point_icon' => 'fa fa-calendar',
						'item_meta'       => esc_html__( 'Thursday, August 27, 2018', 'jet-elements' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'item_title_size',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => esc_html__( 'H1', 'jet-elements' ),
					'h2'   => esc_html__( 'H2', 'jet-elements' ),
					'h3'   => esc_html__( 'H3', 'jet-elements' ),
					'h4'   => esc_html__( 'H4', 'jet-elements' ),
					'h5'   => esc_html__( 'H5', 'jet-elements' ),
					'h6'   => esc_html__( 'H6', 'jet-elements' ),
					'div'  => esc_html__( 'div', 'jet-elements' ),
					'span' => esc_html__( 'span', 'jet-elements' ),
					'p'    => esc_html__( 'p', 'jet-elements' ),
				),
				'default' => 'h5',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_card_arrows',
			array(
				'label'   => esc_html__( 'Show Card Arrows', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'jet-elements' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'flex: 0 0 calc(100%/{{VALUE}}); max-width: calc(100%/{{VALUE}});',
				),
				'render_type'    => 'template',
			)
		);

		$this->add_control(
			'vertical_layout',
			array(
				'label'   => esc_html__( 'Layout', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'toggle'  => false,
				'default' => 'top',
				'options' => array(
					'top' => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'chess' => array(
						'title' => esc_html__( 'Chess', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
			)
		);

		$this->add_control(
			'horizontal_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'toggle'  => false,
				'default' => 'left',
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->add_control(
			'navigation_type',
			array(
				'label'   => esc_html__( 'Navigation Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scroll-bar',
				'options' => array(
					'scroll-bar' => esc_html__( 'Scroll Bar', 'jet-elements' ),
					'arrows-nav' => esc_html__( 'Arrows Navigation', 'jet-elements' ),
				)
			)
		);

		$this->add_control(
			'arrow_type',
			array(
				'label'   => esc_html__( 'Arrow Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fa fa-angle-left',
				'options' => jet_elements_tools()->get_available_prev_arrows_list(),
				'condition' => array(
					'navigation_type' => 'arrows-nav',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `General` Style Section
		 */
		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => esc_html__( 'General', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label' => esc_html__( 'Items Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
				),
				'render_type' => 'template',
			)
		);

		$this->end_controls_section();

		/**
		 * `Cards` Style Section
		 */
		$this->start_controls_section(
			'section_cards_style',
			array(
				'label' => esc_html__( 'Cards', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cards_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
			)
		);

		$this->add_responsive_control(
			'cards_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cards_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cards_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-hor-timeline-list--top ' . $css_scheme['card'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-hor-timeline-list--bottom ' . $css_scheme['card'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after'
			)
		);

		$this->start_controls_tabs( 'cards_style_tabs' );

		$this->start_controls_tab(
			'cards_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'cards_background_normal',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['card_arrow'],
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cards_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'cards_background_hover',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cards_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
				),
				'condition' => array(
					'cards_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_arrow'],
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cards_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'cards_background_active',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] . ':before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cards_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'cards_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card'] . ', {{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_arrow'],
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'cards_arrow_heading',
			array(
				'label'     => esc_html__( 'Arrow', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_card_arrows' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'cards_arrow_width',
			array(
				'label' => esc_html__( 'Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'show_card_arrows' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'cards_arrow_offset',
			array(
				'label' => esc_html__( 'Offset', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-hor-timeline--align-left ' . $css_scheme['card_arrow'] => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-hor-timeline--align-right ' . $css_scheme['card_arrow'] => 'right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'show_card_arrows' => 'yes',
					'horizontal_alignment!' => 'center',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Cards Content` Style Section
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Cards Content', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'cards_content_align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_inner'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_heading',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_stretch',
			array(
				'label'   => esc_html__( 'Stretch Image', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_img'] . ' img' => 'width: 100%;',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['card_title'],
			)
		);

		$this->add_responsive_control(
			'card_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'card_title_style_tabs' );

		$this->start_controls_tab(
			'card_title_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_title_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_title_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_title_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_title_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_title'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['card_desc'],
			)
		);

		$this->add_responsive_control(
			'card_desc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'card_desc_style_tabs' );

		$this->start_controls_tab(
			'card_desc_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_desc_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_desc_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_desc_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_desc_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'card_desc_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['card_desc'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'orders_heading',
			array(
				'label'     => esc_html__( 'Orders', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'image_order',
			array(
				'label' => esc_html__( 'Image Order', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 10,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_img'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_order',
			array(
				'label' => esc_html__( 'Title Order', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 10,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_title'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_order',
			array(
				'label' => esc_html__( 'Description Order', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 10,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_desc'] => 'order: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Meta` Style Section
		 */
		$this->start_controls_section(
			'section_meta_style',
			array(
				'label' => esc_html__( 'Meta', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} ' .  $css_scheme['item_meta'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'meta_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item_meta'],
			)
		);

		$this->add_control(
			'meta_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'meta_spacing',
			array(
				'label' => esc_html__( 'Spacing', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-hor-timeline-list--top ' . $css_scheme['item_meta'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-hor-timeline-list--bottom ' . $css_scheme['item_meta'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after'
			)
		);

		$this->start_controls_tabs( 'meta_style_tabs' );

		$this->start_controls_tab(
			'meta_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'meta_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_normal_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_meta'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'meta_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'meta_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'meta_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_meta'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'meta_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'meta_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'meta_border_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_active_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_meta'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Point` Style Section
		 */
		$this->start_controls_section(
			'section_point_style',
			array(
				'label' => esc_html__( 'Point', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'point_type_style_tabs' );

		$this->start_controls_tab(
			'point_type_text_styles',
			array(
				'label' => esc_html__( 'Text', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'point_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'point_type_icon_styles',
			array(
				'label' => esc_html__( 'Icon', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'point_type_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'point_size',
			array(
				'label' => esc_html__( 'Point Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
				'render_type' => 'template',
			)
		);

		$this->add_responsive_control(
			'point_offset',
			array(
				'label' => esc_html__( 'Offset', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-hor-timeline--align-left ' . $css_scheme['item_point_content'] => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-hor-timeline--align-right ' . $css_scheme['item_point_content'] => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'horizontal_alignment!' => 'center',
				),
				'render_type' => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'point_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
			)
		);

		$this->add_control(
			'point_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'point_style_tabs' );

		$this->start_controls_tab(
			'point_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'point_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'point_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'point_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'point_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'point_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'point_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-hover ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'point_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'point_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->add_control(
			'point_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'point_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'point_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is-active ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'point_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Line` Style Section
		 */
		$this->start_controls_section(
			'section_line_style',
			array(
				'label' => esc_html__( 'Line', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'line_background_color',
			array(
				'label'     => esc_html__( 'Line Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}};',
				),
			)
		);

//		$this->add_control(
//			'progress_background_color',
//			array(
//				'label'     => esc_html__( 'Progress Color', 'jet-elements' ),
//				'type'      => Controls_Manager::COLOR,
//				'selectors' => array(
//					'{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}};',
//				),
//			)
//		);

		$this->add_responsive_control(
			'line_height',
			array(
				'label' => esc_html__( 'Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['line'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Scrollbar` Style Section
		 */
		$this->start_controls_section(
			'section_scrollbar_style',
			array(
				'label' => esc_html__( 'Scrollbar', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation_type' => 'scroll-bar',
				),
			)
		);

		$this->add_control(
			'non_webkit_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Currently works only in -webkit- browsers', 'jet-elements' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'scrollbar_bg',
			array(
				'label'     => esc_html__( 'Scrollbar Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'scrollbar_thumb_bg',
			array(
				'label'     => esc_html__( 'Scrollbar Thumb Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'scrollbar_height',
			array(
				'label' => esc_html__( 'Scrollbar Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'scrollbar_offset',
			array(
				'label' => esc_html__( 'Scrollbar Offset', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['track'] => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'scrollbar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['track'] . '::-webkit-scrollbar-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Arrows` Style Section
		 */
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => esc_html__( 'Arrows', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation_type' => 'arrows-nav',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_prev',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'arrows_style',
				'label'    => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_next_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'arrows_hover_style',
				'label'    => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['arrow'] . ':not(.jet-arrow-disabled):hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'prev_arrow_position',
			array(
				'label'     => esc_html__( 'Prev Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'prev_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
				'render_type'=> 'ui',
			)
		);

		$this->add_responsive_control(
			'prev_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'prev_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' .  $css_scheme['prev_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->add_control(
			'next_arrow_position',
			array(
				'label'     => esc_html__( 'Next Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'next_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
				'render_type'=> 'ui',
			)
		);

		$this->add_responsive_control(
			'next_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'next_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' .  $css_scheme['next_arrow'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->__context = 'render';

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();
	}

	public function __render_image( $item_settings ) {
		$show_image = filter_var( $item_settings['show_item_image'], FILTER_VALIDATE_BOOLEAN );

		if ( ! $show_image || empty( $item_settings['item_image']['url'] ) ) {
			return;
		}

		$img_html = Group_Control_Image_Size::get_attachment_image_html( $item_settings, 'item_image' );

		$image_format = apply_filters( 'jet-elements/horizontal-timeline/image-format', '<div class="jet-hor-timeline-item__card-img">%s</div>' );

		printf( $image_format, $img_html );
	}

	public function __render_point_content( $item_settings ) {
		echo '<div class="jet-hor-timeline-item__point">';
		echo '<div class="jet-hor-timeline-item__point-content">';
		switch ( $item_settings['item_point_type'] ) {
			case 'icon':
				echo $this->__loop_item( array( 'item_point_icon' ), '<i class="%s"></i>' );
				break;
			case 'text':
				echo $this->__loop_item( array( 'item_point_text' ), '%s' );
				break;
		}
		echo '</div>';
		echo '</div>';
	}
}
