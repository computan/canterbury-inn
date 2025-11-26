<?php
/**
 * Hero-Post-Standard-Accommodation
 *
 * Title:             Hero-Post-Standard-Accommodation
 * Description:       The inner block content for the Hero-Post-Standard block for Accommodation post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, accommodation
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:          core/button
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * Styles:
 * InnerBlocks:       true
 * Wrap InnerBlocks:  false
 * Starts With Text:
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
		'title' => __( 'Book Now', 'catapult' ),
	);
}

$allowed_blocks = catapult_text_blocks();

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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-post-standard-accommodation<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Accommodation title text placeholder', 'catapult' );
			$beds              = __( 'xxx size bed', 'catapult' );
			$square_feet       = __( 'xxx square feet', 'catapult' );
			$featured_image_id = 'placeholder-3-2';

		} else {
			$post_title        = $post->post_title;
			$beds              = catapult_get_primary_term( 'category', $post->ID );
			$square_feet       = catapult_get_primary_term( 'category', $post->ID );
			$featured_image_id = get_post_thumbnail_id( $post );
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container">
			<div class="row block-hero-post-standard-accommodation__row">
				<div class="col-12 col-md-10 col-lg-8 mx-auto">
					<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

					<h1 class="block-hero-post-standard-accommodation__title"><?php echo esc_html( $post_title ); ?></h1>

					<div class="block-hero-post-standard-accommodation__meta-bottom">
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
					<div class="col-12">
						<figure class="block-hero-post-standard-accommodation__image-wrapper">
							<?php echo wp_kses_post( wp_get_attachment_image( $featured_image_id, 'col-12', '', array( 'class' => 'block-hero-post-standard-accommodation__image' ) ) ); ?>
						</figure>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
