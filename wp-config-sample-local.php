<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost:/ADD/LOCAL/SOCKET/HERE/mysqld.sock' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

/**
 * Environment
 */
if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
	define( 'WP_ENVIRONMENT_TYPE', 'local' );
}

/**
 * Plugin Licenses and API Keys
 */
define( 'ACF_PRO_LICENSE', 'XXXXXXXXX' );
// define( 'BUGHERD_API_KEY', 'XXXXXXXXX' );
define( 'GF_LICENSE_KEY', 'XXXXXXXXX' );
define( 'WP_PLUGIN_GF_KEY', 'XXXXXXXXX' );
define( 'WPMDB_LICENCE', 'XXXXXXXXX' );
// define( 'GOOGLE_API_KEY', 'XXXXXXXXX' );

// define( 'WPOSES_AWS_ACCESS_KEY_ID', 'XXXXXXXXX' );
// define( 'WPOSES_AWS_SECRET_ACCESS_KEY', 'XXXXXXXXX' );
// define( 'WPOSES_LICENCE', 'XXXXXXXXX' );

/**
 * Custom Settings
 */
define( 'ALLOW_INDEXING', false );

if ( ! defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
	define( 'AUTOMATIC_UPDATER_DISABLED', true );
}

if ( ! defined( 'DISABLE_WP_CRON' ) ) {
	define( 'DISABLE_WP_CRON', false );
}

// Disable the plugin and theme file editor in the admin
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', false );
}

// Disable plugin and theme updates and installation from the admin
if ( ! defined( 'DISALLOW_FILE_MODS' ) ) {
	define( 'DISALLOW_FILE_MODS', false );
}

/**
 * Debugging Settings
 */
define( 'ENABLE_WHOOPS', true );

if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}

if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	define( 'WP_DEBUG_DISPLAY', false );
}

if ( ! defined( 'WP_DEBUG_LOG' ) ) {
	define( 'WP_DEBUG_LOG', true );
}

if ( ! defined( 'SAVEQUERIES' ) ) {
	define( 'SAVEQUERIES', false );
}

if ( ! defined( 'SCRIPT_DEBUG' ) ) {
	define( 'SCRIPT_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
