<?php
/**
 * The7 dashboard registration form.
 *
 * @package The7
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="the7-registration-section">
	<h3><?php echo esc_html_x( 'Thank You for Choosing The7', 'admin', 'the7mk2' ); ?></h3>
	<p>
		<?php
		echo esc_html_x(
			'Activate this copy of the theme to get access to premium plugins, pre-made websites, 1-click updates and more.',
			'admin',
			'the7mk2'
		);
		?>
	</p>
</div>
<div class="the7-divider"></div>
<div class="the7-registration-section">
	<h3><?php echo esc_html( _x( 'Theme Registration', 'admin', 'the7mk2' ) ); ?></h3>
	<form method="post">
		<?php settings_fields( 'the7_theme_registration' ); ?>

		<p>
			<?php echo esc_html_x( 'Activation Code:', 'admin', 'the7mk2' ); ?><br>
			<input id="the7_activation_code" class="of-input" name="theme_activation_code_option" type="text" value="" size="36" title="<?php echo esc_attr_x( 'Activation Code', 'admin', 'the7mk2' ); ?>">
		</p>
		<p>
			<?php echo esc_html_x( 'Tip: you can set THEME_ACTIVATION_CODE in a .env file to override this value.', 'admin', 'the7mk2' ); ?>
		</p>
		<p>
			<button type="submit" id="the7-register-theme-button" class="button button-primary" name="register_theme" value="register" title="<?php echo esc_attr_x( 'Activate Theme', 'admin', 'the7mk2' ); ?>"><?php echo esc_html_x( 'Activate Theme', 'admin', 'the7mk2' ); ?></button>
		</p>
	</form>
</div>