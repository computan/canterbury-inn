<?php
/**
 * Disallow indexing of your site on non-production environments.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

if ( defined( 'DISALLOW_INDEXING' ) && DISALLOW_INDEXING !== false ) {
	add_action( 'pre_option_blog_public', '__return_zero' );
}
