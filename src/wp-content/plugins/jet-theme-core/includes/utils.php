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

if ( ! class_exists( 'Jet_Theme_Core_Utils' ) ) {

	/**
	 * Define Jet_Theme_Core_Utils class
	 */
	class Jet_Theme_Core_Utils {

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public static function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'jet-theme-core/post-types-list/deprecated',
				array(
					'attachment',
					'elementor_library',
					jet_theme_core()->templates->post_type,
				)
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

		/**
		 * Returns all custom taxonomies
		 *
		 * @return [type] [description]
		 */
		public static function get_taxonomies() {

			$taxonomies = get_taxonomies( array(
				'public'   => true,
				'_builtin' => false
			), 'objects' );

			$deprecated = apply_filters(
				'jet-theme-core/taxonomies-list/deprecated',
				array()
			);

			$result = array();

			if ( empty( $taxonomies ) ) {
				return $result;
			}

			foreach ( $taxonomies as $slug => $tax ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $tax->label;

			}

			return $result;

		}

		public static function search_posts_by_type( $type, $query ) {

			add_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10, 2 );

			$posts = get_posts( array(
				'post_type'           => $type,
				'ignore_sticky_posts' => true,
				'posts_per_page'      => -1,
				'suppress_filters'    => false,
				's_title'             => $query,
			) );

			remove_filter( 'posts_where', array( __CLASS__, 'force_search_by_title' ), 10, 2 );

			$result = array();

			if ( ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$result[] = array(
						'id'   => $post->ID,
						'text' => $post->post_title,
					);
				}
			}

			return $result;
		}

		/**
		 * Force query to look in post title while searching
		 * @return [type] [description]
		 */
		public static function force_search_by_title( $where, $query ) {

			$args = $query->query;

			if ( ! isset( $args['s_title'] ) ) {
				return $where;
			} else {
				global $wpdb;

				$searh = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
				$where .= " AND {$wpdb->posts}.post_title LIKE '%$searh%'";

			}

			return $where;
		}

		public static function search_terms_by_tax( $tax, $query ) {

			$terms = get_terms( array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
				'name__like' => $query,
			) );

			$result = array();

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$result[] = array(
						'id'   => $term->term_id,
						'text' => $term->name,
					);
				}
			}

			return $result;

		}

		/**
		 * Perform plugin installtion by passed plugin slug and plugin package URL (optional)
		 *
		 * @param  [type]  $plugin     [description]
		 * @param  boolean $plugin_url [description]
		 * @return [type]              [description]
		 */
		public static function install_plugin( $plugin, $plugin_url = false ) {

			$status = array();

			if ( ! current_user_can( 'install_plugins' ) ) {
				$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'jet-theme-core' );
				wp_send_json_error( $status );
			}

			if ( ! $plugin ) {
				$status['errorMessage'] = __( 'Plugin slug is required', 'jet-theme-core' );
				wp_send_json_error( $status );
			}

			if ( ! $plugin_url ) {

				$api_url = jet_theme_core()->api->api_base();
				$license = jet_theme_core()->dashboard->get( 'license' );
				$package = add_query_arg(
					array(
						'ct_api_action' => 'get_plugin',
						'license'       => $license->get_license(),
						'url'           => urlencode( home_url( '/' ) ),
						'slug'          => dirname( $plugin ),
					),
					$api_url
				);

			} else {

				$package = $plugin_url;

			}


			include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Plugin_Upgrader( $skin );
			$result   = $upgrader->install( $package );

			if ( is_wp_error( $result ) ) {
				$status['errorCode']    = $result->get_error_code();
				$status['errorMessage'] = $result->get_error_message();
				wp_send_json_error( $status );
			} elseif ( is_wp_error( $skin->result ) ) {
				$status['errorCode']    = $skin->result->get_error_code();
				$status['errorMessage'] = $skin->result->get_error_message();
				wp_send_json_error( $status );
			} elseif ( $skin->get_errors()->get_error_code() ) {
				$status['errorMessage'] = $skin->get_error_messages();
				wp_send_json_error( $status );
			} elseif ( is_null( $result ) ) {
				global $wp_filesystem;

				$status['errorCode']    = 'unable_to_connect_to_filesystem';
				$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'jet-theme-core' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
					$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				wp_send_json_error( $status );
			}

			$all_plugins = get_plugins();
			$plugin_data = isset( $all_plugins[ $plugin ] ) ? $all_plugins[ $plugin ] : array();

			if ( isset( $plugin_data['Version'] ) ) {
				$version = $plugin_data['Version'];
			} else {
				$version = '---';
			}

			$status['version'] = $version;

			wp_send_json_success( $status );

		}

		/**
		 * Performs plugin activation
		 *
		 * @param  [type] $plugin [description]
		 * @return [type]         [description]
		 */
		public static function activate_plugin( $plugin ) {

			$status = array();

			if ( ! current_user_can( 'activate_plugins' ) ) {
				$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'jet-theme-core' );
				wp_send_json_error( $status );
			}

			if ( ! $plugin ) {
				$status['errorMessage'] = __( 'Plugin slug is required', 'jet-theme-core' );
				wp_send_json_error( $status );
			}

			$activate = null;

			if ( ! is_plugin_active( $plugin ) ) {
				$activate = activate_plugin( $plugin );
			}

			if ( is_wp_error( $activate ) ) {
				$status['errorMessage'] = $activate->get_error_message();
				wp_send_json_error( $status );
			}

			wp_send_json_success( apply_filters( 'jet-theme-core/utils/activate-plugin-response', $status ) );

		}

	}

}
