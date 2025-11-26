<?php
/**
 * Accordion-Centered
 *
 * Title:             Accordion-Centered
 * Description:       A section with text content and an accordion.
 * Instructions:
 * Category:          Accordion
 * Icon:              awards
 * Keywords:          show, hide, content, accordion, section, centered
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Text Width Styles: true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
		),
	),
	array(
		'acf/accordion',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-accordion-centered<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-accordion-centered__content-wrapper content-wrapper" />
</section>
