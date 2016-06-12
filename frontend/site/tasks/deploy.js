module.exports = function(grunt) {
  
  // Si el enviroment es dev(desarrollo) el task deploy ejecuta las tareas:
  // templates, styles, sprites
  if (grunt.option('dev')) {
    grunt.registerTask('deploy', 'Deployando el proyecto en desarrollo', function() {
      grunt.task.run(['templates', 'styles', 'sprites']);
    });
  } else {
    // Si el enviroment no esta especificado se asume que se trabaja en prod(producción) 
    // el task deploy ejecuta las tareas: templates, htmlmin, styles, cssmin, sprites y 
    // copy_assets y scripts.
    grunt.registerTask('deploy', 'Deployando en proyecto en produccion', function() {
      grunt.task.run([
        'templates',
        'htmlmin',
        'styles:compile',
        'cssmin:minify',
        'sprites',
        'copy_assets',
        'scripts'
      ]);
    });
  }

};