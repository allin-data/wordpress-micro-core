<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Structure_Base' ) ) {

	/**
	 * Define Jet_Theme_Core_Structure_Base class
	 */
	abstract class Jet_Theme_Core_Structure_Base {

		abstract public function get_id();

		abstract public function get_single_label();

		abstract public function get_plural_label();

		abstract public function get_sources();

		abstract public function get_document_type();

		/**
		 * Is current structure could be outputed as location
		 *
		 * @return boolean
		 */
		public function is_location() {
			return false;
		}

		/**
		 * Location name
		 *
		 * @return boolean
		 */
		public function location_name() {
			return '';
		}

		/**
		 * Aproprite location name from Elementor Pro
		 * @return [type] [description]
		 */
		public function pro_location_mapping() {
			return '';
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => true,
				'show_keywords' => true,
			);

		}

	}

}
