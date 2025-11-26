import { Swiper } from 'swiper';
import { A11y, Autoplay } from 'swiper/modules';

const sliders = document.querySelectorAll('.block-logo-carousel .swiper');

if (sliders.length > 0) {
	const intialUrl = new URL(window.location);
	const backstop = intialUrl.searchParams.get('backstop');

	sliders.forEach((slider) => {
		const slides = slider.querySelectorAll('.swiper-slide');

		const settings = {
			loop: slides.length > 2 ? true : false,
			spaceBetween: 16,
			slidesPerView: 2.4375,
			modules: [A11y, Autoplay],
			a11y: {
				itemRoleDescriptionMessage: 'slide',
			},
			speed: 2000,
			autoplay: {
				delay: 0,
			},
			breakpoints: {
				448: {
					slidesPerView: 3,
					loop: slides.length > 3 ? true : false,
				},
				576: {
					slidesPerView: 4,
					spaceBetween: 24,
					loop: slides.length > 4 ? true : false,
				},
				768: {
					slidesPerView: 5,
					spaceBetween: 32,
					loop: slides.length > 5 ? true : false,
				},
				992: {
					slidesPerView: 6,
					spaceBetween: 32,
					loop: slides.length > 6 ? true : false,
				},
			},
		};

		if (backstop) {
			settings.autoplay = false;
		}

		new Swiper(slider, settings);
	});
}
