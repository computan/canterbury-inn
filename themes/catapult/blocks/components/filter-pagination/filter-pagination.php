<?php
/**
 * Filter block pagination component.
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.3
 * @since   3.0.10
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.1
 * @since   3.1.2
 */

?>

<?php if ( is_admin() || ( ! empty( $max_num_pages ) && ! empty( $current_page ) && ! empty( $load_type ) && isset( $_SERVER['REQUEST_URI'] ) ) ) : ?>
	<?php
	$block_classes = ( $current_page === $max_num_pages || 1 === $max_num_pages ) ? ' disabled' : '';

	if ( is_admin() ) {
		$block_classes .= ' is-admin';
	}
	?>

	<div class="block-filter-pagination<?php echo esc_attr( $block_classes ); ?>">
		<?php if ( 'load_more' === $load_type ) : ?>
			<button class="c-btn c-btn--secondary block-filter-pagination__load-more load-more" <?php echo ( $current_page === $max_num_pages || is_admin() ) ? 'disabled' : ''; ?>>Load More</button>
		<?php elseif ( 'pagination' === $load_type ) : ?>
			<?php
			if ( is_admin() ) {
				$current_page  = 1;
				$max_num_pages = 5;
			}
			$current_url      = home_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			$current_url      = preg_replace( '/\/page\/\d+/', '', $current_url );
			$pagination_links = paginate_links(
				array(
					'base'      => $current_url . '%_%',
					'type'      => 'plain',
					'total'     => $max_num_pages,
					'current'   => $current_page,
					'prev_text' => '<span class="sr-only">' . __( 'Previous page', 'catapult' ) . '</span>',
					'next_text' => '<span class="sr-only">' . __( 'Next page', 'catapult' ) . '</span>',
					'show_all'  => false,
					'end_size'  => 1,
					'mid_size'  => 2,
				)
			);
			?>

			<nav class="pagination" aria-label="<?php esc_html_e( 'Pagination', 'catapult' ); ?>">
				<?php if ( ! empty( $pagination_links ) ) : ?>
					<?php echo wp_kses_post( $pagination_links ); ?>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
	</div>
<?php endif; ?>
