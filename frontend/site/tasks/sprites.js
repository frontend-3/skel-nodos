module.exports = function(gulp) {
  var plugins,
      argv;

  argv = require('yargs').argv;
  plugins = {
    glue  : require('gulp-sprite-glue'),
    runSequence: require('run-sequence'),
    notify  : require('gulp-notify')  
  };

  gulp.task('glue', function() {
    return gulp.src('static/sprites')
      .pipe(plugins.glue({
        css: gulp.config.deploy_routes().sprites,
        namespace: "sp",
        img: gulp.config.deploy_routes().sprites,
        url:"../sprites/",
        margin: 10,
        recursive: true,
        project: true,
        quiet: true,
      }))
      .pipe(plugins.notify(gulp.config.notifyConfig('Generated sprites')));
  });

  gulp.task('sprites', function () {
    plugins.runSequence('glue', 'styles:sprites');
  });
}
