<?php
/**
 * Post-Accommodation
 *
 * Title:             Post-Accommodation
 * Description:       A stylelized block with inner blocks.
 * Instructions:
 * Category:          Post
 * Icon:              admin-post
 * Keywords:          post, accommodation, lodging, room, check-in, check-out, rental
 * Post Types:        post, library_block
 * Multiple:          true
 * Active:            false
 * CSS Deps:          acf/form, acf/form-item
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Mode:              preview
 * Text Width Styles: true
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks( array( 'acf/media-slider-grid', 'acf/cta-grid' ) );

$template = array(
	array(
		'core/paragraph',
		array(
			'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
		),
	),
);

$sidebar_blocks = catapult_render_theme_blocks( 'accommodation_sidebar', true );

$block_classes = '';

if ( ! empty( $sidebar_blocks ) ) {
	$block_classes .= ' acf-block--has-sidebar';
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-post-accommodation<?php echo esc_attr( $block_classes ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container block-post-accommodation__container">
		<?php if ( ! empty( $sidebar_blocks ) ) : ?>
			<div class="block-post-accommodation__sidebar">
				<?php if ( is_admin() ) : ?>
					<em class="has-body-2-font-size" style="margin-bottom: 2rem; display: block;"><?php echo wp_kses_post( sprintf( __( 'The sidebar content is managed in the %s', 'catapult' ), '<a href="' . admin_url( 'edit.php?post_type=theme_block' ) . '">Theme Blocks</a>' ) ); ?></em>
				<?php endif; ?>

				<?php echo $sidebar_blocks; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-post-accommodation__content content-wrapper--sidebar" />
	</div>
</section>
