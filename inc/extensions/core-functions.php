<?php
/**
 * The7 core functions.
 *
 * @package The7
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'the7_register_scripts' ) ) :

	/**
	 * Register The7 scripts and styles.
	 *
	 * @since 1.0.0
	 */
	function the7_register_scripts() {
		wp_register_style( 'the7-main-style', get_stylesheet_uri(), array(), THE7_VERSION );

		wp_register_script( 'the7-main', PRESSCORE_THEME_URI . '/js/main', array( 'jquery' ), THE7_VERSION, true );
		wp_register_script( 'the7-ajax-pagination', PRESSCORE_THEME_URI . '/js/ajax-pagination', array( 'jquery' ), THE7_VERSION, true );
	}

endif;

if ( ! function_exists( 'the7_enqueue_scripts' ) ) :

	/**
	 * Enqueue The7 scripts and styles.
	 *
	 * @since 1.0.0
	 */
	function the7_enqueue_scripts() {
		wp_enqueue_style( 'the7-main-style' );

		wp_enqueue_script( 'the7-main' );
		wp_enqueue_script( 'the7-ajax-pagination' );
	}

endif;

if ( ! function_exists( 'presscore_add_action' ) ) :

	/**
	 * Helper function, which add action for all languages.
	 *
	 * @since 7.0.0
	 *
	 * @param string   $tag            The name of the action to which the $function_to_add is hooked.
	 * @param callable $function_to_add The name of the function you wish to be called.
	 * @param int      $priority       Optional. Used to specify the order in which the functions associated with a particular action are executed.
	 * @param int      $accepted_args   Optional. The number of arguments the function accepts.
	 */
	function presscore_add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		add_action( $tag, $function_to_add, $priority, $accepted_args );

		if ( function_exists( 'icl_add_action' ) ) {
			icl_add_action( $tag, $function_to_add, $priority, $accepted_args );
		}
	}

endif;

if ( ! function_exists( 'presscore_add_filter' ) ) :

	/**
	 * Helper function, which add filter for all languages.
	 *
	 * @since 7.0.0
	 *
	 * @param string   $tag            The name of the filter to which the $function_to_add is hooked.
	 * @param callable $function_to_add The name of the function you wish to be called.
	 * @param int      $priority       Optional. Used to specify the order in which the functions associated with a particular filter are executed.
	 * @param int      $accepted_args   Optional. The number of arguments the function accepts.
	 */
	function presscore_add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		add_filter( $tag, $function_to_add, $priority, $accepted_args );

		if ( function_exists( 'icl_add_filter' ) ) {
			icl_add_filter( $tag, $function_to_add, $priority, $accepted_args );
		}
	}

endif;

if ( ! function_exists( 'presscore_get_blog_url' ) ) :

	/**
	 * Get current language blog url.
	 *
	 * @return string
	 */
	function presscore_get_blog_url() {
		if ( function_exists( 'icl_get_home_url' ) ) {
			return icl_get_home_url();
		}

		return home_url();
	}

endif;

if ( ! function_exists( 'presscore_get_header_image' ) ) :

	/**
	 * Retrieve header image for current post.
	 *
	 * @since 1.0.0
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
	 *
	 * @return array
	 */
	function presscore_get_header_image( $post = null ) {
		$img_id      = get_post_thumbnail_id( $post );
		$img_meta    = wp_get_attachment_metadata( $img_id );
		$img_data    = array();
		$src         = '';
		$img_meta_id = 0;

		if ( $img_id && $img_meta && ! empty( $img_meta['image_meta'] ) ) {
			$img_data    = $img_meta['image_meta'];
			$img_meta_id = $img_id;
		}

		if ( ! $img_meta_id ) {
			$src = presscore_get_theme_option( 'general-post-header-bg-image' );
		}

		return array(
			'img_id'   => $img_id,
			'img_meta' => $img_data,
			'img_meta_id' => $img_meta_id,
			'img_src'  => $src,
		);
	}

endif;

if ( ! function_exists( 'presscore_get_submitted_data' ) ) :

	/**
	 * Get sanitized and unslashed submitted data.
	 *
	 * @param array $data Raw data to sanitize.
	 *
	 * @return array
	 */
	function presscore_get_submitted_data( $data = array() ) {
		if ( empty( $data ) ) {
			return array();
		}

		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$data[ $key ] = presscore_get_submitted_data( $value );
				continue;
			}

			$data[ $key ] = sanitize_text_field( wp_unslash( $value ) );
		}

		return $data;
	}

endif;

if ( ! function_exists( 'presscore_get_images_sizes' ) ) :

	/**
	 * Get all registered images sizes.
	 *
	 * @return array
	 */
	function presscore_get_images_sizes() {
		global $_wp_additional_image_sizes;

		$default_sizes = array( 'thumbnail', 'medium', 'large' );
		$additional_sizes = is_array( $_wp_additional_image_sizes ) ? $_wp_additional_image_sizes : array();

		foreach ( $default_sizes as $size ) {
			$additional_sizes[ $size ] = array(
				'width'  => intval( get_option( "{$size}_size_w" ) ),
				'height' => intval( get_option( "{$size}_size_h" ) ),
				'crop'   => (bool) get_option( "{$size}_crop" ),
			);
		}

		return $additional_sizes;
	}

endif;

if ( ! function_exists( 'presscore_template_manager' ) ) :

	/**
	 * @since 7.0.0
	 *
	 * @return The7_Template_Manager
	 */
	function presscore_template_manager() {
		return The7_Template_Manager::get_instance();
	}

endif;

if ( ! function_exists( 'presscore_load_template' ) ) :

	/**
	 * Load the template file.
	 *
	 * @param string $template_file Template file name.
	 * @param array  $args          Optional. Variable list to pass to the template. Default empty array.
	 * @param bool   $require_once  Optional. Whether to require_once or require. Default true.
	 *
	 * @return mixed
	 */
	function presscore_load_template( $_template_file, $args = array(), $require_once = true ) {
		return presscore_template_manager()->load_template( $_template_file, $args, $require_once );
	}

endif;

function presscore_split_classes( $class ) {
	$classes = array();

	if ( $class ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_map( 'esc_attr', $class );
	}

	return $classes;
}

function presscore_sanitize_classes( $classes ) {
	$classes = array_map( 'esc_attr', $classes );
	$classes = array_filter( $classes );
	$classes = array_unique( $classes );
	return $classes;
}

if ( ! defined( 'THEME_ACTIVATION_EXPECTED_HASH' ) ) {
	define( 'THEME_ACTIVATION_EXPECTED_HASH', hash( 'sha256', 'THE7-LOCAL-ACTIVATION' ) );
}

function theme_env_get( $key, $default = null ) {
	static $env_cache = null;

	if ( null === $env_cache ) {
		$env_cache = array();
		$paths     = array(
			trailingslashit( get_stylesheet_directory() ) . '.env',
			trailingslashit( get_template_directory() ) . '.env',
			ABSPATH . '.env',
		);

		foreach ( array_unique( $paths ) as $path ) {
			if ( ! is_readable( $path ) ) {
				continue;
			}

			$lines = file( $path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
			if ( ! is_array( $lines ) ) {
				continue;
			}

			foreach ( $lines as $line ) {
				$line = trim( $line );
				if ( '' === $line || 0 === strpos( $line, '#' ) ) {
					continue;
				}

				if ( false === strpos( $line, '=' ) ) {
					continue;
				}

				list( $env_key, $env_value ) = explode( '=', $line, 2 );
				$env_key   = trim( $env_key );
				$env_value = trim( $env_value );

				if ( '' === $env_key ) {
					continue;
				}

				if ( '' !== $env_value ) {
					$quote = $env_value[0];
					if ( '"' === $quote || "'" === $quote ) {
						if ( substr( $env_value, -1 ) === $quote ) {
							$env_value = substr( $env_value, 1, -1 );
						} else {
							$env_value = substr( $env_value, 1 );
						}
					} else {
						$env_value = preg_split( '/\s+#/', $env_value, 2 )[0];
					}
				}

				$env_cache[ $env_key ] = $env_value;
			}
		}
	}

	return array_key_exists( $key, $env_cache ) ? $env_cache[ $key ] : $default;
}

function the7_get_activation_code() {
	$env_code = theme_env_get( 'THEME_ACTIVATION_CODE' );
	if ( is_string( $env_code ) && '' !== trim( $env_code ) ) {
		return trim( $env_code );
	}

	$option_code = get_site_option( 'theme_activation_code_option' );

	return is_string( $option_code ) ? trim( $option_code ) : '';
}

function the7_mask_activation_code( $code ) {
	$code = trim( (string) $code );

	if ( '' === $code ) {
		return '';
	}

	$length = strlen( $code );
	if ( $length <= 4 ) {
		return str_repeat( '*', $length );
	}

	$start = substr( $code, 0, 4 );
	$end   = $length > 8 ? substr( $code, -4 ) : substr( $code, -2 );
	$mask  = max( 0, $length - strlen( $start ) - strlen( $end ) );

	return $start . str_repeat( '*', $mask ) . $end;
}

function the7_activation_code_matches( $code ) {
	$code = trim( (string) $code );
	if ( '' === $code ) {
		return false;
	}

	$expected_hash = apply_filters( 'the7_activation_expected_hash', THEME_ACTIVATION_EXPECTED_HASH );

	return hash_equals( $expected_hash, hash( 'sha256', $code ) );
}

function theme_is_activated() {
	$code = the7_get_activation_code();

	return the7_activation_code_matches( $code );
}

function presscore_theme_is_activated() {
	return theme_is_activated();
}

function presscore_activate_theme( $code = null ) {
	if ( null !== $code ) {
		update_site_option( 'theme_activation_code_option', $code );
	}

	update_site_option( 'the7_registered', 'yes' );
	do_action( 'the7_after_theme_activation' );
}

function presscore_deactivate_theme() {
	delete_site_option( 'the7_registered' );
	delete_site_option( 'theme_activation_code_option' );
	do_action( 'the7_after_theme_deactivation' );
}

function presscore_delete_purchase_code() {
	delete_site_option( 'the7_purchase_code' );
	delete_site_option( 'theme_activation_code_option' );
}

function presscore_get_purchase_code() {
	$code = the7_get_activation_code();
	if ( '' !== $code ) {
		return $code;
	}

	$legacy_code = get_site_option( 'the7_purchase_code' );

	return is_string( $legacy_code ) ? $legacy_code : '';
}

function presscore_get_censored_purchase_code() {
	return the7_mask_activation_code( presscore_get_purchase_code() );
}

/**
 * Check if silence mode enabled
 *
 * @return boolean
 */
function presscore_is_silence_enabled() {
	return presscore_theme_is_activated() && defined('THE7_SILENCE_BUNDLED_PLUGINS') && THE7_SILENCE_BUNDLED_PLUGINS;
}

/**
 * Wrapper for set_time_limit to see if it is enabled.
 *
 * @since 6.4.0
 * @param int $limit Time limit.
 */
function the7_set_time_limit( $limit = 0 ) {
	if ( function_exists( 'set_time_limit' ) && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) ) {
		@set_time_limit( $limit ); // @codingStandardsIgnoreLine
	}
}

if ( ! function_exists( 'the7_get_theme_version' ) ):

	/**
     * Returns parent theme version.
     *
     * @TODO: Remove in 6.1.0
     *
     * @deprecated
     *
	 * @return false|string
	 */
    function the7_get_theme_version() {
        return THE7_VERSION;
    }

endif;

/**
 * Add a submenu page after specified submenu page.
 *
 * This function takes a capability which will be used to determine whether
 * or not a page is included in the menu.
 *
 * The function which is hooked in to handle the output of the page must check
 * that the user has the required capability as well.
 *
 * @since 7.0.0
 *
 * @global array $submenu
 * @global array $menu
 * @global array $_wp_real_parent_file
 * @global bool  $_wp_submenu_nopriv
 * @global array $_registered_pages
 * @global array $_parent_pages
 *
 * @param string   $parent_slug The slug name for the parent menu (or the file name of a standard
 *                              WordPress admin page).
 * @param string   $page_title  The text to be displayed in the title tags of the page when the menu
 *                              is selected.
 * @param string   $menu_title  The text to be used for the menu.
 * @param string   $capability  The capability required for this menu to be displayed to the user.
 * @param string   $menu_slug   The slug name to refer to this menu by. Should be unique for this menu
 *                              and only include lowercase alphanumeric, dashes, and underscores characters
 *                              to be compatible with sanitize_key(). Since 3.0, this slug is used to
 *                              build the URL for the page.
 * @param callable $function    Optional. The function to be called to output the content for this page.
 * @param int      $position    Optional. The position in the menu order this submenu should appear.
 *
 * @return false|string The resulting page's hook_suffix, or false if the user does not have the capability
 *                      required.
 */
function presscore_add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null ) {
	global $submenu;

	if ( isset( $submenu[ $parent_slug ] ) ) {
		$position = (int) $position;
		$position = $position ? $position : count( $submenu[ $parent_slug ] ) + 1;

		if ( $position <= 0 ) {
			$position = 1;
		}

		$before = array_slice( $submenu[ $parent_slug ], 0, $position, true );
		$after  = array_slice( $submenu[ $parent_slug ], $position, null, true );

		$before[] = array( $menu_title, $capability, $menu_slug, $page_title );

		$submenu[ $parent_slug ] = array_merge( $before, $after );
	} else {
		add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	return $menu_slug;
}
