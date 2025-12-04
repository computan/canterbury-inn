import { disableBodyScroll, clearAllBodyScrollLocks } from 'body-scroll-lock';
import throttle from 'lodash/throttle';

const openEvent = new Event('navigation-opened');
const closeEvent = new Event('navigation-closed');

const scrollableElements = document.querySelectorAll(
	'.block-navigation__menu, .block-navigation.is-style-full-width-dropdown .block-navigation-submenu'
);

const navigationBlocks = document.querySelectorAll('.block-navigation');

const navigationHamburgers = document.querySelectorAll(
	'.block-navigation__hamburger'
);
const mainHeader = document.querySelector('.main-header');
const blockNav = document.querySelector('.main-header > nav.block-navigation');
const topUtility = document.querySelector('.block-navigation-top-utility');

const toggleNavigationHamburger = (e) => {
	const currentNavigationHamburger = e?.currentTarget ?? e;
	currentNavigationHamburger.classList.toggle('active');

	if (currentNavigationHamburger.classList.contains('active')) {
		window.dispatchEvent(openEvent);
		setFirstLevelButtonBlockOffsets();
		setNavMenuActiveClass(currentNavigationHamburger, true);
		currentNavigationHamburger.setAttribute('aria-expanded', true);

		if (window.innerWidth < 992) {
			lockScroll();
		}
	} else {
		window.dispatchEvent(closeEvent);

		clearAllBodyScrollLocks();

		setNavMenuActiveClass(currentNavigationHamburger, false);
		currentNavigationHamburger.setAttribute('aria-expanded', false);
	}
};

const submenuClosed = () => {
	if (window.innerWidth >= 992) {
		clearAllBodyScrollLocks();
	}
};

const setFirstLevelButtonBlockOffsets = () => {
	if (0 === navigationBlocks.length) {
		return;
	}

	navigationBlocks.forEach((navigationBlock) => {
		const firstLevelButtonBlocks = navigationBlock.querySelectorAll(
			'.block-navigation__menu > .wp-block-button'
		);

		if (0 === firstLevelButtonBlocks.length) {
			return;
		}

		let totalOffset = 0;

		for (let i = firstLevelButtonBlocks.length - 1; i >= 0; i--) {
			const firstLevelButtonBlock = firstLevelButtonBlocks[i];

			firstLevelButtonBlock.style.setProperty(
				'--buttonOffset',
				`${totalOffset / 16}rem`
			);

			totalOffset += firstLevelButtonBlock.offsetHeight;
		}

		navigationBlock.style.setProperty(
			'--totalButtonOffset',
			`${totalOffset / 16}rem`
		);
	});
};

const setNavMenuActiveClass = (element, navActive = null) => {
	const currentElement = element?.detail?.currentElement ?? element;

	if (!currentElement) {
		return;
	}

	const currentNavigationBlock = currentElement.closest('.block-navigation');

	if (!currentNavigationBlock) {
		return;
	}

	if (null === navActive) {
		if (
			currentElement.classList.contains('active') ||
			currentElement.parentElement.classList.contains('active') ||
			currentNavigationBlock.querySelector(
				'.block-navigation__hamburger.active'
			)
		) {
			navActive = true;
		} else {
			navActive = false;
		}
	}

	if (true === navActive) {
		currentNavigationBlock.classList.add('active');
		document.addEventListener('keydown', clickOffMenu);
		document.addEventListener('click', clickOffMenu);
	} else {
		currentNavigationBlock.classList.remove('active');
		document.removeEventListener('keydown', clickOffMenu);
		document.removeEventListener('click', clickOffMenu);
	}

	navigationBlocks.forEach((navigationBlock) => {
		if (navigationBlock !== currentNavigationBlock) {
			navigationBlock.classList.remove('active');
		}
	});
};

const lockScroll = () => {
	if (scrollableElements.length) {
		scrollableElements.forEach(function (scrollableElement) {
			const storedRequestAnimationFrame = window.requestAnimationFrame;
			window.requestAnimationFrame = () => 42;

			disableBodyScroll(scrollableElement, {
				reserveScrollBarGap: true,
			});
			window.requestAnimationFrame = storedRequestAnimationFrame;
		});
	}
};

const setHeaderExtras = () => {
	// ⭐ Sticky classes on scroll (main-header sticky removed)
	window.addEventListener('scroll', () => {
		if (window.scrollY > 0) {
			if (blockNav) blockNav.classList.add('scrolled');
			if (topUtility) topUtility.classList.add('sticky');
		} else {
			if (blockNav) blockNav.classList.remove('scrolled');
			if (topUtility) topUtility.classList.remove('sticky');
		}
	});

	window.addEventListener('click', () => {
		const blockNavActive =
			blockNav.classList.contains('active') ||
			blockNav.classList.contains('search-active');

		if (blockNavActive) {
			mainHeader.classList.add('menu-open');

			lockScroll();
			document.body.classList.add('scroll-lock-overlay');
		} else {
			mainHeader.classList.remove('menu-open');

			clearAllBodyScrollLocks();
			document.body.classList.remove('scroll-lock-overlay');
		}
	});
};

const clickOffMenu = (e) => {
	if (0 === navigationHamburgers.length) {
		return;
	}

	let closestMenu;
	let closestHamburger;

	if (e.key === undefined && e.target) {
		closestMenu = e.target.closest('.block-navigation__menu');
		closestHamburger = e.target.closest('.block-navigation__hamburger');
	}

	if (e.key && e.key !== 'Escape') {
		return;
	}

	if (
		closestMenu?.contains(e.target) ||
		closestHamburger?.contains(e.target)
	) {
		return;
	}

	navigationHamburgers.forEach((navigationHamburger) => {
		if (navigationHamburger.classList.contains('active')) {
			toggleNavigationHamburger(navigationHamburger);
		}
	});

	window.dispatchEvent(closeEvent);
};

if (navigationHamburgers.length > 0) {
	navigationHamburgers.forEach((navigationHamburger) => {
		navigationHamburger.addEventListener(
			'click',
			toggleNavigationHamburger
		);
	});
}

setFirstLevelButtonBlockOffsets();
setHeaderExtras();

if (navigationBlocks.length > 0) {
	navigationBlocks.forEach((navigationBlock) => {
		navigationBlock.classList.add('block-navigation--initialized');
	});
}

window.addEventListener('navigation-submenu-closed', submenuClosed);
window.addEventListener('navigation-submenu-closed', setNavMenuActiveClass);
window.addEventListener('navigation-submenu-opened', setNavMenuActiveClass);

window.addEventListener(
	'resize',
	throttle(setFirstLevelButtonBlockOffsets, 100)
);
