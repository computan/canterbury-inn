[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/global-styles/fonts.md) | [Next Article →](/docs/css/global-styles/icons.md)

# Global Styles: Buttons
With the release of the Catapult WordPress Framework 2.0, a shift was made away from using ACF Blocks for buttons and instead using the core WordPress button block. However, there is still some legacy button code. Effort should be made to use the `core/button` block whenever possible.

## Initial Button Styling
When a project begins, buttons styles should be updated to match Figma. The [/themes/catapult/css/__base-includes/_buttons.scss](/themes/catapult/css/__base-includes/_buttons.scss) file contains mixins used to generate the buttons for both the `core/button` block and the legacy button classes.

Note: when using the `gulp watch` command, make sure to save both the `_button-styles.scss` file and the core/legacy files. Updating the `_button-styles.scss` file won't compile the other two automatically.

## Core Button Block
The styles for the core button block are located at [/themes/catapult/blocks/core-blocks/button/style.scss](/themes/catapult/blocks/core-blocks/button/style.scss). This file rarely needs to be changed. If markup for this block is needed outside the editor, it can be used as follows (as either a link or a custom button):

```
<div class="wp-block-button">
	<a href="#" class="wp-block-button__link">Primary</a>
</div>

<div class="wp-block-button">
	<button type="button" class="wp-block-button__link">Primary</button>
</div>
```

This block can also be wrapped with a `core/buttons` block if multiple blocks appear together. This wrapper block can be added in this way:

```
<div class="wp-block-buttons">
	BLOCK MARKUP HERE
</div>
```

## Legacy Button Classes
There are a few places in the theme where the old legacy button classes are still being used. These classes are located at [/themes/catapult/css/__styles/global-elements/_button-utility-classes.scss](/themes/catapult/css/__styles/global-elements/_button-utility-classes.scss) and begin with the prefix `.c-btn`. Using these are not recommended.