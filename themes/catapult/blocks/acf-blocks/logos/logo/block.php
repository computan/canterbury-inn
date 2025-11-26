<?php
/**
 * Logo
 *
 * Title:             Logo
 * Description:       Logos inner block.
 * Instructions:      Logo should be an SVG image using a 192x96 viewBox.
 * Category:          Logo
 * Icon:              screenoptions
 * Keywords:          logo, logos, brands, partners
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/logos, acf/logo-carousel
 * Image Size:        logo-block
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_id = $content_block->get_block_id();

$allowed_blocks = array( 'core/image' );

$template = array(
	array(
		'core/image',
	),
);

?>

<figure class="block-logo">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" />
</figure>
