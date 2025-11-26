const navigationLinks = document.querySelectorAll(
	'.block-navigation-link__button--with-submenu'
);

const toggleNavigationLink = (e) => {
	const currentNavigationLink = e.currentTarget;
	currentNavigationLink.parentElement.classList.toggle('active');

	if (currentNavigationLink.parentElement.classList.contains('active')) {
		navigationLinks.forEach((navigationLink) => {
			if (navigationLink === currentNavigationLink) {
				return;
			}

			navigationLink.parentElement.classList.remove('active');
			navigationLink.setAttribute('aria-expanded', false);
		});

		currentNavigationLink.setAttribute('aria-expanded', true);
	} else {
		currentNavigationLink.setAttribute('aria-expanded', false);
	}

	if (currentNavigationLink.parentElement.classList.contains('active')) {
		const openEvent = new CustomEvent('navigation-submenu-opened', {
			detail: { currentElement: currentNavigationLink },
		});

		window.dispatchEvent(openEvent);
	} else {
		const closeEvent = new CustomEvent('navigation-submenu-closed', {
			detail: { currentElement: currentNavigationLink },
		});

		window.dispatchEvent(closeEvent);
	}
};

const closeNavigationLinks = () => {
	if (0 === navigationLinks.length) {
		return;
	}

	navigationLinks.forEach((navigationLink) => {
		navigationLink.parentElement.classList.remove('active');
		navigationLink.setAttribute('aria-expanded', false);
	});

	const closeEvent = new CustomEvent('navigation-submenu-closed', {
		detail: { currentElement: navigationLinks[0] },
	});

	window.dispatchEvent(closeEvent);
};

if (navigationLinks.length > 0) {
	navigationLinks.forEach((navigationLink) => {
		navigationLink.addEventListener('click', toggleNavigationLink);
	});
}

window.addEventListener('navigation-opened', closeNavigationLinks);
window.addEventListener('navigation-closed', closeNavigationLinks);
window.addEventListener('navigation-search-opened', closeNavigationLinks);
