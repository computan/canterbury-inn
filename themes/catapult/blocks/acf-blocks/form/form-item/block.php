<?php
/**
 * Form-Item
 *
 * Title:             Form-Item
 * Description:       An inline wrapper for a generic form heading/text element.
 * Instructions:
 * Category:          Base
 * Icon:              feedback
 * Keywords:          content, form, price, form
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:
 * Context:           acf/form
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/paragraph' );

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Label', 'catapult' ),
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text here.', 'catapult' ),
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-form-item" templateLock="all" />
