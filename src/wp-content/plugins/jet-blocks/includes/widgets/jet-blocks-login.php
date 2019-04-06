<?php
/**
 * Class: Jet_Blocks_Login
 * Name: Login
 * Slug: jet-login
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

class Jet_Blocks_Login extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-login';
	}

	public function get_title() {
		return esc_html__( 'Login Form', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-2';
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
			'label_password',
			array(
				'label'   => esc_html__( 'Password Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Password', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'label_remember',
			array(
				'label'   => esc_html__( 'Remember Me Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Remember Me', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'label_log_in',
			array(
				'label'   => esc_html__( 'Log In Button Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Log In', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'login_redirect',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Redirect After Login', 'jet-blocks' ),
				'default'    => 'home',
				'options'    => array(
					'home'   => esc_html__( 'Home page', 'jet-blocks' ),
					'left'   => esc_html__( 'Stay on the current page', 'jet-blocks' ),
					'custom' => esc_html__( 'Custom URL', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'login_redirect_url',
			array(
				'label'     => esc_html__( 'Redirect URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'login_redirect' => 'custom',
				),
			)
		);

		$this->add_control(
			'label_logged_in',
			array(
				'label'   => esc_html__( 'Logged in message', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'You already logged in', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'lost_password_link',
			array(
				'label'   => esc_html__( 'Lost Password link', 'jet-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'lost_password_link_text',
			array(
				'label'     => esc_html__( 'Lost Password link text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Lost your password?', 'jet-blocks' ),
				'condition' => array(
					'lost_password_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'login_fields_style',
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
					'{{WRAPPER}} .jet-login input.input' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login input.input' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .jet-login input.input',
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login input.input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-login input.input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'input_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-login input.input',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login input.input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .jet-login input.input',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'login_labels_style',
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
					'{{WRAPPER}} .jet-login label' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'labels_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'labels_typography',
				'selector' => '{{WRAPPER}} .jet-login label',
			)
		);

		$this->add_responsive_control(
			'labels_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-login label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'labels_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-login label',
			)
		);

		$this->add_responsive_control(
			'labels_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'labels_box_shadow',
				'selector' => '{{WRAPPER}} .jet-login label',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'login_submit_style',
			array(
				'label'      => esc_html__( 'Submit', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_form_submit_style' );

		$this->start_controls_tab(
			'login_form_submit_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'login_submit_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'login_submit_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'login_form_submit_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'login_submit_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input[type="submit"]:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'login_submit_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input[type="submit"]:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'login_submit_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'login_submit_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'login_submit_typography',
				'selector' => '{{WRAPPER}} input[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'login_submit_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'login_submit_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'login_submit_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} input[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'login_submit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'login_submit_box_shadow',
				'selector' => '{{WRAPPER}} input[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'login_submit_alignment',
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
					'{{WRAPPER}} .login-submit' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'lost_password_link_style',
			array(
				'label'      => esc_html__( 'Lost Password Link', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'lost_password_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'lost_password_link_typography',
				'selector' => '{{WRAPPER}} .jet-login-lost-password-link',
			)
		);

		$this->add_control(
			'lost_password_link_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login-lost-password-link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'lost_password_link_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login-lost-password-link:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'lost_password_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login-lost-password-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-login-message' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'errors_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login-message' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'errors_link_color',
			array(
				'label'  => esc_html__( 'Link Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login-message a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'errors_link_hover_color',
			array(
				'label'  => esc_html__( 'Link Hover Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-login-message a:hover' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .jet-login-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-login-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'errors_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} .jet-login-message',
			)
		);

		$this->add_responsive_control(
			'errors_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-login-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'errors_box_shadow',
				'selector' => '{{WRAPPER}} .jet-login-message',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$settings = $this->get_settings();

		if ( is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {

			$this->__open_wrap();
			echo $settings['label_logged_in'];
			$this->__close_wrap();

			return;
		}

		$this->__open_wrap();

		$redirect_url = site_url( $_SERVER['REQUEST_URI'] );

		switch ( $settings['login_redirect'] ) {

			case 'home':
				$redirect_url = esc_url( home_url( '/' ) );
				break;

			case 'custom':
				$redirect_url = esc_url( $settings['login_redirect_url'] );
				break;
		}

		add_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );

		$login_form = wp_login_form( array(
			'echo'           => false,
			'redirect'       => $redirect_url,
			'label_username' => $settings['label_username'],
			'label_password' => $settings['label_password'],
			'label_remember' => $settings['label_remember'],
			'label_log_in'   => $settings['label_log_in'],
		) );

		remove_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );

		$login_form = preg_replace( '/action=[\'\"].*?[\'\"]/', '', $login_form );

		echo '<div class="jet-login">';
		echo $login_form;
		include $this->__get_global_template( 'lost-password-link' );
		include $this->__get_global_template( 'messages' );
		echo '</div>';

		$this->__close_wrap();
	}

	/**
	 * Add form fields
	 *
	 * @param  string $content
	 * @return string
	 */
	public function add_login_fields( $content ) {
		$content .= '<input type="hidden" name="jet_login" value="1">';
		return $content;
	}

}
