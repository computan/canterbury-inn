<?php
/**
 *  Navigation-Contact-Information
 *
 * Title:             navigation-contact-information
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
<div class="block-navigation-contact-information">
	<div class="block-navigation__utility-nav container">
		<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" class="utility-nav__link " />		
	</div>
</div>
