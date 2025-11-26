[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/slider.md) | [Next Article →](/docs/blocks/react-blocks/README.md)

# Core Blocks
Standard WordPress blocks are called `core blocks`. The Catapult WordPress Framework uses a number of these rather than creating custom ACF Blocks for every component.

## Custom Core Block SCSS/JS
Custom styles and scripts can be added for core blocks in the core blocks directory: [/themes/catapult/blocks/core-blocks/](/themes/catapult/blocks/core-blocks/). A directory matching the core block name should be created, and then `style.scss`, `script.js`, and `editor.js` files can be created for each core block.

## Disallowing Core Blocks
The [catapult_allowed_block_types_all()](/themes/catapult/includes/gutenberg-block-editor/allow-only-certain-core-blocks.php) function is used as a filter to disallow the core blocks that aren't being using in Catapult. On rare occasions, this can be updated to allow additional blocks.
