<?php
/**
 * Functions related to the Catapult installation.
 *
 * @package Catapult
 * @since   2.2.6
 * @since   2.2.9
 * @since   2.2.10
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 * @since   3.1.4
 */

namespace Catapult\Installation;

/**
 * If Catapult has not been installed, activate the default plugins when the theme is first activated and removed unusued themes and plugins.
 *
 * @param string $old_theme  Old theme name.
 */
function activate_default_plugins_and_remove_unused_themes_and_plugins_on_theme_activation( $old_theme ) {
	if ( empty( get_option( 'catapult_installed' ) ) ) {
		update_option( 'catapult_installed', false, false );

		activate_plugins();
	}

	$wr2x_options = get_option( 'wr2x_options' ) ?? array();
	update_option( 'wr2x_options', $wr2x_options, false );

	$rta_image_sizes = get_option( 'rta_image_sizes' ) ?? array();
	update_option( 'rta_image_sizes', $rta_image_sizes, false );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/theme.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$themes_to_remove = array(
		'genesis-block-theme',
		'twentytwentythree',
		'twentytwentytwo',
		'twentytwentyone',
		'twentytwenty',
	);

	$plugins_to_remove = array(
		'genesis-blocks',
		'akismet',
	);

	foreach ( $plugins_to_remove as $plugin ) {
		$plugin_file = $plugin . '/' . $plugin . '.php';

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			deactivate_plugins( $plugin_file );
			delete_plugins( array( $plugin_file ) );
		}
	}

	foreach ( $themes_to_remove as $theme ) {
		if ( wp_get_theme( $theme )->exists() ) {
			delete_theme( $theme );
		}
	}
}
add_action( 'after_switch_theme', 'Catapult\Installation\activate_default_plugins_and_remove_unused_themes_and_plugins_on_theme_activation' );

/**
 * Display the intallation notice in the dashboard.
 */
function display_installation_notice() {
	if ( ! empty( get_option( 'catapult_installed' ) ) ) {
		return;
	}
	?>

	<div class="notice notice-info installation-notice" style="display: flex; flex-direction: row; padding: 1rem;">
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/screenshot.png" alt="<?php esc_html_e( 'Catapult Symbol', 'catapult' ); ?>" class="installation-notice__image" width="120" height="120" style="flex: 0 0 7rem; width: 7rem; height: 7rem; object-fit: cover; margin-right: 1rem;">

		<div class="installation-notice__content">
			<h2 class="installation-notice__heading" style="margin-top: 0;"><?php esc_html_e( 'Welcome to the Computan Catapult WordPress Framework!', 'catapult' ); ?></h2>

			<p><?php esc_html_e( 'It looks like the default data has not been previously imported. Click the first button below to import the default data, or click the second button to skip this step.', 'catapult' ); ?></p>

			<p><?php esc_html_e( 'Importing the default data will not override existing data. This will run a WordPress Importer import action for the library blocks, theme blocks, Gravity Forms forms, pages, users, and any other post type included in the wp-content/exports/export.xml and wp-content/exports/block-library-export.xml files. This can only be done once.', 'catapult' ); ?></p>

			<div class="installation-notice__forms" style="margin-top: 1rem;">
				<form method="post" action="<?php echo esc_html( get_admin_url() ); ?>" onsubmit="return confirm('<?php esc_html_e( 'Are you sure you want to install the default data? This cannot be undone.', 'catapult' ); ?>');" style="float: left; margin: 0 0.5rem 0.5rem 0;">
					<?php wp_nonce_field( 'catapult_install_nonce', 'catapult_install_nonce' ); ?>

					<input type="submit" class="button-primary" value="<?php esc_html_e( 'Install Default Data', 'catapult' ); ?>" name="catapult_install">
				</form>

				<form method="post" action="" style="float: left;">
					<?php wp_nonce_field( 'catapult_ignore_install_nonce', 'catapult_ignore_install_nonce' ); ?>

					<input type="submit" class="button-secondary" value="<?php esc_html_e( 'Skip Default Data Install', 'catapult' ); ?>" name="catapult_ignore_install">
				</form>
			</div>
		</div>
	</div>

	<?php
}
add_action( 'admin_notices', 'Catapult\Installation\display_installation_notice' );

/**
 * Process the installation.
 */
function process_installation() {
	if ( ! empty( $_POST['catapult_ignore_install'] ) && ! empty( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['catapult_ignore_install_nonce'] ) ), 'catapult_ignore_install_nonce' ) ) ) {
		update_option( 'catapult_installed', true, false );
	}

	if ( ! empty( $_POST['catapult_install'] ) && ! empty( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['catapult_install_nonce'] ) ), 'catapult_install_nonce' ) ) ) {
		global $wp_import;

		$export_file               = ABSPATH . 'wp-content/exports/export.xml';
		$block_library_export_file = ABSPATH . 'wp-content/exports/block-library-export.xml';
		$forms_file                = ABSPATH . 'wp-content/exports/forms.json';

		if ( ! file_exists( $export_file ) || ! file_exists( $block_library_export_file ) ) {
			render_message( __( 'Error importing default data.', 'catapult' ), __( 'Can\'t find export file(s).', 'catapult' ) );

			return;
		}

		if ( ! class_exists( 'WP_Importer' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			require_once ABSPATH . 'wp-content/plugins/wordpress-importer/parsers/class-wxr-parser.php';
			require_once ABSPATH . 'wp-content/plugins/wordpress-importer/parsers/class-wxr-parser-simplexml.php';
			require_once ABSPATH . 'wp-content/plugins/wordpress-importer/class-wp-import.php';
		}

		$importer = new \WP_Import();

		ob_start();
		$import_result = $importer->import( $export_file );
		$content       = ob_get_contents();
		ob_end_clean();

		ob_start();
		$block_library_import_result = $importer->import( $block_library_export_file );
		$block_library_content       = ob_get_contents();
		ob_end_clean();

		if ( is_wp_error( $import_result ) ) {
			render_message( __( 'Error importing default data.', 'catapult' ), $content, 'error', false );
		} elseif ( is_wp_error( $block_library_import_result ) ) {
			render_message( __( 'Error importing default data.', 'catapult' ), $block_library_content, 'error', false );
		} else {
			render_message( __( 'Imported default data.', 'catapult' ), $content, 'success', false );
			render_message( __( 'Imported block library posts.', 'catapult' ), $block_library_content, 'success', false );

			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', 829 );
			update_option( 'catapult_installed', true, false );
		}

		if ( ! file_exists( $forms_file ) ) {
			render_message( __( 'Error importing Gravity Forms forms.', 'catapult' ), __( 'Can\'t find wp-content/exports/forms.json file.', 'catapult' ) );
		} elseif ( ! class_exists( 'GFExport' ) ) {
			render_message( __( 'Error importing Gravity Forms forms.', 'catapult' ), __( 'Gravity Forms plugin not active.', 'catapult' ) );
		} else {
			$gf_export = new \GFExport();

			$gf_export->import_file( $forms_file );
		}
	}
}
add_action( 'admin_notices', 'Catapult\Installation\process_installation', -999 );


/**
 * Activates the necessary plugins for Catapult.
 */
function activate_plugins() {
	if ( ! is_admin() ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

	$plugins_to_activate = array(
		'advanced-custom-fields-pro/acf.php',
		'contact-form-7/wp-contact-form-7.php',
		'regenerate-thumbnails-advanced/regenerate-thumbnails-advanced.php',
		'safe-svg/safe-svg.php',
		'wp-retina-2x/wp-retina-2x.php',
		'wordpress-importer/wordpress-importer.php',
		'wordpress-seo/wp-seo.php',
		'shortpixel-image-optimiser/wp-shortpixel.php',
	);

	foreach ( $plugins_to_activate as $plugin ) {
		$plugin_path = ABSPATH . 'wp-content/plugins/' . $plugin;
		$result      = activate_plugin( $plugin_path );

		if ( is_wp_error( $result ) ) {
			render_message( sprintf( __( 'Error activating plugin: %s.', 'catapult' ), 'wp-content/plugins/' . $plugin ), $result->get_error_message(), 'catapult' );

			return false;
		}
	}

	return true;
}

/**
 * Renders the html for an error message.
 *
 * @param string $heading        The heading for the error message.
 * @param string $message        More details about the error message.
 * @param string $message_type   The message type. Possible values: error, warning, info, or success.
 * @param bool   $wrap_message   Whether or not to wrap the message with a paragraph element.
 */
function render_message( $heading, $message, $message_type = 'error', $wrap_message = true ) {
	?>

	<div class="notice notice-<?php echo esc_attr( $message_type ); ?> is-dismissible installation-notice" style="display: flex; flex-direction: row; padding: 1rem;">
		<div class="installation-notice__content">
			<h2 class="installation-notice__heading" style="margin-top: 0;"><?php echo esc_html( $heading ); ?></h2>

			<?php if ( ! empty( $wrap_message ) ) : ?>
				<p style="margin-bottom: 0;">
			<?php endif; ?>

					<?php echo wp_kses_post( $message ); ?>

			<?php if ( ! empty( $wrap_message ) ) : ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<?php
}
