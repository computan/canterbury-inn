[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/js/controllers.md) | [Next Article →](/docs/php/README.md)

# JavaScript Performance
JavaScript can have a big impact on browser performance. A few things to keep in mind:

1. Avoid the `scroll` and `resize` events (unless using it within a [Controller](/docs/js/controllers.md) or using `lodash/throttle`). Even then, the [Intersection Observer API ↗](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API) is much better for performance.
2. Avoid querying elements multiple times when it can be done once and assigned to a `const` variable.
3. Avoid the `mouse` and `hover` events unless absolutely necessary (also not great for accessibility). Use CSS when possible.
4. Avoid scripts that cause unexpected movement in the page. See [Cumulative Layout Shift ↗](https://web.dev/cls/).
5. Follow the [Global vs Block Assets best practices](/docs/best-practices/global-vs-block-assets.md) and avoid adding global JS unless necessary.