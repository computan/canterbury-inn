<?php
/**
 * Functions for use with Gutenberg Blocks.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

/**
 * Generate array with all blocks including nested block patterns.
 *
 * @param array $blocks  Array of the parsed blocks.
 */
function catapult_parse_block_patterns( $blocks ) {
	$sorted_blocks = array();

	if ( ! empty( $blocks ) ) {
		foreach ( $blocks as $key => $block ) {
			$reusable_blocks = array();

			if ( ! empty( $block['blockName'] ) ) {
				if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) {
					$content = get_post_field( 'post_content', $block['attrs']['ref'] );

					if ( ! empty( $content ) ) {
						$reusable_blocks = parse_blocks( $content );

						if ( ! empty( $reusable_blocks ) ) {
							$reusable_blocks = catapult_parse_block_patterns( $reusable_blocks );
							$sorted_blocks   = array_merge( $sorted_blocks, $reusable_blocks );
						}
					}
				} else {
					$sorted_blocks[] = $block;
				}
			}
		}
	}

	return $sorted_blocks;
}

/**
 * Generate array with all blocks including nested innerBlocks.
 *
 * @param array $blocks  Array of the parsed blocks.
 */
function catapult_parse_inner_blocks( $blocks ) {
	$sorted_blocks = array();

	$blocks = catapult_parse_block_patterns( $blocks );

	if ( ! empty( $blocks ) ) {
		foreach ( $blocks as $key => $block ) {
			$sorted_blocks[] = $block;

			if ( ! empty( $block['innerBlocks'] ) ) {
				$innerblocks   = catapult_parse_inner_blocks( $block['innerBlocks'] );
				$sorted_blocks = array_merge( $sorted_blocks, catapult_parse_block_patterns( $innerblocks ) );
			}
		}
	}

	return $sorted_blocks;
}
