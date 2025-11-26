<?php
/**
 * Hero-Post-Standard-News
 *
 * Title:             Hero-Post-Standard-News
 * Description:       The inner block content for the Hero-Post-Standard block News post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, news
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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-post-standard-news<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$primary_term      = __( 'Category one', 'catapult' );
			$post_title        = __( 'News title text placeholder', 'catapult' );
			$featured_image_id = 'placeholder-3-2';
			$date              = __( 'Mth DD, YYYY', 'catapult' );
		} else {
			$primary_term      = catapult_get_primary_term( 'category', $post->ID );
			$post_title        = $post->post_title;
			$featured_image_id = get_post_thumbnail_id( $post );
			$date              = get_the_date( 'F j, Y', $post );
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container">
			<div class="row block-hero-post-standard-news__row">
				<div class="col-12 col-md-10 col-lg-8 mx-auto">
					<?php if ( ! empty( $primary_term ) ) : ?>
						<div class="block-hero-post-standard-news__primary-term"><?php echo wp_kses_post( $primary_term ); ?></div>
					<?php endif; ?>

					<h1 class="block-hero-post-standard-news__title"><?php echo esc_html( $post_title ); ?></h1>

					<div class="block-hero-post-standard-news__date"><?php echo esc_html( get_the_date( 'F j, Y', $post ) ); ?></div>
				</div>

				<?php if ( ! empty( $featured_image_id ) ) : ?>
					<div class="col-12">
						<figure class="block-hero-post-standard-news__image-wrapper">
							<?php echo wp_kses_post( wp_get_attachment_image( $featured_image_id, 'col-12', '', array( 'class' => 'block-hero-post-standard-news__image' ) ) ); ?>
						</figure>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
