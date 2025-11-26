<?php
/**
 * CTA-Strip
 *
 * Title:             CTA-Strip
 * Description:       Simple call to action block.
 * Instructions:
 * Category:          CTA
 * Icon:              icon786-cta
 * Keywords:          cta, call to action, strip, button
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:  neutral-10
 * InnerBlocks:       true
 * Styles:            In Grid, Full Width
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$background_color = get_field( 'background_color' );

if ( empty( $background_color ) ) {
	$background_color = 'neutral-10';
}

$block_classes = $content_block->get_block_classes( array( 'background_color' => 'transparent' ) );

if ( empty( $block_classes ) || false === strpos( $block_classes, 'is-style-full-width' ) ) {
	$block_classes .= ' acf-block';
}

$allowed_blocks = array( 'acf/content', 'core/button' );

$template = array(
	array(
		'acf/content',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
		array(
			array(
				'core/heading',
				array(
					'level'       => 2,
					'placeholder' => __( 'Add heading here.', 'catapult' ),
				),
			),
		),
	),
	array(
		'core/button',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-cta-strip<?php echo esc_attr( $block_classes ); ?>">
	<div class="container block-cta-strip__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-cta-strip__content bg-<?php echo esc_attr( $background_color ); ?>" />
	</div>
</section>
