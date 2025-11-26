<?php
/**
 * Modifications for `wp_kses()` functionality.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 */

/**
 * Set the allowed HTML for `wp_kses()` by context type.
 *
 * @param array|string $context Context to judge allowed tags by.
 * @param string       $context_type Context name.
 */
function allowed_html_by_context( $context, $context_type ) {
	if ( 'main-header-nav' === $context_type ) {
		$context['div'] = array(
			'class' => true,
		);

		$context['ul'] = array(
			'id'    => true,
			'class' => true,
		);

		$context['li'] = array(
			'id'    => true,
			'class' => true,
		);

		$context['span'] = array(
			'class' => true,
		);
	}

	if ( 'post' === $context_type ) {
		$context['svg'] = array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true,
		);

		$context['g'] = array(
			'fill' => true,
		);

		$context['title'] = array(
			'title' => true,
		);

		$context['path'] = array(
			'd'    => true,
			'fill' => true,
		);
	}

	if ( 'inline-style' === $context_type ) {
		$context = array(
			'style' => array(),
		);
	}

	if ( 'button' === $context_type ) {
		$context = array(
			'span' => array(
				'class' => true,
			),
		);
	}

	if ( isset( $context['img'] ) && ! isset( $context['img']['srcset'] ) ) {
		$context['img'] = array_merge(
			$context['img'],
			array(
				'srcset' => true,
				'sizes'  => true,
			)
		);
	}

	$tags_to_allow_aria_attributes = array(
		'button',
		'input',
		'select',
		'textarea',
		'a',
		'div',
		'span',
		'nav',
		'ul',
		'li',
	);

	$allowed_aria_attributes = array(
		'aria-label'           => true,
		'aria-labelledby'      => true,
		'aria-hidden'          => true,
		'aria-expanded'        => true,
		'aria-controls'        => true,
		'aria-live'            => true,
		'aria-describedby'     => true,
		'aria-disabled'        => true,
		'aria-current'         => true,
		'aria-checked'         => true,
		'aria-roledescription' => true,
	);

	foreach ( $tags_to_allow_aria_attributes as $tag_to_allow_aria_attributes ) {
		if ( empty( $context[ $tag_to_allow_aria_attributes ] ) ) {
			$context[ $tag_to_allow_aria_attributes ] = $allowed_aria_attributes;
		} else {
			$context[ $tag_to_allow_aria_attributes ] = array_merge( $allowed_aria_attributes, $context[ $tag_to_allow_aria_attributes ] );
		}
	}

	return $context;
}
add_filter( 'wp_kses_allowed_html', 'allowed_html_by_context', 10, 2 );
