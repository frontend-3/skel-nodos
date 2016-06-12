<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'website_controller',
                        'action' => 'index',
                    ),
                ),
            ),
        ),   
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
        'factories' => array(
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'zend\cache\service\storagecacheabstractservicefactory',
            'zend\log\loggerabstractservicefactory',
        ),
        'aliases' => array(
            'translator' => 'mvctranslator',
        ),
        'factories'=>array(
        /*
            'ServiceMODEL' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new MODULE\Model\MODEL($dbAdapter);
            },
        */
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'website_controller' => 'Website\Controller\WebsiteController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'Site_404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);

