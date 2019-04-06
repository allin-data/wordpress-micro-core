<?php
/**
 * Class: Jet_Switcher_Widget
 * Name: Jet_Switcher
 * Slug: jet-switcher
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Switcher_Widget extends Jet_Tabs_Base {

	public function get_name() {
		return 'jet-switcher';
	}

	public function get_title() {
		return esc_html__( 'Switcher', 'jet-tabs' );
	}

	public function get_icon() {
		return 'jettabs-icon-82';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-tabs/switcher/css-scheme',
			array(
				'instance'        => '> .elementor-widget-container > .jet-switcher',
				'control_wrapper' => '> .elementor-widget-container > .jet-switcher > .jet-switcher__control-wrapper',
				'control'         => '> .elementor-widget-container > .jet-switcher > .jet-switcher__control-wrapper > .jet-switcher__control-instance',
				'disable_control' => '> .elementor-widget-container > .jet-switcher.jet-switcher--disable > .jet-switcher__control-wrapper',
				'enable_control'  => '> .elementor-widget-container > .jet-switcher.jet-switcher--enable > .jet-switcher__control-wrapper',
				'content_wrapper' => '> .elementor-widget-container > .jet-switcher > .jet-switcher__content-wrapper',
				'content'         => '> .elementor-widget-container > .jet-switcher > .jet-switcher__content-wrapper > .jet-switcher__content',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-tabs' ),
			)
		);


		$templates = jet_tabs()->elementor()->templates_manager->get_source( 'local' )->get_items();

		if ( empty( $templates ) ) {

			$this->add_control(
				'no_templates',
				array(
					'label' => false,
					'type'  => Controls_Manager::RAW_HTML,
					'raw'   => $this->empty_templates_message(),
				)
			);

			return;
		}

		$options = [
			'0' => '— ' . esc_html__( 'Select', 'jet-tabs' ) . ' —',
		];

		$types = [];

		foreach ( $templates as $template ) {
			$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			$types[ $template['template_id'] ] = $template['type'];
		}

		$this->start_controls_tabs( 'swither_content_tabs' );

		$this->start_controls_tab(
			'swither_content_tabs_disable_state',
			array(
				'label' => esc_html__( 'Disable', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'disable_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Disable', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
			)
		);

		$this->add_control(
			'disable_content_type',
			[
				'label'       => esc_html__( 'Content Type', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Template', 'jet-tabs' ),
					'editor'   => esc_html__( 'Editor', 'jet-tabs' ),
				],
				'label_block' => 'true',
			]
		);

		$this->add_control(
			'disable_template_id',
			array(
				'label'       => esc_html__( 'Template', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => $options,
				'types'       => $types,
				'label_block' => 'true',
				'condition'   => [
					'disable_content_type' => 'template',
				]
			)
		);

		$this->add_control(
			'disable_item_editor_content',
			[
				'label'      => __( 'Content', 'jet-tabs' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Tab Item Content', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'   => [
					'disable_content_type' => 'editor',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'swither_content_tabs_enable_state',
			array(
				'label' => esc_html__( 'Enable', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'enable_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enable', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
			)
		);

		$this->add_control(
			'enable_content_type',
			[
				'label'       => esc_html__( 'Content Type', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Template', 'jet-tabs' ),
					'editor'   => esc_html__( 'Editor', 'jet-tabs' ),
				],
				'label_block' => 'true',
			]
		);

		$this->add_control(
			'enable_template_id',
			array(
				'label'       => esc_html__( 'Template', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => $options,
				'types'       => $types,
				'label_block' => 'true',
				'condition'   => [
					'enable_content_type' => 'template',
				]
			)
		);

		$this->add_control(
			'enable_item_editor_content',
			[
				'label'      => __( 'Content', 'jet-tabs' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Tab Item Content', 'jet-tabs' ),
				'dynamic' => [
					'active' => true,
				],
				'condition'   => [
					'enable_content_type' => 'editor',
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings_data',
			array(
				'label' => esc_html__( 'Settings', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'initial_state',
			array(
				'label'        => esc_html__( 'Initial State', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'jet-tabs' ),
				'label_off'    => esc_html__( 'Disable', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'switcher_preset',
			array(
				'label'   => esc_html__( 'Switcher Preset', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'preset-1',
				'options' => array(
					'preset-1' => esc_html__( 'Preset-1', 'jet-tabs' ),
					'preset-2' => esc_html__( 'Preset-2', 'jet-tabs' ),
				),
			)
		);

		$this->add_control(
			'show_effect',
			array(
				'label'   => esc_html__( 'Show Effect', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'move-up',
				'options' => array(
					'none'             => esc_html__( 'None', 'jet-tabs' ),
					'fade'             => esc_html__( 'Fade', 'jet-tabs' ),
					'zoom-in'          => esc_html__( 'Zoom In', 'jet-tabs' ),
					'zoom-out'         => esc_html__( 'Zoom Out', 'jet-tabs' ),
					'move-up'          => esc_html__( 'Move Up', 'jet-tabs' ),
					'fall-perspective' => esc_html__( 'Fall Perspective', 'jet-tabs' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_swither_control_style',
			array(
				'label'      => esc_html__( 'Switcher Control', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'swither_control_width',
			array(
				'label'   => esc_html__( 'Width', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 180,
				'min'     => 20,
				'max'     => 300,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'width: {{VALUE}}px',
				),
			)
		);

		$this->add_responsive_control(
			'swither_control_height',
			array(
				'label'   => esc_html__( 'Height', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 60,
				'min'     => 10,
				'max'     => 100,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'height: {{VALUE}}px',
				),
			)
		);

		$this->add_responsive_control(
			'swither_handler_width',
			array(
				'label'   => esc_html__( 'Handler Width', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 60,
				'min'     => 1,
				'max'     => 300,
				'step'    => 1,
				'condition' => array(
					'switcher_preset' => 'preset-2',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-switcher__control-handler' => 'width: {{VALUE}}px',
					'{{WRAPPER}} ' . $css_scheme['enable_control'] . ' .jet-switcher__control-handler' => 'left: calc(100% - {{VALUE}}px)',
				),
			)
		);

		$this->add_responsive_control(
			'swither_container_margin',
			array(
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'swither_container_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['control'],
			)
		);

		$this->add_responsive_control(
			'swither_container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'swither_container_box_shadow',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['control'],
			)
		);

		$this->add_control(
			'handler_style_heading',
			array(
				'label'     => esc_html__( 'Handler', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'swither_handler_offset',
			array(
				'label'   => esc_html__( 'Handler Offset', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 0,
				'max'     => 20,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-switcher__control-handler span' => 'margin: {{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'labels_style_heading',
			array(
				'label'     => esc_html__( 'Typography', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Labels', 'jet-tabs' ),
				'name'     => 'swither_control_labels_typography',
				'selector' => '{{WRAPPER}} '. $css_scheme['control_wrapper'] . ' .jet-switcher__label-text',
			)
		);

		$this->add_control(
			'control_state_style_heading',
			array(
				'label'     => esc_html__( 'States', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'swither_control_styles_tabs' );

		$this->start_controls_tab(
			'swither_control_styles_disable_tab',
			array(
				'label' => esc_html__( 'Disable', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'control_disable_background_color',
			array(
				'label' => esc_html__( 'Control Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['disable_control'] . ' .jet-switcher__control-instance' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_handler_disable_background_color',
			array(
				'label' => esc_html__( 'Handler Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['disable_control'] . ' .jet-switcher__control-handler span' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_disable_state_disable_label_color',
			array(
				'label' => esc_html__( 'Disable Label Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['disable_control'] . ' .jet-switcher__control--disable' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_disable_state_enable_label_color',
			array(
				'label' => esc_html__( 'Enable Label Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['disable_control'] . ' .jet-switcher__control--enable' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'swither_control_styles_enable_tab',
			array(
				'label' => esc_html__( 'Enable', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'control_enable_background_color',
			array(
				'label' => esc_html__( 'Control Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['enable_control'] . ' .jet-switcher__control-instance' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_handler_enable_background_color',
			array(
				'label' => esc_html__( 'Handler Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['enable_control'] . ' .jet-switcher__control-handler span' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_enable_state_disable_label_color',
			array(
				'label' => esc_html__( 'Disable Label Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['enable_control'] . ' .jet-switcher__control--disable' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'control_enable_state_enable_label_color',
			array(
				'label' => esc_html__( 'Enable Label Color', 'jet-tabs' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['enable_control'] . ' .jet-switcher__control--enable' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Swither Content Style Section
		 */
		$this->start_controls_section(
			'section_swither_content_style',
			array(
				'label'      => esc_html__( 'Switcher Content', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'swither_content_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			)
		);

		$this->add_responsive_control(
			'swither_content_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'swither_content_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			)
		);

		$this->add_responsive_control(
			'swither_content_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_content_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_control(
			'tabs_content_text_color',
			array(
				'label' => esc_html__( 'Text color', 'jet-tabs' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$templates = jet_tabs()->elementor()->templates_manager->get_source( 'local' )->get_items();

		if ( empty( $templates ) ) {
			return;
		}

		$widget_settings = $this->get_settings_for_display();

		$show_effect     = $widget_settings[ 'show_effect' ];
		$switcher_preset = $widget_settings[ 'switcher_preset' ];
		$initial_state   = filter_var( $widget_settings[ 'initial_state' ], FILTER_VALIDATE_BOOLEAN );

		$settings = array(
			'effect' => $widget_settings[ 'show_effect' ],
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-switcher',
				'jet-switcher--' . $switcher_preset,
				'jet-switcher-' . $show_effect . '-effect',
				$initial_state ? 'jet-switcher--enable' : 'jet-switcher--disable',
			),
			'data-settings' => json_encode( $settings ),
		) );

		?><div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-switcher__control-wrapper"><?php
				include jet_tabs()->get_template( $this->get_name() . '/global/' . $switcher_preset . '.php' );
			?></div>
			<div class="jet-switcher__content-wrapper"><?php
				$this->add_render_attribute( 'jet_switcher_content_disable', array(
					'class'    => array(
						'jet-switcher__content',
						'jet-switcher__content--disable',
						! $initial_state ? 'active-content' : '',
					),
				) );

				switch ( $widget_settings['disable_content_type'] ) {
					case 'template':
						$disable_content_html = $this->get_template_content_by_id( $widget_settings['disable_template_id'] );
						break;

					case 'editor':
						$disable_content_html = $this->parse_text_editor( $widget_settings['disable_item_editor_content'] );
						break;
				}

				echo sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'jet_switcher_content_disable' ), $disable_content_html );

				$this->add_render_attribute( 'jet_switcher_content_enable', array(
					'class'    => array(
						'jet-switcher__content',
						'jet-switcher__content--enable',
						$initial_state ? 'active-content' : '',
					),
				) );

				switch ( $widget_settings['enable_content_type'] ) {
					case 'template':
						$enable_content_html = $this->get_template_content_by_id( $widget_settings['enable_template_id'] );
						break;

					case 'editor':
						$enable_content_html = $this->parse_text_editor( $widget_settings['enable_item_editor_content'] );
						break;
				}

				echo sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'jet_switcher_content_enable' ), $enable_content_html );?></div>
		</div>
		<?php
	}

	/**
	 * [get_template_content_by_id description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function get_template_content_by_id( $template_id ) {

		if ( '0' !== $template_id ) {

			$content_html = '';

			$template_content = jet_tabs()->elementor()->frontend->get_builder_content_for_display( $template_id );

			if ( ! empty( $template_content ) ) {
				$content_html .= $template_content;

				if ( jet_tabs_integration()->is_edit_mode() ) {
					$link = add_query_arg(
						array(
							'elementor' => '',
						),
						get_permalink( $template_id )
					);

					$content_html .= sprintf( '<div class="jet-switcher__edit-cover" data-template-edit-link="%s"><i class="fa fa-pencil"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'jet-tabs' ) );
				}
			} else {
				$content_html = $this->no_template_content_message();
			}
		} else {
			$content_html = $this->no_templates_message();
		}

		return $content_html;
	}

	/**
	 * [empty_templates_message description]
	 * @return [type] [description]
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'jet-tabs' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'jet-tabs' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'jet-tabs' ) . '</a></div>
				</div>';
	}

	/**
	 * [no_templates_message description]
	 * @return [type] [description]
	 */
	public function no_templates_message() {
		$message = '<span>' . esc_html__( 'Template is not defined. ', 'jet-tabs' ) . '</span>';

		$link = add_query_arg(
			array(
				'post_type'     => 'elementor_library',
				'action'        => 'elementor_new_post',
				'_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
				'template_type' => 'section',
			),
			esc_url( admin_url( '/edit.php' ) )
		);

		$new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'jet-tabs' ) . '</span><a class="jet-switcher-new-template-link elementor-clickable" target="_blank" href="' . $link . '">' . esc_html__( 'new one', 'jet-tabs' ) . '</a>' ;

		return sprintf(
			'<div class="jet-switcher-no-template-message">%1$s%2$s</div>',
			$message,
			jet_tabs_integration()->in_elementor() ? $new_link : ''
		);
	}

	/**
	 * [no_template_content_message description]
	 * @return [type] [description]
	 */
	public function no_template_content_message() {
		$message = '<span>' . esc_html__( 'The switcher are working. Please, note, that you have to add a template to the library in order to be able to display it inside the switcher.', 'jet-tabs' ) . '</span>';

		return sprintf( '<div class="jet-toogle-no-template-message">%1$s</div>', $message );
	}

	/**
	 * [get_template_edit_link description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function get_template_edit_link( $template_id ) {

		$link = add_query_arg( 'elementor', '', get_permalink( $template_id ) );

		return '<a target="_blank" class="elementor-edit-template elementor-clickable" href="' . $link .'"><i class="fa fa-pencil"></i> ' . esc_html__( 'Edit Template', 'jet-tabs' ) . '</a>';
	}

}
