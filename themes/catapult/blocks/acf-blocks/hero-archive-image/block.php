<?php
/**
 * Hero-Archive-Image
 *
 * Title:             Hero-Archive-Image
 * Description:       Text hero with the name of the archive and a background image.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, content, image, columns, archive, cpt, background, image
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id, background_image
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
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
			'textAlign'   => 'center',
			'fontSize'    => 't1',
		),
	),
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add paragraph here.', 'catapult' ),
			'fontSize'    => 'body',
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-archive-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>
<div class="parent-bg-div">
<svg id="uuid-ce85b55a-ab79-442d-8df1-4c6745c8fd6d" data-name="b" xmlns="http://www.w3.org/2000/svg" width="1920" height="143" viewBox="0 0 1920 143">
	<g id="uuid-3924211f-dd22-4427-ad7d-519a4fb8f2db" data-name="c">
	<path class="uuid-aa1e40f8-a0ea-4e97-bda5-22bbb2065909" d="M3,0c153.21,31.57,308.55,55.21,464.5,70,140.8,13.35,277.58,17.86,418.95,15.96,31.94-.43,58.5,5.32,70.58,38.5.71,1.94,1.95,10.39,2.47,10.54,2.25.67,1.24-1.32,1.51-2.45,9.13-38.6,36.36-47.09,72.52-46.6,141.37,1.9,278.16-2.61,418.95-15.96C1608.78,55.17,1764.38,31.35,1917.98,0h2v143H0V0h3Z"/>
	</g>
</svg>
	</div>
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-archive-image__container content-wrapper" />
</section>
