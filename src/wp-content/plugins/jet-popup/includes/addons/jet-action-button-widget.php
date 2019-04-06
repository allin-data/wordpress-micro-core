<?php
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

class Jet_Action_Button_Widget extends Jet_Popup_Base {

	public function get_name() {
		return 'jet-popup-action-button';
	}

	public function get_title() {
		return esc_html__( 'Popup Action Button', 'jet-popup' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return array( 'jet-popup' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-popup/popup-action-button/css-scheme',
			array(
				'button'   => '.jet-popup-action-button',
				'instance' => '.jet-popup-action-button__instance',
				'text'     => '.jet-popup-action-button__text',
				'icon'     => '.jet-popup-action-button__icon',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-popup' ),
			)
		);

		$this->add_control(
			'button_action_type',
			[
				'label'   => __( 'Action Type', 'jet-popup' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => [
					'link'             => esc_html__( 'Link', 'jet-popup' ),
					'leave'            => esc_html__( 'Leave Page', 'jet-popup' ),
					'close-popup'      => esc_html__( 'Close Popup', 'jet-popup' ),
					'close-constantly' => esc_html__( 'Close Сonstantly', 'jet-popup' ),
				],
			]
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button text', 'jet-popup' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'jet-popup' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'jet-popup' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '#',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Action Button Style Section
		 */
		$this->start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'General', 'jet-popup' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-popup' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'jet-popup' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-popup' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-popup' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'add_button_icon',
			array(
				'label'        => esc_html__( 'Add Icon', 'jet-popup' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-popup' ),
				'label_off'    => esc_html__( 'No', 'jet-popup' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-popup' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-check',
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-popup' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'before' => esc_html__( 'Before Text', 'jet-popup' ),
					'after'  => esc_html__( 'After Text', 'jet-popup' ),
				),
				'default'     => 'after',
				'render_type' => 'template',
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'      => __( 'Icon Margin', 'jet-popup' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-popup' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-popup' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-popup' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ' . $css_scheme['text'],
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-popup' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Jet_Popup_Group_Control_Box_Style::get_type(),
			[
				'name'     => 'button_icon_box',
				'label'    => esc_html__( 'Icon Styles', 'jet-popup' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ' ' . $css_scheme['icon'],
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			]
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-popup' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-popup' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ':hover ' . $css_scheme['text'],
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Jet_Popup_Group_Control_Box_Style::get_type(),
			[
				'name'     => 'button_icon_box_hover',
				'label'    => esc_html__( 'Icon Styles', 'jet-popup' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ':hover ' . $css_scheme['icon'],
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			]
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-popup' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ':hover ' . $css_scheme['text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-popup' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$settings = $this->get_settings();

		$position    = $this->get_settings( 'button_icon_position' );
		$use_icon    = $this->get_settings( 'add_button_icon' );
		$button_icon = $this->get_settings( 'button_icon' );
		$button_text = $this->get_settings( 'button_text' );
		$button_url  = $this->get_settings( 'button_link' );

		if ( empty( $button_url ) ) {
			return false;
		}

		$json_settings = array(
			'action-type'   => $settings['button_action_type'],
		);

		$this->add_render_attribute( 'instance', 'class', array(
			'jet-popup-action-button__instance',
			'jet-popup-action-button--icon-' . $position,
		) );

		$this->add_render_attribute( 'instance', 'data-settings', htmlspecialchars( json_encode( $json_settings ) ) );

		$this->add_render_attribute( 'instance', 'href', $button_url['url'] );

		if ( $button_url['is_external'] ) {
			$this->add_render_attribute( 'instance', 'target', '_blank' );
		}

		if ( ! empty( $button_url['nofollow'] ) ) {
			$this->add_render_attribute( 'instance', 'rel', 'nofollow' );
		}

		?>
		<div class="jet-popup-action-button">
			<a <?php echo $this->get_render_attribute_string( 'instance' ); ?>><?php
				if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
					echo sprintf( '<i class="jet-popup-action-button__icon %s"></i>', $button_icon );
				}
				echo sprintf( '<span class="jet-popup-action-button__text">%s</span>', $button_text );?>
			</a>
		</div><?php
	}
}
