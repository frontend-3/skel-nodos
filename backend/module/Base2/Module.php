<?php
namespace Base2;

use Locale;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleUsageProviderInterface {
    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('America/Lima');
        Locale::setDefault('es_PE');
        
        // Set settings for Sessions
        $config = $e->getApplication()->getServiceManager()->get('Config');
        
        if (!isset($config['session_config'])) {
            throw new \RuntimeException('Configuration is missing a "session_config" key, or the value of that key is not an array');
        }
        
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config['session_config']);
        
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->getValidatorChain()->attach('session.validate', array(new HttpUserAgent(), 'isValid'));
        $sessionManager->getValidatorChain()->attach('session.validate', array(new RemoteAddr(), 'isValid'));
        Container::setDefaultManager($sessionManager);
        
        // Attach RENDER event to add security headers
        $em = $e->getApplication()->getEventManager();
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'onRender'), 100);
    }
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'Base2'     => __DIR__ . '/src',
                    'Base2Test' => __DIR__ . '/test/src',
                ),
            ),
        );
    }
    
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function onRender(MvcEvent $e)
    {
        $response = $e->getResponse();
        
        if ($response instanceOf \Zend\Http\Response) {
          $response->getHeaders()
              ->addHeaderLine('X-Frame-Options: DENY')
              ->addHeaderLine('X-XSS-Protection:1; mode=block')
              ->addHeaderLine('X-Permitted-Cross-Domain-Policies: master-only')
              ->addHeaderLine('X-Content-Type-Options: nosniff')
              ->addHeaderLine('X-Content-Security-Policy: allow \'self\'');
        }
    }
    
    
    public function getConsoleUsage(Console $console)
    {
        return [
            'setenv (production|development|status)' => 'Set Application Environment to "production", "development" or show current status.',
            'console' => 'Drops you into a psysh console. Nifty.',
            'mysql' => 'Drops you into a MySQL console using the application\'s credentials.',
        ];
    }
}

