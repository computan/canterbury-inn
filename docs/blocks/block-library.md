[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/file-structure.md) | [Next Article →](/docs/blocks/acf-blocks/README.md)

# Block Library
All the styled blocks for a project make up the Block Library. This is a subset of all the possible blocks (the Block Inventory). The Block Library exists both in the Figma design files and in the Catapult WordPress Framework, and the two should match as closely as possible.

## Block Library Archive Page

All the blocks can be viewed at http://domain-name.local/block-library/ This is an archive of the `Library Block` custom post type which allows filtering, drag-and-drop re-ordering, sharing URLs of custom views, and more.

Each block library post should correspond to a block from the Block Library in Figma. ***When new blocks are added, a new post should be created with the same name.***

## Activating Blocks
The Catapult WordPress framework contains all the blocks that have been developed out of the Block Inventory. However, not all these will be used on every project.

By default, all ACF blocks start deactivated. ***If a block has been included in the Block Library, the developer should activate that block via the [Block Settings](/docs/blocks/acf-blocks/block-settings.md), along with any associated InnerBlocks.***

Unstyled blocks that aren't in the Block Library should remain inactive.

## Local Environment
If the `WP_ENVIRONMENT_TYPE` constant is set to `local`, then all blocks - both active and inactive - will be available in the editor and visible in the block library (with a `Inactive` label). This allows the developer to view the available blocks. However, inactive blocks will not be available or visible on the `staging` or `production` environments, so be sure to set the used blocks to active!

## Using the Block Library for development
The block library is a great place to work on developing blocks. Click the `Hide All` button to hide every block except the one you are working on.

Blocks can also be rearranged to see how the spacing between blocks works. The markup in the block library mirrors the markup used elsewhere on the site, so spacing should remain consistent.

## QA Overlays
This option will overlay images over each block. The images are imported from Figma via the [QA Tasks](/docs/gulp/qa-tasks.md). NOTE: this feature is currently experimental and relies on the Figma file being structured correctly. Many blocks will have missing images, or the images won't match exactly.