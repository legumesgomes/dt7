<?php
/**
 * Theme activation page.
 *
 * @package The7
 */

defined( 'ABSPATH' ) || exit;

function the7_register_theme_activation_page() {
	add_theme_page(
		__( 'Theme Activation', 'the7mk2' ),
		__( 'Theme Activation', 'the7mk2' ),
		'manage_options',
		'the7-theme-activation',
		'the7_render_theme_activation_page'
	);
}
add_action( 'admin_menu', 'the7_register_theme_activation_page' );

function the7_render_theme_activation_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$env_code        = theme_env_get( 'THEME_ACTIVATION_CODE' );
	$option_code     = get_site_option( 'theme_activation_code_option' );
	$env_code        = is_string( $env_code ) ? trim( $env_code ) : '';
	$option_code     = is_string( $option_code ) ? trim( $option_code ) : '';
	$using_env       = '' !== $env_code;
	$current_code    = $using_env ? $env_code : $option_code;
	$masked_code     = the7_mask_activation_code( $current_code );
	$activation_note = '';

	if ( isset( $_POST['the7_activation_submit'] ) ) {
		check_admin_referer( 'the7-theme-activation' );

		$submitted = '';
		if ( isset( $_POST['the7_activation_code'] ) ) {
			$submitted = sanitize_text_field( wp_unslash( $_POST['the7_activation_code'] ) );
		}

		update_site_option( 'theme_activation_code_option', $submitted );
		$option_code  = $submitted;
		$using_env    = '' !== $env_code;
		$current_code = $using_env ? $env_code : $option_code;
		$masked_code  = the7_mask_activation_code( $current_code );

		$activation_note = __( 'Activation code saved.', 'the7mk2' );
	}

	$status_label = theme_is_activated() ? __( 'Activated', 'the7mk2' ) : __( 'Not activated', 'the7mk2' );
	$source_label = $using_env ? __( 'Loaded from .env', 'the7mk2' ) : ( '' !== $option_code ? __( 'Stored in database', 'the7mk2' ) : __( 'Not set', 'the7mk2' ) );
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Theme Activation', 'the7mk2' ); ?></h1>

		<?php if ( '' !== $activation_note ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html( $activation_note ); ?></p>
			</div>
		<?php endif; ?>

		<div class="card" style="max-width: 720px;">
			<h2><?php echo esc_html__( 'Activation Status', 'the7mk2' ); ?></h2>
			<p><strong><?php echo esc_html( $status_label ); ?></strong></p>
			<p><?php echo esc_html__( 'Source:', 'the7mk2' ); ?> <?php echo esc_html( $source_label ); ?></p>
			<?php if ( '' !== $masked_code ) : ?>
				<p><?php echo esc_html__( 'Code:', 'the7mk2' ); ?> <code><?php echo esc_html( $masked_code ); ?></code></p>
			<?php endif; ?>
		</div>

		<form method="post" style="max-width: 720px;">
			<?php wp_nonce_field( 'the7-theme-activation' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="the7_activation_code"><?php echo esc_html__( 'Activation code', 'the7mk2' ); ?></label>
						</th>
						<td>
							<input name="the7_activation_code" id="the7_activation_code" type="text" class="regular-text" value="" autocomplete="off">
							<p class="description">
								<?php echo esc_html__( 'If a .env file is present, its value takes priority over the database.', 'the7mk2' ); ?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( __( 'Save Activation Code', 'the7mk2' ), 'primary', 'the7_activation_submit' ); ?>
		</form>
	</div>
	<?php
}