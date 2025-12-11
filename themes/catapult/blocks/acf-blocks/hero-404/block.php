<?php
/**
 * Hero-404
 *
 * Title:             Hero-404
 * Description:       Hero block for use on the 404 page.
 * Instructions:
 * Category:          Hero
 * Icon:              warning
 * Keywords:          hero, centered, 404
 * Post Types:        theme_block, library_block
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:  
 * InnerBlocks:       true
 * Text Width Styles: true
 * Starts With Text:
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
		'core/paragraph',
		array(
			'content'   => __( '404 Error', 'catapult' ),
			'fontSize'  => 'overline',
			'textAlign' => 'center',
		),
	),
	array(
		'core/heading',
		array(
			'level'     => 1,
			'content'   => __( 'Oops, we can’t seem to find the page you are looking for', 'catapult' ),
			'textAlign' => 'center',
			'fontSize'  => 't2',
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
					'className' => 'is-style-tertiary',
					'text'      => __( 'Go to Home', 'catapult' ),
					'url'       => home_url(),
				),
			),
			array(
				'core/button',
				array(
					'className' => 'is-style-tertiary',
					'text'      => __( 'Contact Us', 'catapult' ),
					'url'       => home_url( '/contact-us' ),
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-404<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="content-wrapper content-wrapper--no-offset" />
</section>
