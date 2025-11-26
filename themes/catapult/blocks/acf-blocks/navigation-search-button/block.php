<?php
/**
 * Navigation-Search-Button
 *
 * Title:             Navigation-Search-Button
 * Description:       A search button for use in the navigation menu.
 * Instructions:
 * Category:          Navigation
 * Icon:              search
 * Keywords:          nav, navigation, header, search, find
 * Post Types:        all
 * Multiple:          true
 * Active:            true
 * CSS Deps:
 * JS Deps:
 * Global ACF Fields:
 * Parent:            catapult/navigation
 * Mode:              preview
 *
 * @package Catapult
 * @since   3.0.0
 * @since   3.0.18
 * @since   3.1.1
 * @since   3.1.2
 */

$content_block = new Content_Block_Gutenberg( $block, $context );

?>

<div class="block-navigation-search-button">
	<button type="button" class="block-navigation-search-button__button" aria-label="<?php esc_html_e( 'Search', 'catapult' ); ?>"></button>

	<div class="block-navigation-search-button__search">
		<form action="/" method="GET" class="block-navigation-search-button__form">
			<input
				type="search"
				class="block-navigation-search-button__input custom-search-input"
				name="s"
				placeholder="<?php esc_html_e( 'Search this website', 'catapult' ); ?>"
				aria-label="<?php esc_html_e( 'Search this website', 'catapult' ); ?>"
				value="<?php echo esc_html( get_search_query() ); ?>"
				required
			/>

			<button class="block-navigation-search-button__submit" type="submit" aria-label="<?php esc_html_e( 'Search this website', 'catapult' ); ?>"></button>

			<button type="button" class="block-navigation-search-button__clear" onclick="var input = this.previousElementSibling.previousElementSibling; input.value = ''; input.focus();"><span class="sr-only"><?php esc_html_e( 'Clear search', 'catapult' ); ?></span></button>
		</form>
	</div>
</div>
