const alertContainer = document.querySelector('.block-alert-top');

//Add height to body for other fixed/absolute elements
if (alertContainer) {
	const alertObserver = new ResizeObserver((entries) => {
		entries.forEach(() => {
			const alertContainerTop = alertContainer.offsetTop;
			const alertContainerHeight = alertContainer.offsetHeight;

			document.body.style.setProperty(
				'--alert-height',
				`${(alertContainerHeight + alertContainerTop) / 16}rem`
			);
		});
	});

	alertObserver.observe(alertContainer);
}
