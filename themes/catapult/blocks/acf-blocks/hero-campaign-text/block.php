<?php
/**
 * Hero-Campaign-Text
 *
 * Title:             Hero-Campaign-Text
 * Description:       Hero section with side form and extended text content.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, contact, form, demo, request, campaign, text, copy, content, max
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
 * Image Size:        col-8
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
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
	'acf/content',
	'acf/form',
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
				'core/image',
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
				'core/heading',
				array(
					'level'       => 2,
					'placeholder' => __( 'Add heading here.', 'catapult' ),
				),
			),
			array(
				'contact-form-7/contact-form-selector',
				array(
					'label' => 'Select a Contact Form',
				),
			),
			array(
				'acf/form-disclaimer',
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-campaign-text<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-hero-campaign-text__container" />
</section>
