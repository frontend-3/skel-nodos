module.exports = function(gulp) {
  var plugins;

  plugins = {
    notify  : require('gulp-notify'),
    runSequence: require('run-sequence'),
  };

  gulp.task('copy:images', function() {
    return gulp.src([
        'static/images/**/*',
        ])
        .pipe(gulp.dest(gulp.config.deploy_routes().images))
        .pipe(plugins.notify(gulp.config.notifyConfig('Copy images')));
  });

  gulp.task('copy:fonts', function() {
    return gulp.src([
        'static/fonts/**/*',
        ])
        .pipe(gulp.dest(gulp.config.deploy_routes().fonts))
        .pipe(plugins.notify(gulp.config.notifyConfig('Copy fonts')));
  });

  gulp.task('copy', function () {
    plugins.runSequence('copy:fonts', 'copy:images');
  })
}
