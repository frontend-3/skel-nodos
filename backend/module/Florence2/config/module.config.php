<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'export' => array(
                    'options' => array(
                        'route' => 'florence2-export <format> <form>',
                        'defaults' => array(
                            'controller' => 'ConsoleController',
                            'action' => 'export',
                        ),
                    ),
                ),
            ),
        ),   
    ),
    
    'controllers' => array(
        'invokables' => array(
            'ConsoleController' => 'Florence2\ConsoleController',
        ),
    ),
    
    'florence2' => [
        'forms' => [
            'test' => __DIR__ . '/../test/src/test.yml',
        ],
    ],
);

