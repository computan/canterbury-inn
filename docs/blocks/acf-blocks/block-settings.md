[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/acf-blocks/README.md) | [Next Article →](/docs/blocks/acf-blocks/innerblocks.md)

# Block Settings
All ACF Blocks begin with file comments that control the settings used when registering that block type.

## Required Settings
 * `Title` (readable text) - The display title for the block. The block directory should be the slug version of this.
 * `Description` (readable text) - A description of the block for reference in the editor.
 * `Instructions` (readable text or HTML) - optional block instructions that will appear in the block sidebar. Should be used if a block needs explanation.
 * `Category` (readable text) - The display text for the block category in the editor and on the Block Library archive. Block library posts will automatically be categorized based on this.
 * `Icon` (slug) - A [WordPress dashicon ↗](https://developer.wordpress.org/resource/dashicons/) that represents the block.
 * `Keywords` (CSV) - comma-separated list of descriptive word(s) to allow searching by keyword in the editor.
 * `Active` (boolean) - a true or false value whether the block is available in the [Block Library](/docs/blocks/block-library.md). Should be set to `true` if the block is available.

## Frequently Used Optional Settings
 * `Post Types` (CSV) - comma-separated list of post type slugs to limit the block to usage only in certain post types. Leave blank to allow usage everywhere.
 * `Multiple` (boolean) - whether a block can be added multiple times within a single post. Set to `true` to allow multiple instances.
 * `CSS Deps` (CSV) - comma-separated list of CSS style handles registered with [wp_enqueue_style ↗](https://developer.wordpress.org/reference/functions/wp_enqueue_style/). These will be automatically loaded when the current block's styles are loaded. Requires a `style.scss` file. InnerBlock styles do not need to be added. Other block styles begin with an `acf/` prefix to match the Gutenberg block name. Components do not have a prefix or suffix and just match the directory name of the component.
 * `JS Deps` (CSV) - comma-separated list of CSS script handles registered with [wp_enqueue_script ↗](https://developer.wordpress.org/reference/functions/wp_enqueue_script/). These will be automatically loaded when the current block's scripts are loaded. Requires a `script.js` file. InnerBlock scripts do not need to be added. Other block scripts begin with an `acf/` prefix to match the Gutenberg block name. Components do not have a prefix or suffix and just match the directory name of the component.
 * `Global ACF Fields` (CSV) - comma-separated list to automatically add some commonly-used ACF fields to the block (all optional):
	* `image` - an ACF image field for use within the block content. Must be manually added to the block.php file.
	* `scroll_id` - a ACF text field that gets added as the block's ID via the `$content_block->get_block_id_attr()` function.
	* `background_image` - an ACF image field that can be automatically added to the block as long as the `$content_block->get_block_background_image_and_video()` function is used to output the image. See [Hero-Display block.php file](/themes/catapult/blocks/acf-blocks/hero-display/block.php) for an example. If the image is purely decorative, be sure to set the third attribute of the function to `true` which will add an `aria-hidden="true"` to the background image. Generally, hero blocks should have this set to false and all other blocks set to true.
	* `background_video` - works the same way as the `background_image` option.
	* `video` - an ACF oEmbed field that can be automatically added to the block as long as the `$content_block->get_block_image_and_video()` function is used to output the video. See [Content-Side-Image.php file](/themes/catapult/blocks/acf-blocks/content-side-image/block.php) for an example.
	* `background_color` - a background color class that gets added to the block's classes via the `$content_block->get_block_classes()` function. Controls spacing between blocks of the same or different background colors. If used, make sure alternate background colors still display with appropriate font colors. Options are automatically generated from `$background-colors` variable in the [_variables.scss file](/themes/catapult/css/__base-includes/_variables.scss).
 * `Default BG Color` (string) - when the `background_color` setting is used, sets a default initial value for the ACF field.
 * `Background Colors` (CSV) - automatically limits the `background_color` Global ACF field to a subset of the colors from the `$background-colors` sass variable. If a block has background color variations in the design system, this setting should be used to add the options.
 * `InnerBlocks` (boolean) - whether the block contains [InnerBlocks](/docs/blocks/acf-blocks/innerblocks.md). Set to `true` to enable InnerBlocks.
 * `Parent` (CSV) - comma-separated list of block slugs to limit the block to usage only as an InnerBlock as a direct descendant of the the specified block(s). Leave blank to allow usage everywhere.
 * `Ancestor` (CSV) - comma-separated list of block slugs to limit the block to usage only as an InnerBlock descendant of the specified block(s). Leave blank to allow usage everywhere.
 * `Context` (CSV) - specifies a custom [context ↗](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-context/) for the current block. Used to access ACF field data from parent blocks.
 * `Styles` (CSV) - comma-separated list of readable names to automatically register Gutenberg [Block Styles ↗](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/) for the current block. This option appears in the sidebar and adds a style slug to the block. Used for style variations of the same block.

## Additional Optional Settings

 * `Mode` (string) - the ACF display mode for the block. Available settings are:
	* `preview` (default and recommended) - displays the rendered block preview with ACF fields in the sidebar. This is the best option for most blocks, especially for blocks with InnerBlocks.
	* `auto` - displays the preview when not editing the block, and the ACF fields when the block is selected. Not recommended for blocks with InnerBlocks.
	* `edit` - always displays the ACF fields and doesn't display the rendered block. Not recommended.
 * `Wrap InnerBlocks` (boolean) - By default, the InnerBlocks element is wrapped with a `div` and given a class name. This element is required by the editor to ensure the frontend matches the editor and styles are applied consistently. However, occasionally this element needs to be removed, and this option can be set to `false` which will disable this wrapper on the frontend. Use sparingly.
 * `Image Size` - a single registered image size (see [Image Sizes](/docs/best-practices/images.md)). When used, any `core/image` block within this block's InnerBlocks will automatically be forced to use the specified image size to avoid performance issues with images larger than the specified area.
 * `Image Wrapper` - a boolean value. If true, wraps the the `img` element within the `core/image` block with a div with an `.image-wrapper` class.
 * `Text Width Styles` - boolean true/false. If true, any `core/paragraph` or `core/heading` inner blocks will have full, narrow, and wide block styles available. In order for these styles to work, the InnerBlock component needs to have the `content-wrapper` class added to it.
 * `Starts With Text` - boolean true/false. Blocks that start with text have slightly less spacing than the standard `$block-spacing` value. This option should be set to true for these blocks, which automatically adjusts the block spacing so it uses `$block-spacing-with-text` as the top padding value, or the negative margin `$block-spacing-first-text-child-offset` offset if the block follows another block of the same color.
 * `Button Styles` (CSV) - comma-separated list of readable names to automatically register Gutenberg [Block Styles ↗](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/) for any `core/button` blocks that are an immediate child of the current block. Used on blocks like the accordion, tab, and navigation blocks to limit the types of styles available.
 * `CSS Custom Props` (CSV) - a comma-separated list of ACF field names that should be added to the block as CSS Custom Properties. Can also add a colon followed by a default value. For example: `CSS Custom Props:  column_width_mobile: 1, column_width_desktop: 2` will add this to the block via the `$content_block->get_block_style_attr()` function: `style="--column_width_mobile: 1; --column_width_desktop: 2"`. This setting should be used for any CSS custom properties that need to be available in the editor in addition to the frontend.
