document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('form.wpcf7-form').forEach(function (form) {
		form.addEventListener(
			'submit',
			function (event) {
				if (form.classList.contains('sent')) {
					event.preventDefault();
					return false;
				}
				const checkIn = form.querySelector('input[name="checkIn"]');
				const checkOut = form.querySelector('input[name="checkOut"]');

				const oldError = form.querySelector('.date-error');
				if (oldError) oldError.remove();

				let hasError = false;

				if (checkIn && checkOut) {
					const checkInDate = new Date(checkIn.value);
					const checkOutDate = new Date(checkOut.value);

					if (checkOutDate < checkInDate) {
						const error = document.createElement('span');
						error.className = 'date-error dta-error';
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
						hasError = true;
					}
				}

				if (form.querySelector('.dta-error')) {
					hasError = true;
				}

				if (hasError) {
					event.preventDefault();
					event.stopImmediatePropagation();
					return false;
				}
			},
			true
		);
	});
});
