module.exports = function(gulp) {
  var path_os = require('path');

  gulp.task('watch', function() {
    gulp.watch('static/styles/**/*.styl', function(event) {
      var filepath = event.path;
      var relative_path = event.path.replace(gulp.config.dirname + "/static/styles/", "");

      if (
        relative_path.indexOf('modules' + path_os.sep) === -1 &&
        relative_path.indexOf('sections' + path_os.sep) === -1 &&
        relative_path.indexOf('libs' + path_os.sep) === -1
      ) {
        relative_path = event.path.replace(gulp.config.dirname + path_os.sep, "");
        gulp.config.stylesTask(relative_path, { base: 'templates/modules' });
      } else {
        gulp.config.stylesTask( "main.styl", {
          cwd : 'static/styles'
        });
      }
    });

    gulp.watch('templates/**/*.pug', function(event) {
      var filepath = event.path;
      var relative_path = event.path.replace(gulp.config.dirname + "/templates", "");
      var name = filepath.split('/').pop();

      if (/_(.*).pug/.test(name)) {
        gulp.config.pugTask([
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
      } else {
        relative_path = event.path.replace(gulp.config.dirname + path_os.sep, "");
        gulp.config.pugTask(relative_path, { base: 'templates/modules' });
      }
    });

  });
}
