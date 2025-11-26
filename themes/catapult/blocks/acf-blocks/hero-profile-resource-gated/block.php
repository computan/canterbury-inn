<?php
/**
 * Hero-Profile-Resource-Gated
 *
 * Title:             Hero-Profile-Resource-Gated
 * Description:       Hero section with side form to unlock gated content.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, contact, profile, resource, gated, content
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Image Size:        col-8
 * Starts With Text:
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array(
	'core/heading',
	'core/paragraph',
	'core/button',
	'core/html',
	'contact-form-7/contact-form-selector',
	'acf/form-disclaimer',
	'acf/form-item',
	'acf/post-tags-share',
	'acf/share-buttons',
	'acf/form',
	'acf/content',
);

$template = array(
	array(
		'acf/content',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
		array(
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add text or additional blocks here.', 'catapult' ),
				),
			),
		),
	),
	array(
		'acf/form',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
		array(
			array(
				'core/heading',
				array(
					'level'       => 2,
					'placeholder' => __( 'Add heading here.', 'catapult' ),
				),
			),
			array(
				'contact-form-7/contact-form-selector',
				array(
					'label' => 'Select a Contact Form',
				),
			),
			array(
				'acf/post-tags-share',
			),
		),
	),
);

$back_title = '';

if ( catapult_is_block_library() ) {
	$back_title = __( 'All resources', 'catapult' );
}

global $post;
?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block acf-block--has-sidebar block-hero-profile-resource-gated<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link( null, $back_title ); ?>

	<div class="container block-hero-profile-resource-gated__container">
		<?php if ( ! empty( $post ) ) : ?>
			<?php
			if ( catapult_is_block_library() || catapult_is_theme_block() ) {
				$primary_term      = __( 'Category one', 'catapult' );
				$post_title        = __( 'Resource title text placeholder', 'catapult' );
				$featured_image_id = 'placeholder-16-9';
				$date              = __( 'Month DD, YYYY', 'catapult' );
			} else {
				$primary_term      = catapult_get_primary_term( 'category', $post->ID );
				$post_title        = $post->post_title;
				$featured_image_id = get_post_thumbnail_id( $post );
				$date              = get_the_date( 'F j, Y', $post );
			}
			?>

			<div class="block-hero-profile-resource-gated__header">
				<?php if ( ! empty( $primary_term ) ) : ?>
					<div class="block-hero-profile-resource-gated__primary-term"><?php echo wp_kses_post( $primary_term ); ?></div>
				<?php endif; ?>

				<h1 class="block-hero-profile-resource-gated__title"><?php echo esc_html( $post_title ); ?></h1>

				<div class="block-hero-profile-resource-gated__date"><?php echo esc_html( $date ); ?></div>

				<?php if ( ! empty( $featured_image_id ) ) : ?>
					<figure class="block-hero-profile-resource-gated__image-wrapper">
						<?php echo wp_kses_post( wp_get_attachment_image( $featured_image_id, 'col-8', '', array( 'class' => 'block-hero-profile-resource-gated__image' ) ) ); ?>
					</figure>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
	</div>
</section>
