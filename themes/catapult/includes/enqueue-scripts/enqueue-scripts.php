<?php
/**
 * Load scripts.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 */

namespace Catapult\Scripts;

/**
 * Load the main JS bundle.
 *
 * Determine if the browser is Internet Explorer and serve the correct JS bundle.
 */
function load_scripts() {
	$template_directory_uri  = get_template_directory_uri();
	$template_directory_path = get_template_directory();

	if ( file_exists( $template_directory_path . '/dist/bundle.js' ) ) {
		wp_enqueue_script( 'catapult-script', $template_directory_uri . '/dist/bundle.js', catapult_get_script_dependences( $template_directory_path . '/dist/bundle.js' ), filemtime( $template_directory_path . '/dist/bundle.js' ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'Catapult\Scripts\load_scripts' );

remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Only load CSS stylesheets set to all media devices on screens to improve Google lighthouse scores.
 *
 * @param string $tag    The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href   The stylesheet's source URL.
 * @param string $media  The stylesheet's media attribute.
 */
function add_media_all_onload_attribute( $tag, $handle, $href, $media ) {
	if ( 'all' === $media ) {
		$tag = preg_replace( '/media=["\']all["\']/m', 'media="screen" onload="this.media=\'all\'"', $tag );
	}

	return $tag;
}
add_filter( 'style_loader_tag', 'Catapult\Scripts\add_media_all_onload_attribute', 10, 4 );
