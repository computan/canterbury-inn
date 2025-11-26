<?php
/**
 * Configuration for this theme.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

return array(

	/*
	 * Theme settings file
	 * ---------------------------------------------------------------------------------------
	 *
	 * The name of the settings file relative to the theme root directory. This setting
	 * can be used to place the settings file in the subdirectory in order to keep
	 * the theme root clean.
	 *
	 */
	'settings_file' => 'settings.json',

	/*
	 * Theme Core components
	 * ---------------------------------------------------------------------------------------
	 *
	 * Theme Core is built with components, each of which adds its own piece of functionality,
	 * and can be used independently of others. Here the components can be commented out
	 * if they are not needed, or new components can be registered, after adding their classes
	 * to the 'components' directory.
	 *
	 */
	'components'    => array(
		'Theme_Core_Theme_Support',
		'Theme_Core_ACF',
		'Theme_Core_Icons',
		'Theme_Core_Blocks',
		'Theme_Core_React_Blocks',
		'Theme_Core_Admin_Columns',
		'Theme_Core_Navigation',
		'Theme_Core_Scripts',
		'Theme_Core_Thumbnails',
		'Theme_Core_Theme_Blocks',
		'Theme_Core_Gated_Content',
	),

	/*
	 * Load the includes
	 * ---------------------------------------------------------------------------------------
	 *
	 * Load all the additional files that the theme is going to be using. There are three
	 * available load methods:
	 *  - include - Just include all the files in a given directory.
	 *  - PSR-4   - Use PSR-4 autoloader.
	 *  - WP      - Use WP standards compatible autolaoder.
	 *
	 */
	'includes'      => array(
		'loader'         => 'include',
		'directory'      => 'includes',
		'namespace_root' => 'Theme\\',
	),

);
