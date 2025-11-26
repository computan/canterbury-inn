[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/error-checking.md) | [Next Article →](/docs/best-practices/inner-blocks-vs-acf-fields.md)

# Global vs Block Assets
Creating a CSS style or JS script can be done in one of two ways:

1. Global styles/scripts that run on every page of the site.
2. Block-specific styles/scripts that run only when that particular block is used on a page. This includes all the components in the [/blocks/ directory](/docs/blocks/file-structure.md)

Generally, almost all styles/scripts should be added to blocks to avoid unnecessary code loading on every single page of the site. Exceptions to this are things like the fonts, colors, grid, or any other elements that load everywhere. Some [global styles are documented here](/docs/css/global-styles/README.md).

Styles for things like a blog archive should only load on the blog archive, single post styles should only load on the single post of the same type, etc. [Template Styles](/docs/blocks/templates.md) can often be used for instances like these.