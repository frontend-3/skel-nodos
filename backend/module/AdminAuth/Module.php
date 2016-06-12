<?php

namespace AdminAuth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Base' => dirname(__DIR__) . '/Base',
                    'AdminAuth' => __DIR__ . '/src/' . 'AdminAuth',
                ),
            ),
        );
    }

}
