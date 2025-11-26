import { __ } from '@wordpress/i18n';

const removeHubSpotFormStyles = () => {
	const hubspotForm = document.querySelectorAll(
		'.hbspt-form form.hs-custom-style'
	);

	if (hubspotForm.length > 0) {
		hubspotForm.forEach(function (form) {
			form.classList.remove('hs-custom-style');
		});
	}
};

const adjustHubSpotForms = () => {
	const singleCheckboxListItems = document.querySelectorAll(
		'li.hs-form-booleancheckbox:only-child'
	);

	if (singleCheckboxListItems.length > 0) {
		singleCheckboxListItems.forEach((singleCheckboxListItem) => {
			if (singleCheckboxListItem.parentElement) {
				const placeHolderItem = document.createElement('li');
				placeHolderItem.classList.add('sr-only');
				singleCheckboxListItem.parentElement.appendChild(
					placeHolderItem
				);
			}
		});
	}

	const requiredAsterisks = document.querySelectorAll('.hs-form-required');

	if (requiredAsterisks.length > 0) {
		requiredAsterisks.forEach((requiredAsterisk) => {
			const requiredText = document.createElement('span');
			requiredText.innerText = __('(Required)', 'catapult');
			requiredText.classList.add('sr-only');

			requiredAsterisk.appendChild(requiredText);
		});
	}

	const inputsNeedingRequiredAttribute = document.querySelectorAll(
		'.hbspt-form .inputs-list[required] input:not([required])'
	);

	if (inputsNeedingRequiredAttribute.length > 0) {
		inputsNeedingRequiredAttribute.forEach(
			(inputNeedingRequiredAttribute) => {
				inputNeedingRequiredAttribute.setAttribute('required', '');
			}
		);
	}
};

removeHubSpotFormStyles();
adjustHubSpotForms();
