import { Swiper } from 'swiper';
import { A11y, Navigation } from 'swiper/modules';

const sliders = document.querySelectorAll('.block-media-slider-grid .swiper');

if (sliders.length > 0) {
	sliders.forEach((slider) => {
		const slides = slider.querySelectorAll('.swiper-slide');

		const settings = {
			loop: slides.length > 1 ? true : false,
			grabCursor: true,
			spaceBetween: 10,
			slidesPerView: 1,
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
		};

		new Swiper(slider, settings);
	});
}
