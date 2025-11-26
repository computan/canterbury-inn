[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/gulp/block-tasks.md) | [Next Article →](/docs/gulp/qa-tasks.md)

# Additional Gulp Tasks

* `gulp figma`
	* Automatically pulls font and color styles from Figma. Optionally runs on setup, but can be run any time. Will prompt for two required values:
		* Figma API token (found in 1password)
		* Figma File ID (found in the Figma URL for the project: https://www.figma.com/file/THIS_IS_THE_FILE_ID/...)
	* `--token` or `--key` - can use this parameter to specify the Figma API token rather than entering via the prompt.
	* `--file` - can use this parameter to specify the Figma File ID rather than entering via the prompt.
	* This generates scss files in the [/themes/catapult/css/__base-includes/figma/](/themes/catapult/css/__base-includes/figma/) directory. Do not edit these files directly. The one exception is the [figma-settings.json](/themes/catapult/css/__base-includes/figma/figma-settings.json) file which can be used to store the Figma File ID and update the font top margins. [Read more here](/docs/css/figma-variables.md).
	* The color import currently only works with solid colors and linear gradients. Radial and other gradients will need to be imported manually.
	* The task will also ask if you want to import icons from Figma. This will automatically download the SVGs found in the Icon Library in the Foundations section of Figma and save them to the [/icons/](/themes/catapult/icons/) directory with sub-directories based on their categories. See the [icons](/docs/css/global-styles/icons.md) documentation for more information. Watch the console for any errors during this import (especially on older versions of Catapult in Figma).

* `gulp build --production`
	* Builds the project with production settings - minifies all the assets. You can also use `--production` flag with other tasks to make a production build.

* `gulp styles`
	* Scans the [/themes/catapult/css/](/themes/catapult/css/) and [/themes/catapult/blocks/](/themes/catapult/blocks/) directories for SCSS files and outputs the compiled `.css` files to the `/themes/catapult/dist/` directory. Will also output any linting errors found in the files.

* `gulp styles:watch`
	* Watches and compiles SCSS file changes in real-time.

* `gulp scripts`
	* Scans the [/themes/catapult/js/](/themes/catapult/js/) and [/themes/catapult/blocks/](/themes/catapult/blocks/) directories for JS files and outputs the compiled JS files to the `/themes/catapult/dist/` directory. Will also output any linting errors found in the files.

* `gulp scripts:watch`
	* Watches and compiles JS file changes in real-time.

* `gulp icons`
	* Scans the [/themes/catapult/icons/](/themes/catapult/icons/) directory and generates an [/themes/catapult/icons/_icons.scss](/themes/catapult/icons/_icons.scss) file which is used by the theme's scss build. This is only needed for multicolor icons.

* `gulp plugins`
	* Activates the default plugins and updates all plugins via the wp-cli. Note: this only updates plugins in the WordPress instance. `composer update` is how to update the starting packages.

* `gulp theme`
	* Activates the Catapult theme via the wp-cli.

* `gulp browsersync`
	* Launches [Browsersync ↗](https://browsersync.io/).

* `gulp import`
	* Imports `export.xml` and `block-library-export.xml` files in `exports` folder (see [wp import ↗](https://developer.wordpress.org/cli/commands/import/)). This does not replace any existing posts. Also imports forms for Gravity Forms from the `forms.json` file.

* `gulp export`
	* Creates `export.xml` and `block-library-export.xml` files in `exports` folder containing the export of the site's authors, terms, posts, comments, and attachments (see [wp export ↗](https://developer.wordpress.org/cli/commands/export/)). Also exports all Gravity Forms to the `forms.json` file.

* `gulp docs`
	* Scans all the `.md` files in the `/docs/` directory runs link, spelling, and grammar tests. Each of these can also be run separately by adding a flag to the command:
	* `--links` - check for bad links.
	* `--spelling` - check for incorrectly spelled words. Words can be added to the allowed word list in the [/themes/catapult/tasks/docs.js](/themes/catapult/tasks/docs.js) file.
	* `--grammar` - check for bad grammar.

* `gulp docs:header`
	* Scans all the `.md` files in the `/docs/` directory and adds a header with links to home, previous article, and next article.

* `gulp auth`
	* Runs on `npm run setup` and generates an auth.json file used to install ACF via Composer. Will prompt for the ACF Composer Username Credential which can be found in 1password.

* `gulp version`
	* Used prior to the release of a new Catapult version. Scans all the `php` files and the `style.css` and `package.json` file and updates the version numbers based on the versions in the changelog. Only updates files that have been changed from the main branch in the repo. Updated files will have additional @since comments added for each changed version.