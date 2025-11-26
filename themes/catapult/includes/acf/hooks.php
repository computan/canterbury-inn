<?php
/**
 * ACF Hooks & Filters
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

namespace Catapult\AcfHooks;

/**
 * Tell ACF to use wp_block as a post type.
 *
 * @param array $post_types    Array of allowed post types.
 */
function acf_get_post_types( $post_types ) {
	$post_types[] = 'wp_block';

	return $post_types;
}
add_filter( 'acf/get_post_types', 'Catapult\AcfHooks\acf_get_post_types', PHP_INT_MAX );

/**
 * Prevents editing of ACF fields on environments other than local
 *
 * @param string $capability   The capability needed to edit ACF posts.
 *
 * @return string $capability
 */
function remove_acf_menu( $capability ) {
	if ( wp_get_environment_type() !== 'local' ) {
		$capability = false;
	}

	return $capability;
}
add_filter( 'acf/settings/capability', 'Catapult\AcfHooks\remove_acf_menu' );

/**
 * Disable tabs when editing field groups.
 */
add_filter( 'acf/field_group/disable_field_settings_tabs', '__return_true' );

/**
 * Don't show the message about no assigned fields on blocks.
 */
add_filter( 'acf/blocks/no_fields_assigned_message', '__return_false' );
