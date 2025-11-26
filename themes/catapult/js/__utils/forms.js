import { __ } from '@wordpress/i18n';

const addNumberInputButtons = () => {
	const numberInputs = document.querySelectorAll('input[type="number"]');

	if (numberInputs.length <= 0) {
		return;
	}

	numberInputs.forEach(function (numberInput) {
		const wrapper = document.createElement('div');
		const decreaseButton = document.createElement('button');
		const increaseButton = document.createElement('button');

		decreaseButton.setAttribute('type', 'button');
		increaseButton.setAttribute('type', 'button');

		decreaseButton.setAttribute(
			'aria-label',
			__('Decrease value.', 'catapult')
		);
		increaseButton.setAttribute(
			'aria-label',
			__('Increase value.', 'catapult')
		);

		wrapper.classList.add('input-number');
		numberInput.classList.add('input-number__input');
		decreaseButton.classList.add(
			'input-number__button',
			'input-number__button--decrease'
		);
		increaseButton.classList.add(
			'input-number__button',
			'input-number__button--increase'
		);

		decreaseButton.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.nextElementSibling.nextElementSibling.stepDown();
		});

		increaseButton.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.nextElementSibling.stepUp();
		});

		if (
			!numberInput.hasAttribute('step') ||
			'any' === numberInput.getAttribute('step')
		) {
			numberInput.setAttribute('step', 1);
		}

		numberInput.parentNode.insertBefore(wrapper, numberInput);
		wrapper.appendChild(decreaseButton);
		wrapper.appendChild(increaseButton);
		wrapper.appendChild(numberInput);
	});
};

const addFileInputButtons = () => {
	const fileInputs = document.querySelectorAll('input[type="file"]');

	if (fileInputs.length <= 0) {
		return;
	}

	fileInputs.forEach(function (fileInput) {
		const wrapper = document.createElement('div');
		const fileButton = document.createElement('button');
		const fileCancelButton = document.createElement('button');

		fileButton.setAttribute('type', 'button');
		fileCancelButton.setAttribute('type', 'button');

		fileButton.innerText = __('Choose file.', 'catapult');
		fileCancelButton.setAttribute(
			'aria-label',
			__('Remove file.', 'catapult')
		);

		wrapper.classList.add('input-file');
		fileInput.classList.add('input-file__input');
		fileButton.classList.add('input-file__button');
		fileCancelButton.classList.add('input-file__cancel-button');

		fileButton.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.nextElementSibling.click();
		});

		fileCancelButton.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.previousElementSibling.value = '';
			e.currentTarget.previousElementSibling.classList.remove(
				'input-file__input--has-value'
			);
		});

		fileInput.parentNode.insertBefore(wrapper, fileInput);
		wrapper.appendChild(fileButton);
		wrapper.appendChild(fileInput);
		wrapper.appendChild(fileCancelButton);

		fileInput.addEventListener('change', (e) => {
			if (e.currentTarget.value && e.currentTarget.value !== '') {
				e.currentTarget.classList.add('input-file__input--has-value');
			} else {
				e.currentTarget.classList.remove(
					'input-file__input--has-value'
				);
			}
		});
	});
};

const addSearchInputButtons = () => {
	const searchInputs = document.querySelectorAll(
		'input[type="search"]:not(.custom-search-input)'
	);

	if (searchInputs.length <= 0) {
		return;
	}

	searchInputs.forEach(function (searchInput) {
		const wrapper = document.createElement('div');
		const submitButton = document.createElement('button');
		const clearButton = document.createElement('button');

		submitButton.setAttribute('type', 'button');
		clearButton.setAttribute('type', 'button');

		submitButton.setAttribute('aria-label', __('Search.', 'catapult'));
		clearButton.setAttribute('aria-label', __('Clear search.', 'catapult'));

		wrapper.classList.add('input-search');
		searchInput.classList.add('input-search__input');
		submitButton.classList.add(
			'input-search__button',
			'input-search__button--submit'
		);
		clearButton.classList.add(
			'input-search__button',
			'input-search__button--clear'
		);

		submitButton.addEventListener('click', (e) => {
			e.preventDefault();
			const currentForm = e.target.closest('form');

			if (currentForm) {
				currentForm.submit();
			}
		});

		clearButton.addEventListener('click', (e) => {
			e.preventDefault();
			e.currentTarget.nextElementSibling.value = '';
		});

		if (
			!searchInput.hasAttribute('step') ||
			'any' === searchInput.getAttribute('step')
		) {
			searchInput.setAttribute('step', 1);
		}

		searchInput.parentNode.insertBefore(wrapper, searchInput);
		wrapper.appendChild(submitButton);
		wrapper.appendChild(clearButton);
		wrapper.appendChild(searchInput);
	});
};

const Forms = () => {
	addNumberInputButtons();
	addFileInputButtons();
	addSearchInputButtons();
};

export default Forms;
