<?php
/**
 * Footer Top
 *
 * Title:             Footer Top
 * Description:       Block for use globally on the site footer.
 * Instructions:
 * Category:          Core
 * Icon:              info-outline
 * Keywords:          footer, global, address, logo, quick links, newsletter, copyright, social, top
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/footer
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/footer-column' );

$template = array(
	array(
		'acf/footer-column',
		array(
			'data' => array(
				'column_width_mobile'  => '12',
				'column_width_tablet'  => '6',
				'column_width_desktop' => '5',
				'order_mobile'         => '1',
				'order_tablet'         => '1',
			),
		),
		array(
			array(
				'core/image',
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add address here.', 'catapult' ),
				),
			),
		),
	),
	array(
		'acf/footer-column',
		array(
			'data' => array(
				'column_width_mobile'  => '6',
				'column_width_tablet'  => '3',
				'column_width_desktop' => '2',
				'order_mobile'         => '3',
				'order_tablet'         => '2',
			),
		),
		array(),
	),
	array(
		'acf/footer-column',
		array(
			'data' => array(
				'column_width_mobile'  => '6',
				'column_width_tablet'  => '3',
				'column_width_desktop' => '2',
				'order_mobile'         => '4',
				'order_tablet'         => '3',
			),
		),
		array(),
	),
	array(
		'acf/footer-column',
		array(
			'data' => array(
				'column_width_mobile'  => '12',
				'column_width_tablet'  => '12',
				'column_width_desktop' => '3',
				'order_mobile'         => '2',
				'order_tablet'         => '4',
			),
		),
		array(
			array(
				'core/heading',
				array(
					'level'       => 2,
					'placeholder' => 'Add heading here.',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text here.', 'catapult' ),
				),
			),
			array(
				'acf/form',
				array(),
				array(
					array(
						'gravityforms/form',
					),
					array(
						'acf/form-disclaimer',
					),
				),
			),
		),
	),
);

?>

<div class="block-footer-top">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-footer-top__content" />
</div>
