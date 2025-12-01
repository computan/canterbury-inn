<?php
/**
 * The search result partial.
 *
 * @category Template
 * @package Catapult
 * @since   3.1.2
 */

$args = wp_parse_args(
	$args,
	array(
		'title'     => '',
		'url'       => '',
		'content'   => '',
		'img'       => '',
		'post_type' => '',
	)
);
?>

<div class="search-result <?php echo empty( $args['img'] ) ? 'search-result--no-image' : ''; ?>">
	<div class="search-result__text">
		<?php if ( ! empty( $args['post_type'] ) ) : ?>
			<div class="search-result__post-type"><?php echo wp_kses_post( $args['post_type'] ); ?></div>
		<?php endif; ?>
		<h2 class="search-result__title">
			<a href="<?php echo esc_url( $args['url'] ); ?>" aria-label="<?php echo wp_kses_post( $args['title'] ); ?>"><?php echo wp_kses_post( $args['title'] ); ?></a>
		</h2>
		<div class="search-result__excerpt"><?php echo wp_kses_post( $args['content'] ); ?></div>
	</div>
	<?php if ( ! empty( $args['img'] ) ) : ?>
		<div class="search-result__image">
			<a href="<?php echo esc_url( $args['url'] ); ?>" aria-label="<?php echo wp_kses_post( $args['title'] ); ?>"><?php echo wp_kses_post( $args['img'] ); ?></a>
		</div>
	<?php endif; ?>
</div>
