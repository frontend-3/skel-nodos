<?php

if (APP_ENV == 'production') {
    error_reporting(0);
    ini_set('display_startup_errors', false);
    ini_set('display_errors', false);
    ini_set('html_errors', false);
} else {
    error_reporting(E_ALL | E_ALL | E_STRICT | E_NOTICE);
    ini_set('display_startup_errors', true);
    ini_set('display_errors', true);
    ini_set('html_errors', true);
}


$modules = array(
    'Base2',
    'DoctrineModule',
    'DoctrineORMModule',
    'Ubigeo2',
    'Florence2',
    
    'Site',
    //'AdminAuth',
    //'User',
    //'Policy',
    //'Contact',
    //'Social',
    //'Files',
    //'Florence',
    //'Ubigeo',
    'Website',
);

if (APP_ENV != 'production') {
    $modules = array_merge(['BaseTest'], $modules);
}

return array(
    // This should be an array of module namespaces used in the application.
    'modules' => $modules,
    
    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './module',
            './vendor',
        ),
        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        'config_cache_enabled' => (APP_ENV == 'production'),
        // The key used to create the configuration cache file name.
        'config_cache_key' => 'app_config',
        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        'module_map_cache_enabled' => false,
        // The key used to create the class map cache file name.
        'module_map_cache_key' => 'module_map',
        // The path in which to cache merged configuration.
        'cache_dir' => 'data/cache/',
        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other
        // modules that weren't loaded.
        'check_dependencies' => true,
    ),
);

