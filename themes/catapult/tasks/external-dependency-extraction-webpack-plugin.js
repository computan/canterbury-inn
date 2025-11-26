const fs = require('fs');
const path = require('path');

class ExternalDependencyExtractionWebpackPlugin {
	constructor(options) {
		this.options = options;
	}

	apply(compiler) {
		compiler.hooks.emit.tapAsync(
			'ExternalDependencyExtractionWebpackPlugin',
			(compilation, callback) => {
				if (!compilation.entrypoints) {
					return;
				}

				const { entries } = this.options;

				for (const entryName in entries) {
					if (
						Object.prototype.hasOwnProperty.call(entries, entryName)
					) {
						const entry = compilation.entrypoints.get(entryName);

						if (!entry?.chunks) {
							continue;
						}

						if (!entry?._entrypointChunk?.runtime) {
							continue;
						}

						const runtimeChunk = entry._entrypointChunk.runtime;
						const modules = entry.chunks
							.map((chunk) => chunk.name)
							.filter((chunk) => {
								if (chunk && chunk.includes('modules/')) {
									return true;
								}

								return false;
							});

						const content = `<?php return array('dependencies' => ${JSON.stringify(modules)});`;
						const outPutDirectory = path.join(
							compiler.outputPath,
							runtimeChunk
						);
						const outputPath = outPutDirectory + '.modules.php';

						if (!fs.existsSync(outPutDirectory)) {
							fs.mkdirSync(outPutDirectory, {
								recursive: true,
							});
						}

						fs.writeFile(outputPath, content, (err) => {
							if (err) {
								console.error(
									`Error writing to modules file ${outputPath}: ${err}`
								);
							}
						});
					}
				}

				callback();
			}
		);
	}
}

module.exports = ExternalDependencyExtractionWebpackPlugin;
