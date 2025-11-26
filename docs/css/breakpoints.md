[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/variables.md) | [Next Article →](/docs/css/functions-mixins.md)

# Breakpoints
The Bootstrap 4 breakpoint mixins are used to specify breakpoint-specific CSS rather than use @media rules. This ensures the breakpoints are consistent with the [$grid-breakpoints variable](/themes/catapult/css/__base-includes/_variables.scss).

[Full documentation of these mixins is here ↗](https://getbootstrap.com/docs/4.3/layout/overview/#responsive-breakpoints).

The most commonly used mixins are:

* `@include media-breakpoint-up(sm) { ... }` - this the mixin that should be used in most cases. It will apply styles to screens larger than the specified breakpoint. Mobile styles should typically be added first, then use this breakpoint to add styles for increasingly larger screens.
* `@include media-breakpoint-down(sm) { ... }` - use this mixin sparingly. This will apply styles to screens smaller than the specified breakpoint.
* `@include media-breakpoint-between(sm, md) { ... }` - use this to add styles between two breakpoints.

For example this SCSS:
```
.custom-element {
	width: 100%;

	@include media-breakpoint-up(md) {
		width: 50%;
	}
}
```
Will output this CSS:
```
.custom-element {
	width: 100%;
}

@media (min-width: 48rem) {
	.custom-element {
		width: 50%;
	}
}
```