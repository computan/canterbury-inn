<?php
/**
 * Hero-Profile-Standard-Accommodation
 *
 * Title:             Hero-Profile-Standard-Accommodation
 * Description:       The inner block content for the Hero-Profile-Standard block for Accomodation post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, accomodation, studies
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * Styles:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

$registration_booking_link = get_field( 'registration_booking_link' );

if ( catapult_is_block_library() ) {
	$registration_booking_link = array(
		'url'   => '#',
		'title' => __( 'Book Now', 'catapult' ),
	);
}

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add pre-heading here.', 'catapult' ),
			'fontSize'    => 'overline',
		),
	),
);

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-standard-accommodation<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Accommodation title text placeholder', 'catapult' );
			$featured_image_id = 'placeholder-3-2';
			$beds              = __( 'xxx size bed', 'catapult' );
			$square_feet       = __( 'xxx square feet', 'catapult' );
			$description       = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Netus elementum sollicitudin magna bibendum.', 'catapult' );
		} else {
			$post_title        = $post->post_title;
			$featured_image_id = get_post_thumbnail_id( $post );
			$beds              = __( 'xxx size bed', 'catapult' );
			$square_feet       = __( 'xxx square feet', 'catapult' );
			$description       = $post->post_excerpt;
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container block-hero-profile-standard-accommodation__container">
			<div class="block-hero-profile-standard-accommodation__content">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

				<h1 class="block-hero-profile-standard-accommodation__title"><?php echo esc_html( $post_title ); ?></h1>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="block-hero-profile-standard-accommodation__excerpt"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
				<div class="block-hero-profile-standard-accommodation__meta-bottom">
					<?php if ( ! empty( $beds ) ) : ?>
						<span><span class="icon icon-bed"></span><?php echo esc_html( $beds ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $square_feet ) ) : ?>
						<span><span class="icon icon-square-feet"></span><?php echo esc_html( $square_feet ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $registration_booking_link ) ) : ?>
					<?php echo wp_kses_post( catapult_array_to_link( $registration_booking_link, 'block-quick-links__link', array() ) ); ?>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $featured_image_id ) ) : ?>
				<figure class="block-hero-profile-standard-accommodation__image-wrapper">
					<?php
					echo wp_kses_post(
						wp_get_attachment_image(
							$featured_image_id,
							'col-6',
							'',
							array(
								'class' => 'block-hero-profile-standard-accommodation__image',
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
