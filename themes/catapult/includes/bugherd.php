<?php
/**
 * Register BugHerd API and script
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   3.0.0
 */

/**
 * Checks if BugHerd API is present in .env file and loads BugHerd script.
 *
 * @return void
 */
function load_bugherd() {
	if ( defined( 'BUGHERD_API_KEY' ) ) {
		wp_enqueue_script( 'bugherd', 'https://www.bugherd.com/sidebarv2.js?apikey=' . BUGHERD_API_KEY, array(), '1.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'load_bugherd' );
