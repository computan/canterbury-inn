const runProcess = require('./runProcess');

module.exports = (command, commandArgs = []) => {
	runProcess('npm run wp' + ' -- ' + [command, ...commandArgs].join(' '));
};
