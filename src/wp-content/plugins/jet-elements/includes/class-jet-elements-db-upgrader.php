<?php
/**
 * DB upgrder class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_DB_Upgrader' ) ) {

	/**
	 * Define Jet_Elements_DB_Upgrader class
	 */
	class Jet_Elements_DB_Upgrader {

		/**
		 * Setting key
		 *
		 * @var string
		 */
		public $key = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->key = jet_elements_settings()->key;

			/**
			 * Plugin initialized on new Jet_Elements_DB_Upgrader call.
			 * Please ensure, that it called only on admin context
			 */
			$this->init_upgrader();
		}

		/**
		 * Initialize upgrader module
		 *
		 * @return void
		 */
		public function init_upgrader() {

			$db_updater_data = jet_elements()->framework->get_included_module_data( 'cherry-x-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'jet-elements',
					'version'   => '1.15.3',
					'callbacks' => array(
						'1.7.2' => array(
							array( $this, 'update_db_1_7_2' ),
						),
						'1.10.0' => array(
							array( $this, 'update_db_1_10_0' ),
						),
						'1.11.0' => array(
							array( $this, 'update_db_1_11_0' ),
						),
						'1.12.0' => array(
							array( $this, 'update_db_1_12_0' ),
						),
						'1.13.0' => array(
							array( $this, 'update_db_1_13_0' ),
						),
						'1.14.0' => array(
							array( $this, 'update_db_1_14_0' ),
						),
						'1.15.0' => array(
							array( $this, 'update_db_1_15_0' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'jet-elements' ),
						'data_update'  => esc_html__( 'Data Update', 'jet-elements' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'jet-elements' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'jet-elements' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'jet-elements' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.7.2
		 *
		 * @return void
		 */
		public function update_db_1_7_2() {

			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-progress-bar'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-progress-bar'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}
			}
		}

		/**
		 * Update db updater 1.10.0
		 *
		 * @return void
		 */
		public function update_db_1_10_0() {

			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-portfolio'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-portfolio'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}

		/**
		 * Update db updater 1.11.0
		 *
		 * @return void
		 */
		public function update_db_1_11_0() {

			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-timeline'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-timeline'] = 'true';
					}
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-inline-svg'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-inline-svg'] = 'true';
					}
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-price-list'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-price-list'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}

		/**
		 * Update db updater 1.12.0
		 *
		 * @return void
		 */
		public function update_db_1_12_0() {
			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-weather'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-weather'] = 'true';
					}
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-table'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-table'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}

		/**
		 * Update db updater 1.13.0
		 *
		 * @return void
		 */
		public function update_db_1_13_0() {
			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-dropbar'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-dropbar'] = 'true';
					}
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-audio'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-audio'] = 'true';
					}
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-video'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-video'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}
		/**
		 * Update db updater 1.14.0
		 *
		 * @return void
		 */
		public function update_db_1_14_0() {
			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-horizontal-timeline'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-horizontal-timeline'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}

		/**
		 * Update db updater 1.15.0
		 *
		 * @return void
		 */
		public function update_db_1_15_0() {
			$current_version_settings = get_option( $this->key, false );

			if ( $current_version_settings ) {
				if ( isset( $current_version_settings['avaliable_widgets'] ) ) {
					if ( ! isset( $current_version_settings['avaliable_widgets']['jet-elements-pie-chart'] ) ) {
						$current_version_settings['avaliable_widgets']['jet-elements-pie-chart'] = 'true';
					}
					update_option( $this->key, $current_version_settings );
				}

			}
		}
	}

}
