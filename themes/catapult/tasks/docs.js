const gulp = require('gulp');
const glob = require('glob');
const path = require('path');
const fs = require('fs');
const markdownLinkCheck = require('markdown-link-check');
const chalk = require('chalk');
const gramma = require('gramma');
const getArguments = require('./getArguments');
const replace = require('gulp-replace');

let envArgs;
let docsPath;
let tableOfContents;

const loadVars = () => {
	envArgs = getArguments();
	getArguments.parse();
	docsPath = path.resolve(`../../docs`);
	tableOfContents = [];
};

gulp.task('docs', (done) => {
	loadVars();

	glob(`${docsPath}/**/*.md`, function (error, files) {
		if (!error && files.length) {
			files.forEach((file) => {
				const fileContents = fs.readFileSync(file, 'utf8');

				if (fileContents) {
					if (
						!envArgs ||
						(!envArgs.spelling &&
							!envArgs.grammar &&
							!envArgs.links)
					) {
						checkSpelling(fileContents, file);
						checkGrammar(fileContents, file);
						checkLinks(fileContents, file);
					} else if (envArgs) {
						if (envArgs.spelling) {
							checkSpelling(fileContents, file);
						}

						if (envArgs.grammar) {
							checkGrammar(fileContents, file);
						}

						if (envArgs.links) {
							checkLinks(fileContents, file);
						}
					}
				}
			});
		}

		done();
	});
});

gulp.task('docs:header', (done) => {
	loadVars();

	gulp.src(`${docsPath}/**/*.md`)
		.pipe(
			replace(
				/^.*Table of Contents.*(?=\n)/,
				function handleReplace(
					currentHeader,
					position,
					offset,
					fileContents
				) {
					const docHeader = getDocHeader(
						this.file.relative,
						currentHeader
					);

					return docHeader;
				}
			)
		)
		.pipe(gulp.dest(docsPath))
		.on('finish', done);
});

const checkSpelling = (fileContents, file) => {
	gramma
		.check(fileContents, {
			language: 'en-US',
			markdown: true,
			dictionary: [
				'InnerBlocks', 'acf-blocks', 'json', 'figma', 'mixins', '$content', 'wp', 'VSCode', 'esc', 'gravityforms', 'acf', 'mixin', 'Mixins', 'PHPCS', 'md', 'wp-content', '$blog', '$transition-standard', 'figma-settings', 'InnerBlock', 'ThemeName', 'WCAG', 'wp-block-button', 'acf-json', 'attr', 'gutenberg-block-editor', 'init', 'sm', 'Stylelint', 'wp-cli', '$allowed', '$background-colors', '$block-spacing', '$fonts', '$grid-breakpoints', '$grid-gap', '$image', '$template', 'allowedBlocks', 'blocks', 'columnWidth', 'const', 'dashicon', 'href', 'kses', 'mysql', 'nvm', 'oddeven', 'srcset', 'templateLock', 'wordpress-plugin', 'wpackagist-plugin', '^X', '$additional-paints', '$background-font-colors', '$background-font-hover-colors', '$backgrounds-with-alt-text-color', '$base', '$blocks', '$effects', '$paints', 'abcdefg', 'acf-innerblocks-container', 'ADDTHEMENAMEHERE', 'allowBlocks', 'browsersync', 'Browsersync', 'c-btn', 'calc', 'CodeSniffer', 'containerMaxWidth', 'containerWidth', 'contrainsted', 'ea2f', 'figmaFileID', 'fontStyles', 'foreach', 'gitignore', 'gulp-cli', 'gutterWidth', 'InnnerBlocks', 'isset', 'MacOS', 'moz-osx-font-smoothing', 'mysqld', 'myusername', 'non-existant', 'rgba', 'rv', 'stadards', 'svgs', 'uniqueid', 'Unstyled', 'webkit-font-smoothing', 'woocommerce', 'wp-block-buttons', 'wp-config', 'wp-config-sample', 'wpackagist', 'wpdev', 'xxl', 'TOTAL LINES', 'DISTINCT LINES', 'npm', 'Setup', 'url', 'antialiased', 'templated', 'blocks', 'args', 'BackstopJS', 'qa', 'Yoast', 'WPEngine', 'Swiper', 'lightbox', 'Lightbox', 'Hubspot', 'oEmbed', 'AddToAny', 'Zsh', 'Unsynced', 'unsynced', 'figma-block-nodes', 'figma-and-wp-block-names', 'block-qa-status', 'enqueuing', 'qa-settings' // eslint-disable-line
			],
			rules: {
				casing: false,
				colloquialisms: false,
				compounding: false,
				confused_words: false,
				false_friends: false,
				gender_neutrality: false,
				grammar: false,
				misc: false,
				punctuation: false,
				redundancy: false,
				regionalisms: false,
				repetitions: false,
				semantics: false,
				style: false,
				typography: false,
				typos: true,
			},
		})
		.then((result) => {
			if (result.matches && result.matches.length > 0) {
				let firstErrorOutput = false;

				result.matches.forEach((match) => {
					if (
						match.rule &&
						match.rule.id &&
						'POSSESSIVE_APOSTROPHE' === match.rule.id
					) {
						return;
					}

					if (false === firstErrorOutput) {
						firstErrorOutput = true;

						console.log('');

						console.log(
							chalk.red(
								`Spelling errors in: ${file.replace(
									docsPath,
									'/docs'
								)}`
							)
						);
					}

					if (match.word && match.sentence && match.message) {
						console.log(
							chalk.yellow(
								match.message + ` (${match.rule.id}): `
							) +
								match.sentence.replaceAll(
									match.word,
									chalk.magenta(match.word)
								)
						);
					}
				});
			}
		});
};

const checkGrammar = (fileContents, file) => {
	gramma
		.check(fileContents, {
			language: 'en-US',
			markdown: true,
			rules: {
				casing: false,
				colloquialisms: true,
				compounding: true,
				confused_words: true,
				false_friends: true,
				gender_neutrality: true,
				grammar: true,
				misc: false,
				punctuation: true,
				redundancy: true,
				regionalisms: true,
				repetitions: true,
				semantics: true,
				style: true,
				typography: true,
				typos: false,
			},
		})
		.then((result) => {
			if (result.matches && result.matches.length > 0) {
				let firstErrorOutput = false;

				result.matches.forEach((match) => {
					if (
						match.rule &&
						match.rule.id &&
						('COMMA_PARENTHESIS_WHITESPACE' === match.rule.id ||
							'ARROWS' === match.rule.id ||
							'THE_CC' === match.rule.id ||
							'DT_DT' === match.rule.id ||
							'PHRASE_REPETITION' === match.rule.id ||
							'CONSECUTIVE_SPACES' === match.rule.id ||
							'WHITESPACE_RULE' === match.rule.id)
					) {
						return;
					}

					if (
						match.message &&
						'Two consecutive commas' === match.message
					) {
						return;
					}

					if (false === firstErrorOutput) {
						firstErrorOutput = true;

						console.log('');

						console.log(
							chalk.red(
								`Grammar errors in: ${file.replace(
									docsPath,
									'/docs'
								)}`
							)
						);
					}

					if (match.word && match.sentence && match.message) {
						console.log(
							chalk.yellow(
								match.message + ` (${match.rule.id}): `
							) +
								match.sentence.replaceAll(
									match.word,
									chalk.magenta(match.word)
								)
						);
					}
				});
			}
		});
};

const checkLinks = (fileContents, file) => {
	markdownLinkCheck(
		fileContents,
		{
			baseUrl: `file://${process.cwd()}`,
			projectBaseUrl: `file://${process.cwd()}`,
			replacementPatterns: [
				{
					pattern: '^/',
					replacement: '{{BASEURL}}/',
				},
				{
					pattern: 'themes/catapult/',
					replacement: '',
				},
			],
			ignorePatterns: [
				{
					pattern: '.*THIS_IS_THE_FILE_ID.*',
				},
				{
					pattern: '.*domain-name.*',
				},
			],
		},
		function (err, results) {
			if (err) {
				console.error('Error', err);
				return;
			}
			results.forEach(function (result) {
				if ('alive' !== result.status && 'ignored' !== result.status) {
					console.log(
						chalk.red(
							`Error with link in: ${file.replace(
								docsPath,
								'/docs'
							)}`
						)
					);

					console.log(
						chalk.red(
							`Link: ${result.link.replace(
								'file://' + docsPath.replace('/docs', ''),
								''
							)} is ${result.status}`
						)
					);
				}
			});
		}
	);
};

const getDocHeader = (file, header) => {
	if (!tableOfContents.length) {
		const tableOfContentsPath = path.resolve(`../../docs/README.md`);

		if (fs.existsSync(tableOfContentsPath)) {
			const tableOfContentsFileContents = fs.readFileSync(
				tableOfContentsPath,
				'utf8'
			);

			const regex = /\[.*\]\((.*)\)/g;
			let matches;

			while (
				(matches = regex.exec(tableOfContentsFileContents)) !== null
			) {
				if (matches.index === regex.lastIndex) {
					regex.lastIndex++;
				}

				if (matches[1] && '/docs/README.md' !== matches[1]) {
					tableOfContents.push(matches[1]);
				}
			}
		}
	}

	if (tableOfContents.length) {
		const index = tableOfContents.indexOf('/docs/' + file);
		let previousArticleIndex;
		let nextArticleIndex;

		if (index >= 0) {
			header = '[⌂ Table of Contents](/docs/README.md)';

			if (index > 0) {
				previousArticleIndex = index - 1;
			} else if (index === 0) {
				previousArticleIndex = tableOfContents.length - 1;
			}

			if (index >= 0 && index < tableOfContents.length - 1) {
				nextArticleIndex = index + 1;
			} else if (index >= 0 && index === tableOfContents.length - 1) {
				nextArticleIndex = 0;
			}

			if (previousArticleIndex >= 0) {
				header += ` | [← Previous Article](${tableOfContents[previousArticleIndex]})`;
			}

			if (nextArticleIndex >= 0) {
				header += ` | [Next Article →](${tableOfContents[nextArticleIndex]})`;
			}
		}
	}

	return header;
};
