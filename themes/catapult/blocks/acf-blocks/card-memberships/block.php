<?php
/**
 * Card-Memberships
 *
 * Title:             Card-Memberships
 * Description:       A row of 3 cards with membership plan details.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, links, memberships, plans, trial
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:  neutral-10
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
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
			'align'       => 'center',
		),
	),
	array(
		'acf/cards',
		array(),
		array(
			array(
				'acf/card-membership',
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-card-memberships<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-card-memberships__container" />
</section>
