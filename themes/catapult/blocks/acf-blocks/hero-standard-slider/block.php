<?php
/**
 * Hero-Standard-Slider
 *
 * Title:             Hero-Standard-Slider
 * Description:       Hero Slider with two columns - content plus an image.
 * Instructions:
 * Category:          Hero
 * Icon:              align-pull-right
 * Keywords:          hero, content, image, columns, slider, slide
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:          slider
 * JS Deps:
 * Global ACF Fields: scroll_id
 * InnerBlocks:       true
 * Background Colors: transparent, white, dark
 * Default BG Color:  dark
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/hero-standard-slide' );

$template = array(
	array( 'acf/hero-standard-slide' ),
	array( 'acf/hero-standard-slide' ),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-standard-slider<?php echo wp_kses_post( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link(); ?>
	<div class="swiper" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Hero slider.', 'catapult' ); ?>">
		<div class="swiper-wrapper">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />				
		</div>					
	</div>
</section>
