<?php
/**
 * Determine if a url will return a 200 response.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Determine if a url will return a 200 response.
 *
 * @param string $url The url in question.
 */
function catapult_is_url_200( $url ) {
	stream_context_set_default(
		array(
			'ssl' => array(
				'verify_peer'      => false,
				'verify_peer_name' => false,
			),
		)
	);

	$headers = get_headers( $url, 1 );

	if ( strpos( $headers[0], '200' ) === false ) {
		return false;
	} else {
		return true;
	}
}
