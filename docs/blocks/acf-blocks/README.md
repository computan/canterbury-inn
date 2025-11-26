[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/block-library.md) | [Next Article →](/docs/blocks/acf-blocks/block-settings.md)

# ACF Blocks
The majority of blocks in Catapult are created as ACF Blocks and can be found in the [/themes/catapult/blocks/acf-blocks/](/themes/catapult/blocks/acf-blocks/) directory.

These blocks use the [Advanced Custom Fields Pro ↗](https://www.advancedcustomfields.com/) (ACF) plugin to register blocks along with a custom templating system to allow the creation of Gutenberg blocks with PHP.

Each block has a `block.php` which contains the primary HTML output along with the [Block Settings](/docs/blocks/acf-blocks/block-settings.md) for that block.

Each block can also optionally have a `style.scss` file to add custom CSS for that block, along with optional `script.js` and `editor.js` files for custom JavaScript.

ACF Blocks directories can also contain one level of nested directories to add additional blocks for better file organization. Nested directories are structured the same way.

* [Block Settings](/docs/blocks/acf-blocks/block-settings.md)
* [InnerBlocks](/docs/blocks/acf-blocks/innerblocks.md)