<?php
/**
 * Helper functions for relevanssi search.
 *
 * @category Helper
 * @package Catapult
 * @since   3.1.2
 */

/**
 * Function to filter the search description according to the content, wp excerpt and Yoast Description.
 *
 * @param  int $post_id The ID of the post whose search excerpt should be retrieved.
 * @return string The search excerpt for the given post ID.
 */
function custom_search_excerpt( $post_id ) {
	if ( defined( 'WPSEO_VERSION' ) ) {
		$yoast_meta_desc = get_post_meta( $post_id, '_yoast_wpseo_metadesc', true );
		if ( $yoast_meta_desc ) {
			return $yoast_meta_desc;
		}
	}

	$post_excerpt = get_the_excerpt( $post_id );
	if ( $post_excerpt ) {
		return $post_excerpt;
	}

	$post_content = get_post_field( 'post_content', $post_id );
	if ( $post_content ) {
		$post_content = strip_shortcodes( wp_strip_all_tags( $post_content ) );
		return substr( $post_content, 0, 160 );
	}

	return '';
}

/**
 * Function to get the post type of the search result and remove page type label from the search.
 *
 * @param  int $post_id The ID of the WordPress post.
 * @return string|bool The singular name label of the post type associated with the post, or false if the post type is not registered or is a page.
 */
function get_post_type_singular_name( $post_id ) {
	$post_type = get_post_type( $post_id );

	if ( 'page' === $post_type || ! $post_type ) {
		return false;
	}

	$post_type_object = get_post_type_object( $post_type );
	if ( ! $post_type_object ) {
		return false;
	}

	if ( 'case-study' === $post_type ) {
		return 'Case Studies';
	}

	if ( 'resource' === $post_type ) {
		return 'Resources';
	}

	if ( 'people' === $post_type ) {
		return 'People';
	}

	if ( 'post' === $post_type ) {
		return 'News & Updates';
	}

	return $post_type_object->labels->singular_name;
}
