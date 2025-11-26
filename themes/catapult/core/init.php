<?php
/**
 * Initialize core functionality.
 *
 * This file prepares all the core functionality that is required by the theme
 * to function. It should contain only includes, and Theme_Core class setup.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   3.0.0
 */

if ( 'local' === wp_get_environment_type() && file_exists( dirname( __DIR__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __DIR__ ) . '/vendor/autoload.php';
}

// Give access to all publicly visible functions offered by the core.
require_once 'core-functions.php';
recursive_include( __DIR__ . DIRECTORY_SEPARATOR . 'includes' );

// Load all core components.
require_once 'class-theme-core-component.php';
recursive_include( __DIR__ . DIRECTORY_SEPARATOR . 'components' );

// Load theme core itself.
require_once 'class-theme-core.php';

// Setup the core and initialize components.
Theme_Core::setup();
