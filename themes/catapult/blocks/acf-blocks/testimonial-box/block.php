<?php
/**
 * Testimonial-Box
 *
 * Title:             Testimonial-Box
 * Description:       Display testimonials post type in a slider within the grid.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:  neutral-10
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

$background_color = get_field( 'background_color' );

if ( empty( $background_color ) ) {
	$background_color = 'neutral-10';
}

$allowed_blocks = catapult_text_blocks( 'acf/testimonials' );

$template = array(
	array(
		'acf/testimonials',
	),
);

$block_classes = '';

if ( ! empty( $content ) && false !== strpos( $content, 'block-testimonial__logo' ) ) {
	$block_classes = ' block-testimonial-box--has-logo';
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-testimonial-box<?php echo esc_attr( $block_classes ); ?><?php echo esc_attr( $content_block->get_block_classes( ( array( 'background_color' => 'transparent ' ) ) ) ); ?>">
	<div class="container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-testimonial-box__content bg-<?php echo esc_attr( $background_color ); ?>" />
	</div>
</section>
