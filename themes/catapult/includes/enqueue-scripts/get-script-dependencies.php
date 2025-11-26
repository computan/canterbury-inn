<?php
/**
 * Get the dependencies for a script from its asset file.
 *
 * @package Catapult
 * @since   3.0.0
 */

/**
 * Get the dependencies for a script from its asset file.
 *
 * @param string $script_path    The path of the script.
 * @param array  $dependencies   An array of dependencies to include.
 */
function catapult_get_script_dependences( $script_path, $dependencies = array() ) {
	$script_asset_path = str_replace( '.js', '.asset.php', $script_path );

	$script_modules_path = str_replace( '.js', '.modules.php', $script_path );

	if ( file_exists( $script_modules_path ) ) {
		$script_modules = require $script_modules_path;

		if ( ! empty( $script_modules['dependencies'] ) ) {
			$dependencies = array_unique( array_merge( $dependencies, $script_modules['dependencies'] ) );
		}
	}

	if ( file_exists( $script_asset_path ) ) {
		$script_asset = require $script_asset_path;

		if ( ! empty( $script_asset['dependencies'] ) ) {
			$dependencies = array_unique( array_merge( $dependencies, $script_asset['dependencies'] ) );
		}
	}

	return $dependencies;
}
