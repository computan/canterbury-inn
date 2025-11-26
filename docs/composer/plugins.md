[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/composer/README.md) | [Next Article →](/docs/setup/README.md)

# Installing WordPress plugins
The initial installation of WordPress plugins is done using Composer. However, after an environment is set up, plugins are managed through the dashboard or through WPEngine.

If a plugin needs to be added to the initial Composer setup, first find the plugin on [https://wpackagist.org/ ↗](https://wpackagist.org/). To install it run:
```
composer require wpackagist-plugin/plugin-name
```
For example, for WooCommerce:
```
composer require wpackagist-plugin/woocommerce
```

If the plugin is not available on wpackagist (e.g. it's a paid plugin), the plugin should just be manually installed locally and on the appropriate dev/stage/production sites. It does not need to be managed with Composer.

## Creating custom plugins

Custom developed plugins can be added directly to the `plugins` directory. Just be sure to add a rule to track the plugin's folder in the `.gitignore` file.