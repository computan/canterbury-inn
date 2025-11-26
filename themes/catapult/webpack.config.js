const path = require('path');
const glob = require('glob');
const ESLintPlugin = require('eslint-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const ExternalDependencyExtractionWebpackPlugin = require('./tasks/external-dependency-extraction-webpack-plugin');

const themeJsDirectory = 'js';
const themeDistDirectory = `dist${path.sep}`;
const blockDirectory = 'blocks';

const blockEntries = glob
	.sync(`.${path.sep}${blockDirectory}${path.sep}**${path.sep}script.js`, {
		ignore: `.${path.sep}${blockDirectory}${path.sep}react-blocks${path.sep}**`,
	})
	.reduce((acc, filepath) => {
		let entry = filepath
			.replace(`./${blockDirectory}`, '')
			.replace(`/script.js`, '');
		let entryParts = entry.split('/');

		if (entryParts.length > 3) {
			entryParts = [
				entryParts[0],
				entryParts[1],
				entryParts[entryParts.length - 1],
			];
			entry = entryParts.join('/');
		}
		acc[entry] = filepath;
		return acc;
	}, {});

const blockEditorEntries = glob
	.sync(`.${path.sep}${blockDirectory}${path.sep}**${path.sep}editor.js`, {
		ignore: `.${path.sep}${blockDirectory}${path.sep}react-blocks${path.sep}**`,
	})
	.reduce((acc, filepath) => {
		let entry = filepath
			.replace(`./${blockDirectory}`, '')
			.replace(`/editor.js`, '');
		let entryParts = entry.split('/');
		if (entryParts.length > 3) {
			entryParts = [
				entryParts[0],
				entryParts[1],
				entryParts[entryParts.length - 1],
			];
			entry = entryParts.join('/');
		}
		acc[entry + '-editor'] = filepath;
		return acc;
	}, {});

const reactBlockEntries = glob
	.sync(
		`.${path.sep}${blockDirectory}${path.sep}react-blocks${path.sep}**${path.sep}{index,script,editor,view}.js`
	)
	.reduce((acc, filepath) => {
		const entry = filepath
			.replace(`./${blockDirectory}`, '')
			.replace(`.js`, '');
		acc[entry] = filepath;
		return acc;
	}, {});

const entries = {
	bundle: path.resolve(__dirname, themeJsDirectory, 'script.js'),
	editor: path.resolve(__dirname, themeJsDirectory, 'editor.js'),
	lighthouse: path.resolve(__dirname, themeJsDirectory, 'lighthouse.js'),
	...blockEntries,
	...blockEditorEntries,
	...reactBlockEntries,
};

const settings = {
	entry: entries,
	output: {
		path: path.resolve(__dirname, themeDistDirectory),
		filename: '[name].js',
		publicPath: '/wp-content/themes/catapult/dist/',
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				sideEffects: false,
				use: [
					{
						loader: 'babel-loader',
					},
				],
			},
			{
				test: /swiper/,
				sideEffects: false,
			},
		],
	},
	plugins: [
		new ESLintPlugin({ failOnError: false }),
		new DependencyExtractionWebpackPlugin(),
		new ExternalDependencyExtractionWebpackPlugin({ entries }),
	],
	mode: 'development',
	devtool: 'source-map',
	optimization: {
		splitChunks: {
			chunks: 'all',
			hidePathInfo: true,
			minSize: 5000,
			minChunks: 1,
			cacheGroups: {
				modules: {
					test: /[\\/]node_modules[\\/]/,
					name: (module) => {
						let packageName = module.context.match(
							/[\\/]node_modules[\\/](.*?)([\\/]|$)/
						)[1];

						if (
							module?.resourceResolveData?.context?.issuer &&
							module.resourceResolveData.context.issuer.includes(
								'node_modules'
							)
						) {
							packageName =
								module.resourceResolveData.context.issuer.match(
									/[\\/]node_modules[\\/](.*?)([\\/]|$)/
								)[1];
						}

						return `modules/${packageName.replace('@', '')}`;
					},
					chunks: 'all',
				},
				components: {
					test: /[\\/]blocks[\\/]components[\\/]/,
					name: (module) => {
						const packageName = module.context.match(
							/[\\/]blocks[\\/]components[\\/](.*?)([\\/]|$)/
						)[1];

						return `modules/${packageName}`;
					},
					chunks: 'all',
				},
			},
		},
	},
};

module.exports = settings;
