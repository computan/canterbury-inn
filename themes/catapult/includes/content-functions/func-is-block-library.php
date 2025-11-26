<?php
/**
 * Determine whether currently viewing or editing the block library.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Determine whether currently viewing or editing the block library.
 *
 * @return boolean  Whether or not the current page/screen is a library block.
 */
function catapult_is_block_library() {
	global $wp_query;

	// phpcs:disable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
	if ( is_post_type_archive( 'library_block' ) ) {
		return true;
	} elseif (
		is_admin() &&
		(
			(
				! empty( $_GET['action'] ) &&
				! empty( $_GET['post'] ) &&
				'edit' === sanitize_text_field(
					wp_unslash( $_GET['action'] )
				)
			) ||
			(
				wp_doing_ajax() &&
				! empty( $_POST['post_id'] )
			)
		)
	) {
		if ( wp_doing_ajax() ) {
			$post_id = intval( wp_unslash( $_POST['post_id'] ) );
		} else {
			$post_id = intval( wp_unslash( $_GET['post'] ) );
		}

		if ( ! empty( $post_id ) && 'library_block' === get_post_type( $post_id ) ) {
			return true;
		}
	} elseif ( ! empty( $_SERVER['REQUEST_URI'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'block-library' ) ) {
		return true;
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended, WordPress.Security.NonceVerification.Missing
}
