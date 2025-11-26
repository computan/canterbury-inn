/* eslint no-console: 0 */

const gulp = require('gulp');
const Figma = require('figma-api');
const prompt = require('gulp-prompt');
const chalk = require('chalk');
const { JSONPath } = require('jsonpath-plus');
const fs = require('fs');
const path = require('path');
const WP = require('wp-cli');
const download = require('image-downloader');
const backstop = require('backstopjs');
const replace = require('gulp-replace');
const getArguments = require('./getArguments');
getArguments.parse();
const envArgs = getArguments();
const rimraf = require('rimraf');
const { figmaRGBToHex } = require('@figma-plugin/helpers');
const sharp = require('sharp');
const Jimp = require('jimp');

let settings;
let siteUrl;
let qaSettings;
let backstopConfig;
let figmaAndWPBlockNames;
let blockNames;

gulp.task('qa:automatedSetup', (done) => {
	envArgs.import = true;

	done();
});

gulp.task('qa:loadFigmaSettings', (done) => {
	settings = JSON.parse(
		fs.readFileSync(
			path.resolve(
				`css${path.sep}__base-includes${path.sep}figma${path.sep}figma-settings.json`
			)
		)
	);

	qaSettings = JSON.parse(
		fs.readFileSync(path.resolve(`qa${path.sep}qa-settings.json`))
	);

	done();
});

gulp.task('qa:loadBlockNames', (done) => {
	WP.discover({ 'skip-plugins': true }, function (wp) {
		wp.option.get('siteUrl', function (error, url) {
			if (error) {
				console.log(error);
				done();
			}

			if (url) {
				siteUrl = url;

				if (envArgs.block || envArgs.blocks) {
					let singleBlocks = envArgs.blocks;

					if (!singleBlocks) {
						singleBlocks = envArgs.block;
					}

					blockNames = singleBlocks.split(',').map((block) => {
						return block.trim();
					});

					done();
				} else {
					wp.post.list(
						{ post_type: 'library_block', fields: 'post_title' },
						function (err, posts) {
							if (!err) {
								if (posts && posts.length > 0) {
									blockNames = posts.map((post) => {
										return post.post_title;
									});

									done();
								}
							} else {
								console.log(err);
								console.log(
									chalk.red(
										`Error. Is the Local site running?`
									)
								);
								done();
								process.exit();
							}
						}
					);
				}
			}
		});
	});
});

gulp.task('qa:loadBackstopConfig', (done) => {
	backstopConfig = JSON.parse(
		fs.readFileSync(path.resolve(`qa${path.sep}backstop.json`))
	);

	figmaAndWPBlockNames = JSON.parse(
		fs.readFileSync(
			path.resolve(`qa${path.sep}figma-and-wp-block-names.json`)
		)
	);

	if (
		backstopConfig &&
		figmaAndWPBlockNames &&
		blockNames.length > 0 &&
		siteUrl
	) {
		blockNames.forEach((blockName) => {
			let scenario = {};

			if (figmaAndWPBlockNames[`${blockName}-Desktop`]) {
				figmaAndWPBlockNames[`${blockName}-Desktop`].forEach(
					(blockVariationName, blockIndex) => {
						const label = blockVariationName.replace(
							'-Desktop',
							''
						);

						if (qaSettings.ignoreBlockVariations.includes(label)) {
							return;
						}

						scenario = {
							label,
							url:
								siteUrl +
								`/block-library/?backstop=1&qa=${blockName}&index=${
									blockIndex + 1
								}`,
						};

						backstopConfig.scenarios.push({
							...backstopConfig.scenarioDefaults,
							...qaSettings?.blockScenarioOverrides?.[blockName],
							...qaSettings?.blockScenarioOverrides?.[label],
							...scenario,
						});
					}
				);
			}
		});
	}

	done();
});

gulp.task('qa:backstop', (done) => {
	backstop('test', {
		config: backstopConfig,
	})
		.then((result) => {
			// test successful
			done();
		})
		.catch(() => {
			// test failed
			done();
		});
});

gulp.task('qa:renameLatestTest', (done) => {
	const filesToCheck = [
		path.resolve(`qa${path.sep}reports${path.sep}html${path.sep}config.js`),
		path.resolve(
			`qa${path.sep}reports${path.sep}json${path.sep}jsonReport.json`
		),
	];

	let filesExist = true;

	filesToCheck.forEach((file) => {
		const fileDir = path.dirname(file);

		if (!fs.existsSync(fileDir) || !fs.existsSync(file)) {
			filesExist = false;
		}
	});

	if (filesExist) {
		gulp.src([
			`qa/reports/html/config.js`,
			`qa/reports/json/jsonReport.json`,
		])
			.pipe(
				replace(
					/\/images\/wordpress\/\d*-\d*\//gm,
					'/images/wordpress/latest-test/'
				)
			)
			.pipe(
				gulp.dest(function (file) {
					return file.base;
				})
			)
			.on('finish', done);
	} else {
		gulp.src('./package.json').on('finish', done);
	}
});

gulp.task('qa:generateBlockQAStatusFile', (done) => {
	if (envArgs.block || envArgs.blocks) {
		done();
		return;
	}

	const json = JSON.parse(
		fs.readFileSync(
			path.resolve(
				`qa${path.sep}reports${path.sep}json${path.sep}jsonReport.json`
			)
		)
	);

	const missingBlocks = JSON.parse(
		fs.readFileSync(path.resolve(`qa${path.sep}missing-blocks.json`))
	);

	const result = {
		'Failed Blocks': {},
		'Missing Blocks': {},
		'Ignored Blocks': qaSettings?.blocksToIgnore ?? {},
		'Undeveloped Blocks': Object.values(missingBlocks)[2],
		'Passing Blocks': {},
	};

	if (json.tests && json.tests.length > 0) {
		const figmaBlockNodesData = JSON.parse(
			fs.readFileSync(path.resolve(`qa${path.sep}figma-block-nodes.json`))
		);

		json.tests.forEach((test) => {
			if (test.pair && test.pair.label && test.pair.viewportLabel) {
				let category = 'Unknown';

				for (const figmaBlockNodeName in figmaBlockNodesData) {
					if (test.pair.label.includes(figmaBlockNodeName)) {
						category =
							figmaBlockNodesData[figmaBlockNodeName].category;

						break;
					}
				}

				if (
					test.pair.diff &&
					test.pair.diff.rawMisMatchPercentage !== null &&
					test.pair.misMatchThreshold !== null
				) {
					if (
						test.pair.diff.rawMisMatchPercentage >=
						test.pair.misMatchThreshold
					) {
						if (!result['Failed Blocks'][category]) {
							result['Failed Blocks'][category] = [];
						}

						result['Failed Blocks'][category].push(
							`${test.pair.label}-${test.pair.viewportLabel}`
						);
					} else {
						if (!result['Passing Blocks'][category]) {
							result['Passing Blocks'][category] = [];
						}

						result['Passing Blocks'][category].push(
							`${test.pair.label}-${test.pair.viewportLabel}`
						);
					}
				} else {
					if (!result['Missing Blocks'][category]) {
						result['Missing Blocks'][category] = [];
					}

					result['Missing Blocks'][category].push(
						`${test.pair.label}-${test.pair.viewportLabel}`
					);
				}
			}
		});
	}

	for (const key in result) {
		const blocks = result[key];
		for (const blockKey in blocks) {
			const blockArray = blocks[blockKey];
			if (Array.isArray(blockArray) && blockArray.length > 0) {
				blockArray.sort();
			}
		}
	}

	fs.writeFile(
		`${path.resolve(`qa`)}${path.sep}block-qa-status.json`,
		JSON.stringify(result, null, 4),
		function (missingError) {
			if (missingError) {
				console.log(chalk.red(`Error creating block-qa-status.json`));
			} else {
				console.log(`Created block-qa-status.json.`);
			}
		}
	);

	done();
});

gulp.task('qa:copyLatestTest', (done) => {
	const wordpressImagePath = path.resolve(
		`qa${path.sep}images${path.sep}wordpress${path.sep}`
	);

	if (!fs.existsSync(wordpressImagePath)) {
		fs.mkdirSync(wordpressImagePath);
	}

	const directories = fs.readdirSync(wordpressImagePath);

	let directoryToCopy = 'temp-directory';

	if (directories.length > 0) {
		directories.reverse();

		directories.forEach((directory) => {
			if (
				'temp-directory' === directoryToCopy &&
				'latest-test' !== directory
			) {
				directoryToCopy = directory;
			}
		});
	}

	gulp.src([`qa/images/wordpress/${directoryToCopy}/*`])
		.pipe(
			gulp.dest(
				path.resolve(
					`qa${path.sep}images${path.sep}wordpress${path.sep}latest-test`
				)
			)
		)
		.on('finish', done);
});

gulp.task('qa:downloadImages', async (done) => {
	if (!blockNames || !blockNames.length > 0) {
		console.log(chalk.red(`No WordPress blocks found.`));

		done();
		process.exit();
	}

	if (envArgs.file) {
		settings.figmaFileID = envArgs.file;
	}

	if (envArgs.key) {
		envArgs.token = envArgs.key;
	}

	const promptSettings = [];

	if (!envArgs.token && !envArgs.figmaFileID && !envArgs.import) {
		promptSettings.push({
			type: 'confirm',
			name: 'importImages',
			message: chalk.blue(
				'Do you want to download the latest block images from Figma?'
			),
			default: false,
		});
	}

	if (!envArgs.token) {
		promptSettings.push({
			type: 'input',
			name: 'apiToken',
			message: chalk.blue('Figma API token (found in 1password):'),
			validate(input) {
				if ('' === input) {
					return chalk.red('API token required.');
				}

				return true;
			},
			when(responses) {
				if (true === responses.importImages || envArgs.import) {
					return true;
				}
			},
		});
	}

	if (!settings.figmaFileID) {
		promptSettings.push({
			type: 'input',
			name: 'figmaFileID',
			message: chalk.blue(
				'Figma File ID (found in the Figma URL for the project: https://www.figma.com/file/THIS_IS_THE_FILE_ID/...):'
			),
			validate(input) {
				if ('' === input) {
					return chalk.red('Figma File ID required.');
				}

				return true;
			},
			when(responses) {
				if (true === responses.importImages || envArgs.import) {
					return true;
				}
			},
		});
	}

	await new Promise((resolve) => {
		gulp.src('./package.json').pipe(
			prompt.prompt(promptSettings, function (response) {
				if (!response.apiToken && !envArgs.token) {
					resolve();
					done();
					return;
				}

				if (!envArgs.block && !envArgs.blocks) {
					const imageDirectory = path.resolve(`qa/images/figma/`);

					rimraf(imageDirectory, function () {
						console.log(
							chalk.green(
								`Deleted old Figma images in: ${imageDirectory}`
							)
						);

						fs.mkdirSync(imageDirectory, { recursive: true });

						let token;

						if (envArgs.token) {
							token = envArgs.token;
						} else {
							token = response.apiToken;
						}

						const api = new Figma.Api({
							personalAccessToken: token,
						});

						let figmaFileID;

						if (settings.figmaFileID) {
							figmaFileID = settings.figmaFileID;
						} else {
							figmaFileID = response.figmaFileID;
						}

						api.getFileNodes(figmaFileID, ['2061:1']).then(
							(file) => {
								const blocks = [];
								let blockChildrenIds = [];
								const figmaBlockNames = {};
								let blockNodes = [];
								const blockLabelsWithoutMatchingBlocks = [];
								const figmaBlocksWithoutWordpressBlocks = {
									'Essential Blocks': [],
									'Curated Blocks': [],
									'Archive Blocks': [],
									'New Curated Blocks': [],
									'Traction Rec': [],
									Other: [],
								};
								const wordpressBlocksWithoutFigmaBlocks = [];

								const foundationBlockNodes = JSONPath({
									path: `$..document..children[?(@.type === 'FRAME' && (@.name.match(/Button-Styles$/gm) || @.name.match(/Text-Styles$/gm) || @.name.match(/Icon-Library$/gm) || @.name.match(/Form-Styles$/gm) || @.name.match(/Color-Styles$/gm)))]`,
									json: file,
								});

								const blockCategoryNodes = JSONPath({
									path: `$..document..children[?(@.type === 'FRAME' && @.name.match(/.*Blocks$/gm))]`,
									json: file,
								});

								if (foundationBlockNodes.length > 0) {
									getBlockImages(
										foundationBlockNodes,
										api,
										figmaFileID
									).then(() => {
										resolve();
										done();
									});
								}

								if (blockCategoryNodes.length > 0) {
									blockCategoryNodes.forEach(
										(blockCategoryNode) => {
											const blockLabelNodes = JSONPath({
												path: `$..children[?(@.name.match(/^Block-Label.*/gm))]`,
												json: blockCategoryNode,
											});

											const blockLabels = [];

											if (blockLabelNodes.length > 0) {
												blockLabelNodes.forEach(
													(blockLabelNode) => {
														let blockCategory =
															'Unknown';

														if (
															blockLabelNode.backgroundColor
														) {
															const backgroundColorHexCode =
																figmaRGBToHex(
																	blockLabelNode.backgroundColor
																);

															if (
																'#9d49fd' ===
																backgroundColorHexCode
															) {
																blockCategory =
																	'Essential Blocks';
															} else if (
																'#127cfd' ===
																backgroundColorHexCode
															) {
																blockCategory =
																	'Curated Blocks';
															} else if (
																'#d252e0' ===
																backgroundColorHexCode
															) {
																blockCategory =
																	'Archive Blocks';
															} else if (
																'#f59e2c' ===
																backgroundColorHexCode
															) {
																blockCategory =
																	'New Curated Blocks';
															} else if (
																'#31ad6b' ===
																backgroundColorHexCode
															) {
																blockCategory =
																	'Traction Rec';
															}
														}

														if (
															blockLabelNode
																.children[0] &&
															blockLabelNode
																.children[0]
																.characters
														) {
															blockLabels.push({
																label: blockLabelNode
																	.children[0]
																	.characters,
																blockCategory,
															});
														}
													}
												);
											}

											if (blockLabels.length > 0) {
												blockLabels.forEach(
													(blockLabelObject) => {
														const blockLabel =
															blockLabelObject.label;

														const blockFrameNodes =
															JSONPath({
																path: `$..children[?(@.name.match(/^${blockLabel}$/gm) && @.type === 'FRAME')]`,
																json: blockCategoryNode,
															});

														let blockCategoryBlockNodes =
															JSONPath({
																path: `$..children[?((@.type === 'COMPONENT' || @.type === 'INSTANCE') && !@.name.match(/^Block-Label.*/gm) && (@.name.match(/^${blockLabel}.*-Desktop$/gm) || @.name.match(/^${blockLabel}.*-Mobile$/gm)))]`,
																json:
																	blockFrameNodes.length >
																	0
																		? blockFrameNodes[0]
																		: blockCategoryNode,
															});

														if (
															blockCategoryBlockNodes.length >
															0
														) {
															const childrenNodeIds =
																getRecursiveChildrenIds(
																	blockCategoryBlockNodes
																);

															blockCategoryBlockNodes =
																blockCategoryBlockNodes.filter(
																	(node) =>
																		!childrenNodeIds.includes(
																			node.id
																		)
																);
														}

														if (
															blockCategoryBlockNodes.length >
															0
														) {
															blockCategoryBlockNodes.forEach(
																(
																	blockCategoryBlockNode,
																	index
																) => {
																	blockCategoryBlockNodes[
																		index
																	].blockCategory =
																		blockLabelObject.blockCategory;
																}
															);

															blockNodes =
																blockNodes.concat(
																	blockCategoryBlockNodes
																);
														} else {
															blockLabelsWithoutMatchingBlocks.push(
																blockLabel
															);
														}
													}
												);
											}
										}
									);
								}

								if (!blockNodes.length) {
									console.log(
										chalk.red(
											`Error finding components in Figma file.`
										)
									);

									done();
									process.exit();
								}

								blockNames.forEach((blockName) => {
									if (blockNodes && blockNodes.length > 0) {
										let desktopBlockFound = false;
										let mobileBlockFound = false;

										blockNodes.forEach((blockNode) => {
											if (!blockNode.name) {
												return;
											}

											blockNode.nodeName = blockNode.name;

											if (
												!blockNode.name.includes(
													blockName
												)
											) {
												return;
											}

											blockNode.blockName = blockName;

											const screenSizes = [
												'Desktop',
												'Mobile',
											];

											screenSizes.forEach(
												(screenSize) => {
													if (
														blockNode.name.includes(
															`-${screenSize}`
														)
													) {
														const blockNameWithScreenSize = `${blockName.replace(
															':',
															''
														)}-${screenSize}`;

														let variationNumber = 1;

														if (
															!figmaBlockNames[
																blockNameWithScreenSize
															]
														) {
															figmaBlockNames[
																blockNameWithScreenSize
															] = [];
														} else {
															variationNumber =
																figmaBlockNames[
																	blockNameWithScreenSize
																].length + 1;
														}

														if (
															'Desktop' ===
															screenSize
														) {
															blockNode.name =
																blockNode.name.replace(
																	'-Desktop',
																	`-${variationNumber}-Desktop`
																);
															desktopBlockFound = true;
														} else if (
															'Mobile' ===
															screenSize
														) {
															blockNode.name =
																blockNode.name.replace(
																	'-Mobile',
																	`-${variationNumber}-Mobile`
																);
															mobileBlockFound = true;
														}

														if (
															!blockChildrenIds.includes(
																blockNode.id
															)
														) {
															if (
																!checkIfInIgnoreList(
																	blockNode.name
																)
															) {
																blocks.push(
																	blockNode
																);

																figmaBlockNames[
																	blockNameWithScreenSize
																].push(
																	blockNode.name.replace(
																		':',
																		''
																	)
																);

																blockChildrenIds =
																	getChildElementIds(
																		blockNode,
																		blockChildrenIds
																	);
															}
														}
													}
												}
											);
										});

										if (!checkIfInIgnoreList(blockName)) {
											if (false === desktopBlockFound) {
												console.log(
													chalk.red(
														`Error: block not found in Figma: ${blockName}-Desktop`
													)
												);

												wordpressBlocksWithoutFigmaBlocks.push(
													`${blockName}-Desktop`
												);
											}

											if (false === mobileBlockFound) {
												console.log(
													chalk.red(
														`Error: block not found in Figma: ${blockName}-Mobile`
													)
												);

												wordpressBlocksWithoutFigmaBlocks.push(
													`${blockName}-Mobile`
												);
											}
										}
									}
								});

								blockNodes.forEach((blockNode) => {
									if (blockNode.name) {
										const blockNameWithoutScreenSize =
											blockNode.name
												.replace('-Desktop', '')
												.replace('-Mobile', '');

										let wordPressBlockExists = false;

										if (
											blockNames.includes(
												blockNameWithoutScreenSize
											)
										) {
											wordPressBlockExists = true;
										} else {
											blockNames.forEach((blockName) => {
												if (
													!wordPressBlockExists &&
													blockNameWithoutScreenSize.includes(
														blockName
													)
												) {
													wordPressBlockExists = true;
												}
											});

											if (
												!figmaBlocksWithoutWordpressBlocks[
													blockNode.blockCategory
												]
											) {
												figmaBlocksWithoutWordpressBlocks[
													blockNode.blockCategory
												] = [];
											}

											if (
												!wordPressBlockExists &&
												!figmaBlocksWithoutWordpressBlocks[
													blockNode.blockCategory
												].includes(
													blockNameWithoutScreenSize
												) &&
												!checkIfInIgnoreList(
													blockNode.name
												)
											) {
												figmaBlocksWithoutWordpressBlocks[
													blockNode.blockCategory
												].push(
													blockNameWithoutScreenSize
												);
											}
										}
									}
								});

								const missingBlockData = {
									'Block labels in Figma without any matching Figma blocks. All block components in Figma should contain the block label as a prefix. Contact the Computan designer to update the block components to match the block labels.':
										blockLabelsWithoutMatchingBlocks,
									'WordPress block library posts not found in Figma. Check to make sure they exist and the WordPress library block post titles match the Figma block component names. The developer will likely need to update the library block post titles to match Figma.':
										wordpressBlocksWithoutFigmaBlocks,
									'Figma blocks not found within WordPress. Check to make sure the block code exists within WordPress and a Library Block post has been created.':
										figmaBlocksWithoutWordpressBlocks,
								};

								if (!envArgs.block && !envArgs.blocks) {
									fs.writeFile(
										`${path.resolve(`qa`)}${
											path.sep
										}missing-blocks.json`,
										JSON.stringify(
											missingBlockData,
											null,
											4
										),
										function (missingError) {
											if (missingError) {
												console.log(
													chalk.red(
														`Error creating missing-blocks.json`
													)
												);
											} else {
												console.log(
													`Created missing-blocks.json.`
												);
											}
										}
									);
								}

								if (blocks.length > 0) {
									if (!envArgs.block && !envArgs.blocks) {
										fs.writeFile(
											`${path.resolve(`qa`)}${
												path.sep
											}figma-and-wp-block-names.json`,
											JSON.stringify(
												figmaBlockNames,
												null,
												4
											),
											function (blockNamesError) {
												if (blockNamesError) {
													console.log(
														chalk.red(
															`Error creating figma-and-wp-block-names.json`
														)
													);
												} else {
													console.log(
														`Created figma-and-wp-block-names.json`
													);
												}
											}
										);
									}

									getBlockImages(
										blocks,
										api,
										figmaFileID
									).then(() => {
										resolve();
										done();
									});
								}

								if (!envArgs.block && !envArgs.blocks) {
									const figmaBlockNodesData =
										blockNodes.reduce(
											(accumulator, blockNode) => {
												let blockName =
													blockNode.nodeName;

												if (blockNode.blockName) {
													blockName =
														blockNode.blockName;
												}

												blockName = blockName
													.replace('-Desktop', '')
													.replace('-Mobile', '');

												if (!accumulator[blockName]) {
													accumulator[blockName] = {
														desktopVariations: 0,
														mobileVariations: 0,
														category:
															blockNode.blockCategory,
													};
												}

												if (
													blockNode.nodeName.includes(
														'-Desktop'
													)
												) {
													accumulator[blockName]
														.desktopVariations++;
												} else if (
													blockNode.nodeName.includes(
														'-Mobile'
													)
												) {
													accumulator[blockName]
														.mobileVariations++;
												}

												return accumulator;
											},
											{}
										);

									fs.writeFile(
										`${path.resolve(`qa`)}${
											path.sep
										}figma-block-nodes.json`,
										JSON.stringify(
											figmaBlockNodesData,
											null,
											4
										),
										function (blockNodesError) {
											if (blockNodesError) {
												console.log(
													chalk.red(
														`Error creating figma-block-nodes.json`
													)
												);
											} else {
												console.log(
													`Created figma-block-nodes.json`
												);
											}
										}
									);
								}

								getBlockImages(blocks, api, figmaFileID).then(
									() => {
										resolve();
										done();
									}
								);
							},
							(error) => {
								if (
									error.response &&
									error.response.data &&
									error.response.data.status &&
									error.response.data.err
								) {
									console.log(
										chalk.red(
											`Error retrieving Figma file. Error code ${error.response.data.status}: ${error.response.data.err}`
										)
									);
								} else {
									console.log(
										chalk.red(
											`Error retrieving Figma file.`
										)
									);
								}
								done();
								process.exit();
							}
						);
					});
				}
			})
		);
	});
});

const getRecursiveChildrenIds = (nodeArray, isFirstCall = true) => {
	return nodeArray.reduce((accumulator, blockNode) => {
		if (!isFirstCall) {
			accumulator.push(blockNode.id);
		}

		if (blockNode.children && blockNode.children.length > 0) {
			const childIds = getRecursiveChildrenIds(blockNode.children, false);
			accumulator.push(...childIds);
		}

		return accumulator;
	}, []);
};

const downloadImage = (
	block,
	file,
	imageDirectory,
	currentTimeMs,
	blockIndex
) => {
	return new Promise((resolve) => {
		if (file.images[block.id]) {
			const options = {
				url: file.images[block.id],
				dest: `${imageDirectory}${path.sep}${block.name}.png`,
			};

			download
				.image(options)
				.then(({ filename }) => {
					const currentTime = new Date(currentTimeMs + blockIndex);

					fs.utimesSync(filename, currentTime, currentTime);
					console.log('Saved to', filename);
					resolve();

					if (filename.includes('Button-Styles.png')) {
						convertImageIntoSections(filename);
					}
				})
				.catch((err) => {
					console.error(err);
					resolve();
				});
		}
	});
};

const getBlockImages = async (blocks, api, figmaFileID) => {
	blocks = blocks.map((block) => {
		block.name = block.name.replace(':', '');

		return block;
	});

	return new Promise((resolve) => {
		const imageDirectory = path.resolve(`qa/images/figma/`);

		downloadBlockImages(blocks, api, figmaFileID, imageDirectory).then(
			resolve()
		);
	});
};

const downloadBlockImages = async (
	blocks,
	api,
	figmaFileID,
	imageDirectory
) => {
	const blockIds = blocks.map((block) => {
		return block.id;
	});

	const currentTimeMs = new Date().getMilliseconds();

	let segmentOfBlockIds = [];

	for (let i = 0; i < blockIds.length / 100; i++) {
		segmentOfBlockIds = blockIds.slice(i * 100, i * 100 + 100);

		api.getImage(figmaFileID, { ids: [segmentOfBlockIds] }).then(
			(file) => {
				if (file.images) {
					Promise.all(
						blocks.map((block, blockIndex) =>
							downloadImage(
								block,
								file,
								imageDirectory,
								currentTimeMs,
								blockIndex
							)
						)
					);
				}
			},
			(error) => {
				if (
					error.response &&
					error.response.data &&
					error.response.data.status &&
					error.response.data.err
				) {
					console.log(
						chalk.red(
							`Error retrieving Figma image. Error code ${error.response.data.status}: ${error.response.data.err}`
						)
					);
				} else {
					console.log(chalk.red(`Error retrieving Figma image.`));
				}
			}
		);
	}
};

const getChildElementIds = (blockNode, blockChildrenIds) => {
	if (blockNode.children && blockNode.children.length > 0) {
		blockNode.children.forEach(function (childNode) {
			blockChildrenIds.push(childNode.id);

			blockChildrenIds = getChildElementIds(childNode, blockChildrenIds);
		});
	}

	return blockChildrenIds;
};

const convertImageIntoSections = (filePath) => {
	// Load the image
	sharp(filePath)
		.toBuffer()
		.then((buffer) => {
			// Convert the image to grayscale
			return sharp(buffer).greyscale().toBuffer();
		})
		.then((buffer) => {
			Jimp.read(buffer, (err, image) => {
				if (err) {
					console.error(err);
					return;
				}

				const width = image.bitmap.width;
				const height = image.bitmap.height;

				// Analyze the image to detect sections
				let lastColor = null;
				let sectionStart = 0;
				let sectionCounter = 0;

				for (let y = 0; y < height; y++) {
					const currentColor = image.getPixelColour(0, y);

					if (lastColor === null) {
						lastColor = currentColor;
					} else if (currentColor !== lastColor) {
						// Split the section and save it as a separate image
						const sectionWidth = y - sectionStart;
						const sectionFilePath = `${path.dirname(
							filePath
						)}/${path.basename(
							filePath,
							path.extname(filePath)
						)}-${sectionCounter}.png`;
						sharp(filePath)
							.extract({
								left: 0,
								top: sectionStart,
								width,
								height: sectionWidth,
							})
							.toFile(sectionFilePath, (sectionError) => {
								if (sectionError) {
									console.error(
										'Error saving section:',
										sectionError
									);
								} else {
									console.log(
										`Section saved: ${sectionFilePath}`
									);
								}
							});
						// Move to the next section
						sectionStart = y;
						lastColor = currentColor;
						sectionCounter++;
					}
				}
			});
		})
		.catch((err) => {
			console.error('Error processing image:', err);
		});
};

const checkIfInIgnoreList = (blockName) => {
	let inIgnoreList = false;

	if (qaSettings.blocksToIgnore && qaSettings.blocksToIgnore.length > 0) {
		qaSettings.blocksToIgnore.forEach((ignoredBlockName) => {
			if (blockName.includes(ignoredBlockName)) {
				inIgnoreList = true;
			}
		});
	}

	return inIgnoreList;
};

gulp.task(
	'qa',
	gulp.series(
		'qa:automatedSetup',
		'qa:loadFigmaSettings',
		'qa:loadBlockNames',
		'qa:downloadImages',
		'qa:loadBackstopConfig',
		'qa:backstop',
		'qa:copyLatestTest',
		'qa:renameLatestTest',
		'qa:generateBlockQAStatusFile'
	)
);

gulp.task(
	'qa:load',
	gulp.series(
		'qa:loadFigmaSettings',
		'qa:loadBlockNames',
		'qa:downloadImages'
	)
);

gulp.task(
	'qa:test',
	gulp.series(
		'qa:loadFigmaSettings',
		'qa:loadBlockNames',
		'qa:loadBackstopConfig',
		'qa:backstop',
		'qa:copyLatestTest',
		'qa:renameLatestTest',
		'qa:generateBlockQAStatusFile'
	)
);
