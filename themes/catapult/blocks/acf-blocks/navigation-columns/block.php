<?php
/**
 * Navigation-Columns
 *
 * Title:             Navigation-Columns
 * Description:       A megamenu dropdown with multiple flexible columns of links.
 * Instructions:
 * Category:          Navigation
 * Icon:              editor-table
 * Keywords:          nav, navigation, links, header
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Ancestor:          catapult/navigation-submenu
 *
 * @package Catapult
 * @since   3.0.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/navigation-link-column' );

$template = array(
	array(
		'acf/navigation-link-column',
	),
);

$block_classes      = '';
$link_column_count  = 0;
$total_column_count = 0;

if ( ! empty( $content ) ) {
	preg_match_all( '/"block-navigation-(link|featured-link)-column"/m', $content, $matches );
	preg_match_all( '/"navigation-featured-link-card"/m', $content, $card_matches );

	if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
		if ( '"block-navigation-featured-link-column"' === $matches[0][0] ) {
			$block_classes .= ' block-navigation-columns--has-first-link-column';
		}

		if ( '"block-navigation-featured-link-column"' === $matches[0][ count( $matches[0] ) - 1 ] ) {
			$block_classes .= ' block-navigation-columns--has-last-link-column';
		}

		foreach ( $matches[0] as $match ) {
			++$total_column_count;

			if ( '"block-navigation-link-column"' === $match ) {
				++$link_column_count;
			}
		}

		if ( $link_column_count > 3 ) {
			$block_classes .= ' block-navigation-columns--narrow-link-columns';
		}
	}

	if ( ! empty( $card_matches ) && ! empty( $card_matches[0] ) && count( $card_matches[0] ) > 1 ) {
		$block_classes .= ' block-navigation-columns--multiple-featured-link-cards';
	}
}
?>

<div class="block-navigation-columns<?php echo esc_attr( $block_classes ); ?>" style="--linkColumnCount: <?php echo esc_attr( $link_column_count ); ?>; --totalColumnCount: <?php echo esc_attr( $total_column_count ); ?>;">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-navigation-columns__content" />
</div>
