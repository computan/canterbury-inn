const getArguments = require('./tasks/getArguments');
getArguments.parse();
const envArgs = getArguments();

module.exports = {
	paths: {
		themeName: 'catapult',
	},
	envArgs,
	isProduction: !!envArgs.production,
};
