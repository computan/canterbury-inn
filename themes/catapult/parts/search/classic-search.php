<?php
/**
 * The classic search partial.
 *
 * @category Template
 * @package Catapult
 * @since   3.1.2
 */

global $wp_query;

$search_query       = get_search_query();
$current_page       = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
$found_posts        = $wp_query->found_posts;
$empty_search_class = '';

if ( empty( $search_query ) ) {
	$empty_search_class = 'empty-search';
}
?>
<main class="container-wrapper <?php echo esc_attr( $empty_search_class ); ?>">
	<?php
	get_template_part(
		'parts/search/hero',
		null,
		array(
			'search_query' => $search_query,
			'found_posts'  => $found_posts,
		)
	);
	?>

	<div class="search-content content-wrapper">
		<div class="search-content__wrapper">
			<?php if ( empty( $search_query ) ) : ?>
				<div class="search-content__col">
					<div class="empty-search">
						<div class="empty-search__heading">
							<h2>Search cannot be empty!</h2>
						</div>
					</div>
				</div>
			<?php else : ?>
				<div class="search-content__col">
					<?php if ( 0 !== $found_posts ) : ?>
						<?php if ( ! empty( $search_query ) ) : ?>
							<div class="search-results <?php echo ( $wp_query->max_num_pages > 1 ) ? '' : 'no-pagination'; ?>">
								<?php
								while ( have_posts() ) :
									the_post();
									get_template_part(
										'parts/search/result',
										null,
										array(
											'img'       => wp_get_attachment_image( get_post_thumbnail_id(), 'full', '', array( 'alt' => get_the_title() ) ),
											'title'     => get_the_title(),
											'content'   => custom_search_excerpt( get_the_ID() ),
											'url'       => get_permalink(),
											'post_type' => get_post_type_singular_name( get_the_ID() ),
										)
									);
								endwhile;
								?>
							</div>
							<nav class="search-pagination">
								<?php
								if ( $wp_query->max_num_pages > 1 ) {
									get_template_part(
										'parts/global/pagination',
										null,
										array(
											'current_page' => $current_page,
											'max_num_pages' => $wp_query->max_num_pages,
										)
									);
								}
								?>
							</nav>
						<?php endif; ?>
					<?php else : ?>
						<?php
						get_template_part(
							'parts/search/no-search-result',
							null,
							array(
								'found_posts' => $found_posts,
							)
						);
						?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
