<?php
/**
 * Internet Explorer related functions.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

/**
 * Determine if users browser is Internet Explorer or not.
 */
function is_ie() {
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$ua = htmlentities( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), ENT_QUOTES, 'UTF-8' );
		if ( preg_match( '~MSIE|Internet Explorer~i', $ua ) || ( strpos( $ua, 'Trident/7.0' ) !== false && strpos( $ua, 'rv:11.0' ) !== false ) ) {
			return true;
		}
	}

	return false;
}
