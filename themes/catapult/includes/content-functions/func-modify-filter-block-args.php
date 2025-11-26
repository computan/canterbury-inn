<?php
/**
 * Modify the query args for the filters blocks.
 *
 * @package Catapult
 * @since   3.0.14
 * @since   3.1.0
 */

/**
 * Modify the query args for the filters blocks.
 *
 * @param array $args          An array of post query args to modify.
 *
 * @return arra  $args          The modified query args.
 */
function catapult_modify_filter_block_args( $args ) {
	$args['post_wrapper_classes'] = '';

	if ( 'attachment' === $args['post_type'] ) {
		$args['post_wrapper_classes'] = ' component-lightbox';
		$args['post_status']          = 'inherit';
		$args['tax_query'][]          = array(
			'taxonomy' => 'media_gallery_category',
			'operator' => 'EXISTS',
		);

		if ( empty( $args['media_type'] ) ) {
			$args['post_mime_type'] = array( 'image', 'video' );
		} elseif ( ( is_string( $args['media_type'] ) && 'video' === $args['media_type'] ) ) {
			$args['post_mime_type'] = array( 'image', 'video' );

			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'OR',
				array(
					'key'     => '_wp_attachment_metadata',
					'value'   => 'video',
					'compare' => 'LIKE',
				),
				array(
					'relation' => 'AND',
					array(
						'key'     => '_wp_attachment_metadata',
						'value'   => 'image',
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'media_gallery_video',
						'value'   => '',
						'compare' => '!=',
					),
				),
			);
		} elseif ( ( is_string( $args['media_type'] ) && 'image' === $args['media_type'] ) ) {
			$args['post_mime_type'] = array( 'image' );

			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'OR',
				array(
					'key'     => 'media_gallery_video',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'media_gallery_video',
					'value'   => '',
					'compare' => '=',
				),
			);
		}
	}

	return $args;
}
