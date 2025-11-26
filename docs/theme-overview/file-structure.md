[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/theme-overview/README.md) | [Next Article →](/docs/theme-overview/theme-settings.md)

# Theme File Structure
The following is an overview of the essential directories and files of the Catapult theme.

* [/](/) - the main repo directory. This should be the WordPress `/wp-content/` directory.
	* [catapult.code-workspace](/catapult.code-workspace) - the VSCode workspace file that contains the linting settings, plugin recommendations, multi-root directory rules, search exclusion rules, and any other project-specific rules for VSCode. The name will be changed on each project per the [Local Setup](/docs/setup/local-setup.md) instructions.
	* [wp-config-sample-local.php](/wp-config-sample-local.php) - the sample wp-config.php data used during [Local Setup](/docs/setup/local-setup.md).
	* [wp-config-sample-prod.php](/wp-config-sample-prod.php) - the sample wp-config.php data used during setup of production sites.
* [/exports/](/exports/) - the directory containing the default export data used with the [gulp export and gulp import](/docs/gulp/additional-tasks.md) tasks.
* [/plugins/](/plugins/) - the main plugin directory. By default, all plugins are not tracked in the repo. Custom plugins can be added and tracked to this repo following the [Plugins](/docs/composer/plugins.md) documentation.
* [/themes/catapult/](/themes/catapult/) - the main theme directory. All gulp, npm, and composer terminal commands should be run from this directory.
	* [/acf-json/](/themes/catapult/acf-json/) - this contains [ACF Local JSON files ↗](https://www.advancedcustomfields.com/resources/local-json/) that get updated automatically when ACF field groups are updated from the dashboard.
	* [/blocks/](/themes/catapult/blocks/) - the main directory for all blocks, components, and non-global asset source files. The file structure for this directory is [documented here](/docs/blocks/file-structure.md).
	* [/core/](/themes/catapult/core/) - most of the files in this directory should never be changed, since they provide the foundational functionality for the theme. With one exception:
		* [/components/class-theme-core-blocks.php](/themes/catapult/core/components/class-theme-core-blocks.php) - This file handles the ACF block registration, block settings, and enqueuing CSS/JS assets for all blocks. The `load_global_blocks()` function in this file occasionally needs to be updated when adding new [Theme Block](/docs/blocks/theme-blocks.md) locations.
	* [/css/](/themes/catapult/css/) - the primary directory for any global CSS source files. [Use global styles sparingly](/docs/best-practices/global-vs-block-assets.md).
		* [/base-includes/](/themes/catapult/css/__base-includes) - the directory for the base includes.
			* [_variables.scss](/themes/catapult/css/__base-includes/_variables.scss) - file used for setting theme [variables](/docs/css/variables.md).
			* [_button-styles.scss](/themes/catapult/css/__base-includes/_buttons.scss) - file used for setting [button styles](/docs/css/global-styles/buttons.md).
			* [_font-styles.scss](/themes/catapult/css/__base-includes/_fonts.scss) - file used for setting custom [font styles](/docs/css/global-styles/fonts.md).
		* [_base-includes.scss](/themes/catapult/css/_base-includes.scss) - this file and all the SCSS files it imports should only be used for sass variables, functions, mixins, and should generate no actual CSS of its own. This file is included in any `style.scss`, `editor.scss` file or any other file that compiles to its own css.
		* [admin.scss](/themes/catapult/css/admin.scss) - styles loading on every page of the WordPress dashboard.
		* [editor.scss](/themes/catapult/css/editor.scss) - styles loaded within the Block Editor.
		* [styles.scss](/themes/catapult/css/styles.scss) - styles loaded on all pages on the frontend.  [Use global styles sparingly](/docs/best-practices/global-vs-block-assets.md).
	* `/dist/` - all CSS, JS, and font assets are compiled to this folder. Should be deployed but not be tracked in the repo.
	* [/icons/](/themes/catapult/icons/) - Icon SVG files added here will automatically become available in the theme icon picker fields and can be used with the icon mixin.
	* [/images/](/themes/catapult/images/) - a folder for any images or graphics used within the theme. Note: this is primarily for SVG graphics. Any raster (jpg, png, etc.) images should typically be added via the media library and not here, to avoid images without a properly generated srcset tag.
	* [/includes/](/themes/catapult/includes/) - any custom PHP functionality is added to this folder instead of the functions.php file. All files added to this directory get included automatically.
		* [/acf/](/themes/catapult/includes/acf/) - a directory for ACF functions and hooks.
			* [acf-defaults.php](/themes/catapult/includes/acf/acf-defaults.php) - this file can be used to hard-code default values for fields or dynamically create field values.
		* [/content-functions/](/themes/catapult/includes/content-functions/) - this contains various helper functions used throughout the theme.
		* [/content-hooks/](/themes/catapult/includes/content-hooks/) - this contains any WordPress action or filter hooks needed.
		* [/gravity-forms/](/themes/catapult/includes/gravity-forms/) - a directory for Gravity Forms functions and hooks.
		* [/post-types-and-taxonomies/](/themes/catapult/includes/post-types-and-taxonomies/) - all Custom Post Type (CPT) functionality is included here. Note: registering post types and taxonomies is done via the [settings.json](/themes/catapult/settings.json) file.
			* [post-taxonomies.php](/themes/catapult/includes/post-types-and-taxonomies/post-taxonomies.php) - custom functionality for post taxonomies.
			* [post-types.php](/themes/catapult/includes/post-types-and-taxonomies/post-types.php) - custom functionality for Custom Post Types.
	* [/js/](/themes/catapult/js/) - the primary directory for any global JS source files. [Use global styles sparingly](/docs/best-practices/global-vs-block-assets.md).
		* [/__init/controller.js](/themes/catapult/js/__init/controller.js) - this is the primary [Controller file](/docs/js/controllers.md) for the global scripts.
		* [editor.js](/themes/catapult/js/editor.js) - for custom block editor scripts.
		* [script.js](/themes/catapult/js/script.js) - for global frontend scripts.
	* `/node_modules/` - the NPM package directory. Should not be tracked in the repo or deployed.
	* [/parts/](/themes/catapult/parts/) - Will likely be deprecated at some point in favor of the [components](/themes/catapult/blocks/components/) directory. Can be used for PHP files included elsewhere.
	* [/qa/](/themes/catapult/qa/) - this contains all the configuration files, images, and test results for the automated visual QA tests. See [QA Tasks](/docs/gulp/qa-tasks.md) for more information.
	* [/tasks/](/themes/catapult/tasks/) - contains all the gulp tasks.
	* `/vendor/` - the Composer package directory. Should not be tracked in the repo or deployed.
	* [functions.php](/themes/catapult/functions.php) - Do not edit. Any functionality typically added to this file should be instead added to the [/includes/](/themes/catapult/includes/) directory.
	* [settings.json](/themes/catapult/settings.json) - This file is used to register various [theme settings](/docs/theme-overview/theme-settings.md).
	* [theme.json](/themes/catapult/theme.json) - Core [WordPress theme.json ↗](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/) file used to set global styles. For most projects, this won't need to be modified.
