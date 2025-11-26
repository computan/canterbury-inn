<?php
/**
 * Filter-Media-Types
 *
 * Title:             Filter-Media-Types
 * Description:       Select filter to select media types.
 * Category:          filters
 * Icon:              editor-kitchensink
 * Keywords:          filters, filter, select, media, attachment
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:
 * Styles:
 * Context:           acf/filter-top, acf/filter-side, catapult/filter-post-type
 * Ancestor:          acf/filters
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.14
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

?>

<div class="filter-container">
	<div data-type="select" data-taxonomy="media_type" data-default-value="<?php esc_html_e( 'All Media Types', 'catapult' ); ?>" class="filter-select filter">
		<button type="button" class="filter-select__current"><?php esc_html_e( 'All Media Types', 'catapult' ); ?></button>

		<div class="filter-select__dropdown">
			<button type="button" data-value="" class="filter-select__option filter-select__all"><?php esc_html_e( 'All Media Types', 'catapult' ); ?></button>

			<button type="button" class="filter-select__option" data-value="image"><?php esc_html_e( 'Images', 'catapult' ); ?></button>

			<button type="button" class="filter-select__option" data-value="video"><?php esc_html_e( 'Videos', 'catapult' ); ?></button>
		</div>
	</div>
</div>
