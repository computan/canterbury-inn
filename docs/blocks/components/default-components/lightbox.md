[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/hubspot.md) | [Next Article →](/docs/blocks/components/default-components/share-icons.md)

# Lightbox
The lightbox component is used when images or videos within a block can be clicked to open them in a slider of images/videos in a lightbox. This component includes both JS and SCSS that should be included in a block's CSS/JS Deps setting. The block can then be configured to use this component with the following steps:

1. Add `component-lightbox` class to the block.
2. Any `<figure>` HTML element will automatically become clickable. If the figure has a `data-lightbox-content` attribute, that will become the content of the lightbox. If there is no `data-lightbox-content` attribute, then the contents of the `<figure>` element will be used.

The [render-block.php](/themes/catapult/includes/gutenberg-block-editor/render-block.php) file can be used to modify the frontend markup of a block to add the `data-lightbox-content` property as needed. See the `acf/media-gallery` code in that file for how that is done.