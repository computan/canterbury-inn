<?php
/**
 * Card-List
 *
 * Title:             Card-List
 * Description:       A block with an image and custom content.
 * Instructions:
 * Category:          Content
 * Icon:              icon829-card-list
 * Keywords:          image, content, WYSIWYG
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, image, background_color
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.6
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$image_alignment = get_field( 'image_alignment' );

$block_classes       = $content_block->get_block_classes();
$content_col_classes = '';

if ( empty( $block_classes ) || false !== strpos( $block_classes, 'is-style-frame' ) ) {
	$block_classes = $content_block->get_block_classes( array( 'background_color' => 'transparent' ) );

	$background_color = get_field( 'background_color' );

	if ( ! empty( $background_color ) ) {
		$content_col_classes = ' bg-' . $background_color;
	}
}

if ( empty( $image_alignment ) ) {
	$image_alignment = 'right';
}

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 't2',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
			'fontSize'    => 'body-1',
		),
	),
	array(
		'core/buttons',
		array(),
		array(
			array(
				'core/button',
				array(
					'className'  => 'is-style-tertiary',
					'buttonIcon' => 'icon-arrow-right',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-card-list block-card-list--<?php echo esc_attr( $image_alignment ); ?><?php echo esc_attr( $block_classes ); ?>">
	<div class="container">
<div class="block-card-list__container bg-neutral-11">
		<div class="block-card-list__content-col<?php echo esc_attr( $content_col_classes ); ?>">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-card-list__content" />
		</div>

		<div class="block-card-list__image-col">
			<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'col-6-square', 'block-card-list__image-wrapper image-wrapper' ) ); ?>
		</div>
	</div>
	</div>
</section>
