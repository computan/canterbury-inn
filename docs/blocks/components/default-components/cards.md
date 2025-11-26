[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/background-video.md) | [Next Article →](/docs/blocks/components/default-components/filters.md)

# Cards
Post type cards are used throughout the site within various blocks. Because of this, they exist as separate components that can be included where needed and reduce the need for duplicating the same code. The following post type cards currently exist:

* Attachment (media)
* Post
* Post-Featured
* News
* Resource

Some cards can have their heading level set using an ACF field on the filter blocks. When using components, always make sure the headings are hierarchical with the other blocks on the page so that no heading level gets skipped for SEO purposes.

Additional cards can be added in the [catapult/blocks/components](/themes/catapult/blocks/components/) directory and should follow the basic structure of the existing card components.