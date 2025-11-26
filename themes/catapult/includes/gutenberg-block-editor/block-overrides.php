<?php
/**
 * Override default block settings.
 *
 * @package Catapult
 * @since   3.0.0
 */

namespace Catapult\BlockOverrides;

/**
 * Override default block settings.
 *
 * @param array $metadata Metadata for registering a block type.
 *
 * @return array
 */
function block_type_metadata( $metadata ) {
	if ( empty( $metadata['name'] ) ) {
		return $metadata;
	}

	if ( 'core/button' === $metadata['name'] ) {
		$metadata['supports']['typography'] = array();
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'Catapult\BlockOverrides\block_type_metadata' );
