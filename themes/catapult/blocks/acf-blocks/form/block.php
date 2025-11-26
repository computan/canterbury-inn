<?php
/**
 * Form
 *
 * Title:             Form
 * Description:       An inline wrapper for displaying a form.
 * Instructions:
 * Category:          Base
 * Icon:              feedback
 * Keywords:          form, hubspot,cf7, forms, contact, input
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:          hubspot, contact-form-7
 * JS Deps:           hubspot, contact-form-7
 * Global ACF Fields:
 * Background Colors:
 * Default BG Color:
 * InnerBlocks:       true
 * Parent:
 * Context:           acf/hero-donate
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.16
 * @since   3.1.2
 */

$template = array();

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button', 'core/html', 'contact-form-7/contact-form-selector', 'acf/form-disclaimer', 'acf/form-item', 'acf/post-tags-share' );

$content_block = new Content_Block_Gutenberg( $block, $context );

$background_color = 'transparent';

if ( ! empty( $context['hero-donate'] ) ) {
	$background_color = 'dark';
}

?>

<div class="block-form acf-inline-block<?php echo esc_attr( $content_block->get_block_classes( array( 'background_color' => $background_color ) ) ); ?>">
	<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" class="block-form__content" />
</div>
