const tablesFlip = document.querySelectorAll('.wp-block-table.is-style-flip');
const tablesStack = document.querySelectorAll('.wp-block-table.is-style-stack');

if (tablesFlip.length > 0) {
	tablesFlip.forEach((table) => {
		const firstRowItems = table.querySelectorAll('tbody td');

		if (firstRowItems.length > 0) {
			firstRowItems.forEach((firstRowItem, index) => {
				const items = table.querySelectorAll(
					'th:nth-child(' +
						(index + 1) +
						'), td:nth-child(' +
						(index + 1) +
						')'
				);

				if (items.length > 0) {
					items.forEach((item) => {
						item.style.setProperty('--mobileRow', index + 1);
					});
				}
			});
		}
	});
}

if (tablesStack.length > 0) {
	tablesStack.forEach((table) => {
		const headings = table.querySelectorAll('thead th');

		if (headings.length > 0) {
			headings.forEach((heading, index) => {
				const items = table.querySelectorAll(
					'td:nth-child(' + (index + 1) + ')'
				);

				if (items.length > 0) {
					items.forEach((item) => {
						const label = document.createElement('div');
						const text = document.createTextNode(heading.innerText);
						label.classList.add('wp-block-table__mobile-label');

						label.appendChild(text);
						item.insertBefore(label, item.firstChild);
					});
				}
			});
		}
	});
}
