<?php

return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin_Controller_Auth',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'seo' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/seo',
                            'defaults' => array(
                                'controller' => 'admin_seo_controller',
                                'action' => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'view' => array(
                                'type' => 'Segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/view/:id',
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin_seo_controller',
                                        'action' => 'view',
                                    ),
                                ),
                            ),
                            'add' => array(
                                'type' => 'Literal',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/add',
                                    'defaults' => array(
                                        'controller' => 'admin_seo_controller',
                                        'action' => 'add',
                                        'id' => '0',
                                    ),
                                ),
                            ),
                            'update' => array(
                                'may_terminate' => true,
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/update/:id',
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin_seo_controller',
                                        'action' => 'update',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'segment',
                                'may_terminate' => true,
                                'options' => array(
                                    'route' => '/delete/:id',
                                    'constraints' => array(
                                        'id' => '[0-9]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin_seo_controller',
                                        'action' => 'delete',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ), 
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
           'admin_seo_controller' => 'Seo\Controller\AdminSeoController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'seo_load' => array(
                    'options' => array(
                        'route' => 'load_seo',
                        'defaults' => array(
                            'controller' => 'AdminSeoLoad_Controller',
                            'action' => 'index'
                        )
                    )
                ),
            ),
        ),
    ),
);
