<?php
/**
 * Hero-Details
 *
 * Title:             Hero-Details
 * Description:       An inline wrapper for hero details.
 * Instructions:
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
 * Parent:
 * Image Size:
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/hero-detail' );

$template = array(
	array(
		'acf/hero-detail',
		array(),
		array(
			array(
				'core/heading',
				array(
					'level'    => 2,
					'content'  => __( 'Type', 'catapult' ),
					'fontSize' => 'overline',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text.', 'catapult' ),
				),
			),
		),
	),
	array(
		'acf/hero-detail',
		array(),
		array(
			array(
				'core/heading',
				array(
					'level'    => 2,
					'content'  => __( 'Category', 'catapult' ),
					'fontSize' => 'overline',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text.', 'catapult' ),
				),
			),
		),
	),
	array(
		'acf/hero-detail',
		array(),
		array(
			array(
				'core/heading',
				array(
					'level'    => 2,
					'content'  => __( 'Amount', 'catapult' ),
					'fontSize' => 'overline',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text.', 'catapult' ),
				),
			),
		),
	),
	array(
		'acf/hero-detail',
		array(),
		array(
			array(
				'core/heading',
				array(
					'level'    => 2,
					'content'  => __( 'Locations', 'catapult' ),
					'fontSize' => 'overline',
				),
			),
			array(
				'core/image',
			),
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-details" />
