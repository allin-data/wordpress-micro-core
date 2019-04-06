<?php
/**
 * Class: Jet_Blocks_Register
 * Name: Register
 * Slug: jet-register
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

class Jet_Blocks_Register extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-register';
	}

	public function get_title() {
		return esc_html__( 'Registration Form', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-3';
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
			'label_username',
			array(
				'label'   => esc_html__( 'Username Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Username', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'placeholder_username',
			array(
				'label'   => esc_html__( 'Username Placeholder', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Username', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'label_email',
			array(
				'label'   => esc_html__( 'Email Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'placeholder_email',
			array(
				'label'   => esc_html__( 'Email Placeholder', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'label_pass',
			array(
				'label'   => esc_html__( 'Password Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'placeholder_pass',
			array(
				'label'   => esc_html__( 'Password Placeholder', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'confirm_password',
			array(
				'label'        => esc_html__( 'Show Confirm Password Field', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_pass_confirm',
			array(
				'label'     => esc_html__( 'Confirm Password Label', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Please Confirm Password', 'jet-blocks' ),
				'condition' => array(
					'confirm_password' => 'yes'
				)
			)
		);

		$this->add_control(
			'placeholder_pass_confirm',
			array(
				'label'     => esc_html__( 'Confirm Password Placeholder', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Confirm Password', 'jet-blocks' ),
				'condition' => array(
					'confirm_password' => 'yes'
				)
			)
		);

		$this->add_control(
			'label_submit',
			array(
				'label'   => esc_html__( 'Register Button Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Register', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'register_redirect',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Redirect After Register', 'jet-blocks' ),
				'default'    => 'home',
				'options'    => array(
					'home'   => esc_html__( 'Home page', 'jet-blocks' ),
					'left'   => esc_html__( 'Stay on the current page', 'jet-blocks' ),
					'custom' => esc_html__( 'Custom URL', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'register_redirect_url',
			array(
				'label'     => esc_html__( 'Redirect URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'register_redirect' => 'custom',
				),
			)
		);

		$this->add_control(
			'label_registered',
			array(
				'label'   => esc_html__( 'User Registered Message', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'You already registered', 'jet-blocks' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'register_fields_style',
			array(
				'label'      => esc_html__( 'Fields', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'input_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__input' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__input' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .jet-register__input',
			)
		);

		$this->add_control(
			'placeholder_style',
			array(
				'label'     => esc_html__( 'Placeholder', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'  => esc_html__( 'Placeholder Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__input::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .jet-register__input::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-register__input:-ms-input-placeholder'      => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_placeholder_typography',
				'selector' => '{{WRAPPER}} .jet-register__input::-webkit-input-placeholder',
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'input_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-register__input',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .jet-register__input',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'register_labels_style',
			array(
				'label'      => esc_html__( 'Labels', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'labels_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__label' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'labels_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} .jet-register__label',
			)
		);

		$this->add_responsive_control(
			'labels_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'labels_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'labels_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-register__label',
			)
		);

		$this->add_responsive_control(
			'labels_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'labels_box_shadow',
				'selector' => '{{WRAPPER}} .jet-register__label',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'register_submit_style',
			array(
				'label'      => esc_html__( 'Submit', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_form_submit_style' );

		$this->start_controls_tab(
			'register_form_submit_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'register_submit_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__submit' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'register_submit_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__submit' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'register_form_submit_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'register_submit_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__submit:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'register_submit_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register__submit:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'register_submit_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'register_submit_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-register__submit:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'register_submit_typography',
				'selector' => '{{WRAPPER}} .jet-register__submit',
			)
		);

		$this->add_responsive_control(
			'register_submit_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'register_submit_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'register_submit_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-register__submit',
			)
		);

		$this->add_responsive_control(
			'register_submit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'register_submit_box_shadow',
				'selector' => '{{WRAPPER}} .jet-register__submit',
			)
		);

		$this->add_responsive_control(
			'register_submit_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
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
				'selectors'  => array(
					'{{WRAPPER}} .jet-register-submit' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'login_errors_style',
			array(
				'label'      => esc_html__( 'Errors', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'errors_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register-message' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'errors_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-register-message' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'errors_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'errors_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'errors_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-register-message',
			)
		);

		$this->add_responsive_control(
			'errors_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-register-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'errors_box_shadow',
				'selector' => '{{WRAPPER}} .jet-register-message',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$settings = $this->get_settings();

		if ( is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {

			$this->__open_wrap();
			echo $settings['label_registered'];
			$this->__close_wrap();

			return;
		}

		$registration_enabled = get_option( 'users_can_register' );

		if ( ! $registration_enabled && ! jet_blocks_integration()->in_elementor() ) {

			$this->__open_wrap();
			esc_html_e( 'Registration disabled', 'jet-blocks' );
			$this->__close_wrap();

			return;
		}

		$this->__open_wrap();

		$redirect_url = site_url( $_SERVER['REQUEST_URI'] );

		switch ( $settings['register_redirect'] ) {

			case 'home':
				$redirect_url = esc_url( home_url( '/' ) );
				break;

			case 'custom':
				$redirect_url = $settings['register_redirect_url'];
				break;
		}

		if ( ! $registration_enabled ) {
			esc_html_e( 'Registration currently disabled and this form will not be visible for guest users. Please, enable registration in Settings/General or remove this widget from the page.', 'jet-blocks' );
		}

		include $this->__get_global_template( 'index' );

		$this->__close_wrap();
	}

}
