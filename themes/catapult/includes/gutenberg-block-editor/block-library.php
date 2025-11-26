<?php
/**
 * Functions and hooks for the block library.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.2
 * @since   3.0.12
 */

namespace Catapult\BlockLibrary;

/**
 * Register Block Library post type.
 */
function library_block() {
	$labels = array(
		'name'                  => __( 'Block Library', 'catapult' ),
		'singular_name'         => __( 'Library Block', 'catapult' ),
		'menu_name'             => __( 'Block Library', 'catapult' ),
		'name_admin_bar'        => __( 'Block Library', 'catapult' ),
		'add_new'               => __( 'Add New', 'catapult' ),
		'add_new_item'          => __( 'Add New Library Block', 'catapult' ),
		'new_item'              => __( 'New Library Block', 'catapult' ),
		'edit_item'             => __( 'Edit Library Block', 'catapult' ),
		'view_item'             => __( 'View Library Block', 'catapult' ),
		'all_items'             => __( 'All Library Blocks', 'catapult' ),
		'search_items'          => __( 'Search Block Library', 'catapult' ),
		'parent_item_colon'     => __( 'Parent Block Library:', 'catapult' ),
		'not_found'             => __( 'No library blocks found.', 'catapult' ),
		'not_found_in_trash'    => __( 'No library blocks found in Trash.', 'catapult' ),
		'featured_image'        => __( 'Library Block Cover Image', 'catapult' ),
		'archives'              => __( 'Block library archives', 'catapult' ),
		'insert_into_item'      => __( 'Insert into library block', 'catapult' ),
		'uploaded_to_this_item' => __( 'Uploaded to this library block', 'catapult' ),
		'filter_items_list'     => __( 'Filter block library list', 'catapult' ),
		'items_list_navigation' => __( 'Block library list navigation', 'catapult' ),
		'items_list'            => __( 'Block library list', 'catapult' ),
	);

	register_post_type(
		'library_block',
		array(
			'label'               => __( 'Block Library', 'catapult' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'revisions', 'editor' ),
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-block-default',
			'has_archive'         => true,
			'show_in_rest'        => true,
			'rewrite'             => array(
				'with_front' => false,
				'slug'       => 'block-library',
			),
		)
	);
}
add_action( 'init', 'Catapult\BlockLibrary\library_block' );

/**
 * Add inactive blocks column to block library admin dashboard page.
 *
 * @param string[] $post_columns An associative array of column headings.
 */
function add_inactive_blocks_admin_column( $post_columns ) {
	$post_columns['inactive_blocks'] = __( 'Inactive Blocks', 'catapult' );

	return $post_columns;
}
add_filter( 'manage_library_block_posts_columns', 'Catapult\BlockLibrary\add_inactive_blocks_admin_column' );

/**
 * Add inactive block values to library block admin dashboard posts.
 *
 * @param string $column_name The name of the column to display.
 * @param int    $post_id    The current post ID.
 */
function add_active_block_column_values( $column_name, $post_id ) {
	if ( 'inactive_blocks' !== $column_name ) {
		return;
	}

	$has_inactive_blocks = get_post_meta( $post_id, 'has_inactive_blocks', true );

	if ( $has_inactive_blocks ) {
		esc_html_e( 'Yes', 'catapult' );
	}
}
add_action( 'manage_library_block_posts_custom_column', 'Catapult\BlockLibrary\add_active_block_column_values', 10, 2 );

/**
 * Add sortable column functionality to the inactive blocks column.
 *
 * @param array $sortable_columns An array of sortable columns.
 */
function sortable_columns( $sortable_columns ) {
	$sortable_columns['inactive_blocks'] = 'inactive_blocks';

	return $sortable_columns;
}
add_filter( 'manage_edit-library_block_sortable_columns', 'Catapult\BlockLibrary\sortable_columns' );

/**
 * Exclude library blocks from sitemap.
 *
 * @param bool   $exclude   Default false.
 * @param string $post_type Post type name.
 */
function exclude_from_sitemap( $exclude, $post_type ) {
	if ( 'library_block' === $post_type ) {
		return true;
	}

	return $exclude;
}
add_filter( 'wpseo_sitemap_exclude_post_type', 'Catapult\BlockLibrary\exclude_from_sitemap', 10, 2 );

/**
 * Set the has_inactive_blocks meta field for block library posts that contain inactive blocks.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function set_library_block_post_active_status( $post_id, $post, $update ) {
	if ( empty( $post->post_content ) ) {
		return;
	}

	$block_types = acf_get_block_types();

	if ( empty( $block_types ) ) {
		return;
	}

	preg_match_all( '/ wp:(acf\/[A-Za-z0-9-_]*)/m', $post->post_content, $matches );

	if ( empty( $matches ) ) {
		return;
	}

	$has_inactive_blocks = false;

	foreach ( $matches[1] as $post_block_name ) {
		if ( ! isset( $block_types[ $post_block_name ] ) ) {
			$has_inactive_blocks = true;
			break;
		}

		if ( ! isset( $block_types[ $post_block_name ]['active'] ) ) {
			continue;
		}

		if ( 'false' === $block_types[ $post_block_name ]['active'] || false === $block_types[ $post_block_name ]['active'] ) {
			$has_inactive_blocks = true;
			break;
		}
	}

	update_post_meta( $post_id, 'has_inactive_blocks', $has_inactive_blocks );
}
add_action( 'save_post_library_block', 'Catapult\BlockLibrary\set_library_block_post_active_status', 10, 3 );

/**
 * Hide inactive blocks from the dashboard on environments other than local and development, sort them on local environments.

 * @param object $query    WordPress query object.
 */
function hide_or_sort_inactive_blocks( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'library_block' !== $query->get( 'post_type' ) ) {
		return;
	}

	if ( in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) ) {
		$orderby = $query->get( 'orderby' );

		if ( 'inactive_blocks' === $orderby ) {
			$query->set( 'meta_key', 'has_inactive_blocks' );
			$query->set( 'orderby', 'meta_value' );
		}
	} else {
		global $pagenow;

		$meta_query = $query->get( 'meta_query' );

		if ( empty( $meta_query ) ) {
			$meta_query = array();
		}

		$meta_query[] = array(
			array(
				'key'     => 'has_inactive_blocks',
				'value'   => true,
				'compare' => '!=',
			),
		);

		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'Catapult\BlockLibrary\hide_or_sort_inactive_blocks', 10, 2 );

/**
 * Modify the number of posts listed to exclude inactive blocks.
 *
 * @param stdClass $counts An object containing the current post_type's post counts by status.
 * @param string   $type   Post type.
 * @param string   $perm   The permission to determine if the posts are 'readable' by the current user.
 */
function hide_inactive_blocks_from_post_count( $counts, $type, $perm ) {
	if ( in_array( wp_get_environment_type(), array( 'local', 'development' ), true ) ) {
		return $counts;
	}

	if ( is_admin() && 'library_block' === $type ) {
		$args = array(
			'post_type'      => 'library_block',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'has_inactive_blocks',
					'value'   => true,
					'compare' => '!=',
				),
			),
		);

		$block_library_ids = get_posts( $args );

		if ( ! empty( $block_library_ids ) ) {
			$counts->publish = count( $block_library_ids );
		}
	}

	return $counts;
}
add_filter( 'wp_count_posts', 'Catapult\BlockLibrary\hide_inactive_blocks_from_post_count', 10, 3 );

/**
 * Create custom post type links pointing back to the archive.
 *
 * @param string  $post_link The post's permalink.
 * @param WP_Post $post      The post in question.
 * @param bool    $leavename Whether to keep the post name.
 * @param bool    $sample    Is it a sample permalink.
 */
function post_type_link( $post_link, $post, $leavename, $sample ) {
	if ( ! is_a( $post, 'WP_Post' ) ) {
		return $permalink;
	}

	if ( 'library_block' === $post->post_type ) {
		$post_link = get_post_type_archive_link( 'library_block' );
		$content   = get_the_content( null, false, $post->ID );

		if ( ! empty( $content ) ) {
			$post_link = add_query_arg( $post->ID, 'v', $post_link );
		}
	}

	return $post_link;
}
add_filter( 'post_type_link', 'Catapult\BlockLibrary\post_type_link', 10, 4 );

/**
 * Redirect single library blocks to block library archive.
 */
function redirect_single_library_blocks() {
	if ( is_singular( 'library_block' ) ) {
		$post_link    = get_post_type_archive_link( 'library_block' );
		$qa_query_var = get_query_var( 'qa' );

		if ( ! empty( $qa_query_var ) ) {
			$post_link = add_query_arg(
				array(
					'qa' => $qa_query_var,
				),
				$post_link
			);
		}

		wp_safe_redirect( $post_link );
		exit;
	}
}
add_action( 'wp', 'Catapult\BlockLibrary\redirect_single_library_blocks', 10, 4 );

/**
 * For the block library, ignore the order set by the post type order plugin so they order alphabetically.
 *
 * @param bool     $ignore Whether to ignore the plugin order.
 * @param string   $order_by      The orderby parameter.
 * @param WP_Query $query  The query object.
 */
function ignore_post_type_order( $ignore, $order_by, $query ) {
	if ( catapult_is_block_library() ) {
		$ignore = true;
	}

	return $ignore;
}
add_filter( 'pto/posts_orderby/ignore', 'Catapult\BlockLibrary\ignore_post_type_order', 10, 3 );

/**
 * On the block library page, use default image for any images that are missing.
 *
 * @param array|false  $image         {
 *     Array of image data, or boolean false if no image is available.
 *
 *     @type string $0 Image source URL.
 *     @type int    $1 Image width in pixels.
 *     @type int    $2 Image height in pixels.
 *     @type bool   $3 Whether the image is a resized image.
 * }
 * @param int          $attachment_id Image attachment ID.
 * @param string|int[] $size          Requested image size. Can be any registered image size name, or
 *                                    an array of width and height values in pixels (in that order).
 * @param bool         $icon          Whether the image should be treated as an icon.
 */
function use_default_image_if_missing( $image, $attachment_id, $size, $icon ) {
	if ( is_array( $attachment_id ) ) {
		return $image;
	}

	if ( catapult_is_block_library() || false !== strpos( $attachment_id, 'placeholder' ) ) {
		if ( 'logo-placeholder' === $attachment_id ) {
			$image = array(
				get_stylesheet_directory_uri() . '/images/block-library/logo-placeholder.svg',
				192,
				96,
				false,
			);
		} elseif ( 'logo-placeholder-no-padding' === $attachment_id ) {
			$image = array(
				get_stylesheet_directory_uri() . '/images/block-library/logo-placeholder-no-padding.svg',
				80,
				24,
				false,
			);
		} elseif ( 'site-logo-placeholder' === $attachment_id ) {
			$image = array(
				get_stylesheet_directory_uri() . '/images/block-library/site-logo-placeholder.svg',
				80,
				24,
				false,
			);
		} elseif ( 'site-logo-placeholder-white' === $attachment_id ) {
			$image = array(
				get_stylesheet_directory_uri() . '/images/block-library/site-logo-placeholder-white.svg',
				80,
				24,
				false,
			);
		} elseif ( empty( $image ) || false !== strpos( $attachment_id, 'placeholder' ) ) {
			$image_subsizes = wp_get_registered_image_subsizes();
			$width          = 640;
			$height         = 640;

			if ( ! empty( $size ) && is_string( $size ) && ! empty( $image_subsizes ) && is_array( $image_subsizes ) && ! empty( $image_subsizes[ $size ] ) ) {
				if ( ! empty( ! empty( $image_subsizes[ $size ]['width'] ) ) && false === strpos( $image_subsizes[ $size ]['width'], '999' ) ) {
					$width = $image_subsizes[ $size ]['width'];
				}
			}

			if ( file_exists( get_stylesheet_directory() . '/images/block-library/' . $attachment_id . '.png' ) ) {
				$placeholder_url = get_stylesheet_directory_uri() . '/images/block-library/' . $attachment_id . '.png';
			} else {
				$placeholder_url = get_stylesheet_directory_uri() . '/images/block-library/placeholder-16-9.png';
			}

			if ( $width === $height ) {
				$placeholder_url = get_stylesheet_directory_uri() . '/images/block-library/placeholder-1-1.png';
			}

			$image = array(
				$placeholder_url,
				$width,
				$height,
				false,
			);
		} else {
			$image_path = wp_get_original_image_path( $attachment_id );

			if ( false === $image_path || ! file_exists( $image_path ) ) {
				$placeholder_url = get_stylesheet_directory_uri() . '/images/block-library/placeholder-16-9.png';

				if ( false !== strpos( $image[0], 'default-image-logo' ) ) {
					$placeholder_url = get_stylesheet_directory_uri() . '/images/block-library/logo-placeholder.svg';
				}

				$image[0] = $placeholder_url;
			}
		}
	}

	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'Catapult\BlockLibrary\use_default_image_if_missing', 10, 4 );

/**
 * Set aspect ratio for a block library placeholder image if a ratio is specified for the block library.
 *
 * @param string[]     $attr       Array of attribute values for the image markup, keyed by attribute name.
 *                                 See wp_get_attachment_image().
 * @param WP_Post      $attachment Image attachment post.
 * @param string|int[] $size       Requested image size. Can be any registered image size name, or
 *                                 an array of width and height values in pixels (in that order).
 */
function set_block_library_image_ratio( $attr, $attachment, $size ) {
	if ( empty( $attachment ) || empty( $attachment->ID ) ) {
		if ( catapult_is_block_library() && ! empty( $attr['block_library_placeholder_aspect_ratio'] ) ) {
			if ( empty( $attr['style'] ) ) {
				$attr['style'] = '';
			}

			$attr['style'] .= 'aspect-ratio: ' . $attr['block_library_placeholder_aspect_ratio'] . '; object-fit: cover;';
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'Catapult\BlockLibrary\set_block_library_image_ratio', 9999, 3 );

/**
 * Filter rendered block output to add placeholder image on block library page.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 */
function render_block( $block_content, $block ) {
	global $wp_query;

	if ( catapult_is_block_library() ) {
		if ( 'core/image' === $block['blockName'] && ! empty( $block['attrs'] ) && ! empty( $block['attrs']['id'] ) ) {
			$image_path = wp_get_original_image_path( $block['attrs']['id'] );

			if ( false === $image_path || ! file_exists( $image_path ) ) {
				$block_content = preg_replace( '/(?<=src=").*?(?=")/m', get_stylesheet_directory_uri() . '/images/block-library/placeholder.png', $block_content );
			}
		}
	}

	return $block_content;
}
add_filter( 'render_block', 'Catapult\BlockLibrary\render_block', 10, 2 );

/**
 * Retrieves the post thumbnail ID.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 * @return int|false Post thumbnail ID (which can be 0 if the thumbnail is not set),
 *                   or false if the post does not exist.
 */
function add_default_placeholder_image( $post ) {
	if ( catapult_is_block_library() && empty( $post ) ) {
		$post = 1;
	}

	return $post;
}
add_filter( 'post_thumbnail_id', 'Catapult\BlockLibrary\add_default_placeholder_image', 10, 2 );

/**
 * Modify the document body classes.
 *
 * @param array $classes       An array of classes to add to the body element.
 */
function body_class( $classes ) {
	if ( ! is_post_type_archive( 'library_block' ) ) {
		return $classes;
	}

	$classes[] = 'qa-active';

	if ( ! empty( $_GET['hide-hover-labels'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'hide-hover-labels';
	}

	if ( ! empty( $_GET['show-overlays'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'show-overlays';
	}

	if ( ! empty( $_GET['show-qa-overlays'] ) || ! empty( $_GET['qa'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'show-qa-overlays';
	}

	if ( ! empty( $_GET['hide-colors'] ) || 1 === count( $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'hide-colors';
	}

	if ( ! empty( $_GET['hide-buttons'] ) || 1 === count( $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'hide-buttons';
	}

	if ( ! empty( $_GET['hide-forms'] ) || 1 === count( $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'hide-forms';
	}

	if ( ! empty( $_GET['simple-mode'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$classes[] = 'simple-mode';
	}

	return $classes;
}
add_filter( 'body_class', 'Catapult\BlockLibrary\body_class' );

/**
 * Limit the metadata that gets exported for block library posts.
 *
 * @param bool   $skip       Whether or not to skip this meta object from being exported.
 * @param string $meta_key   Current meta key.
 * @param object $meta       Current meta object.
 *
 * @return bool
 */
function limit_block_library_export_meta( $skip, $meta_key, $meta ) {
	if ( empty( $meta->meta_key ) || empty( $meta->post_id ) || 'library_block' !== get_post_type( $meta->post_id ) ) {
		return $skip;
	}

	if ( false !== strpos( $meta->meta_key, 'wpseo' ) ) {
		$skip = true;
	}

	return $skip;
}
add_filter( 'wxr_export_skip_postmeta', 'Catapult\BlockLibrary\limit_block_library_export_meta', 10, 3 );

/**
 * Add custom rewrite rules for block URLs.
 *
 * @param array $rules Array of rewrite rules.
 */
function rewrite_rules( $rules ) {
	$new_rules = array(
		'block-library/qa/(.*)/?$' => 'index.php?library_block=$matches[1]&qa=$matches[1]',
	);

	return array_merge( $new_rules, $rules );
}
add_filter( 'rewrite_rules_array', 'Catapult\BlockLibrary\rewrite_rules' );

/**
 * Add custom query vars for the block library QA pages.
 *
 * @param array $query_vars Array of query vars.
 */
function custom_query_vars( $query_vars ) {
	$query_vars[] = 'qa';

	return $query_vars;
}
add_filter( 'query_vars', 'Catapult\BlockLibrary\custom_query_vars' );

/**
 * Change the page title for block QA pages.
 *
 * @param string $title The document title.
 */
function wp_title( $title ) {
	if ( ! empty( get_query_var( 'qa' ) ) ) {
		$block_library_post_object = get_page_by_path( get_query_var( 'qa' ), OBJECT, 'library_block' );

		if ( ! empty( $block_library_post_object ) && ! empty( $block_library_post_object->post_title ) ) {
			$title = 'Block: ' . get_page_by_path( get_query_var( 'qa' ), OBJECT, 'library_block' )->post_title;
		}
	}

	return $title;
}
add_filter( 'wpseo_title', 'Catapult\BlockLibrary\wp_title' );
