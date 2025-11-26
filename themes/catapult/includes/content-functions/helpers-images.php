<?php
/**
 * Functions related to retrieving images.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Echoes theme images path.
 *
 * @return void
 */
function the_images_url() {
	echo esc_url( get_template_directory_uri() . '/images/' );
}

/**
 * Returns theme images path.
 *
 * @return string Images path
 */
function get_the_images_url() {
	return get_template_directory_uri() . '/images/';
}

/**
 * Return the path to the file if we want to output the contents rather than the path.
 *
 * @param int $attachment_id WP image ID.
 */
function vector_image_path( $attachment_id ) {
	$file = get_attached_file( $attachment_id, true );
	if ( ! wp_attachment_is_image( $attachment_id ) ) {
		return false;
	}
	return realpath( $file );
}

/**
 * Clean SVG function.
 *
 * A helper for get_svg() function.
 *
 * @param string $img File source.
 */
function get_clean_svg( $img ) {
	$img_svg = wp_remote_get( $img );
	preg_match( '/<svg[\s\S]*\/svg>/m', $img_svg, $matches );

	if ( isset( $matches[0] ) ) {
		return $matches[0];
	}

	return $img_svg;
}

/**
 * Retrieve an image or an svg to represent an attachment - based on file name or WP Image ID.
 *
 * @param string|int   $attachment File name or WP image ID.
 * @param string|array $thumbnail  Optional. WP thumbnail size - usable only with image ID and NOT SVG files.
 * @param string       $path       Optional. Path to file.
 * @param string       $ext        Optional. File extension.
 */
function get_svg( $attachment, $thumbnail = 'full-size', $path = '/icons/', $ext = '.svg' ) {
	if ( is_int( $attachment ) ) {
		$src = wp_get_attachment_image_src( $attachment, $thumbnail );

		if ( ! $src ) {
			return '';
		}

		$ext = pathinfo( $src[0], PATHINFO_EXTENSION );

		if ( 'svg' !== $ext ) {
			return wp_get_attachment_image( $attachment, $thumbnail );
		}

		$file = get_attached_file( $attachment, true );
		$img  = realpath( $file );

		if ( $img ) {
			return get_clean_svg( $img );
		}
	} else {
		$filename = strpos( $attachment, $ext ) !== false ? $attachment : $attachment . $ext;
		$file     = get_template_directory() . $path . $filename;

		if ( ! file_exists( $file ) ) {
			return '';
		}

		return get_clean_svg( $file );
	}
}
