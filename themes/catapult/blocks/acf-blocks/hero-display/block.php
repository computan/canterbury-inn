<?php
/**
 * Hero-Display
 *
 * Title:             Hero-Display
 * Description:       Hero with background image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, display, image
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_color, background_image, background_video
 * Background Colors:
 * Default BG Color:  neutral-3
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   2.2.10
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

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
			'fontSize'    => 'display',
		),
	),
	array(
		'core/buttons',
		array(),
		array(
			array(
				'core/button',
				array(
					'className' => 'is-style-primary',
				),
			),
			array(
				'core/button',
				array(
					'className' => 'is-style-secondary',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-display<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>

	<div class="container block-hero-display__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-display__content" />
	</div>
</section>
