<?php
/**
 * Register theme post types
 *
 * Post types should always support revisions.
 * Please follow the same format for registering new post types.
 *
 * Reference: https://developer.wordpress.org/reference/functions/register_post_type/
 * Dashicons for menu_icon: https://developer.wordpress.org/resource/dashicons/
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.17
 * @since   3.1.0
 */

namespace Catapult\PostTypes;

/**
 * Register all post types set in the settings.json file.
 */
function register_post_types() {
	// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
	$post_types = get_theme_setting( 'post_types' );

	if ( empty( $post_types ) ) {
		return;
	}

	foreach ( $post_types as $post_type_slug => $post_type_args ) {
		$post_type_args = wp_parse_args(
			$post_type_args,
			array(
				'singular'   => $post_type_slug,
				'plural'     => $post_type_slug,
				'taxonomies' => array(),
				'args'       => array(),
			)
		);

		$post_type_args['args'] = wp_parse_args(
			$post_type_args['args'],
			array(
				'supports'      => array( 'title', 'revisions', 'author', 'editor', 'excerpt', 'thumbnail' ),
				'taxonomies'    => array_keys( $post_type_args['taxonomies'] ),
				'public'        => true,
				'has_archive'   => true,
				'show_in_rest'  => true,
				'menu_position' => 5,
				'rewrite'       => array(),
			)
		);

		$post_type_args['args']['rewrite'] = wp_parse_args(
			$post_type_args['args']['rewrite'],
			array(
				'with_front' => false,
			)
		);

		$args = array_merge(
			array(
				'label'  => __( $post_type_args['plural'], 'catapult' ),
				'labels' => get_labels(
					$post_type_args['singular'],
					$post_type_args['plural']
				),
			),
			$post_type_args['args']
		);

		register_post_type( $post_type_slug, $args );
		// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText
	}
}
add_action( 'init', 'Catapult\PostTypes\register_post_types' );

/**
 * Get post type labels
 *
 * @param  string $singular  Singular name for the post type.
 * @param  string $plural    Plural name for the post type.
 * @param  string $menu_name Name the post type will appear as in the admin sidebar.
 * @return array             Lables for registering a post type.
 */
function get_labels( string $singular, string $plural = '', string $menu_name = '' ): array {
	if ( empty( $plural ) ) {
		$plural = $singular . 's';
	}

	if ( empty( $menu_name ) ) {
		$menu_name = $plural;
	}

	// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText
	return array(
		'name'                  => _x( $plural, 'Post Type General Name', 'catapult' ),
		'singular_name'         => _x( $singular, 'Post Type Singular Name', 'catapult' ),
		'menu_name'             => __( $menu_name, 'catapult' ),
		'name_admin_bar'        => __( $singular, 'catapult' ),
		'archives'              => __( $singular . ' Archives', 'catapult' ),
		'attributes'            => __( $singular . ' Attributes', 'catapult' ),
		'parent_item_colon'     => __( 'Parent ' . $singular, 'catapult' ),
		'all_items'             => __( 'All ' . $plural, 'catapult' ),
		'add_new_item'          => __( 'Add New ' . $singular, 'catapult' ),
		'add_new'               => __( 'Add New', 'catapult' ),
		'new_item'              => __( 'New ' . $singular, 'catapult' ),
		'edit_item'             => __( 'Edit ' . $singular, 'catapult' ),
		'update_item'           => __( 'Update ' . $singular, 'catapult' ),
		'view_item'             => __( 'View ' . $singular, 'catapult' ),
		'view_items'            => __( 'View ' . $plural, 'catapult' ),
		'search_items'          => __( 'Search ' . $singular, 'catapult' ),
		'not_found'             => __( 'Not found', 'catapult' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'catapult' ),
		'featured_image'        => __( 'Featured Image', 'catapult' ),
		'set_featured_image'    => __( 'Set featured image', 'catapult' ),
		'remove_featured_image' => __( 'Remove featured image', 'catapult' ),
		'use_featured_image'    => __( 'Use as featured image', 'catapult' ),
		'insert_into_item'      => __( 'Insert into ' . strtolower( $singular ), 'catapult' ),
		'uploaded_to_this_item' => __( 'Uploaded to this ' . strtolower( $singular ), 'catapult' ),
		'items_list'            => __( $plural . ' list', 'catapult' ),
		'items_list_navigation' => __( $plural . ' list navigation', 'catapult' ),
		'filter_items_list'     => __( 'Filter ' . strtolower( $plural ) . ' list', 'catapult' ),
	);
	// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText
}

// NOTICE: do not register post types here unless a high level of customization is needed. Instead use the settings.json file. If you register post types here, you must also manually create theme block locations for them (the settings.json file will do this automatically for you).
