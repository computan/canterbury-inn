<?php
/**
 * Navigation-Simple-Links
 *
 * Title:             Navigation-Simple-Links
 * Description:       A single column dropdown of links.
 * Instructions:
 * Category:          Navigation
 * Icon:              editor-ul
 * Keywords:          nav, navigation, links, header
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Ancestor:          catapult/navigation-submenu
 * Button Styles:     Navigation Link
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/button', 'acf/navigation-detailed-link' );

$template = array(
	array(
		'core/button',
		array(
			'className' => 'is-style-navigation-link',
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-navigation-simple-links" />
