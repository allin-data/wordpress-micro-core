<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Dashboard_Base' ) ) {

	/**
	 * Define Jet_Theme_Core_Dashboard_Base class
	 */
	abstract class Jet_Theme_Core_Dashboard_Base {

		/**
		 * Builder instance
		 *
		 * @var CX_Interface_Builder
		 */
		public $builder = null;

		/**
		 * Manager instance
		 *
		 * @var Jet_Theme_Core_Dashboard
		 */
		public $manager = null;

		/**
		 * Class constructor
		 */
		public function __construct( Jet_Theme_Core_Dashboard $manager ) {

			$this->manager = $manager;

			add_action( 'jet-theme-core/dashboard/actions/' . $this->get_slug(), array( $this, 'attach_handlers' ) );

			if ( true === $this->use_builder() ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			}

			// Custom init only for this page.
			if ( true === $this->is_page_now() ) {
				$this->init();
			}

			// Custom global initialization.
			$this->init_glob();

		}

		/**
		 * Custom initializtion. Works globally
		 *
		 * @return void
		 */
		public function init_glob() {}

		/**
		 * Custom initializtion. Works only on current pae.
		 *
		 * @return void
		 */
		public function init() {}

		/**
		 * Disable or enable builder initializtion
		 * Disable this if you don't use builder for current page or page uses external builder instance
		 *
		 * @return bool
		 */
		public function use_builder() {
			return true;
		}

		/**
		 * Check if current dashborad page displaying now
		 *
		 * @return bool
		 */
		public function is_page_now() {

			if ( ! isset( $_REQUEST['page'] ) || $this->manager->slug() !== $_REQUEST['page'] ) {
				return false;
			}

			if ( isset( $_REQUEST['tab'] ) && $this->get_slug() !== $_REQUEST['tab'] ) {
				return false;
			}

			return true;
		}

		/**
		 * Returns current page URL
		 *
		 * @return string
		 */
		public function get_current_page_link() {

			return add_query_arg(
				array(
					'page' => $this->manager->slug(),
					'tab'  => $this->get_slug(),
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * Initialize page builder module if reqired
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( ! $this->is_page_now() ) {
				return;
			}

			$builder_data = jet_theme_core()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Attach Handlers for current page
		 *
		 * @return bool
		 */
		public function attach_handlers() {

			$action = isset( $_GET['handle'] ) ? esc_attr( $_GET['handle'] ) : '';

			if ( ! $action ) {
				return false;
			}

			if ( ! method_exists( $this, $action ) ) {
				return false;
			}

			$data = $_REQUEST;

			unset( $data['handle'] );
			unset( $data['jet_action'] );

			call_user_func( array( $this, $action ), $data );

			return true;
		}

		/**
		 * Icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'dashicons dashicons-admin-generic';
		}

		/**
		 * Page slug
		 *
		 * @return string
		 */
		abstract public function get_slug();

		/**
		 * Page name
		 *
		 * @return string
		 */
		abstract public function get_name();

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		abstract public function render_page();

	}

}
