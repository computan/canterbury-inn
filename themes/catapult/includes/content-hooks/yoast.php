<?php
/**
 * Modify Yoast functionality.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.10
 * @since   3.0.0
 */

namespace Catapult\Yoast;

/**
 * Don't include noindex posts from the WordPress search

 * @param object $query    WordPress query object.
 */
function exclude_noindex_posts_from_search( $query ) {
	if ( class_exists( 'WPSEO_Options' ) && $query->is_main_query() && $query->is_search() && ! is_admin() ) {
		$meta_query = ! empty( $query->meta_query ) ? $query->meta_query : array();

		$meta_query['noindex'] = array(
			'key'     => '_yoast_wpseo_meta-robots-noindex',
			'value'   => 1,
			'compare' => 'NOT EXISTS',
		);

		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'Catapult\Yoast\exclude_noindex_posts_from_search' );

/**
 * Move Yoast SEO to the bottom of the screen.
 */
function yoast_to_bottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'Catapult\Yoast\yoast_to_bottom' );

/**
 * Exclude authors from sitemap.
 *
 * @param array $users Array of user objects to filter.
 */
function exclude_authors_from_sitemap( $users ) {
	return false;
}
add_filter( 'wpseo_sitemap_exclude_author', 'Catapult\Yoast\exclude_authors_from_sitemap', 10, 2 );
