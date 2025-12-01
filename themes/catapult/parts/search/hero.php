<?php
/**
 * The search page hero.
 *
 * @category Template
 * @package Catapult
 * @since   3.1.2
 */

$args = wp_parse_args(
	$args,
	array(
		'search_query' => '',
		'found_posts'  => 0,
	)
);
?>
<section class="acf-block block-hero-search search-hero content-wrapper">
	<div class="search-hero__search-form">
		<div class="search-hero__col">
			<form action="/" method="GET" id="search-form">
				<?php if ( function_exists( 'wpes_use_autocomplete' ) && wpes_use_autocomplete() ) : ?>
					<div id="wpes-autocomplete" data-name="s" data-query="<?php echo esc_attr( $args['search_query'] ); ?>"></div>
				<?php else : ?>
					<div class="search-field clear-input-container">
						<i class="icon icon-search"></i>
						<input type="text" class="search-field__input" name="s" placeholder="Search this website" aria-label="Term to search" required value="<?php echo esc_html( $args['search_query'] ); ?>" />
						<button class="search-field__clear" type="button">
							<i class="icon icon-close"></i>
						</button>
					</div>
				<?php endif; ?>
			</form>
			<?php if ( 0 === $args['found_posts'] ) : ?>
				<?php if ( ! empty( $args['search_query'] ) ) : ?>
					<div class="no-result-text">
						<p><?php echo esc_html( $args['found_posts'] ); ?> results for "<?php echo esc_html( $args['search_query'] ); ?>"</p>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="result-found-text">
					<p>
						<?php if ( ! empty( $args['search_query'] ) ) : ?>
							<?php echo esc_html( $args['found_posts'] ); ?> results for "<?php echo esc_html( $args['search_query'] ); ?>"
						<?php endif; ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
