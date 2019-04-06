<?php
/**
 * Define callback functions for templater.
 *
 * @package   Cherry_Team
 * @author    Cherry Team
 * @license   GPL-3.0+
 * @link      http://www.cherryframework.com/
 * @copyright 2012 - 2015, Cherry Team
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Callbacks for Projects shortcode templater.
 *
 * @since 1.0.0
 */
class Cherry_Popups_Template_Callbacks {

	/**
	 * Shortcode attributes array.
	 *
	 * @var array
	 */
	public $atts = array();

	/**
	 * Current post meta.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public $post_meta = null;

	/**
	 * Current popup id.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public $popup_id = null;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 * @param array $atts Set of attributes.
	 */
	public function __construct( $atts ) {
		$this->atts = $atts;
	}

	/**
	 * Get post meta.
	 *
	 * @since 1.1.0
	 */
	public function get_meta() {
		if ( null === $this->post_meta ) {
			global $post;
			$this->post_meta = get_post_meta( $post->ID, '', true );
		}

		return $this->post_meta;
	}

	/**
	 * Clear post data after loop iteration.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function clear_meta() {
		$this->post_meta = null;
	}

	/**
	 * Get post title.
	 *
	 * @since 1.0.0
	 */
	public function get_title( $attr = array() ) {

		$default_attr = array( 'number_of_words' => 10 );

		$attr = wp_parse_args( $attr, $default_attr );

		$html = '<h4 %1$s>%4$s</h4>';

		$settings = array(
			'visible'      => true,
			'length'       => $attr['number_of_words'],
			'trimmed_type' => 'word',
			'ending'       => '&hellip;',
			'html'         => $html,
			'class'        => '',
			'title'        => '',
			'echo'         => false,
		);

		/**
		 * Filter post title settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		$settings = apply_filters( 'cherry-popup-title-settings', $settings );

		$html = '<div class="cherry-popup-title">';
			$html .= cherry_popups_init()->cherry_utility->attributes->get_title( $settings, 'post', $this->popup_id );
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get post content.
	 *
	 * @since 1.0.0
	 */
	public function get_content( $attr = array() ) {
		$post_data = get_post( $this->popup_id );

		$default_attr = array(
			'number_of_words' => -1,
		);

		$attr = wp_parse_args( $attr, $default_attr );

		global $post;

		$post = get_post( $this->popup_id );

		setup_postdata( $post );
		ob_start();
		the_content();
		$content = sprintf( '<div class="cherry-popup-content">%s</div>', ob_get_clean() );
		wp_reset_postdata();

		return $content;
	}

	/**
	 * Get subscribe form.
	 *
	 * @since 1.0.0
	 */
	public function get_subscribe_form( $attr = array() ) {
		$default_attr = array(
			'submit_text'      => esc_html__( 'Subscribe', 'cherry-popups' ),
			'placeholder_text' => esc_html__( 'Your email', 'cherry-popups' ),
		);

		$attr = wp_parse_args( $attr, $default_attr );

		$html = '<div class="cherry-popup-subscribe">';
			$html .= '<form method="POST" action="#" class="cherry-popup-subscribe__form">';
				$html .= '<div class="cherry-popup-subscribe__message"><span></span></div>';
				$html .= '<div class="cherry-popup-subscribe__input-group">';
					$html .= '<input class="cherry-popup-subscribe__input" type="email" name="subscribe-mail" value="" placeholder="' . $attr['placeholder_text'] . '">';
					$html .= '<div class="cherry-popup-subscribe__submit">' . $attr['submit_text'] . '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get subscribe form.
	 *
	 * @since 1.0.0
	 */
	public function get_login_form( $attr = array() ) {
		$default_attr = apply_filters( 'cherry_popup_login_form_defaults_attr', array(
			'submit_text'          => esc_html__( 'Log in', 'cherry-popups' ),
			'user_placeholder'     => esc_html__( 'Login', 'cherry-popups' ),
			'password_placeholder' => esc_html__( 'Password', 'cherry-popups' ),
			'sign_up_message'      => esc_html__( 'Don\'t have an account? Click here to', 'cherry-popups' ),
			'sign_up_link_text'    => esc_html__( 'Sign up', 'cherry-popups' ),
			'sign_up_link'         => esc_html__( '#', 'cherry-popups' ),
			'remember_message'     => esc_html__( 'Remember me', 'cherry-popups' ),
			'use_mail'             => esc_html__( 'or use your login data', 'cherry-popups' ),
			'already_login_text'   => esc_html__( 'You are currently logged in', 'cherry-popups' ),
		) );

		$attr = wp_parse_args( $attr, $default_attr );

		if ( is_user_logged_in() ) {
			$html = '<h2 class="cherry-popup-login__already-message"><span>' . $attr['already_login_text'] . '</span></h2>';

			return $html;
		}

		$html = '<div class="cherry-popup-login">';
			$html .= '<form method="POST" action="#" class="cherry-popup-login__form">';

				if ( function_exists( 'wsl_render_auth_widget' ) ) {
					$html .= wsl_render_auth_widget( array(
						'caption'=> '',
						)
					);
					$html .= '<div class="cherry-popup-login__use-mail"><span>' . $attr['use_mail'] . '</span></div>';
				}

				$html .= '<div class="cherry-popup-login__input-group">';
					$html .= '<div class="cherry-popup-login__wrap">';
						$html .= '<input id="cherry-popup-user-input" class="cherry-popup__input cherry-popup-login__input-user" type="text" name="login-user" value="" placeholder="' . $attr['user_placeholder'] . '">';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-login__wrap">';
						$html .= '<input id="cherry-popup-pass-input" class="cherry-popup__input cherry-popup-login__input-pass" type="password" name="login-password" value="" placeholder="' . $attr['password_placeholder'] . '">';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-login__wrap">';
						$html .= '<div class="cherry-popup-login__signup-message">';
							$html .= '<span>' . $attr['sign_up_message'] . '</span><a href="' . $attr['sign_up_link'] . '" class="cherry-popups-signup-link">' . $attr['sign_up_link_text'] . '</a>';
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-login__wrap">';
						$html .= sprintf( '<div class="cherry-popup-check cherry-popup-login__remember"><div class="marker"><span class="dashicons dashicons-yes"></span></div><span class="label">%1$s</span></div>', $attr['remember_message'] );
					$html .= '</div>';
					$html .= '<div class="cherry-popup-login__wrap">';
						$html .= '<div class="cherry-popup-login__login-in"><span>' . $attr['submit_text'] . '</span><div class="cherry-popup-spinner"><div class="cherry-double-bounce1"></div><div class="cherry-double-bounce2"></div></div></div>';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="cherry-popup-login__message"><span></span></div>';
			$html .= '</form>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get register form.
	 *
	 * @since 1.0.0
	 */
	public function get_register_form( $attr = array() ) {
		$default_attr = apply_filters( 'cherry_popup_register_form_defaults_attr', array(
			'submit_text'       => esc_html__( 'Sign up', 'cherry-popups' ),
			'login_placeholder' => esc_html__( 'Login', 'cherry-popups' ),
			'mail_placeholder'  => esc_html__( 'Email', 'cherry-popups' ),
			'have_account'      => esc_html__( 'Already have an account?', 'cherry-popups' ),
			'login_link_text'   => esc_html__( 'Log in', 'cherry-popups' ),
			'login_link'        => esc_html__( '#', 'cherry-popups' ),
			'new_user_data'     => esc_html__( 'or enter your registration credentials', 'cherry-popups' ),
		) );

		$attr = wp_parse_args( $attr, $default_attr );

		$html = '<div class="cherry-popup-register">';
			$html .= '<form method="POST" action="#" class="cherry-popup-register__form">';
				if ( function_exists( 'wsl_render_auth_widget' ) ) {
					$html .= wsl_render_auth_widget( array(
						'caption'=> '',
						)
					);
					$html .= '<div class="cherry-popup-signup__new-user"><span>' . $attr['new_user_data'] . '</span></div>';
				}
				$html .= '<div class="cherry-popup-register__input-group">';
					$html .= '<div class="cherry-popup-register__wrap">';
						$html .= '<input id="cherry-popup-register-login-input" class="cherry-popup__input cherry-popup-register__input-login" type="text" name="register-login" value="" placeholder="' . $attr['login_placeholder'] . '" tabindex=1>';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-register__wrap">';
						$html .= '<input id="cherry-popup-register-mail-input" class="cherry-popup__input cherry-popup-login__input-mail" type="email" name="register-mail" value="" placeholder="' . $attr['mail_placeholder'] . '" tabindex=2>';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-signup__login-user">';
						$html .= '<span>' . $attr['have_account'] . '</span><a href="' . $attr['login_link'] . '" class="cherry-popups-login-link">' . $attr['login_link_text'] . '</a>';
					$html .= '</div>';
					$html .= '<div class="cherry-popup-register__wrap">';
						$html .= '<div class="cherry-popup-register__sign-up"><span>' . $attr['submit_text'] . '</span><div class="cherry-popup-spinner"><div class="cherry-double-bounce1"></div><div class="cherry-double-bounce2"></div></div></div>';
					$html .= '</div>';
				$html .= '</div>';
				$html .= '<div class="cherry-popup-register__message"><span></span></div>';
			$html .= '</form>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Get register form.
	 *
	 * @since 1.0.0
	 */
	public function get_close_label( $attr = array() ) {

		$default_attr = apply_filters( 'cherry_popup_close_label_defaults_attr', array(
			'label_text' => esc_html__( 'Close Popup', 'cherry-popups' ),
		) );

		$attr = wp_parse_args( $attr, $default_attr );

		$html = '<span class="cherry-popup-close-label">' . $attr['label_text'] . '</span>';

		return $html;
	}

}
