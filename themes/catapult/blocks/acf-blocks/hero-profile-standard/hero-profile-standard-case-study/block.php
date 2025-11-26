<?php
/**
 * Hero-Profile-Standard-Case-Study
 *
 * Title:             Hero-Profile-Standard-Case-Study
 * Description:       The inner block content for the Hero-Profile-Standard block for Case Study post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, case, study, studies
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
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-standard-case-study<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Case Study title text placeholder', 'catapult' );
			$logo_id           = 'logo-placeholder-no-padding';
			$featured_image_id = 'placeholder-3-2';
			$primary_term      = __( 'Category one', 'catapult' );
			$date              = __( 'Mth DD, YYYY', 'catapult' );
		} else {
			$post_title        = $post->post_title;
			$logo_id           = get_field( 'logo' );
			$featured_image_id = get_post_thumbnail_id( $post );
			$primary_term      = get_primary_term( 'category', $post->ID );
			$date              = get_the_date( 'F j, Y', $post );
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container block-hero-profile-standard-case-study__container">
			<div class="block-hero-profile-standard-case-study__content">
				<?php if ( ! empty( $logo_id ) ) : ?>
					<figure class="block-hero-profile-standard-case-study__logo-wrapper">
						<?php echo wp_kses_post( wp_get_attachment_image( $logo_id, 'logo-block', '', array( 'class' => 'block-hero-profile-standard-case-study__logo' ) ) ); ?>
					</figure>
				<?php endif; ?>

				<h1 class="block-hero-profile-standard-case-study__title"><?php echo esc_html( $post_title ); ?></h1>
				
				<div class="block-hero-profile-standard-case-study__meta-bottom">
					<?php if ( ! empty( $primary_term ) ) : ?>
						<span><?php echo esc_html( $primary_term ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( ! empty( $featured_image_id ) ) : ?>
				<figure class="block-hero-profile-standard-case-study__image-wrapper">
					<?php
					echo wp_kses_post(
						wp_get_attachment_image(
							$featured_image_id,
							'col-6',
							'',
							array(
								'class' => 'block-hero-profile-standard-case-study__image',
								'block_library_placeholder_aspect_ratio' => '1.33333333',
							)
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
