<?php
/**
 * Blog post card component.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 * @since   3.1.7
 */

?>

<?php if ( ! empty( $post_object ) ) : ?>
	<?php
	$read_time    = catapult_get_read_time( $post_object->ID );
	$primary_term = catapult_get_primary_term( 'category', $post_object->ID );
	$author_name  = get_the_author_meta( 'display_name', $post_object->post_author );

	if ( empty( $card_heading_level ) ) {
		$card_heading_level = 'h3';
	}
	?>

	<a href="<?php echo esc_url( get_the_permalink( $post_object ) ); ?>" class="post-card" aria-label="<?php echo esc_html( $post_object->post_title ); ?>">
		<figure class="post-card__image-wrapper image-wrapper">
			<?php
			echo wp_kses_post(
				get_the_post_thumbnail(
					$post_object,
					'card-image-link-4',
					array(
						'class'       => 'post-card__image',
						'aria-hidden' => 'true',
					)
				)
			);
			?>
		</figure>

		<?php if ( ! empty( $read_time ) || ! empty( $primary_term ) ) : ?>
			<div class="post-card__meta">
				<?php if ( ! empty( $primary_term ) ) : ?>
					<span><?php echo wp_kses_post( $primary_term ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $read_time ) ) : ?>
					<span><?php echo wp_kses_post( $read_time ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<<?php echo esc_attr( $card_heading_level ); ?> class="post-card__title"><?php echo esc_html( $post_object->post_title ); ?></<?php echo esc_attr( $card_heading_level ); ?>>

		<?php if ( ! empty( $author_name ) ) : ?>
			<div class="post-card__author"><?php echo esc_html( sprintf( __( 'By %s', 'catapult' ), $author_name ) ); ?></div>
		<?php endif; ?>
	</a>
<?php endif; ?>
