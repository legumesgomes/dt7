<?php
/**
 * @package The7
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class The7_Theme_Auto_Deactivation
 */
class The7_Theme_Auto_Deactivation {


	/**
	 * Add hooks.
	 */
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'add_admin_notice' ) );
		add_action( 'the7_after_theme_activation', array( __CLASS__, 'dismiss_admin_notice_on_theme_activation' ) );
		add_action( 'the7_demo_content_before_content_import', array( __CLASS__, 'add_auto_deactivation_check' ) );
		add_filter( 'upgrader_pre_download', array( __CLASS__, 'add_auto_deactivation_check' ), 10, 3 );
	}

	/**
	 * Add admin notice.
	 */
	public static function add_admin_notice() {
		// Notice is now registered via the7_add_admin_notices() in inc/admin/admin-notices.php
	}

	/**
	 * Dismiss admin notice on theme activation.
	 */
	public static function dismiss_admin_notice_on_theme_activation() {
		the7_admin_notices()->dismiss_notice( 'the7_auto_deactivation' );
	}

	/**
	 * Add auto deactivation check to 'http_response' filter. Used with 'upgrader_pre_download' filter.
	 *
	 * @param bool $r
	 *
	 * @return bool
	 */
	public static function add_auto_deactivation_check( $r = false ) {


		return $r;
	}

	/**
	 * Verify purchase code on 403 response header.
	 *
	 * @param $response
	 *
	 * @return array|WP_Error Array containing 'headers', 'body', 'response', 'cookies', 'filename'.
	 *                        A WP_Error instance upon error.
	 */
	public static function http_response_filter( $response ) {


		return $response;
	}
}
