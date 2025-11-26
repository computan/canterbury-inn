<?php
/**
 * Calculate the post read time and save it as a custom meta value.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.1.1
 * @since   2.2.6
 */

namespace Catapult\ReadTime;

/**
 * Calculate the post read time and save it as a custom meta value.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function calculate_read_time( $post_id, $post, $update ) {
	if ( 'post' === $post->post_type ) {
		$pattern = '/<!--.*?-->/U';
		$content = get_the_content( null, false, $post_id );
		$content = esc_html( preg_replace( $pattern, '', $content ) );

		$word_count = str_word_count( $content );

		$words_per_minute = 225;
		$minutes          = ceil( $word_count / $words_per_minute );

		update_post_meta( $post_id, 'estimated_read_time', $minutes );
	}
}
add_action( 'save_post', 'Catapult\ReadTime\calculate_read_time', 10, 3 );
