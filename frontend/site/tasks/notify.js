module.exports = function(grunt) {
  // Carga el paquete de dependecia
  grunt.loadNpmTasks('grunt-notify');

  grunt.config.set('notify', {
    watch: { // Notificación para tarea watch
      options: {
        title: 'Watch',
        message: 'Updated files are compiled'
      }
    },
    connect: { // Notificación para tarea connect
      options: {
        title: 'Conect',
        message: 'Static server is ready'
      }
    },
    sprites: { // Notificación para tarea sprites
      options: {
        title: 'Glue',
        message: 'Sprites generated'
      }
    },
    templates: { // Notificación para tarea templates
      options: {
        title: 'Templates',
        message: 'Jade compiled'
      }
    },    
    copy_assets: { // Notificación para tarea copy_assets
      options: {
        title: 'Copy',
        message: 'Images, sprites and fonts copied'
      }
    },
    scripts: { // Notificación para tarea scripts
      options: {
        title: 'Requirejs',
        message: 'Requirejs modules compiled'
      }
    },
    styles: { // Notificación para tarea styles
      options: {
        title: 'Css',
        message: 'Stylus compiled',
      }
    }
  });
};