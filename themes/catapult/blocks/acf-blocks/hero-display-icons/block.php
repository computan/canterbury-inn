<?php
/**
 * Hero-Display-Icons
 *
 * Title:             Hero-Display-Icons
 * Description:       Hero image with with bottom icon and text information.
 * Instructions:
 * Category:          Hero
 * Icon:              align-pull-right
 * Keywords:          hero, content, image, columns, icons
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: background_color
 * InnerBlocks:       true
 * Background Colors: transparent, white, secondary, neutral-8
 * Default BG Color:  secondary
 * Styles:
 * Context:
 *
 * @package Catapult
 * @since   3.0.16
 */

$content_block      = new Content_Block_Gutenberg( $block, $context );
$hero_display_icons = get_field( 'hero_display_icons' );
$allowed_blocks     = catapult_text_blocks( 'acf/icons-text' );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'display',
			'className'   => 'block-hero-display-icons-heading',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add details here.', 'catapult' ),
			'fontSize'    => 'body',
			'className'   => 'block-hero-display-icons-details',
		),
	),
	array(
		'core/buttons',
		array(
			'className' => 'is-style-primary is-content-justification-center',
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-display-icons">
	<div class="container block-hero-display-icons__container<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-display-icons__container__content" />
	</div>
	<div class="block-hero-display-icons-text">
		<div class="block-hero-display-icons__text-container">
			<?php
			if ( ! empty( $hero_display_icons['icon_details'] ) ) {
				foreach ( $hero_display_icons['icon_details'] as $detail ) {

					$icon_description = $detail['icon_description'];
					$icon_heading     = $detail['icon_heading'];
					$icon             = $detail['icon'];
					?>
					<div class="hero-display-icon-details">
						<?php
						if ( $icon ) {
							?>
							<div class="hero-display-icon">
								<i class="<?php echo esc_attr( $icon ); ?>"></i>
							</div>
							<?php
						}
						?>
						<div class="hero-display-icon-text">
							<?php
							if ( $icon_heading ) {
								?>
								<h2 class="hero-display-icon-text__heading"><?php echo esc_html( $icon_heading ); ?></h2>
								<?php
							}
							if ( $icon_description ) {
								?>
								<p class="hero-display-icon-text__details"><?php echo esc_html( $icon_description ); ?></p>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>
