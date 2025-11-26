const gulp = require('gulp');
const runProcess = require('./runProcess');
const path = require('path');
const fs = require('fs');
const WP = require('wp-cli');
const replace = require('gulp-replace');
const async = require('async');
const chalk = require('chalk');
const getArguments = require('./getArguments');
getArguments.parse();
const envArgs = getArguments();

let siteURL;
let auth;
let blockURLs = [];

function convertObjectToConfigString(config) {
	return Object.entries(config)
		.map(([key, value]) =>
			value !== null ? `--${key} ${value}` : `--${key}`
		)
		.join(' ');
}

const getSiteURL = (done) => {
	if (envArgs.site) {
		siteURL = envArgs.site;
	} else if (envArgs.url) {
		siteURL = envArgs.url;
	}

	if (envArgs.auth) {
		auth = envArgs.auth;
	}

	if (siteURL) {
		if (siteURL.includes('.local')) {
			done();
		} else {
			getSiteURLifRedirected(siteURL)
				.then((updatedURL) => {
					siteURL = updatedURL;
					done();
				})
				.catch((error) => {
					console.log(
						chalk.red(`Failed to check redirect for ${siteURL}`)
					);
					process.exit();
				});
		}
	} else {
		WP.discover({ 'skip-plugins': true }, (wp) => {
			wp.option.get('siteUrl', (error, url) => {
				if (error) {
					console.log(chalk.red(error));
					done();
				}

				if (url) {
					siteURL = url;
					done();
				}
			});
		});
	}
};

const getSiteURLifRedirected = async (url) => {
	console.log(chalk.blue(`Checking redirects for ${url}`));

	const fetchArgs = {
		method: 'GET',
		redirect: 'follow',
	};

	if (auth) {
		const fetchHeaders = new Headers();
		fetchHeaders.set('Authorization', 'Basic ' + btoa(`${auth}:${auth}`));

		fetchArgs.headers = fetchHeaders;
	}

	try {
		const response = await fetch(url, fetchArgs);

		if (url === response.url) {
			console.log(chalk.blue(`No redirect found. Using ${url}`));

			return url;
		}

		let newUrl = response.url;

		if (newUrl.endsWith('/')) {
			newUrl = newUrl.slice(0, -1);
		}

		console.log(chalk.blue(`Using redirect to: ${newUrl}`));

		return newUrl;
	} catch (error) {
		console.log(chalk.red(`Failed to fetch ${url}: ${error}`));
		process.exit();
	}
};

const getBlockURLs = (done) => {
	if (envArgs.noBlocks) {
		done();
		return;
	}

	if (envArgs.auth) {
		auth = envArgs.auth;
	}

	if (siteURL && !siteURL.includes('.local')) {
		getBlockUrlsFromAPI(siteURL)
			.then((updatedURL) => {
				blockURLs = updatedURL;
				done();
			})
			.catch((error) => {
				console.log(
					chalk.red(`Failed to get block URLs from ${siteURL}`)
				);
				process.exit();
			});
	} else {
		WP.discover({ 'skip-plugins': true }, (wp) => {
			wp.post.list({ post_type: 'library_block' }, (error, results) => {
				if (error) {
					console.log(chalk.red(error));
					done();
				}

				if (results) {
					results.forEach((result) => {
						if (!result.post_title) {
							return;
						}

						blockURLs.push(
							`${siteURL}/block-library/?qa=${result.post_title}&backstop=1`
						);
					});

					done();
				}
			});
		});
	}
};

const getBlockUrlsFromAPI = async (url) => {
	console.log(chalk.blue(`Checking redirects for ${url}`));

	const fetchArgs = {
		method: 'GET',
		redirect: 'follow',
	};

	if (auth) {
		const fetchHeaders = new Headers();
		fetchHeaders.set('Authorization', 'Basic ' + btoa(`${auth}:${auth}`));

		fetchArgs.headers = fetchHeaders;
	}

	try {
		const response = await fetch(
			`${url}/wp-json/catapult/v1/block-lighthouse-urls/`,
			fetchArgs
		);

		if (!response || !response.ok) {
			console.log(
				chalk.red(
					`No block URLs found at ${url}/wp-json/catapult/v1/block-lighthouse-urls/`
				)
			);

			return [];
		}

		const responseJson = await response.json();

		blockURLs = responseJson;

		return blockURLs;
	} catch (error) {
		console.log(
			chalk.red(
				`Failed to fetch ${url}/wp-json/catapult/v1/block-lighthouse-urls/: ${error}`
			)
		);
		process.exit();
	}
};

const runLighthouseScan = (args, done) => {
	const defaultConfig = {
		site: siteURL,
		debug: false,
		desktop: null,
		'output-path': './lighthouse/',
		'build-static': true,
		'router-prefix': '/lighthouse',
	};

	if (auth) {
		defaultConfig.auth = `${auth}:${auth}`;
	}

	const config = { ...defaultConfig, ...args };

	const unlighthouseConfig = convertObjectToConfigString(config);

	console.log(
		chalk.green(`Unlighthouse command line config: ${unlighthouseConfig}`)
	);

	runProcess('npx unlighthouse-ci ' + unlighthouseConfig);

	done();
};

const lighthouseChangeFiletype = (scan, done) => {
	const dirPath = scan['output-path'];
	const filePath = path.resolve(`${dirPath}/index.html`);

	if (!fs.existsSync(filePath)) {
		done();
		return;
	}

	const newFilePath = path.resolve(`${dirPath}/index.php`);

	fs.rename(filePath, newFilePath, (err) => {
		if (err) {
			console.error(chalk.red(`Error renaming file:' ${err}`));
		} else {
			console.log(chalk.green(`File type changed successfully!`));
		}
		done();
	});
};

const injectLoadWPCode = (reportPath, done) => {
	const linkStyles =
		'--tw-bg-opacity: 0; background-color: rgba(30, 58, 138, var(--tw-bg-opacity)); padding: 0.25rem 1rem;';
	let desktopLinkStyles = ' --tw-bg-opacity: 1;';
	let mobileLinkStyles = '';
	let blocksDesktopLinkStyles = '';
	let blocksMobileLinkStyles = '';

	if (reportPath.includes('mobile')) {
		if (reportPath.includes('blocks')) {
			desktopLinkStyles = '';
			mobileLinkStyles = '';
			blocksDesktopLinkStyles = '';
			blocksMobileLinkStyles = ' --tw-bg-opacity: 1;';
		} else {
			desktopLinkStyles = '';
			mobileLinkStyles = ' --tw-bg-opacity: 1;';
			blocksDesktopLinkStyles = '';
			blocksMobileLinkStyles = '';
		}
	} else if (reportPath.includes('blocks')) {
		desktopLinkStyles = '';
		mobileLinkStyles = '';
		blocksDesktopLinkStyles = ' --tw-bg-opacity: 1;';
		blocksMobileLinkStyles = '';
	}

	if (!fs.existsSync(`${reportPath}/index.php`)) {
		done();
		return;
	}

	gulp.src(`${reportPath}/index.php`)
		.pipe(
			replace(
				'<!DOCTYPE html>',
				"<?php require_once( ABSPATH . '/wp-blog-header.php' ); ?>\n<!DOCTYPE html>"
			)
		)
		.pipe(
			replace(
				'<div id="app">',
				`<div style="padding: 0 0.5rem; display: block; color: #fff; font-size: 1rem; text-align: right; display: flex; flex-direction: row; flex-wrap: wrap; gap: 2px; background-color: rgb(21, 28, 35); position: sticky; top: 0;"><a style="${linkStyles}${desktopLinkStyles}" href="<?php echo esc_url( home_url( '/lighthouse/desktop/' ) ); ?>">Desktop Report</a><a style="${linkStyles}${mobileLinkStyles}" href="<?php echo esc_url( home_url( '/lighthouse/mobile/' ) ); ?>">Mobile Report</a><a style="${linkStyles}${blocksDesktopLinkStyles}" href="<?php echo esc_url( home_url( '/lighthouse/blocks/desktop/' ) ); ?>">Blocks Desktop Report</a><a style="${linkStyles}${blocksMobileLinkStyles}" href="<?php echo esc_url( home_url( '/lighthouse/blocks/mobile/' ) ); ?>">Blocks Mobile Report</a></div><div id="app">`
			)
		)
		.pipe(
			replace(
				'"/lighthouse/',
				`"<?php echo esc_url( get_template_directory_uri() ); ?>/lighthouse/`
			)
		)
		.pipe(
			replace(
				'payload.js"></script>',
				`payload.js"></script>\n<script>window.catapultThemeUrl = "<?php echo esc_url( get_template_directory_uri() ); ?>";</script>\n<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/dist/lighthouse.js"></script>`
			)
		)
		.pipe(gulp.dest(reportPath))
		.on('finish', done);
};

const runScans = (runScansDone) => {
	let scans = [];

	if (!envArgs.noSitemap) {
		scans = [
			{
				desktop: true,
				'output-path': './lighthouse/desktop',
				'router-prefix': '/lighthouse/desktop',
				'exclude-urls': '/block-library/.*',
			},
			{
				desktop: false,
				'output-path': './lighthouse/mobile',
				'router-prefix': '/lighthouse/mobile',
				'exclude-urls': '/block-library/.*',
			},
		];
	}

	if (!envArgs.noBlocks) {
		let blockURLsString = blockURLs.join().replaceAll(siteURL, '');

		if (envArgs.blockLimit) {
			blockURLsString = blockURLs
				.slice(0, envArgs.blockLimit)
				.join()
				.replaceAll(siteURL, '');
		}

		scans.push({
			desktop: true,
			'output-path': './lighthouse/blocks/desktop',
			'router-prefix': '/lighthouse/blocks/desktop',
			urls: blockURLsString,
		});

		scans.push({
			desktop: false,
			'output-path': './lighthouse/blocks/mobile',
			'router-prefix': '/lighthouse/blocks/mobile',
			urls: blockURLsString,
		});
	}

	if (0 === scans.length) {
		runScansDone();
		return;
	}

	async.eachSeries(
		scans,
		(scan, scanDone) => {
			gulp.series(
				(done) => runLighthouseScan(scan, done),
				(done) => lighthouseChangeFiletype(scan, done),
				(done) =>
					injectLoadWPCode(path.resolve(scan['output-path']), done)
			)(scanDone);
		},
		(error) => {
			if (null !== error) {
				console.log(chalk.red(error));
			}

			runScansDone();
		}
	);
};

gulp.task('lighthouse', (done) => {
	gulp.series(getSiteURL, getBlockURLs, runScans)(done);
});

gulp.task('unlighthouse', (done) => {
	gulp.series('lighthouse')(done);
});
