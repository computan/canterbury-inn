<?php
/**
 * Classic search pagination partial.
 *
 * @package Catapult
 * @since   3.1.2
 */

$args = wp_parse_args(
	$args,
	array(
		'current_page'  => 0,
		'max_num_pages' => 1,
		'base_url'      => get_home_url( null, '/?s=' . rawurlencode( get_search_query() ) ),
		'page_prefix'   => '&paged=',
	)
);

if ( $args['max_num_pages'] > 1 ) :
	$current_page  = (int) $args['current_page'];
	$max_num_pages = (int) $args['max_num_pages'];

	$pagination_range = catapult_get_pagination_array( $current_page, $max_num_pages );

	$prev_url = 2 === $current_page ? $args['base_url'] : $args['base_url'] . $args['page_prefix'] . ( $current_page - 1 );
	$next_url = $args['base_url'] . $args['page_prefix'] . ( $current_page + 1 );
	?>

	<ul class="pagination">

		<?php if ( 1 !== $current_page ) : ?>
			<li class="pagination__item pagination__item--prev">
				<a href="<?php echo esc_url( $prev_url ); ?>" class="pagination__link">
					<span class="icon-chev-left"></span>
					<span class="sr-only">Previous search page</span>
				</a>
			</li>
		<?php endif; ?>

		<?php
		foreach ( $pagination_range as $page_number ) {
			if ( is_numeric( $page_number ) ) {
				$href    = 1 === $page_number ? $args['base_url'] : $args['base_url'] . $args['page_prefix'] . $page_number;
				$current = $page_number === $current_page ? ' pagination__item--current' : '';
				?>

				<li class="pagination__item<?php echo esc_attr( $current ); ?>">
					<a
						class="pagination__item-inner pagination__link"
						href="<?php echo esc_url( $href ); ?>"
						><?php echo esc_html( $page_number ); ?></a
					>
				</li>

				<?php
			} else { // Ellipses.
				?>

				<li class="pagination__item pagination__item--ellipses">
					<span class="pagination__item-inner"><?php echo esc_html( $page_number ); ?></span>
				</li>

				<?php
			}
		}
		?>

		<?php if ( $max_num_pages !== $current_page ) : ?>
			<li class="pagination__item pagination__item--next">
				<a
					href="<?php echo esc_url( $next_url ); ?>"
					class="pagination__link"
				>
					<span class="icon-chev-right"></span>
					<span class="sr-only">Previous search page</span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
	<?php
endif;
