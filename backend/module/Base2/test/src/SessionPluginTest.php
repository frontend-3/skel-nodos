<?php
namespace Base2Test;

use PHPUnit_Framework_TestCase;

use Base2\SessionPlugin;

class SessionPluginTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $session = new SessionPlugin();
        $session->set('a', 123);
        
        $this->assertTrue($session->has('a'));
        $this->assertFalse($session->has('b'));
        
        $this->assertEquals(123, $session->get('a'));
    }
    
    
    public function testGetInvalid()
    {
        $this->setExpectedException("OutofRangeexception");
        
        $session = new SessionPlugin();
        $session->get('nonexistent');
    }
}

