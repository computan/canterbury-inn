<?php
/**
 * Hero-Profile-Standard-Dining
 *
 * Title:             Hero-Profile-Standard-Dining
 * Description:       The inner block content for the Hero-Profile-Standard block for Dining post types.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          post, hero, profile, standard, dining
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

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-standard-dining<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php
	if ( ! empty( $post ) ) :
		?>
		<?php
		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$post_title        = __( 'Dining title text placeholder', 'catapult' );
			$featured_image_id = 'placeholder-3-2';
			$cuisine           = __( 'Italian', 'catapult' );
			$price             = __( '$$$', 'catapult' );
			$kid_friendly      = __( 'Kid Friendly', 'catapult' );
			$description       = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Netus elementum sollicitudin magna bibendum.', 'catapult' );
		} else {
			$post_title        = $post->post_title;
			$featured_image_id = get_post_thumbnail_id( $post );
			$cuisine           = get_field( 'cuisine' );
			$price             = get_field( 'price' );
			$kid_friendly      = get_field( 'kid_friendly' );
			$description       = $post->post_excerpt;
		}
		?>

		<?php catapult_the_back_link(); ?>

		<div class="container block-hero-profile-standard-dining__container">
			<div class="block-hero-profile-standard-dining__content">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
				
				<h1 class="block-hero-profile-standard-dining__title"><?php echo esc_html( $post_title ); ?></h1>

				<?php if ( ! empty( $description ) ) : ?>
					<p class="block-hero-profile-standard-dining__excerpt"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<div class="block-hero-profile-standard-dining__meta-bottom">
					<?php if ( ! empty( $cuisine ) ) : ?>
						<span><?php echo esc_html( $cuisine ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $price ) ) : ?>
						<span><?php echo esc_html( $price ); ?></span>
					<?php endif; ?>
					
					<?php if ( ! empty( $kid_friendly ) ) : ?>
						<span><?php echo esc_html( $kid_friendly ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( ! empty( $featured_image_id ) ) : ?>
				<figure class="block-hero-profile-standard-dining__image-wrapper">
						<?php
						echo wp_kses_post(
							wp_get_attachment_image(
								$featured_image_id,
								'col-7',
								'',
								array(
									'class' => 'block-hero-profile-standard-dining__image',
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
