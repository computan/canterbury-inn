<?php
/**
 * CTA-Standard
 *
 * Title:             CTA-Standard
 * Description:       Call to action block.
 * Instructions:
 * Category:          CTA
 * Icon:              icon786-cta
 * Keywords:          cta, call to action, standard, button, banner
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: white, gradient-a, gradient-d, neutral-1, neutral-11
 * Default BG Color:  gradient-a
 * InnerBlocks:       true
 * Text Width Styles: true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
			'fontSize'    => 't2',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
			'align'       => 'center',
			'fontSize'    => 'body-1',
		),
	),
	array(
		'core/buttons',
		array(
			'layout' => array(
				'justifyContent' => 'center',
			),
		),
		array(
			array(
				'core/button',
				array(
					'className'   => 'is-style-primary',
					'placeholder' => 'Primary CTA',
				),
			),
			array(
				'core/button',
				array(
					'className'   => 'is-style-secondary',
					'placeholder' => 'Secondary CTA',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-cta-standard<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="content-wrapper" />
</section>
