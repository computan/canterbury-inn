[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/breakpoints.md) | [Next Article →](/docs/css/global-styles/README.md)

# Sass Functions/Mixins
There are a number of SCSS mixins and functions. `Mixins` are used to output entire blocks of code, whereas `Functions` are used to output specific property values. Full documentation of the functions and mixins can be found in the respective scss files.

## Functions
The [/themes/catapult/css/__base-includes/functions/_functions.scss](/themes/catapult/css/__base-includes/functions/_functions.scss) file contains a number of helpful SCSS mixins. These most frequently used are:

* `rem()` - Converts an pixel value into a rem value. Can take multiple values. This should always be used instead of raw px values. For example this SCSS:
	```
	margin-top: rem(16);
	padding: rem(16 32 48 64);
	```
	Will output this CSS:
	```
	margin-top: 1rem;
	padding: 1rem 2rem 3rem 4rem;
	```

* `responsive-values()` (can also use `rv()`) - Creates a CSS clamp value that sizes between breakpoints. For example this SCSS:
	```
	padding-top: responsive-values(16, 32);
	```
	Will output a top padding value that starts at 1rem on the smallest screen sizes, then scale up between the `md` and `xxl` breakpoints (`$grid-breakpoints`) until it gets to 2rem:
	```
	padding-top: clamp(1rem, -0.1428571429rem + 2.380952381vw, 2rem);
	```

* `font()` - Outputs a font family value from the $fonts variable. For example this SCSS:
	```
	font-family: font(base);
	```
	Will output this CSS:
	```
	font-family: 'Open Sans', sans-serif;
	```

* `paint()` - Outputs a color value from the $paints or $additional-paints variables. For example this SCSS:
	```
	color: paint(neutral-9);
	```
	Will output this CSS:
	```
	color: #dadee6;
	```

	Note: using `paint(text)` will regenerate a value using a [CSS Custom Property](/docs/css/custom-properties.md) to allow dynamic text color based on the background color of a block. This is recommended whenever styling text unless the color is different from the standard text color.

* `effect()` - Outputs an effect value from the $effects variable. For example this SCSS:
	```
	box-shadow: effect(cards);
	```
	Will output this CSS:
	```
	box-shadow: 0rem 0rem 0.5rem rgba(0, 0, 0, 0.08);
	```

## Mixins
The [/themes/catapult/css/__base-includes/mixins/_mixins.scss](/themes/catapult/css/__base-includes/mixins/_mixins.scss) file contains a number of helpful SCSS mixins. These most frequently used are:

* `headings` - Used to target heading selectors. Generates a large amount of CSS so use sparingly. Using the `.wp-block-heading` class is a better approach to reduce the amount of CSS generated.
* `grid` - Used to add the CSS Grid properties for the 12-column grid.
* `icon` - Used to add properties to an element to make an icon based on the icons in the [/themes/catapult/icons/](/themes/catapult/icons/) directory.
* `responsive-grid` - Generates CSS to fit an element into the 12-column grid. Only works when used on an element where the parent element is the width of the entire page. Can have `--additionalGridOffset` CSS Custom property set on blocks if they need their width/max width adjusted from the default grid size.
* `alt-text-selectors` - Used to target elements with a background color that has a text color different from the standard color.
* `sr-only` - Used to visually hide an element but still allow screen readers to access the element and its contents for accessibility. If an element needs to be hidden from screen readers as well, then the element should ideally be removed, otherwise `display: none` can be used.
* `light-button-styles` - Can be used on a `.wp-block-button` selector to reset its styles to the default light background style (for use on things like cards where the block background could be light/dark and the cards should always use the light button style).

Mixins are always added with the `@include` rule. For example this SCSS:
```
.custom-icon {
	&::before {
		@include icon("facebook");
	}
}
```
Will output this CSS (a pseudo element with a Facebook icon):
```
.custom-icon::before {
	-webkit-mask-size: contain;
	mask-size: contain;
	-webkit-mask-repeat: no-repeat;
	mask-repeat: no-repeat;
	-webkit-mask-position: center;
	mask-position: center;
	-webkit-mask-image: var(--icon-facebook);
	mask-image: var(--icon-facebook);
	content: " ";
	width: 1.5rem;
	height: 1.5rem;
	background-color: currentcolor;
}
```