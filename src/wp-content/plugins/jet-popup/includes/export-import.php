<?php
/**
 * Jet Popup post type template
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Export_Import' ) ) {

	/**
	 * Define Jet_Export_Import class
	 */
	class Jet_Export_Import {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_filter( 'post_row_actions', [ $this, 'add_export_import_in_dashboard' ], 10, 2 );

			add_action( 'admin_action_jet_popup_import_preset', array( $this, 'import_popup_preset' ) );

			add_action( 'admin_action_jet_popup_create_from_preset', array( $this, 'create_predesigned_popup' ) );

			add_action( 'admin_action_jet_popup_create_from_library_preset', array( $this, 'create_from_library_preset' ) );

			add_action( 'admin_init', [ $this, 'popup_export_preset' ] );

			add_action( 'admin_footer', [ $this, 'admin_popup_forms' ] );

		}

		/**
		 * [add_export_import_in_dashboard description]
		 * @param [type]   $actions [description]
		 * @param \WP_Post $post    [description]
		 */
		public function add_export_import_in_dashboard( $actions, \WP_Post $post ) {

			if (
				Elementor\User::is_current_user_can_edit( $post->ID ) &&
				Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID ) &&
				'jet-popup' === get_post_type( $post->ID )
			) {
				$actions['jet_popup_export'] = sprintf(
					'<a id="jet-popup-export-link" href="%1$s">%2$s</a>',
					$this->get_export_link( $post->ID ),
					__( 'Export Popup', 'jet-popup' )
				);
			}

			return $actions;
		}

		/**
		 * [popup_export_preset description]
		 * @return [type] [description]
		 */
		public function popup_export_preset() {

			if ( ! isset( $_GET['action'] ) ) {
				return;
			}

			if ( 'jet_popup_export_preset' !== $_GET['action'] && ! isset( $_GET['popup_id'] ) ) {
				return;
			}

			$popup_id = $_GET['popup_id'];

			$this->export_template( $popup_id );
		}

		/**
		 * [get_export_link description]
		 * @param  [type] $popup_id [description]
		 * @return [type]           [description]
		 */
		public function get_export_link( $popup_id ) {
			return add_query_arg(
				[
					'action'   => 'jet_popup_export_preset',
					'popup_id' => $popup_id,
				],
				admin_url( 'admin-ajax.php' )
			);
		}

		/**
		 * [export_template description]
		 * @param  [type] $popup_id [description]
		 * @return [type]           [description]
		 */
		public function export_template( $popup_id ) {
			$file_data = $this->prepare_popup_export( $popup_id );

			header( 'Pragma: public' );
			header( 'Expires: 0' );
			header( 'Cache-Control: public' );
			header( 'Content-Description: File Transfer' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename="'. $file_data['name'] . '"' );
			header( 'Content-Transfer-Encoding: binary' );

			session_write_close();

			// Output file contents.
			echo $file_data['content'];

			die();
		}

		/**
		 * [prepare_popup_export description]
		 * @param  [type] $popup_id [description]
		 * @return [type]           [description]
		 */
		public function prepare_popup_export( $popup_id ) {

			$db = Elementor\Plugin::$instance->db;

			$content = $db->get_builder( $popup_id );

			$popup_data = [];

			$popup_data['content'] = $content;

			if ( get_post_meta( $popup_id, '_elementor_page_settings', true ) ) {

				$page_settings = get_post_meta( $popup_id, '_elementor_page_settings', true );

				if ( ! empty( $page_settings ) ) {
					$popup_data['page_settings'] = $page_settings;
				}
			}

			$export_data = [
				'version' => Elementor\DB::DB_VERSION,
				'title'   => get_the_title( $popup_id ),
			];

			$export_data += $popup_data;

			return [
				'name'    => 'jet-popup-' . $popup_id . '-' . date( 'Y-m-d' ) . '.json',
				'content' => wp_json_encode( $export_data ),
			];
		}

		/**
		 * [import_popup_preset description]
		 * @return [type] [description]
		 */
		public function import_popup_preset() {

			if ( ! current_user_can( 'import' ) ) {
				wp_die( __( 'You don\'t have permissions to do this', 'jet-popup' ) );
			}

			if ( empty( $_FILES ) ) {
				wp_die( __( 'File not passed', 'jet-popup' ) );
			}

			$file = $_FILES['file'];

			if ( 'application/json' !== $file['type'] ) {
				wp_die( __( 'Format not allowed', 'jet-popup' ) );
			}

			$content = file_get_contents( $file['tmp_name'] );
			$content = json_decode( $content, true );

			if ( ! $content ) {
				wp_die( __( 'No data found in file', 'jet-popup' ) );
			}

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( jet_popup()->post_type->slug() );

			$popup_content = $content['content'];

			$popup_content = $this->process_export_import_content( $popup_content, 'on_import' );

			$popup_settings = $content['page_settings'];

			$popup_settings = $this->reset_popup_conditions( $popup_settings );

			$post_data = array(
				'post_type'  => jet_popup()->post_type->slug(),
				'meta_input' => array(
					'_elementor_edit_mode'     => 'builder',
					$doc_type::TYPE_META_KEY   => jet_popup()->post_type->slug(),
					'_elementor_data'          => wp_slash( json_encode( $popup_content ) ),
					'_elementor_page_settings' =>  $popup_settings,
				),
			);

			$post_data['post_title'] = ! empty( $content['title'] ) ? $content['title'] : __( 'New Popup', 'jet-popup' );

			$popup_id = wp_insert_post( $post_data );

			jet_popup()->conditions->update_site_conditions( $popup_id );

			if ( ! $popup_id ) {
				wp_die(
					esc_html__( 'Can\'t create popup. Please try again', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			wp_redirect( Elementor\Utils::get_edit_link( $popup_id ) );
			die();
		}

		/**
		 * Create new template
		 *
		 * @return [type] [description]
		 */
		public function create_predesigned_popup() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			$popup  = isset( $_REQUEST['popup'] ) ? esc_attr( $_REQUEST['popup'] ) : false;

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( jet_popup()->post_type->slug() );
			$popups = $this->predesigned_popups();

			if ( ! isset( $popups[ $popup ] ) ) {
				wp_die(
					esc_html__( 'This template not rigestered', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			$data    = $popups[ $popup ];
			$content = $data['content'];

			ob_start();
			include $content;
			$preset_data = ob_get_clean();

			$preset_data = json_decode( $preset_data, true );

			$this->create_new_popup_data( $preset_data );

		}

		/**
		 * [create_from_library_preset description]
		 * @return [type] [description]
		 */
		public function create_from_library_preset() {
			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			$preset = isset( $_REQUEST['preset'] ) ? esc_attr( $_REQUEST['preset'] ) : false;

			if ( ! $preset ) {
				wp_die(
					esc_html__( 'Stop Cheating!', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			$response_data = $this->get_preset_remote_request( $preset );

			if ( ! $response_data['success']) {
				wp_die(
					esc_html__( 'Api Server Error', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			$preset_data = json_decode( $response_data['data'], true );

			$this->create_new_popup_data( $preset_data );
		}

		/**
		 * [create_new_popup_data description]
		 * @param  [type] $preset_data [description]
		 * @return [type]              [description]
		 */
		public function create_new_popup_data( $preset_data ) {

			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( jet_popup()->post_type->slug() );

			$popup_content = $preset_data['content'];
			$popup_settings = $preset_data['page_settings'];

			$popup_content = $this->process_export_import_content( $popup_content, 'on_import' );

			$popup_settings = $this->reset_popup_conditions( $popup_settings );

			$post_data = [
				'post_title' => ! empty( $preset_data['title'] ) ? $preset_data['title'] : __( 'New Popup', 'jet-popup' ),
				'post_type'  => jet_popup()->post_type->slug(),
				'meta_input' => [
					'_elementor_edit_mode'     => 'builder',
					$doc_type::TYPE_META_KEY   => jet_popup()->post_type->slug(),
					'_elementor_data'          => wp_slash( json_encode( $popup_content ) ),
					'_elementor_page_settings' => $popup_settings,
				],
			];

			$popup_id = wp_insert_post( $post_data );

			jet_popup()->conditions->update_site_conditions( $popup_id );

			if ( ! $popup_id ) {
				wp_die(
					esc_html__( 'Can\'t create preset. Please try again', 'jet-popup' ),
					esc_html__( 'Error', 'jet-popup' )
				);
			}

			wp_redirect( Elementor\Utils::get_edit_link( $popup_id ) );

			exit();
		}

		/**
		 * Process content for export/import.
		 *
		 * Process the content and all the inner elements, and prepare all the
		 * elements data for export/import.
		 *
		 * @since 1.5.0
		 * @access protected
		 *
		 * @param array  $content A set of elements.
		 * @param string $method  Accepts either `on_export` to export data or
		 *                        `on_import` to import data.
		 *
		 * @return mixed Processed content data.
		 */
		protected function process_export_import_content( $content, $method ) {
			return ELementor\Plugin::$instance->db->iterate_data(
				$content, function( $element_data ) use ( $method ) {
					$element = ELementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

					// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
					if ( ! $element ) {
						return null;
					}

					return $this->process_element_export_import_content( $element, $method );
				}
			);
		}

		/**
		 * Process single element content for export/import.
		 *
		 * Process any given element and prepare the element data for export/import.
		 *
		 * @since 1.5.0
		 * @access protected
		 *
		 * @param Controls_Stack $element
		 * @param string         $method
		 *
		 * @return array Processed element data.
		 */
		protected function process_element_export_import_content( $element, $method ) {

			$element_data = $element->get_data();

			if ( method_exists( $element, $method ) ) {
				// TODO: Use the internal element data without parameters.
				$element_data = $element->{$method}( $element_data );
			}

			foreach ( $element->get_controls() as $control ) {
				$control_class = ELementor\Plugin::$instance->controls_manager->get_control( $control['type'] );

				// If the control isn't exist, like a plugin that creates the control but deactivated.
				if ( ! $control_class ) {
					return $element_data;
				}

				if ( method_exists( $control_class, $method ) ) {
					$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
				}
			}

			return $element_data;
		}

		/**
		 * [reset_popup_conditions description]
		 * @param  [type] $popup_settings [description]
		 * @return [type]                 [description]
		 */
		public function reset_popup_conditions( $popup_settings ) {

			foreach ( $popup_settings as $condition => $value ) {

				if ( false !== strpos( $condition, 'conditions_' ) ) {
					unset( $popup_settings[ $condition ] );
				}
			}

			return $popup_settings;
		}

		/**
		 * Retrieve the raw response from the HTTP request using the GET method.
		 *
		 * @since  1.0.0
		 * @return array|WP_Error
		 */
		public function get_preset_remote_request( $preset_id ) {

			$preset_end_point = apply_filters( 'jet-popup/preset-endpoint', 'https://jetpopup.zemez.io/wp-json/croco/v1/preset/' );

			$url = $preset_end_point . $preset_id;

			$response = wp_remote_get( $url, array(
				'timeout'   => 60,
				'sslverify' => false
			) );

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( '' === $response_code ) {
				return new \WP_Error;
			}

			$result = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! is_array( $result ) ) {
				return new \WP_Error;
			}

			return $result;
		}

		/**
		 * [predesigned_popups description]
		 * @return [type] [description]
		 */
		public function predesigned_popups() {

			$base_url = jet_popup()->plugin_url( 'templates/dummy-popups/' );
			$base_dir = jet_popup()->plugin_path( 'templates/dummy-popups/' );

			return apply_filters( 'jet-popup/predesigned-popups', [
				'popup-1' => [
					'title'    => __( 'Classic', 'jet-popup' ),
					'content'  => $base_dir . 'popup-1/preset.json',
					'thumb'    => $base_url . 'popup-1/thumbnail.png',
				],
				'popup-2' => [
					'title'    => __( 'Slide In', 'jet-popup' ),
					'content'  => $base_dir . 'popup-2/preset.json',
					'thumb'    => $base_url . 'popup-2/thumbnail.png',
				],
				'popup-3' => [
					'title'   => __( 'Bar', 'jet-popup' ),
					'content' => $base_dir . 'popup-3/preset.json',
					'thumb'   => $base_url . 'popup-3/thumbnail.png',
				],
				'popup-4' => [
					'title'   => __( 'Bordering', 'jet-popup' ),
					'content' => $base_dir . 'popup-4/preset.json',
					'thumb'   => $base_url . 'popup-4/thumbnail.png',
				],
				'popup-5' => [
					'title'   => __( 'Full View', 'jet-popup' ),
					'content' => $base_dir . 'popup-5/preset.json',
					'thumb'   => $base_url . 'popup-5/thumbnail.png',
				],
				'popup-6' => [
					'title'   => __( 'Full Width', 'jet-popup' ),
					'content' => $base_dir . 'popup-6/preset.json',
					'thumb'   => $base_url . 'popup-6/thumbnail.png',
				],
			] );
		}

		/**
		 * [admin_popup_forms description]
		 * @return [type] [description]
		 */
		public function admin_popup_forms() {

			global $current_screen;

			if ( 'edit-jet-popup' !== $current_screen->id ) {
				return false;
			}

			$import_action = add_query_arg(
				array(
					'action' => 'jet_popup_import_preset',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			$crate_action = add_query_arg(
				array(
					'action' => 'jet_popup_create_from_preset',
				),
				esc_url( admin_url( 'admin.php' ) )
			);
			$checked = ' checked';
			?>
			<div id="jet-popup-hidden-area">
				<a id="jet-popup-import-trigger" href="#" class="page-title-action"><?php echo __( 'Import Popup', 'jet-popup' ); ?></a>
				<form id="jet-popup-import-form" method="post" action="<?php echo $import_action ?>" enctype="multipart/form-data">
					<fieldset id="jet-popup-import-form-inputs">
						<input type="file" class="file-button" name="file" accept=".json,application/json,.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" required>
						<input type="submit" class="button button-primary" value="<?php echo esc_attr__( 'Import Now', 'jet-popup' ); ?>">
					</fieldset>
				</form>
				<a id="jet-popup-create-trigger" href="#" class="page-title-action"><?php echo __( 'Popup Layout', 'jet-popup' ); ?></a>
				<form  id="jet-popup-create-form" class="jet-popup-create-form" method="POST" action="<?php echo $crate_action; ?>" >
					<h3 class="jet-popup-create-form__title"><?php
						esc_html_e( 'Select Layout Preset', 'jet-popup' );
					?></h3>
					<div class="jet-popup-create-form__item-list"><?php
						foreach ( $this->predesigned_popups() as $id => $data ) {
							?>
							<div class="jet-popup-create-form__item">
								<div class="jet-popup-create-form__item-inner">
									<label class="jet-popup-create-form__label">
										<input type="radio" name="popup" value="<?php echo $id; ?>"<?php echo $checked; ?>>
										<span class="jet-popup-create-form__check dashicons dashicons-yes"></span>
										<img src="<?php echo $data['thumb']; ?>" alt="">
									</label>
									<h4 class="jet-popup-create-form__item-title"><?php
										 echo $data['title'];
									?></h4>
								</div>
							</div>
							<?php
							$checked = '';
						}
					?></div>
					<div class="jet-popup-create-form__actions">
						<button type="submit" id="jet_popup_type_submit" class="button button-primary"><?php
							esc_html_e( 'Create Popup', 'jet-popup' );
						?></button>
					</div>
				</form>
			</div><?php
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
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
