document.addEventListener('DOMContentLoaded', () => {
	const alerts = document.querySelectorAll('.block-alert-bottom');
	const cookieName = 'alert-bottom';

	if (!alerts.length) {
		return;
	}

	const setCookie = (name, value, days) => {
		const date = new Date();
		date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
		const expires = 'expires=' + date.toUTCString();
		document.cookie =
			name + '=' + JSON.stringify(value) + ';' + expires + ';path=/';
	};

	const getCookie = (name) => {
		const cookieValue = document.cookie.match(
			'(^|;)\\s*' + name + '\\s*=\\s*([^;]+)'
		);
		return cookieValue ? decodeURIComponent(cookieValue.pop()) : null;
	};

	const alertState = JSON.parse(getCookie(cookieName)) || {};

	const setStorage = () => {
		setCookie(cookieName, alertState, 7); // Set cookie with a week expiration
	};

	alerts.forEach((alert) => {
		if (!alert.id) {
			return;
		}

		const alertID = alert.id;
		const alertCloseButton = alert.querySelector(
			'.block-alert-bottom__close-button'
		);

		//Close alert item and set cookie
		if (alertCloseButton) {
			alertCloseButton.addEventListener('click', function (e) {
				e.preventDefault();
				alert.classList.add('block-alert-bottom--hidden');
				alertState[alertID] = { viewed: true };
				setStorage();
			});
		}

		//No cookie, set all to not viewed
		if (!alertState[alertID]) {
			alertState[alertID] = { viewed: false };
			alert.classList.remove('block-alert-bottom--hidden');
			setStorage();

			return;
		}

		if (alertState[alertID].viewed) {
			alert.classList.add('block-alert-bottom--hidden');
		} else {
			alert.classList.remove('block-alert-bottom--hidden');
		}
	});
});
