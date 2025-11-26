import DOMPurify from 'dompurify';

const contentWrapper = document.querySelector('#page > .content-wrapper');

const formBlocks = document.querySelectorAll(
	'#page > .content-wrapper .block-form'
);

let postId;

const loadUnlockedContent = () => {
	if (postId) {
		const cookies = document.cookie;

		if (cookies.includes(`${postId}-ungated`)) {
			contentWrapper.classList.add('loading');

			const gatedContentUrl = `${window.catapult.siteUrl}/wp-json/catapult/v1/post-content/${postId}/`;

			fetch(gatedContentUrl)
				.then((response) => response.json())
				.then(processData)
				.catch((error) => {
					// eslint-disable-next-line no-console
					console.log(error);

					displayError();
				});
		}
	}
};

const processData = (data) => {
	if (data && contentWrapper) {
		contentWrapper.innerHTML = DOMPurify.sanitize(data);
	} else {
		displayError();
	}

	contentWrapper.classList.remove('loading');
};

const displayError = () => {
	contentWrapper.classList.remove('loading');
};

const formSubmitted = (response) => {
	const formId = response?.detail?.formId ?? response;

	if (postId && formId && 'string' === typeof formId) {
		const d = new Date();
		const numdays = 365;
		d.setTime(d.getTime() + numdays * 24 * 60 * 60 * 1000);

		document.cookie = `${postId}-ungated=${formId};expires=${d.toUTCString()};path=/`;

		loadUnlockedContent();
	}
};

if (
	formBlocks.length > 0 &&
	document.body.classList.contains('has-gated-content') &&
	contentWrapper
) {
	const postIdMatch = document.body.classList.value.match(
		new RegExp('(?<=id-).*?(?= )')
	);

	if (postIdMatch?.[0]) {
		postId = postIdMatch[0];

		loadUnlockedContent();

		const hubspotForms = document.querySelectorAll(
			'#page > .content-wrapper .block-form .hbspt-form'
		);

		window.addEventListener('form-submit-success', formSubmitted);

		if (hubspotForms.length) {
			window.addEventListener('message', (e) => {
				if (
					e.data.type === 'hsFormCallback' &&
					e.data.eventName === 'onFormSubmitted' &&
					e.data.data.formGuid
				) {
					formSubmitted(e.data.data.formGuid);
				}
			});
		}
	}
}
