const navigationBlocks = document.querySelectorAll('.block-navigation');
const searchButtonBlocks = document.querySelectorAll(
	'.block-navigation-search-button'
);

const openEvent = new Event('navigation-search-opened');
const closeEvent = new Event('navigation-search-closed');

if (searchButtonBlocks.length > 0) {
	searchButtonBlocks.forEach((searchButtonBlock) => {
		const currentNavigationBlock = searchButtonBlock.closest(
			'.block-navigation__container'
		);
		const searchDropdown = searchButtonBlock.querySelector(
			'.block-navigation-search-button__search'
		);
		const searchButton = searchButtonBlock.querySelector(
			'.block-navigation-search-button__button'
		);

		if (currentNavigationBlock && searchDropdown && searchButton) {
			const mobileButton = searchButton.cloneNode(true);
			mobileButton.classList.add(
				'block-navigation-search-button__button--mobile'
			);

			currentNavigationBlock.appendChild(mobileButton);
			currentNavigationBlock.appendChild(searchDropdown);
		}
	});
}

const searchButtons = document.querySelectorAll(
	'.block-navigation-search-button__button'
);

const toggleSearch = (e) => {
	const currentSearchButton = e.currentTarget;

	const currentNavigationBlock =
		currentSearchButton.closest('.block-navigation');

	if (!currentNavigationBlock) {
		return;
	}

	currentNavigationBlock.classList.toggle('search-active');

	if (currentNavigationBlock.classList.contains('search-active')) {
		navigationBlocks.forEach((navigationBlock) => {
			if (navigationBlock === currentNavigationBlock) {
				return;
			}

			navigationBlock.classList.remove('search-active');

			const openElements = navigationBlock.querySelectorAll(
				'[aria-expanded="true"]'
			);

			if (openElements.length > 0) {
				openElements.forEach((openElement) => {
					openElement.setAttribute('aria-expanded', false);
				});
			}
		});

		currentSearchButton.setAttribute('aria-expanded', true);
	} else {
		currentSearchButton.setAttribute('aria-expanded', false);
	}

	if (currentNavigationBlock.classList.contains('search-active')) {
		window.dispatchEvent(openEvent);

		document.addEventListener('keydown', clickOffMenu);
		document.addEventListener('click', clickOffMenu);
	} else {
		window.dispatchEvent(closeEvent);

		document.removeEventListener('keydown', clickOffMenu);
		document.removeEventListener('click', clickOffMenu);
	}
};

const closeSearch = () => {
	if (0 === searchButtons.length || 0 === navigationBlocks) {
		return;
	}

	navigationBlocks.forEach((navigationBlock) => {
		navigationBlock.classList.remove('search-active');
	});

	searchButtons.forEach((searchButton) => {
		searchButton.setAttribute('aria-expanded', false);
	});

	window.dispatchEvent(closeEvent);

	document.removeEventListener('keydown', clickOffMenu);
	document.removeEventListener('click', clickOffMenu);
};

const clickOffMenu = (e) => {
	if (0 === navigationBlocks.length) {
		return;
	}

	let closestSearch;
	let closestSearchButton;

	if (e.key === undefined && e.target) {
		closestSearch = e.target.closest(
			'.block-navigation-search-button__search'
		);
		closestSearchButton = e.target.closest(
			'.block-navigation-search-button__button'
		);
	}

	if (e.key && e.key !== 'Escape') {
		return;
	}

	if (
		closestSearch?.contains(e.target) ||
		closestSearchButton?.contains(e.target)
	) {
		return;
	}

	navigationBlocks.forEach((navigationBlock) => {
		if (navigationBlock.classList.contains('search-active')) {
			navigationBlock.classList.remove('search-active');

			document.removeEventListener('keydown', clickOffMenu);
			document.removeEventListener('click', clickOffMenu);

			const openSearchButtons = navigationBlock.querySelectorAll(
				'.block-navigation-search-button__button[aria-expanded="true"]'
			);

			if (openSearchButtons.length > 0) {
				openSearchButtons.forEach((openSearchButton) => {
					openSearchButton.setAttribute('aria-expanded', false);
				});
			}
		}
	});

	window.dispatchEvent(closeEvent);
};

if (searchButtons.length > 0) {
	searchButtons.forEach((searchButton) => {
		searchButton.addEventListener('click', toggleSearch);
	});
}

window.addEventListener('navigation-opened', closeSearch);
window.addEventListener('navigation-submenu-opened', closeSearch);
