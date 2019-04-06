<?php
/**
 * Class: Jet_Blocks_Auth_Links
 * Name: Auth Links
 * Slug: jet-auth-links
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

class Jet_Blocks_Auth_Links extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-auth-links';
	}

	public function get_title() {
		return esc_html__( 'Auth Links', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jetblocks-icon-4';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_login_link',
			array(
				'label' => esc_html__( 'Login Link', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'show_login_link',
			array(
				'label'        => esc_html__( 'Show Login Link', 'jet-blocks' ),
				'description'  => esc_html__( 'For not logged-in users', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'login_link_url',
			array(
				'label'     => esc_html__( 'Login Page URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'show_login_link' => 'true',
				),
			)
		);

		$this->add_control(
			'login_link_text',
			array(
				'label'     => esc_html__( 'Login Link Text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Login', 'jet-blocks' ),
				'condition' => array(
					'show_login_link' => 'true',
				),
			)
		);

		$this->add_control(
			'login_link_icon',
			array(
				'label'       => esc_html__( 'Login Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-sign-in',
				'condition'   => array(
					'show_login_link' => 'true',
				),
			)
		);

		$this->add_control(
			'login_prefix',
			array(
				'label'     => esc_html__( 'Login Prefix', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Have an account?', 'jet-blocks' ),
				'condition' => array(
					'show_login_link' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_logout_link',
			array(
				'label' => esc_html__( 'Logout Link', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'show_logout_link',
			array(
				'label'        => esc_html__( 'Show Logout Link', 'jet-blocks' ),
				'description'  => esc_html__( 'For logged-in users', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'logout_link_text',
			array(
				'label'     => esc_html__( 'Logout Link Text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Logout', 'jet-blocks' ),
				'condition' => array(
					'show_logout_link' => 'true',
				),
			)
		);

		$this->add_control(
			'logout_link_icon',
			array(
				'label'       => esc_html__( 'Logout Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-sign-out',
				'condition'   => array(
					'show_logout_link' => 'true',
				),
			)
		);

		$this->add_control(
			'logout_redirect',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Redirect After Logout', 'jet-blocks' ),
				'default'    => 'left',
				'options'    => array(
					'home'   => esc_html__( 'Home page', 'jet-blocks' ),
					'left'   => esc_html__( 'Left on the current page', 'jet-blocks' ),
					'custom' => esc_html__( 'Custom URL', 'jet-blocks' ),
				),
				'condition' => array(
					'show_logout_link' => 'true',
				),
			)
		);

		$this->add_control(
			'logout_redirect_url',
			array(
				'label'     => esc_html__( 'Logout Link URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'show_logout_link' => 'true',
					'logout_redirect'  => 'custom',
				),
			)
		);

		$this->add_control(
			'logout_prefix',
			array(
				'label'       => esc_html__( 'Logout Prefix', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hi, %s', 'jet-blocks' ),
				'description' => esc_html__( 'Use %s marker for username', 'jet-blocks' ),
				'condition'   => array(
					'show_logout_link' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_register_link',
			array(
				'label' => esc_html__( 'Register Link', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'show_register_link',
			array(
				'label'        => esc_html__( 'Show Register Link', 'jet-blocks' ),
				'description'  => esc_html__( 'For not logged-in users', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'register_link_url',
			array(
				'label'     => esc_html__( 'Register Page URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'show_register_link' => 'true',
				),
			)
		);

		$this->add_control(
			'register_link_text',
			array(
				'label'     => esc_html__( 'Register Link Text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Register', 'jet-blocks' ),
				'condition' => array(
					'show_register_link' => 'true',
				),
			)
		);

		$this->add_control(
			'register_link_icon',
			array(
				'label'       => esc_html__( 'Register Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-user-plus',
				'condition'   => array(
					'show_register_link' => 'true',
				),
			)
		);

		$this->add_control(
			'register_prefix',
			array(
				'label'       => esc_html__( 'Register Prefix', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'or', 'jet-blocks' ),
				'condition'   => array(
					'show_register_link' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_registered_link',
			array(
				'label' => esc_html__( 'Registered Link', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'show_registered_link',
			array(
				'label'        => esc_html__( 'Show Registered Link', 'jet-blocks' ),
				'description'  => esc_html__( 'For logged-in users', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'registered_link_url',
			array(
				'label'     => esc_html__( 'Registered Page URL', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'show_registered_link' => 'true',
				),
			)
		);

		$this->add_control(
			'registered_link_text',
			array(
				'label'     => esc_html__( 'Registered Link Text', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'My Account', 'jet-blocks' ),
				'condition' => array(
					'show_registered_link' => 'true',
				),
			)
		);

		$this->add_control(
			'registered_link_icon',
			array(
				'label'       => esc_html__( 'Registered Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-user',
				'condition'   => array(
					'show_registered_link' => 'true',
				),
			)
		);

		$this->add_control(
			'registered_prefix',
			array(
				'label'       => esc_html__( 'Registered Prefix', 'jet-blocks' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '|',
				'condition'   => array(
					'show_registered_link' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_settings',
			array(
				'label' => esc_html__( 'General', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'       => esc_html__( 'Order', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'login_register',
				'options'     => array(
					'login_register' => esc_html__( 'Login/Logout, Register/Registered', 'jet-blocks' ),
					'register_login' => esc_html__( 'Register/Registered, Login/Logout', 'jet-blocks' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_responsive_control(
			'auth_alignment',
			array(
				'label'   => esc_html__( 'Auth Links Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
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
					'{{WRAPPER}} .jet-auth-links' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-blocks/auth-links/css-scheme',
			array(
				'login_link'        => '.jet-auth-links__login .jet-auth-links__item',
				'login_prefix'      => '.jet-auth-links__login .jet-auth-links__prefix',
				'logout_link'       => '.jet-auth-links__logout .jet-auth-links__item',
				'logout_prefix'     => '.jet-auth-links__logout .jet-auth-links__prefix',
				'register_link'     => '.jet-auth-links__register .jet-auth-links__item',
				'register_prefix'   => '.jet-auth-links__register .jet-auth-links__prefix',
				'registered_link'   => '.jet-auth-links__registered .jet-auth-links__item',
				'registered_prefix' => '.jet-auth-links__registered .jet-auth-links__prefix',
			)
		);

		$this->start_controls_section(
			'section_login_link_style',
			array(
				'label'      => esc_html__( 'Login Link Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'login_link_style',
			array(
				'label' => esc_html__( 'Link', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'login_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['login_link'],
			)
		);

		$this->start_controls_tabs( 'tabs_login_link_style' );

		$this->start_controls_tab(
			'tab_login_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'login_link_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_link_background_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_login_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'login_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_link_background_hover_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_link_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'login_link_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'login_link_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['login_link'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'login_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'login_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['login_link'],
			)
		);

		$this->add_control(
			'login_link_padding',
			array(
				'label' => esc_html__( 'Padding', 'jet-blocks' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'login_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['login_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'login_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'login_prefix_color',
			array(
				'label' => __( 'Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['login_prefix'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'login_prefix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['login_prefix'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_logout_link_style',
			array(
				'label'      => esc_html__( 'Logout Link Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'logout_link_style',
			array(
				'label' => esc_html__( 'Link', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'logout_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['logout_link'],
			)
		);

		$this->start_controls_tabs( 'tabs_logout_link_style' );

		$this->start_controls_tab(
			'tab_logout_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'logout_link_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'logout_link_background_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_logout_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'logout_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'logout_link_background_hover_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'logout_link_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'logout_link_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'logout_link_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['logout_link'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'logout_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'logout_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['logout_link'],
			)
		);

		$this->add_control(
			'logout_link_padding',
			array(
				'label' => esc_html__( 'Padding', 'jet-blocks' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'logout_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['logout_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'logout_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'logout_prefix_color',
			array(
				'label' => __( 'Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['logout_prefix'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'logout_prefix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['logout_prefix'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_register_link_style',
			array(
				'label'      => esc_html__( 'Register Link Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'register_link_style',
			array(
				'label' => esc_html__( 'Link', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'register_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['register_link'],
			)
		);

		$this->start_controls_tabs( 'tabs_register_link_style' );

		$this->start_controls_tab(
			'tab_register_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'register_link_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'register_link_background_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_register_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'register_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'register_link_background_hover_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'register_link_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'register_link_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'register_link_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['register_link'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'register_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'register_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['register_link'],
			)
		);

		$this->add_control(
			'register_link_padding',
			array(
				'label' => esc_html__( 'Padding', 'jet-blocks' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'register_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['register_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'register_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'register_prefix_color',
			array(
				'label' => __( 'Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['register_prefix'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'register_prefix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['register_prefix'],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_registered_link_style',
			array(
				'label'      => esc_html__( 'Registered Link Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'registered_link_style',
			array(
				'label' => esc_html__( 'Link', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'registered_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['registered_link'],
			)
		);

		$this->start_controls_tabs( 'tabs_registered_link_style' );

		$this->start_controls_tab(
			'tab_registered_link_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'registered_link_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'registered_link_background_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_registered_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'registered_link_color',
			array(
				'label' => esc_html__( 'Text Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'registered_link_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'registered_link_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'registered_link_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'registered_link_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['registered_link'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'registered_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'registered_link_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['registered_link'],
			)
		);

		$this->add_control(
			'registered_link_padding',
			array(
				'label' => esc_html__( 'Padding', 'jet-blocks' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'registered_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['registered_link'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'registered_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'registered_prefix_color',
			array(
				'label' => __( 'Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['registered_prefix'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'registered_prefix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['registered_prefix'],
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
	 * Try to get URL from settings by name
	 *
	 * @param  array  $settings [description]
	 * @param  string $name     [description]
	 * @return [type]           [description]
	 */
	public function __get_url( $settings = array(), $name = '' ) {

		$url = isset( $settings[ $name ] ) ? $settings[ $name ] : '';

		if ( ! $url ) {
			return '#';
		}

		if ( false === strpos( $url, 'http' ) ) {
			return get_permalink( get_page_by_path( $url ) );
		} else {
			return esc_url( $url );
		}

	}

	/**
	 * Logout URL
	 *
	 * @return string
	 */
	public function __logout_url() {

		$settings        = $this->get_settings();
		$logout_redirect = isset( $settings['logout_redirect'] ) ? $settings['logout_redirect'] : 'left';

		switch ( $logout_redirect ) {
			case 'home':
				$redirect = esc_url( home_url( '/' ) );
				break;

			case 'left':
				$redirect = get_permalink();
				break;

			case 'custom':
				$redirect = $this->__get_url( $settings, 'logout_redirect_url' );
				break;

			default:
				$redirect = '';
				break;
		}



		return wp_logout_url( $redirect );
	}

}
