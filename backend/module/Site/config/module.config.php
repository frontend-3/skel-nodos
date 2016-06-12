<?php

return array(
    'router' => array(
        'routes' => array(
            'error404' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/404',
                    'defaults' => array(
                        'controller' => 'Site_Controller',
                        'action' => 'error404',
                    ),
                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'showImg' => 'Site\Helper\ImageNoAvailableHelper',
            'showError' => 'Site\Helper\PrintErrorHelper',
            'toCamelCase' => 'Site\Helper\CamelCaseHelper'
        ),
        'factories' => array(
            'getImagePath' => function($sm){
                $sm = $sm->getServiceLocator();
                $helper = new Site\Helper\GetImageFullPath();
                $helper->setServiceLocator($sm);
                return $helper;
            }
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
        'factories'=>array(
            'ServiceOptionTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Site\Model\WebOptionsTable($dbAdapter);
            },
            'ServiceSectionTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new Site\Model\WebSectionsTable($dbAdapter);
            },
            'ServiceSiteTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new \Site\Model\WebSiteTable($dbAdapter);
            },
            'Zend\Log' => function ($sm) {
                $logger = new \Zend\Log\Logger;
                $writer = new \Zend\Log\Writer\Stream(ROOT_PATH . '/log.txt');
                $logger->addWriter($writer);
                \Zend\Log\Logger::registerErrorHandler($logger);
                \Zend\Log\Logger::registerExceptionHandler($logger);
                return $logger;
            },
            'Zend\Session\SessionManager' => function ($sm) {
                $config = $sm->get('config');
                if (isset($config['session'])) {
                    $session = $config['session'];
                    $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                    $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                    $sessionConfig = new $class();
                    $sessionConfig->setOptions($options);
                    $class = $session['storage'];
                    $sessionStorage = new $class();
                    $sessionManager = new SessionManager($sessionConfig, $sessionStorage);
                } else {
                    $sessionManager = new SessionManager();
                }
                Container::setDefaultManager($sessionManager);
                return $sessionManager;
            },
        )
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
            'Site_Controller' => 'Site\Controller\IndexController',
            'Site_Load_Controller'=> 'Site\Controller\commands\LoadSiteController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'Site_layout' => __DIR__ . '/../view/layout/layout.phtml',
            'Site_404' => __DIR__ . '/../view/site/404.phtml',
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'console' => array(
    ),
);
