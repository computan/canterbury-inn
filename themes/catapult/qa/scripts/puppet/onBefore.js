const path = require('path');
const chalk = require('chalk');

module.exports = async (page, scenario, vp) => {
	/*
	const ignoredMessages = [
		'Close Browser',
		'BackstopTools have been installed',
		'JQMIGRATE',
		'report \u001b',
		'openReport \u001b',
		'90m      COMMAND',
		'core/command/report.js',
		`qa${path.sep}images${path.sep}wordpress`,
		'ended with an error after',
		'Executing core for',
		'Object-literal config detected.',
	];

	console.log = (message) => {
		if (!ignoredMessages.some((ignore) => message.includes(ignore))) {
			if (message.includes('ERROR { requireSameDimensions')) {
				const matchPercentage = message.match(/(?<=content: ).*?%/gm);
				message = `${chalk.red(`${matchPercentage} match error`)} for ${
					scenario.label
				} at ${scenario.url}`;

				//message = JSON.stringify(message);
			}

			process.stdout.write(`${message}\n`);
		}
	};
	*/

	console.log(`Testing ${vp.label}: ${scenario.label} ${scenario.url}`);
};
