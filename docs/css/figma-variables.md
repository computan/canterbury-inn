[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/bem.md) | [Next Article →](/docs/css/variables.md)

# Figma Sass Variables
During the initial setup of the theme, the [gulp figma task](/docs/gulp/additional-tasks.md) will automatically pull font and color styles from Figma and generates three files:

* [/themes/catapult/css/__base-includes/figma/_figma-color-variables.scss](/themes/catapult/css/__base-includes/figma/_figma-color-variables.scss) - this file contains an SCSS array of all the `Colors` in the `Foundations` section of the Figma file. If all the colors are not listed there, contact the Computan design team to update the Figma file.
* [/themes/catapult/css/__base-includes/figma/_figma-font-styles.scss](/themes/catapult/css/__base-includes/figma/_figma-font-styles.scss) - this file contains mixins for all the font styles specified in `Typography` within the `Foundations` section of the Figma file. These mixins should be used anywhere a specific font style is used. If all the font styles are not listed there, contact the Computan design team to update the Figma file.
* [/themes/catapult/css/__base-includes/figma/_figma-font-variables.scss](/themes/catapult/css/__base-includes/figma/_figma-font-variables.scss) - this file contains an SCSS array of all the of the font styles specified in `Typography` within the `Foundations` section of the Figma file. This array is used to automatically add [CSS Custom Properties](/docs/css/custom-properties.md) to the root of the page for each style. If all the font styles are not listed there, contact the Computan design team to update the Figma file.

***NOTE: Because these files are generated dynamically, DO NOT edit these files directly. If the values in the files are incorrect, contact the Computan team.***

There is an additional file at [/themes/catapult/css/__base-includes/figma/figma-settings.json](/themes/catapult/css/__base-includes/figma/figma-settings.json) that CAN be edited:

This file currently contains two values:

* `figmaFileID` - this is the value prompted in the [gulp figma task](/docs/gulp/additional-tasks.md). It can be added to this file to avoid having to complete it in the prompt every single time the task is run.
* `fontStyles` - because Figma doesn't have a specific margin-top property the way the Figma Design System specifies, this is used to set the top margin for font styles. This rarely needs to be changed.
