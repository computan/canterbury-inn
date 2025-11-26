<?php
/**
 * Register theme taxonomies
 *
 * Please follow the same format for registering new taxonomies.
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_taxonomy/
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.14
 * @since   3.1.0
 */

namespace Catapult\Taxonomies;

/**
 * Register all taxonomies set in the settings.json file.
 */
function register_taxonomies() {
	// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
	$post_types = get_theme_setting( 'post_types' );

	if ( empty( $post_types ) ) {
		return;
	}

	foreach ( $post_types as $post_type_slug => $post_type_args ) {
		if ( empty( $post_type_args['taxonomies'] ) ) {
			continue;
		}

		foreach ( $post_type_args['taxonomies'] as $taxonomy_slug => $taxonomy_args ) {
			$taxonomy_args = wp_parse_args(
				$taxonomy_args,
				array(
					'singular' => $taxonomy_slug,
					'plural'   => $taxonomy_slug,
					'args'     => array(),
				)
			);

			$taxonomy_args['args'] = wp_parse_args(
				$taxonomy_args['args'],
				array(
					'labels'            => get_labels(
						$taxonomy_args['singular'],
						$taxonomy_args['plural']
					),
					'hierarchical'      => true,
					'public'            => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'show_in_rest'      => true,
					'rewrite'           => array(),
				)
			);

			$taxonomy_args['args']['rewrite'] = wp_parse_args(
				$taxonomy_args['args']['rewrite'],
				array(
					'with_front' => false,
				)
			);

			$args = array_merge(
				array(
					'label'  => __( $taxonomy_args['plural'], 'catapult' ),
					'labels' => get_labels(
						$taxonomy_args['singular'],
						$taxonomy_args['plural']
					),
				),
				$taxonomy_args['args']
			);

			register_taxonomy( $taxonomy_slug, array( $post_type_slug ), $args );
		}
	}
	// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText
}
add_action( 'init', 'Catapult\Taxonomies\register_taxonomies' );

/**
 * Get taxonomy labels
 *
 * @param  string $singular  Singular name for the taxonomy.
 * @param  string $plural    Plural name for the taxonomy.
 * @param  string $menu_name Name the taxonomy will appear as in the admin sidebar.
 * @return array             Lables for registering a taxonomy.
 */
function get_labels( string $singular, string $plural = '', string $menu_name = '' ): array {
	// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
	if ( empty( $plural ) ) {
		$plural = $singular . 's';
	}

	if ( empty( $menu_name ) ) {
		$menu_name = $plural;
	}

	return array(
		'name'                       => _x( $plural, 'Taxonomy General Name', 'catapult' ),
		'singular_name'              => _x( $singular, 'Taxonomy Singular Name', 'catapult' ),
		'menu_name'                  => __( $menu_name, 'catapult' ),
		'all_items'                  => __( 'All ' . $plural, 'catapult' ),
		'parent_item'                => __( 'Parent ' . $singular, 'catapult' ),
		'parent_item_colon'          => __( 'Parent ' . $singular . ':', 'catapult' ),
		'new_item_name'              => __( 'New ' . $singular . ' Name', 'catapult' ),
		'add_new_item'               => __( 'Add New ' . $singular, 'catapult' ),
		'edit_item'                  => __( 'Edit ' . $singular, 'catapult' ),
		'update_item'                => __( 'Update ' . $singular, 'catapult' ),
		'view_item'                  => __( 'View ' . $singular, 'catapult' ),
		'separate_items_with_commas' => __( 'Separate ' . strtolower( $plural ) . ' with commas', 'catapult' ),
		'add_or_remove_items'        => __( 'Add or remove ' . strtolower( $plural ), 'catapult' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'catapult' ),
		'popular_items'              => __( 'Popular ' . $plural, 'catapult' ),
		'search_items'               => __( 'Search ' . $plural, 'catapult' ),
		'not_found'                  => __( 'Not Found', 'catapult' ),
		'no_terms'                   => __( 'No ' . strtolower( $plural ), 'catapult' ),
		'items_list'                 => __( $plural . ' list', 'catapult' ),
		'items_list_navigation'      => __( $plural . ' list navigation', 'catapult' ),
	);
	// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText
}

/**
 * Register media gallery category taxonomy.
 */
function media_gallery_category() {
	$args = array(
		'labels'                => get_labels( 'Media Gallery Category', 'Media Gallery Categories' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'show_in_rest'          => true,
		'update_count_callback' => '_update_generic_term_count',
	);

	register_taxonomy( 'media_gallery_category', array( 'attachment' ), $args );
}
add_action( 'init', 'Catapult\Taxonomies\media_gallery_category' );

// NOTICE: do not register taxonomies here unless a high level of customization is needed. Instead use the settings.json file. If you register taxonomies here, you must also manually create theme block locations for them (the settings.json file will do this automatically for you).
