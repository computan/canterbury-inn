[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/figma-variables.md) | [Next Article →](/docs/css/breakpoints.md)

# Sass Variables
The [/themes/catapult/css/__base-includes/_variables.scss](/themes/catapult/css/__base-includes/_variables.scss) file is where the majority of the theme's global variables are set. This file has its own documentation about each variable.

The variables that are most frequently changed on projects and should be checked at the start of every project are:

* `$fonts`
* `$background-colors`
* `$background-font-colors`
* `$background-font-hover-colors`
* `$backgrounds-with-alt-text-color`
* `$block-spacing`

The variables that are most frequently used are:

* `$transition-standard` (default: 0.2s ease-in-out) - this sets the duration and easing and can be used throughout the theme for consistent transition speeds. For example:

	```
	.element {
		transition: color $transition-standard;
	}
	```

	* Note: avoid using `all` for the transition property - this can cause performance issues. Instead, use multiple transition properties:

	```
	.element {
		transition: color $transition-standard, opacity $transition-standard;
	}
	```
* `$block-spacing` - this is the standard block spacing value defined in Figma. It automatically gets added as padding to ACF blocks. The top padding will be removed on blocks that follow a block of the same color. This variable occasionally needs to be used to manually set the spacing for non-standard blocks.
* `$grid-gap` - this is the value of the column gap within the column grid.
