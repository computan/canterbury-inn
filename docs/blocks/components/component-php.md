[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/README.md) | [Next Article →](/docs/blocks/components/component-scss-js.md)

# Component PHP
An example of a component with reusable PHP is the [Post Card](/themes/catapult/blocks/components/post-card/post-card.php) component. This is a card block to display a single blog post. It can be reused in multiple places using the `catapult_get_component()` function. For example, given an array of post objects:

```
<?php
if ( ! empty( $blog_posts ) ) {
	foreach ( $blog_posts as $blog_post ) {
		catapult_get_component( 'post-card', array( 'post_object' => $blog_post ) );
	}
}
?>
```
