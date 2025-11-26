<?php
/**
 * Filters
 *
 * Title:             Filters
 * Description:       Container for filters
 * Category:          filters
 * Icon:              editor-kitchensink
 * Keywords:          filters, filter
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Styles:
 * Context:           catapult/filter-post-type, catapult/show-search, catapult/show-sort
 * Parent:            acf/filter-top, acf/filter-side
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.3
 * @since   3.0.4
 * @since   3.0.5
 * @since   3.0.7
 * @since   3.0.10
 * @since   3.0.14
 * @since   3.0.17
 * @since   3.0.18
 * @since   3.0.19
 * @since   3.1.0
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$filters_post_type = $block['filterPostType'] ?? '';
$filter_position   = ( ! empty( $context['filter-top'] ) ) ? 'top' : 'side';
$mobile_style      = $context[ 'filter-' . $filter_position ]['mobile_style'] ?? '';
$show_search       = $block['showSearch'] ?? false;
$show_sort         = $block['showSort'] ?? false;
$selected_count    = 0;

if ( is_tax() || is_category() ) {
	$selected_count = 1;
}

$allowed_blocks = array( 'acf/filter-tabs', 'acf/filter-select', 'acf/filter-multi-select', 'acf/filter-media-types' );

$template = array(
	array( 'acf/filter-tabs' ),
);

?>

<div class="filters-container filters-<?php echo esc_html( $filter_position ); ?>">
	<?php if ( 'modal' === $mobile_style ) : ?>
		<button class="filters-container__modal-button c-btn c-btn--primary" data-selected-count="<?php echo esc_html( $selected_count ); ?>"><?php echo esc_html( __( 'Filter & Sort', 'catapult' ) ); ?></button>
	<?php endif; ?>

	<div class="filters-container__modal">
		<?php if ( 'modal' === $mobile_style ) : ?>
			<div class="filters-container__modal-header">
				<p><?php echo esc_html( sprintf( __( 'Filter %s', 'catapult' ), ucfirst( $filters_post_type ) ) ); ?></p>

				<button class="filters-container__modal-close"></button>
			</div>
		<?php endif; ?>
		
		<div class="filters-container__inner">
			<?php if ( $show_search ) : ?>
				<form class="filter-container filter-search">
					<input class="filter-search__input" type="text" value="" placeholder="<?php echo esc_html( sprintf( __( 'Search %s', 'catapult' ), $filters_post_type ) ); ?>" />

					<button class="filter-search__button" type="submit"><span class="sr-only"><?php esc_html_e( 'Search', 'catapult' ); ?></span><span class="icon icon-search"></span></button>

					<button class="filter-search__clear" type="button"><span class="sr-only"><?php esc_html_e( 'Clear search', 'catapult' ); ?></span><span class="icon icon-close"></span></button>
				</form>
			<?php endif; ?>

			<InnerBlocks template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />

			<?php
			if ( 'modal' === $mobile_style && ! empty( $show_sort ) ) {
				catapult_get_component( 'filter-sort' );
			}
			?>
		</div>

		<?php if ( 'modal' === $mobile_style ) : ?>
			<button class="filters-container__modal-show-results c-btn c-btn--primary"><?php echo esc_html( __( 'Show My Results', 'catapult' ) ); ?></button>
		<?php endif; ?>
	</div>
</div>
