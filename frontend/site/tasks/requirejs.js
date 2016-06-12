var plugins;

module.exports = function(gulp) {
  plugins = {
    notify  : require('gulp-notify'),
    requirejs : require('requirejs'),
    runSequence: require('run-sequence'),
  };

  gulp.task('requirejs', function() {
    plugins.requirejs.optimize({
      appDir:'static/scripts',
      mainConfigFile: "static/scripts/libs/require-config.js",
      baseUrl: '.',
      dir: gulp.config.deploy_routes().scripts,
      preserveLicenseComments: false,
      modules: [
        { name: 'site.validator' },
        { name: 'site.home' },
        { name: 'site.prehome' },
        { name: 'site.register' },
        { name: 'site.terms' },
        { name: 'site.thanks' }
      ]
    })
  });

  gulp.task('scripts', function () {
    plugins.runSequence('coffee', 'copy:js', 'requirejs')
  });

}
