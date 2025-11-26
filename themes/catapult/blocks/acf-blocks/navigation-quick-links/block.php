<?php
/**
 * Navigation-Quick-Links
 *
 * Title:             Navigation-Quick-Links
 * Description:       A row of links for use in the hamburger style navigation menu.
 * Instructions:
 * Category:          Navigation
 * Icon:              editor-ul
 * Keywords:          nav, navigation, links, header, quick
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Ancestor:          catapult/navigation
 * Button Styles:     Navigation Link
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/button' );

$template = array(
	array(
		'core/heading',
		array(
			'level'    => 2,
			'content'  => __( 'Quick Links', 'catapult' ),
			'fontSize' => 'overline',
		),
	),
	array(
		'core/button',
		array(
			'className' => 'is-style-navigation-link',
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-navigation-quick-links" />
