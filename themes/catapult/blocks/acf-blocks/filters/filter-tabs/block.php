<?php
/**
 * Filter-Tabs
 *
 * Title:             Filter-Tabs
 * Description:       Tab filters
 * Category:          filters
 * Icon:              editor-kitchensink
 * Keywords:          filters, filter, button group
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
 * @since   3.0.0
 * @since   3.0.1
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.7
 * @since   3.0.10
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$queried_object = get_queried_object();

$current_term_id   = null;
$current_term_name = null;

if ( $queried_object instanceof WP_Term ) {
	$current_term_id   = $queried_object->term_id;
	$current_term_name = $queried_object->name;
}

$filter_taxonomy = $block['filterTaxonomy'] ?? '';
$filter_label    = $block['filterTaxonomyLabel'] ?? '';

if ( ! empty( $filter_taxonomy ) ) {
	$filter_terms = get_terms(
		array(
			'taxonomy'   => $filter_taxonomy,
			'hide_empty' => true,
		)
	);
}
?>

<?php if ( isset( $filter_terms ) && ! is_wp_error( $filter_terms ) && ! empty( $filter_terms ) ) : ?>
	<div class="filter-container">
		<div data-type="tabs" data-taxonomy="<?php echo esc_attr( $filter_taxonomy ); ?>" data-default-value="<?php echo esc_html( $filter_label ); ?>" class="filter-tabs filter">
			<button class="filter-tabs__select"><?php esc_html_e( 'Browse by:', 'catapult' ); ?> <span class="filter-tabs__select-selected"><?php echo esc_html( ( $current_term_name ) ? $current_term_name : $filter_label ); ?></span>
			</button>
			
			<div class="filter-tabs__container">
				<button type="button" value="" data-taxonomy="<?php echo esc_html( $filter_taxonomy ); ?>" class="filter-tabs__tab <?php echo ( ! $current_term_id ) ? 'selected' : ''; ?>"><?php echo esc_html( $filter_label ); ?></button>

				<?php foreach ( $filter_terms as $filter_term ) : ?>
					<?php
					if ( 'Uncategorized' === $filter_term->name ) {
						continue;
					}
					?>

					<button type="button" value="<?php echo esc_html( $filter_term->term_id ); ?>" data-taxonomy="<?php echo esc_html( $filter_taxonomy ); ?>" class="filter-tabs__tab <?php echo ( $current_term_id === $filter_term->term_id ) ? 'selected' : ''; ?>" id="<?php echo esc_html( $filter_taxonomy . '_' . $filter_term->term_id ); ?>">
						<?php echo esc_html( $filter_term->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
