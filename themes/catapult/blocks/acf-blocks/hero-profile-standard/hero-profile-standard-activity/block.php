<?php
/**
 * Hero-Profile-Standard-Activity
 *
 * Title:             Hero-Profile-Standard-Activity
 * Description:       The inner block content for the Hero-Profile-Standard block for Activity post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, activity
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Styles:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$registration_booking_link = get_field( 'registration_booking_link' );

if ( catapult_is_block_library() ) {
	$registration_booking_link = array(
		'url'   => '#',
		'title' => __( 'Register', 'catapult' ),
	);
}

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-standard-activity<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php
	if ( ! empty( $post ) ) :
		?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Activity title text placeholder', 'catapult' );
			$logo_id           = 'logo-placeholder';
			$featured_image_id = 'placeholder-3-2';
			$primary_term      = __( 'Category', 'catapult' );
			$age               = __( 'Age Label', 'catapult' );
			$description       = __( 'This is the activity post excerpt. It is approximately 160 characters to align with Google\'s average excerpt character count. Lorem Ipsum is simply dummy text.', 'catapult' );
		} else {
			$post_title        = $post->post_title;
			$logo_id           = get_field( 'logo' );
			$featured_image_id = get_post_thumbnail_id( $post );
			$primary_term      = get_primary_term( 'category', $post->ID );
			$age               = get_field( 'age' );
			$description       = $post->post_excerpt;
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container block-hero-profile-standard-activity__container">
			<div class="block-hero-profile-standard-activity__content">
				<?php if ( ! empty( $primary_term ) ) : ?>
					<span class="block-hero-profile-standard-activity__term"><?php echo esc_html( $primary_term ); ?></span>
				<?php endif; ?>

				<h1 class="block-hero-profile-standard-activity__title"><?php echo esc_html( $post_title ); ?></h1>

				<div class="block-hero-profile-standard-activity__meta-bottom">
					<?php if ( ! empty( $age ) ) : ?>
						<span><span class="icon icon-age"></span><?php echo esc_html( $age ); ?></span>
					<?php endif; ?>
				</div>
				
				<?php if ( ! empty( $description ) ) : ?>
					<p class="block-hero-profile-standard-activity__excerpt"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $registration_booking_link ) ) : ?>
					<?php echo wp_kses_post( catapult_array_to_link( $registration_booking_link, 'block-quick-links__link', array() ) ); ?>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $featured_image_id ) ) : ?>
				<figure class="block-hero-profile-standard-activity__image-wrapper">
					<?php
					echo wp_kses_post(
						wp_get_attachment_image(
							$featured_image_id,
							'col-7',
							'',
							array(
								'class' => 'block-hero-profile-standard-activity__image',
								'block_library_placeholder_aspect_ratio' => '1.4920634',
							)
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
