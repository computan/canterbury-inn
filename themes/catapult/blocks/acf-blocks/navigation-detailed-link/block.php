<?php
/**
 * Navigation-Detailed-Link
 *
 * Title:             Navigation-Detailed-Link
 * Description:       A link with additional paragraph text.
 * Instructions:
 * Category:          Navigation
 * Icon:              admin-links
 * Keywords:          navigation, link, text, paragraph, detail, detailed
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Ancestor:          acf/navigation-simple-links, acf/navigation-link-column
 * InnerBlocks:       true
 * Button Styles:     Navigation-Link
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/button', 'core/paragraph' );

$template = array(
	array(
		'core/button',
		array(
			'className' => 'is-style-navigation-link',
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

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-navigation-detailed-link" />
