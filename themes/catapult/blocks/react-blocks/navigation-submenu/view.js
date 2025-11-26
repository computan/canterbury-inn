import throttle from 'lodash/throttle';
const navigationSubMenus = document.querySelectorAll(
	'.block-navigation-submenu'
);
const navigationBackButtons = document.querySelectorAll(
	'.block-navigation-submenu__back-button'
);

const clickNavigationBackButton = (e) => {
	const currentNavigationBackButton = e.currentTarget;
	const navigationLink = currentNavigationBackButton.closest(
		'.block-navigation-link'
	);

	if (!navigationLink) {
		return;
	}

	navigationLink.classList.remove('active');
};

const setMenuOffset = () => {
	if (0 === navigationSubMenus.length) {
		return;
	}

	navigationSubMenus.forEach((navigationSubMenu) => {
		navigationSubMenu.style.setProperty('--submenuOffset', `0rem`);

		const navigationSubMenuRect = navigationSubMenu.getBoundingClientRect();

		if (0 === navigationSubMenuRect.width || !navigationSubMenuRect.x) {
			return;
		}

		const navigationSubMenuLeft = navigationSubMenuRect.x;
		const navigationSubMenuRight =
			navigationSubMenuRect.x + navigationSubMenuRect.width;
		const navigationBlock = navigationSubMenu.closest(
			'.block-navigation__container'
		);
		let navigationBlockWidth = window.innerWidth;

		if (navigationBlock) {
			const navigationBlockStyles =
				window.getComputedStyle(navigationBlock);
			navigationBlockWidth =
				navigationBlock.offsetWidth -
				parseFloat(navigationBlockStyles['padding-left']) -
				parseFloat(navigationBlockStyles['padding-right']);
		}

		const containerLeft = (window.innerWidth - navigationBlockWidth) / 2;
		const containerRight = containerLeft + navigationBlockWidth;
		let offset = 0;

		if (navigationSubMenuLeft < containerLeft) {
			offset = containerLeft - navigationSubMenuLeft;
		} else if (navigationSubMenuRight > containerRight) {
			offset = containerRight - navigationSubMenuRight;
		}

		navigationSubMenu.style.setProperty(
			'--submenuOffset',
			`${offset / 16}rem`
		);
	});
};

if (navigationBackButtons.length > 0) {
	navigationBackButtons.forEach((navigationBackButton) => {
		navigationBackButton.addEventListener(
			'click',
			clickNavigationBackButton
		);
	});
}

setMenuOffset();

window.addEventListener('navigation-submenu-opened', setMenuOffset);
window.addEventListener('resize', throttle(setMenuOffset, 100));
window.addEventListener('load', setMenuOffset);
