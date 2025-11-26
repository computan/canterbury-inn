[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/data-sanitization.md) | [Next Article →](/docs/best-practices/global-vs-block-assets.md)

# Error Checking
All code should be developed so that it is both free from errors and free from potential errors if the data changes. Using the [Linting rules](/docs/best-practices/linting/README.md) is a good place to start, but additional checking is often needed.

## PHP
The Catapult WordPress Framework comes with the error handler framework [whoops ↗](https://github.com/filp/whoops) which can be used to better diagnose PHP errors on a site. If a PHP error occurs, the entire screen will display the error along with helpful information about what caused the error. It makes errors harder to ignore or miss.

To enable whoops, the `ENABLE_WHOOPS` constant must be set to `true` in the `wp-config.php` file. It should be enabled on all local development environments, and turned off on all staging and production environments. See the [Local Setup instructions](/docs/setup/local-setup.md) for more information about setting up the recommended `wp-config.php` settings.

If whoops is not enabled, the PHP error log should be closely monitored.

One of the most common PHP errors is due to null or undeclared variables. All variables should have their values checked before being output - don't assume the variable always has a value set. For example, this is incorrect if the value of `$content` isn't first checked:

```
echo esc_html( $content );
```

Instead, it should be:
```
if ( ! empty( $content ) ) {
	echo esc_html( $content );
}
```

Additional functions that are commonly used [(more here) ↗](https://www.php.net/manual/en/ref.var.php):

* [empty() ↗](https://www.php.net/manual/en/function.empty.php)
* [isset() ↗](https://www.php.net/manual/en/function.isset.php)
* [is_array() ↗](https://www.php.net/manual/en/function.is-array.php)
* [is_bool() ↗](https://www.php.net/manual/en/function.is-bool.php)
* [is_int() ↗](https://www.php.net/manual/en/function.is-int.php)
* [function_exists() ↗](https://www.php.net/manual/en/function.function-exists.php)
* [class_exists() ↗](https://www.php.net/manual/en/function.class-exists.php)

## JS
The browser console should be closely monitored for any JavaScript errors. Errors should be fixed when discovered.