import { Swiper } from 'swiper';
import { A11y, Navigation } from 'swiper/modules';

const blocks = document.querySelectorAll('.block-testimonial-cards');

if (blocks.length > 0) {
	blocks.forEach((block) => {
		const slider = block.querySelector('.block-testimonials .swiper');

		if (!slider) {
			return;
		}

		let cardsPerRow = 3;

		if (block.hasAttribute('data-cards-per-row')) {
			cardsPerRow = parseInt(block.getAttribute('data-cards-per-row'));
		}

		const slides = slider.querySelectorAll('.swiper-slide');

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
				prevEl: slider.parentElement.querySelector(
					'.swiper-button-prev'
				),
				nextEl: slider.parentElement.querySelector(
					'.swiper-button-next'
				),
			},
			breakpoints: {
				576: {
					slidesPerView: 2,
					loop: slides.length > 2 ? true : false,
				},
				992: {
					slidesPerView: cardsPerRow,
					loop: slides.length > cardsPerRow ? true : false,
				},
			},
		};

		new Swiper(slider, settings);
	});
}
