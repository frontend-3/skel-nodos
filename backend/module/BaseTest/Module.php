<?php
/**
 * This file is part of BaseTest Zend Framework 2 module.
 */

namespace BaseTest;

class Module {
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

