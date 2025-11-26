const gulp = require('gulp');
const path = require('path');
const getArguments = require('./getArguments');
const replace = require('gulp-replace');
const through2 = require('through2');
const { execSync } = require('child_process');
const fs = require('fs');

let changelogData;

// Function to get changed PHP files
const getChangedPhpFiles = () => {
	const repoRoot = execSync('git rev-parse --show-toplevel', {
		encoding: 'utf-8',
	}).trim();

	const output = execSync("git diff --name-only main..HEAD -- '*.php'", {
		encoding: 'utf-8',
		cwd: repoRoot,
	});
	const files = output.split('\n').filter((file) => file.trim() !== '');

	return files.filter((file) => {
		const diff = execSync(
			`git diff main..HEAD -- ${file} | sed '/^index/d; /^---/d; /^\+\+/d; /^@@/d'`,
			{
				encoding: 'utf-8',
				cwd: repoRoot,
			}
		);

		const changedLines = diff
			.split('\n')
			.filter((line) => line.startsWith('+'));

		if (changedLines.some((str) => !str.includes('@since'))) {
			return true;
		}

		return false;
	});
};

const getChangelogData = (filePath) => {
	const changeLogPath = path
		.resolve(filePath)
		.replace(/(?<=wp-content.).*/gm, 'CHANGELOG.md');

	const catapultDirectory = path.dirname(changeLogPath);

	process.chdir(catapultDirectory);

	if (!fs.existsSync(changeLogPath)) {
		console.log('No CHANGELOG.md file found at: ' + changeLogPath);

		return;
	}

	const changelogText = fs.readFileSync(changeLogPath, 'utf8');

	const matches = [
		...changelogText.matchAll(/## \[([^[]*)\] - (.{4}-.{2}-.{2})/gm),
	];

	const results = matches.map((match) => ({
		version: match[1],
		dateText: match[2],
		date: new Date(match[2]),
		year: parseInt(match[2].slice(0, 4)),
		month: parseInt(match[2].slice(5, 7)),
		day: parseInt(match[2].slice(8, 10)),
	}));

	return results;
};

const getFileChangelogData = (filePath) => {
	if (!changelogData) {
		changelogData = getChangelogData(filePath);
	}

	if (!changelogData) {
		return;
	}

	const gitLog = execSync(
		`git log --pretty=format:"%s|%ad" --date=iso -- "${filePath}"`,
		{ encoding: 'utf8' }
	);

	const commits = gitLog.split('\n').map((line) => {
		const [message, dateStr] = line.split('|');
		const date = new Date(dateStr);

		return {
			message,
			date,
			year: date.getFullYear(),
			month: date.getMonth() + 1,
			day: date.getDate(),
		};
	});

	const fileChangelogData = commits.map((commit) => {
		let closestChangelogDate = '';
		let closestChangelogVersion = '';
		let closestDiff = Infinity;

		if ('XXXX-XX-XX' === changelogData[0]?.dateText) {
			closestChangelogVersion = changelogData[0].version;
		}

		changelogData.forEach((changeLogEntry) => {
			if (
				changeLogEntry.date > commit.date ||
				(changeLogEntry.year === commit.year &&
					changeLogEntry.month === commit.month &&
					changeLogEntry.day === commit.day)
			) {
				const diff =
					changeLogEntry.date.getTime() - commit.date.getTime();

				if (diff < closestDiff) {
					closestDiff = diff;
					closestChangelogDate = changeLogEntry.dateText;
					closestChangelogVersion = changeLogEntry.version;
				}
			}
		});

		return {
			commit: commit.message,
			date: commit.date,
			closestChangelogDate,
			closestChangelogVersion,
		};
	});

	return fileChangelogData;
};

gulp.task('version', (done) => {
	const changedFiles = getChangedPhpFiles();

	const existingFiles = [];

	if (changedFiles.length > 0) {
		changedFiles.forEach((changedFile) => {
			if (!changedFile.includes('catapult')) {
				return;
			}

			if (
				!fs.existsSync(path.resolve(`../../${path.sep}${changedFile}`))
			) {
				return;
			}

			existingFiles.push(
				changedFile.replace(`themes${path.sep}catapult`, '.')
			);
		});
	} else {
		console.log('No changed PHP files found.');
	}

	existingFiles.push('./style.css');
	existingFiles.push('./package.json');

	gulp.src(existingFiles)
		.pipe(
			replace(/(?:^.*@since.*\n?)+/gm, function handleReplace(match) {
				const lineStart = match.split('@since')[0] ?? ' * ';
				const whitespaceAfterSince = (match.match(/(?<=@since)\s*/) || [
					'',
				])[0];

				const existingVersionLines = match
					.split('\n')
					.reduce((acc, line) => {
						const versionMatch = line.match(
							/([0-9]+\.[0-9]+\.[0-9]+)/
						);

						if (versionMatch) {
							const version = versionMatch[1];
							acc[version] = line;
						}
						return acc;
					}, {});

				const filePath = this.file.path;

				const fileChangelogData = getFileChangelogData(filePath);

				let versions = [
					...new Set(
						fileChangelogData.map(
							(item) => item.closestChangelogVersion
						)
					),
				];

				Object.keys(existingVersionLines).forEach((existingVersion) => {
					if (!versions.includes(existingVersion)) {
						versions.push(existingVersion);
					}
				});

				versions = versions.sort((a, b) =>
					a.localeCompare(b, undefined, { numeric: true })
				);

				let output = '';

				versions.forEach((version) => {
					if (existingVersionLines[version]) {
						output += `${existingVersionLines[version]}\n`;
					} else {
						output += `${lineStart}@since${whitespaceAfterSince}${version}\n`;
					}
				});

				return output;
			})
		)
		.pipe(
			replace(
				/(Version:|"version":\s*").*/gm,
				function handleReplace(match) {
					const filePath = this.file.path;

					if (!changelogData) {
						changelogData = getChangelogData(filePath);
					}

					return match.replace(
						/[0-9]+(\.[0-9]+)*/gm,
						changelogData[0].version
					);
				}
			)
		)
		.pipe(
			through2.obj(function (chunk, enc, callback) {
				chunk.stat = {
					mtime: new Date(),
				};

				this.push(chunk);
				callback();
			})
		)
		.pipe(
			gulp.dest(function (file) {
				return `${file.dirname}${path.sep}`;
			})
		)
		.on('finish', done);
});
