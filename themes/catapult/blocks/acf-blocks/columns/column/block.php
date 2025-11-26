<?php
/**
 * Column
 *
 * Title:             Column
 * Description:       An individual column of content.
 * Instructions:
 * Category:          Text
 * Icon:              columns
 * Keywords:          columns, column, text, side
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Parent:            acf/columns
 * InnerBlocks:       true
 * CSS Custom Props:  column_width
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
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
);

?>

<div <?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-column">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
</div>
