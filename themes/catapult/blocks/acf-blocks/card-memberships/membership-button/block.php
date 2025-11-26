<?php
/**
 * Membership Button
 *
 * Title:             Membership Button
 * Description:       Block with a text price and button side-by-side.
 * Instructions:
 * Category:          Core
 * Icon:              button
 * Keywords:          button. price, fee, join, links, memberships, plans, trial
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/paragraph', 'core/button' );

$template = array(
	array(
		'core/paragraph',
		array(
			'content' => __( 'Monthly Fee', 'catapult' ) . '<strong>' . __( '$XX-$XX', 'catapult' ) . '</strong>',
		),
	),
	array(
		'core/button',
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-membership-button" />
