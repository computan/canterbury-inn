<?php
/**
 * Tab-Standard-Tab
 *
 * Title:             Tab-Standard-Tab
 * Description:       Tab-Standard tab inner block.
 * Instructions:
 * Category:          Tab
 * Icon:              icon786-tab
 * Keywords:          tab, tabs, standard
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Parent:            acf/tab-standard
 * Button Styles:     Tab
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/button', 'acf/content-section' );

$template = array(
	array(
		'core/button',
		array(
			'className' => 'is-style-tab',
		),
	),
	array(
		'acf/content-section',
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-tab-standard-tab<?php echo esc_attr( $content_block->get_block_classes() ); ?>" />
