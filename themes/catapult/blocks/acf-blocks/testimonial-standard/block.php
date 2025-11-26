<?php
/**
 * Testimonial-Standard
 *
 * Title:             Testimonial-Standard
 * Description:       Display testimonials post type in a slider.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote
 * Post Types:        all
 * Multiple:          true
 * Active:            true
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

$allowed_blocks = catapult_text_blocks( 'acf/testimonials' );

$template = array(
	array(
		'acf/testimonials',
	),
);

$block_classes = '';

if ( ! empty( $content ) && false !== strpos( $content, 'block-testimonial__logo' ) ) {
	$block_classes = ' block-testimonial-standard--has-logo';
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-testimonial-standard<?php echo esc_attr( $block_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-testimonial-standard__container" />
</section>
