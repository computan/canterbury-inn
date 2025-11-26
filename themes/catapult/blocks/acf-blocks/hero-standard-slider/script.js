import { Swiper } from 'swiper';
import { A11y, Autoplay } from 'swiper/modules';

const sliders = document.querySelectorAll(
	'.block-hero-standard-slider .swiper'
);

if (sliders.length > 0) {
	sliders.forEach((slider) => {
		const slides = slider.querySelectorAll('.swiper-slide');

		const settings = {
			loop: slides.length > 1,
			grabCursor: true,
			spaceBetween: 32,
			slidesPerView: 1,
			modules: [A11y, Autoplay],
			a11y: {
				itemRoleDescriptionMessage: 'slide',
			},
		};

		new Swiper(slider, settings);
	});
}
