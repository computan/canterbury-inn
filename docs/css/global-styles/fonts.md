[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/global-styles/grid.md) | [Next Article →](/docs/css/global-styles/buttons.md)

# Global Styles: Fonts
Font styles should rarely be set in SCSS files with CSS properties. Instead, every font style has a mixin automatically generated. These can be found in the [/themes/catapult/css/__base-includes/figma/_figma-font-styles.scss](/themes/catapult/css/__base-includes/figma/_figma-font-styles.scss) file.

***Here's the INCORRECT way to add font sizes:***
```
.custom-item {
	font-weight: 600;
	font-size: rem(72);
	font-family: 'Open Sans', sans-serif;
	line-height: rem(44);
}
```
Instead, font styles should be added with a mixin that will include all these styles
```
.custom-item {
	@include t2;
}
```

Most of the font style mixins accept one true/false attribute to disable the margins for that element. So `@include t2` or `@include t2(true)` will add a `margin-bottom` property. Whereas `@include t2(false)` will not set the margin.

## Font Styles on Core Blocks
The core heading and paragraph blocks have the ability to select one of the font sizes within the editor. The available font sizes for these blocks is specified in the [theme.json](/themes/catapult/theme.json) file. In this case, these blocks get the classes `.has-SIZE-font-size` added to them. These utility classes can be used elsewhere, but the mixin is generally preferred.

## Hosting Custom Fonts
For Google fonts, use the [settings.json](/themes/catapult/settings.json) file for automatic local hosting using the [fonts setting documented here](/docs/theme-overview/theme-settings.md).

If fonts need to be hosted locally, the @font-face rules can be added to the [/themes/catapult/css/__styles/init/_custom-fonts.scss](/themes/catapult/css/__styles/init/_custom-fonts.scss) file. Font files should be added to the `themes/catapult/fonts` directory as `woff2` files.