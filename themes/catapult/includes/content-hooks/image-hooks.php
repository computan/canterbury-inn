<?php
/**
 * Image hooks.
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.4
 * @since   3.1.6
 * @since   3.1.7
 */

namespace Catapult\ImageHooks;

/**
 * Remove srcset sizes larger than the specified image.
 *
 * @param array  $sources {
 *     One or more arrays of source data to include in the 'srcset'.
 *
 *     @type array $width {
 *         @type string $url        The URL of an image source.
 *         @type string $descriptor The descriptor type used in the image candidate string,
 *                                  either 'w' or 'x'.
 *         @type int    $value      The source width if paired with a 'w' descriptor, or a
 *                                  pixel density value if paired with an 'x' descriptor.
 *     }
 * }
 * @param array  $size_array     {
 *      An array of requested width and height values.
 *
 *     @type int $0 The width in pixels.
 *     @type int $1 The height in pixels.
 * }
 * @param string $image_src     The 'src' of the image.
 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
 * @param int    $attachment_id Image attachment ID or 0.
 */
function limit_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	$max_size = $size_array[0];

	foreach ( $sources as $size => $image ) {
		if ( $size > $max_size ) {
			unset( $sources[ $size ] );
		}
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'Catapult\ImageHooks\limit_image_srcset', 11, 5 );

/**
 * Make sure webp images are added to all of the srcset values if they don't already exist.
 *
 * @param array  $sources       One or more arrays of source data to include in the 'srcset'.
 * @param array  $size_array    An array of requested width and height values.
 * @param string $image_src     The 'src' of the image.
 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
 * @param int    $attachment_id Image attachment ID or 0.
 */
function make_sure_webp_images_are_added_to_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	foreach ( $sources as $size => &$image ) {
		if ( empty( $image['url'] ) ) {
			continue;
		}

		if ( false === strpos( $image['url'], 'webp' ) ) {
			$upload_dir = wp_upload_dir();

			if ( empty( $upload_dir['baseurl'] ) || empty( $upload_dir['basedir'] ) ) {
				continue;
			}

			$webp_url  = $image['url'] . '.webp';
			$webp_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $webp_url );

			if ( file_exists( $webp_path ) ) {
				$image['url'] = $webp_url;
			}
		}
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'Catapult\ImageHooks\make_sure_webp_images_are_added_to_srcset', 99999, 5 );

/**
 * If computan Smartcrop plugin is used, add position to image style attribute.
 *
 * @param string[]     $attr       Array of attribute values for the image markup, keyed by attribute name.
 *                                 See wp_get_attachment_image().
 * @param WP_Post      $attachment Image attachment post.
 * @param string|int[] $size       Requested image size. Can be any registered image size name, or
 *                                 an array of width and height values in pixels (in that order).
 */
function add_smartcrop_position( $attr, $attachment, $size ) {
	if ( empty( $attachment ) || empty( $attachment->ID ) ) {
		return $attr;
	}

	$focus = get_post_meta( $attachment->ID, '_wpsmartcrop_image_focus', true );

	if ( ! empty( $focus ) && ! empty( $focus['top'] && ! empty( $focus['left'] ) ) ) {
		if ( empty( $attr['style'] ) ) {
			$attr['style'] = '';
		}

		$attr['style'] .= 'object-position: ' . $focus['left'] . '% ' . $focus['top'] . '%;';
	}

	if ( ! empty( $attr['src'] ) && false !== stripos( $attr['src'], '.svg' ) ) {
		$attachment_data = wp_get_attachment_metadata( $attachment->ID );

		if ( empty( $attachment_data ) || empty( $attachment_data['width'] ) || empty( $attachment_data['height'] ) ) {
			return $attr;
		}

		if ( empty( $attr['style'] ) ) {
			$attr['style'] = '';
		}

		$attr['style'] .= 'width: ' . $attachment_data['width'] / 16 . 'rem; height: ' . $attachment_data['height'] / 16 . 'rem;';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'Catapult\ImageHooks\add_smartcrop_position', 10, 3 );

/**
 * Sets the maximum image width to twice the width of the designs to allow uploading 2x retina images.
 *
 * If the original image width or height is above the threshold, it will be scaled down. The threshold is
 * used as max width and max height. The scaled down image will be used as the largest available size, including
 * the `_wp_attached_file` post meta value.
 *
 * Returning `false` from the filter callback will disable the scaling.
 *
 * @param int    $threshold     The threshold value in pixels. Default 2560.
 * @param array  $imagesize     {
 *     Indexed array of the image width and height in pixels.
 *
 *     @type int $0 The image width.
 *     @type int $1 The image height.
 * }
 * @param string $file          Full path to the uploaded image file.
 * @param int    $attachment_id Attachment post ID.
 */
function big_image_size_threshold( $threshold, $imagesize, $file, $attachment_id ) {
	return 3360;
}
add_filter( 'big_image_size_threshold', 'Catapult\ImageHooks\big_image_size_threshold', 10, 4 );

/**
 * Set the options required for the perfect images plugin to force generating retina images for all image sizes.
 *
 * @param mixed  $value     The new, unserialized option value.
 * @param mixed  $old_value The old option value.
 * @param string $option    Option name.
 */
function set_required_perfect_images_retina_options( $value, $old_value, $option ) {
	if ( empty( $value ) ) {
		$value = array();
	}

	$image_sizes = wp_get_additional_image_sizes();

	if ( ! empty( $image_sizes ) ) {
		$all_image_sizes            = array_merge( array( 'large', 'medium', 'medium_large', 'thumbnail' ), array_keys( wp_get_additional_image_sizes() ) );
		$value['retina_sizes']      = $all_image_sizes;
		$value['webp_sizes']        = $all_image_sizes;
		$value['webp_retina_sizes'] = $all_image_sizes;
	}

	$value['method']                = 'Responsive';
	$value['webp_method']           = 'Responsive';
	$value['module_retina_enabled'] = true;
	$value['module_webp_enabled']   = true;
	$value['auto_generate']         = true;
	$value['webp_auto_generate']    = true;
	$value['hide_retina_column']    = false;
	$value['disable_responsive']    = false;
	$value['sizes']                 = array();

	return $value;
}
add_filter( 'pre_update_option_wr2x_options', 'Catapult\ImageHooks\set_required_perfect_images_retina_options', 10, 3 );

/**
 * Set the options required for the Regenerate Thumbnails Advanced plugin to force generating thumbnails for all image sizes.
 *
 * @param mixed  $value     The new, unserialized option value.
 * @param mixed  $old_value The old option value.
 * @param string $option    Option name.
 */
function set_required_regenerate_thumbnails_advanced_options( $value, $old_value, $option ) {
	if ( empty( $value ) ) {
		$value = array();
	}

	$image_sizes = wp_get_additional_image_sizes();

	if ( ! empty( $image_sizes ) ) {
		$value['process_image_sizes'] = array_merge( array( 'large', 'medium', 'medium_large', 'thumbnail' ), array_keys( wp_get_additional_image_sizes() ) );
	}

	return $value;
}
add_filter( 'pre_update_option_rta_image_sizes', 'Catapult\ImageHooks\set_required_regenerate_thumbnails_advanced_options', 10, 3 );

/**
 * Only generate webp image thumbnails since thumbnails in the original format are not needed.
 *
 * @param string[] $output_format {
 *     An array of mime type mappings. Maps a source mime type to a new
 *     destination mime type. Default empty array.
 *
 *     @type string ...$0 The new mime type.
 * }
 * @param string   $filename  Path to the image.
 * @param string   $mime_type The source image mime type.
 */
function image_editor_output_format( $output_format, $filename, $mime_type ) {
	$output_format['image/jpeg'] = 'image/webp';
	$output_format['image/png']  = 'image/webp';

	return $output_format;
}
add_filter( 'image_editor_output_format', 'Catapult\ImageHooks\image_editor_output_format', 10, 3 );
