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
                    'settings' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/settings',
                            'defaults' => array(
                                'controller' => 'admin_settings_controller',
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
                                        'controller' => 'admin_settings_controller',
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
                                        'controller' => 'admin_settings_controller',
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
                                        'controller' => 'admin_settings_controller',
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
                                        'controller' => 'admin_settings_controller',
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
            'admin_settings_controller' => 'Settings\Controller\AdminSettingsController',
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
    ),
);
