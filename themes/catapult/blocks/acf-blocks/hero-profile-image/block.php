<?php
/**
 * Hero-Profile-Image
 *
 * Title:             Hero-Profile-Image
 * Description:       A hero section for single posts with a background image and centered text.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, content, image, columns, cpt, background, image, profile
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_image, background_video
 * Background Colors:
 * Default BG Color:  dark
 * InnerBlocks:       true
 * Starts With Text:
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
			'align'       => 'center',
		),
	),
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
		),
	),
	array(
		'core/buttons',
		array(
			'layout' => array(
				'justifyContent' => 'center',
			),
		),
		array(
			array(
				'core/button',
				array(
					'className' => 'is-style-primary',
				),
			),
		),
	),
);

$back_title = '';

if ( catapult_is_block_library() ) {
	$back_title = __( 'All accommodations', 'catapult' );
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>

	<?php catapult_the_back_link( null, $back_title ); ?>

	<div class="block-hero-profile-image__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-profile-image__content content-wrapper" />
	</div>
</section>
