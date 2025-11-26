<?php
/**
 * Filter-Top
 *
 * Title:             Filter-Top
 * Description:       Post type archive with filters located at the top of the block.
 * Category:          filters
 * Icon:              editor-kitchensink
 * Keywords:          filters, filter, archive, post, card
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          filters, filter-sort, filter-pagination, filter-no-results, lightbox, video
 * JS Deps:           lightbox
 * Global ACF Fields: scroll_id
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Styles:
 * Context:
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.1
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.7
 * @since   3.0.10
 * @since   3.0.14
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.18
 * @since   3.0.19
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.5
 * @since   3.1.6
 * @since   3.1.7
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$json_settings = array();

$paged_query_var = get_query_var( 'paged' );
$queried_object  = get_queried_object();
$is_archive      = is_archive();
$is_prefiltered  = false;

if ( $queried_object instanceof WP_Term ) {
	$current_taxonomy  = $queried_object->taxonomy ?? null;
	$current_term_id   = $queried_object->term_id ?? null;
	$current_term_name = $queried_object->slug ?? null;
	$is_prefiltered    = true;

	$taxonomy_object = get_taxonomy( $current_taxonomy );
	if ( ! empty( $taxonomy_object ) ) {
		$current_post_type = $taxonomy_object->object_type[0];
	}

	$json_settings['taxonomy'] = $current_taxonomy;
	$json_settings['termID']   = $current_term_id;
	$json_settings['termName'] = ucfirst( $current_term_name );
} elseif ( $queried_object instanceof WP_Post_Type ) {
	$current_post_type = $queried_object->name;
}

$filters_post_type  = $block['filterPostType'] ?? $current_post_type ?? 'post';
$posts_per_page     = get_field( 'posts_per_page' );
$mobile_style       = get_field( 'mobile_style' );
$load_type          = get_field( 'load_type' ) ?? 'pagination';
$card_heading_level = get_field( 'card_heading_level' ) ?? 'h3';
$show_results_count = $block['showResultsCount'] ?? false;
$show_sort          = $block['showSort'] ?? false;
$current_page       = ( 0 !== $paged_query_var ) ? $paged_query_var : 1;
$max_num_pages      = 1;
$card_type          = file_exists( get_template_directory() . '/blocks/components/' . $filters_post_type . '-card/' . $filters_post_type . '-card.php' ) ? $filters_post_type : 'post';

$args = array(
	'post_type'      => $filters_post_type,
	'posts_per_page' => $posts_per_page,
);

if ( $current_page ) {
	$args['paged'] = $current_page;
}

// Pre-filter taxonomy.
if ( ! empty( $current_taxonomy ) ) {
	$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => $current_taxonomy,
			'field'    => 'slug',
			'terms'    => $current_term_name,
		),
	);
}

$args = catapult_modify_filter_block_args( $args );

$initial_posts = new WP_Query( $args );

$json_settings['postType']     = $filters_post_type;
$json_settings['loadType']     = $load_type;
$json_settings['postsPerPage'] = $posts_per_page;
$json_settings['totalPosts']   = $initial_posts->found_posts;
$json_settings['maxPages']     = $initial_posts->max_num_pages;
$json_settings['currentPage']  = $current_page;
$json_settings['isArchive']    = $is_archive;

$max_num_pages = intval( $initial_posts->max_num_pages );

$allowed_blocks = array( 'acf/filters' );

$template = array();

// Enqueue card stylesheet based on post type.
if ( ! empty( $card_type ) ) {
	$card_stylesheet_handle = "catapult-component-{$card_type}-card";
	wp_enqueue_style( $card_stylesheet_handle );
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?>class="acf-block block-filter-top<?php echo esc_attr( $content_block->get_block_classes() ); ?>" data-json-settings="<?php echo esc_attr( wp_json_encode( $json_settings ) ); ?>" data-mobile-style="<?php echo esc_attr( $mobile_style ); ?>" data-card-type="<?php echo esc_attr( $card_type ); ?>">
	<div class="container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="false" />

		<?php if ( ! empty( $content ) || is_admin() ) : ?>
			<div class="block-filter-top__selected-filter-sort-container">
				<div class="block-filter-top__selected-filters-wrapper">
					<?php if ( ! empty( $show_results_count ) ) : ?>
						<div role="alert" class="block-filter-top__result-count filter-result-count"><?php echo esc_attr( sprintf( __( '%s Results', 'catapult' ), $initial_posts->found_posts ) ); ?></div>
					<?php endif; ?>
					
					<div class="block-filter-top__selected-filters-container">
						<div class="block-filter-top__selected-filters selected-filters"></div>

						<button class="block-filter-top__clear-filters clear-filters" <?php echo ! $is_prefiltered ? 'disabled' : ''; ?>><?php esc_html_e( 'Clear All', 'catapult' ); ?></button>
					</div>
				</div>

				<?php
				if ( ! empty( $show_sort ) ) {
					catapult_get_component( 'filter-sort' );
				}
				?>
			</div>
		<?php endif; ?>

		<div id="block-filter-top__posts" class="block-filter-top__posts<?php echo esc_attr( $args['post_wrapper_classes'] ); ?>">
			<div class="block-filter__posts-container">
				<?php if ( ! empty( $initial_posts->posts ) ) : ?>
					<?php
					foreach ( $initial_posts->posts as $post_object ) {
						$card_name = $card_type . '-card';
						catapult_get_component(
							$card_name,
							array(
								'post_object'        => $post_object,
								'card_heading_level' => $card_heading_level,
							)
						);
					}
					?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		catapult_get_component(
			'filter-pagination',
			array(
				'max_num_pages' => $max_num_pages,
				'current_page'  => $current_page,
				'load_type'     => $load_type,
			)
		);
		?>
	</div>
</section>
