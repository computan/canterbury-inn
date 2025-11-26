[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/linting/js-eslint.md) | [Next Article →](/docs/best-practices/data-sanitization.md)

# PHP: PHPCS
[PHP_CodeSniffer (PHPCS) ↗](https://github.com/squizlabs/PHP_CodeSniffer) is the linter used to check PHP code.

## Command Line Tasks
There are two tasks that can be run specifically for PHPCS linting:

* `composer test`
	* Scans the [/themes/catapult/](/themes/catapult/) directory for `.php` files and outputs in the terminal any linting errors found in the files.

* `composer fix`
	* Scans the [/themes/catapult/](/themes/catapult/) directory for `.php` files and fixes any issues that are able to be automatically fixed. Not all issues are fixed, so the `composer test` task should always be used after this task to highlight any errors that need manual fixing.

## VSCode Plugin
Make sure to install the [PHP Sniffer & Beautifier ↗](https://marketplace.visualstudio.com/items?itemName=ValeryanM.vscode-phpsab).

When working on a file, this extension will highlight any errors with a wavy underline underneath. If you hover your cursor over the underline, a note with the error will appear. When a file is saved, any errors that can be fixed will be fixed automatically.

### Troubleshooting
1. A quick way to check if this is working is to remove a space within a function's parenthesis, like this:
	```
	<?php esc_html($content); ?>
	```

	This should then be automatically fixed to this:
	```
	<?php esc_html( $content ); ?>
	```
2. Make sure the `VSCode workspace` is open. This will not work if the file directory was opened directly.
3. VSCode might display an error message in the bottom right corner when opening the workspace. Check the error message to see if the problem is fixable.
4. Make sure your system is using the correct PHP version for the project. Use `brew unlink php@XX ; brew link php@XX` to set your system versions correctly. [Full instructions here](/docs/setup/system-setup.md)
5. If it still doesn't work, contact the Computan dev team.

## Exceptions
Occasionally a rule can be ignored if it causes an issue. Documentation about how to do this can be [found here ↗](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Advanced-Usage#ignoring-parts-of-a-file). Use these rules sparingly.