<?php
/**
 * The search template
 *
 * @package Catapult
 * @since   1.0.0
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.17
 * @since   3.1.0
 * @since   3.1.1
 * @since   3.1.2
 */

wp_enqueue_style( 'catapult-component-filter-pagination' );

get_header();

global $wp_query;

$search_query = get_search_query();
$found_posts  = $wp_query->found_posts;
$current_page = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
?>

<main id="main">
	<div class="search-hero">
		<div class="container">
			<div class="row">
				<div class="col-12 col-md-10 col-lg-8 mx-auto">
					<h1 class="sr-only"><?php esc_html_e( 'Search', 'catapult' ); ?></h1>

					<form action="/" method="GET" id="search-hero__form">
						<div class="search-hero__search-field">
							<input
								type="search"
								class="search-hero__input custom-search-input"
								name="s"
								placeholder="Search"
								aria-label="Term to search"
								value="<?php echo esc_html( $search_query ); ?>"
								required
							/>

							<button class="search-hero__submit" type="submit" aria-label="<?php esc_html_e( 'Search this website', 'catapult' ); ?>"></button>

							<button type="button" class="search-hero__clear" onclick="var input = this.previousElementSibling.previousElementSibling; input.value = ''; input.focus();"><span class="sr-only"><?php esc_html_e( 'Clear search', 'catapult' ); ?></span></button>
						</div>
					</form>

					<p class="search-hero__results" role="alert" aria-live="assertive">
						<?php if ( ! empty( $search_query ) ) : ?>
							<?php echo esc_html( $found_posts ); ?> results for "<?php echo esc_html( $search_query ); ?>"
						<?php endif; ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="content container">
		<div class="row">
			<div class="col-12 col-md-10 col-lg-8 mx-auto">
				<div class="search-results">
					<?php if ( have_posts() ) : ?>
						<?php
						while ( have_posts() ) :
							the_post();

							$current_post_type_object = get_post_type_object( get_post_type() );
							$result_subtitle          = '';
							$result_img               = wp_get_attachment_image(
								get_post_thumbnail_id(),
								'thumbnail',
								false,
								array(
									'aria-hidden' => 'true',
								)
							);
							$result_title             = get_the_title();
							$result_content           = get_the_excerpt();
							$result_url               = get_permalink();

							if ( ! empty( $current_post_type_object ) && ! empty( $current_post_type_object->labels ) && ! empty( $current_post_type_object->labels->singular_name ) ) {
								$result_subtitle = $current_post_type_object->labels->singular_name;
							}
							?>

							<a href="<?php echo esc_url( $result_url ); ?>" class="search-result" aria-label="<?php echo esc_html( $result_title ); ?>">
								<div class="search-result__text">
									<?php if ( ! empty( $result_subtitle ) ) : ?>
										<div class="search-result__subtitle"><?php echo esc_html( $result_subtitle ); ?></div>
									<?php endif; ?>

									<h2 class="search-result__title"><?php echo wp_kses_post( $result_title ); ?></h2>

									<?php if ( ! empty( $result_content ) ) : ?>
										<div class="search-result__excerpt"><?php echo wp_kses_post( $result_content ); ?></div>
									<?php endif; ?>
								</div>

								<?php if ( ! empty( $result_img ) ) : ?>
									<div class="search-result__image-wrapper image-wrapper">
										<?php echo wp_kses_post( $result_img ); ?>
									</div>
								<?php endif; ?>
							</a>
						<?php endwhile; ?>

						<?php if ( $wp_query->max_num_pages > 1 ) : ?>
							<nav class="search-results__pagination" aria-label="<?php esc_html_e( 'Pagination', 'catapult' ); ?>">
								<?php
								$base_url          = add_query_arg( 's', $search_query, home_url( '/' ) );
								$search_pagination = paginate_links(
									array(
										'current'   => $current_page,
										'total'     => $wp_query->max_num_pages,
										'base'      => $base_url . '%_%',
										'format'    => '&paged=%#%',
										'type'      => 'plain',
										'prev_text' => '<span class="sr-only">Previous page</span>',
										'next_text' => '<span class="sr-only">Next page</span>',
										'show_all'  => false,
										'end_size'  => 1,
										'mid_size'  => 1,
									)
								);
								?>
								
								<nav class="pagination" aria-label="<?php esc_html_e( 'Pagination', 'catapult' ); ?>">
									<?php echo wp_kses_post( $search_pagination ); ?>
								</nav>
							</nav>
						<?php endif; ?>
					<?php else : ?>
						<?php
						$contact_page = get_field( 'contact_page', 'general' );
						?>

						<div class="search-results__no-results">
							<h2 role="alert" aria-live="assertive"><?php esc_html_e( 'No results found', 'catapult' ); ?></h2>

							<p><?php esc_html_e( 'Check your spelling and filter options, or search a different keyword.', 'catapult' ); ?></p>

							<div class="wp-block-buttons">
								<div class="wp-block-button is-style-tertiary">
									<a href="<?php echo esc_url( add_query_arg( 's', '', home_url( '/' ) ) ); ?>" class="wp-block-button__link"><?php esc_html_e( 'Clear Search', 'catapult' ); ?></a>
								</div>

								<?php if ( ! empty( $contact_page ) ) : ?>
									<div class="wp-block-button is-style-tertiary">
										<a href="<?php echo esc_url( get_the_permalink( $contact_page ) ); ?>" class="wp-block-button__link"><?php esc_html_e( 'Contact Us', 'catapult' ); ?></a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
