const runProcess = require('./runProcess');
runProcess('npm install');
runProcess('composer install');
runProcess('gulp plugins');
runProcess('gulp theme');
runProcess('gulp import');
runProcess('gulp homepage');
runProcess('gulp build');
