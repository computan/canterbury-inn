[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/linting/php-phpcs.md) | [Next Article →](/docs/best-practices/error-checking.md)

# Data Sanitization
We follow the guidelines established by WordPress regarding data sanitization and escaping. The following documentation contains excerpts from wordpress.org, but the specific articles should be read for additional info.

## Sanitizing Data
[https://developer.wordpress.org/apis/security/sanitizing/](https://developer.wordpress.org/apis/security/sanitizing/)

Untrusted data comes from many sources (users, third party sites, even your own database!) and all of it needs to be checked before it’s used.

Remember: Even admins are users, and users will enter incorrect data, either on purpose or accidentally. It’s your job to protect them from themselves.

Sanitizing input is the process of securing/cleaning/filtering input data. Validation is preferred over sanitization because validation is more specific. But when “more specific” isn’t possible, sanitization is the next best thing.

## Escaping Data
[https://developer.wordpress.org/apis/security/escaping/](https://developer.wordpress.org/apis/security/escaping/)

Escaping output is the process of securing output data by stripping out unwanted data, like malformed HTML or script tags. This process helps secure your data prior to rendering it for the end user. 

## Commonly used functions for sanitizing and escaping
The theme's [PHPCS](/docs/best-practices/linting/php-phpcs.md) rules will flag incorrectly sanitized data. The most common occurrence is when trying to directly `echo` data.

This is incorrect:
```
echo $content;
```

Instead, here are some functions that can be used to sanitize outputted data:

* [esc_html() ↗](https://developer.wordpress.org/reference/functions/esc_html/) - use anytime an HTML element encloses a section of data being displayed. This will remove HTML.
	```
	echo esc_html( $content );
	```
* [esc_url() ↗](https://developer.wordpress.org/reference/functions/esc_url/) - Use on all URLs, including those in the src and href attributes of an HTML element.
	```
	echo esc_url( $content );
	```
* [esc_attr() ↗](https://developer.wordpress.org/reference/functions/esc_attr/) - Use on everything else that’s printed into an HTML element’s attribute.
	```
	echo esc_attr( $content );
	```
* [wp_kses_post() ↗](https://developer.wordpress.org/reference/functions/wp_kses_post/) - Use to safely escape for all non-trusted HTML. This preserves HTML. Alternative version of wp_kses()that automatically allows all HTML that is permitted in post content.
	```
	echo wp_kses_post( $content );
	```
