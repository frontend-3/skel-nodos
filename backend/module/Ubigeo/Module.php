<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ubigeo;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    
    public function getServiceConfig()
    {
        return array(
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
                    'Ubigeo' => __DIR__ . '/src/' . 'Ubigeo',
                    'Base' => dirname(__DIR__) . '/Base',
                ),
            ),
        );
    }
}

