<?php
/**
 * Accordion-Side-Image
 *
 * Title:             Accordion-Side-Image
 * Description:       Accordion with side images.
 * Instructions:
 * Category:          Accordion
 * Icon:              awards
 * Keywords:          accordion, side, image
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:          acf/accordion
 * JS Deps:           acf/accordion-item
 * Global ACF Fields: scroll_id
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
 * @since   3.1.6
 * @since   3.1.7
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( 'acf/accordion' );

$content_block->replace( 'is-style-multiple-open', 'is-style-single-open' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'acf/accordion',
		array(
			'className' => 'is-style-single-open',
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-accordion-side-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<div class="block-accordion-side-image__row">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-accordion-side-image__content" />

			<div class="block-accordion-side-image__spacer"><div class="block-accordion-side-image__spacer-inner"></div></div>
		</div>
	</div>
</section>
