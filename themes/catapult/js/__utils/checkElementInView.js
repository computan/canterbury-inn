export const checkElementInView = (
	selector,
	inViewCallback,
	outOfViewCallback,
	customConfig = {}
) => {
	let elements;

	if ('string' === typeof selector) {
		elements = document.querySelectorAll(selector);
	} else if ('object' === typeof selector) {
		if (selector.length) {
			elements = selector;
		} else {
			elements = [selector];
		}
	} else {
		return;
	}

	const defaultConfig = {
		root: null,
		rootMargin: '0px 0px 0px 0px',
		threshold: [0],
	};

	const config = Object.assign({}, defaultConfig, customConfig);

	const observer = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				inViewCallback(entry.target);
			} else {
				outOfViewCallback(entry.target);
			}
		});
	}, config);

	elements.forEach((element) => {
		observer.observe(element);
	});

	return observer;
};
