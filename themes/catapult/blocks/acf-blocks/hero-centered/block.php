<?php
/**
 * Hero-Centered
 *
 * Title:             Hero-Centered
 * Description:       Hero with centered text and optional image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, centered, image
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, image, video, background_video
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Text Width Styles: true
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$section_classes = '';

if ( empty( $image ) ) {
	$image = get_post_thumbnail_id();
}

$allowed_blocks = catapult_text_blocks();

if ( ! empty( $image ) ) {
	$section_classes = ' block-hero-centered--has-image';
}

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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-centered<?php echo esc_attr( $section_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link(); ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="content-wrapper" />

	<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'col-12', 'block-hero-centered__image', '1.772972973' ) ); ?>
</section>
