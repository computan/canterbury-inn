[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/component-php.md) | [Next Article →](/docs/blocks/components/default-components/README.md)

# Component SCSS/JS
Alternatively, components can be created for reusable CSS or JS that can be enqueued with any blocks that use it using the CSS Deps and JS Deps properties in the CSS dependency with the [Block Settings](/docs/blocks/acf-blocks/block-settings.md).

Loading styles and scripts in this way allows to only be loaded when they're actually being used rather than adding them globally and creating unused CSS/JS.

## Dependencies
Just as blocks can include components as dependencies, components can also require other components as dependencies. This can be done by creating a file in the component directory called `dependencies.json` with the following code:

```
{
	"css": [
		"dependency-1-name",
		"dependency-2-name"
	],
	"js": [
		"dependency-1-name",
		"dependency-2-name"
	]
}
```
