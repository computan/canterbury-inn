<?php
/**
 * Text-Row Item
 *
 * Title:             Text-Row Item
 * Description:       Inner block for use with text-row block.
 * Instructions:
 * Category:          Text
 * Icon:              text
 * Keywords:          info, list, heading, paragraph, item
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * InnerBlocks:       true
 * Parent:            acf/text-row
 * Styles:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array();

$allowed_blocks = array( 'acf/content' );

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
					'fontSize'    => 't3',
				),
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

?>

<div class="block-text-row-item">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-text-row-item__content" templateLock="all" />
</div>
