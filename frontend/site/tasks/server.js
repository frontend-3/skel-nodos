module.exports = function(gulp) {
  gulp.task('server', function () {
    var express = require('express');
    var app = express();

    app.use('/static', express.static(gulp.config.deploy_routes().static));

    require('http')
      .createServer(app)
      .listen(8000)
      .on('listening', function () {
            console.log('Started connect web server on http://localhost:8000');
      })
  });
}
