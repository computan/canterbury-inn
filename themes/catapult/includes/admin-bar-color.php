<?php
/**
 * Utility to change adminbar color based on environment.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   3.0.0
 */

/**
 * Utility to change adminbar color based on environment
 *
 * @return void
 */
function admin_bar_color() {
	$environment_type = wp_get_environment_type();

	if ( 'staging' === $environment_type ) {
		echo '<style>#wpadminbar {background: #0d4b00;}</style>';
	} elseif ( 'development' === $environment_type ) {
		echo '<style>#wpadminbar {background: #650100;}</style>';
	} elseif ( 'local' === $environment_type ) {
		echo '<style>#wpadminbar {background: #650100;}</style>';
	}
}
add_action( 'wp_head', 'admin_bar_color' );
add_action( 'admin_head', 'admin_bar_color' );
