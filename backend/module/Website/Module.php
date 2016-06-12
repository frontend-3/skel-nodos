<?php

namespace Website;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator'); 
        
        foreach(array('Zend_Captcha', 'Zend_Validate') as $f) {
            $translator->addTranslationFile(
                'phpArray',
                'vendor/zendframework/zendframework/resources/languages/es/' . $f . '.php',
                'default',
                'es_ES'
            );
        }
        
        // Set es_ES as the forced default locale
        \Locale::setDefault('es');
        $translator->setLocale('es_ES')->setFallbackLocale('es_ES');
        
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
    
    }
    
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }


    public function getAutoloaderConfig() {

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Website' => __DIR__ . '/src/' . 'Website',
                ),
            ),
        );
    }

}
