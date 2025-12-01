<?php
/**
 * The elastic search partial.
 *
 * @category Template
 * @package Catapult
 * @since   3.1.2
 */

$search_query = get_search_query();
$result       = wpes_search(
	array(
		'per_page' => 1,
	)
);
$current_page = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

get_template_part(
	'parts/search/hero',
	null,
	array(
		'search_query' => $search_query,
		'found_posts'  => $result->found_posts,
	)
);
?>

<main class="content container">
	<div class="row">
		<div class="col-12 col-md-10 mx-auto">
			<div class="search-results">
				<?php
				foreach ( $result->records as $record ) {
					get_template_part(
						'parts/search/result',
						null,
						array(
							'subtitle' => ucfirst( $record['_source']['type'] ),
							'img'      => wp_get_attachment_image( $record['_source']['image_id'], 'thumbnail' ),
							'title'    => wpes_maybe_get_highlighted_field( 'title', $record ),
							'content'  => wpes_get_excerpt( $record ),
							'url'      => $record['_source']['url'],
						)
					);
				}
				?>
			</div>

			<nav class="search-pagination">
				<?php
				if ( $result->max_num_pages > 1 ) {
					get_template_part(
						'parts/global/pagination',
						null,
						array(
							'current_page'  => $current_page,
							'max_num_pages' => $result->max_num_pages,
						)
					);
				}
				?>
			</nav>
		</div>
	</div>
</main>
