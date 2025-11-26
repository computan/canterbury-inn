const gulp = require('gulp');
const wpcli = require('./wpcli');
const fs = require('fs');
const path = require('path');
const WP = require('wp-cli');

gulp.task('import', (done) => {
	try {
		if (
			!fs.existsSync(
				path.resolve(__dirname, `../../../exports/export.xml`)
			) ||
			!fs.existsSync(
				path.resolve(__dirname, `../../../exports/block-library-export.xml`)
			)
		) {
			console.log('Export files not found');
			return done();
		}

		wpcli(`import ../../exports/export.xml --authors=create`);
		wpcli(`import ../../exports/block-library-export.xml --authors=create`);
		wpcli(`option delete catapult_installed`);
		wpcli(`option add catapult_installed 1 --autoload=no`);
		wpcli('search-replace "http://catapult.local" "$(wp option get siteurl)" --precise --skip-columns=guid');
		wpcli('search-replace "https://catapult.local" "$(wp option get siteurl)" --precise --skip-columns=guid');

		done();
	} catch (err) {
		console.error('Import task failed:', err.message);
		done(err);
	}
});


gulp.task('plugins', (done) => {
	wpcli('plugin update --all');
	wpcli('plugin activate', [
		'advanced-custom-fields-pro',
		'safe-svg',
		'wordpress-importer',
		'wordpress-seo',
	]);
	done();
});

gulp.task('theme', (done) => {
	wpcli('theme activate catapult');
	done();
});

gulp.task('homepage', (done) => {
	wpcli('option update show_on_front "page"');
	wpcli('option update page_on_front 829');
	wpcli('option update permalink_structure "/%postname%/"');
	wpcli('rewrite flush');
	wpcli('cache flush');
	done();
});
