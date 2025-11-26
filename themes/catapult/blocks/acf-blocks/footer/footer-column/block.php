<?php
/**
 * Footer Column
 *
 * Title:             Footer Column
 * Description:       A column block for the Footer-Top section.
 * Instructions:
 * Category:          Core
 * Icon:              info-outline
 * Keywords:          footer, top, column, content
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Parent:            acf/footer-top
 * Image Size:        main-logo
 * Button Styles:     Navigation Link, Primary, Secondary, Tertiary
 * CSS Custom Props:  column_width_mobile: 12, column_width_tablet: 6, column_width_desktop: 3, order_mobile, order_tablet
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'gravityforms/form' ) );

$column_width_mobile  = get_field( 'column_width_mobile' );
$column_width_tablet  = get_field( 'column_width_tablet' );
$column_width_desktop = get_field( 'column_width_desktop' );
$block_classes        = '';

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => 'Add heading here.',
		),
	),
	array(
		'core/button',
		array(
			'className' => 'is-style-navigation-link',
		),
	),
);

if ( ! empty( $spacer_column ) ) {
	$template = array();

	if ( 0 === $column_width_mobile ) {
		$block_classes .= ' block-footer-column--mobile-hidden';
	}

	if ( 0 === $column_width_tablet ) {
		$block_classes .= ' block-footer-column--tablet-hidden';
	}

	if ( 0 === $column_width_desktop ) {
		$block_classes .= ' block-footer-column--desktop-hidden';
	}
}

?>

<div <?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="block-footer-column<?php echo esc_attr( $block_classes ); ?>">
	<?php if ( empty( $spacer_column ) ) : ?>
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-footer-column__content" />
	<?php endif; ?>
</div>
