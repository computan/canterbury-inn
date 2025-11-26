<?php
/**
 * Text-Checklist
 *
 * Title:             Text-Checklist
 * Description:       Block with columns of checklist items.
 * Instructions:
 * Category:          Text
 * Icon:              text
 * Keywords:          text, checklist, info, list, items
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:  true
 * CSS Custom Props:  column_count: 3
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$column_count = get_field( 'column_count' );

if ( empty( $column_count ) ) {
	$column_count = '3';
}

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'core/list',
		array(
			'className' => 'is-style-check-icon',
		),
	),
	array(
		'core/buttons',
		array(),
		array(
			array(
				'core/button',
				array(
					'className' => 'is-style-secondary',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-text-checklist block-text-checklist--<?php echo esc_attr( $column_count ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container" />
</section>
