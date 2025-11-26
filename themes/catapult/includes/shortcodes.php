<?php
/**
 * Theme shortcodes
 *
 * Please follow the same format for registering new shortcodes.
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 */

namespace Catapult\Shortcodes;

/**
 * Current year shortcode.
 *
 * @param array $atts The shortcodes attributes.
 */
function current_year( $atts ) {
	return gmdate( 'Y' );
}
add_shortcode( 'current_year', 'Catapult\Shortcodes\current_year' );
