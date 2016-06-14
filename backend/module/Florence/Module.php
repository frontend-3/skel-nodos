<?php

namespace Florence;

class Module {
    public function getAutoLoaderConfig() {
        return array(
           'Zend\Loader\StandardAutoloader' => array(
               'namespaces' => array(
                   __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
               )
           )
        );
    }
    
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
}

?>
