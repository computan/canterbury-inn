<?php
/**
 * Logo-Carousel
 *
 * Title:             Logo-Carousel
 * Description:       Block with row of logos.
 * Instructions:
 * Category:          Logo
 * Icon:              ellipsis
 * Keywords:          logo, logos, brands, partners, animation, carousel, slider
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:          slider
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$content_block->replace( '"block-logo"', '"block-logo swiper-slide"' );

$allowed_blocks = array( 'acf/logo' );

$template = array(
	array(
		'acf/logo',
	),
);

$background_color = get_field( 'background_color' );

if ( empty( $background_color ) ) {
	$background_color = 'white';
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-logo-carousel<?php echo wp_kses_post( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<div class="swiper" role="group" aria-roledescription="carousel" aria-label="<?php esc_html_e( 'Logo slider.', 'catapult' ); ?>">
			<div class="swiper-wrapper">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
			</div>

			<div class="block-logo-carousel__overlay bg-<?php echo esc_attr( $background_color ); ?>"></div>
		</div>
	</div>
</section>
