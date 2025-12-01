<?php
/**
 * Displays placeholder image.
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.1.0
 */

/**
 * Displays placeholder image.
 *
 * @param string|null $classes CSS classes for the image (optional).
 * @param string|null $alt Alt text for the image (optional).
 * @param string      $size Image size for the attachment (default: 'full').
 * @param string|null $id ID attribute for the image (optional).
 * @return string|null The <img> tag or null if no image is found.
 */
function get_placeholder_image( $classes = null, $alt = null, $size = 'full', $id = null ) {
	$image_id = get_field( 'default_placeholder_image', 'general' );
	if ( ! $image_id ) {
		return '<img src="' . esc_url( get_stylesheet_directory_uri() . '/images/placeholder.jpg' ) . '" alt="Placeholder" />';
	}

	// Use alt if provided or fallback to image alt.
	$alt     = esc_attr( $alt ? $alt : get_post_meta( $image_id, '_wp_attachment_image_alt', true ) );
	$classes = esc_attr( $classes ? $classes : '' );
	$id_attr = $id ? ' id="' . esc_attr( $id ) . '"' : '';

	// Generate the <img> tag with srcset and sizes for optimization.
	$img_html = wp_get_attachment_image(
		$image_id,
		$size,
		false,
		array(
			'alt'   => $alt,
			'class' => $classes,
			'id'    => $id,
		)
	);

	return $img_html;
}
