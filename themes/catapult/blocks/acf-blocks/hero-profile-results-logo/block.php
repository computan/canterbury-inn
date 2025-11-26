<?php
/**
 * Hero-Profile-Results-Logo
 *
 * Title:             Hero-Profile-Results-Logo
 * Description:       Hero section with side membership form.
 * Instructions:
 * Category:          Hero
 * Icon:              align-full-width
 * Keywords:          hero, membership, member, tour, form
 * Post Types:        all
 * Multiple:          true
 * Active:            false
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Styles:
 * Starts With Text:
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = array( 'acf/content', 'acf/logos' );

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
				'core/heading',
				array(
					'level'       => 1,
					'placeholder' => __( 'Add heading here.', 'catapult' ),
				),
			),
			array(
				'acf/hero-details',
			),
		),
	),
	array(
		'acf/logos',
		array(
			'lock' => array(
				'move'   => true,
				'remove' => true,
			),
		),
	),
);

if ( catapult_is_block_library() ) {
	$back_title = __( 'All results', 'catapult' );
}

?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-hero-profile-results-logo<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<?php catapult_the_back_link( null, $back_title ); ?>

	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="container block-hero-profile-results-logo__container" />
</section>
