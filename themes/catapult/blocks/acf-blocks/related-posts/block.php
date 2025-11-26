<?php
/**
 * Related-Posts
 *
 * Title:             Related-Posts
 * Description:       Block displaying related post cards.
 * Instructions:
 * Category:          Post
 * Icon:              admin-post
 * Keywords:          blog, post, card, related, cpt
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:  neutral-10
 * InnerBlocks:       true
 * Styles:
 * Context:
 * Starts With Text:  true
 * CSS Custom Props:  cards_per_row: 3
 *
 * @package Catapult
 * @since   1.0.0
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$post_objects = catapult_get_block_related_posts();

$cards_per_row = get_field( 'cards_per_row' );
$post_types    = get_field( 'post_types' );

if ( empty( $cards_per_row ) ) {
	$cards_per_row = 3;
}

$cards_per_row = intval( $cards_per_row );

$card_sizes = array(
	'Xl',
	'Lg',
	'Md',
	'Sm',
);

$card_size = $card_sizes[ $cards_per_row - 1 ];

$allowed_blocks = array( 'core/heading', 'core/buttons' );

$template = array(
	array(
		'core/heading',
		array(
			'level'   => 2,
			'content' => __( 'Related Posts', 'catapult' ),
		),
	),
	array(
		'core/buttons',
		array(),
		array(
			array(
				'core/button',
				array(
					'className'  => 'is-style-tertiary',
					'text'       => __( 'View all posts', 'catapult' ),
					'url'        => get_the_permalink( get_option( 'page_for_posts' ) ),
					'buttonIcon' => 'icon-arrow-right',
				),
			),
		),
	),
);

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-related-posts block-related-posts--<?php echo esc_attr( $cards_per_row ); ?><?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-related-posts__top" />

		<?php if ( ! empty( $post_objects ) || catapult_is_block_library() ) : ?>
			<div class="block-related-posts__post-grid">
				<?php if ( ! empty( $post_objects ) ) : ?>
					<?php
					foreach ( $post_objects as $post_object ) {
						catapult_get_component(
							$post_object->post_type . '-card',
							array(
								'post_object' => $post_object,
								'card_size'   => $card_size,
							)
						);
					}
					?>
				<?php else : ?>
					<?php
					if ( 1 === $cards_per_row ) {
						$cards_per_row = 3;
					}
					?>

					<?php for ( $i = 0; $i < $cards_per_row; $i++ ) : ?>
						<div class="block-related-posts__placeholder-card">
							<span><?php esc_html_e( 'Mobile Card', 'catapult' ); ?></span>
							<span><?php echo esc_html( sprintf( __( '%s Card', 'catapult' ), $card_size ) ); ?></span>
						</div>
					<?php endfor; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
