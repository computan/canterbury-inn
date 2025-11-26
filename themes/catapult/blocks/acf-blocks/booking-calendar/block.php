<?php
/**
 * Booking-Calendar
 *
 * Title:             Booking-Calendar
 * Description:       Display booking calendar.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          booking, calendar
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: transparent, white, light, neutral-12, dark, lime, green
 * Default BG Color:  neutral-12
 * InnerBlocks:       true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_id      = $content_block->get_block_id();
$iframe_source = get_field( 'iframe_source', $block_id );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-booking-calendar<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-booking-calendar__container" />
	<?php if ( ! empty( $iframe_source ) ) : ?>
		<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
		<iframe src="<?php echo esc_attr( $iframe_source ); ?>" scrolling="yes" style="border:0"></iframe>
	<?php endif; ?>
</section>
