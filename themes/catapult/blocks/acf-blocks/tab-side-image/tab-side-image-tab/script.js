const tabButtons = document.querySelectorAll(
	'.block-tab-side-image-tab > .wp-block-button .wp-block-button__link'
);

const tabBlocks = document.querySelectorAll('.block-tab-side-image');

const toggleTab = (e) => {
	e.preventDefault();

	const tabBlock = e.currentTarget.closest('.block-tab-side-image');

	if (tabBlock) {
		const activeTabs = tabBlock.querySelectorAll(
			'.block-tab-side-image-tab > .wp-block-button.active'
		);

		if (activeTabs.length) {
			activeTabs.forEach((activeTab) => {
				if (activeTab !== e.currentTarget.parentElement) {
					activeTab.classList.remove('active');
					activeTab.parentElement.classList.remove('active');
				}
			});
		}

		e.currentTarget.parentElement.classList.add('active');
		e.currentTarget.parentElement.parentElement.classList.add('active');
	}
};

if (tabButtons.length) {
	tabButtons.forEach((tabButton) => {
		tabButton.addEventListener('click', toggleTab);
	});
}

if (tabBlocks.length) {
	tabBlocks.forEach((tabBlock) => {
		const tabs = tabBlock.querySelectorAll('.block-tab-side-image-tab');

		if (tabs.length) {
			tabs.forEach((tab, index) => {
				const tabButton = tab.querySelector(
					'.block-tab-side-image-tab > .wp-block-button'
				);

				tabButton.classList.add('initialized');

				if (0 === index) {
					tabButton.classList.add('active');
					tabButton.parentElement.classList.add('active');
				}
			});
		}
	});
}
