<?php
/**
 * The search template
 *
 * @category Template
 * @package  Catapult
 * @author   829 Studios <info@829studios.com>
 * @license  GPL-2.0+ https://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.829studios.com
 * @since    3.1.2
 * @php      8.2
 */

wp_enqueue_style( 'catapult-component-filter-pagination' );

get_header();

if ( function_exists( 'wpes_search' ) ) {
	get_template_part( 'parts/search/elasticsearch' );
} else {
	get_template_part( 'parts/search/classic-search' );
}

get_footer();
