<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Document_Base extends Elementor\Core\Base\Document {

	public function get_name() {
		return '';
	}

	public static function get_title() {
		return '';
	}

	public function has_conditions() {
		return true;
	}

	public function get_preview_as_query_args() {
		return array();
	}

	protected function get_default_data() {

		if ( $this->has_conditions() ) {
			return array(
				'id' => 0,
				'settings' => array(
					'jet_conditions' => array(
						'main' => '',
					),
				),
			);
		} else {
			return array(
				'id' => 0,
				'settings' => array(),
			);
		}

	}

	protected function _register_controls() {

		parent::_register_controls();

		if ( $this->has_conditions() ) {

			$this->start_controls_section(
				'jet_template_conditions',
				array(
					'label' => __( 'Conditions', 'jet-theme-core' ),
					'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
				)
			);

			jet_theme_core()->conditions->register_condition_controls( $this );

			$this->end_controls_section();

		}

	}

	public function get_elements_raw_data( $data = null, $with_html_content = false ) {

		$structures_manager = jet_theme_core()->structures;

		$structures_manager->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data( $data, $with_html_content );

		$structures_manager->restore_current_query();

		return $editor_data;
	}

	public function render_element( $data ) {

		$structures_manager = jet_theme_core()->structures;

		$structures_manager->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		$structures_manager->restore_current_query();

		return $render_html;
	}

}
