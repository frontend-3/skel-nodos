<?php
namespace ZfEloquent;

use Zend\Mvc\MvcEvent;
use Illuminate\Database\Capsule\Manager as Capsule;

class Module {
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        
        if (!isset($config['eloquent'])) {
            throw new \RuntimeException('Configuration is missing an "eloquent" key, or the value of that key is not an array');
        }
        
        $capsule = new Capsule;
        $capsule->addConnection($config['eloquent']);
        
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'ZfEloquent' => dirname(__DIR__) . '/ZfEloquent/src',
                ),
            ),
        );
    }
}


