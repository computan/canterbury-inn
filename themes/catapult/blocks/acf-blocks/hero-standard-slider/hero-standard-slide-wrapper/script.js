import Swiper from 'swiper';
import { Navigation, Pagination, A11y } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', () => {
	const heroSliders = document.querySelectorAll(
		'.block-hero-standard-slide-wrapper'
	);

	if (!heroSliders.length) return;

	heroSliders.forEach((sliderWrapper) => {
		const swiperEl = sliderWrapper.querySelector('.swiper');
		const prevBtn = sliderWrapper.querySelector('.swiper-button-prev');
		const nextBtn = sliderWrapper.querySelector('.swiper-button-next');
		const pagination = sliderWrapper.querySelector('.swiper-pagination');

		new Swiper(swiperEl, {
			modules: [Navigation, Pagination, A11y],
			slidesPerView: 1,
			loop: false,
			spaceBetween: 16,
			slideClass: 'swiper-slide',
			wrapperClass: 'swiper-wrapper',
			a11y: {
				itemRoleDescriptionMessage: 'slide',
			},
			navigation: {
				prevEl: prevBtn,
				nextEl: nextBtn,
			},
			pagination: {
				el: pagination,
				clickable: true,
			},
			on: {
				init() {
					toggleNavState(prevBtn, nextBtn);
				},
				slideChange() {
					toggleNavState(prevBtn, nextBtn);
				},
			},
		});

		[prevBtn, nextBtn].forEach((btn) => {
			btn?.addEventListener('mouseup', () => btn.blur());
		});
	});
});

function toggleNavState(prev, next) {
	if (!prev || !next) return;

	prev.classList.toggle(
		'is-active',
		!prev.classList.contains('swiper-button-disabled')
	);
	next.classList.toggle(
		'is-active',
		!next.classList.contains('swiper-button-disabled')
	);
}
