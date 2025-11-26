<?php
/**
 * Creates admin page with usage stats for ACF Gutenberg Blocks.
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.1.6
 */

namespace Catapult\AcfUsedBlocks;

/**
 * Registers meta box on stats page.
 */
function register_acf_metabox() {
	if ( ! acf_is_screen( 'theme-options_page_acf-options-used-blocks' ) ) {
		return;
	}

	add_meta_box( 'currently-used-page-blocks', __( 'Theme Options: Used Blocks', 'catapult' ), 'Catapult\AcfUsedBlocks\display_acf_metabox', 'acf_options_page', 'normal' );
}
add_action( 'acf/input/admin_head', 'Catapult\AcfUsedBlocks\register_acf_metabox' );

/**
 * Generate the markup for the stats page.
 */
function display_acf_metabox() {
	$used_page_blocks = array();

	$args = array(
		'post_type'      => get_post_types(),
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'orderby'        => 'title',
		'order'          => 'ASC',
	);

	$post_ids = get_posts( $args );

	if ( ! empty( $post_ids ) ) : ?>
		<div class="used-blocks">
			<div>
				<h3 class="used-blocks__heading"><?php esc_html_e( 'Block Occurences', 'catapult' ); ?></h3>

				<?php if ( ! empty( $post_ids ) ) : ?>
					<?php foreach ( $post_ids as $post_id ) : ?>
						<?php if ( has_blocks( $post_id ) ) : ?>
							<?php $used_page_blocks = get_post_block_name_lists( $post_id, $used_page_blocks ); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<?php
			sort( $used_page_blocks );
			$used_page_blocks_count = array_count_values( $used_page_blocks );
			?>

			<div class="used-blocks__block-count">
				<h3 class="used-blocks__heading"><?php esc_html_e( 'Block Count', 'catapult' ); ?></h3>

				<table>
					<?php foreach ( $used_page_blocks_count as $key => $block_count ) : ?>
						<tr>
							<td>
								<?php echo esc_html( $key ); ?>: 
							</td>

							<td>
								<strong><?php echo esc_html( $block_count ); ?></strong><br />
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<?php
	endif;
}


/**
 * Generate the markup for the list of blocks and returns the names of the used blocks.
 *
 * @param int   $post_id   The ID of the post.
 * @param array $used_page_blocks   The names of the currently used blocks.
 */
function get_post_block_name_lists( $post_id, $used_page_blocks ) {
	$content     = get_post_field( 'post_content', $post_id );
	$blocks      = parse_blocks( $content );
	$block_types = acf_get_block_types();
	$block_count = count( $used_page_blocks );

	ob_start();

	if ( ! empty( $blocks ) ) :
		?>
		<ul class="used-blocks__list">
			<?php foreach ( $blocks as $block ) : ?>
				<?php if ( ! empty( $block['blockName'] ) ) : ?>
					<?php if ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) ) : ?>
						<li class="used-blocks__list-item">
							<?php $used_page_blocks[] = get_the_title( $block['attrs']['ref'] ) . __( ' (Block Pattern)', 'catapult' ); ?>

							<?php echo esc_html( get_the_title( $block['attrs']['ref'] ) ); ?><span class="used-blocks__block-pattern-label"><?php esc_html_e( ' (Block Pattern)', 'catapult' ); ?></span>

							<?php $used_page_blocks = get_post_block_name_lists( $block['attrs']['ref'], $used_page_blocks ); ?>
						</li>
					<?php elseif ( false !== strpos( $block['blockName'], 'acf/' ) ) : ?>
						<li class="used-blocks__list-item">
							<?php if ( ! empty( $block_types[ $block['blockName'] ] ) && ! empty( $block_types[ $block['blockName'] ]['title'] ) ) : ?>
								<?php $used_page_blocks[] = $block_types[ $block['blockName'] ]['title']; ?>

								<?php echo esc_html( $block_types[ $block['blockName'] ]['title'] ); ?>
							<?php endif; ?>
						</li>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php
	endif;

	$content = ob_get_contents();
	ob_end_clean();

	if ( $block_count < count( $used_page_blocks ) ) :
		?>
		<?php if ( 'wp_block' !== get_post_type( $post_id ) ) : ?>
			<h4 class="used-blocks__heading">
				<?php echo esc_html( get_the_title( $post_id ) ); ?> (<a href="<?php echo esc_url( get_edit_post_link( $post_id ) ); ?>"><?php esc_html_e( 'Edit', 'catapult' ); ?></a>) (<a target="_blank" href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>"><?php esc_html_e( 'View', 'catapult' ); ?></a>)
			</h4>
		<?php endif; ?>

		<?php echo $content; //phpcs:ignore ?>

		<?php
	endif;

	return $used_page_blocks;
}
