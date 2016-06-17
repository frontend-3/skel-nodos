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
