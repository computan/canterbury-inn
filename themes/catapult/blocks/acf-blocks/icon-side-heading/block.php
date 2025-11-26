<?php
/**
 * Icon-Side-Heading
 *
 * Title:             Icon-Side-Heading
 * Description:       A block with flexible icon content blocks and a side heading.
 * Instructions:
 * Category:          Icon
 * Icon:              marker
 * Keywords:          icon, content, side, heading
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/content', 'acf/icon-contents' );

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
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
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
	array(
		'acf/icon-contents',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-icon-side-heading<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-icon-side-heading__container" />
</section>
