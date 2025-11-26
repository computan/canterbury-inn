[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/theme-overview/theme-options.md) | [Next Article →](/docs/theme-overview/navigation-footer.md)

# Compiling Theme Assets

Several commands need to be run from the terminal in the theme directory to generate the CSS, JS, and fonts for the theme.

To compile everything at once, use `gulp build`. This is often helpful at the beginning of a project or when pulling down the latest commits.

When working on a project, `gulp watch` will actively watch the SCSS and JS files and compile the files that have been changed. It will also open up a browser window that will automatically sync the changes as you edit the files. This task can be buggy depending on your system setup. Email the Computan dev team if you run into any issues.

When the assets compile, pay attention to the terminal for any errors.

More documentation:
* [Primary Tasks](/docs/gulp/primary-tasks.md)
* [Additional Tasks](/docs/gulp/additional-tasks.md)