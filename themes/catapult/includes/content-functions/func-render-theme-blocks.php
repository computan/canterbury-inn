<?php
/**
 * Display theme blocks assigned to a specific location.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Display theme blocks assigned to a specific location.
 *
 * @param string $display_location                 Load blocks assigned to this location.
 * @param bool   $return_output                    Whether or not to echo the blocks or return them.
 *
 * Make sure to update the load_global_blocks() function in the catapult/includes/core/components/class-theme-core-blocks.php file so that the blocks get loaded into the global $blocks variable (otherwise the CSS/JS won't load).
 */
function catapult_render_theme_blocks( $display_location, $return_output = false ) {
	$args = array(
		'post_type'      => 'theme_block',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => 'display_location',
				'value'   => '"' . $display_location . '"',
				'compare' => 'LIKE',
			),
		),
	);

	$theme_block_posts = get_posts( $args );

	if ( empty( $theme_block_posts ) && catapult_is_block_library() ) {
		unset( $args['meta_query'] );
		$theme_block_default_title = substr_replace( $display_location, ' - ', strrpos( $display_location, '_' ), 1 );

		$theme_block_default_title = str_replace( '_', ' ', $theme_block_default_title );

		$theme_block_default_title = ucwords( $theme_block_default_title );

		$args['post_name__in'] = array( $theme_block_default_title );

		$theme_block_posts = get_posts( $args );
	}

	if ( ! empty( $theme_block_posts ) ) {
		$content = '';

		foreach ( $theme_block_posts as $theme_block_post ) {
			if ( ! empty( $theme_block_post->post_content ) ) {
				$content .= $theme_block_post->post_content;
			}
		}
	}

	if ( ! empty( $content ) ) {
		$html = apply_filters( 'the_content', $content );

		// This code is from /plugins/advanced-custom-fields-pro/pro/blocks.php and is used to prevent nested innerblocks from rendering as editable blocks.
		if ( is_admin() ) {
			$content = str_replace( '$', '\$', $content );
			$matches = array();

			if ( preg_match( '/<InnerBlocks(?:[^<]+?)(?:class|className)=(?:["\']\W+\s*(?:\w+)\()?["\']([^\'"]+)[\'"]/', $html, $matches ) ) {
				$class = isset( $matches[1] ) ? $matches[1] : 'acf-innerblocks-container';
			} else {
				$class = 'acf-innerblocks-container';
			}

			$content = '<div class="' . $class . '">' . $content . '</div>';
			$html    = preg_replace( '/<InnerBlocks([\S\s]*?)\/>/', $content, $html );
		}

		if ( true === $return_output ) {
			return $html;
		} else {
			echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
