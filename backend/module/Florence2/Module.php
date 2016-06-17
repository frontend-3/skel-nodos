<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

class Module {
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getAutoLoaderConfig()
    {
        return array(
           'Zend\Loader\StandardAutoloader' => array(
               'namespaces' => array(
                   __NAMESPACE__ => __DIR__ . '/src',
               )
           )
        );
    }
}

