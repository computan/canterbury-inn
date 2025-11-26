<?php
/**
 * Hero-Standard-Slide
 *
 * Title:             Hero-Standard-Slide
 * Description:       Single slide for Hero Standard Slider with two columns - content plus an image.
 * Instructions:
 * Category:          Hero
 * Icon:              align-pull-right
 * Keywords:          hero, content, image, columns, slider, slide
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Parent:            acf/hero-standard-slider
 * Global ACF Fields: image
 * InnerBlocks:       true
 * Background Colors: transparent
 * Default BG Color:  transparent
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

if ( empty( $image ) ) {
	$image = get_post_thumbnail_id();
}

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add pre-heading here.', 'catapult' ),
			'fontSize'    => 'overline',
		),
	),
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'display',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
			'fontSize'    => 'body',
		),
	),
);

?>

<div class="block-hero-standard-slide swiper-slide">
	<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'large', 'background-image-wrapper', '1.33333333' ) ); ?>
	<div class="block-hero-standard-slide__container">
		<div class="container">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-standard-slide__content" />
		</div>
	</div>
</div>
