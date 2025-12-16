<?php
/**
 * Card-Image-Content
 *
 * Title:             Card-Image-Content
 * Description:       A row of cards with images and additional content.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, content, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: white, secondary-teal-a, gradient-a, neutral-11
 * Default BG Color:  white
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'acf/cards' ) );

$template = array(
	array(
		'acf/cards',
		array(),
		array(
			array(
				'acf/card-image-content-card',
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-card-image-content<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-card-image-content__container" />
</section>
