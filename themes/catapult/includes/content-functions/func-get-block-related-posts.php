<?php
/**
 * Get the post objects for a related posts block.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.5
 */

/**
 * Get the post objects for a related posts block.
 *
 * @param bool $enqueue_card_assets    Whether or not to load the CSS/JS for the cards from each post type.
 *
 * @return  array   An array of post objects.
 */
function catapult_get_block_related_posts( $enqueue_card_assets = true ) {
	$related_posts  = array();
	$post_selection = get_field( 'post_selection' );
	$post_order     = get_field( 'post_order' );
	$terms          = get_field( 'terms' );
	$taxonomies     = get_field( 'taxonomies' );
	$manual         = get_field( 'manual' );

	if ( ! empty( $post_selection ) ) {
		$cards_per_row = get_field( 'cards_per_row' );
		if ( empty( $cards_per_row ) ) {
			$cards_per_row = 3;
		}
		$args = array(
			'posts_per_page' => intval( $cards_per_row ),
			'post_status'    => array( 'publish' ),
		);

		if ( ! empty( $post_order ) ) {
			$args['orderby'] = $post_order;
		} else {
			$args['orderby'] = 'rand';
		}

		if ( is_singular() ) {
			$args['post__not_in'] = array( get_the_ID() );
			$args['post_type']    = array( get_post_type() );
		}

		if ( 'automatic' === $post_selection ) {
			$terms      = get_field( 'terms' );
			$post_types = get_field( 'post_types' );

			if ( ! empty( $post_types ) ) {
				$args['post_type'] = $post_types;
			} else {
				return $related_posts;
			}

			if ( ! empty( $terms ) ) {
				$taxonomy_data = array();

				foreach ( $terms as $this_term ) {
					$term_data = explode( '&&', $this_term );

					if ( ! empty( $term_data[0] ) && ! empty( $term_data[1] ) ) {
						if ( empty( $taxonomy_data[ $term_data[0] ] ) ) {
							$taxonomy_data[ $term_data[0] ] = array();
						}

						$taxonomy_data[ $term_data[0] ][] = $term_data[1];
					}
				}

				if ( ! empty( $taxonomy_data ) ) {
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					$args['tax_query'] = array( 'relation' => 'OR' );

					foreach ( $taxonomy_data as $this_taxonomy => $this_terms ) {
						$args['tax_query'][] = array(
							'taxonomy' => $this_taxonomy,
							'field'    => 'term_id',
							'terms'    => $this_terms,
						);
					}
				}
			}

			$related_posts = get_posts( $args );
		} elseif ( 'primary_term' === $post_selection ) {
			$taxonomies = get_field( 'taxonomies' );

			if ( ! empty( $taxonomies ) && is_singular() ) {

				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$args['tax_query'] = array( 'relation' => 'OR' );

				foreach ( $taxonomies as $this_taxonomy ) {
					$primary_term = catapult_get_primary_term( $this_taxonomy, null, array( 'return' => 'term_id' ) );

					if ( ! empty( $primary_term ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => $this_taxonomy,
							'field'    => 'term_id',
							'terms'    => $primary_term,
						);
					}
				}
			}

			$related_posts = get_posts( $args );
		} elseif ( 'manual' === $post_selection ) {
			if ( is_array( $manual ) && ! empty( $manual ) && is_object( $manual[0] ) ) {
				$related_posts = $manual;
			}
		}
	}

	if ( ! empty( $args['post_type'] ) ) {
		foreach ( $args['post_type'] as $post_type ) {
			$handle = 'catapult-component-' . $post_type . '-card';

			if ( wp_style_is( $handle, 'registered' ) ) {
				wp_enqueue_style( $handle );
			}

			if ( wp_script_is( $handle, 'registered' ) ) {
				wp_script_is( $handle );
			}
		}
	}

	return $related_posts;
}
