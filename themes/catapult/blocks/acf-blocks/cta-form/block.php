<?php
/**
 * CTA-Form
 *
 * Title:             CTA-Form
 * Description:       Call to action block with form on the side.
 * Instructions:
 * Category:          CTA
 * Icon:              forms
 * Keywords:          cta, call to action, form, contact, side
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: transparent, white, green, dark, light
 * Default BG Color:  light
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/content', 'acf/form' );

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
		),
	),
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
			),
		),
	),
);
?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-cta-form<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-cta-form__container" />
</section>
