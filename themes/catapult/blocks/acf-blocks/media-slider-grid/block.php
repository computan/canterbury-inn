<?php
/**
 * Media-Slider-Grid
 *
 * Title:             Media-Slider-Grid
 * Description:       Block with images or video.
 * Instructions:
 * Category:          Media
 * Icon:              format-image
 * Keywords:          image, images, slider, carousel, swiper, gallery
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: white, neutral-11
 * Default BG Color:  white
 * InnerBlocks:       true
 * Image Size:        media-slider-grid
 * Image Wrapper:     true
 * Styles:            Spacing Regular, Spacing Small
 * Starts With Text:
 *
 * @package Catapult
 * @since   1.0.0
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$content_block->replace( 'wp-block-image', 'wp-block-image swiper-slide' );

$allowed_blocks = array( 'core/image' );

?>

<section id="<?php echo esc_attr( $content_block->get_block_id() ); ?>" class="acf-block block-media-slider-grid<?php echo wp_kses_post( $content_block->get_block_classes() ); ?>">
	<div class="container block-media-slider-grid__container">
		<div class="swiper" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Media slider.', 'catapult' ); ?>">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="swiper-wrapper" />

			<div class="swiper-button-wrapper image-wrapper">
				<button class="swiper-button-prev swiper-button--transparent"><?php esc_html_e( 'Previous slide', 'catapult' ); ?></button>
				<button class="swiper-button-next swiper-button--transparent"><?php esc_html_e( 'Next slide', 'catapult' ); ?></button>
			</div>
		</div>
	</div>
</section>
