<?php
/**
 * Text-Columns
 *
 * Title:             Text-Columns
 * Description:       Block with centered heading and adjustable columns of text.
 * Instructions:
 * Category:          Text
 * Icon:              columns
 * Keywords:          info, heading, paragraph, button, content, section, column, columns, grid, text
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'acf/columns' ) );

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add pre-heading here.', 'catapult' ),
			'fontSize'    => 'overline',
		),
	),
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'acf/columns',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-text-columns<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container" />
</section>
