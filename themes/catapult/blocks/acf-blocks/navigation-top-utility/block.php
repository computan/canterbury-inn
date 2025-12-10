<?php
/**
 *  Navigation-Top-Utility
 *
 * Title:             Navigation-Top-Utility
 * Description:       A row of icons for use in the navigation menu at top.
 * Instructions:
 * Category:          Navigation
 * Icon:              editor-ul
 * Keywords:          nav, navigation, links, header, top, utility
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Parent:
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.18
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

$allowed_blocks = catapult_text_blocks();
?>
<div class="block-navigation-top-utility bg-dark">
	<div class="block-navigation__utility-nav container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="utility-nav__link <?php echo esc_attr( $content_block->get_block_classes() ); ?>" />		
	</div>
</div>
