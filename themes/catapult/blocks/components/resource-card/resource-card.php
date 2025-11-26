<?php
/**
 * Resource post card component.
 *
 * @package Catapult
 * @since   3.0.3
 * @since   3.0.5
 * @since   3.0.17
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.6
 * @since   3.1.7
 */

wp_enqueue_style( 'core-button' );
?>

<?php if ( ! empty( $post_object ) ) : ?>
	<?php
	$permalink    = get_the_permalink( $post_object->ID );
	$primary_term = catapult_get_primary_term( 'resources_category', $post_object->ID );
	$excerpt      = get_the_excerpt( $post_object->ID );

	if ( empty( $card_heading_level ) ) {
		$card_heading_level = 'h3';
	}
	?>

	<div class="resource-card">
		<?php if ( ! empty( $primary_term ) ) : ?>
			<?php if ( ! empty( $primary_term ) ) : ?>
				<p class="resource-card__primary-term"><?php echo wp_kses_post( $primary_term ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

		<<?php echo esc_attr( $card_heading_level ); ?> class="resource-card__title"><?php echo esc_html( $post_object->post_title ); ?></<?php echo esc_attr( $card_heading_level ); ?>>

		<?php if ( ! empty( $excerpt ) ) : ?>
			<p class="resource-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<?php endif; ?>

		<div class="resource-card__read-more wp-block-button wp-block-button--icon-right wp-block-button--small is-style-tertiary" style="--buttonIcon: var(--icon-arrow-right)">
			<a href="<?php echo esc_url( $permalink ); ?>" class="wp-block-button__link"><?php esc_html_e( 'Read More', 'catapult' ); ?></a>
		</div>
	</div>
<?php endif; ?>
