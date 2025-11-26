<?php
/**
 * Custom Lighthouse functions.
 *
 * @package Catapult
 * @since   3.0.0
 */

namespace Catapult\Lighthouse;

/**
 * Add custom query vars for the lighthouse pages.
 *
 * @param array $query_vars Array of query vars.
 */
function custom_query_vars( $query_vars ) {
	$query_vars[] = 'lighthouse-template';

	return $query_vars;
}
add_filter( 'query_vars', 'Catapult\Lighthouse\custom_query_vars' );

/**
 * Add custom rewrite rules for lighthouse URLs.
 *
 * @param array $rules Array of rewrite rules.
 */
function custom_rewrite_rules( $rules ) {
	$new_rules = array(
		'lighthouse/mobile/?$'         => 'index.php?lighthouse-template=mobile',
		'lighthouse/desktop/?$'        => 'index.php?lighthouse-template=desktop',
		'lighthouse/blocks/mobile/?$'  => 'index.php?lighthouse-template=blocks-mobile',
		'lighthouse/blocks/desktop/?$' => 'index.php?lighthouse-template=blocks-desktop',
		'lighthouse/?$'                => 'index.php?lighthouse-template=desktop',
	);

	return array_merge( $new_rules, $rules );
}
add_filter( 'rewrite_rules_array', 'Catapult\Lighthouse\custom_rewrite_rules', 9999 );

/**
 * Load the lighthouse reports if viewing the lighthouse URLs.
 *
 * @param string $template Template name.
 */
function lighthouse_templates( $template ) {
	$lighthouse_query_var = get_query_var( 'lighthouse-template' );

	if ( empty( $lighthouse_query_var ) ) {
		return $template;
	}

	global $current_user;

	if ( ! is_user_logged_in() || ! in_array( 'administrator', $current_user->roles, true ) || false === strpos( $current_user->user_email, 'computan.com' ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit;
	}

	if ( 'desktop' === $lighthouse_query_var ) {
		$new_template = locate_template( array( 'lighthouse/desktop/index.php' ) );
	} elseif ( 'mobile' === $lighthouse_query_var ) {
		$new_template = locate_template( array( 'lighthouse/mobile/index.php' ) );
	} elseif ( 'blocks-desktop' === $lighthouse_query_var ) {
		$new_template = locate_template( array( 'lighthouse/blocks/desktop/index.php' ) );
	} elseif ( 'blocks-mobile' === $lighthouse_query_var ) {
		$new_template = locate_template( array( 'lighthouse/blocks/mobile/index.php' ) );
	}

	if ( empty( $new_template ) ) {
		esc_html_e( 'Cannot find the Lighthouse report. Either the scan is currently running, or the previous scan failed. Check with the Computan dev team if you see this message.', 'catapult' );
	}

	return $new_template;
}
add_filter( 'template_include', 'Catapult\Lighthouse\lighthouse_templates', 99 );
