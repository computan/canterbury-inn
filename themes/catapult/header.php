<?php
/**
 * The Header for theme.
 *
 * Displays all of the <head> section and page header
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.1.0
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 */

$custom_navigation = get_field( 'custom_navigation' );
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<div id="page">
			<div id="top" tabindex="-1"></div>

			<a href="#content" class="skip-link"><?php esc_html_e( 'Skip navigation and go to main content.', 'catapult' ); ?></a>

			<?php catapult_render_theme_blocks( 'alert_top' ); ?>

			<header class="main-header">
				<?php
				if ( ! empty( $custom_navigation ) ) {
					if ( ! empty( $custom_navigation->post_content ) ) {
						echo apply_filters( 'the_content', $custom_navigation->post_content ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					catapult_render_theme_blocks( 'primary_navigation' );
				}
				?>
			</header>

			<div id="content" tabindex="-1"></div>
