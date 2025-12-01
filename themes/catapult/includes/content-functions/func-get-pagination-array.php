<?php
/**
 * Pagination array generation functions.
 *
 * @package Catapult
 * @since   3.1.2
 */

/**
 * Get pagination array for displaying page numbers with ellipses.
 *
 * @since 3.1.2
 *
 * @param int $current_page  Current page number.
 * @param int $max_num_pages Total number of pages.
 * @return array Array of page numbers and ellipses ('...').
 */
function catapult_get_pagination_array( $current_page, $max_num_pages ) {
	$pagination_range = array();

	// Always show first page.
	$pagination_range[] = 1;

	// Calculate range around current page.
	$range_start = max( 2, $current_page - 1 );
	$range_end   = min( $max_num_pages - 1, $current_page + 1 );

	// Add ellipsis after first page if needed.
	if ( $range_start > 2 ) {
		$pagination_range[] = '...';
	}

	// Add pages around current page.
	for ( $i = $range_start; $i <= $range_end; $i++ ) {
		$pagination_range[] = $i;
	}

	// Add ellipsis before last page if needed.
	if ( $range_end < $max_num_pages - 1 ) {
		$pagination_range[] = '...';
	}

	// Always show last page if more than one page.
	if ( $max_num_pages > 1 ) {
		$pagination_range[] = $max_num_pages;
	}

	return $pagination_range;
}
