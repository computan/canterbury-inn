<?php
/**
 * Testimonial-Cards
 *
 * Title:             Testimonial-Cards
 * Description:       Display testimonials post type in a slider with multiple cards per row.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote, cards
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );


if ( ! empty( $content ) && 0 !== strpos( trim( $content ), '<div class="block-testimonials' ) ) {
	$section_classes = ' block-testimonial-cards--has-heading-content';
} else {
	$section_classes = ' block-testimonial-cards--no-heading-content';
}

$cards_per_row = get_field( 'cards_per_row' );

if ( empty( $cards_per_row ) ) {
	$cards_per_row = '3';
}

$allowed_blocks = catapult_text_blocks( 'acf/testimonials' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'acf/testimonials',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-testimonial-cards<?php echo esc_attr( $section_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>" data-cards-per-row="<?php echo esc_attr( $cards_per_row ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-testimonial-cards__container" />
</section>
