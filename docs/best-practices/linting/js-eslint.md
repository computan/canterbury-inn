[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/linting/css-stylelint.md) | [Next Article →](/docs/best-practices/linting/php-phpcs.md)

# JS: ESLint
[ESLint ↗](https://eslint.org/) is the linter used to check JavaScript code.

## Command Line Tasks
The [gulp build](/docs/gulp/additional-tasks.md), [gulp watch](/docs/gulp/additional-tasks.md), [gulp scripts](/docs/gulp/additional-tasks.md), and [gulp scripts:watch](/docs/gulp/additional-tasks.md) tasks will also lint all JS files and output errors in the terminal. All errors should be fixed.

## VSCode Plugin
Make sure to install the [ESLint extension ↗](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint).

When working on a file, this extension will highlight any errors with a wavy underline underneath. If you hover your cursor over the underline, a note with the error will appear. When a file is saved, any errors that can be fixed will be fixed automatically.

### Troubleshooting
1. A quick way to check if this is working is to add an extra tab on a line of code. The tab should be automatically removed.
2. Make sure the `VSCode workspace` is open. This will not work if the file directory was opened directly.
3. VSCode might display an error message in the bottom right corner when opening the workspace. Check the error message to see if the problem is fixable.
4. If it still doesn't work, contact the Computan dev team.

## Exceptions
Occasionally a rule can be ignored if it causes an issue. Documentation about how to do this can be [found here ↗](https://eslint.org/docs/latest/use/configure/rules). Use these rules sparingly.