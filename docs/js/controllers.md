[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/js/loading-packages.md) | [Next Article →](/docs/js/performance.md)

# JavaScript Controllers
The global JavaScript file uses a [controller](/themes/catapult/js/__init/controller.js) - a file that imports functions and runs them on set event listeners (some are throttled). ACF blocks used to use this structure, but now use just a single JS file.

The controller file contains code similar to this:

```
const controller = {
	init() {},
	loaded() {},
	resized() {},
	scrolled() {},
	keyDown() {},
	mouseUp() {},
};
export default controller;
```

Modules can be imported and run within each of these actions.