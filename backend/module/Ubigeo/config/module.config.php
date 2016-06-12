<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'departments' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/departamentos',
                    'defaults' => array(
                        'controller' => 'Ubigeo_Controller',
                        'action' => 'getDepartments',
                    ),
                ),
            ),
            'provinces' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/provincias',
                    'defaults' => array(
                        'controller' => 'Ubigeo_Controller',
                        'action' => 'getProvinces',
                    ),
                ),
            ),
            'districts' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/distritos',
                    'defaults' => array(
                        'controller' => 'Ubigeo_Controller',
                        'action' => 'getDistricts',
                    ),
                ),
            ),
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(

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
        'factories' => array(
            'ServiceDepartments' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Ubigeo\Model\WebUbigeoDepartments($dbAdapter);
            },
            'ServiceProvinces' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Ubigeo\Model\WebUbigeoProvinces($dbAdapter);
            },
            'ServiceDistricts' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Ubigeo\Model\WebUbigeoDistricts($dbAdapter);
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Ubigeo_Controller' => 'Ubigeo\Controller\UbigeoController',
            'Ubigeo_Load_Controller' => 'Ubigeo\Controller\LoadController'
        ),
    ),
    'view_manager' => array(
        /*'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'Site_404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'Site_layout' => __DIR__ . '/../view/layout/layout.phtml',
            'Site_404' => __DIR__ . '/../view/error/404.phtml',
            'Site_error_index' => __DIR__ . '/../view/error/index.phtml',
        ),
        */
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'ubigeo_generate' => array(
                    'options' => array(
                        'route' => 'ubigeo_generate',
                        'defaults' => array(
                            'controller' => 'Ubigeo_Controller',
                            'action' => 'generate'
                        )
                    )
                ),
                'department_load' => array(
                    'options' => array(
                        'route' => 'department_load',
                        'defaults' => array(
                            'controller' => 'Ubigeo_Load_Controller',
                            'action' => 'departmentLoad'
                        )
                    )
                ),
                'province_load' => array(
                    'options' => array(
                        'route' => 'province_load',
                        'defaults' => array(
                            'controller' => 'Ubigeo_Load_Controller',
                            'action' => 'provinceLoad'
                        )
                    )
                ),
                'district_load' => array(
                    'options' => array(
                        'route' => 'district_load',
                        'defaults' => array(
                            'controller' => 'Ubigeo_Load_Controller',
                            'action' => 'districLoad'
                        )
                    )
                ),
            ),
        ),
    ),
);
