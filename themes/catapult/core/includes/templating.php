<?php
/**
 * Theme building functions.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

/**
 * Retrieve part of the template.
 * Uses template engine build into theme to grab the file (relative to "parts" directory),
 * and pass variables to this files local scope.
 *
 * @param string $part   The theme template file (without .php extension).
 * @param array  $data   Data to be passed to the template. Variables will be made of the array's keys.
 * @param string $folder The folder the partial is located in.
 */
function get_theme_part( $part, $data = array(), $folder = 'parts' ) {
	$engine = Theme_Template_Engine::create( $folder );
	$engine->render( $part, $data );
}

/**
 * Retrieve a component.
 * Uses template engine build into theme to grab the file (relative to "blocks/components" directory), and pass variables to this files local scope. The primary component PHP file should match the name of its directory, such as "blocks/components/post-card/post-card.php".
 *
 * @param string $component_name   The name of the component to load.
 * @param array  $data             Data to be passed to the template. Variables will be made of the array's keys.
 * @param string $folder           The folder the partial is located in.
 */
function catapult_get_component( $component_name, $data = array(), $folder = 'blocks/components' ) {
	if ( false === strpos( $component_name, '/' ) ) {
		$component_name = $component_name . '/' . $component_name;
	}

	$engine = Theme_Template_Engine::create( $folder );
	$engine->render( $component_name, $data );
}

/**
 * Begin generating a theme part layout.
 *
 * @param string $layout Template name containing a layout.
 * @param array  $data   Data to pass to the layout file.
 * @param string $folder The folder the partial is located in.
 */
function start_layout( $layout, $data = array(), $folder = 'parts' ) {
	$engine = Theme_Template_Engine::create( $folder );
	$engine->start_layout( $layout, $data );
}

/**
 * Begin outputting to the next layout part.
 *
 * @param string $folder The folder the partial is located in.
 */
function next_layout_part( $folder = 'parts' ) {
	$engine = Theme_Template_Engine::create( $folder );
	$engine->next_layout_part();
}

/**
 * Finalize (print) generated layout.
 *
 * @param string $folder The folder the partial is located in.
 */
function close_layout( $folder = 'parts' ) {
	$engine = Theme_Template_Engine::create( $folder );
	$engine->close_layout();
}
