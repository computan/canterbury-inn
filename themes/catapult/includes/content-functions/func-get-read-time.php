<?php
/**
 * Gets read time of a post.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Gets read time of a post.
 *
 * @param int $post_id    The post ID.
 *
 * @return string   The read time of the post.
 */
function catapult_get_read_time( $post_id = null ) {
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$read_time = get_post_meta( $post_id, 'estimated_read_time', true );

	if ( ! empty( $read_time ) ) {
		return sprintf( __( '%s min read', 'catapult' ), $read_time );
	}
}
