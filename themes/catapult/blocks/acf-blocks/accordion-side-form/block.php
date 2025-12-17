<?php
/**
 * Accordion-Side-Form
 *
 * Title:             Accordion-Side-Form
 * Description:       A section with text content and an accordion.
 * Instructions:
 * Category:          Accordion
 * Icon:              awards
 * Keywords:          show, hide, content, accordion, section
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
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

$allowed_blocks = catapult_text_blocks( 'acf/accordion', 'acf/form' );


$template = array(
	array(
		'core/columns',
		array(),
		array(
			// Column 1
			array(
				'core/column',
				array(
					'width' => '50%',
				),
				array(
					array(
						'core/heading',
						array(
							'level'       => 5,
							'placeholder' => __( 'Add heading here.', 'catapult' ),
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
						'acf/accordion',
					),
				),
			),

			// Column 2
			array(
				'core/column',
				array(
					'width' => '50%',
				),
				array(
					array(
						'acf/form',
						array(
							'lock' => array(
								'move'   => true,
								'remove' => true,
							),
						),
						array(
							array(
								'contact-form-7/contact-form-selector',
								array(
									'label' => 'Select a Contact Form',
								),
							),
						),
					),
				),
			),
		),
	),
);



// $template = array(
// 	array(
// 		'core/heading',
// 		array(
// 			'level'       => 2,
// 			'placeholder' => __( 'Add heading here.', 'catapult' ),
// 			'textAlign'   => 'center',
// 		),
// 	),
// 	array(
// 		'acf/accordion',
// 	),
// 		array(
// 		'acf/form',
// 		array(
// 			'lock' => array(
// 				'move'   => true,
// 				'remove' => true,
// 			),
// 		),
// 		array(
// 			array(
// 				'contact-form-7/contact-form-selector',
// 				array(
// 					'label' => 'Select a Contact Form',
// 				),
// 			),
// 		),
// 	),
// );
// $template = array(
// 	array(
// 		'acf/content-section',
// 		array(
// 			'lock' => array(
// 				'move'   => true,
// 				'remove' => true,
// 			),
// 		),
// 		array(
// 			array(
// 				'core/heading',
// 				array(
// 					'level'    => 2,
// 					'content'  => __( 'Connect', 'catapult' ),
// 					'fontSize' => 'overline',
// 				),
// 			),
// 			array(
// 				'core/heading',
// 				array(
// 					'level'       => 1,
// 					'placeholder' => __( 'Add heading here.', 'catapult' ),
// 				),
// 			),
// 		),
// 	),
// 	array(
// 		'acf/accordion',
// 		array(
// 			'lock' => array(
// 				'move'   => true,
// 				'remove' => true,
// 			),
// 		),
// 	),
// 	array(
// 		'acf/form',
// 		array(
// 			'lock' => array(
// 				'move'   => true,
// 				'remove' => true,
// 			),
// 		),
// 		array(
// 			array(
// 				'contact-form-7/contact-form-selector',
// 				array(
// 					'label' => 'Select a Contact Form',
// 				),
// 			),
// 		),
// 	),
// );

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-accordion-side-form<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-accordion-side-form__container" />
</section>
