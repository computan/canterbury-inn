<?php
/**
 * Display all registered image sizes in WP Admin panel
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

/**
 * Filter out disallowed image sizes.
 *
 * @param string $image_size A potential image size.
 */
function filter_sizes( $image_size ) {
	$disallowed_sizes = array( 'main-logo' );

	foreach ( $disallowed_sizes as $disallowed_size ) {
		if ( stripos( $image_size, $disallowed_size ) !== false ) {
			return false;
		}
	}
	return true;
}

/**
 * Add custom image sizes to list of existing image sizes.
 *
 * @param array $sizes Existing image sizes.
 */
function admin_custom_image_sizes( $sizes ) {
	$all_sizes    = array();
	$custom_sizes = get_intermediate_image_sizes();
	$custom_sizes = array_filter( $custom_sizes, 'filter_sizes' );

	foreach ( $custom_sizes as $key => $value ) {
		$all_sizes[ $value ] = $value;
	}
	$all_sizes = array_merge( $all_sizes, $sizes );

	return $all_sizes;
}
add_filter( 'image_size_names_choose', 'admin_custom_image_sizes', 11, 1 );
