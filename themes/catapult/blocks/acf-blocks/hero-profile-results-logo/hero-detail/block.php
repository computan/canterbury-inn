<?php
/**
 * Hero-Detail
 *
 * Title:             Hero-Detail
 * Description:       An inline wrapper for hero details with a heading and text or a logo.
 * Instructions:      If adding an image, it should be an SVG using a 28px height viewBox.
 * Category:          Base
 * Icon:              ellipsis
 * Keywords:          hero, profile, results, logo, type, category, amount, locations
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/hero-details
 * Image Size:        logo-block
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/image' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'overline',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or logo here.', 'catapult' ),
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-detail" />
