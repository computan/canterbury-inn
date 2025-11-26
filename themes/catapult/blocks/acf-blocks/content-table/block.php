<?php
/**
 * Content-Table
 *
 * Title:             Content-Table
 * Description:       A block with content and a table
 * Category:          Content
 * Icon:              align-wide
 * Keywords:          content, table
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_color
 * InnerBlocks:       true
 * Default BG Color:  white
 * Starts With Text:  true
 * Styles:
 *
 * @package Catapult
 * @since   2.2.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => 'Add heading here.',
			'fontSize'    => 't2',
			'textAlign'   => 'center',
		),
	),
	array(
		'core/table',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?>class="acf-block block-content-table<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-content-table__container" />
</section>
