const runProcess = require('./runProcess');
const gulp = require('gulp');

const runReactBlocksBuild = (args, done) => {
	runProcess('npm run build-react-blocks');

	done();
};

gulp.task('react-blocks', (done) => {
	gulp.series(runReactBlocksBuild)(done);
});
