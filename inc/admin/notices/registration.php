<?php

namespace The7\Admin\Notices;

defined( 'ABSPATH' ) || exit;

class Registration extends Abstract_Notice {

	public function render() {
		$register_url = admin_url( 'themes.php?page=the7-theme-activation' );
		?>

		<p role="heading"><?php echo esc_html_x( 'Thank you for choosing The7!', 'admin', 'the7mk2' ); ?></p>
		<p>
			<?php
			echo wp_kses_post(
				sprintf(
					// translators: %s: register link.
					_x( 'Activate this copy of the theme to access premium plugins, pre-made website templates, one-click updates, and more. %s', 'admin', 'the7mk2' ),
					'<a href="' . esc_url( $register_url ) . '">' . esc_html_x( 'Open activation settings', 'admin', 'the7mk2' ) . '</a>'
				)
			);
			?>
		</p>

		<?php
	}

	public function get_code() {
		return 'the7_registration_notice';
	}

	public function is_visible() {
		return ! presscore_theme_is_activated();
	}

	public function get_wrapper_class() {
		return 'notice-info';
	}
}
