<?php
/**
 * Restrict access to the `library_block` archive page.
 *
 * Behavior:
 * - If the constant `HIDE_BLOCK_LIBRARY` is defined and set to true:
 *   - Only users with the **Administrator** role can access the `library_block` archive.
 *   - Non-admin users (logged-in or logged-out) will see a 404 page.
 * - If the constant is not defined or set to false:
 *   - No restrictions are applied, and the archive is accessible to everyone.
 *
 * Hook: template_redirect
 *
 * @return void
 */
function hide_block_library() {
    // Bail early if constant not defined or false
    if ( ! defined( 'HIDE_BLOCK_LIBRARY' ) || HIDE_BLOCK_LIBRARY !== true ) {
        return;
    }

    // Only restrict on the `library_block` post type archive
    if ( is_post_type_archive( 'library_block' ) && ! current_user_can( 'administrator' ) ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        nocache_headers();

        // Load 404 template
        include get_query_template( '404' );
        exit;
    }
}
add_action( 'template_redirect', 'hide_block_library' );
