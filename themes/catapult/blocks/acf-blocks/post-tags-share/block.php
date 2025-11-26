<?php
/**
 * Post-Tags-Share
 *
 * Title:             Post-Tags-Share
 * Description:       Tag and social media link section for use on single post footer.
 * Instructions:
 * Category:          Post
 * Icon:              admin-post
 * Keywords:          blog, posts, profile, tag, tags, social, media
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          share-icons
 * JS Deps:           add-to-any
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       false
 * Mode:              preview
 * Styles:
 * Context:
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.0.17
 * @since   3.0.19
 * @since   3.1.0
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

global $post;

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-post-tags-share<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php if ( ! empty( $post ) ) : ?>
		<?php
		$tags = wp_get_post_tags( $post->ID );

		if ( catapult_is_block_library() || catapult_is_theme_block() ) {
			$tags = array();

			for ( $i = 0; $i < 6; $i++ ) {
				$number_words = array(
					__( 'One', 'catapult' ),
					__( 'Two', 'catapult' ),
					__( 'Three', 'catapult' ),
					__( 'Four', 'catapult' ),
					__( 'Five', 'catapult' ),
					__( 'Six', 'catapult' ),
				);

				$fake_term           = new stdClass();
				$fake_term->name     = sprintf( 'Tag %s', $number_words[ $i ] );
				$fake_term->term_id  = 1;
				$fake_term->slug     = sprintf( 'tag-%s', $number_words[ $i ] );
				$fake_term->taxonomy = 'post_tag';
				$tags[]              = $fake_term;
			}
		}
		?>

		<div class="container block-post-tags-share__container">
			<div class="block-post-tags-share__share-column">
				<div class="block-post-tags-share__share-content">
					<div class="block-post-tags-share__tags-title"><?php esc_html_e( 'Share', 'catapult' ); ?></div>

					<?php catapult_get_component( 'share-icons' ); ?>
				</div>
			</div>

			<div class="block-post-tags-share__tags-column">
				<?php if ( ! empty( $tags ) ) : ?>
					<div class="block-post-tags-share__tags-title"><?php esc_html_e( 'Tags', 'catapult' ); ?></div>

					<div class="block-post-tags-share__tags">
						<?php foreach ( $tags as $item ) : ?>
							<a class="block-post-tags-share__tag" href="<?php echo esc_url( get_tag_link( $item ) ); ?>"><?php echo esc_html( $item->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
