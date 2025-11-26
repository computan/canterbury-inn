const alerts = document.querySelectorAll('.block-alert-popup');
const cookieName = 'alert-popup';

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

const keydown = (e) => {
	if ('Escape' === e.key) {
		const firstOpenAlert = document.querySelector(
			'.block-alert-popup:not(.block-alert-popup--hidden)'
		);

		if (!firstOpenAlert || !firstOpenAlert.id) {
			document.addEventListener('keydown', keydown);
			return;
		}

		firstOpenAlert.classList.add('block-alert-popup--hidden');
		alertState[firstOpenAlert.id] = { viewed: true };
		setStorage();
	}
};

if (alerts.length > 0) {
	document.addEventListener('keydown', keydown);

	alerts.forEach((alert) => {
		if (!alert.id) {
			return;
		}

		const alertID = alert.id;
		const alertCloseButton = alert.querySelector(
			'.block-alert-popup__close-button'
		);

		//Close alert item and set cookie
		if (alertCloseButton) {
			alertCloseButton.addEventListener('click', function (e) {
				e.preventDefault();
				alert.classList.add('block-alert-popup--hidden');
				alertState[alertID] = { viewed: true };
				setStorage();
			});
		}

		//No cookie, set all to not viewed
		if (!alertState[alertID]) {
			alertState[alertID] = { viewed: false };
			alert.classList.remove('block-alert-popup--hidden');
			setStorage();

			return;
		}

		if (alertState[alertID].viewed) {
			alert.classList.add('block-alert-popup--hidden');
		} else {
			alert.classList.remove('block-alert-popup--hidden');
		}
	});
}
