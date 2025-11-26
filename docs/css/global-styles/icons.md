[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/global-styles/buttons.md) | [Next Article →](/docs/css/custom-properties.md)

# Global Styles: Icons
During the initial setup of the theme, the [gulp figma task](/docs/gulp/additional-tasks.md) will automatically pull icons from Figma and place them in categorized subdirectories in the [/themes/catapult/icons/](/themes/catapult/icons/) directory. Additional icons can also be added to this directory.

All icons should be SVGs and can be both a single color or multicolor.

When using the [@icon()](/docs/css/functions-mixins.md) mixin, this will either add `mask-image` CSS properties for single color icons which will then inherit the color specified on that element, or will use `background-image` for multicolor icons and that color will be whatever is specified in the SVG file. These properties use CSS Custom properties that are automatically added inline on the page that pointing to the SVG file URLs.

The ACF Icon Picker plugin is no longer being used. If you need an icon selector, add a `radio` field with the field name `icon` and it will automatically load the icons as options. Adding an underscore and the category to the end of the field name will limit it to a specific category of icons. For example, a field name of `icon_social` will create a radio field with only the social media icons available.