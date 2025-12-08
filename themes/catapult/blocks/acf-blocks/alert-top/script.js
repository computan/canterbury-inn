const alertContainer = document.querySelector('.block-alert-top');
const COOKIENAME = 'alert-top';

function checkCookie(cookieName) {
	const cookieKey = cookieName + '=';
	const decodedCookies = decodeURIComponent(document.cookie);
	const cookiesArray = decodedCookies.split('; ');

	let cookieValue;
	cookiesArray.forEach((cookie) => {
		if (cookie.indexOf(cookieKey) === 0) {
			cookieValue = cookie.substring(cookieKey.length);
		}
	});
	return cookieValue;
}

function toggleAlertBar(alertContainerElement) {
	const hasAlertCookie = checkCookie(COOKIENAME);
	const closeButton = alertContainerElement.querySelector(
		'.block-alert-top__close'
	);

	if (hasAlertCookie) {
		alertContainerElement.style.display = 'none';
	} else {
		alertContainerElement.style.display = 'block';
	}

	if (closeButton) {
		closeButton.addEventListener('click', (event) => {
			event.preventDefault();
			document.cookie = `${COOKIENAME}=true; path=/`;
			alertContainerElement.style.display = 'none';
		});
	}
}

if (alertContainer) {
	toggleAlertBar(alertContainer);

	const alertObserver = new ResizeObserver((entries) => {
		entries.forEach(() => {
			const alertContainerHeight = alertContainer.offsetHeight;

			document.body.style.setProperty(
				'--alert-height',
				`${alertContainerHeight}px`
			);
		});
	});

	alertObserver.observe(alertContainer);
}
