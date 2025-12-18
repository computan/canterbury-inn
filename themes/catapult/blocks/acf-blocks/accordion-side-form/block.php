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
 * Global ACF Fields: scroll_id, background_image
 * Background Colors: white, green, gold, secondary-teal-a, gradient-a, gradient-d, neutral-1, neutral-11
 * Default BG Color:  secondary-teal-a
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
			array(
				'core/column',
				array(
					'width' => '52.5%',
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
			array(
				'core/column',
				array(
					'width' => '43.5%',
					'className'   => 'booking-form',
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

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-accordion-side-form<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-accordion-side-form__container" />
</section>
