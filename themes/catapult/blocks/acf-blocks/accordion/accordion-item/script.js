require('dom-slider');

const { slideToggle, slideUp } = window.domSlider;

const accordionButtons = document.querySelectorAll(
	'.block-accordion-item > .wp-block-button .wp-block-button__link'
);

const toggleAccordion = (e) => {
	e.preventDefault();

	const accordionBlock = e.currentTarget.closest('.block-accordion');

	if (
		accordionBlock.classList.contains('is-style-single-open') &&
		accordionBlock.classList.contains('block-accordion--opening')
	) {
		return;
	}

	const currentItem = e.currentTarget.closest('.block-accordion-item');

	if (
		currentItem &&
		accordionBlock &&
		!e.currentTarget.classList.contains('wp-block-button__link--opening')
	) {
		if (currentItem.classList.contains('is-style-open')) {
			currentItem.classList.add('active');
		}

		currentItem.classList.toggle('active');
		e.currentTarget.setAttribute(
			'aria-expanded',
			currentItem.classList.contains('active')
		);
		e.currentTarget.classList.add('wp-block-button__link--opening');
		accordionBlock.classList.add('block-accordion--opening');

		if (
			!currentItem.classList.contains('active') &&
			currentItem.closest('.block-accordion-side-image')
		) {
			e.currentTarget.setAttribute('disabled', true);
		}

		const currentContent = currentItem.querySelector(
			'.block-accordion-item > .block-content'
		);

		currentContent.classList.remove('block-content--hidden');

		slideToggle({
			element: currentContent,
			slideSpeed: 400,
			easing: 'ease-in-out',
		}).then((contentElement) => {
			if (contentElement.classList.contains('DOM-slider-hidden')) {
				contentElement.classList.add('block-content--hidden');
			}

			const buttonElement = contentElement.parentElement.querySelector(
				'.wp-block-button__link'
			);

			if (buttonElement) {
				buttonElement.classList.remove(
					'wp-block-button__link--opening'
				);
			}

			accordionBlock.classList.remove('block-accordion--opening');
		});

		if (accordionBlock.classList.contains('is-style-single-open')) {
			const activeItemContents = accordionBlock.querySelectorAll(
				'.block-accordion-item.active > .block-content, .block-accordion-item.is-style-open > .block-content'
			);

			if (activeItemContents.length) {
				activeItemContents.forEach((activeItemContent) => {
					if (activeItemContent !== currentContent) {
						activeItemContent.parentElement.classList.remove(
							'active'
						);

						const buttonElement =
							activeItemContent.previousElementSibling.querySelector(
								'.wp-block-button__link'
							);

						if (buttonElement) {
							buttonElement.setAttribute(
								'aria-expanded',
								'false'
							);
						}

						slideUp({
							element: activeItemContent,
							slideSpeed: 400,
							easing: 'ease-in-out',
						}).then((contentElement) => {
							if (
								contentElement.classList.contains(
									'DOM-slider-hidden'
								)
							) {
								contentElement.classList.add(
									'block-content--hidden'
								);

								contentElement.parentElement.classList.remove(
									'is-style-open'
								);
							}
						});
					}
				});
			}
		}

		if (currentItem.classList.contains('is-style-open')) {
			currentItem.classList.remove('is-style-open');
		}
	}
};

if (accordionButtons.length) {
	accordionButtons.forEach((accordionButton) => {
		accordionButton.addEventListener('click', toggleAccordion);
	});
}
