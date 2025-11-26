<?php
/**
 * Hero-Standard-Slide
 *
 * Title:             Hero-Standard-Slide-Wrapper
 * Description:       Wrapper slider for Hero Standard Slide.
 * Instructions:
 * Category:          Hero
 * Icon:              align-pull-right
 * Keywords:          hero, content, image, columns, slider, slide
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * Parent:            acf/hero-standard-slider
 * Global ACF Fields:
 * InnerBlocks:
 * Background Colors: transparent, white
 * Default BG Color:  white
 * Wrap InnerBlocks:  false
 * Mode:              preview
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( 'acf/hero-standard-slide' );

$template = array(
	array(
		'acf/hero-standard-slide',
	),
);

?>

<div class="block-hero-standard-slide-wrapper">
	<div class="swiper" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Hero slider.', 'catapult' ); ?>">
		<div class="swiper-wrapper">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
		</div>
	</div>
	<div class="swiper-button-wrapper">
		<div class="swiper-buttons">
			<button class="swiper-button-prev swiper-button--outline">
				<div class="icon-arrow-left"></div>
			</button>
			<button class="swiper-button-next swiper-button--outline">
				<div class="icon-arrow-right"></div>
			</button>
		</div>
		<div class="swiper-pagination"></div>
	</div>
</div>
