<?php
/**
 * Accordion
 *
 * Title:             Accordion
 * Description:       Inline accordion block.
 * Instructions:
 * Category:          Accordion
 * Icon:              awards
 * Keywords:          show, hide, content, accordion
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:            Multiple Open, Single Open
 * Starts With Text:
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/accordion-item' );

$template = array(
	array( 'acf/accordion-item' ),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="false" class="acf-block block-accordion<?php echo esc_attr( $content_block->get_block_classes( array( 'background_color' => 'transparent' ) ) ); ?>" />
