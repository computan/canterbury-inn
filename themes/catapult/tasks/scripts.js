const gulp = require('gulp');
const config = require('../gulp.config');

const fs = require('fs');
const path = require('path');
const webpack = require('webpack');

/*
 * Compile scripts using webpack.
 */

/**
 * @param {string}   configName Name of the config file to import.
 * @param {string}   name       Printed in console to identify the build, for devs convenience.
 * @param {Function} done       Gulp callback, passes from task.
 */
const compileWebpack = (configName, name, done) => {
	const filePath = `${path.resolve(__dirname, configName)}.js`;

	fs.access(filePath, fs.constants.R_OK || fs.constants.W_OK, (err) => {
		if (err) {
			console.error(err);
			done();
			return;
		}

		const webpackConfig = require(configName);

		if (config.isProduction) {
			webpackConfig.mode = 'production';
		}

		console.info(`\n[webpack][${name}]\n`);

		webpack(webpackConfig, (err, stats) => {
			console.info(
				stats.toString({
					chunks: false, // Makes the build much quieter
					colors: true,
				})
			);

			done();
		});
	});
};

gulp.task('scripts:default', (done) => {
	compileWebpack('../webpack.config', 'default', done);
});

gulp.task('scripts', gulp.parallel('scripts:default'));

gulp.task('scripts:watch', (done) => {
	const webpackConfig = require('../webpack.config');

	if (config.isProduction) {
		webpackConfig.mode = 'production';
	}

	webpack(webpackConfig).watch(
		{
			aggregateTimeout: 300,
			poll: undefined,
		},
		(err, stats) => {
			console.info(
				stats.toString({
					chunks: false, // Makes the build much quieter
					colors: true,
					minimal: true,
				})
			);
		}
	);

	done();
});
