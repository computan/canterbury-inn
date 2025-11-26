const cf7Forms = document.querySelectorAll('.wpcf7-form');
const config = { childList: true, subtree: true };

const formChanged = (mutationList, observer) => {
	if (mutationList.length > 0) {
		for (const mutation of mutationList) {
			// Handle submit button state during submission
			const isSubmitting =
				mutation.target.classList.contains('submitting');
			const submitButtons =
				mutation.target.querySelectorAll('[type="submit"]');

			if (submitButtons.length > 0) {
				submitButtons.forEach((submitButton) => {
					if (isSubmitting) {
						submitButton.classList.add('disabled');
					} else {
						submitButton.classList.remove('disabled');
					}
				});
			}

			// Check for success response
			// Check for success response
			if (mutation.addedNodes) {
				const responseOutput = Array.from(mutation.addedNodes).find(
					(node) =>
						node.classList?.contains('wpcf7-response-output') &&
						node.classList.contains('wpcf7-mail-sent-ok')
				);

				if (responseOutput) {
					const formId = mutation.target.closest('.wpcf7')?.id || '';
					const formSubmittedEvent = new CustomEvent(
						'form-submit-success',
						{
							detail: { formId },
						}
					);
					window.dispatchEvent(formSubmittedEvent);

					mutation.target
						.closest('.block-form__content')
						?.classList.add('block-form__content--success');

					observer.disconnect();
					break;
				}
			}
		}
	}
};

if (cf7Forms.length > 0) {
	cf7Forms.forEach((form) => {
		const observer = new MutationObserver(formChanged);

		// Observe the form's parent element
		observer.observe(form.parentElement, config);

		// Also observe the form itself for submission state changes
		const formObserver = new MutationObserver(formChanged);
		formObserver.observe(form, {
			attributes: true,
			attributeFilter: ['class'],
		});
	});
}

// Additional CF7 submission handling
document.addEventListener(
	'wpcf7submit',
	function (e) {
		const form = e.target;
		const formContainer = form.closest('.block-form__content');

		if (formContainer) {
			formContainer.classList.add('block-form__content--success');
		}

		const formSubmittedEvent = new CustomEvent('form-submit-success', {
			detail: {
				formId: form.id || '',
			},
		});
		window.dispatchEvent(formSubmittedEvent);
	},
	false
);
