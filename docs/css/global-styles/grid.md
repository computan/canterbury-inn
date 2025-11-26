[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/global-styles/README.md) | [Next Article →](/docs/css/global-styles/fonts.md)

# Global Styles: Grid
The Catapult Design System and WordPress Framework styles are divided into a 12-column grid constrained within a container with width limits and responsive padding.

The Bootstrap 4 Grid classes are used when utility classes are needed to set the grid size. [Full documentation of these classes is here ↗](https://getbootstrap.com/docs/4.3/layout/grid/).

The most commonly used classes are:

* `container` - used to constrain content within a the grid container width. The parent element must be the full viewport width for this class to work.
* `row` - another element with this class is needed to provide the correct negative margins when any of the column classes are used.
* `col-XX` - an element directly inside a `row` with a specified column width. Can also have breakpoints added.

For example:

```
<div class="container">
	<div class="row">
		<div class="col-12 col-md-6"></div>

		<div class="col-12 col-md-6"></div>
	</div>
</div>
```

This will create a block that is constrained into the grid with two half-width columns that go down to full width below the md breakpoint.

## Using CSS Grid
Alternatively, the `grid()` mixin can be used on an element to create a CSS Grid with 12 columns of the same size (still needs to live inside a `container` element to be contrainsted to the container width). This will create the same sized columns as the previous example:

PHP:
```
<div class="container custom-element">
	<div class="custom-element__column"></div>

	<div class="custom-element__column"></div>
</div>
```

SCSS:
```
.custom-element {
	@include grid();

	&__column {
		grid-column: 1 / span 12;

		@include media-breakpoint-up(md) {
			grid-column: auto / span 6;
		}
	}
}
```