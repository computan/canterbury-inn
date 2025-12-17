<?php
/**
 * Hero-Contact
 *
 * Title:             Hero-Contact
 * Description:       Hero section with side contact form.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, contact, form
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
 * @since   2.0.0
 * @since   2.2.6
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
	'acf/contact-item',
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
				'acf/contact-item',
			),
			array(
				'acf/contact-item',
			),
			array(
				'acf/contact-item',
			),
			array(
				'core/group',
				array(
					'className' => 'social-buttons-wrapper',
				),
				array(
					array(
						'core/heading',
						array(
							'level'    => 2,
							'content'  => __( 'Connect', 'catapult' ),
							'fontSize' => 'overline',
						),
					),
					array(
						'core/buttons',
						array(),
						array(
							array(
								'core/button',
								array(
									'className'  => 'is-style-social',
									'buttonIcon' => 'icon-instagram',
								),
							),
							array(
								'core/button',
								array(
									'className'  => 'is-style-social',
									'buttonIcon' => 'icon-facebook',
								),
							),
							array(
								'core/button',
								array(
									'className'  => 'is-style-social',
									'buttonIcon' => 'icon-x',
								),
							),
							array(
								'core/button',
								array(
									'className'  => 'is-style-social',
									'buttonIcon' => 'icon-pinterest',
								),
							),
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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-contact<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-hero-contact__container" />
</section>
