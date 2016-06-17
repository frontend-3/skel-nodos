<?php
namespace Base2\Test;

use PHPUnit_Framework_TestCase;
use BaseTest\ServiceManagerGrabber;
use Mockery as m;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Base2\Base2Controller;

class Base2ControllerTest extends AbstractHttpControllerTestCase
{
    use \BaseTest\Helpers;
    
    public $c;
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(include(__DIR__ . '/../../../../config/application.config.php'));
        parent::setUp();
    }
    
    
    public function tearDown()
    {
        m::close();
    }
    
    
    /**
     * Setup the show_backend_template config variable
     */
    public function setupShowBackendTemplate($status)
    {
        $sm = $this->getServiceManager();
        $sm->setAllowOverride(true);
        $sm->setService('Config', [
            'site' => [
                'show_backend_template' => $status,
            ],
        ]);
        $sm->setAllowOverride(false);
    }
    
    
    public function setupParamsPlugin($c)
    {
        $mockParams = m::mock('Zend\Mvc\Controller\Plugin\Params')
            ->shouldIgnoreMissing()
            ->shouldReceive('__invoke')->twice()
            ->andReturn('FooController', 'barAction')
            ->getMock();
        
        $pm = $c->getPluginManager();
        $pm->setAllowOverride(true);
        $pm->setService('params', $mockParams);
    }
    
    
    public function testShowBackendTemplate()
    {
        $c = new Base2Controller();
        
        $this->setupShowBackendTemplate(true);
        $c->setServiceLocator($this->getServiceManager());
        $this->assertTrue($c->showBackendTemplate());
        
        $this->setupShowBackendTemplate(false);
        $this->assertFalse($c->showBackendTemplate());
    }
    
    
    public function testGetControllerNameParts()
    {
        $c = new Base2Controller();
        $c->setServiceLocator($this->getServiceManager());
        $this->setupParamsPlugin($c);
        
        $a = $c->getControllerNameParts();
        
        $this->assertEquals('foo', $a['controller']);
        $this->assertEquals('barAction', $a['action']);
    }
    
    
    public function testGetViewTemplateNameWithParam()
    {
        $c = new Base2Controller();
        $c->setServiceLocator($this->getServiceManager());
        
        // As true
        $this->setupShowBackendTemplate(true);
        
        $name = $c->getViewTemplateName('foo/bar');
        $this->assertEquals('foo/_bar', $name);
        
        $name = $c->getViewTemplateName('foo/bar/baz');
        $this->assertEquals('foo/bar/_baz', $name);
        
        // As false
        $this->setupShowBackendTemplate(false);
        
        $name = $c->getViewTemplateName('foo/bar');
        $this->assertEquals('foo/bar', $name);
        
        $name = $c->getViewTemplateName('foo/bar/baz');
        $this->assertEquals('foo/bar/baz', $name);
        
    }
    
    
    public function testGetViewTemplateNameWithoutParam()
    {
        $c = new Base2Controller();
        $c->setServiceLocator($this->getServiceManager());
        
        // As true
        $this->setupShowBackendTemplate(true);
        
        $this->setupParamsPlugin($c);
        $name = $c->getViewTemplateName();
        $this->assertEquals('foo/_barAction', $name);
        
        // As false
        $this->setupShowBackendTemplate(false);
        
        $this->setupParamsPlugin($c);
        $name = $c->getViewTemplateName();
        $this->assertEquals('foo/barAction', $name);
    }
}

