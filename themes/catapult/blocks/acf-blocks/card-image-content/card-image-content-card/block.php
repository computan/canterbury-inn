<?php
/**
 * Card-Image-Content-Card
 *
 * Title:             Card-Image-Content-Card
 * Description:       Card block for use within parent Card-Image-Content block.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, flexible, content, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: image
 * Ancestor:          acf/card-image-content
 * InnerBlocks:       true
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$image         = get_field( 'image' );
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

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button' );

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
	array(
		'core/button',
		array(
			'className'  => 'is-style-tertiary',
			'buttonIcon' => 'icon-arrow-right',
		),
	),
);

?>

<div class="block-card-image-content-card">
	<?php if ( ! empty( $image ) ) : ?>
		<figure class="block-card-image-content-card__image-wrapper image-wrapper">
			<?php echo wp_kses_post( wp_get_attachment_image( $image, $image_size ) ); ?>
		</figure>
	<?php endif; ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-card-image-content-card__content" />
</div>
