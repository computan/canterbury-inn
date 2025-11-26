<?php
/**
 * Featured blog post card component.
 *
 * @package Catapult
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.18
 * @since   3.0.19
 * @since   3.1.0
 * @since   3.1.6
 * @since   3.1.7
 */

?>

<?php if ( ! empty( $post_object ) ) : ?>
	<?php
	$read_time    = catapult_get_read_time( $post_object->ID );
	$primary_term = catapult_get_primary_term( 'category', $post_object->ID );
	$author_name  = get_the_author_meta( 'display_name', $post_object->post_author );
	?>

	<article class="post-card-featured">
		<div class="post-card-featured__image-wrapper image-wrapper">
			<?php echo wp_kses_post( get_the_post_thumbnail( $post_object, 'blog-post-6', array( 'class' => 'post-card-featured__image' ) ) ); ?>
		</div>

		<div class="post-card-featured__content">
			<?php if ( ! empty( $read_time ) || ! empty( $primary_term ) ) : ?>
				<div class="post-card-featured__meta">
					<?php if ( ! empty( $primary_term ) ) : ?>
						<span><?php echo wp_kses_post( $primary_term ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $read_time ) ) : ?>
						<span><?php echo wp_kses_post( $read_time ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<h2 class="post-card-featured__title"><a href="<?php echo esc_url( get_the_permalink( $post_object ) ); ?>" class="post-card-featured__link"><?php echo esc_html( $post_object->post_title ); ?></a></h2>

			<?php if ( ! empty( $post_object->post_excerpt ) ) : ?>
				<p class="post-card-featured__excerpt has-body-2-font-size"><?php echo wp_kses_post( $post_object->post_excerpt ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $author_name ) ) : ?>
				<div class="post-card-featured__author"><?php echo esc_html( sprintf( __( 'By %s', 'catapult' ), $author_name ) ); ?></div>
			<?php endif; ?>
		</div>
	</article>
<?php endif; ?>
