<?php
/**
 * Class: Jet_Blocks_Hamburger_Panel
 * Name: Hamburger Panel
 * Slug: jet-hamburger-panel
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

class Jet_Blocks_Hamburger_Panel extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-hamburger-panel';
	}

	public function get_title() {
		return esc_html__( 'Hamburger Panel', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-5';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-blocks/hamburger-panel/css-scheme',
			array(
				'panel'    => '.jet-hamburger-panel',
				'instance' => '.jet-hamburger-panel__instance',
				'inner'    => '.jet-hamburger-panel__inner',
				'content'  => '.jet-hamburger-panel__content',
				'close'    => '.jet-hamburger-panel__close-button',
				'toggle'   => '.jet-hamburger-panel__toggle',
				'icon'     => '.jet-hamburger-panel__icon',
				'label'    => '.jet-hamburger-panel__toggle-label',
			)
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'panel_toggle_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-align-justify',
			)
		);

		$this->add_control(
			'panel_toggle_active_icon',
			array(
				'label'       => esc_html__( 'Active Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-close',
			)
		);

		$this->add_control(
			'panel_close_icon',
			array(
				'label'       => esc_html__( 'Close Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-close',
			)
		);

		$this->add_control(
			'panel_toggle_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'More', 'jet-blocks' ),
			)
		);

		$this->add_responsive_control(
			'panel_toggle_label_alignment',
			array(
				'label'   => esc_html__( 'Toggle Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} ' . $css_scheme['panel'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$templates = jet_blocks()->elementor()->templates_manager->get_source( 'local' )->get_items();

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
			'0' => '— ' . esc_html__( 'Select', 'jet-blocks' ) . ' —',
		];

		$types = [];

		foreach ( $templates as $template ) {
			$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			$types[ $template['template_id'] ] = $template['type'];
		}

		$this->add_control(
			'panel_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => $options,
				'types'       => $types,
				'label_block' => 'true',
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
			'position',
			array(
				'label'       => esc_html__( 'Position', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'options' => array(
					'right' => esc_html__( 'Right', 'jet-blocks' ),
					'left'  => esc_html__( 'Left', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'animation_effect',
			array(
				'label'       => esc_html__( 'Effect', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'jet-blocks' ),
					'fade'  => esc_html__( 'Fade', 'jet-blocks' ),
					'zoom'  => esc_html__( 'Zoom', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'z_index',
			array(
				'label'   => esc_html__( 'Z-Index', 'jet-blocks' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100000,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'z-index: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_style',
			array(
				'label'      => esc_html__( 'Panel', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'panel_width',
			array(
				'label'      => esc_html__( 'Panel Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 250,
						'max' => 800,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'panel_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'panel_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
			)
		);

		$this->add_control(
			'panel_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'panel_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			)
		);

		$this->add_responsive_control(
			'panel_padding',
			array(
				'label'      => __( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'close_button_style_heading',
			array(
				'label'     => esc_html__( 'Close Button', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'close_button_styles' );

		$this->start_controls_tab(
			'close_button_control',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Close Icon', 'jet-blocks' ),
				'name'     => 'close_icon_box',
				'selector' => '{{WRAPPER}} ' . $css_scheme['close'] . ' i',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'close_button_control_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Close Icon', 'jet-blocks' ),
				'name'     => 'close_icon_box_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['close'] . ':hover i',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_panel_toggle_style',
			array(
				'label'      => esc_html__( 'Toggle', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'toggle_styles' );

		$this->start_controls_tab(
			'toggle_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background_hover',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_control(
			'toggle_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_icon_style_heading',
			array(
				'label'     => esc_html__( 'Icon Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'toggle_icon_styles' );

		$this->start_controls_tab(
			'toggle_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Toggle Icon', 'jet-blocks' ),
				'name'     => 'toggle_icon_box',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Toggle Icon', 'jet-blocks' ),
				'name'     => 'toggle_icon_box_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['icon'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_label_style_heading',
			array(
				'label'     => esc_html__( 'Label Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'toggle_label_styles' );

		$this->start_controls_tab(
			'toggle_label_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'toggle_control_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['label'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_label_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'toggle_control_label_color_hover',
			array(
				'label'     => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$panel_settings = $this->get_settings();

		$template_id      = isset( $panel_settings['panel_template_id'] ) ? $panel_settings['panel_template_id'] : '0';
		$position         = isset( $panel_settings['position'] ) ? $panel_settings['position'] : 'right';
		$animation_effect = isset( $panel_settings['animation_effect'] ) ? $panel_settings['animation_effect'] : 'slide';

		$settings = array(
			'position' => $position,
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-hamburger-panel',
				//'jet-hamburger-panel-' . $panel_settings['type'] . '-type',
				'jet-hamburger-panel-' . $position . '-position',
				'jet-hamburger-panel-' . $animation_effect . '-effect',
			),
			'data-settings' => json_encode( $settings ),
		) );

		$close_button_html = '';

		if ( isset( $panel_settings['panel_close_icon'] ) ) {
			$close_button_html .= sprintf( '<div class="jet-hamburger-panel__close-button"><i class="%1$s"></i></div>', $panel_settings['panel_close_icon'] );
		}

		$toggle_control_html = '';

		if ( ! empty( $panel_settings['panel_toggle_icon'] ) && ! empty( $panel_settings['panel_toggle_active_icon'] ) ) {
			$toggle_control_html .= sprintf( '<div class="jet-hamburger-panel__toggle-icon"><i class="jet-hamburger-panel__icon icon-normal %1$s"></i><i class="jet-hamburger-panel__icon icon-active %2$s"></i></div>',
			$panel_settings['panel_toggle_icon'],
			$panel_settings['panel_toggle_active_icon'] );
		}

		$toggle_label_html = '';

		if ( ! empty( $panel_settings['panel_toggle_label'] ) ) {
			$toggle_label_html .= sprintf( '<div class="jet-hamburger-panel__toggle-label"><span>%1$s</span></div>', $panel_settings['panel_toggle_label'] );
		}

		$toggle_html = sprintf( '<div class="jet-hamburger-panel__toggle">%1$s%2$s</div>', $toggle_control_html, $toggle_label_html );

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<?php echo $toggle_html; ?>
			<div class="jet-hamburger-panel__instance">
				<div class="jet-hamburger-panel__cover"></div>
				<div class="jet-hamburger-panel__inner">
					<?php
						echo $close_button_html;

						if ( '0' !== $template_id ) {
							$link = add_query_arg(
								array(
									'elementor' => '',
								),
								get_permalink( $template_id )
							);

							if ( jet_blocks_integration()->in_elementor() ) {
								echo sprintf( '<div class="jet-blocks__edit-cover" data-template-edit-link="%s"><i class="fa fa-pencil"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'jet-blocks' ) );
							}
						}

					?>
					<div class="jet-hamburger-panel__content"><?php
						$content_html = '';

						if ( '0' !== $template_id ) {
							$content_html .= jet_blocks()->elementor()->frontend->get_builder_content_for_display( $template_id );
						} else {
							$content_html = $this->no_templates_message();
						}

						echo $content_html;
					?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Empty templates message description
	 *
	 * @return string
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'jet-blocks' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'jet-blocks' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'jet-blocks' ) . '</a></div>
				</div>';
	}

	/**
	 * No templates message
	 *
	 * @return string
	 */
	public function no_templates_message() {
		$message = '<span>' . esc_html__( 'Template is not defined. ', 'jet-blocks' ) . '</span>';

		$url = add_query_arg(
			array(
				'post_type'     => 'elementor_library',
				'action'        => 'elementor_new_post',
				'_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
				'template_type' => 'section',
			),
			esc_url( admin_url( '/edit.php' ) )
		);

		$new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'jet-blocks' ) . '</span><a class="jet-blocks-new-template-link elementor-clickable" href="' . $url . '" target="_blank">' . esc_html__( 'new one', 'jet-blocks' ) . '</a>' ;

		return sprintf(
			'<div class="jet-blocks-no-template-message">%1$s%2$s</div>',
			$message,
			jet_blocks_integration()->in_elementor() ? $new_link : ''
		);
	}

}


