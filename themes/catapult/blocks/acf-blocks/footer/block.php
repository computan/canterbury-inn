<?php
/**
 * Footer
 *
 * Title:             Footer
 * Description:       Block for use globally on the site footer.
 * Instructions:
 * Category:          Core
 * Icon:              info-outline
 * Keywords:          footer, global, address, logo, quick links, newsletter, copyright, social
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/footer-top', 'acf/footer-bottom' );

$template = array(
	array(
		'acf/footer-top',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
	array(
		'acf/footer-bottom',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-footer<?php echo esc_attr( $content_block->get_block_classes( array( 'background_color' => 'dark' ) ) ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-footer__container" />
</section>
