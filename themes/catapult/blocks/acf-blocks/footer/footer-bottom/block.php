<?php
/**
 * Footer Bottom
 *
 * Title:             Footer Bottom
 * Description:       Block for use globally on the site footer.
 * Instructions:
 * Category:          Core
 * Icon:              info-outline
 * Keywords:          footer, global, address, logo, quick links, newsletter, copyright, social, bottom
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
 * Button Styles:     Social
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/content' );

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
				'core/paragraph',
				array(
					'content' => '© Organization Name [current_year]. All Rights Reserved',
				),
			),
			array(
				'core/paragraph',
				array(
					'content' => '<a href="#">Privacy Policy</a>',
				),
			),
			array(
				'core/paragraph',
				array(
					'content' => '<a href="#">Accessibility Policy</a>',
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
				'core/buttons',
				array(),
				array(
					array(
						'core/button',
						array(
							'className'  => 'is-style-social',
							'buttonIcon' => 'icon-linkedin',
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
							'buttonIcon' => 'icon-facebook',
						),
					),
					array(
						'core/button',
						array(
							'className'  => 'is-style-social',
							'buttonIcon' => 'icon-youtube',
						),
					),
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
);

?>

<div class="block-footer-bottom">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-footer-bottom__content" />
</div>
