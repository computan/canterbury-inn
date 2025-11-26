<?php
/**
 * Navigation-Link-Column
 *
 * Title:             Navigation-Link-Column
 * Description:       A column of links.
 * Instructions:
 * Category:          Base
 * Icon:              admin-links
 * Keywords:          link, links, column
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/navigation-columns
 * Button Styles:     Navigation Link, Tertiary
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/button', 'acf/navigation-detailed-link' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'overline',
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

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-navigation-link-column" />
