<?php
/**
 * Return array of allowed blocks omitting those disallowed by this function.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Return array of allowed blocks omitting those disallowed by this function.
 *
 * Gets a list of registered ACF blocks and combines that with a list of allowed core blocks.
 *
 * @param bool|string[] $allowed_block_types  Array of block type slugs, or boolean to allow/disallow all.
 *                                            Default true (all registered block types supported).
 *
 * @return bool|string[] Array of block type slugs, or boolean to allow/disallow all.
 */
function catapult_allowed_block_types_all( $allowed_block_types = array() ) {
	if ( ! class_exists( 'WP_Block_Type_Registry' ) ) {
		return $allowed_block_types;
	}

	if ( is_array( $allowed_block_types ) && ! empty( $allowed_block_types ) ) {
		$acf_blocks          = acf_get_block_types();
		$acf_blocks          = array_keys( $acf_blocks );
		$allowed_block_types = array_merge( $acf_blocks, $allowed_block_types );
	} else {
		$block_registry        = WP_Block_Type_Registry::get_instance();
		$all_registered_blocks = $block_registry->get_all_registered();

		if ( ! empty( $all_registered_blocks ) ) {
			$allowed_block_types = array_keys( $all_registered_blocks );
		}
	}

	$allowed_block_types = array_unique( $allowed_block_types );

	$disallowed_core_blocks = array(
		'core/legacy-widget',
		'core/widget-group',
		'core/archives',
		'core/avatar',
		'core/calendar',
		'core/categories',
		'core/columns',
		'core/column',
		'core/comment-author-name',
		'core/comment-content',
		'core/comment-date',
		'core/comment-edit-link',
		'core/comment-reply-link',
		'core/comment-template',
		'core/comments',
		'core/comments-pagination',
		'core/comments-pagination-next',
		'core/comments-pagination-numbers',
		'core/comments-pagination-previous',
		'core/comments-title',
		'core/cover',
		'core/details',
		'core/embed',
		'core/file',
		'core/footnotes',
		'core/gallery',
		'core/home-link',
		'core/latest-comments',
		'core/latest-posts',
		'core/loginout',
		'core/navigation',
		'core/navigation-link',
		'core/navigation-submenu',
		'core/page-list',
		'core/page-list-item',
		'core/pattern',
		'core/post-author',
		'core/post-author-name',
		'core/post-author-biography',
		'core/post-comments-form',
		'core/post-content',
		'core/post-date',
		'core/post-excerpt',
		'core/post-featured-image',
		'core/post-navigation-link',
		'core/post-template',
		'core/post-terms',
		'core/query',
		'core/query-no-results',
		'core/query-pagination',
		'core/query-pagination-next',
		'core/query-pagination-numbers',
		'core/query-pagination-previous',
		'core/query-title',
		'core/read-more',
		'core/rss',
		'core/search',
		'core/site-logo',
		'core/site-tagline',
		'core/site-title',
		'core/social-link',
		'core/tag-cloud',
		'core/template-part',
		'core/term-description',
		'core/audio',
		'core/code',
		'core/freeform',
		'core/group',
		'core/media-text',
		'core/missing',
		'core/more',
		'core/nextpage',
		'core/preformatted',
		'core/pullquote',
		'core/social-links',
		'core/text-columns',
		'core/verse',
		'core/video',
		'core/post-comments',
	);

	$allowed_block_types = array_values( array_diff( $allowed_block_types, $disallowed_core_blocks ) );

	return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'catapult_allowed_block_types_all', 10, 2 );

/**
 * Gets a list of the basic text blocks.
 *
 * @param  array $additional_blocks Additional blocks to include.
 * @return array The list of blocks.
 */
function catapult_text_blocks( $additional_blocks = array() ) {
	if ( ! is_array( $additional_blocks ) ) {
		$additional_blocks = array( $additional_blocks );
	}

	return array_merge(
		array(
			'acf/accordion',
			'acf/columns',
			'acf/content',
			'acf/contact-item',
			'acf/form',
			'acf/hero-details',
			'acf/icon',
			'acf/list-items',
			'acf/share-buttons',
			'acf/tabs',
			'core/button',
			'core/buttons',
			'core/heading',
			'core/html',
			'core/image',
			'core/list',
			'core/paragraph',
			'core/quote',
			'core/separator',
			'core/shortcode',
			'core/table',
		),
		$additional_blocks
	);
}
