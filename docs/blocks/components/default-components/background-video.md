[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/README.md) | [Next Article →](/docs/blocks/components/default-components/cards.md)

# Background Video
This component is used to display an autoplaying video instead of a background image. This component has the ability to be loaded automatically rather than needing to add it via the CSS/JS Deps property. The [Hero Display](/themes/catapult/blocks/acf-blocks/hero-display/block.php) block is a good example of how this is used.

1. Add `background_video` to the `Global ACF Fields` block.php setting. This will automatically add an oEmbed ACF field to the block.
2. Add `<?php echo wp_kses_post( $content_block->get_block_background_image_and_video() ); ?>` to the block.php file wherever the background video should be output. This will automatically add the necessary markup. This also will work when outputting the `background_image` block setting.
3. The component CSS/JS will be automatically enqueued.
