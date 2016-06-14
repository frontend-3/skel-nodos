<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'setEnvironment' => array(
                    'options' => array(
                        'route' => 'setenv (production|development|status):environment',
                        'defaults' => array(
                            'controller' => 'ConsoleController',
                            'action' => 'setEnvironment',
                        ),
                    ),
                ),
                'console' => array(
                    'options' => array(
                        'route' => 'console',
                        'defaults' => array(
                            'controller' => 'ConsoleController',
                            'action' => 'console',
                        ),
                    ),
                ),
                'mysql' => array(
                    'options' => array(
                        'route' => 'mysql',
                        'defaults' => array(
                            'controller' => 'ConsoleController',
                            'action' => 'mysql',
                        ),
                    ),
                ),
            ),
        ),   
    ),
    
    'controllers' => array(
        'invokables' => array(
            'ConsoleController'            => 'Base2\ConsoleController',
            'base2tests_controller_sample' => 'Base2Tests\ControllerSample',
        ),
    ),
    
    'controller_plugins' => array(
        'invokables' => array(
            'session' => 'Base2\SessionPlugin',
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'view_helpers' => array(
        'invokables' => array(
            'formError' => 'Base2\Helper\FormErrorHelper',
        ),
    ),
);

