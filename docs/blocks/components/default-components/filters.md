[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/cards.md) | [Next Article →](/docs/blocks/components/default-components/gravity-forms.md)

# Filters
There are several components that are used across the different filter blocks. These should all be included as CSS Deps and JS Deps in the [Block Settings](/docs/blocks/acf-blocks/block-settings.md) for any block that needs them. These are the shared components:

## [Filters](/themes/catapult/blocks/components/filters)
This is a CSS and JS-only component. This contains the primary shared logic and styling used for handling the frontend filtering functionality. The JS file exports the `Filters` class for use as an import within the JS on each filter block.

## [Filter-No-Results](/themes/catapult/blocks/components/filter-no-results)
This is a PHP and CSS component that displays on the filter blocks when no results are found.

## [Filter-Pagination](/themes/catapult/blocks/components/filter-pagination)
This is a PHP and CSS component for the filter block pagination. The CSS is also used for the search results pagination.

## [Filter-Sort](/themes/catapult/blocks/components/filter-sort)
This is a PHP and CSS component for the filter block sort by functionality.