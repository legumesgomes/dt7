<?php
/**
 * The7 HTML helpers.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'presscore_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Navigation arguments.
	 * @return void
	 */
	function presscore_posts_navigation( $args = array() ) {
		global $wp_query, $wp_rewrite;

		if ( ! $wp_query->max_num_pages ) {
			return;
		}

		$current = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

		// Array of arguments used to generate the paginated links.
		$args = wp_parse_args(
			$args,
			array(
				'base'      => add_query_arg( 'paged', '%#%' ),
				'format'    => '',
				'total'     => $wp_query->max_num_pages,
				'current'   => $current,
				'show_all'  => false,
				'end_size'  => 1,
				'mid_size'  => 2,
				'prev_next' => true,
				'prev_text' => '<i class="dt-icon-the7-arrow-41-1" aria-hidden="true"></i><span class="screen-reader-text">' . esc_html__( 'Previous page', 'dt-the7' ) . '</span>',
				'next_text' => '<i class="dt-icon-the7-arrow-41-2" aria-hidden="true"></i><span class="screen-reader-text">' . esc_html__( 'Next page', 'dt-the7' ) . '</span>',
				'type'      => 'list',
				'add_args'  => false,
				'add_fragment' => '',
			)
		);

		// Save any existing query string arguments.
		$existing_args = array();
		parse_str( isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '', $existing_args );

		if ( array_key_exists( 'paged', $existing_args ) ) {
			unset( $existing_args['paged'] );
		}

		$args['add_args'] = $existing_args;

		if ( $wp_rewrite->using_permalinks() ) {
			// Fix path.
			$base = trailingslashit( get_pagenum_link( 1 ) );
			$args['base'] = $base . '%_%';
			$args['format'] = user_trailingslashit( $wp_rewrite->pagination_base . '/%#%/', 'paged' );
		} else {
			$args['format'] = '';
		}

		echo wp_kses_post( paginate_links( $args ) );
	}

endif;

if ( ! function_exists( 'presscore_get_share_buttons' ) ) :

	/**
	 * Get share buttons.
	 *
	 * @param array  $buttons Buttons array.
	 * @param string $classes Extra classes.
	 *
	 * @return string
	 */
	function presscore_get_share_buttons( $buttons, $classes = '' ) {
		$buttons = (array) $buttons;

		$output = '';

		$allowed_tags = array(
			'a' => array(
				'class' => true,
				'href'  => true,
				'target' => true,
				'rel'   => true,
			),
			'span' => array(
				'class' => true,
			),
		);

		foreach ( $buttons as $button ) {
			$title = isset( $button['title'] ) ? $button['title'] : '';
			$icon  = isset( $button['icon'] ) ? $button['icon'] : '';
			$url   = isset( $button['url'] ) ? $button['url'] : '#';

			$output .= wp_kses( presscore_get_share_button( $title, $icon, $url, $classes ), $allowed_tags );
		}

		return $output;
	}

endif;

if ( ! function_exists( 'presscore_get_share_button' ) ) :

	/**
	 * Get share button.
	 *
	 * @param string $title Title.
	 * @param string $icon Icon class.
	 * @param string $url Url.
	 * @param string $classes Extra classes.
	 *
	 * @return string
	 */
	function presscore_get_share_button( $title, $icon, $url, $classes = '' ) {
		$icon_attributes = array();
		$target = '_blank';

		if ( 'mailto' === substr( $url, 0, 6 ) ) {
			$url = 'mailto:' . esc_attr( $url );
			$target = '_top';
		} else {
			$url = esc_attr( $url );
		}

		$icon_attributes[] = 'href="' . $url . '"';
		$icon_attributes[] = 'target="' . esc_attr( $target ) . '"';

		$icon_classes = is_array( $classes ) ? $classes : array();
		$icon_classes[] = $icon;

		$icon_attributes[] = 'class="' . esc_attr( implode( ' ',  $icon_classes ) ) . '"';

		$output = '<a ' . implode( ' ', $icon_attributes ) . '><span class="soc-font-icon"></span><span class="screen-reader-text">' . $title . '</span></a>';

		return $output;
	}

endif;

if ( ! function_exists( 'presscore_get_device_icons' ) ) :

	/**
	 * Returns device icons meta tags array.
	 *
	 * @since 2.2.1
	 *
	 * @return string
	 */
	function presscore_get_device_icons() {
        $output = '';

		if ( ! function_exists( 'dt_get_of_uploaded_image' ) ) {
			return $output;
		}

		$icons = array(
			'general-favicon'    => '16x16',
			'general-favicon_hd' => '32x32',
		);
		foreach ( $icons as $opt => $sizes ) {
			$icon = dt_get_of_uploaded_image( of_get_option( $opt ) );
			if ( ! $icon ) {
			    continue;
            }

			$mime = the7_get_image_mime( $icon );
			$output .= sprintf( '<link rel="icon" href="%s" type="%s" sizes="%s"/>', $icon, $mime, $sizes );
		}

		$device_icons = array(
			array(
				'option_id' => 'general-handheld_icon-old_iphone',
			),
			array(
				'option_id' => 'general-handheld_icon-old_ipad',
				'sizes' => '76x76',
			),
			array(
				'option_id' => 'general-handheld_icon-retina_iphone',
				'sizes' => '120x120',
			),
			array(
				'option_id' => 'general-handheld_icon-retina_ipad',
				'sizes' => '152x152',
			),
		);

		foreach ( $device_icons as $icon ) {
			$src = dt_get_of_uploaded_image( of_get_option( $icon['option_id'] ) );
			if ( $src ) {
				$output .= '<link rel="apple-touch-icon"' . ( empty( $icon['sizes'] ) ? '' : ' sizes="' . esc_attr( $icon['sizes'] ) . '"' ) . ' href="' . esc_url( $src ) . '">';
			}
		}

		return $output;
	}

endif;
