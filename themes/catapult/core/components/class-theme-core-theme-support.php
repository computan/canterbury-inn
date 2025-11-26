<?php
/**
 * Theme Support.
 *
 * This component registers theme support for WP features.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Class used to define this themes support of WP functionality.
 */
class Theme_Core_Theme_Support extends Theme_Core_Component {

	/**
	 * Kicks off this class' functionality.
	 */
	protected function init() {
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'title-tag' );
		add_theme_support( 'widgets' ); // We don't use widgets, but this is required in order to avoid a PHP error in the Customizer.
		remove_theme_support( 'core-block-patterns' );
	}
}
