const viewportUnits = () => {
	const rootFontSize = parseFloat(
		getComputedStyle(document.documentElement).fontSize
	);

	document.documentElement.style.setProperty(
		'--vh',
		`${(window.innerHeight * 0.01) / rootFontSize}rem`
	);

	document.documentElement.style.setProperty(
		'--vw',
		`${(document.body.clientWidth * 0.01) / rootFontSize}rem`
	);
};

export default viewportUnits;
