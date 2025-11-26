/* global catapult */
import Sortable from 'sortablejs';

const pageContent = document.querySelector('.block-library__content-wrapper');
const inputs = document.querySelectorAll('.block-library__input');
const navElement = document.querySelector('.block-library__nav');
const navWrappers = document.querySelectorAll('.block-library__nav > li');
const navHeadingButtons = document.querySelectorAll(
	'.block-library__nav-heading-button'
);
const toggleButtons = document.querySelectorAll(
	'.block-library__toggle-button'
);
const showAllButton = document.querySelector(
	'.block-library__toggle-buttons .block-library__show-all'
);
const hideAllButton = document.querySelector(
	'.block-library__toggle-buttons .block-library__hide-all'
);
const hoverLabelsToggleButton = document.querySelector(
	'.block-library__hover-labels-toggle-button'
);
const overlaysToggleButton = document.querySelector(
	'.block-library__overlays-toggle-button'
);
const qaOverlaysToggleButton = document.querySelector(
	'.block-library__qa-overlays-toggle-button'
);
const qaOpacitySlider = document.querySelector(
	'.block-library__qa-opacity-slider'
);
const qaOpacityResetButton = document.querySelector(
	'.block-library__qa-opacity-reset'
);
const colorsToggleButton = document.querySelector(
	'.block-library__colors-toggle-button'
);
const buttonsToggleButton = document.querySelector(
	'.block-library__buttons-toggle-button'
);
const formsToggleButton = document.querySelector(
	'.block-library__forms-toggle-button'
);
const resetOrderButton = document.querySelector(
	'.block-library__reset-order-button'
);
const simpleModeButton = document.querySelector(
	'.block-library__simple-mode-button'
);
const blockLibrarySection = document.querySelector('.block-library');
let childElements = document.querySelectorAll(
	'.block-library__content-wrapper > *'
);
let sortable; // eslint-disable-line no-unused-vars
const originalOrder = []; // eslint-disable-line no-unused-vars
const toggleEvent = new Event('block-library-change');
const initEvent = new Event('block-library-init');

const intialUrl = new URL(window.location);
let qa = intialUrl.searchParams.get('qa');
let qaIndex = intialUrl.searchParams.get('index');
let keydown = false;

if (!document.body.classList.contains('qa-active')) {
	qa = false;
}

const toggleInputs = (e) => {
	e.preventDefault();

	if (e.currentTarget.hasAttribute('data-category')) {
		const dataCategory = e.currentTarget.getAttribute('data-category');
		let categoryInputs;

		if ('all' === dataCategory) {
			categoryInputs = document.querySelectorAll('.block-library__input');

			if (colorsToggleButton) {
				if (
					e.currentTarget.classList.contains(
						'block-library__show-all'
					)
				) {
					colorsToggleButton.classList.remove('active');
					document.body.classList.remove('hide-colors');
				} else {
					colorsToggleButton.classList.add('active');
					document.body.classList.add('hide-colors');
				}
			}

			if (buttonsToggleButton) {
				if (
					e.currentTarget.classList.contains(
						'block-library__show-all'
					)
				) {
					buttonsToggleButton.classList.remove('active');
					document.body.classList.remove('hide-buttons');
				} else {
					buttonsToggleButton.classList.add('active');
					document.body.classList.add('hide-buttons');
				}
			}

			if (formsToggleButton) {
				if (
					e.currentTarget.classList.contains(
						'block-library__show-all'
					)
				) {
					formsToggleButton.classList.remove('active');
					document.body.classList.remove('hide-forms');
				} else {
					formsToggleButton.classList.add('active');
					document.body.classList.add('hide-forms');
				}
			}
		} else {
			categoryInputs = document.querySelectorAll(
				'ul[data-category="' + dataCategory + '"] .block-library__input'
			);
		}

		if (categoryInputs.length) {
			let checked = false;

			if (e.currentTarget.classList.contains('block-library__show-all')) {
				checked = true;
			}

			categoryInputs.forEach((input) => {
				input.checked = checked;
			});

			changeInput();
		}
	}
};

const changeInput = (e = true) => {
	const url = new URL(window.location);
	let visibleIds = []; // eslint-disable-line prefer-const
	let currentOrder = url.searchParams.get('order');

	if (e && e.currentTarget) {
		const otherInputs = document.querySelectorAll(
			'.block-library__input[value="' + e.currentTarget.value + '"]'
		);

		if (otherInputs.length > 1) {
			otherInputs.forEach((otherInput) => {
				if (e.currentTarget.checked) {
					otherInput.checked = true;
				} else {
					otherInput.checked = false;
				}
			});
		}
	}

	if (false !== e) {
		if (currentOrder) {
			currentOrder = currentOrder.split('_');
		} else if (sortable && sortable.el) {
			currentOrder = sortable.toArray();
		}
	}

	if (
		hoverLabelsToggleButton &&
		hoverLabelsToggleButton.classList.contains('active')
	) {
		url.searchParams.set('hide-hover-labels', '1');
	} else {
		url.searchParams.delete('hide-hover-labels');
	}

	if (
		overlaysToggleButton &&
		overlaysToggleButton.classList.contains('active')
	) {
		url.searchParams.set('show-overlays', '1');
	} else {
		url.searchParams.delete('show-overlays');
	}

	if (colorsToggleButton && colorsToggleButton.classList.contains('active')) {
		url.searchParams.set('hide-colors', '1');
	} else {
		url.searchParams.delete('hide-colors');
	}

	if (
		buttonsToggleButton &&
		buttonsToggleButton.classList.contains('active')
	) {
		url.searchParams.set('hide-buttons', '1');
	} else {
		url.searchParams.delete('hide-buttons');
	}

	if (formsToggleButton && formsToggleButton.classList.contains('active')) {
		url.searchParams.set('hide-forms', '1');
	} else {
		url.searchParams.delete('hide-forms');
	}

	if (simpleModeButton && simpleModeButton.classList.contains('active')) {
		url.searchParams.set('simple-mode', '1');
	} else {
		url.searchParams.delete('simple-mode');
	}

	inputs.forEach((input) => {
		const name = input.getAttribute('name');
		url.searchParams.delete(name);

		if (input.checked) {
			blockLibrarySection.classList.add('visible-' + name);
			url.searchParams.set(name, 'v');

			visibleIds.push(name);
		} else {
			blockLibrarySection.classList.remove('visible-' + name);
			url.searchParams.set(name, 'h');
		}
	});

	if (false !== e && currentOrder && sortable && sortable.el) {
		const visibleItems = currentOrder.filter((value) => {
			if (visibleIds.indexOf(value.split('-')[0]) >= 0) {
				return value;
			}

			return false;
		});

		currentOrder = currentOrder.filter((value) => {
			if (visibleItems.indexOf(value) === -1) {
				return value;
			}

			return false;
		});

		const newOrder = visibleItems.concat(currentOrder);

		sortable.sort(newOrder, true);

		url.searchParams.delete('order');
		url.searchParams.set('order', newOrder.join('_'));
	}

	if (
		qaOverlaysToggleButton &&
		qaOverlaysToggleButton.classList.contains('active')
	) {
		url.searchParams.set('show-qa-overlays', '1');

		removeBlockDividers();
		addBlockDividers();
	} else {
		url.searchParams.delete('show-qa-overlays');
		removeBlockDividers();
	}

	window.history.pushState({}, '', url);

	setParentBlockHeight();

	window.dispatchEvent(toggleEvent);
};

const hoverLabelsToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('hide-hover-labels');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('hide-hover-labels');
	}

	changeInput();
};

const overlaysToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('show-overlays');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('show-overlays');
	}

	changeInput();
};

const qaOverlaysToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('show-qa-overlays');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('show-qa-overlays');
	}

	changeInput();
};

const qaOpacityChange = () => {
	if (!qaOpacitySlider) {
		return;
	}

	document.body.style.setProperty(
		'--qa-opacity',
		`${qaOpacitySlider.value / 100}`
	);
};

const qaOpacityChanged = () => {
	const url = new URL(window.location);
	url.searchParams.set('qa-opacity', qaOpacitySlider.value);
	window.history.pushState({}, '', url);
};

const changeQaOpacity = (value) => {
	if (value > 100) {
		value = 100;
	} else if (value < 0) {
		value = 0;
	}

	const e = {};
	e.currentTarget = {};
	e.currentTarget.value = value;
	qaOpacitySlider.value = value;

	qaOpacityChange();
	qaOpacityChanged();
};

const qaOpacityReset = (e) => {
	e.preventDefault();
	changeQaOpacity(50);
};

const addBlockDividers = () => {
	childElements.forEach((childElement) => {
		if (childElement.offsetHeight) {
			if (childElement.hasAttribute('data-block-index')) {
				const blockDivider = document.createElement('div');
				blockDivider.classList.add('block-library__qa-divider');

				pageContent.insertBefore(blockDivider, childElement);
			}
		}
	});
};

const removeBlockDividers = () => {
	const blockDividers = document.querySelectorAll(
		'.block-library__qa-divider'
	);

	if (blockDividers.length > 0) {
		blockDividers.forEach((blockDivider) => {
			blockDivider.remove();
		});
	}
};

const colorsToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('hide-colors');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('hide-colors');
	}

	changeInput();
};

const buttonsToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('hide-buttons');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('hide-buttons');
	}

	changeInput();
};

const formsToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('hide-forms');
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('hide-forms');
	}

	changeInput();
};

const simpleModeToggle = (e) => {
	e.preventDefault();

	if (e.currentTarget.classList.contains('active')) {
		e.currentTarget.classList.remove('active');
		document.body.classList.remove('simple-mode');
		initializeSorting();
	} else {
		e.currentTarget.classList.add('active');
		document.body.classList.add('simple-mode');
		destroySorting();
	}

	changeInput();
};

const resetOrder = (e) => {
	e.preventDefault();
	const url = new URL(window.location);

	url.searchParams.delete('order');

	window.history.pushState({}, '', url);

	if (sortable && sortable.el) {
		sortable.sort(originalOrder, true);
	}

	changeInput();
};

const toggleMenus = (e) => {
	e.preventDefault();

	if (!e.currentTarget.classList.contains('active')) {
		navHeadingButtons.forEach((button) => {
			button.classList.remove('active');
		});
	}

	e.currentTarget.classList.toggle('active');
};

const setParentBlockHeight = () => {
	if (!childElements || qa) {
		return;
	}

	childElements.forEach((childElement) => {
		const styles = getComputedStyle(childElement);

		childElement.style.setProperty(
			'--blockHeight',
			`${
				parseFloat(styles.height) +
				parseFloat(styles.marginTop) +
				parseFloat(styles.marginBottom)
			}px`
		);

		childElement.style.setProperty(
			'--blockPaddingTop',
			`${styles.paddingTop}`
		);

		childElement.style.setProperty(
			'--blockPaddingBottom',
			`${styles.paddingBottom}`
		);

		childElement.style.setProperty(
			'--blockMarginTop',
			`${styles.marginTop}`
		);

		childElement.style.setProperty(
			'--blockMarginBottom',
			`${styles.marginBottom}`
		);

		childElement.style.setProperty(
			'--blockMarginLeft',
			`${styles.marginLeft}`
		);

		if (parseFloat(styles.marginLeft) > 20) {
			childElement.classList.add('offset-label');
		} else {
			childElement.classList.remove('offset-label');
		}
	});
};

const initMouseOver = () => {
	if (navWrappers.length && !qa) {
		let height;
		let multipleRows = false;

		for (const navWrapper of navWrappers) {
			if (navWrapper.offsetTop > height) {
				multipleRows = true;
				break;
			}

			height = navWrapper.offsetTop;
		}

		if (true === multipleRows) {
			navElement.classList.remove('hover-enabled');
		} else {
			navElement.classList.add('hover-enabled');
		}
	}
};

const addQaElements = (blockNames) => {
	const renderedChildElements = document.querySelectorAll(
		'.block-library__content-wrapper > *[data-block-title][data-block-index], .block-library__buttons-section'
	);

	renderedChildElements.forEach((childElement) => {
		let overlayElement = 'figure';
		const childElementType = childElement.tagName.toLowerCase();

		if ('ul' === childElementType || 'ol' === childElementType) {
			overlayElement = 'li';
		}

		if ('hr' === childElementType) {
			return;
		}

		let blockTitle = childElement.getAttribute('data-block-title');
		const blockIndex = childElement.getAttribute('data-block-index');
		const blockQaOverlay = document.createElement(overlayElement);
		const blockQaOverlayErrorDesktop = document.createElement('span');
		const blockQaOverlayErrorMobile = document.createElement('span');
		const blockQaOverlayImageDesktop = document.createElement('img');
		const blockQaOverlayImageMobile = document.createElement('img');
		const overlayErrorLabelDesktop = document.createTextNode(
			'Figma Desktop QA Image Not Found'
		);
		const overlayErrorLabelMobile = document.createTextNode(
			'Figma Mobile QA Image Not Found'
		);

		blockQaOverlay.classList.add('block-library__qa-overlay');

		blockQaOverlayErrorDesktop.classList.add(
			'block-library__qa-overlay-error-label',
			'block-library__qa-overlay-error-label--desktop'
		);
		blockQaOverlayErrorMobile.classList.add(
			'block-library__qa-overlay-error-label',
			'block-library__qa-overlay-error-label--mobile'
		);

		blockQaOverlayImageDesktop.classList.add(
			'block-library__qa-overlay-image',
			'block-library__qa-overlay-image--desktop'
		);
		blockQaOverlayImageMobile.classList.add(
			'block-library__qa-overlay-image',
			'block-library__qa-overlay-image--mobile'
		);

		blockQaOverlayImageDesktop.setAttribute('loading', 'lazy');
		blockQaOverlayImageMobile.setAttribute('loading', 'lazy');

		blockQaOverlayImageDesktop.addEventListener('error', (e) => {
			e.currentTarget.parentElement.classList.add(
				'block-library__qa-overlay--desktop-error'
			);
		});

		blockQaOverlayImageMobile.addEventListener('error', (e) => {
			e.currentTarget.parentElement.classList.add(
				'block-library__qa-overlay--mobile-error'
			);
		});

		if (childElement.classList.contains('block-library__buttons-section')) {
			blockTitle = 'Button-Styles';
		}

		let fileNameDesktop = `${blockTitle}-Desktop`;
		let fileNameMobile = `${blockTitle}-Mobile`;

		if (undefined === blockNames[fileNameDesktop]) {
			blockQaOverlay.classList.add(
				'block-library__qa-overlay--desktop-error'
			);
		} else if (blockNames[fileNameDesktop].length > 0) {
			fileNameDesktop = blockNames[fileNameDesktop][blockIndex];
		}

		if (undefined === blockNames[fileNameMobile]) {
			blockQaOverlay.classList.add(
				'block-library__qa-overlay--mobile-error'
			);
		} else if (blockNames[fileNameMobile].length > 0) {
			fileNameMobile = blockNames[fileNameMobile][blockIndex];
		}

		if ('Button-Styles' === blockTitle) {
			fileNameDesktop = blockTitle + '-' + blockIndex;

			blockQaOverlay.classList.remove(
				'block-library__qa-overlay--desktop-error'
			);

			childElement.addEventListener('mousedown', startDragButtonSection);
			childElement.addEventListener('mouseup', endDragButtonSection);
		}

		blockQaOverlayImageDesktop.src = `${catapult.stylesheetUrl}/qa/images/figma/${fileNameDesktop}.png`;
		blockQaOverlayImageMobile.src = `${catapult.stylesheetUrl}/qa/images/figma/${fileNameMobile}.png`;

		blockQaOverlayErrorDesktop.appendChild(overlayErrorLabelDesktop);
		blockQaOverlayErrorMobile.appendChild(overlayErrorLabelMobile);

		blockQaOverlay.appendChild(blockQaOverlayErrorDesktop);
		blockQaOverlay.appendChild(blockQaOverlayErrorMobile);

		blockQaOverlay.appendChild(blockQaOverlayImageDesktop);
		blockQaOverlay.appendChild(blockQaOverlayImageMobile);

		childElement.prepend(blockQaOverlay);
	});
};

const startDragButtonSection = (e) => {
	e.currentTarget.addEventListener('mousemove', dragButtonSection);
	e.currentTarget.setAttribute(
		'data-current-scrollX',
		e.currentTarget.scrollLeft
	);
	e.currentTarget.setAttribute('data-cursor-startX', e.pageX);
};

const endDragButtonSection = (e) => {
	e.currentTarget.removeEventListener('mousemove', dragButtonSection);
};

const dragButtonSection = (e) => {
	if (
		!e.currentTarget.hasAttribute('data-current-scrollX') ||
		!e.currentTarget.hasAttribute('data-cursor-startX')
	) {
		return;
	}

	e.preventDefault();

	e.currentTarget.scrollLeft =
		parseInt(e.currentTarget.getAttribute('data-current-scrollX')) +
		parseInt(e.currentTarget.getAttribute('data-cursor-startX')) -
		e.pageX;
};

const checkKeyDown = (e) => {
	if (
		!pageContent.ownerDocument.activeElement ||
		('TEXTAREA' !== pageContent.ownerDocument.activeElement.tagName &&
			'INPUT' !== pageContent.ownerDocument.activeElement.tagName)
	) {
		if (e.key && false === keydown) {
			keydown = true;

			if ('a' === e.key) {
				showAllButton.click();
			} else if ('h' === e.key) {
				hideAllButton.click();
			} else if ('l' === e.key) {
				hoverLabelsToggleButton.click();
			} else if ('o' === e.key) {
				overlaysToggleButton.click();
			} else if ('c' === e.key) {
				colorsToggleButton.click();
			} else if ('b' === e.key) {
				buttonsToggleButton.click();
			} else if ('f' === e.key) {
				formsToggleButton.click();
			} else if ('q' === e.key) {
				qaOverlaysToggleButton.click();
			} else if ('r' === e.key) {
				resetOrderButton.click();
			} else if ('s' === e.key) {
				simpleModeButton.click();
			} else if ('+' === e.key) {
				changeQaOpacity(parseInt(qaOpacitySlider.value) + 10);
			} else if ('-' === e.key) {
				changeQaOpacity(parseInt(qaOpacitySlider.value) - 10);
			} else if (!isNaN(parseInt(e.key))) {
				changeQaOpacity(parseInt(e.key) * 10);
			}
		}
	}
};

const setKeyUp = () => {
	keydown = false;
};

const initializeSorting = () => {
	if (sortable && sortable.el) {
		return;
	}

	sortable = Sortable.create(pageContent, {
		filter: '.block-library, .block-library__hero',
		dataIdAttr: 'data-block-id',
		onMove(evt) {
			if (
				evt.related.classList.contains('block-library') ||
				evt.related.classList.contains('block-library__hero')
			) {
				return false;
			}
		},
		onEnd() {
			const url = new URL(window.location);

			url.searchParams.delete('order');
			url.searchParams.set('order', sortable.toArray().join('_'));

			window.history.pushState({}, '', url);

			changeInput(false);
		},
	});
};

const destroySorting = () => {
	if (sortable) {
		sortable.destroy();
	}
};

if (childElements.length && !qa) {
	let blockID = '';
	let blockTitle = '';
	let counter = 0;
	let counterWithoutSpacers = 0;

	childElements.forEach((childElement) => {
		if (
			childElement.classList.contains(
				'block-library__section-placeholder'
			)
		) {
			blockID = childElement.getAttribute('data-block-id');
			blockTitle = childElement.getAttribute('data-block-title');
			childElement.remove();
			counter = 0;
			counterWithoutSpacers = 0;
		} else {
			counter++;

			childElement.setAttribute('data-block-id', blockID + '-' + counter);

			childElement.setAttribute(
				'data-block-index',
				counterWithoutSpacers
			);

			if (!childElement.classList.contains('wp-block-spacer')) {
				counterWithoutSpacers++;
			}

			childElement.setAttribute('data-block-title', blockTitle);

			if (childElement.classList.contains('block-inactive')) {
				const blockInventoryLabel = document.createElement('label');
				const blockInventoryLabelText =
					document.createTextNode('Inactive');
				blockInventoryLabel.classList.add(
					'block-library__inactive-label'
				);
				blockInventoryLabel.appendChild(blockInventoryLabelText);
				childElement.prepend(blockInventoryLabel);
			}

			let overlayElement = 'label';
			const childElementType = childElement.tagName.toLowerCase();

			if ('ul' === childElementType || 'ol' === childElementType) {
				overlayElement = 'li';
			}

			if ('hr' === childElementType) {
				return;
			}

			const blockLabel = document.createElement(overlayElement);
			const blockLabelText = document.createTextNode(blockTitle);
			blockLabel.classList.add('block-library__block-label');
			blockLabel.appendChild(blockLabelText);

			if (catapult && catapult.editPostsLink) {
				const blockLabelLink = document.createElement('a');
				const blockLabelLinkText = document.createTextNode('(Edit)');
				blockLabelLink.appendChild(blockLabelLinkText);
				blockLabelLink.href = JSON.parse(
					catapult.editPostsLink
				).replace('POSTID', blockID);
				blockLabelLink.setAttribute('target', '_blank');

				blockLabel.appendChild(blockLabelLink);
			}

			if (catapult && catapult.siteUrl) {
				const blockViewLink = document.createElement('a');
				const blockViewLinkText = document.createTextNode('(Link)');
				blockViewLink.appendChild(blockViewLinkText);
				blockViewLink.href =
					catapult.siteUrl + `/block-library/?${blockID}=v`;
				blockViewLink.setAttribute('target', '_blank');
				blockLabel.appendChild(blockViewLink);
			}

			const blockQALink = document.createElement('a');
			const blockQALinkText = document.createTextNode('(QA)');
			blockQALink.appendChild(blockQALinkText);
			blockQALink.href =
				catapult.siteUrl + `/block-library/?qa=${blockTitle}`;
			blockQALink.setAttribute('target', '_blank');
			blockLabel.appendChild(blockQALink);

			childElement.prepend(blockLabel);

			originalOrder.push(blockID + '-' + counter);
		}
	});

	childElements = document.querySelectorAll(
		'.block-library__content-wrapper > *'
	);

	if (catapult && catapult.stylesheetUrl) {
		fetch(`${catapult.stylesheetUrl}/qa/figma-and-wp-block-names.json`)
			.then((response) => response.json())
			.then(addQaElements);
	}
} else if (childElements.length && qa) {
	document.body.setAttribute('data-block', qa);
	childElements = document.querySelectorAll(
		'.block-library__content-wrapper > *:not(.wp-block-spacer)'
	);

	if (qaIndex) {
		qaIndex = parseInt(qaIndex);

		childElements.forEach((childElement, index) => {
			if (qaIndex !== index + 1) {
				if (1 === childElement.parentElement.children.length) {
					pageContent.classList.add('empty');
				}

				childElement.remove();
			}
		});
	}
}

const leadingParagraph = document.createElement('div');
const trailingParagraph = document.createElement('div');
leadingParagraph.classList.add('block-library__qa-placeholder');
trailingParagraph.classList.add('block-library__qa-placeholder');

pageContent.prepend(leadingParagraph);
pageContent.append(trailingParagraph);

if (
	pageContent &&
	navElement &&
	!qa &&
	!document.body.classList.contains('simple-mode')
) {
	initializeSorting();
}

if (blockLibrarySection && !qa) {
	if (inputs.length) {
		inputs.forEach((input) => {
			input.addEventListener('change', changeInput);
		});

		changeInput();
	}

	if (toggleButtons.length) {
		toggleButtons.forEach((toggleButton) => {
			toggleButton.addEventListener('click', toggleInputs);
		});
	}

	if (hoverLabelsToggleButton) {
		hoverLabelsToggleButton.addEventListener('click', hoverLabelsToggle);
	}

	if (overlaysToggleButton) {
		overlaysToggleButton.addEventListener('click', overlaysToggle);
	}

	if (qaOverlaysToggleButton) {
		qaOverlaysToggleButton.addEventListener('click', qaOverlaysToggle);
	}

	if (qaOpacitySlider) {
		qaOpacitySlider.addEventListener('input', qaOpacityChange);
		qaOpacitySlider.addEventListener('change', qaOpacityChanged);

		if (qaOpacityResetButton) {
			qaOpacityResetButton.addEventListener('click', qaOpacityReset);
		}
	}

	if (colorsToggleButton) {
		colorsToggleButton.addEventListener('click', colorsToggle);
	}

	if (buttonsToggleButton) {
		buttonsToggleButton.addEventListener('click', buttonsToggle);
	}

	if (formsToggleButton) {
		formsToggleButton.addEventListener('click', formsToggle);
	}

	if (resetOrderButton) {
		resetOrderButton.addEventListener('click', resetOrder);
	}

	if (simpleModeButton) {
		simpleModeButton.addEventListener('click', simpleModeToggle);
	}

	document.addEventListener('keydown', checkKeyDown);
	document.addEventListener('keyup', setKeyUp);
}

if (navHeadingButtons && !qa) {
	initMouseOver();

	navHeadingButtons.forEach((button) => {
		button.addEventListener('click', toggleMenus);
	});
}

document.body.classList.add('block-library-initialized');

if (
	qaOverlaysToggleButton &&
	qaOverlaysToggleButton.classList.contains('active')
) {
	addBlockDividers();
}

setParentBlockHeight();
qaOpacityChange();

const resizeFunctions = () => {
	initMouseOver();
	setParentBlockHeight();
};

window.addEventListener('resize', resizeFunctions);
window.dispatchEvent(initEvent);
