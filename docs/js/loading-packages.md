[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/js/README.md) | [Next Article →](/docs/js/controllers.md)

# Loading Packages
The Catapult webpack configuration utilizes [Code Splitting ↗](https://webpack.js.org/guides/code-splitting/) which splits NPM packages into separate bundles to significantly reduce the file size of individual block JS files and improve performance by sharing common modules.

Using the `Swiper` package as an example, the package is included within a block using the following code:

```
import Swiper from 'swiper';
```

Instead of including the `Swiper` code directly in the block's compiled JS, this instead creates a separate `swiper.js` file in the `/themes/catapult/dist/modules/` directory. The block then also has a new `blockname.modules.php` file similar to the standard WordPress `blockname.assets.php` file. This file is then used to add the module script as a dependency to the block's JS, which then gets enqueued when the block is used.

This configuration typically does not need to be modified. However, if scripts are not loading as expected, this is an area worth checking for errors.
