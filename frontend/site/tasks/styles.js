module.exports = function(gulp) {
  var plugins;

  plugins = {
    stylus  : require('gulp-stylus'),
    cssmin  : require('gulp-cssmin'),
    notify  : require('gulp-notify'),
    nib     : require('nib'),
    rename  : require('gulp-rename'),
  };

  function stylesTask(src, config) {
    return gulp.src(src, config)
      .pipe(plugins.stylus({
        use     : plugins.nib(),
        compress: true
      })
      .on("error",plugins.notify.onError(function (error) {
        return "Error Stylus " + error.message;
      })))
      .pipe(plugins.cssmin({
        keepSpecialComments: false,
      })
      .on("error",plugins.notify.onError(function (error) {
        return "Error cssmin " + error.message;
      })))
      .pipe(gulp.dest(gulp.config.deploy_routes().styles))
      .pipe(plugins.notify(gulp.config.notifyConfig('Stylus compiled')));
  }

  gulp.task('styles', function() {
    return stylesTask('main.styl', {cwd : 'static/styles'});
  });

  gulp.task('styles:sprites', function() {
    return gulp.src([
      '**.css',
      ], {
        cwd : gulp.config.deploy_routes().sprites
      })
      .pipe(plugins.stylus({
        css: true
      })
      .pipe(plugins.rename(function (path){
        path.extname = ".sprite.styl"
      }))
      .on("error",plugins.notify.onError(function (error) {
        return "Error stylus sprite " + error.message;
      })))
      .pipe(gulp.dest("static/styles/modules"))
      .pipe(plugins.notify(gulp.config.notifyConfig('Stylus sprite generated')));
  });

  gulp.config.stylesTask = stylesTask;

}
