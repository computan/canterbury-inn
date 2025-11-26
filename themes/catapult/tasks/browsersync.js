const gulp = require('gulp');
const browserSync = require('browser-sync');

/*
 * Launch browsersync for live reload and browser testing.
 */
gulp.task('browsersync', () => {
	const localBaseMatch = process.cwd().match(/([^\\\/]*)[\\\/]app/);
	let proxyUrl;

	if (localBaseMatch) {
		proxyUrl = 'https://' + localBaseMatch[1] + '.local';
	}

	return browserSync({
		files: [
			{
				match: `**/*.*`,
			},
		],
		ignore: [
			`uploads/*`,
			`plugins/*`,
			`**/src/**/*`,
			`**/*.scss`,
			`blocks/**/*.scss`,
			`blocks/**/*.js`,
			`qa/*`,
			`lighthouse/*`,
		],
		watchEvents: ['change', 'add'],
		codeSync: true,
		proxy: proxyUrl,
		snippetOptions: {
			ignorePaths: ['*/wp-admin/**'],
		},
	});
});
