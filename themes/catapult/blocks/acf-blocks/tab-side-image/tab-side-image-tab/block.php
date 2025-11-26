<?php
/**
 * Tab-Side-Image-Tab
 *
 * Title:             Tab-Side-Image-Tab
 * Description:       Tab-Side-Image tab inner block.
 * Instructions:
 * Category:          Tab
 * Icon:              icon786-tab
 * Keywords:          tab, tabs, side, image
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: image, video
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Parent:            acf/tab-side-image
 * Button Styles:     Tab
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$image = get_field( 'image' );

$allowed_blocks = array( 'core/button', 'acf/content' );

$template = array(
	array(
		'core/button',
		array(
			'className' => 'is-style-tab',
			'lock'      => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
	array(
		'acf/content',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

$block_classes = '';

if ( ! empty( $image ) ) {
	$block_classes = ' block-tab-side-image-tab--has-image';
}

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-tab-side-image-tab<?php echo esc_attr( $block_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>" />

<div class="block-tab-side-image-tab__figure">
	<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'col-6-square', 'block-tab-side-image-tab__image-wrapper image-wrapper' ) ); ?>
</div>
