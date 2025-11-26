let reportType = 'desktop';

const updateValues = (obj) => {
	for (const key in obj) {
		if (obj.hasOwnProperty(key)) {
			if (typeof obj[key] === 'string') {
				if (obj[key].startsWith('/lighthouse/')) {
					obj[key] = window.catapultThemeUrl + obj[key];
				} else if (obj[key].startsWith('reports/')) {
					obj[key] =
						`${window.catapultThemeUrl}/lighthouse/${reportType}/${obj[key]}`;
				}
			} else if (typeof obj[key] === 'object') {
				updateValues(obj[key]);
			}
		}
	}
};

if (window.__unlighthouse_payload && window.catapultThemeUrl) {
	if (
		window.__unlighthouse_payload?.reports[0]?.artifactUrl.includes(
			'mobile'
		)
	) {
		reportType = 'mobile';
	}

	if (
		window.__unlighthouse_payload?.reports[0]?.artifactUrl.includes(
			'blocks'
		)
	) {
		reportType = 'blocks/' + reportType;
	}

	updateValues(window.__unlighthouse_payload);
}
