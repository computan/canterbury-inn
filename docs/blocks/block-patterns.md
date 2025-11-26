[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/templates.md) | [Next Article →](/docs/blocks/theme-blocks.md)

# Block Patterns
Block patterns are a core WordPress feature that allow setting predefined arrangements/layouts of blocks as well as syncing the content of blocks for reusable global blocks used across multiple posts. When creating a block pattern, it can either be set to sync or not.

## Synced Patterns
Synced patterns (previously called Reusable Blocks before WordPress 6.3), allow saving a block or group of blocks that can then be displayed elsewhere on the site. This allows reusing of the same content in multiple places across the site. If a synced pattern is changed in one location, that same content will be changed everywhere else it is used.

[Read more about Synced Patterns here ↗](https://wordpress.org/documentation/article/reusable-blocks/)

## Unsynced Patterns
Unlike synced patterns, once an unsynced pattern is added to a page/post, it can then be changed without changing the original pattern. It is just a starting point for the creation of pages/posts.

The Catapult WordPress Framework adds the `Block Patterns` custom post type in the dashboard which allows the creation of block patterns via the editor. This is often used by the Computan production team to create arrangements of blocks frequently used across the site.

[Read more about Block Patterns here ↗](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/)