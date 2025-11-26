<?php
/**
 * Filter-Multi-Select
 *
 * Title:             Filter-Multi-Select
 * Description:       Select multiple filters
 * Category:          filters
 * Icon:              editor-kitchensink
 * Keywords:          filters, filter, select, multi-select
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
 * @since   3.0.1
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.7
 * @since   3.0.10
 * @since   3.0.17
 * @since   3.0.18
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$queried_object = get_queried_object();

$current_term_name     = null;
$current_term_taxonomy = null;

if ( $queried_object instanceof WP_Term ) {
	$current_term_name     = $queried_object->name;
	$current_term_taxonomy = $queried_object->taxonomy;
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
		<div data-type="multi-select" data-taxonomy="<?php echo esc_attr( $filter_taxonomy ); ?>" data-default-value="<?php echo esc_html( $filter_label ); ?>" class="filter-multi-select filter">
			<button type="button" class="filter-multi-select__current">
				<?php echo esc_html( $filter_label ); ?>
			</button>
			
			<div class="filter-multi-select__dropdown">
				<?php foreach ( $filter_terms as $filter_term ) : ?>
					<?php
					$name = $filter_term->name;

					if ( 'Uncategorized' === $name ) {
						continue;
					}

					$is_checked = $name === $current_term_name;
					?>

					<div class="filter-multi-select__checkbox-wrapper">
						<input <?php echo $is_checked ? 'checked' : ''; ?> type="checkbox" name="<?php echo esc_attr( $filter_taxonomy ); ?>" id="<?php echo esc_attr( $filter_taxonomy . '_' . strtolower( $name ) ); ?>" class="filter-multi-select__checkbox" data-value="<?php echo esc_attr( $filter_term->term_id ); ?>" data-name="<?php echo esc_html( $name ); ?>">

						<label for="<?php echo esc_attr( $filter_taxonomy . '_' . strtolower( $name ) ); ?>"><?php echo esc_html( $name ); ?></label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
