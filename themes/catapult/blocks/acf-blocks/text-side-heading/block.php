<?php
/**
 * Text-Side-Heading
 *
 * Title:             Text-Side-Heading
 * Description:       Text block with side heading column.
 * Instructions:
 * Category:          Text
 * Icon:              text
 * Keywords:          info, heading, paragraph, button, content, section, column, text
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

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
				'core/paragraph',
				array(
					'placeholder' => __( 'Add pre-heading here.', 'catapult' ),
					'fontSize'    => 'overline',
				),
			),
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
		'acf/content',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
		array(
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
							'className' => 'is-style-tertiary',
						),
					),
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-text-side-heading<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-text-side-heading__container" />
</section>
