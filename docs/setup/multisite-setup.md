[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/setup/local-setup.md) | [Next Article →](/docs/setup/server-setup.md)

# Setting up multisite/network

Multisite setup is almost the same as a standard site with a few exceptions:

1. When installing the Local site, on the username/password settings page click `Advanced options` and select `Yes` for multisite. Either subdirectory or subdomain can be used.
2. When copying the `wp-config-sample` files, replace everything except for these lines in `wp-config.php` file:
	```
	define( 'WP_ALLOW_MULTISITE', true );
	define( 'MULTISITE', true );
	define( 'SUBDOMAIN_INSTALL', true );
	$base = '/';
	define( 'DOMAIN_CURRENT_SITE', 'domain-name.local' );
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );
	```
3. If you are using a subdomain setup, when new subdomain sites are added, there is an option in Local to "Sync Multisite domains to host file" that needs to be run for the subdomains to work.
4. Network activate the Catapult theme and any needed plugins. Make sure these are activated on any sites that are created.