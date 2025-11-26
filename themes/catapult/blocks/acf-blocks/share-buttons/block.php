<?php
/**
 * Share-Buttons
 *
 * Title:             Share-Buttons
 * Description:       Buttons to share the current post with Add to Any.
 * Instructions:
 * Category:          Base
 * Icon:              share
 * Keywords:          share, icons, social, media, link, facebook, twitter, linkedin, url
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          share-icons
 * JS Deps:           add-to-any
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Parent:
 * Wrap InnerBlocks:  false
 * Styles:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

$template = array(
	array(
		'core/heading',
		array(
			'level'    => 3,
			'content'  => __( 'Share', 'catapult' ),
			'fontSize' => 'overline',
		),
	),
);

$allowed_blocks = catapult_text_blocks();

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_classes = $content_block->get_block_classes();

?>

<div class="block-share-buttons">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

	<?php catapult_get_component( 'share-icons' ); ?>
</div>
