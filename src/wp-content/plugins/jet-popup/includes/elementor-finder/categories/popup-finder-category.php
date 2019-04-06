<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Settings Category
 *
 * Provides items related to Elementor's settings.
 */
class Jet_Popup_Finder_Category extends \Elementor\Core\Common\Modules\Finder\Base_Category {

	/**
	 * Get title.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Jet Popup Settings', 'jet-popup' );
	}

	/**
	 * Get category items.
	 *
	 * @access public
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function get_category_items( array $options = [] ) {
		return [
			'jet-popup-settings' => [
				'title'    => __( 'Jet Popup Settings', 'jet-popup' ),
				'url'      => jet_popup()->settings->get_settings_page_url(),
				'keywords' => [ 'general', 'popup', 'settings', 'jet', 'mailchimp' ],
			],
			'jet-popup-library' => [
				'title'    => __( 'Jet Popup Library', 'jet-popup' ),
				'url'      => jet_popup()->post_type->get_library_page_url(),
				'icon'     => 'folder',
				'keywords' => [ 'popup', 'library', 'jet', 'create', 'new' ],
			],
		];
	}
}
