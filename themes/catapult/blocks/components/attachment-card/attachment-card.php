<?php
/**
 * Media gallery attachment card component.
 *
 * @package Catapult
 * @since   3.0.14
 * @since   3.1.1
 * @since   3.1.2
 */

?>

<?php if ( ! empty( $post_object ) && ! empty( $post_object->post_mime_type ) ) : ?>
	<?php
	$media_gallery_video = get_field( 'media_gallery_video', $post_object->ID, false );
	$caption             = wp_get_attachment_caption( $post_object->ID );

	$additional_attributes = '';
	$additional_classes    = '';
	$caption_html          = '';
	$page_number           = 1;

	if ( ! empty( $_GET['page'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page_number = intval( wp_unslash( $_GET['page'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( 0 === strpos( $post_object->post_mime_type, 'video' ) ) {
		$video_url = wp_get_attachment_url( $post_object->ID );

		if ( ! empty( $video_url ) ) {
			$additional_attributes = ' data-embed-url="' . $video_url . '"';
			$additional_classes    = ' component-video';
		}
	} elseif ( ! empty( $media_gallery_video ) ) {
		$additional_attributes = ' data-embed-url="' . $media_gallery_video . '"';
		$additional_classes    = ' component-video';
	} elseif ( ! empty( $caption ) ) {
		$caption_html = '<figcaption class="wp-element-caption">' . $caption . '</figcaption>';
	}
	?>

	<figure class="attachment-card<?php echo esc_attr( $additional_classes ); ?>" data-lightbox-content="<?php echo wp_kses_post( htmlspecialchars( wp_json_encode( '<figure class="component-lightbox__image-wrapper' . $additional_classes . '" data-page="' . $page_number . '" data-post-id="' . $post_object->ID . '"' . $additional_attributes . '>' . wp_get_attachment_image( $post_object->ID, 'full-width' ) . '</figure>' . $caption_html ) ) ); ?>" data-post-id="<?php echo esc_attr( $post_object->ID ); ?>" data-page="<?php echo esc_attr( $page_number ); ?>"<?php echo wp_kses_post( $additional_attributes ); ?>>
		<div class="attachment-card__image-wrapper image-wrapper">
			<?php if ( 0 === strpos( $post_object->post_mime_type, 'image' ) ) : ?>
				<?php if ( ! empty( $media_gallery_video ) ) : ?>
					<button class="component-video__play-button c-btn--play c-btn--color-alt" type="button"><span class="sr-only"><?php esc_html_e( 'Play video', 'catapult' ); ?></span></button>
				<?php endif; ?>

				<?php echo wp_kses_post( wp_get_attachment_image( $post_object->ID, 'attachment-card', '', array( 'class' => 'attachment-card__image' ) ) ); ?>
			<?php elseif ( ! empty( $video_url ) ) : ?>
				<video class="attachment-card__video">
					<source src="<?php echo esc_url( $video_url ); ?>">
				</video>

				<button class="component-video__play-button c-btn--play c-btn--color-alt" type="button"><span class="sr-only"><?php esc_html_e( 'Play video', 'catapult' ); ?></span></button>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $caption ) ) : ?>
			<figcaption class="attachment-card__caption wp-element-caption"><?php echo wp_kses_post( $caption ); ?></figcaption>
		<?php endif; ?>
	</figure>
<?php endif; ?>
