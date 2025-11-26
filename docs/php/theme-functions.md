[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/php/content-functions.md) | [Next Article →](/docs/gulp/README.md)

# PHP Theme Functions
Some additional functions are also available for use within the theme. There are some of the most commonly used functions:

* `catapult_get_component( $component_name, $data, $folder )` File: [templating.php](/themes/catapult/core/includes/templating.php)
	* This function can include a PHP component from the [blocks/components](/themes/catapult/blocks/components) directory. It can also send data to the component. See the [Component PHP documentation](/docs/blocks/components/component-php.md) for more info. This function and components should generally be used over the older `get_theme_part` function.
