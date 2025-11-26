[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/global-vs-block-assets.md) | [Next Article →](/docs/best-practices/wcag.md)

# InnerBlocks VS ACF Fields
When creating or editing an [ACF Block](/docs/blocks/acf-blocks/README.md), data can be added to the block in two ways:

1. InnerBlocks
2. ACF Fields

As a general rule, InnerBlocks should be used for text `content` and ACF Fields should be used for `options`.

The goal is for any text that appears in the editor to be able to be clicked and edited exactly where it appears. Any particular options for various blocks will then always appear on the sidebar.

For example, a button should be added as an InnerBlock since it has text content that should be editable when clicking the button. But whether a block of cards should have 3 or 4 cards per row should be added as an ACF Field.

When there are multiple style variations of the same block, the `Styles` [Block Setting](/docs/blocks/acf-blocks/block-settings.md) is the best option.