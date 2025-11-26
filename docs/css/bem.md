[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/css/README.md) | [Next Article →](/docs/css/figma-variables.md)

# BEM
The majority of CSS styles (with a few exceptions) use the [BEM methodology ↗](https://getbem.com/) which give classes to components based on their `block`, `element`, and `modifier`. Per the BEM guidelines:

* `Block` - standalone entity that is meaningful on its own.
* `Element` - a part of a block that has no standalone meaning and is semantically tied to its block.
* `Modifier` - a flag on a block or element. Use them to change appearance or behavior.

For example, here's an example ACF Block with the name `custom-block`:

```
<section class="block-custom-block block-custom-block--active">
	<a class="block-custom-block__link"></a>

	<div class="block-custom-block__content"></div>
</section>
```

* The `Block` class is `block-custom-block`.
* There are two `Elements` with the classes `block-custom-block__link` and `block-custom-block__content`.
* The main block has a `Modifier` added with the class `block-custom-block--active`.

## Exceptions
There are a few exceptions to this model:

1. Classes added via Gutenberg and the [*Styles* block setting](/docs/blocks/acf-blocks/block-settings.md) don't follow the `Modifier` BEM rule. Instead, these are added as `is-style-STYLE_NAME`. Font sizes from Gutenberg are added as `.has-SIZE-font-size`.
2. There is some legacy code that still needs to be updated. Even if old code doesn't follow BEM, all new code should follow it as closely as possible.