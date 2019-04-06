<?php
/**
 * Sets up the plugin option page.
 *
 * @package    Blank_Plugin
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2002-2016, Cherry Team
 */

// If class `Popups_Options_Page` doesn't exists yet.
if ( ! class_exists( 'Popups_Options_Page' ) ) {

	/**
	 * Blank_Plugin_Options_Page class.
	 */
	class Popups_Options_Page {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Instance of the class Cherry_Interface_Builder.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private $builder = null;

		/**
		 * HTML spinner.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $spinner = '<span class="loader-wrapper"><span class="loader"></span></span>';

		/**
		 * Dashicons.
		 *
		 * @since 1.0.0
		 * @var string
		 * @access private
		 */
		private $button_icon = '<span class="dashicons dashicons-yes icon"></span>';

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->builder = cherry_popups()->get_core()->modules['cherry-interface-builder'];
			$this->render_page();
		}

		/**
		 * Render plugin options page.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function render_page() {
			$this->builder->register_section(
				array(
					'popups_options_section' => array(
						'type'        => 'section',
						'scroll'      => false,
						'title'       => esc_html__( 'Cherry PopUps Settings', 'cherry-popups' ),
						'description' => esc_html__( 'General cherry popUps settings', 'cherry-popups' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'cherry-popups-options-form' => array(
						'type'   => 'form',
						'parent' => 'popups_options_section',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'option_page_content' => array(
						'type'   => 'settings',
						'parent' => 'cherry-popups-options-form',
					),
					'option_page_footer' => array(
						'type'   => 'settings',
						'parent' => 'cherry-popups-options-form',
						'class'  => 'option-page-footer',
					),
				)
			);

			$this->builder->register_component(
				array(
					'tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'option_page_content',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'general_tab' => array(
						'parent'      => 'tab_vertical',
						'title'       => esc_html__( 'General settings', 'cherry-popups' ),
						'description' => esc_html__( 'General plugin settings', 'cherry-popups' ),
					),
					'open_page_tab' => array(
						'parent'      => 'tab_vertical',
						'title'       => esc_html__( 'Open page settings', 'cherry-popups' ),
						'description' => esc_html__( 'Open page default popups settings', 'cherry-popups' ),
					),
					'close_page_tab' => array(
						'parent'      => 'tab_vertical',
						'title'       => esc_html__( 'Close page settings', 'cherry-popups' ),
						'description' => esc_html__( 'Close page default popups settings', 'cherry-popups' ),
					),
					'mailing_options' => array(
						'parent'      => 'tab_vertical',
						'title'       => esc_html__( 'Mailing List Manager', 'cherry-popups' ),
						'description' => esc_html__( 'MailChimp settings', 'cherry-popups' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'cherry-popups-restore-options' => array(
						'type'          => 'button',
						'parent'        => 'option_page_footer',
						'style'         => 'normal',
						'view_wrapping' => false,
						'content'       => '<span class="text">' . esc_html__( 'Restore', 'cherry-popups' ) . '</span>' . $this->spinner . $this->button_icon,
					),
					'cherry-popups-save-options' => array(
						'type'          => 'button',
						'parent'        => 'option_page_footer',
						'style'         => 'success',
						'class'         => 'custom-class',
						'view_wrapping' => false,
						'content'       => '<span class="text">' . esc_html__( 'Save', 'cherry-popups' ) . '</span>' . $this->spinner . $this->button_icon,
					),
					'enable-popups' => array(
						'type'         => 'switcher',
						'parent'       => 'general_tab',
						'title'        => esc_html__( 'Enable popups', 'cherry-popups' ),
						'description'  => esc_html__( 'Enable / Disable popups at once on all pages', 'cherry-popups' ),
						'value'        => cherry_popups()->get_option( 'enable-popups', 'true' ),
						'toggle'       => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
						'style'        => 'normal',
						'class'        => '',
						'label'        => '',
					),
					'mobile-enable-popups' => array(
						'type'         => 'switcher',
						'parent'       => 'general_tab',
						'title'        => esc_html__( 'Enable Plugin on Mobile Devices', 'cherry-popups' ),
						'description'  => esc_html__( 'Enable / Disable popups on mobile devices at once on all pages', 'cherry-popups' ),
						'value'        => cherry_popups()->get_option( 'mobile-enable-popups', 'true' ),
						'toggle'       => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
						'style'        => 'normal',
						'class'        => '',
						'label'        => '',
					),
					'enable-logged-users' => array(
						'type'         => 'switcher',
						'parent'       => 'general_tab',
						'title'        => esc_html__( 'Enable for logged users', 'cherry-popups' ),
						'description'  => esc_html__( 'All popup will not be displayed for logged users', 'cherry-popups' ),
						'value'        => cherry_popups()->get_option( 'enable-logged-users', 'true' ),
						'toggle'       => array(
							'true_toggle'  => 'Yes',
							'false_toggle' => 'No',
						),
						'style'        => 'normal',
						'class'        => '',
						'label'        => '',
					),
					'default-open-page-popup' => array(
						'type'             => 'select',
						'parent'           => 'open_page_tab',
						'title'            => esc_html__( 'Default Open Page Popup', 'cherry-popups' ),
						'description'      => esc_html__( 'Assign one of the popup that is displayed when you open the page.', 'cherry-popups' ),
						'multiple'         => false,
						'filter'           => true,
						'value'            => cherry_popups()->get_option( 'default-open-page-popup', 'disable' ),
						'options'          => array(),
						'options_callback' => array( cherry_popups_init(), 'get_avaliable_popups' ),
						'placeholder'      => 'Select',
						'label'            => '',
						'class'            => '',
					),
					'open-page-popup-display' => array(
						'type'			=> 'checkbox',
						'parent'		=> 'open_page_tab',
						'title'			=> esc_html__( 'Open page popup display in:', 'cherry-popups' ),
						'description'	=> esc_html__( 'Displaing Open page popup in site pages', 'cherry-popups' ),
						'class'			=> '',
						'value'			=> cherry_popups()->get_option( 'open-page-popup-display', array() ),
						'options'		=> array(
							'home'  => esc_html__( 'Home', 'cherry-popups' ),
							'pages' => esc_html__( 'Pages', 'cherry-popups' ),
							'posts' => esc_html__( 'Posts', 'cherry-popups' ),
							'other' => esc_html__( 'Categories, Archive and other', 'cherry-popups' ),
						),
					),
					'default-close-page-popup' => array(
						'type'             => 'select',
						'parent'           => 'close_page_tab',
						'title'            => esc_html__( 'Default Close Page Popup', 'cherry-popups' ),
						'description'      => esc_html__( 'Assign one of the popup that is displayed when you close the page', 'cherry-popups' ),
						'multiple'         => false,
						'filter'           => true,
						'value'            => cherry_popups()->get_option( 'default-close-page-popup', 'disable' ),
						'options'          => array(),
						'options_callback' => array( cherry_popups_init(), 'get_avaliable_popups' ),
						'placeholder'      => 'Select',
						'label'            => '',
						'class'            => '',
					),
					'close-page-popup-display' => array(
						'type'        => 'checkbox',
						'parent'      => 'close_page_tab',
						'title'       => esc_html__( 'Close page popup display in:', 'cherry-popups' ),
						'description' => esc_html__( 'Displaing Close page popup in site pages', 'cherry-popups' ),
						'class'       => '',
						'value'       => cherry_popups()->get_option( 'close-page-popup-display', array() ),
						'options'     => array(
							'home'  => esc_html__( 'Home', 'cherry-popups' ),
							'pages' => esc_html__( 'Pages', 'cherry-popups' ),
							'posts' => esc_html__( 'Posts', 'cherry-popups' ),
							'other' => esc_html__( 'Categories, Archive and other', 'cherry-popups' ),
						),
					),
					'mailchimp-api-key' => array(
						'type'         => 'text',
						'parent'       => 'mailing_options',
						'title'        => esc_html__( 'MailChimp API key', 'cherry-popups' ),
						'description'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys">%2$s</a>', esc_html__( 'Input your MailChimp API key', 'cherry-popups' ), esc_html__( 'About API Keys', 'cherry-popups' ) ),
						'value'        => cherry_popups()->get_option( 'mailchimp-api-key', '' ),
						'class'        => '',
						'label'        => '',
					),
					'mailchimp-list-id' => array(
						'type'         => 'text',
						'parent'       => 'mailing_options',
						'title'        => esc_html__( 'MailChimp list ID', 'cherry-popups' ),
						'description'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id">%2$s</a>', esc_html__( 'MailChimp list ID', 'cherry-popups' ), esc_html__( 'list ID', 'cherry-popups' ) ),
						'value'        => cherry_popups()->get_option( 'mailchimp-list-id', '' ),
						'class'        => '',
						'label'        => '',
					),
				)
			);

			$this->builder->render();
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}
