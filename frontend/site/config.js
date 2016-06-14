var config = {
  dirname: __dirname,
  deploy_routes : function () {
    var routes = {},
        base,
        static_path;
    if (this.env === 'prod') {
      base = '../../backend/';
      static_path =  base + 'public/site/';
      routes = {
        base: '',
        //templates: base + 'module/User/view/site/',
        templates: base + 'module/',
        static: static_path,
        styles: static_path + 'styles/',
        images: static_path + 'images/',
        fonts: static_path + 'fonts/',
        scripts: static_path + 'scripts/',
        sprites: static_path + 'sprites/',
        svg: static_path + 'svg/',
      }
    } else {
      base = 'build';
      static_path =  base + '/static';
      routes = {
        base : base,
        templates: base + '/templates/',
        static: 'static',
        styles: static_path + '/styles',
        images: static_path + '/images',
        fonts: static_path + '/fonts',
        scripts: static_path + '/scripts',
        sprites: static_path + '/sprites',
        svg: static_path + 'svg/',
      }
    }
    return routes;
  },
  static_url: function(url) {
    if (this.env == 'prod') {
      return '<?php echo $this->basePath().\'/site/' + url + '\';?>'
    }
    return this.settings.static_uri + '/' + url;
  },
  dev_static_url: function(url) {
    return 'static/' + url;
  },
  reverse_url: function() {
    return "";
  },
  setEnv : function (env) {
    this.env = env;
  },
  getEnv: function () {
    return this.env;
  },
  notifyConfig : function (options) {
    var _config = {}

    if (typeof options == "object") {
      return options
    }

    _config = {
      message : options,
      onLast  : true
    }

    return _config;
  },
  getSVG: function(file) {
    fs = require('fs');
    return fs.readFileSync(file, 'utf8')
  }
};

module.exports = config;
