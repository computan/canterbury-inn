document.addEventListener('DOMContentLoaded', () => {
	const heroDisplay = document.querySelector('.block-hero-display');
	const videoWrapper = heroDisplay.querySelector(
		'.acf-block__background-video-wrapper'
	);
	const videoComponent = heroDisplay.querySelector('.component-video');

	if (!videoWrapper || !videoComponent) return;

	if (videoWrapper.hasAttribute('data-video-url')) {
		const videoURL = videoWrapper.getAttribute('data-video-url');
		videoComponent.setAttribute('data-embed-url', videoURL);
	}
});
