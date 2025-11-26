<?php
/**
 * Testimonials
 *
 * Title:             Testimonials
 * Description:       The inner block wrapper for testimonial blocks.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * InnerBlocks:       true
 * Styles:
 * Parent:            acf/testimonial-standard, acf/testimonial-image, acf/testimonial-cards, acf/testimonial-box
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/testimonial' );

$template = array(
	array(
		'acf/testimonial',
	),
);

$arrow_position = 'top';

if ( ! empty( $context ) && ! empty( $context['testimonial-image'] ) && ! empty( $context['testimonial-image']['arrow_position'] ) ) {
	$arrow_position = $context['testimonial-image']['arrow_position'];
}

?>

<div class="block-testimonials" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Testimonial slider.', 'catapult' ); ?>">
	<?php if ( 'bottom' !== $arrow_position && substr_count( $content, '"block-testimonial ' ) > 1 ) : ?>
		<button class="swiper-button-prev swiper-button--outline"><?php esc_html_e( 'Previous slide', 'catapult' ); ?></button>
		<button class="swiper-button-next swiper-button--outline"><?php esc_html_e( 'Next slide', 'catapult' ); ?></button>
	<?php endif; ?>

	<div class="swiper">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="swiper-wrapper" />
	</div>

	<?php if ( 'bottom' === $arrow_position && substr_count( $content, '"block-testimonial ' ) > 1 ) : ?>
		<button class="swiper-button-prev swiper-button--outline"><?php esc_html_e( 'Previous slide', 'catapult' ); ?></button>
		<button class="swiper-button-next swiper-button--outline"><?php esc_html_e( 'Next slide', 'catapult' ); ?></button>
	<?php endif; ?>
</div>
