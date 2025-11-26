const gulp = require('gulp');

require('./tasks/icons');
require('./tasks/styles');
require('./tasks/scripts');
require('./tasks/browsersync');
require('./tasks/acf-blocks');
require('./tasks/react-blocks');
require('./tasks/db');

gulp.task('default', gulp.series('icons', gulp.parallel('styles', 'scripts')));

gulp.task('build', gulp.series('default'));

gulp.task(
	'watch',
	gulp.parallel('styles:watch', 'scripts:watch', 'browsersync')
);
