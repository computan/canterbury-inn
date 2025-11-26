<?php
/**
 * Functions related to the_content filters.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

/**
 * Add paragraphs to content after parsing shortcodes (WP default is before).
 * That fixes some shortcode usages, but breaks more complex shortcodes,
 * so should be enabled / disabled based on theme.
 */
function fix_shortcodes() {
	if ( array_key_exists( 'acf_the_content', $GLOBALS['wp_filter'] ) ) {
		remove_filter( 'acf_the_content', 'shortcode_unautop' );
		remove_filter( 'acf_the_content', 'wpautop' );
		add_filter( 'acf_the_content', 'wpautop', 12 );
	}
}

// phpcs:disable
/*
function theme_theme_core_post_content () {
	if ( isset( $this->settings->enable_shortcodes_fix ) && true === $this->settings->enable_shortcodes_fix ) {
		remove_filter( 'the_content', 'shortcode_unautop' );
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 12 );
	}
}
*/
// phpcs:enable

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop', 99 );
add_filter( 'the_content', 'shortcode_unautop', 100 );

/**
 * Remove empty tags around shortcodes.
 *
 * @param string $value The ACF content.
 */
function remove_empty_tags_around_shortcodes_acf_wysiwyg( $value ) {
	$tags = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br>'   => ']',
		']<br />' => ']',
	);

	$content = apply_filters( 'the_content', $value );
	$content = strtr( $content, $tags );
	return $content;
}
add_filter( 'acf_the_content', 'remove_empty_tags_around_shortcodes_acf_wysiwyg', 10, 1 );
