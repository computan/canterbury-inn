<?php
/**
 * Hero-Archive-Featured
 *
 * Title:             Hero-Archive-Featured
 * Description:       Hero section for use on archives that displays 1 to 5 highlighted posts.
 * Instructions:
 * Category:          Hero
 * Icon:              admin-post
 * Keywords:          blog, archive, hero, featured, highlight
 * Post Types:        all
 * Multiple:          false
 * Active:            true
 * CSS Deps:          post-card-featured
 * JS Deps:
 * Global ACF Fields: scroll_id
 * InnerBlocks:       true
 * Styles:
 * Context:
 * Starts With Text:
 * Default BG Color:
 *
 * @package Catapult
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$featured_posts       = get_field( 'featured_posts' );
$additional_card_size = get_field( 'additional_card_size' );

if ( empty( $additional_card_size ) ) {
	$additional_card_size = 'small';
}

if ( ! empty( $featured_posts ) ) {
	if ( 'medium' === $additional_card_size ) {
		$featured_posts = array_slice( $featured_posts, 0, 4 );
	} elseif ( 'none' === $additional_card_size ) {
		$featured_posts = array_slice( $featured_posts, 0, 1 );
	} else {
		$featured_posts = array_slice( $featured_posts, 0, 5 );
	}
} else {
	$args = array(
		'post_type'      => 'post',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => 5,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( is_post_type_archive() ) {
		$args['post_type'] = get_post_type();
	}

	if ( 'medium' === $additional_card_size ) {
		$args['posts_per_page'] = 4;
	} elseif ( 'none' === $additional_card_size ) {
		$args['posts_per_page'] = 1;
	}

	$featured_posts = get_posts( $args );
}

$allowed_blocks = catapult_text_blocks();

$template = array(
	array(
		'core/heading',
		array(
			'level'       => 1,
			'placeholder' => __( 'Add heading here.', 'catapult' ),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-archive-featured block-hero-archive-featured--<?php echo esc_attr( $additional_card_size ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-hero-archive-featured__content" />

		<?php if ( ! empty( $featured_posts ) ) : ?>
			<?php
			$featured_post_count = count( $featured_posts );
			?>

			<?php catapult_get_component( $featured_posts[0]->post_type . '-card-featured', array( 'post_object' => $featured_posts[0] ) ); ?>

			<?php if ( $featured_post_count > 1 ) : ?>
				<h2 class="block-hero-archive-featured__latest-heading"><?php echo esc_html( sprintf( __( 'Latest %ss', 'catapult' ), $featured_posts[0]->post_type ) ); ?></h2>

				<div class="block-hero-archive-featured__grid">
					<?php for ( $i = 1; $i < $featured_post_count; $i++ ) : ?>
						<?php catapult_get_component( $featured_posts[ $i ]->post_type . '-card', array( 'post_object' => $featured_posts[ $i ] ) ); ?>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
