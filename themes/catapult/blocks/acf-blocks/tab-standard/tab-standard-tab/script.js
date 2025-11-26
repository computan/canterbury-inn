const tabButtons = document.querySelectorAll(
	'.block-tab-standard-tab > .wp-block-button .wp-block-button__link'
);

const tabBlocks = document.querySelectorAll('.block-tab-standard');

const toggleTab = (e) => {
	e.preventDefault();

	const tabBlock = e.currentTarget.closest('.block-tab-standard');

	if (tabBlock) {
		const activeTabs = tabBlock.querySelectorAll(
			'.block-tab-standard-tab > .wp-block-button.active'
		);

		if (activeTabs.length) {
			activeTabs.forEach((activeTab) => {
				if (activeTab !== e.currentTarget.parentElement) {
					activeTab.classList.remove('active');
				}
			});
		}

		e.currentTarget.parentElement.classList.add('active');

		const selectElement = tabBlock.querySelector(
			'.block-tab-standard__mobile-select'
		);
		const tab = e.currentTarget.closest('.block-tab-standard-tab');

		if (tab && tab.hasAttribute('data-index') && selectElement) {
			selectElement.value = tab.getAttribute('data-index');
		}
	}
};

const mobileSelect = (e) => {
	const tabBlock = e.currentTarget.closest('.block-tab-standard');

	if (tabBlock) {
		const activeTabs = tabBlock.querySelectorAll(
			'.block-tab-standard-tab > .wp-block-button.active'
		);
		const currentTabButton = tabBlock.querySelector(
			'.block-tab-standard-tab[data-index="' +
				e.currentTarget.value +
				'"] > .wp-block-button'
		);

		if (currentTabButton) {
			if (activeTabs.length) {
				activeTabs.forEach((activeTab) => {
					if (activeTab !== e.currentTarget.parentElement) {
						activeTab.classList.remove('active');
					}
				});
			}

			currentTabButton.classList.add('active');
		}
	}
};

if (tabButtons.length) {
	tabButtons.forEach((tabButton) => {
		tabButton.addEventListener('click', toggleTab);
	});
}

if (tabBlocks.length) {
	tabBlocks.forEach((tabBlock) => {
		const tabs = tabBlock.querySelectorAll('.block-tab-standard-tab');

		if (tabs.length) {
			const selectElement = tabBlock.querySelector(
				'.block-tab-standard__mobile-select'
			);

			if (!selectElement) {
				return;
			}

			tabs.forEach((tab, index) => {
				const tabButton = tab.querySelector(
					'.block-tab-standard-tab > .wp-block-button'
				);

				if (tabButton) {
					const option = document.createElement('option');
					option.value = index;
					option.text = tabButton.textContent;
					selectElement.appendChild(option);
					tab.setAttribute('data-index', index);

					if (index + 1 === tabs.length) {
						selectElement.addEventListener('change', mobileSelect);
						tab.parentElement.appendChild(selectElement);
					}
				}

				tabButton.classList.add('initialized');

				if (0 === index) {
					tabButton.classList.add('active');
				}
			});
		}
	});
}
