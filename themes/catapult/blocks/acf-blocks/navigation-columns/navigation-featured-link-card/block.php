<?php
/**
 * Navigation-Featured-Link-Card
 *
 * Title:             Navigation-Featured-Link-Card
 * Description:       Card block for use within parent Navigation-Featured-Link-Column block.
 * Instructions:
 * Category:          Navigation
 * Icon:              admin-links
 * Keywords:          cards, card, flexible, links, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: image, video
 * Ancestor:          acf/navigation-featured-link-column
 * InnerBlocks:       true
 * Button Styles:     Tertiary
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/button' );

$template = array(
	array(
		'core/button',
		array(
			'className'  => 'is-style-tertiary',
			'buttonIcon' => 'icon-arrow-right',
		),
	),
);

?>

<div class="navigation-featured-link-card">
	<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'card-image-link-3', 'navigation-featured-link-card__image-wrapper image-wrapper' ) ); ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="navigation-featured-link-card__content" />
</div>
