[⌂ Table of Contents](/docs/README.md) | [← Previous Article](/docs/blocks/components/default-components/share-icons.md) | [Next Article →](/docs/blocks/core-blocks.md)

# Slider
The slider component includes shared SCSS styles for slider components. All sliders should utilize the [Swiper ↗](https://swiperjs.com/) library and can be modified using the [Swiper API Settings ↗](https://swiperjs.com/swiper-api).

Swiper contains a lot of extra functionality and effects in its [Modules ↗](https://swiperjs.com/swiper-api#modules) system. For sites with custom sliders, this is a good place to start to see if a module already exists to accomplish the desired effect. However, in order to improve site performance, try to only include modules when needed for a specific block. See [/docs/js/loading-packages.md](/docs/js/loading-packages.md) for more information about loading Swiper into a block's JS file.

While Swiper has a setting that will automatically generate the slider HTML markup, it's generally recommended to add this markup directly in the blocks in order to have more control over the accessibility labels, classes, and text domains. The default Swiper HTML classes should be used:

```
<!-- Slider main container -->
<div class="swiper">
	<!-- Additional required wrapper -->
	<div class="swiper-wrapper">
		<!-- Slides -->
		<div class="swiper-slide">Slide 1</div>
		<div class="swiper-slide">Slide 2</div>
		<div class="swiper-slide">Slide 3</div>
		...
	</div>
	<!-- If we need pagination -->
	<div class="swiper-pagination"></div>

	<!-- If we need navigation buttons -->
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>

	<!-- If we need scrollbar -->
	<div class="swiper-scrollbar"></div>
</div>
```