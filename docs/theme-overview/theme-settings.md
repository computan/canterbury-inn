[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/theme-overview/file-structure.md) | [Next Article →](/docs/theme-overview/theme-options.md)

# Theme Settings (settings.json file)
The [settings.json](/themes/catapult/settings.json) file is used to set a number of project settings. The file must be in proper json format, otherwise it will cause a PHP error and the site won't load (the one exception is that comments between `/*` and `*/` can be added). These are the most commonly updated settings:

## post_types
Custom Post Types and taxonomies should be registered here. This will also automatically create a [Theme Blocks Location](/docs/blocks/theme-blocks.md) for the top, bottom, and sidebar of the single posts, the taxonomy pages for each registered taxonomy, and the post type archive, if the post has these set to public. This will handle the rendering of these block locations and loading of the CSS.

The `singular`, `plural`, and `menu_icon` attributes are all required. Additional options can be added using the standard args from the [register_post_type](https://developer.wordpress.org/reference/functions/register_post_type/) and [register_taxonomy](https://developer.wordpress.org/reference/functions/register_taxonomy/) functions.

For example, here's how to register a "resource" post type with a single taxonomy:

```
"resource": {
	"singular": "Resource",
	"plural": "Resources",
	"args": {
		"menu_icon": "dashicons-archive",
		"rewrite": {
			"slug": "resources"
		}
	},
	"taxonomies": {
		"resource_type": {
			"singular": "Resource Type",
			"plural": "Resource Types",
			"args": {
				"rewrite": {
					"slug": "resources/type"
				}
			}
		}
	}
}
```

This will create the following theme block locations and automatically load blocks assigned to these locations and their CSS/JS on the single post and taxonomy pages.
- Resource - Top
- Resource - Bottom
- Resource - Sidebar
- Resource Type

## acf_options
These settings are used to add custom [ACF options pages ↗](https://www.advancedcustomfields.com/resources/options-page/). Settings should be given a custom `post_id` to use as the second parameter in the ACF [get_field() ↗](https://www.advancedcustomfields.com/resources/get_field/) function. Subpages are automatically created for any custom post types registered with the `post_types` setting.

## theme_block_locations
New Theme Blocks locations can be registered here. Read more about how to use [Theme Blocks here](/docs/blocks/theme-blocks.md).

## register_styles, register_scripts
These settings can be used to register frontend styles and scripts. Registered styles aren't automatically loaded on the frontend - they just become available for use as a dependency elsewhere (such as with block [CSS Deps and JS Deps](/docs/blocks/acf-blocks/block-settings.md)).

## fonts
Font CSS URLs containing `@font-face` rules can be added here. If the font is a Google font, the font `woff2` files will be automatically downloaded and hosted locally in the `wp-content/uploads/fonts` directory. This functionality is based on the [WordPress Font Library ↗](https://make.wordpress.org/core/2024/03/14/new-feature-font-library/) but gets generated in PHP rather than using the Full Site Editor interface. Other fonts will just have the URL included in a `<link>` tag. See the [Fonts](/docs/css/global-styles/fonts.md) documentation for information about hosting other non-Google fonts locally.

## enqueue_styles, enqueue_scripts, enqueue_admin_styles
These settings are used to register and automatically load styles on every page of the site. These should generally just be used for the main theme files and don't need to be changed.

## register_nav_menus
Registers navigation menu [locations ↗](https://developer.wordpress.org/reference/functions/register_nav_menus/). These are rarely used in favor of [block-based navigation](/docs/theme-overview/navigation-footer.md) and [Theme Blocks](/docs/blocks/theme-blocks.md).

## thumbnails
Registers new image sizes. See [best practices for image sizes here](/docs/best-practices/images.md).