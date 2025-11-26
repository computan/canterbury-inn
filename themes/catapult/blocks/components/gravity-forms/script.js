const formWrappers = document.querySelectorAll('.gform_wrapper');
const config = { childList: true, subtree: true };

const formChanged = (mutationList, observer) => {
	if (mutationList.length > 0) {
		for (const mutation of mutationList) {
			const loader = mutation.target.querySelector('.gform-loader');
			const submitButtons =
				mutation.target.querySelectorAll('[type="submit"]');

			if (submitButtons.length > 0) {
				submitButtons.forEach((submitButton) => {
					if (loader) {
						submitButton.classList.add('disabled');
					} else {
						submitButton.classList.remove('disabled');
					}
				});
			}

			if (mutation.addedNodes) {
				let confirmationWrapper;

				const hasConfirmationWrapper = Array.from(
					mutation.addedNodes
				).some((node) => {
					if (
						node.classList &&
						node.classList.contains('gform_confirmation_wrapper')
					) {
						confirmationWrapper = node;

						return true;
					}

					return false;
				});

				if (hasConfirmationWrapper) {
					const formSubmittedEvent = new CustomEvent(
						'form-submit-success',
						{
							detail: {
								formId:
									confirmationWrapper?.id.replace(
										'gform_confirmation_wrapper_',
										'gform-'
									) ?? '',
							},
						}
					);
					window.dispatchEvent(formSubmittedEvent);

					if (
						mutation.target.classList.contains(
							'block-form__content'
						)
					) {
						mutation.target.classList.add(
							'block-form__content--success'
						);
					} else {
						const formBlock = mutation.target.querySelector(
							'.block-form__content'
						);

						if (formBlock) {
							formBlock.classList.add(
								'block-form__content--success'
							);
						}
					}

					observer.disconnect();
					break;
				}
			}
		}
	}
};

if (formWrappers.length > 0) {
	formWrappers.forEach((formWrapper) => {
		const observer = new MutationObserver(formChanged);

		observer.observe(formWrapper.parentElement, config);
	});
}
