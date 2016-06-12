<?php

namespace Site;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $config = $e->getApplication()->getServiceManager()->get('config');
        
        if(isset($config['site'])) {
            if($config['site']['debug'] == false) {
                $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $event) {
                    $url = $event->getRouter()->assemble(array('action' => 'index'), array('name' => 'error404'));
                    $response = $event->getResponse();
                    $response->getHeaders()->addHeaderLine('Location', $url);
                    $response->setStatusCode(302);
                    $response->sendHeaders();
                }, -200);
            }
        }
    }
    
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getAutoloaderConfig() {
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
