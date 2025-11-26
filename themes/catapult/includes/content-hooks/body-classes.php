<?php
/**
 * Add to the main body class.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

namespace Catapult\BodyClasses;

/**
 * Modify the document body classes.
 *
 * @param array $classes       An array of classes to add to the body element.
 */
function body_class( $classes ) {
	global $blocks;

	if ( ! empty( $blocks ) ) {
		if ( ! empty( $blocks[0]['blockName'] ) && false !== strpos( $blocks[0]['blockName'], 'hero' ) ) {
			$classes[] = 'page-has-hero';
		}

		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) ) {
				if ( 'acf/quick-links' === $block['blockName'] ) {
					$classes[] = 'has-quick-links';
				}
			}
		}
	}

	return $classes;
}
add_filter( 'body_class', 'Catapult\BodyClasses\body_class' );
