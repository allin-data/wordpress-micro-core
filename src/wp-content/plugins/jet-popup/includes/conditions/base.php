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

if ( ! class_exists( 'Jet_Popup_Conditions_Base' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Base class
	 */
	abstract class Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		abstract public function get_id();

		/**
		 * Condition label
		 *
		 * @return string
		 */
		abstract public function get_label();

		/**
		 * Condition group
		 *
		 * @return string
		 */
		abstract public function get_group();

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		abstract public function check( $args );

		/**
		 * Returns parent codition ID for current condition
		 *
		 * @return array
		 */
		public function get_childs() {
			return array();
		}

		/**
		 * Returns parent codition ID for current condition
		 *
		 * @return array
		 */
		public function get_controls() {
			return array();
		}

		/**
		 * Returns parent codition ID for current condition
		 *
		 * @return array
		 */
		public function get_avaliable_options() {
			return false;
		}

		/**
		 * [action description]
		 * @return [type] [description]
		 */
		public function ajax_action() {
			return false;
		}

		/**
		 * Returns human-reading information about active arguments for condition
		 *
		 * @param  arrayu $args
		 * @return string
		 */
		public function verbose_args( $args ) {
			return '';
		}

		/**
		 * [get_label_by_value description]
		 * @param  [type] $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value ) {
			return '';
		}


	}

}
