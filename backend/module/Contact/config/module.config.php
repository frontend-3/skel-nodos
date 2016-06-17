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
            'contact'=>array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/contactenos',
                    'defaults' => array(
                        'controller' => 'Contact_Controller',
                        'action' => 'index',
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
        'factories' => array(
            'ServiceContactCategoryTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Contact\Model\WebContactCategoryTable($dbAdapter);
            },
            'ServiceContactTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Contact\Model\WebContactTable($dbAdapter);
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
            'Contact_Controller' => 'Contact\Controller\ContactController',
            'Contact_Category_Load_Controller' => 'Contact\Controller\LoadController'
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
                'category_contact_load' => array(
                    'options' => array(
                        'route' => 'category_contact_load',
                        'defaults' => array(
                            'controller' => 'Contact_Category_Load_Controller',
                            'action' => 'loadCategory'
                        )
                    )
                ),
            ),
        ),
    ),
);
