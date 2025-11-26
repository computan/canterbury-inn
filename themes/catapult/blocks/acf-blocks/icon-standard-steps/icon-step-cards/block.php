<?php
/**
 * Icon-Step-Cards
 *
 * Title:             Icon-Step-Cards
 * Description:       Card block for use within Icon-Standard-Steps block.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, flexible, links
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: image
 * Ancestor:          acf/icon-standard-steps
 * InnerBlocks:       true
 * CSS Custom Props:  cards_per_row: 3
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$cards_per_row = $content_block->get_parent_field( 'cards_per_row', 'acf/cards' );

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button', 'acf/icon' );
$image_size     = 'card-image-link-4';

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add pill text here.', 'catapult' ),
			'fontSize'    => 'overline',
			'className'   => 'pill',
		),
	),
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'title-1',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
			'fontSize'    => 'subtitle-1',
		),
	),
	array(
		'core/list',
		array(
			'placeholder' => __( 'Add List here.', 'catapult' ),
			'fontSize'    => 'subtitle-1',
			'className'   => 'card-list',
		),
	),
);

?>

<div class="block-icon-step-cards">
	<?php echo wp_kses_post( $content_block->get_block_image_and_video( $image_size, 'block-icon-step-cards__image-wrapper image-wrapper' ) ); ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-icon-step-cards__content" />
</div>
