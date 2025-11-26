<?php
/**
 * Card-Membership
 *
 * Title:             Card-Membership
 * Description:       Card block for use within parent Cards block.
 * Instructions:
 * Category:          Card
 * Icon:              screenoptions
 * Keywords:          cards, card, links, memberships, plans, trial
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Ancestor:          acf/card-memberships
 * InnerBlocks:       true
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/list', 'core/button', 'acf/membership-button' );

$template = array(
	array(
		'acf/content',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
		array(
			array(
				'core/heading',
				array(
					'level'    => 3,
					'content'  => __( 'Membership  Plan', 'catapult' ),
					'fontSize' => 't4',
				),
			),
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
				),
			),
			array(
				'core/heading',
				array(
					'level'    => 4,
					'content'  => __( 'Includes', 'catapult' ),
					'fontSize' => 't5',
				),
			),
			array(
				'core/list',
				array(
					'className' => 'is-style-check-icon',
				),
			),
		),
	),
	array(
		'acf/membership-button',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-card-membership bg-white" />
