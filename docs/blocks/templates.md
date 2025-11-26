[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/react-blocks/README.md) | [Next Article →](/docs/blocks/block-patterns.md)

# Templates
The [/themes/catapult/blocks/templates/](/themes/catapult/blocks/templates/) directory can be used to add custom styles or scripts that get loaded only on specific templates to help reduce the number of global styles.

If the name of the template directory matches the name of [current template ↗](https://developer.wordpress.org/themes/basics/template-hierarchy/), then these styles and scripts will be automatically enqueued for that template.

For example, the [/themes/catapult/blocks/templates/search/style.scss](/themes/catapult/blocks/templates/search/style.scss) file generates CSS that automatically gets loaded when the [search.php](/themes/catapult/single.php) template is loaded.