<?php
/**
 * Hero-Profile-Staff
 *
 * Title:             Hero-Profile-Staff
 * Description:       A hero section for staff posts with a sidebar and content.
 * Instructions:
 * Category:          Hero
 * Icon:              cover-image
 * Keywords:          hero, staff, sidebar, people, team, employee, leader
 * Post Types:        all
 * Multiple:          false
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Starts With Text:
 * Wrap InnerBlocks:  false
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.14
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 * @since   3.1.3
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();

if ( catapult_is_block_library() ) {
	$content_block->replace( 'Hero-Profile-Staff', 'Name Surname' );
	$job_title         = __( 'Job title goes here', 'catapult' );
	$featured_image_id = 'placeholder-1-1';
} else {
	$job_title         = get_field( 'job_title' );
	$featured_image_id = get_post_thumbnail_id();
}

$template = array(
	array(
		'core/post-title',
		array(
			'level' => 1,
			'lock'  => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
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
				'core/buttons',
				array(),
				array(
					array(
						'core/button',
						array(
							'className'  => 'is-style-social',
							'buttonIcon' => 'icon-linkedin',
						),
					),
					array(
						'core/button',
						array(
							'className'  => 'is-style-social',
							'buttonIcon' => 'icon-email',
						),
					),
				),
			),
			array(
				'acf/contact-item',
				array(),
				array(
					array(
						'core/heading',
						array(
							'level'   => 2,
							'content' => __( 'Qualifications.', 'catapult' ),
						),
					),
					array(
						'core/paragraph',
						array(
							'placeholder' => __( 'Add text here.', 'catapult' ),
						),
					),
				),
			),
			array(
				'acf/contact-item',
				array(),
				array(
					array(
						'core/heading',
						array(
							'level'   => 2,
							'content' => __( 'Affiliations.', 'catapult' ),
						),
					),
					array(
						'core/list',
					),
				),
			),
		),
	),
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
);

$back_title = '';

if ( catapult_is_block_library() ) {
	$back_title = __( 'All staff', 'catapult' );
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-staff<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link( null, $back_title ); ?>

	<div class="block-hero-profile-staff__container container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />

		<?php if ( ! empty( $featured_image_id ) ) : ?>
			<figure class="block-hero-profile-staff__image-wrapper image-wrapper">
				<?php echo wp_kses_post( wp_get_attachment_image( $featured_image_id, 'col-3-square', '', array( 'class' => 'block-hero-profile-staff__image' ) ) ); ?>
			</figure>
		<?php endif; ?>

		<?php if ( ! empty( $job_title ) ) : ?>
			<h2 class="block-hero-profile-staff__job-title">
				<?php echo wp_kses_post( $job_title ); ?>
			</h2>
		<?php endif; ?>

		<nav class="block-hero-profile-staff__navigation" aria-label="<?php esc_html_e( 'Pagination', 'catapult' ); ?>">
			<?php if ( catapult_is_block_library() ) : ?>
				<a href="#" rel="prev"><span class="icon icon-arrow-left"></span><span><?php echo esc_html_e( 'Prev', 'catapult' ); ?></span></a>

				<a href="#" rel="next"><span><?php echo esc_html_e( 'Next', 'catapult' ); ?></span><span class="icon icon-arrow-right"></span></a>
			<?php else : ?>
				<?php next_post_link( '%link', '<span class="icon icon-arrow-left"></span><span>' . __( 'Prev', 'catapult' ) . '</span>' ); ?>

				<?php previous_post_link( '%link', '<span>' . __( 'Next', 'catapult' ) . '</span><span class="icon icon-arrow-right"></span>' ); ?>
			<?php endif; ?>
		</nav>
	</div>
</section>
