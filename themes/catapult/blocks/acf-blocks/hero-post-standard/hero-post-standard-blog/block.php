<?php
/**
 * Hero-Post-Standard-Blog
 *
 * Title:             Hero-Post-Standard-Blog
 * Description:       The inner block content for the Hero-Post-Standard block for Blog post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, blog
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * Styles:
 * Mode:              preview
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-post-standard-blog<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$read_time         = __( '4 min read', 'catapult' );
			$primary_term      = __( 'Category one', 'catapult' );
			$post_title        = __( 'Blog title text placeholder', 'catapult' );
			$author_name       = __( 'Author Name', 'catapult' );
			$featured_image_id = 'placeholder-3-2';
			$date              = __( 'Mth DD, YYYY', 'catapult' );

		} else {
			$read_time         = catapult_get_read_time( $post->ID );
			$primary_term      = catapult_get_primary_term( 'category', $post->ID );
			$post_title        = $post->post_title;
			$featured_image_id = get_post_thumbnail_id( $post );
			$date              = get_the_date( 'F j, Y', $post );

			if ( ! empty( $post->post_author ) ) {
				$author_name = get_the_author_meta( 'display_name', $post->post_author );
			}
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container">
			<div class="row block-hero-post-standard-blog__row">
				<div class="col-12 col-md-10 col-lg-8 mx-auto">
					<?php if ( ! empty( $primary_term ) || ! empty( $primary_term ) ) : ?>
						<div class="block-hero-post-standard-blog__meta-top">
							<?php if ( ! empty( $primary_term ) ) : ?>
								<span class="block-hero-post-standard-blog__primary-term"><?php echo wp_kses_post( $primary_term ); ?></span>
							<?php endif; ?>

							<?php if ( ! empty( $read_time ) ) : ?>
								<span class="block-hero-post-standard-blog__read-time"><?php echo esc_html( $read_time ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<h1 class="block-hero-post-standard-blog__title"><?php echo esc_html( $post_title ); ?></h1>

					<div class="block-hero-post-standard-blog__meta-bottom">
						<?php if ( ! empty( $author_name ) ) : ?>
							<span><?php echo esc_html( $author_name ); ?></span>
						<?php endif; ?>

						<span><?php echo esc_html( $date ); ?></span>
					</div>
				</div>

				<?php if ( ! empty( $featured_image_id ) ) : ?>
					<div class="col-12">
						<figure class="block-hero-post-standard-blog__image-wrapper">
							<?php echo wp_kses_post( wp_get_attachment_image( $featured_image_id, 'col-12', '', array( 'class' => 'block-hero-post-standard-blog__image' ) ) ); ?>
						</figure>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
