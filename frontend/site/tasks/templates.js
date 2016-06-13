module.exports = function(gulp) {
  var plugins,
      argv;

  argv = require('yargs').argv;

  plugins = {
    pug     : require('gulp-pug'),
    htmlmin : require('gulp-htmlmin'),
    rename  : require('gulp-rename'),
    notify  : require('gulp-notify')
  };

  gulp.task('templates', function() {

  pretty = argv.format ? true : false;

  return gulp.src([
      '*.pug',
      '**/*.pug',
      '!_layout.pug',
      '!**/_layout.pug',
      '!includes/**/*.pug',
      '!mixins/**/*.pug',
      '!_*.pug'
      ], {
          cwd : 'templates/modules'
      })
      .pipe(plugins.pug({
        pretty: pretty,
        data: {
          config: gulp.config
        }
      })
      .on("error",plugins.notify.onError(function (error) {
        return "Error Jade " + error.message;
      })))
      .pipe(plugins.rename(function (path){
        path.dirname = path.dirname.replace(/^([^\/]*)/, '$1/view/site');
        path.extname = gulp.config.settings.template_ext
      }))
      .on("error",plugins.notify.onError(function (error) {
        return "Error on change extension: " + error.message;
      }))
      .pipe(plugins.htmlmin({
        removeComments    : true,
        collapseWhitespace: true,
        minifyJS          : true
      })
      .on("error",plugins.notify.onError(function (error) {
        return "Error htmlmin: " + error.message;
      })))
      .pipe(gulp.dest(gulp.config.deploy_routes().templates))
      .pipe(plugins.notify(gulp.config.notifyConfig('Jade compiled')));
  });
}