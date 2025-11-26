<?php
/**
 * Logos
 *
 * Title:             Logos
 * Description:       Inner block row of logos.
 * Instructions:
 * Category:          Logo
 * Icon:              screenoptions
 * Keywords:          logo, logos, brands, partners
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Styles:
 * Parent:            acf/logo-standard, acf/logo-strip
 * Global ACF Fields:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/logo' );

$template = array(
	array(
		'acf/logo',
	),
);

$logos_per_row_mobile  = get_field( 'logos_per_row_mobile' );
$logos_per_row_tablet  = get_field( 'logos_per_row_tablet' );
$logos_per_row_desktop = get_field( 'logos_per_row_desktop' );

if ( empty( $logos_per_row_mobile ) ) {
	$logos_per_row_mobile = 2;
}

if ( empty( $logos_per_row_tablet ) ) {
	$logos_per_row_tablet = 3;
}

if ( empty( $logos_per_row_desktop ) ) {
	$logos_per_row_desktop = 6;
}

$block_style = '--logoWidthMobile: ' . 100 / $logos_per_row_mobile . '%; --logoWidthTablet: ' . 100 / $logos_per_row_tablet . '%; --logoWidthDesktop: ' . 100 / $logos_per_row_desktop . '%;';

?>

<div class="block-logos row" style="<?php echo esc_html( $block_style ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
</div>
