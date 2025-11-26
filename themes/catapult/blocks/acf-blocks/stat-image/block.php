<?php
/**
 * Stat-Image
 *
 * Title:             Stat-Image
 * Description:       Block with stats and an image.
 * Instructions:
 * Category:          Stat
 * Icon:              chart-bar
 * Keywords:          stats, statistics, numbers, data, results, image
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps
 * JS Deps:
 * Global ACF Fields: scroll_id, image, video
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'acf/stats' ) );

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 2,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
	array(
		'acf/stats',
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-stat-image<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<div class="row block-stat-image__row">
			<div class="col-12 col-md-6">
				<?php echo wp_kses_post( $content_block->get_block_image_and_video( 'square-half', 'block-stat-image__image-wrapper image-wrapper' ) ); ?>
			</div>

			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="col-12 col-md-6 col-lg-5" />
		</div>
	</div>
</section>
