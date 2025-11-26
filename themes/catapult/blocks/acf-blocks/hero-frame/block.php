<?php
/**
 * Hero-Frame
 *
 * Title:             Hero-Frame
 * Description:       Hero with background image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, frame, image
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
 * @since   2.2.10
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$background_color = get_field( 'background_color' );

if ( empty( $background_color ) ) {
	$background_color = 'transparent';
}

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
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-frame<?php echo esc_attr( $content_block->get_block_classes( array( 'background_color' => 'white' ) ) ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video( 'full-width', 'bg-' . $background_color ) ); ?>

	<div class="container block-hero-frame__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-frame__card bg-white" />
	</div>
</section>
