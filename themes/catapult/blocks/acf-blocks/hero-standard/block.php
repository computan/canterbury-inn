<?php
/**
 * Hero-Standard
 *
 * Title:             Hero-Standard
 * Description:       Hero with two columns - content plus an image.
 * Instructions:
 * Category:          Hero
 * Icon:              align-pull-right
 * Keywords:          hero, content, image, columns
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, image, video
 * Background Colors:
 * Default BG Color:
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
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
		),
	),
	array( 'core/buttons' ),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-standard<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link(); ?>

	<div class="container block-hero-standard__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-standard__content" />

		<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'col-6', 'block-hero-standard__image', '1.33333333' ) ); ?>
	</div>
</section>
