// Function to handle search clear functionality
function searchClearFunc() {
	const searchFieldInput = document.querySelector('.search-field__input');
	function checkSearchVal() {
		const searchFieldClear = document.querySelector(
			'.search .search-field__clear'
		);

		if (searchFieldInput && searchFieldClear) {
			if (searchFieldInput.value === '') {
				searchFieldClear.classList.remove('active');
			} else {
				searchFieldClear.classList.add('active');
			}
		}
	}

	checkSearchVal();

	if (searchFieldInput) {
		searchFieldInput.addEventListener('keyup', function () {
			checkSearchVal();
			searchEmptyResult();
		});
	}

	const searchFieldClear = document.querySelector(
		'.search .search-field__clear'
	);
	if (searchFieldClear) {
		searchFieldClear.addEventListener('click', function (e) {
			e.preventDefault();
			const searchHero = document.querySelector('.search .search-hero');
			if (searchFieldInput && searchHero) {
				searchFieldInput.value = '';
				searchHero.classList.add('search-empty');
				searchFieldClear.classList.remove('active');
				searchResultValue();
			}
		});
	}

	const clearSearchBtn = document.querySelector(
		'.search .no-result__buttons .clear-search-btn'
	);
	if (clearSearchBtn) {
		clearSearchBtn.addEventListener('click', function (e) {
			e.preventDefault();

			const mainHeader = document.querySelector('.search .main-header');
			if (searchFieldInput && mainHeader) {
				const searchFieldTop =
					searchFieldInput.getBoundingClientRect().top +
					window.scrollY;
				const scrollPosition = window.scrollY;
				const viewportHeight = window.innerHeight;
				const headerHeight = mainHeader.offsetHeight;
				const scrollTarget = searchFieldTop - (headerHeight + 140);
				const searchFieldHeight = searchFieldInput.offsetHeight;

				if (
					searchFieldTop >= scrollPosition &&
					searchFieldTop + searchFieldHeight <=
						scrollPosition + viewportHeight
				) {
					clearSearchInputAndFocus();
				} else {
					window.scrollTo({
						top: scrollTarget,
						behavior: 'smooth',
					});
					window.setTimeout(clearSearchInputAndFocus, 500);
				}
			}
		});
	}
}

function clearSearchInputAndFocus() {
	const searchFieldInput = document.querySelector('.search-field__input');
	const searchHero = document.querySelector('.search .search-hero');
	const searchFieldClear = document.querySelector(
		'.search .search-field__clear'
	);
	if (searchFieldInput && searchHero && searchFieldClear) {
		searchFieldInput.value = '';
		searchHero.classList.add('search-empty');
		searchFieldClear.classList.remove('active');
		searchFieldInput.focus();
		searchResultValue();
	}
}

function searchEmptyResult() {
	const searchFieldInput = document.querySelector(
		'.search .search-field__input'
	);
	if (searchFieldInput) {
		searchFieldInput.classList.remove('empty-search');
	}
}

function searchResultValue() {
	const searchResult = document.querySelector('.no-result-text p');
	if (searchResult) {
		searchResult.textContent = '';
	}
}

function searchClear() {
	searchClearFunc();
}

document.addEventListener('DOMContentLoaded', function () {
	searchClear();
});
export default searchClear;
