<?php
/**
 * Content hooks related to the post filter blocks.
 *
 * @package Catapult
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

namespace Catapult\Filters;

/**
 * Filter rest response to send no results html for filter blocks
 *
 * @param array $result     Result object.
 * @param array $server     Server object.
 * @param array $request    Request object.
 */
function catapult_rest_pre_echo_response( $result, $server, $request ) {
	if ( $request->get_header( 'X-Filterblock' ) && empty( $result ) ) {
		$selected_filters = $request->get_header( 'X-Selectedfilters' );
		ob_start();
		catapult_get_component( 'filter-no-results', array( 'selected_filters' => $selected_filters ) );
		$no_results_html = ob_get_contents();
		ob_end_clean();

		return array( 'no_results' => $no_results_html );
	}

	return $result;
}
add_filter( 'rest_pre_echo_response', 'Catapult\Filters\catapult_rest_pre_echo_response', 10, 3 );

/**
 * Fetch public post types.
 *
 * @param array $request    Request object.
 */
function filter_post_types( $request ) {
	$public_post_types = get_post_types( array( 'public' => true ), 'objects' );
	$types_to_remove   = array( 'attachment', 'library_block' );

	$post_type_objects = array_filter(
		$public_post_types,
		function ( $key ) use ( $types_to_remove ) {
			return ! in_array( $key, $types_to_remove, true );
		},
		ARRAY_FILTER_USE_KEY
	);

	$request = array_map(
		function ( $post_type ) {
			return array(
				'slug' => $post_type->name,
				'name' => $post_type->label,
			);
		},
		$post_type_objects
	);

	$request[] = array(
		'slug' => 'attachment',
		'name' => 'Media',
	);

	return $request;
}

/**
 * Register custom REST endpoints.
 */
function catapult_register_filter_endpoints() {
	register_rest_route(
		'catapult/v1',
		'/filter-post-types',
		array(
			'methods'             => 'GET',
			'callback'            => 'Catapult\Filters\filter_post_types',
			'permission_callback' => '__return_true',
		)
	);
}
add_filter( 'rest_api_init', 'Catapult\Filters\catapult_register_filter_endpoints' );

/**
 * Add custom REST fields
 */
function add_custom_rest_fields() {
	$args = array(
		'public' => true,
	);

	$post_types = get_post_types( $args, 'names' );

	if ( ! empty( $post_types ) ) {
		$post_types_with_cards = array_filter(
			$post_types,
			function ( $post_type ) {
				return ! in_array( $post_type, array( 'page', 'library_block' ), true );
			}
		);

		if ( ! empty( $post_types_with_cards ) ) {
			register_rest_field(
				$post_types_with_cards,
				'card',
				array(
					'get_callback' => 'Catapult\Filters\render_card',
				)
			);
		}
	}
}
add_action( 'rest_api_init', 'Catapult\Filters\add_custom_rest_fields' );

/**
 * Modify the post query for the filter blocks.

 * @param object $query    WordPress query object.
 */
function pre_get_posts( $query ) {
	if ( is_admin() || empty( $query->query ) || empty( $query->query['post_type'] ) ) {
		return;
	}

	if ( $query->is_main_query() && is_string( $query->query['post_type'] ) ) {
		$args = array(
			'post_type'      => 'theme_block',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'display_location',
					'value'   => '"' . $query->query['post_type'] . '_archive"',
					'compare' => 'LIKE',
				),
			),
		);

		$theme_block_posts = get_posts( $args );

		if ( ! empty( $theme_block_posts ) ) {
			foreach ( $theme_block_posts as $theme_block_post ) {
				if ( empty( $theme_block_post->post_content ) ) {
					continue;
				}

				preg_match( '/(?<="posts_per_page":")[0-9]*(?=")/m', $theme_block_post->post_content, $matches );

				if ( ! empty( $matches ) ) {
					$query->set( 'posts_per_page', intval( $matches[0] ) );
				}
			}
		}
	}

	if ( ! empty( $query->query['catapult_filters'] ) ) {
		$query->query      = catapult_modify_filter_block_args( $query->query );
		$query->query_vars = wp_parse_args( $query->query, $query->query_vars );
	}
}
add_action( 'pre_get_posts', 'Catapult\Filters\pre_get_posts' );

/**
 * Modify the REST API query for attachment/media posts.
 *
 * @param array $args The query parameters array.
 * @param array $request The REST request.
 */
function rest_attachment_query( $args, $request ) {
	if ( $request->has_param( 'catapult_filters' ) ) {
		$args['catapult_filters'] = true;
	}

	if ( $request->has_param( 'media_type' ) ) {
		$args['media_type'] = sanitize_text_field( $request->get_param( 'media_type' ) );
	}

	return $args;
}
add_filter( 'rest_attachment_query', 'Catapult\Filters\rest_attachment_query', 11, 2 );

/**
 * Load the card component file.
 *
 * @param array $obj       Post array.
 */
function render_card( $obj ) {
	if ( empty( $obj['id'] ) ) {
		return;
	}

	$card_post = get_post( $obj['id'] );
	$card_type = file_exists( get_template_directory() . '/blocks/components/' . $card_post->post_type . '-card/' . $card_post->post_type . '-card.php' ) ? $card_post->post_type : 'post';

	if ( empty( $card_post ) || empty( $card_post->post_type ) ) {
		return;
	}

	ob_start();
	catapult_get_component( $card_type . '-card', array( 'post_object' => $card_post ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
