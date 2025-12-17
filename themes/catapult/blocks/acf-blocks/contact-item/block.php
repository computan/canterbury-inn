<?php
/**
 * Contact-Item
 *
 * Title:             Contact-Item
 * Description:       An inline wrapper for contact info with a heading and text.
 * Instructions:
 * Category:          Base
 * Icon:              phone
 * Keywords:          content, contact, phone, email, address
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:
 * Context:           acf/hero-contact, acf/hero-profile-staff
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/paragraph' );

$heading_level = 3;

if ( ! empty( $context ) && ( ! empty( $context['hero-contact'] ) || ! empty( $context['hero-profile-staff'] ) ) ) {
	$heading_level = 2;
}

$template = array(
	array(
		'core/columns',
		array(),
		array(
			array(
				'core/column',
				array(
					'width' => '20%',
				),
				array(
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
						),
					),
				),
			),
			array(
				'core/column',
				array(
					'width' => '80%',
				),
				array(
					array(
						'core/heading',
						array(
							'level'       => 3,
							'placeholder' => __( 'Add heading here.', 'catapult' ),
							'fontSize'    => 'overline',
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
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-contact-item" />
