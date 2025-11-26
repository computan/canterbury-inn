const setHeaderHeight = () => {
	const header = document.querySelector('.main-header');
	const adminBar = document.getElementById('wpadminbar');

	const headerHeight = header ? header.offsetHeight : 0;
	const adminBarHeight = adminBar ? adminBar.offsetHeight : 0;

	document.body.style.setProperty(
		'--header-height',
		`${(headerHeight + adminBarHeight) / 16}rem`
	);
};

export default setHeaderHeight;
