<?php
/**
 * Tab-Side-Image
 *
 * Title:             Tab-Side-Image
 * Description:       A block with tabbed content and a side image.
 * Instructions:
 * Category:          Tab
 * Icon:              icon786-tab
 * Keywords:          tab, tabs, side, image
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( 'acf/tab-side-image-tab' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'acf/tab-side-image-tab',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-tab-side-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<div class="block-tab-side-image__row">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-tab-side-image__content" />

			<div class="block-tab-side-image__spacer"><div class="block-tab-side-image__spacer-inner"></div></div>
		</div>
	</div>
</section>
