export default {
	puppeteerOptions: {
		args: ['--no-sandbox'],
	},
	puppeteerClusterOptions: {
		maxConcurrency: 8,
	},
	scanner: {
		dynamicSampling: 5,
		crawler: false,
	},
	defaultQueryParams: {
		lighthouse: '1',
	},
};
