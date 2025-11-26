const detectTabNav = (e) => {
	if (e.keyCode === 9) {
		document.querySelector('html').classList.add('user-tab-nav');

		window.removeEventListener('keydown', detectTabNav);
		window.addEventListener('mousedown', detectMouseNav);
	}
};

const detectMouseNav = () => {
	document.querySelector('html').classList.remove('user-tab-nav');

	window.removeEventListener('mousedown', detectMouseNav);
	window.addEventListener('keydown', detectTabNav);
};

export default detectTabNav;
