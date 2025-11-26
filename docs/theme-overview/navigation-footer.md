[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/theme-overview/compiling-theme-assets.md) | [Next Article →](/docs/best-practices/README.md)

# Navigation/Footer
The navigation and footer are both created using blocks rather than using fixed templates or the WordPress menu system. These blocks are assigned to either the `Primary Navigation` or `Footer` [Theme Block Locations](/docs/blocks/theme-blocks.md). Some default blocks exist and can be modified as needed according to project's designs:

## Navigation Blocks
The outer navigation blocks exist as [React blocks](/docs/blocks/react-blocks/README.md) and are the outer wrappers for the navigation system. These typically do not need to be modified. There are 3 of these blocks:
* [/themes/catapult/blocks/react-blocks/navigation/](/themes/catapult/blocks/react-blocks/navigation/) - this the outermost wrapper block that contains the entire menu block system. It contains a logo, and the ability to add navigation links and buttons, and has 3 styles available.
* [/themes/catapult/blocks/react-blocks/navigation-link/](/themes/catapult/blocks/react-blocks/navigation-link/) - This is the top-level navigation link block and has the option to add a submenu.
* [/themes/catapult/blocks/react-blocks/navigation-submenu/](/themes/catapult/blocks/react-blocks/navigation-submenu/) - This is the submenu block that gets added to a navigation link. It contains ACF blocks that are used for the contents of the submenu dropdown.

The primary work on a project will be developing the ACF blocks used within the submenus. These are the default ACF submenu blocks
* [/themes/catapult/blocks/acf-blocks/navigation-columns](themes/catapult/blocks/acf-blocks/navigation-columns) - this block contains columns of links that can be rearranged as needed.
* [/themes/catapult/blocks/acf-blocks/navigation-simple-links](/themes/catapult/blocks/acf-blocks/navigation-simple-links) - this block is a simple list of links.

If a new submenu block is added, the `allowedBlocks` variable in the [/themes/catapult/blocks/react-blocks/navigation-submenu/edit.js](/themes/catapult/blocks/react-blocks/navigation-submenu/edit.js) file should be updated.

## Footer Blocks
The footer blocks are all created as [ACF blocks](/docs/blocks/acf-blocks/README.md) and follow the same structure as other ACF blocks.