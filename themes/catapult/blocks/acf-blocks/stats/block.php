<?php
/**
 * Stats
 *
 * Title:             Stats
 * Description:       Block with stats and descriptions.
 * Instructions:
 * Category:          Stat
 * Icon:              chart-bar
 * Keywords:          stats, statistics, numbers, data, results
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/stat-strip, acf/stat-simple, acf/stat-supporting, acf/stat-image
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_id = $content_block->get_block_id();

$allowed_blocks = array( 'acf/stat' );

$template = array(
	array(
		'acf/stat',
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-stats row" />
