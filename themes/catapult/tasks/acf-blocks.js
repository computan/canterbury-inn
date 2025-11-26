const gulp = require('gulp');
const config = require('../gulp.config');
const prompt = require('gulp-prompt');
const template = require('gulp-template');
const fs = require('fs');
const path = require('path');
const rename = require('gulp-rename');
const open = require('open');
const { customAlphabet } = require('nanoid/non-secure');
const nanoid = customAlphabet('1234567890abcdefghijklmnopqrstuvwxyz', 13);
const { cleanForSlug } = require('@wordpress/url');
const async = require('async');
const findInFiles = require('find-in-files');
const chalk = require('chalk');
const rimraf = require('rimraf');

let blocksDirectory;
let blocks;

const loadVars = () => {
	blocksDirectory = `blocks${path.sep}acf-blocks${path.sep}`;

	blocks = fs.readdirSync(
		path.resolve(`blocks${path.sep}acf-blocks${path.sep}`)
	);
	blocks = [...new Set(blocks)];
	blocks = blocks.filter((file) => !file.endsWith('.scss'));
	blocks = blocks.filter((file) => !file.endsWith('.php'));
	blocks = blocks.filter((file) => !file.startsWith('.'));
};

gulp.task('block:add', (done) => {
	loadVars();

	findInFiles
		.find(new RegExp('(?<= * Category:).*'), path.resolve(blocksDirectory))
		.then(function (results) {
			const categories = ['Add new category'];

			const templates = fs.readdirSync(
				path.resolve(
					`.${path.sep}tasks${path.sep}acf-block-templates${path.sep}`
				)
			);

			const phpFiles = templates.filter((file) => file.endsWith('.php'));
			const jsonFiles = templates.filter((file) =>
				file.endsWith('.json')
			);

			const promptSettings = [
				{
					type: 'input',
					name: 'title',
					message: chalk.blue('Block title:'),
					validate(input) {
						if ('' === input) {
							return chalk.red('Title required.');
						}

						return true;
					},
				},
				{
					type: 'input',
					name: 'description',
					message: chalk.blue('Block description:'),
					validate(input) {
						if ('' === input) {
							return chalk.red('Description required.');
						}

						return true;
					},
				},
			];

			for (const result in results) {
				const res = results[result];

				if (
					res.matches[0] &&
					!categories.includes(res.matches[0].trim())
				) {
					categories.push(res.matches[0].trim());
				}
			}

			if (categories.length) {
				promptSettings.push(
					{
						type: 'list',
						name: 'category',
						message: chalk.blue('Block category:'),
						choices: categories,
						pageSize: 6,
						default: 'Add new category',
					},
					{
						type: 'input',
						name: 'newCategory',
						message: chalk.blue('New block category:'),
						when(responses) {
							if ('Add new category' === responses.category) {
								return true;
							}
						},
						validate(input) {
							if ('' === input) {
								return chalk.red('Category required.');
							}

							return true;
						},
					}
				);
			}

			promptSettings.push(
				{
					type: 'input',
					name: 'icon',
					message: chalk.blue(
						'WordPress dashicon slug (see https://developer.wordpress.org/resource/dashicons/):'
					),
					validate(input) {
						if ('' === input) {
							return chalk.red('Icon required.');
						}

						return true;
					},
				},
				{
					type: 'input',
					name: 'keywords',
					message: chalk.blue('Keywords (separated by commas):'),
					validate(input) {
						if ('' === input) {
							return chalk.red('Keywords required.');
						}

						return true;
					},
				},
				{
					type: 'list',
					name: 'phpTemplate',
					message: chalk.blue('PHP template:'),
					choices: phpFiles,
					pageSize: 6,
					default: 'parent-block.php',
				},
				{
					type: 'checkbox',
					name: 'parentBlocks',
					message: chalk.blue('Parent block(s):'),
					choices: blocks,
					when(responses) {
						if ('inner-block.php' === responses.phpTemplate) {
							return true;
						}
					},
					filter(responses) {
						const formattedResponses = responses.map(
							(response) => `acf${path.sep}${response}`
						);

						return formattedResponses.join(',');
					},
				},
				{
					type: 'confirm',
					name: 'innerBlocks',
					message: chalk.blue('Does this block have inner blocks?'),
					default: true,
				},
				{
					type: 'confirm',
					name: 'defaultTextBlocks',
					message: chalk.blue(
						'Allow default text blocks as inner blocks?'
					),
					default: true,
					when(responses) {
						if (true === responses.innerBlocks) {
							return true;
						}
					},
				},
				{
					type: 'checkbox',
					name: 'allowedInnerBlocks',
					message: chalk.blue('Allow additional inner blocks:'),
					choices: blocks,
					when(responses) {
						if (true === responses.innerBlocks) {
							return true;
						}
					},
					filter(responses) {
						const formattedResponses = responses.map(
							(response) => "'" + response + "'"
						);

						return formattedResponses.join(', ');
					},
				},
				{
					type: 'checkbox',
					name: 'templateBlocks',
					message: chalk.blue(
						'Blocks to include in inner block template:'
					),
					choices: ['Heading', 'Paragraph', 'Buttons'].concat(blocks),
					when(responses) {
						if (true === responses.innerBlocks) {
							return true;
						}
					},
				}
			);

			if (jsonFiles.length > 0) {
				promptSettings.push({
					type: 'confirm',
					name: 'acfJSON',
					message: chalk.blue('Create an ACF field group/JSON file?'),
					default: true,
				});
			}

			if (jsonFiles.length > 1) {
				promptSettings.push({
					type: 'list',
					name: 'jsonTemplate',
					message: chalk.blue('ACF JSON template:'),
					choices: jsonFiles,
					pageSize: 6,
					default: 'default-acf-json.json',
					when(responses) {
						if (true === responses.acfJSON) {
							return true;
						}
					},
				});
			}

			return gulp.src(`./package.json`).pipe(
				prompt.prompt(promptSettings, function (responses) {
					responses.directory = cleanForSlug(
						responses.title
					).replaceAll(/--*/gi, '-');
					responses.themeName = config.paths.themeName;
					responses.icon = responses.icon.replace('dashicons-', '');
					responses.date = Math.floor(Date.now() / 1000);

					if (responses.phpTemplate === undefined) {
						responses.phpTemplate = 'default-block.php';
					}

					if (
						responses.jsonTemplate === undefined &&
						true === responses.acfJSON
					) {
						responses.jsonTemplate = 'default-acf-json.json';
					}

					if (
						responses.description[
							responses.description.length - 1
						] !== '.'
					) {
						responses.description += '.';
					}

					for (let i = 1; i <= 10; i++) {
						responses['id_' + i] = nanoid();
					}

					const blockDirectory = `blocks${path.sep}acf-blocks${path.sep}${responses.directory}${path.sep}`;
					const jsonDirectory = `acf-json${path.sep}`;
					let error = false;

					if (
						fs.existsSync(
							path.resolve(
								blockDirectory,
								`.${path.sep}block.php`
							)
						)
					) {
						console.log(
							chalk.red(
								`ERROR: block.php file already exists at ${blockDirectory}`
							)
						);
						error = true;
					}

					if (
						fs.existsSync(
							path.resolve(
								blockDirectory,
								`.${path.sep}style.scss`
							)
						)
					) {
						console.log(
							chalk.red(
								`ERROR: scss files already exists at ${blockDirectory}`
							)
						);
						error = true;
					}

					if (
						true === responses.acfJSON &&
						fs.existsSync(
							path.resolve(
								jsonDirectory,
								`.${path.sep}group_${responses.id_1}.json`
							)
						)
					) {
						console.log(
							chalk.red(
								`ERROR: json files already exists at ${jsonDirectory}group_${responses.id_1}.json`
							)
						);
						error = true;
					}

					if (true === error) {
						done();
						return;
					}

					gulp.src(`./tasks/acf-block-templates/*.scss`)
						.pipe(template(responses))
						.pipe(gulp.dest(blockDirectory))
						.on('finish', function () {
							console.log(
								chalk.green(
									`Successfully created scss files at: ${blockDirectory}`
								)
							);
							open(`${blockDirectory}style.scss`, {
								wait: false,
							});
						});

					gulp.src(
						`./tasks/acf-block-templates/${responses.phpTemplate}`
					)
						.pipe(template(responses))
						.pipe(rename(`block.php`))
						.pipe(gulp.dest(blockDirectory))
						.on('finish', function () {
							console.log(
								chalk.green(
									`Successfully created block.php file at: ${blockDirectory}block.php`
								)
							);
							open(`${blockDirectory}block.php`, { wait: false });
						});

					if (true === responses.acfJSON) {
						gulp.src(
							`./tasks/acf-block-templates/${responses.jsonTemplate}`
						)
							.pipe(template(responses))
							.pipe(rename(`group_${responses.id_1}.json`))
							.pipe(gulp.dest(jsonDirectory))
							.on('finish', function () {
								console.log(
									chalk.green(
										`Successfully created json file file at: ${jsonDirectory}group_${responses.id_1}.json`
									)
								);
							});
					}

					done();
				})
			);
		});
});

gulp.task('block:remove', (done) => {
	loadVars();

	const promptSettings = [
		{
			type: 'checkbox',
			name: 'blocks',
			message: chalk.blue('Select blocks to remove:'),
			choices: blocks,
		},
	];

	return gulp.src(`./package.json`).pipe(
		prompt.prompt(promptSettings, function (responses) {
			if (responses.blocks.length > 0) {
				async.eachSeries(
					responses.blocks,
					(block, callback) => {
						const blockDirectory = `${blocksDirectory}${block}${path.sep}`;

						findInFiles
							.find(
								new RegExp('"value": "acf.*' + block + '"'),
								path.resolve(`acf-json${path.sep}`)
							)
							.then(function (acfJsonFiles) {
								console.log(chalk.cyan('Files to be deleted:'));
								if (fs.existsSync(blockDirectory)) {
									console.log(blockDirectory);
								}

								if (Object.keys(acfJsonFiles).length > 0) {
									for (acfJsonPath in acfJsonFiles) {
										console.log(acfJsonPath);
									}
								}

								gulp.src(`./package.json`).pipe(
									prompt.prompt(
										[
											{
												type: 'confirm',
												name: 'confirm',
												message: chalk.red(
													`${block} - do you really want to remove these files/directories?`
												),
												default: false,
											},
										],
										function (responses) {
											if (responses.confirm === true) {
												if (
													fs.existsSync(
														blockDirectory
													)
												) {
													rimraf(
														blockDirectory,
														function () {
															console.log(
																chalk.green(
																	`DELETED: ${blockDirectory}`
																)
															);
														}
													);
												}

												if (
													Object.keys(acfJsonFiles)
														.length > 0
												) {
													for (acfJsonPath in acfJsonFiles) {
														rimraf(
															acfJsonPath,
															function () {
																console.log(
																	chalk.green(
																		`DELETED: ${acfJsonPath}`
																	)
																);
																console.log(
																	chalk.yellow(
																		'The deleted ACF field groups must also be removed from the database by manually deleting them from the "Custom Fields" page in the WordPress dashboard.'
																	)
																);
															}
														);
													}
												} else {
													console.log(
														chalk.yellow(
															'No ACF json files were found corresponding to this block. Be sure to double check and manually delete any corresponding fields on the "Custom Fields" page in the WordPress dashboard.'
														)
													);
												}

												callback();
											} else {
												callback();
											}
										}
									)
								);
							});
					},
					(error) => {
						if (null !== error) {
							console.log(chalk.red(error));
						}

						done();
					}
				);
			} else {
				console.log(chalk.red('No blocks selected for removal.'));
				done();
			}
		})
	);
});
