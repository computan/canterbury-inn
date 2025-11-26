import React from 'react';
import { createRoot } from 'react-dom/client';
import ReactPlayer from 'react-player';

const backgroundVideoComponent = () => {
	const backgroundVideoBlocks = [];
	let backgroundVideos = [];

	const loadVideos = () => {
		backgroundVideos = document.querySelectorAll(
			'.acf-block__background-video-wrapper'
		);

		if (backgroundVideos.length) {
			backgroundVideos.forEach((backgroundVideo) => {
				if (backgroundVideo.hasAttribute('data-video-url')) {
					const videoURL =
						backgroundVideo.getAttribute('data-video-url');
					const videoLoopAttribute =
						backgroundVideo.getAttribute('data-video-loop');
					const root = createRoot(backgroundVideo);
					const parentBlock = backgroundVideo.closest('.acf-block');
					let videoLoop = true;

					if (
						'false' === videoLoopAttribute ||
						'0' === videoLoopAttribute
					) {
						videoLoop = false;
					}

					if (parentBlock) {
						backgroundVideoBlocks.push(parentBlock);

						parentBlock.style.setProperty(
							'--blockHeight',
							`${parentBlock.offsetHeight / 16}rem`
						);
					}

					root.render(
						<ReactPlayer
							className="acf-block__background-video"
							url={videoURL}
							playing
							loop={videoLoop}
							muted
							onReady={videoLoaded}
						/>
					);
				}
			});
		}
	};

	const videoLoaded = (player) => {
		player.wrapper.classList.add('loaded');
	};

	const setBackgroundVideoSize = () => {
		if (backgroundVideoBlocks.length) {
			backgroundVideoBlocks.forEach((backgroundVideoBlock) => {
				backgroundVideoBlock.style.setProperty(
					'--blockHeight',
					`${backgroundVideoBlock.offsetHeight / 16}rem`
				);
			});
		}
	};

	loadVideos();

	window.addEventListener('load', setBackgroundVideoSize);
};

backgroundVideoComponent();

export default backgroundVideoComponent;
