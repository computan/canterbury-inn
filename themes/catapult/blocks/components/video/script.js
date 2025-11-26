import { __ } from '@wordpress/i18n';
import React from 'react';
import { createRoot } from 'react-dom/client';
import ReactPlayer from 'react-player';
import { disableBodyScroll, clearAllBodyScrollLocks } from 'body-scroll-lock';

const videoComponent = () => {
	const videoComponents = document.querySelectorAll(
		'.component-video:not(.component-lightbox__button):not([data-lightbox-content])'
	);
	let videoLightbox;
	let videoLightboxContent;
	let root;

	const initializeVideoLightbox = (videoLightboxComponent) => {
		const videoLightboxFigure = videoLightboxComponent;

		videoLightboxFigure.classList.add('component-video__button');
		// open video - play icon click
		videoLightboxFigure
			.querySelector('.component-video__play-button')
			.addEventListener('click', openVideoLightbox);
		videoLightboxFigure.addEventListener('click', openVideoLightbox);
		videoLightboxFigure.setAttribute(
			'aria-label',
			__('Open Image in Lightbox', 'catapult')
		);
	};

	// open lightbox
	const openVideoLightbox = (e) => {
		e.preventDefault();

		if (!videoLightbox) {
			createVideoLightbox();
		}

		const lightboxVideoComponent =
			e.currentTarget.closest('.component-video');
		const lightboxVideoContent = videoLightbox.querySelector(
			'.component-video-lightbox__content'
		);

		videoLightbox.classList.add('active');

		disableBodyScroll(videoLightbox, {
			reserveScrollBarGap: true,
		});

		setTimeout(function () {
			videoLightbox.classList.add('visible');
		}, 1);

		document.addEventListener('keydown', keydown);

		if (
			lightboxVideoComponent &&
			lightboxVideoComponent.hasAttribute('data-embed-url')
		) {
			const videoURL =
				lightboxVideoComponent.getAttribute('data-embed-url');
			root = createRoot(lightboxVideoContent);

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
		}
	};

	const closeVideoLightbox = (e) => {
		e.preventDefault();

		videoLightbox.classList.remove('visible');

		setTimeout(function () {
			videoLightbox.classList.remove('active');
			clearAllBodyScrollLocks();
			videoLightboxContent.innerHTML = '';
		}, 400);

		root.unmount();

		document.removeEventListener('keydown', closeVideoLightbox);
	};

	const keydown = (e) => {
		if ('Escape' === e.key) {
			closeVideoLightbox(e);
		}
	};

	// create lightbox
	const createVideoLightbox = () => {
		videoLightbox = document.createElement('div');
		videoLightbox.classList.add('bg-dark', 'component-video-lightbox');
		videoLightbox.setAttribute('role', 'dialog');
		videoLightbox.setAttribute('aria-modal', 'true');

		const videoLightboxContainer = document.createElement('div');
		videoLightboxContainer.classList.add(
			'component-video-lightbox__container'
		);

		videoLightboxContent = document.createElement('div');
		videoLightboxContent.classList.add('component-video-lightbox__content');

		const videoLightboxCloseOverlay = document.createElement('button');
		videoLightboxCloseOverlay.classList.add(
			'component-video-lightbox__close-button-overlay'
		);
		videoLightboxCloseOverlay.setAttribute('role', 'button');
		videoLightboxCloseOverlay.setAttribute(
			'aria-label',
			__('Close Lightbox', 'catapult')
		);
		videoLightboxCloseOverlay.addEventListener('click', closeVideoLightbox);

		const videoLightboxCloseButton = document.createElement('span');
		videoLightboxCloseButton.classList.add(
			'component-video-lightbox__close-button'
		);
		videoLightboxCloseButton.addEventListener('click', closeVideoLightbox);

		videoLightboxCloseOverlay.appendChild(videoLightboxCloseButton);
		videoLightboxContainer.appendChild(videoLightboxContent);
		videoLightbox.appendChild(videoLightboxContainer);
		videoLightbox.appendChild(videoLightboxCloseOverlay);
		document.body.appendChild(videoLightbox);
	};

	// loop through each video component and start the lightbox
	if (videoComponents.length > 0) {
		videoComponents.forEach((video) => {
			initializeVideoLightbox(video);
		});
	}
};

videoComponent();

export default videoComponent;
