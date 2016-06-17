<?php
namespace Ubigeo2;

return array(
    'router' => array(
        'routes' => array(
            'departments' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/departments',
                    'defaults' => array(
                        'controller' => 'Ubigeo2Controller',
                        'action' => 'departments',
                    ),
                ),
            ),
            'provinces' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/provinces',
                    'defaults' => array(
                        'controller' => 'Ubigeo2Controller',
                        'action' => 'provinces',
                    ),
                ),
            ),
            'districts' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/districts',
                    'defaults' => array(
                        'controller' => 'Ubigeo2Controller',
                        'action' => 'districts',
                    ),
                ),
            ),
        ),
    ),
    
    'controllers' => array(
        'invokables' => array(
            'Ubigeo2Controller' => 'Ubigeo2\Controller\Ubigeo2Controller',
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);

