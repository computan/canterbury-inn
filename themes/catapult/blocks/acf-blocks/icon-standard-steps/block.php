<?php
/**
 * Icon-Standard-Steps
 *
 * Title:             Icon-Standard-Steps
 * Description:       Standard Steps with multiple styles.
 * Instructions:
 * Category:          Icon
 * Icon:              align-pull-right
 * Keywords:          icon, standard, steps, heading
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields: scroll_id
 * Background Colors: blue-tint-b
 * Default BG Color:  blue-tint-b
 * InnerBlocks:       true
 * Styles:            Default, Grid-Top
 * Starts With Text:  true
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 */

$content_block  = new Content_Block_Gutenberg( $block, $context );
$allowed_blocks = catapult_text_blocks( array( 'acf/cards' ) );

$template = array(
	array(
		'core/columns',
		array(),
		array(
			array(
				'core/column',
				array(
					'width' => '100%',
				),
				array(
					array(
						'core/heading',
						array(
							'level'       => 1,
							'placeholder' => 'Add heading here.',
							'fontSize'    => 't2',
						),
					),
					array(
						'core/paragraph',
						array(
							'placeholder' => 'Add text or additional blocks here.',
							'fontSize'    => 'body-1',
						),
					),
					array(
						'core/button',
						array(
							'className'   => 'is-style-primary',
							'placeholder' => 'Add text...',
						),
					),
				),
			),
		),
	),
	array(
		'acf/cards',
		array(),
		array(
			array(
				'acf/icon-step-cards',
			),
		),
	),
);
?>

<section <?php echo wp_kses_post( $content_block->get_block_id_attr() ); ?><?php echo wp_kses_post( $content_block->get_block_style_attr() ); ?>class="acf-block block-icon-standard-steps<?php echo esc_attr( $content_block->get_block_classes() ); ?>">
	<div class="container block-icon-standard-steps__container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-icon-standard-steps__content" />
	</div>
</section>
