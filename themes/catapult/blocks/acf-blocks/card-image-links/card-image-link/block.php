<?php
/**
 * Card-Image-Link
 *
 * Title:             Card-Image-Link
 * Description:       Card block for use within parent Card-Image-Links block.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, flexible, links, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: image, video
 * Ancestor:          acf/card-image-links
 * InnerBlocks:       true
 * Button Styles:     Tertiary
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$cards_per_row = $content_block->get_parent_field( 'cards_per_row', 'acf/cards' );
$image_size    = 'card-image-link-4';

if ( empty( $cards_per_row ) ) {
	$cards_per_row = '3';
}

if ( '2' === $cards_per_row ) {
	$image_size = 'card-image-link-6';
} elseif ( '4' === $cards_per_row ) {
	$image_size = 'card-image-link-3';
}

$allowed_blocks = array( 'core/button' );

$template = array(
	array(
		'core/button',
		array(
			'className'  => 'is-style-tertiary',
			'buttonIcon' => 'icon-arrow-right',
		),
	),
);

?>

<div class="block-card-image-link">
	<?php echo wp_kses_post( $content_block->get_block_image_and_video( $image_size, 'block-card-image-link__image-wrapper image-wrapper' ) ); ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-card-image-link__content" />
</div>
