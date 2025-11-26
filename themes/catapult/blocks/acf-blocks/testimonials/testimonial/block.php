<?php
/**
 * Testimonial
 *
 * Title:             Testimonial
 * Description:       Testimonial block for use within parent testimonials block.
 * Instructions:
 * Category:          Testimonial
 * Icon:              format-quote
 * Keywords:          testimonial, slider, quote
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Parent:            acf/testimonials
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Context:           acf/testimonial-standard, acf/testimonial-image, acf/testimonial-cards, acf/testimonial-box
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$testimonial                = get_field( 'testimonial' );
$custom_testimonial_content = get_field( 'custom_testimonial_content' );

if ( ! empty( $custom_testimonial_content ) ) {
	$name        = get_field( 'name' );
	$label       = get_field( 'label' );
	$logo        = get_field( 'logo' );
	$image       = get_field( 'image' );
	$button_link = get_field( 'button_link' );
}

if ( ! empty( $testimonial ) ) {
	$quote = get_field( 'quote', $testimonial );

	if ( ! empty( $name ) || empty( $custom_testimonial_content ) ) {
		$name = get_the_title( $testimonial );
	}

	if ( ! empty( $label ) || empty( $custom_testimonial_content ) ) {
		$label = get_field( 'label', $testimonial );
	}

	if ( ! empty( $logo ) || empty( $custom_testimonial_content ) ) {
		$logo = get_field( 'logo', $testimonial );
	}

	if ( ! empty( $image ) || empty( $custom_testimonial_content ) ) {
		$image = get_post_thumbnail_id( $testimonial );
	}
}

$allowed_blocks = array( 'core/paragraph' );

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add testimonial content here.', 'catapult' ),
		),
	),
);

if ( ! empty( $logo ) && catapult_is_block_library() && false === get_post_status( $logo ) ) {
	$logo = 'logo-placeholder-no-padding';
}

?>

<div class="block-testimonial swiper-slide">
	<?php if ( ! empty( $image ) && ( empty( $context ) || ! empty( $context['testimonial-image'] ) ) ) : ?>
		<div class="block-testimonial__image-col">
			<div class="block-testimonial__image-wrapper image-wrapper">
				<?php echo wp_kses_post( wp_get_attachment_image( $image, 'col-5-square', '', array( 'class' => 'block-testimonial__image' ) ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="block-testimonial__content-col">
		<?php if ( ! empty( $logo ) && ( empty( $context ) || empty( $context['testimonial-cards'] ) ) ) : ?>
			<?php
			echo wp_kses_post(
				wp_get_attachment_image(
					$logo,
					'logo-block',
					'',
					array(
						'class' => 'block-testimonial__logo',
					)
				)
			);
			?>
		<?php elseif ( empty( $context ) || ( empty( $context['testimonial-cards'] ) && empty( $context['testimonial-image'] ) ) ) : ?>
			<div class="block-testimonial__quote-icon icon-quote"></div>
		<?php elseif ( ! empty( $context ) && ! empty( $context['testimonial-image'] ) ) : ?>
			<div class="block-testimonial__quote-icon icon-quote-aligned"></div>
		<?php endif; ?>

		<blockquote class="block-testimonial__content">
			<?php if ( ! empty( $quote ) ) : ?>
				<?php echo wp_kses_post( $quote ); ?>
			<?php elseif ( ! empty( $custom_testimonial_content ) ) : ?>
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
			<?php endif; ?>
		</blockquote>

		<figcaption class="block-testimonial__slide-footer-content">
			<?php if ( ! empty( $name ) ) : ?>
				<div class="block-testimonial__slide-footer-name"><?php echo wp_kses_post( $name ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $label ) ) : ?>
				<cite class="block-testimonial__slide-footer-label">
					<?php echo wp_kses_post( $label ); ?>
				</cite>
			<?php endif; ?>
		</figcaption>

		<?php if ( ! empty( $button_link ) ) : ?>
			<?php echo wp_kses_post( catapult_array_to_link( $button_link, 'is-style-tertiary block-testimonial__button', array( 'icon' => 'icon-arrow-right' ) ) ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $context ) && ! empty( $context['testimonial-image'] ) && ! empty( $context['testimonial-image']['arrow_position'] ) && 'bottom' === $context['testimonial-image']['arrow_position'] ) : ?>
			<div class="block-testimonial__swiper-buttons">
				<button class="swiper-button-prev swiper-button--outline"><?php esc_html_e( 'Previous slide', 'catapult' ); ?></button>
				<button class="swiper-button-next swiper-button--outline"><?php esc_html_e( 'Next slide', 'catapult' ); ?></button>
			</div>
		<?php endif; ?>
	</div>
</div>
