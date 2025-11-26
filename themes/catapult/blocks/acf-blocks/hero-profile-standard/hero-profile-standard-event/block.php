<?php
/**
 * Hero-Profile-Standard-Event
 *
 * Title:             Hero-Profile-Standard-Event
 * Description:       The inner block content for the Hero-Profile-Standard block for Event post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, event
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:          share-icons
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * Styles:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$registration_booking_link = get_field( 'registration_booking_link' );

if ( catapult_is_block_library() ) {
	$registration_booking_link = array(
		'url'   => '#',
		'title' => __( 'Register Link', 'catapult' ),
	);
}

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-standard-event<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php
	if ( ! empty( $post ) ) :
		?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Event post title goes here', 'catapult' );
			$featured_image_id = 'placeholder-3-2';
			$age               = __( 'Age Range One', 'catapult' );
			$event_date        = __( 'Month DD at 00:00 am - 00:00 pm', 'catapult' );
			$recurring         = __( 'Every Monday from MM/DD/YY to MM/DD/YY – View All', 'catapult' );
			$price             = __( 'Member: $XXX | Non-Member: $XYZ', 'catapult' );
			$description       = __( 'This is the event post excerpt. It is approximately 160 characters to align with Google\'s average excerpt character count. Lorem Ipsum is simply dummy text.', 'catapult' );
			$categories        = array(
				0 => (object) array(
					'name' => 'Category one',
				),
				1 => (object) array(
					'name' => 'Category Two',
				),
			);
		} else {
			$post_title        = $post->post_title;
			$featured_image_id = get_post_thumbnail_id( $post );
			$age               = get_field( 'age' );
			$price             = get_field( 'member_price' );
			$event_date        = get_field( 'event_date' );
			$description       = $post->post_excerpt;
			$categories        = get_the_terms( $post, 'category' );
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container block-hero-profile-standard-event__container">
			<div class="block-hero-profile-standard-event__content">
				<div class="block-hero-profile-standard-event__top-content">
					<?php if ( ! empty( $categories ) ) : ?>
						<div class="block-hero-profile-standard-event__terms">
							<?php foreach ( $categories as $category ) : ?>
								<span class="block-hero-profile-standard-event__term"><?php echo esc_html( $category->name ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?> 

					<h1 class="block-hero-profile-standard-event__title"><?php echo esc_html( $post_title ); ?></h1>

					<div class="block-hero-profile-standard-event__meta">
						<?php if ( ! empty( $age ) ) : ?>
							<span><span class="icon icon-age"></span><?php echo esc_html( $age ); ?></span>
						<?php endif; ?>
						
						<?php if ( ! empty( $event_date ) ) : ?>
							<span><span class="icon icon-time"></span><?php echo esc_html( $event_date ); ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $recurring ) ) : ?>
							<span><span class="icon icon-recurring"></span><?php echo esc_html( $recurring ); ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $price ) ) : ?>
							<span>
								<span class="icon icon-tag"></span>
								<span><?php echo esc_html( $price ); ?></span>
							</span>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $description ) ) : ?>
						<p class="block-hero-profile-standard-event__excerpt"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $registration_booking_link ) ) : ?>
						<?php echo wp_kses_post( catapult_array_to_link( $registration_booking_link, 'block-quick-links__link wp-block-button--small', array() ) ); ?>
					<?php endif; ?>
				</div>			
			</div>
			<?php if ( ! empty( $featured_image_id ) ) : ?>
				<figure class="block-hero-profile-standard-event__image-wrapper">
						<?php
						echo wp_kses_post(
							wp_get_attachment_image(
								$featured_image_id,
								'col-7',
								'',
								array(
									'class' => 'block-hero-profile-standard-event__image',
									'block_library_placeholder_aspect_ratio' => '1.3333',
								)
							)
						);
						?>
				</figure>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
