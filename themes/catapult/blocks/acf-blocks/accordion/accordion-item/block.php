<?php
/**
 * Accordion Item
 *
 * Title:             Accordion Item
 * Description:       Accordion item inner block.
 * Instructions:
 * Category:          Accordion
 * Icon:              icon786-image-accordion
 * Keywords:          show, hide, content, accordion
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Parent:            acf/accordion
 * Styles:            Closed, Open
 * Button Styles:     Accordion
 * Context:           acf/accordion-side-image
 *
 * @package Catapult
 * @since   2.0.0
 * @since   2.2.6
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$block_classes = $content_block->get_block_classes();

$image = get_field( 'image' );

$allowed_blocks = array( 'core/button', 'acf/content' );

$template = array(
	array(
		'core/button',
		array(
			'className' => 'is-style-accordion',
			'lock'      => array(
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
	),
);

?>

<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" templateLock="all" class="block-accordion-item<?php echo esc_attr( $block_classes ); ?>" />

<?php if ( ! empty( $image ) && ! empty( $context ) && ! empty( $context['accordion-side-image'] ) ) : ?>
	<figure class="block-accordion-item__figure">
		<div class="block-accordion-item__image-wrapper image-wrapper">
			<?php echo wp_kses_post( wp_get_attachment_image( $image, 'col-6-square' ) ); ?>
		</div>
	</figure>
<?php endif; ?>
