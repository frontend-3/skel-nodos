<?php
namespace Ubigeo2Tests;

use PHPUnit_Framework_TestCase;
use BaseTest\ServiceManagerGrabber;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class Ubigeo2ControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(include(__DIR__ . '/../../../../config/application.config.php'));
        parent::setUp();
    }
    
    
    public function testNormalRequestsReturn404()
    {
        $this->dispatch('/departments');
        $this->assertResponseStatusCode(404);
        
        $this->dispatch('/provinces');
        $this->assertResponseStatusCode(404);
        
        $this->dispatch('/districts');
        $this->assertResponseStatusCode(404);
    }
    
    
    public function testDepartments()
    {
        foreach (['GET', 'POST'] as $method) {
            $this->dispatch('/departments', $method, [], true);
            $this->assertResponseStatusCode(200);
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            
            // Try to parse the JSON
            $response = $this->getResponse()->getContent();
            $array = json_decode($response, true);
            
            $this->assertInternalType('array', $array);
            
            // Is not empty
            $this->assertGreaterThan(0, count($array));
        }
    }
    
    
    public function testProvinces()
    {
        foreach (['GET', 'POST'] as $method) {
            $this->dispatch('/provinces', $method, ['departmentID' => 78], true);
            $this->assertResponseStatusCode(200);
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            
            // Try to parse the JSON
            $response = $this->getResponse()->getContent();
            $array = json_decode($response, true);
            
            $this->assertInternalType('array', $array);
            
            // Is not empty
            $this->assertGreaterThan(0, count($array));
        }
        
        // Test get empty JSON
        foreach (['GET', 'POST'] as $method) {
            $this->dispatch('/provinces', $method, ['departmentID' => 1111], true);
            $this->assertResponseStatusCode(200);
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            
            // Try to parse the JSON
            $response = $this->getResponse()->getContent();
            $array = json_decode($response, true);
            
            $this->assertInternalType('array', $array);
            
            // And... it's empty?
            $this->assertEquals(0, count($array));
        }
        
        // Test invalid non-numeric parameter
        foreach (['GET', 'POST'] as $method) {
            $this->dispatch('/provinces', $method, ['departmentID' => 'a'], true);
            $this->assertResponseStatusCode(404);
        }
    }
    
    
    public function testDistricts()
    {
        foreach (['GET', 'POST'] as $method) {
            $params = [
                'departmentID' => 78,
                'provinceID'   => 324,
            ];
            $this->dispatch('/districts', $method, $params, true);
            $this->assertResponseStatusCode(200);
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            
            // Try to parse the JSON
            $response = $this->getResponse()->getContent();
            $array = json_decode($response, true);
            
            $this->assertInternalType('array', $array);
            
            // Is not empty
            $this->assertGreaterThan(0, count($array));
        }
        
        // Test get empty JSON
        foreach (['GET', 'POST'] as $method) {
            $params = [
                'departmentID' => 1111,
                'provinceID'   => 9999,
            ];
            $this->dispatch('/districts', $method, $params, true);
            $this->assertResponseStatusCode(200);
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            
            // Try to parse the JSON
            $response = $this->getResponse()->getContent();
            $array = json_decode($response, true);
            
            $this->assertInternalType('array', $array);
            
            // And... it's empty?
            $this->assertEquals(0, count($array));
        }
        
        // Test invalid non-numeric parameter
        foreach (['GET', 'POST'] as $method) {
            $params = [
                'departmentID' => 'a',
                'provinceID'   => 'b',
            ];
            $this->dispatch('/districts', $method, $params, true);
            $this->assertResponseStatusCode(404);
        }
    }
}

