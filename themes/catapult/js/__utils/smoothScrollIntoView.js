import scrollIntoView from 'scroll-into-view-if-needed';

const SmoothScrollIntoView = () => {
	function scrollToLocation(target) {
		const element = document.getElementById(target);
		// smooth scrolling set in _global.scss based on user animation preferences.
		scrollIntoView(element, {
			scrollMode: 'if-needed',
			behavior: 'auto',
			block: 'start',
			inline: 'start',
		});

		element.focus();
	}

	// get all links on a page that include a hash symbol. Ignore disabled links.
	const anchorsLinks = document.querySelectorAll(
		'a[href^="#"]:not([href="#"]):not([href="#onetrust"])'
	);

	if (anchorsLinks.length) {
		anchorsLinks.forEach(function (anchor) {
			anchor.addEventListener('click', function (e) {
				e.preventDefault();
				const target = anchor.getAttribute('href').substring(1);
				scrollToLocation(target);
			});

			anchor.addEventListener('keydown', function (e) {
				if (' ' === e.key || 'Enter' === e.key) {
					e.preventDefault();
					const target = anchor.getAttribute('href').substring(1);
					scrollToLocation(target);
				}
			});
		});
	}
};

export default SmoothScrollIntoView;
