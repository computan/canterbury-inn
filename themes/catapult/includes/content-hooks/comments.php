<?php
/**
 * Functions used to turn off comments sitewide.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   3.0.0
 */

namespace Catapult\Comments;

/**
 * Redirect any user trying to access comments page.
 */
function redirect_comments_page() {
	global $pagenow;

	if ( 'edit-comments.php' === $pagenow || 'options-discussion.php' === $pagenow ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'admin_init', 'Catapult\Comments\redirect_comments_page' );

/**
 * Remove comments metabox from dashboard.
 */
function remove_dashboard_comments_metabox() {
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'Catapult\Comments\remove_dashboard_comments_metabox' );

/**
 * Disable support for comments and trackbacks in post types.
 */
function disable_comments_support() {
	foreach ( get_post_types() as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}
add_action( 'admin_init', 'Catapult\Comments\disable_comments_support' );

/**
 * Close comments on the front-end.
 *
 * @param bool $open      Whether the current post is open for comments.
 * @param int  $post_id   The post ID.
 * @return bool             False to close comments.
 */
function close_comments_front_end( $open, $post_id ) {
	return false;
}
add_filter( 'comments_open', 'Catapult\Comments\close_comments_front_end', 20, 2 );
add_filter( 'pings_open', 'Catapult\Comments\close_comments_front_end', 20, 2 );

/**
 * Hide existing comments.
 *
 * @param array $comments   An array of comments.
 * @param int   $post_id    The post ID.
 * @return array             An empty array.
 */
function hide_existing_comments( $comments, $post_id ) {
	return array();
}
add_filter( 'comments_array', 'Catapult\Comments\hide_existing_comments', 10, 2 );

/**
 * Remove comments page in menu.
 */
function remove_comments_page_menu() {
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action( 'admin_menu', 'Catapult\Comments\remove_comments_page_menu' );

/**
 * Remove comments links from admin bar.
 */
function remove_comments_links_admin_bar() {
	if ( is_admin_bar_showing() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}
}
add_action( 'init', 'Catapult\Comments\remove_comments_links_admin_bar' );

/**
 * Remove comments icon from admin bar.
 */
function remove_comments_icon_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'Catapult\Comments\remove_comments_icon_admin_bar' );

/**
 * Return a comment count of zero to hide existing comment entry link.
 *
 * @param int $count  The comment count.
 * @return int        Zero to hide the comment count.
 */
function zero_comment_count( $count ) {
	return 0;
}
add_filter( 'get_comments_number', 'Catapult\Comments\zero_comment_count' );

/**
 * Multisite - Remove manage comments from admin bar.
 *
 * @param object $bar  The admin bar object.
 */
function remove_manage_comments_multisite_admin_bar( $bar ) {
	$sites = get_blogs_of_user( get_current_user_id() );
	foreach ( $sites as $site ) {
		$bar->remove_node( "blog-{$site->userblog_id}-c" );
	}
}
add_action( 'admin_bar_menu', 'Catapult\Comments\remove_manage_comments_multisite_admin_bar', PHP_INT_MAX - 1 );
