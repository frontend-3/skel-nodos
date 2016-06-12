module.exports = function(grunt) {
  try {
    /*
    * La variable config, es una variable global que se encuentra disponible
    * en todos los archivos que puedan accederla(jade, tareas grunt, etc)
    */
    var settings,
        config;

     config = require('./config.js');

    /*
    * Existen 2 ambientes de desarrollo Dev(Desarrollo) y Prod(Producción)
    * Por defecto se asume que todo proyecto trabaja en producción si se desea
    * cambiar esta configuración del ambiente al ejecutar la tarea elegida se debe 
    * agregar el parametro "--dev=true"
    * Ejemplo:
    * grunt templates --dev
    * grunt watch --dev    
    */

    if (grunt.option('dev')) {
      config.setEnv('dev');
      settings = require('./settings/dev');
      
    }
    else {
      config.setEnv('prod');
      settings = require('./settings/prod');
    }
  }
  catch (e) {    
    config = {};
  }
  config.settings = settings;
  grunt.config.set('config', config);
  // Carga todas las tareas existentes en la carpeta tasks
  grunt.loadTasks('./tasks'); 
};