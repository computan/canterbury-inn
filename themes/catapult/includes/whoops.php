<?php
/**
 * Enable Whoops error reporting
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   3.0.0
 */

// Enable error handling via Whoops if not CLI.
if ( defined( 'ENABLE_WHOOPS' ) && ENABLE_WHOOPS === true && 'local' === wp_get_environment_type() && class_exists( '\Whoops\Run' ) ) {
	if ( php_sapi_name() !== 'cli' ) {
		$whoops = new \Whoops\Run();
		$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
		$whoops->register();
	}
}
