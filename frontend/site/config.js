var config = {
  deploy_routes : function () {
    var routes = {},
        base,
        static_path;
    if (this.env === 'prod') {
      base = '../../backend/';
      static_path =  base + 'public/site/';
      routes = {
        base: '',
        templates: base + 'module/',
        static: static_path,
        styles: static_path + 'styles/',
        images: static_path + 'images/',
        fonts: static_path + 'fonts/',
        scripts: static_path + 'scripts/',
        sprites: static_path + 'sprites/'
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
        sprites: static_path + '/sprites'
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
  setEnv : function (env) {
    this.env = env;
  },
  getEnv: function () {
    return this.env;
  }
};

module.exports = config;
