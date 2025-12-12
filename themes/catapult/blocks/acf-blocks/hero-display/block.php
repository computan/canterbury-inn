<?php
/**
 * Hero-Display
 *
 * Title:             Hero-Display
 * Description:       Hero with background image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, display, image
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_color, background_image, background_video
 * Background Colors:
 * Default BG Color:  
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   2.2.9
 * @since   2.2.10
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
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'fontSize'    => 'display',
		),
	),
		array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add pre-heading here.', 'catapult' ),
			'fontSize'    => 'body',
		),
	),
	array(
		'core/buttons',
		array(),
		array(
			array(
				'core/button',
				array(
					'className' => 'is-style-primary',
				),
			),
			array(
				'core/button',
				array(
					'className' => 'is-style-secondary',
				),
			),
		),
	),
);
?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?> <?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?> class="acf-block block-hero-display bg-dark<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>

	<div class="parent-bg-div">
		<svg id="uuid-15d934c3-7323-4c43-8944-bc4e19dd220f" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="1920" height="76" viewBox="0 0 1920 76">
			<path d="M468.49,1.99c23.27.25,47.78,2.24,70.89,5.04,118.89,14.44,239.25,40,358.29,56.96,35.44,5.05,60.93,6.32,96.79,5.06,148.17-5.22,293.59-36.85,440.18-55.09,129.09-16.06,272.81,3.78,399.55,30.76,28.78,6.13,58.63,12.39,85.81,23.38v7.9H0v-7.9c66.1-19.86,133.55-35.74,201.81-47.07,62.5-10.37,126.29-17.59,189.77-19.02,0,0,75.21-.04,76.91-.02Z"/>
		</svg>
	</div>

	<div class="container block-hero-display__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-display__content" />
	</div>
</section>
