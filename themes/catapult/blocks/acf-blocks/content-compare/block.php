<?php
/**
 * Content-Compare
 *
 * Title:             Content-Compare
 * Description:       A block with an custom content in two columns.
 * Instructions:
 * Category:          Content
 * Icon:              icon829-content-side-image
 * Keywords:          image, content, WYSIWYG, columns
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: background_color
 * InnerBlocks:       true
 * Background Colors: transparent, white, neutral-10
 * Default BG Color:  neutral-10
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/columns',
		array(),
		array(
			array(
				'core/column',
				array(
					'width'     => '100%',
					'className' => 'bg-white card-left',
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
							'fontSize'    => 't2',
						),
					),
					array(
						'core/list',
						array(
							'className' => 'is-style-check-icon',
						),
					),
					array(
						'core/buttons',
						array(),
						array(
							array(
								'core/button',
								array(
									'className' => 'is-style-secondary',
								),
							),
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
			array(
				'core/column',
				array(
					'width'     => '100%',
					'className' => 'bg-secondary card-right',
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
							'fontSize'    => 't2',
						),
					),
					array(
						'core/list',
						array(
							'className' => 'is-style-check-icon',
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
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-content-compare<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-content-compare__container" />
</section>
