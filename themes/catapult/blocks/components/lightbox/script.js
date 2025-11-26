import { __ } from '@wordpress/i18n';
import React from 'react';
import { createRoot } from 'react-dom/client';
import ReactPlayer from 'react-player';
import DOMPurify from 'dompurify';
import { Swiper } from 'swiper';
import { A11y, Navigation, Pagination, Manipulation } from 'swiper/modules';
import { disableBodyScroll, clearAllBodyScrollLocks } from 'body-scroll-lock';

const lightboxComponents = document.querySelectorAll('.component-lightbox');
let lightbox;
let lightboxContent;
let currentSlider;
let root;
let currentSlidePostIds = [];
let appendOrPrepend = 'append';

const initializeLightbox = (lightboxComponent) => {
	if (lightboxComponent?.detail?.postsWrapper) {
		lightboxComponent = lightboxComponent.detail.postsWrapper;
	}

	const lightboxFigures = lightboxComponent.querySelectorAll(
		'figure:not(.block-library__qa-overlay)'
	);

	if (lightboxFigures.length > 0) {
		lightboxFigures.forEach((lightboxFigure, index) => {
			lightboxFigure.classList.add('component-lightbox__button');
			lightboxFigure.addEventListener('click', openLightbox);
			lightboxFigure.setAttribute(
				'aria-label',
				__('Open Image in Lightbox', 'catapult')
			);
			lightboxFigure.setAttribute('data-lightbox-index', index);
		});
	}
};

const createLightbox = () => {
	lightbox = document.createElement('div');
	lightbox.classList.add('component-lightbox__frame', 'bg-dark');
	lightbox.setAttribute('role', 'dialog');
	lightbox.setAttribute('aria-modal', 'true');

	const lightboxContainer = document.createElement('div');
	lightboxContainer.classList.add('component-lightbox__container', 'swiper');

	lightboxContent = document.createElement('div');
	lightboxContent.classList.add(
		'component-lightbox__content',
		'swiper-wrapper'
	);

	const lightboxCloseOverlay = document.createElement('button');
	lightboxCloseOverlay.classList.add(
		'component-lightbox__close-button-overlay'
	);
	lightboxCloseOverlay.setAttribute('role', 'button');
	lightboxCloseOverlay.setAttribute(
		'aria-label',
		__('Close Lightbox', 'catapult')
	);
	lightboxCloseOverlay.addEventListener('click', closeLightbox);

	const lightboxCloseButton = document.createElement('span');
	lightboxCloseButton.classList.add('component-lightbox__close-button');
	lightboxCloseButton.addEventListener('click', closeLightbox);

	const lightboxButtonPrev = document.createElement('button');
	lightboxButtonPrev.classList.add('swiper-button-prev');
	lightboxButtonPrev.setAttribute('role', 'button');
	lightboxButtonPrev.innerText = __('Previous slide', 'catapult');
	lightboxContainer.appendChild(lightboxButtonPrev);

	const lightboxButtonNext = document.createElement('button');
	lightboxButtonNext.classList.add('swiper-button-next');
	lightboxButtonNext.setAttribute('role', 'button');
	lightboxButtonNext.innerText = __('Next slide', 'catapult');
	lightboxContainer.appendChild(lightboxButtonNext);

	const lightboxPagination = document.createElement('div');
	lightboxPagination.classList.add('swiper-pagination');
	lightboxContainer.appendChild(lightboxPagination);

	lightboxCloseOverlay.appendChild(lightboxCloseButton);
	lightboxContainer.appendChild(lightboxContent);
	lightbox.appendChild(lightboxContainer);
	lightbox.appendChild(lightboxCloseOverlay);
	document.body.appendChild(lightbox);
};

const openLightbox = (e) => {
	e.preventDefault();

	if (!lightbox) {
		createLightbox();
	}

	let currentSlideIndex = 1;

	if (e.currentTarget.hasAttribute('data-lightbox-index')) {
		currentSlideIndex = e.currentTarget.getAttribute('data-lightbox-index');
	}

	const lightboxComponent = e.currentTarget.closest('.component-lightbox');

	if (lightboxComponent) {
		const lightboxFigures = lightboxComponent.querySelectorAll(
			'.component-lightbox__button'
		);

		if (lightboxFigures.length > 0) {
			lightboxContent.innerHTML = '';
			currentSlidePostIds = [];

			lightboxFigures.forEach((lightboxFigure) => {
				const slideContent = getSlideContent(lightboxFigure);

				lightboxContent.appendChild(slideContent);

				if (lightboxFigure.hasAttribute('data-post-id')) {
					currentSlidePostIds.push(
						parseInt(lightboxFigure.getAttribute('data-post-id'))
					);
				}
			});

			const settings = {
				loop: lightboxFigures.length > 1 ? true : false,
				grabCursor: true,
				spaceBetween: 32,
				slidesPerView: 1,
				initialSlide: currentSlideIndex,
				modules: [A11y, Navigation, Pagination, Manipulation],
				a11y: {
					itemRoleDescriptionMessage: 'slide',
				},
				navigation: {
					prevEl: '.swiper-button-prev',
					nextEl: '.swiper-button-next',
				},
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
					dynamicBullets: true,
					dynamicMainBullets: 5,
				},
				on: {
					slideChange,
				},
			};

			if (
				lightboxComponent.classList.contains(
					'block-filter-top__posts'
				) ||
				lightboxComponent.classList.contains('block-filter-side__posts')
			) {
				settings.loop = false;

				settings.on.reachBeginning = () => {
					appendOrPrepend = 'prepend';
					currentSlider.allowSlidePrev = false;
					currentSlider.params.allowSlidePrev = false;
					currentSlider.navigation.prevEl.classList.add(
						'swiper-button--loading'
					);
					currentSlider.navigation.prevEl.disabled = true;

					const reachEndEvent = new CustomEvent(
						'catapult-lightbox-reach-beginning',
						{
							detail: {
								lightboxComponent,
								page:
									parseInt(
										currentSlider.slides[
											currentSlider.activeIndex
										].firstElementChild.getAttribute(
											'data-page'
										)
									) - 1,
							},
						}
					);
					window.dispatchEvent(reachEndEvent);
					slideChange(currentSlider);
				};

				settings.on.reachEnd = () => {
					appendOrPrepend = 'append';
					currentSlider.allowSlideNext = false;
					currentSlider.params.allowSlideNext = false;
					currentSlider.navigation.nextEl.classList.add(
						'swiper-button--loading'
					);
					currentSlider.navigation.nextEl.disabled = true;

					const reachEndEvent = new CustomEvent(
						'catapult-lightbox-reach-end',
						{
							detail: {
								lightboxComponent,
								page:
									parseInt(
										currentSlider.slides[
											currentSlider.activeIndex
										].firstElementChild.getAttribute(
											'data-page'
										)
									) + 1,
							},
						}
					);
					window.dispatchEvent(reachEndEvent);
					slideChange(currentSlider);
				};
			}

			currentSlider = new Swiper(
				'.component-lightbox__container',
				settings
			);

			lightbox.classList.add('active');

			disableBodyScroll(lightbox, {
				reserveScrollBarGap: true,
			});

			document.addEventListener('keydown', keydown);

			setTimeout(function () {
				lightbox.classList.add('visible');
			}, 1);
		}
	}
};

const getSlideContent = (lightboxFigure) => {
	let newLightboxFigure;
	const slideContent = document.createElement('div');
	slideContent.classList.add('component-lightbox__slide', 'swiper-slide');

	if (lightboxFigure.hasAttribute('data-lightbox-content')) {
		const slideContentString = JSON.parse(
			lightboxFigure.getAttribute('data-lightbox-content')
		);

		slideContent.innerHTML = DOMPurify.sanitize(slideContentString);

		if (lightboxFigure.hasAttribute('data-page')) {
			slideContent.firstElementChild.setAttribute(
				'data-page',
				lightboxFigure.getAttribute('data-page')
			);
		}

		newLightboxFigure = slideContent.querySelector('[data-embed-url]');
	} else {
		newLightboxFigure = lightboxFigure.cloneNode(true);
		newLightboxFigure.classList.remove('component-lightbox__button');
		newLightboxFigure.removeAttribute('aria-label');
		slideContent.appendChild(newLightboxFigure);
	}

	return slideContent;
};

const keydown = (e) => {
	if ('Escape' === e.key) {
		closeLightbox(e);
	} else if ('ArrowRight' === e.key) {
		currentSlider.slideNext();
	} else if ('ArrowLeft' === e.key) {
		currentSlider.slidePrev();
	}
};

const closeLightbox = (e) => {
	e.preventDefault();

	lightbox.classList.remove('visible');

	setTimeout(function () {
		lightbox.classList.remove('active');
		clearAllBodyScrollLocks();

		currentSlider.destroy(true);
		lightboxContent.innerHTML = '';
	}, 400);

	document.removeEventListener('keydown', closeLightbox);
};

const slideChange = (swiperInstance) => {
	if (
		!swiperInstance.slides ||
		!swiperInstance.slides[swiperInstance.activeIndex]
	) {
		return;
	}

	const currentSlide = swiperInstance.slides[swiperInstance.activeIndex];
	const lightboxFigure = currentSlide.querySelector(
		'.component-video[data-embed-url]'
	);

	if (lightboxFigure) {
		playVideo(lightboxFigure);
	} else if (swiperInstance) {
		stopVideos(swiperInstance);
	}
};

const playVideo = (lightboxFigure) => {
	if (
		lightboxFigure.classList.contains(
			'component-video-lightbox__video--playing'
		)
	) {
		return;
	}

	const videoURL = lightboxFigure.getAttribute('data-embed-url');
	lightboxFigure.classList.add('component-video-lightbox__video--playing');
	root = createRoot(lightboxFigure);

	root.render(
		<ReactPlayer
			className="component-video-lightbox__video"
			url={videoURL}
			playing
			height="100%"
			width="100%"
			controls={true}
		/>
	);
};

const stopVideos = () => {
	if (root && root._internalRoot) {
		root.unmount();
	}

	const playingVideos = document.querySelectorAll(
		'.component-video-lightbox__video--playing'
	);

	if (playingVideos.length > 0) {
		playingVideos.forEach((playingVideo) => {
			playingVideo.classList.remove(
				'component-video-lightbox__video--playing'
			);
		});
	}
};

const possiblyRenderMoreSlides = (e) => {
	if (
		!currentSlider ||
		currentSlider?.destroyed ||
		!e?.detail?.postsWrapper
	) {
		return;
	}

	if (!currentSlider.params) {
		currentSlider.params = {};
	}

	currentSlider.allowSlidePrev = true;
	currentSlider.params.allowSlidePrev = true;
	currentSlider.navigation.prevEl.classList.remove('swiper-button--loading');
	currentSlider.navigation.prevEl.disabled = false;
	currentSlider.allowSlideNext = true;
	currentSlider.params.allowSlideNext = true;
	currentSlider.navigation.nextEl.classList.remove('swiper-button--loading');
	currentSlider.navigation.nextEl.disabled = false;

	let lightboxFigures = e.detail.postsWrapper.querySelectorAll(
		'.component-lightbox__button'
	);

	if (lightboxFigures.length > 0) {
		if ('prepend' === appendOrPrepend) {
			lightboxFigures = Array.from(lightboxFigures).reverse();
		}

		lightboxFigures.forEach((lightboxFigure) => {
			if (!lightboxFigure.hasAttribute('data-post-id')) {
				return;
			}

			const postId = parseInt(
				lightboxFigure.getAttribute('data-post-id')
			);

			if (currentSlider?.slides?.length > 0) {
				for (
					let index = 0;
					index < currentSlider.slides.length;
					index++
				) {
					if (
						!currentSlider.slides[
							index
						]?.firstElementChild.hasAttribute('data-post-id')
					) {
						continue;
					}

					if (
						postId ===
						parseInt(
							currentSlider.slides[
								index
							].firstElementChild.getAttribute('data-post-id')
						)
					) {
						return;
					}
				}
			}

			const slideContent = getSlideContent(lightboxFigure);

			if ('prepend' === appendOrPrepend) {
				currentSlider.prependSlide(slideContent);
			} else {
				currentSlider.appendSlide(slideContent);
			}

			currentSlidePostIds.push(postId);
		});

		currentSlider.update();
	}
};

const handledRenderedPageSlides = () => {
	if (!currentSlider) {
		return;
	}

	if (!currentSlider.params) {
		currentSlider.params = {};
	}

	currentSlider.allowSlidePrev = true;
	currentSlider.params.allowSlidePrev = true;
	currentSlider.navigation.prevEl.classList.remove('swiper-button--loading');
	currentSlider.navigation.prevEl.disabled = false;
	currentSlider.allowSlideNext = true;
	currentSlider.params.allowSlideNext = true;
	currentSlider.navigation.nextEl.classList.remove('swiper-button--loading');
	currentSlider.navigation.nextEl.disabled = false;
};

if (lightboxComponents.length > 0) {
	lightboxComponents.forEach((lightboxComponent) => {
		initializeLightbox(lightboxComponent);
	});
}

window.addEventListener('catapult-filters-render-posts', initializeLightbox);
window.addEventListener(
	'catapult-filters-render-posts',
	possiblyRenderMoreSlides
);
window.addEventListener(
	'catapult-filters-page-already-rendered',
	handledRenderedPageSlides
);
