<?php
/**
 * Testimonial-Image
 *
 * Title:             Testimonial-Image
 * Description:       Display testimonials post type in a slider with a side image.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote, image
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
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

$arrow_position = get_field( 'arrow_position' );

if ( empty( $arrow_position ) ) {
	$arrow_position = 'top';
}

$section_classes = ' block-testimonial-image--arrows-' . $arrow_position;

if ( ! empty( $content ) && 0 !== strpos( trim( $content ), '<div class="block-testimonials' ) ) {
	$section_classes .= ' block-testimonial-image--has-heading-content';
} else {
	$section_classes .= ' block-testimonial-image--no-heading-content';
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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-testimonial-image<?php echo esc_attr( $section_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-testimonial-image__container" />
</section>
