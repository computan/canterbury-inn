[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/README.md) | [Next Article →](/docs/best-practices/linting/css-stylelint.md)

# Linting
Linting is the process of automatically checking code to make sure it meets the established coding stadards for the project. Linting isn't perfect, but will help catch a large percentage of issues in your code.

The Catapult WordPress Framework has linting rules for PHP, SCSS, and JS code. As outlined in the [Setup](/docs/setup/README.md) documentation, [VS Code ↗](https://code.visualstudio.com/) is the recommended IDE and all documentation here will assume you are using this. Setting up these linters and making sure they're working is essential before starting a project. If you use a different IDE, you are responsible for setting up your own linters, checking your code, and ensuring it meets these standards.

Each linter can be used two ways:

1. Command line tasks that scan every respective file in the theme and alert you to any errors.
2. VSCode extensions that will highlight any issues in the current file you are working on. When a file is saved, some issues will be fixed automatically.

* [CSS: Stylelint](/docs/best-practices/linting/css-stylelint.md)
* [JS: ESLint](/docs/best-practices/linting/js-eslint.md)
* [PHP: PHPCS](/docs/best-practices/linting/php-phpcs.md)