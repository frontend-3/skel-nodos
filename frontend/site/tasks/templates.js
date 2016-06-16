module.exports = function(gulp) {
  var plugins;
  var argv;

  argv = require('yargs').argv;
  plugins = {
    pug     : require('gulp-pug'),
    htmlmin : require('gulp-htmlmin'),
    rename  : require('gulp-rename'),
    notify  : require('gulp-notify'),
    gulpIf  : require('gulp-if')
  };
  pretty = argv.format ? true : false;

  function pugTask(src, options) {
    return gulp.src(src, options)
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
        var namespace = (path.dirname).charAt(0).toLowerCase() + (path.dirname).slice(1);
        path.dirname = path.dirname.replace(/^([^\/]*)/, '$1/view/' + namespace);
        path.extname = gulp.config.settings.template_ext;
      }))
      .on("error",plugins.notify.onError(function (error) {
        return "Error on change extension: " + error.message;
      }))
      .pipe(plugins.gulpIf(!pretty, plugins.htmlmin({
        removeComments    : true,
        collapseWhitespace: true,
        minifyJS          : true
      })
      .on("error",plugins.notify.onError(function (error) {
        return "Error htmlmin: " + error.message;
      }))))
      .pipe(gulp.dest(gulp.config.deploy_routes().templates))
      .pipe(plugins.notify(gulp.config.notifyConfig('Jade compiled')));
  }

  gulp.task('templates', function() {
    return pugTask([
        '*.pug',
        '**/*.pug',
        '!_layout.pug',
        '!**/_layout.pug',
        '!includes/**/*.pug',
        '!mixins/**/*.pug',
        '!_*.pug'
      ], {
        cwd : 'templates/modules'
      });
  });

  gulp.config.pugTask = pugTask;
}
