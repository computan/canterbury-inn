<?php
/**
 * Hero-Donate
 *
 * Title:             Hero-Donate
 * Description:       Hero section with side donate form.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, donate, support, payment, form, contact
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array(
	'core/heading',
	'core/paragraph',
	'core/button',
	'core/html',
	'contact-form-7/contact-form-selector',
	'acf/form-disclaimer',
	'acf/form-item',
	'acf/post-tags-share',
	'acf/share-buttons',
	'acf/form',
	'acf/content',
);

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
					'level'       => 1,
					'placeholder' => __( 'Add heading here.', 'catapult' ),
					'fontSize'    => 'display',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
				),
			),
			array(
				'acf/share-buttons',
				array(),
				array(
					array(
						'core/heading',
						array(
							'level'    => 2,
							'content'  => __( 'Share', 'catapult' ),
							'fontSize' => 'overline',
						),
					),
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
				array(
					'label' => 'Select a Contact Form',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-donate<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-hero-donate__container" />
</section>
