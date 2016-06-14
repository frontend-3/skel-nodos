<?php
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use BaseTest\ServiceManagerGrabber; 
use Illuminate\Database\Capsule\Manager as Capsule;

date_default_timezone_set('America/Lima');

// Chroot to the root of the application. Easier to work this way.
$cwd = dirname(__DIR__);
chdir($cwd);
 
 // Load Composer's autoloader
$loader = require_once './vendor/autoload.php';
$loader->register();

define('APP_ENV', 'test');

// Load ZF2's autoloader
define('ROOT_PATH', $cwd);
require_once('init_autoloader.php');

// Create a Service Manager
$configuration = require_once './config/application.config.php';
$smConfig = (isset($configuration['service_manager']) ? $configuration['service_manager'] : []);
$serviceManager = new ServiceManager(new ServiceManagerConfig($smConfig));
$serviceManager->setService('ApplicationConfig', $configuration);
$serviceManager->get('ModuleManager')->loadModules();

ServiceManagerGrabber::setServiceManager($serviceManager);

// Bootstrap Eloquent
$config = $serviceManager->get('Config');
if (array_key_exists('eloquent', $config)) {
    $capsule = new Capsule;
    $capsule->addConnection($config['eloquent']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
}

// Unset this ServiceManager, otherwise PHPUnit will try to serialize it
unset($serviceManager);

