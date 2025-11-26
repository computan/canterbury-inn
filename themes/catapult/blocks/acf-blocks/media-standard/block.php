<?php
/**
 * Media-Standard
 *
 * Title:             Media-Standard
 * Description:       Block with images or video.
 * Instructions:
 * Category:          Media
 * Icon:              format-image
 * Keywords:          image, images, composition, collage, media
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:            Spacing Regular, Spacing Small
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/image' );

?>

<section id="<?php echo esc_attr( $content_block->get_block_id() ); ?>" class="acf-block block-media-standard<?php echo wp_kses_post( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="row block-media-standard__row" />
	</div>
</section>
