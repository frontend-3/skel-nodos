var extend,
    base,
    local,
    base,
    settings;

settings = {
  "root_deploy" : "build"
}

extend = require('util')._extend;
base = require('./base.js');
extended = extend(base, settings);
module.exports = extended;