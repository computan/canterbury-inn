<?php
/**
 * Get primary term from Yoast otherwise get first term
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Get primary term from Yoast otherwise get first term
 *
 * @param string $taxonomy  Taxonomy slug.
 * @param string $post_id   The post ID.
 * @param array  $args {
 *     Optional. Array of parameters.
 *
 *     @type int       return                   Type of value to return - term, term_id, or name (default).
 *     @type bool      icon                     Whether to output the icon (if "icon" ACF field for the term exists).
 *     @type bool      ignore_uncategorized     Whether to ignore the uncategorized taxonomy term.
 */
function catapult_get_primary_term( $taxonomy, $post_id = null, $args = array() ) {
	$args = shortcode_atts(
		array(
			'return'               => 'name',
			'icon'                 => false,
			'ignore_uncategorized' => true,
		),
		$args
	);

	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
		$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
	}

	if ( ! empty( $wpseo_primary_term ) ) {
		$term = get_term( $wpseo_primary_term );
	} else {
		$categories = get_the_terms( $post_id, $taxonomy );

		if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
			$term = $categories[0];
		}
	}

	if ( ! empty( $term ) && 'Uncategorized' !== $term->name ) {
		if ( ! empty( $args['return'] ) ) {
			if ( true === $args['ignore_uncategorized'] && 'Uncategorized' === $term->name ) {
				return false;
			}

			if ( 'term' === $args['return'] || 'object' === $args['return'] ) {
				return $term;
			} elseif ( 'term_id' === $args['return'] || 'id' === $args['return'] || 'ID' === $args['return'] ) {
				return $term->term_id;
			} else {
				$output = $term->name;
				$icon   = '';

				if ( ! empty( $args['icon'] ) && true === $args['icon'] ) {
					$icon = get_field( 'icon', $term );

					if ( ! empty( $icon ) ) {
						$output = '<span class="icon ' . $icon . '"></span>' . $output;
					}
				}

				return $output;
			}
		}
	}
}
