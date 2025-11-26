[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/setup/multisite-setup.md) | [Next Article →](/docs/theme-overview/README.md)

# Server Setup
Once a site is set up on the server, the next step is to set up the Buddy pipelines. Catapult contains a [buddy.yml](/buddy.yml) file that controls all the pipelines needed for installing and deploying Catapult on a WPEngine server.

When a new project is created in Buddy, the pipelines get automatically imported from this file. Any changes to these pipelines should be made by updating the buddy.yml file rather than using the Buddy UI.

## WPEngine SFTP Users
Each environment needs an SFTP user created with the name `buddy` (WPEngine will automatically add the environment key/slug as a prefix). The passwords should then be added to the following Buddy variables (don't save the passwords anywhere, including 1password).

## Buddy Variables
After the Buddy project is set up, the following variables should be added to the project:

- **Production Site SFTP Credentials:**
  - `prod_install_id` - The WPEngine production environment key/slug.
  - `prod_password` - Password for production site SFTP login.

- **Stage Site SFTP Credentials:**
  - `stage_install_id` - The WPEngine staging environment key/slug. Usually ends with `stage`.
  - `stage_password` - Password for staging site SFTP login.

- **Dev Site SFTP Credentials:**
  - `dev_install_id` - The WPEngine development environment key/slug. Usually ends with `dev`.
  - `dev_password` - Password for dev site SFTP login.

## Buddy Pipelines

### Install Catapult on Dev Site
This pipeline is used for the initial installation of Catapult on the dev site. This should only ever be run once - and developers should use WP Migrate DB Pro to push database further updates. This pipeline does the following:
1. Installs and deploys all the WordPress plugins installed with Composer or tracked in the repo.
2. Compiles and deploys all CSS and JS with Gulp, along with all theme files.
3. Uploads the exported posts and form files from the [/exports/](/exports/) directory.
4. Runs WP-CLI commands on the server to update and activate plugins, activate the theme, import the default posts and Gravity Forms forms, set the homepage, and set/flush the permalinks.

### Dev Site Deployment
This pipeline compiles and deploys all theme CSS and JS with Gulp, along with all theme files, to the dev site. It also uploads any custom plugin files tracked in the repo. It runs whenever the `dev` branches is updated.

### Stage Site Deployment
This pipeline compiles and deploys all theme CSS and JS with Gulp, along with all theme files, to the stage site. It also uploads any custom plugin files tracked in the repo. It runs whenever the `stage` branches is updated.

### Production Site Deployment
This pipeline compiles and deploys all theme CSS and JS with Gulp, along with all theme files, to the production site. It also uploads any custom plugin files tracked in the repo. It requires a manual click to run and uses the `main` branch.

## Alternative Installation
Instead of running the `Install Catapult on Dev Site` pipeline, the WP-CLI tasks can be run directly on the server after uploading the exports files:

```
wp plugin update --all --exclude=gravityforms,gravityformscli
wp plugin activate advanced-custom-fields-pro eight29-filters-react gravityforms gravityformscli regenerate-thumbnails-advanced safe-svg wp-retina-2x wordpress-importer wordpress-seo shortpixel-image-optimiser
wp theme activate catapult
wp import wp-content/exports/export.xml --authors=create
wp import wp-content/exports/block-library-export.xml --authors=create
wp gf form import wp-content/exports/forms.json
wp option update show_on_front "page"
wp option update page_on_front 768
wp option update catapult_installed 1
wp option update permalink_structure '/%postname%/'
wp rewrite flush
```