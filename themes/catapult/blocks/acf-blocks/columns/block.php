<?php
/**
 * Columns
 *
 * Title:             Columns
 * Description:       Display content in multiple columns.
 * Instructions:
 * Category:          Text
 * Icon:              columns
 * Keywords:          columns, column, text, side
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * InnerBlocks:       true
 * Styles:
 * Parent:
 * CSS Custom Props:  column_width: 3
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/column' );

$template = array(
	array( 'acf/column' ),
	array( 'acf/column' ),
	array( 'acf/column' ),
	array( 'acf/column' ),
);

?>

<div <?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-columns acf-inline-block">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
</div>
