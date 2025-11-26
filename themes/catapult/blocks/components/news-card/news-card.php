<?php
/**
 * Blog post card component.
 *
 * @package Catapult
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.10
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 * @since   3.1.7
 */

?>

<?php if ( ! empty( $post_object ) ) : ?>
	<?php
	$permalink    = get_the_permalink( $post_object->ID );
	$news_date    = $post_object->post_date;
	$news_month   = gmdate( 'M', strtotime( $news_date ) );
	$news_day     = gmdate( 'd', strtotime( $news_date ) );
	$primary_term = catapult_get_primary_term( 'news-category', $post_object->ID );
	$excerpt      = get_the_excerpt( $post_object->ID );

	if ( empty( $card_heading_level ) ) {
		$card_heading_level = 'h3';
	}
	?>

	<a href="<?php echo esc_url( $permalink ); ?>" class="news-card" aria-label="<?php echo esc_html( $post_object->post_title ); ?>">
		<div class="news-card__date">
			<span class="news-card__date-month"><?php echo esc_html( $news_month ); ?></span>
			<span class="news-card__date-day"><?php echo esc_html( $news_day ); ?></span>
		</div>
		
		<div class="news-card__content">
			<?php if ( ! empty( $primary_term ) ) : ?>
				<?php if ( ! empty( $primary_term ) ) : ?>
					<p class="news-card__primary-term"><?php echo wp_kses_post( $primary_term ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<<?php echo esc_attr( $card_heading_level ); ?> class="news-card__title"><?php echo esc_html( $post_object->post_title ); ?></<?php echo esc_attr( $card_heading_level ); ?>>

			<?php if ( ! empty( $excerpt ) ) : ?>
				<p class="news-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>
		</div>

		<div class="news-card__arrow">
			<span class="c-btn c-btn--secondary"><i class="icon-arrow-right"></i></span>
		</div>
	</a>
<?php endif; ?>
