[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/setup/system-setup.md) | [Next Article →](/docs/setup/multisite-setup.md)

# Local Setup

1. ## Create new Local site
	* Use the following settings:
		* PHP: `8.2.x`
		* Web server: `nginx`
		* Database: `mysql 8.x.x`
		* WordPress username: `wpdev`
		* WordPress password: randomly generated string of at least 20 letters, numbers, and characters. Add to timesheet or send to the project manager.
		* WordPress email: `websupport@computan.com`

2. ## Clone repo
	* Navigate to the site folder and delete the `wp-content` directory. Create a new `wp-content` directory and clone the repo here from the terminal (clone command can be copied from Bitbucket - make sure to add a period at the end to indicate cloning into the current directory):
		```
		git clone git@github.com:computan/ADDPROJECTNAMEHERE.git .
		```

3. ## Open VS Code Workspace
	* In the root of the repo, rename the `catapult.code-workspace` file to the name of the project (with `.code-workspace` extension).
	* In VS Code - go to `File->Open Workspace from File` and select the renamed file. Open this workspace any time you are working on the project to ensure the automatic linting rules work (note: a PHPCS warning will appear until step #5 is completed).
	* When you open the workspace, there are several recommended plugins that VS Code will suggest you install. If a notification does not appear, these plugins can be found in the workspace file or on the VS Code extensions tab.
	* The workspace utilizes [Multi-root Workspaces ↗](https://code.visualstudio.com/docs/editor/multi-root-workspaces) which allows easy-access to nested directories in the file browser. Additional directories can be added if desired. The following directories will appear as root directories:
		* `/wp-content/themes/catapult/` - the main theme directory. This is where the majority of development will occur, and all terminal commands that follow should be run from this directory.
		* `/wp-content/` - the root directory for the Git repo. This is where you will find the wp-config sample files.
		* `/app/public/` - the root WordPress directory. This lives outside the repo and is where the site's wp-config.php file can be found.

4. ## Update wp-config.php
	* Copy the entire file contents from [wp-config-sample-local.php](/wp-config-sample-local.php) into your Local site's `wp-config.php` file.
	* On the `Database` tab for the site in Local, copy the full `Socket` address and paste it into the `DB_HOST` value in your `wp-config.php` file, prepended with `localhost:` For example:
		```
		define( 'DB_HOST', 'localhost:/Users/myusername/Library/Application Support/Local/run/abcdefg/mysql/mysqld.sock' );
		```
		* Note: on Windows, don't modify this line and use the value created by Local.
	* For plugin licenses, copy the settings in the `wp-config.php pre-filled values` notes in 1Password.

5. ## Catapult install
	* Make sure you check the [package.json](/themes/catapult/package.json)  and [composer.json](/themes/catapult/composer.json) files for the Node and PHP version, and use `nvm use XX` and `brew unlink php@XX ; brew link php@XX` to set your system versions correctly. [Full instructions here](/docs/setup/system-setup.md).
	* From the `themes/catapult` directory, run the following command to automatically install composer/node packages, plugins, activate the theme and plugins, import initial post data, and run an initial build:
		```
		npm run setup
		```
		* In order to install Advanced Custom Fields (ACF) via composer, you will be prompted for the ACF composer username credential which can be found in timesheet.
		* This task will prompt if you want to import font styles and colors from Figma.  This should be done the first time work on a site has begun. If working on an existing site, skip this step. You will need a Figma API key. See the `gulp figma` task for details.
		* Note: if on Windows or if any errors occur, run this task from the `Open Site Shell` option within `Local` to make sure the wp-cli database tasks work correctly.
	* Check the terminal for any errors when installing. If there are errors installing Node packages, be sure you're using the correct Node version. If there are other errors, check the previous steps or message the Computan dev team. Otherwise, you're ready to begin development!
