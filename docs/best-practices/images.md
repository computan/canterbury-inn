[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/best-practices/wcag.md) | [Next Article →](/docs/blocks/README.md)

# Images
When adding images to a site, there are two primary types of images:

1. `Raster images` - these are images that are made up of pixels, such as JPG and PNG.
	* These should almost always be added via the [WordPress Media Library ↗](https://wordpress.com/support/media/). Do not add these to the theme files since responsive sizes will not be generated.
2. `Vector images` - these are images created with paths, shapes, curves, and points. The most commonly used format is SVG.
	* These can either be added to the theme files as fixed graphical or icon elements used throughout the site, or they can also be uploaded to the media library.
	* By default, WordPress prevents uploading of svgs, but the plugin [Safe SVG ↗](https://wordpress.org/plugins/safe-svg/) can be used to enable this functionality. Do not user other methods to enable uploading these. SVG files can contain malicious code and must be properly sanitized by the plugin when uploaded.
	* Vector images added to the theme can be used as background images. Vector images added via the media library should follow the same rules as raster images.

## Displaying Images
When displaying raster images in the theme, WordPress image functions should always be used. The two most often used are:
* [wp_get_attachment_image() ↗](https://developer.wordpress.org/reference/functions/wp_get_attachment_image/) - to display any image added to the media library.
* [get_the_post_thumbnail() ↗](https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/) - to display a specific post's featured image.

Following the [Data Sanitization](/docs/best-practices/data-sanitization.md) rules, both of these can be wrapped with the [wp_kses_post() ↗](https://developer.wordpress.org/reference/functions/wp_kses_post/) function. For example:

```
if ( ! empty( $image_id ) ) {
	echo wp_kses_post( wp_get_attachment_image( $image_id, 'image-size', false, array( 'class' => 'image-class' ) ) );
}
```

The third and fourth parameters can be skipped if no CSS class is needed for the image.

***Images should never be added by manually coding the `src` tag or by adding it as a background image.*** Doing this prevents WordPress from generating the srcset needed for responsive image sizes and will impact site performance.

## Image Sizes
When displaying images from the media library, an image size should always be specified. The image size name should always match one of the image sizes added in the [settings.json file](/themes/catapult/settings.json).

The image sizes added to this file have 3 attributes that mirror the attributes of the [add_image_size() ↗](https://developer.wordpress.org/reference/functions/add_image_size/) function:

* `name` - the slug used to label the image size.
* `width` - the image width in pixels.
* `height` - the image height in pixels.
* `crop` - whether to automatically crop the image to the specified size. If false, the image will be constrained by the narrowest value.

The image size displayed should match the size in the Figma design. If a size that's too small is used, the image will be blurry. If a size that's too large is used, the image will impact performance and page speed scores. New image sizes can be added to ensure the correct image sizes are used.

Note: too many image sizes will impact the speed of uploading images. However, it's better to err on the side of a slower editor experience than a slow frontend experience. If there seem to be too many different image sizes, contact the Computan design and dev teams.