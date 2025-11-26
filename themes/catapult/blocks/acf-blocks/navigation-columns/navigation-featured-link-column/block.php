<?php
/**
 * Navigation-Featured-Link-Column
 *
 * Title:             Navigation-Featured-Link-Column
 * Description:       A column with one or two featured links.
 * Instructions:
 * Category:          Navigation
 * Icon:              admin-links
 * Keywords:          link, links, column, featured
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

$allowed_blocks = array( 'core/heading', 'acf/navigation-featured-link-card', 'core/button' );

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
		'acf/navigation-featured-link-card',
	),
);

?>

<div class="block-navigation-featured-link-column">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-navigation-featured-link-column__content" />
</div>
