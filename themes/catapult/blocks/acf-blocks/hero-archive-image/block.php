<?php
/**
 * Hero-Archive-Image
 *
 * Title:             Hero-Archive-Image
 * Description:       Text hero with the name of the archive and a background image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, content, image, columns, archive, cpt, background, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_image
 * Background Colors:
 * Default BG Color:  dark
 * InnerBlocks:       true
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
			'fontSize'    => 't1',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add paragraph here.', 'catapult' ),
			'fontSize'    => 'body',
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-archive-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>
<div class="parent-bg-div">
<img src="<?php echo esc_url( get_template_directory_uri() . '/images/block-library/shape-bottom-whites.png' ); ?>" alt="<?php esc_attr_e( 'wave', 'catapult' ); ?>">
	</div>
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-archive-image__container content-wrapper" />
</section>
