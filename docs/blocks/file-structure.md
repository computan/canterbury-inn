[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/README.md) | [Next Article →](/docs/blocks/block-library.md)

# Block File Structure
The following is an overview of the directories and files found in the blocks directory.

* [/themes/catapult/blocks/](/themes/catapult/blocks/) - the main directory for all blocks, components, and non-global asset source files.
	* [/acf-blocks/BLOCK-NAME/](/themes/catapult/blocks/acf-blocks/) - where ACF blocks are created/modified.
		* `block.php` - controls the block's settings and rendering template.
		* `style.scss` (optional) - for custom block styles.
		* `editor.scss` (optional) - for styles loaded within the Block Editor.
		* `script.js` (optional) - for custom block frontend scripts.
		* `editor.js` (optional) - for custom block editor scripts.
	* [/components/COMPONENT-NAME/](/themes/catapult/blocks/components/) - components or SCSS/JS asset source code that need to be used in multiple places on the site but aren't their own Gutenberg blocks.
		* `COMPONENT-NAME.php` (optional) - file that can be included where needed to display the component.
		* `style.scss` (optional) - for custom component styles.
		* `script.js` (optional) - for custom component scripts.
	* [/core-blocks/BLOCK-NAME/](/themes/catapult/blocks/core-blocks/) - For adding styles or functionality to existing core Gutenberg blocks.
		* `style.scss` (optional) - for custom block styles.
		* `editor.scss` (optional) - for styles loaded within the Block Editor.
		* `script.js` (optional) - for custom block frontend scripts.
		* `editor.js` (optional) - for custom block editor scripts.
	* [/react-blocks/BLOCK-NAME/](/themes/catapult/blocks/react-blocks/) - this is used for blocks built as Gutenberg React blocks. This follows the [same basic structure ↗](https://developer.wordpress.org/block-editor/getting-started/fundamentals/file-structure-of-a-block/) of native Gutenberg blocks. The only difference is the build directory structure and process in Catapult is slightly different.
		* `block.json` - this contains all the information about a block that is normally included in the `block.php` file in the ACF blocks. The available metadata can be found [here ↗](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/).
		* `index.js` - The `index.js` file (as defined in the `editorScript` property of `block.json`) is the entry point file for JavaScript that only gets loaded in the Block Editor. It’s responsible for calling the `registerBlockType` function to register the block on the client and typically imports the `edit.js` and `save.js` files to get the functions required for block registration.
		* `edit.js` - The `edit.js` file contains the React component responsible for rendering the block’s editing user interface, allowing users to interact with and customize the block’s content and settings in the Block Editor. This component gets passed to the `edit` property of the `registerBlockType` function in the `index.js` file.
		* `save.js` - The `save.js` exports the function that returns the static HTML markup that gets saved to the WordPress database. This function gets passed to the `save` property of the `registerBlockType` function in the `index.js` file.
		* `view.js` - The `view.js` (as defined in the `viewScript` property of `block.json`) file will be loaded in the front end when the block is displayed.
		* `style.scss` (optional) - for custom block styles.
		* `editor.scss` (optional) - for styles loaded within the Block Editor.
	* [/templates/TEMPLATE-NAME/](/themes/catapult/blocks/templates/) - if the TEMPLATE-NAME directory matches the name of the [current template ↗](https://developer.wordpress.org/themes/basics/template-hierarchy/) then these styles and scripts will be automatically enqueued for that template.
		* `style.scss` (optional) - for custom block styles.
		* `script.js` (optional) - for custom block frontend scripts.