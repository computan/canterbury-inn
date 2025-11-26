<?php
/**
 * Media-Slider-Fullscreen
 *
 * Title:             Media-Slider-Fullscreen
 * Description:       Block with images or video.
 * Instructions:
 * Category:          Media
 * Icon:              format-image
 * Keywords:          image, images, slider, carousel, swiper, gallery, fullscreen
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Image Size:        media-slider-fullscreen
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$content_block->replace( 'wp-block-image', 'wp-block-image swiper-slide' );

$allowed_blocks = array( 'core/image' );

?>

<section id="<?php echo esc_attr( $content_block->get_block_id() ); ?>" class="acf-block block-media-slider-fullscreen<?php echo wp_kses_post( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<div class="swiper" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Media slider.', 'catapult' ); ?>">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="swiper-wrapper" />

			<button class="swiper-button-prev swiper-button--color-alt"><?php esc_html_e( 'Previous slide', 'catapult' ); ?></button>
			<button class="swiper-button-next swiper-button--color-alt"><?php esc_html_e( 'Next slide', 'catapult' ); ?></button>
		</div>
	</div>
</section>
