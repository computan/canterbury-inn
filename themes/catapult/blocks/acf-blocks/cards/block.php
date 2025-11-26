<?php
/**
 * Cards
 *
 * Title:             Cards
 * Description:       The inner block wrapper for card blocks.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, links
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * InnerBlocks:       true
 * Styles:
 * Parent:            acf/card-standard, acf/card-stacked, acf/card-image-links, acf/card-text-links, acf/card-image-content, acf/card-memberships
 * CSS Custom Props:  cards_per_row: 3
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$cards_per_row = get_field( 'cards_per_row' );

if ( empty( $cards_per_row ) ) {
	$cards_per_row = '3';
}

$allowed_blocks = array( 'acf/card' );

if ( ! empty( $context ) ) {
	if ( ! empty( $context['card-text-links'] ) ) {
		$allowed_blocks = array( 'acf/card-text-link' );
	} elseif ( ! empty( $context['card-image-links'] ) ) {
		$allowed_blocks = array( 'acf/card-image-link' );
	} elseif ( ! empty( $context['card-image-content'] ) ) {
		$allowed_blocks = array( 'acf/card-image-content-card' );
	} elseif ( ! empty( $context['card-memberships'] ) ) {
		$allowed_blocks = array( 'acf/card-membership' );
	}
}

?>

<div <?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-cards acf-inline-block block-cards--<?php echo esc_attr( $cards_per_row ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="block-cards__grid" />
</div>
