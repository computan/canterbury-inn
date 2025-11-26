[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/global-styles/icons.md) | [Next Article →](/docs/js/README.md)

# CSS Custom Properties
The theme automatically adds a number of [CSS Custom Properties ↗](https://developer.mozilla.org/en-US/docs/Web/CSS/--*) that can be used when needed:

## Global Properties
These properties are added to the root document:

* `Colors` - these match the color names.
* `Font styles` - these match the font name combined with the font property.
	* `--margin-top` is added to some of the font styles when they should have extra spacing above when following certain elements. This value can be changed on elements when needed. In cases where this needs to be overridden, use `--margin-top: 0;` rather than `margin-top: 0;`.
* `Grid/Container` - these are used to dynamically reference the container sizes.
	* `--containerWidth` and `--containerMaxWidth` - the current container width and max-width. Must be used together to set the `width` and `max-width` attributes of an element outside a container that you want to have the same size of the container.
	* `--gutterWidth` - the current width between the edge of the container and one side of the viewport. Extremely useful with absolute positioned items that need to be positioned to one side of the grid.
	* `--negativeGutterWidth` - the negative value of the gutter width.
	* `--columnWidth` and `--columnWidth` - the current column width and max-width. Must be used together to set the `width` and `max-width` attributes of an element outside a column that you want to have the same size of the column. Will need to be combined with the `calc()` CSS function and the `$grid-gap` SCSS variable if calculating multiple column sizes.

The grid/container variables can be used wherever needed. The Color and Font variables should be used only when absolutely needed and should instead be added to elements via the ([Functions/Mixins](/docs/css/functions-mixins.md)). This ensures that if the base colors are changed, an alert will prevent using a non-existant color (no alert will appear if using CSS Custom Properties).

## Block Background Color Properties
All ACF blocks have a background color utility class starting with `bg-` that also has some CSS Custom Properties that get added:

* `--blockBackgroundColor` - the block's background color.
* `--blockColor` - the block's font color.
* `--blockHoverColor` - the block's font hover color for links.

These should be used any time a color needs to be set in a block. The `--blockColor` var will be automatically generated when using the `text` foundations color. So this SCSS:

```
.block-custom {
	color: paint(text);
}
```

Will generate this CSS:

```
.block-custom {
	color: var(--blockColor, #0a0a0a);
}
```

If the block uses the [Background Color](/docs/blocks/acf-blocks/block-settings.md) block setting, this will allow the color to automatically change to the font color specified for the dark background.
