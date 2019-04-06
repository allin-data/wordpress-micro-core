<?php
/**
 * Class: Jet_Blocks_Logo
 * Name: Logo
 * Slug: jet-logo
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
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Blocks_Logo extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-1';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'logo_type',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Logo Type', 'jet-blocks' ),
				'default' => 'text',
				'options' => array(
					'text'  => esc_html__( 'Text', 'jet-blocks' ),
					'image' => esc_html__( 'Image', 'jet-blocks' ),
					'both'  => esc_html__( 'Both Text and Image', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'logo_image',
			array(
				'label'     => esc_html__( 'Logo Image', 'jet-blocks' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'logo_type!' => 'text',
				),
			)
		);

		$this->add_control(
			'logo_image_2x',
			array(
				'label'     => esc_html__( 'Retina Logo Image', 'jet-blocks' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'logo_type!' => 'text',
				),
			)
		);

		$this->add_control(
			'logo_text_from',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Logo Text From', 'jet-blocks' ),
				'default'    => 'site_name',
				'options'    => array(
					'site_name' => esc_html__( 'Site Name', 'jet-blocks' ),
					'custom'    => esc_html__( 'Custom', 'jet-blocks' ),
				),
				'condition' => array(
					'logo_type!' => 'image',
				),
			)
		);

		$this->add_control(
			'logo_text',
			array(
				'label'     => esc_html__( 'Custom Logo Text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'logo_text_from' => 'custom',
					'logo_type!'     => 'image',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'linked_logo',
			array(
				'label'        => esc_html__( 'Linked Logo', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'remove_link_on_front',
			array(
				'label'        => esc_html__( 'Remove Link on Front Page', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'logo_display',
			array(
				'type'        => 'select',
				'label'       => esc_html__( 'Display Logo Image and Text', 'jet-blocks' ),
				'label_block' => true,
				'default'     => 'block',
				'options'     => array(
					'inline' => esc_html__( 'Inline', 'jet-blocks' ),
					'block'  => esc_html__( 'Text Below Image', 'jet-blocks' ),
				),
				'condition' => array(
					'logo_type' => 'both',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'logo_style',
			array(
				'label'      => esc_html__( 'Logo', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'logo_alignment',
			array(
				'label'   => esc_html__( 'Logo Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-logo' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'vertical_logo_alignment',
			array(
				'label'       => esc_html__( 'Image and Text Vertical Alignment', 'jet-blocks' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'label_block' => true,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'jet-blocks' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'jet-blocks' ),
						'icon' => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-blocks' ),
						'icon' => 'eicon-v-align-bottom',
					),
					'baseline' => array(
						'title' => esc_html__( 'Baseline', 'jet-blocks' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-logo__link' => 'align-items: {{VALUE}}',
				),
				'condition' => array(
					'logo_type'    => 'both',
					'logo_display' => 'inline',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_logo_style',
			array(
				'label'      => esc_html__( 'Text', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'text_logo_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-logo__text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_logo_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .jet-logo__text',
			)
		);

		$this->add_control(
			'text_logo_gap',
			array(
				'label'      => esc_html__( 'Gap', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 5,
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-logo-display-block .jet-logo__img'  => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-logo-display-inline .jet-logo__img' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'logo_type' => 'both',
				),
			)
		);

		$this->add_responsive_control(
			'text_logo_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-logo__text' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'logo_type'    => 'both',
					'logo_display' => 'block',
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

	/**
	 * Check if logo is linked
	 * @return [type] [description]
	 */
	public function __is_linked() {

		$settings = $this->get_settings();

		if ( empty( $settings['linked_logo'] ) ) {
			return false;
		}

		if ( 'true' === $settings['remove_link_on_front'] && is_front_page() ) {
			return false;
		}

		return true;

	}

	/**
	 * Returns logo text
	 *
	 * @return string Text logo HTML markup.
	 */
	public function __get_logo_text() {

		$settings    = $this->get_settings();
		$type        = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
		$text_from   = isset( $settings['logo_text_from'] ) ? esc_attr( $settings['logo_text_from'] ) : 'site_name';
		$custom_text = isset( $settings['logo_text'] ) ? esc_attr( $settings['logo_text'] ) : '';

		if ( 'image' === $type ) {
			return;
		}

		if ( 'site_name' === $text_from ) {
			$text = get_bloginfo( 'name' );
		} else {
			$text = $custom_text;
		}

		$format = apply_filters(
			'jet-blocks/widgets/logo/text-foramt',
			'<div class="jet-logo__text">%s</div>'
		);

		return sprintf( $format, $text );
	}

	/**
	 * Returns logo classes string
	 *
	 * @return string
	 */
	public function __get_logo_classes() {

		$settings = $this->get_settings();

		$classes = array(
			'jet-logo',
			'jet-logo-type-' . $settings['logo_type'],
			'jet-logo-display-' . $settings['logo_display'],
		);

		return implode( ' ', $classes );
	}

	/**
	 * Returns logo image
	 *
	 * @return string Image logo HTML markup.
	 */
	public function __get_logo_image() {

		$settings = $this->get_settings();
		$type     = isset( $settings['logo_type'] ) ? esc_attr( $settings['logo_type'] ) : 'text';
		$image    = isset( $settings['logo_image'] ) ? $settings['logo_image'] : false;
		$image_2x = isset( $settings['logo_image_2x'] ) ? $settings['logo_image_2x'] : false;

		if ( 'text' === $type || ! $image ) {
			return;
		}

		if ( empty( $image['url'] ) && empty( $image_2x['url'] ) ) {
			return;
		}

		$format = apply_filters(
			'jet-blocks/widgets/logo/image-format',
			'<img src="%1$s" class="jet-logo__img" alt="%2$s"%3$s>'
		);

		$image_data = wp_get_attachment_image_src( $image['id'], 'full' );
		$width      = isset( $image_data[1] ) ? $image_data[1] : false;
		$height     = isset( $image_data[2] ) ? $image_data[2] : false;

		$attrs = sprintf(
			'%1$s%2$s%3$s',
			$width ? ' width="' . $width . '"' : '',
			$height ? ' height="' . $height . '"' : '',
			( ! empty( $image_2x['url'] ) ? ' srcset="' . $image_2x['url'] . ' 2x"' : '' )
		);

		return sprintf( $format, $image['url'], get_bloginfo( 'name' ), $attrs );
	}

}
