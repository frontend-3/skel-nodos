<?php

namespace Site;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getServiceConfig()
    {
        return array (
            'abstract_factories' => array(
                'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                'Zend\Log\LoggerAbstractServiceFactory',
            ),
            'aliases' => array(
                'translator' => 'MvcTranslator',
            ),
            'factories' => array(
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
                /*
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
                */
            ),
        );
    }
    
    
    public function getViewHelperConfig()
    {
        return array(
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
        );
    }
    
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Base' => dirname(__DIR__) . '/Base',
                    'Site' => __DIR__ . '/src/' . 'Site',
                ),
            ),
        );
    }

}
