import { Swiper } from 'swiper';
import { A11y, Navigation } from 'swiper/modules';

const sliders = document.querySelectorAll(
	'.acf-block:not(.block-testimonial-cards) .block-testimonials .swiper'
);

if (sliders.length > 0) {
	sliders.forEach((slider, index) => {
		const slides = slider.querySelectorAll('.swiper-slide');

		slider.parentElement.classList.add(
			`block-testimonials__swiper--${index}`
		);

		const settings = {
			loop: slides.length > 1 ? true : false,
			grabCursor: true,
			spaceBetween: 32,
			slidesPerView: 1,
			modules: [A11y, Navigation],
			a11y: {
				itemRoleDescriptionMessage: 'slide',
			},
			navigation: {
				prevEl: `.block-testimonials__swiper--${index} .swiper-button-prev`,
				nextEl: `.block-testimonials__swiper--${index} .swiper-button-next`,
			},
		};

		new Swiper(slider, settings);
	});
}
