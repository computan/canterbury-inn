<?php
/**
 * Card
 *
 * Title:             Card
 * Description:       Card block for use within parent cards block.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, flexible, links
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Ancestor:          acf/card-standard, acf/card-stacked
 * InnerBlocks:       true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button', 'acf/icon' );

$template = array(
	array(
		'acf/icon',
	),
	array(
		'core/heading',
		array(
			'level'       => 3,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
		),
	),
	array(
		'core/button',
		array(
			'className'  => 'is-style-tertiary',
			'buttonIcon' => 'icon-arrow-right',
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-card bg-white" />
