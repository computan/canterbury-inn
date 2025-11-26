import { Swiper } from 'swiper';
import { A11y, Navigation } from 'swiper/modules';

const sliders = document.querySelectorAll(
	'.block-media-slider-fullscreen .swiper'
);

if (sliders.length > 0) {
	sliders.forEach((slider) => {
		const settings = {
			loop: true,
			grabCursor: true,
			spaceBetween: 10,
			slidesPerView: 'auto',
			centeredSlides: true,
			modules: [A11y, Navigation],
			a11y: {
				itemRoleDescriptionMessage: 'slide',
			},
			navigation: {
				prevEl: slider.parentElement.querySelector(
					'.swiper-button-prev'
				),
				nextEl: slider.parentElement.querySelector(
					'.swiper-button-next'
				),
			},
			on: {
				transitionEnd(swiper) {
					if (1 === swiper.activeIndex) {
						swiper.loopFix();
					}
				},
			},
		};

		new Swiper(slider, settings);
	});
}
