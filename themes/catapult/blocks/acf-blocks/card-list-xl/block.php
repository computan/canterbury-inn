<?php
/**
 * Card-List-Xl
 *
 * Title:               Card-List-Xl
 * Description:         A stylized block with inner blocks.
 * Instructions:
 * Category:            Base
 * Icon:                align-wide
 * Keywords:            card, list, XL
 * Post Types:          all
 * Multiple:            true
 * Active:              true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:   scroll_id
 * Background Colors:   transparent, white
 * Default BG Color:    white
 * InnerBlocks:         true
 * Mode:                preview
 * Text Width Styles:   true
 * Starts With Text:    true
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$allowed_blocks = array( 'acf/card-list' );

$template = array(
	array(
		'acf/card-list',
	),
	array(
		'acf/card-list',
	),
	array(
		'acf/card-list',
	),
);

$content_block = new Content_Block_Gutenberg( $block, $context );
?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-card-list-xl<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks 
		allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
		template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" 
		templateLock="false" 
		class="block-content-section__content content-wrapper" 
	/>
</section>
