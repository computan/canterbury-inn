document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('form.wpcf7-form').forEach(function (form) {
		form.addEventListener('submit', function (event) {
			const checkIn = form.querySelector('input[name="checkIn"]');
			const checkOut = form.querySelector('input[name="checkOut"]');

			if (!checkIn || !checkOut) return true;

			const checkInDate = new Date(checkIn.value);
			const checkOutDate = new Date(checkOut.value);

			const oldError = form.querySelector('.date-error');
			if (oldError) oldError.remove();

			if (checkOutDate < checkInDate) {
				event.preventDefault();

				const error = document.createElement('span');
				error.className = 'date-error';
				error.style.color = '#ad0322';
				error.style.backgroundColor = 'white';
				error.style.borderRadius = '4px';
				error.style.padding = '4px 6px';
				error.style.display = 'block';
				error.style.marginTop = '5px';
				error.style.fontSize = '13px';
				error.textContent = 'Invalid Checkout date.';
				checkOut.parentNode.appendChild(error);

				checkOut.focus();
				return false;
			}
		});
	});
});
