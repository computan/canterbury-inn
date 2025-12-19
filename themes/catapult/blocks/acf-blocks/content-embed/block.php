<?php
/**
 * Content-Embed
 *
 * Title:             Content Embed
 * Description:       Display iframe.
 * Instructions:
 * Category:          Content
 * Icon:              format-quote
 * Keywords:          booking, calendar
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: transparent, white
 * Default BG Color:  white
 * InnerBlocks:       true
 * Styles:            Small, Medium, Full
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_id      = $content_block->get_block_id();
$iframe_source = get_field( 'iframe_source', $block_id );

$allowed_blocks = catapult_text_blocks();
?>

<?php if ( ! empty( $iframe_source ) ) : ?>
	<section
		<?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?>
		<?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>
		class="acf-block block-content-embed<?php echo esc_attr( $content_block->get_block_classes() ); ?>"
	>
		<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
		<iframe
			src="<?php echo esc_attr( $iframe_source ); ?>"
			scrolling="yes"
			style="border: 0;"
		></iframe>
	</section>
<?php endif; ?>
