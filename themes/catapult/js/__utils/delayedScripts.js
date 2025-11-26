const loadScripts = () => {
	document.removeEventListener('scroll', loadScripts);
	document.removeEventListener('mousemove', loadScripts);
	document.removeEventListener('touchstart', loadScripts);

	const scripts = document.querySelectorAll(
		'script[type="catapult-delayed-script"]'
	);

	if (scripts.length > 0) {
		scripts.forEach((script) => {
			script.type = script?.dataset?.type ?? 'text/javascript';

			script.removeAttribute('catapult-delayed-script');

			const parent = script?.parentNode;
			const sibling = script?.nextSibling;

			script.remove();

			if (!parent) {
				document.body.appendChild(script);
			} else if (sibling) {
				parent.insertBefore(script, sibling);
			} else {
				parent.appendChild(script);
			}
		});
	}
};

const delayedScripts = () => {
	const scripts = document.querySelectorAll(
		'script[type="catapult-delayed-script"]'
	);

	if (scripts.length > 0) {
		document.addEventListener('scroll', loadScripts);
		document.addEventListener('mousemove', loadScripts);
		document.addEventListener('touchstart', loadScripts);
		setTimeout(loadScripts, 3500);
	}
};

export default delayedScripts;
