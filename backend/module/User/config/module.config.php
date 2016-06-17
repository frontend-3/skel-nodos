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
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/iniciar-sesion[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cerrar-sesion[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'logout',
                    ),
                ),
            ),
            'register' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/registro[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'add',
                    ),
                ),
            ),
            'forgot_password' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/olvide-mi-contrasena[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'forgotPassword',
                    ),
                ),
            ),
            'forgot_password_thanks' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/olvide-mi-contrasena/gracias[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'forgotThanks',
                    ),
                ),
            ),
            'reset_password'=>array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/resetear-mi-contrasena/:token[/]',
                    'constraints' => array(
                        'token' => '(.*)',
                    ),
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'resetPassword',
                    ),
                ),
            ),
            'register_thanks' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/registro/gracias[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'thanks',
                    ),
                ),
            ),
            'activate' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/registro/gracias[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'activate',
                    ),
                ),
            ),

            'profile'=> array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/editar-perfil[/]',
                    'defaults' => array(
                        'controller' => 'User_Controller',
                        'action' => 'profile',
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
            'ServiceUserTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new User\Model\WebUserTable($dbAdapter);
            },
            'ServiceUserFacebookTable' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new User\Model\WebUserFacebookTable($dbAdapter);
            }
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
            'User_Controller' => 'User\Controller\UserController',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
