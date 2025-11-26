<?php
/**
 * Content Section
 *
 * Title:             Content Section
 * Description:       A stylelized block with inner blocks.
 * Instructions:
 * Category:          Base
 * Icon:              align-wide
 * Keywords:          content, section, innerblocks
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Mode:              preview
 * Text Width Styles: true
 * Starts With Text:
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
		),
	),
);

$allowed_blocks = catapult_allowed_block_types_all();

$content_block = new Content_Block_Gutenberg( $block, $context );

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-content-section<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="false" class="block-content-section__content content-wrapper" />
</section>
