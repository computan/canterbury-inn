<?php
/**
 * Tab-Standard
 *
 * Title:             Tab-Standard
 * Description:       A block with tabbed content.
 * Instructions:
 * Category:          Tab
 * Icon:              icon786-tab
 * Keywords:          tab, tabs, standard
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/tab-standard-tab' );

$template = array(
	array( 'acf/tab-standard-tab' ),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-tab-standard<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<select class="block-tab-standard__mobile-select select-filter" aria-label="<?php esc_html_e( 'Select Tab', 'catapult' ); ?>"></select>

		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-tab-standard__content" />
	</div>
</section>
