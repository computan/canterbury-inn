<?php
/**
 * Icon-Image-Stacked
 *
 * Title:             Icon-Image-Stacked
 * Description:       A block with flexible icon content blocks and a side image.
 * Instructions:
 * Category:          Icon
 * Icon:              marker
 * Keywords:          icon, content, image, side
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, image, video
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'acf/icon-contents' ) );

$template = array(
	array(
		'acf/icon-contents',
		array(
			'data' => array(
				'field_6398a0e3cbb8f' => '1',
			),
		),
		array(
			array(
				'acf/icon-content',
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-icon-image-stacked<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container block-icon-image-stacked__container">
		<div class="block-icon-image-stacked__image-col">
			<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'col-7', 'block-icon-image-stacked__image-wrapper image-wrapper' ) ); ?>
		</div>

		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-icon-image-stacked__content-col" />
	</div>
</section>
