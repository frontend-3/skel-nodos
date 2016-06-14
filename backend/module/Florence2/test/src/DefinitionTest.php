<?php
namespace Florence2\Tests;

use PHPUnit_Framework_TestCase;
use BaseTest\ServiceManagerGrabber;

use Florence2\Definition;


class DefinitionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $sm = new ServiceManagerGrabber();
        $this->serviceManager = $sm->getServiceManager();
    }
    
    
    public function testGet()
    {
        $data = [
            'foo' => 'bar',
        ];
        
        $d = new Definition('bongo', $data, $this->serviceManager);
        $this->assertEquals('bar', $d->get('foo'));
        
        $this->setExpectedException('OutOfRangeException');
        $this->assertNull($d->get('nonexistent'));
    }
    
    
    public function testSet()
    {
        $data = [
            'foo' => 'bar',
        ];
        
        $d = new Definition('bongo', $data, $this->serviceManager);
        $d->set('foo', 'men');
        $this->assertEquals('men', $d->get('foo'));
    }
    
    
    public function testMerge()
    {
        $data = [
            'foo'     => 'bar',
            'one'     => 'two',
            'several' => [
                'a' => 1,
                'b' => 2,
            ]
        ];
        
        $d = new Definition('bongo', $data, $this->serviceManager);
        $d->merge([
            'foo' => 'Kylo Ren',
            'several' => [
               'b' => 1.5,
               'c' => 3,
            ],
        ]);
        
        $final = [
            'foo'     => 'Kylo Ren',
            'one'     => 'two',
            'several' => [
                'a' => 1,
                'b' => 1.5,
                'c' => 3,
            ],
        ];
        
        $this->assertEquals($final['foo'], $d->get('foo'));
        //$this->assertEquals($final['one'], $d->get('one'));
        //$this->assertEquals($final['several'], $d->get('several'));
    }
    
    
    public function testCreate()
    {
        $data = [
            'foo'     => 'bar',
            'element' => 'Select',
        ];
        
        $d = new Definition('bongo', $data, $this->serviceManager);
    }
    
    
    public function testInferElementClass()
    {
        $d = new Definition('bongo', [], $this->serviceManager);
        
        $name = $d->inferElementClass('Select');
        $this->assertEquals('\Zend\Form\Element\Select', $name);
        
        $name = $d->inferElementClass('Foo\Bar\Ele');
        $this->assertEquals('Foo\Bar\Ele', $name);
    }
    
    
    public function testInitElement()
    {
        $data = [
            'element' => 'Select',
        ];
        $d = new Definition('bongo', $data, $this->serviceManager);
        $d->initElement();
        
        $this->assertEquals('\Zend\Form\Element\Select', $d->get('elementClassName'));
    }
    
    
    public function testLoadConfigurators()
    {
        // TODO
    }
    
    
    public function testGetConfiguratorsFor()
    {
        // TODO
    }
    
    
    public function testCallConfigurator()
    {
        // TODO
    }
    
    
    public function testConfigureElement()
    {
        $data = [
            'element' => 'Text',
            'actsAs'  => 'telephone',
        ];
        $d = new Definition('first_name', $data, $this->serviceManager);
        $d->configureElement();
    }
    
    
    /*
    public function testGetElement()
    {
        $d = new Definition('department', [
           'element' => 'Select',
        ]);
        $d->init();
        $element = $d->element();
        $this->assertInstanceOf('\Zend\Form\Element\Select', $element);
        
        $d = new Definition('department', [
           'element' => 'Zend\Form\Element\Text',
        ]);
        $d->init();
        $element = $d->element();
        $this->assertInstanceOf('\Zend\Form\Element\Text', $element);
        
        // Instantiate a null class
        $this->setExpectedException('RuntimeException');
        $d = new Definition('department', []);
        $d->init();
        $element = $d->element();
        
        // Instantiate a non-Element class
        $this->setExpectedException('RuntimeException');
        $d = new Definition('department', [
           'element' => 'Zend\Validator\Csrf',
        ]);
        $d->init();
        $element = $d->element();
    }
    
    
    public function testInitElement()
    {
        $d = new Definition('department', [
            'element'  => 'Text',
            'label'    => 'department_label',
            'attributes' => [
                'id'       => 'department_id',
                'class'    => 'department_class',
                'required' => true,
            ],
            'value'    => 'Michelle',
        ]);
        $e = $d->element();
        
        $this->assertEquals('department', $e->getName());
        $this->assertEquals('department_label', $e->getLabel());
        $this->assertEquals('Michelle', $e->getValue());
        $this->assertEquals('department_id', $e->getAttribute('id'));
        $this->assertEquals('department_class', $e->getAttribute('class'));
        $this->assertEquals(true, $e->getAttribute('required'));
        
        $d = new Definition('first_name', [
            'element'  => 'Text',
            'value'    => 'Joseph',
        ]);
        $e = $d->element();
        
        $this->assertEquals('first_name', $e->getName());
        $this->assertEquals(null, $e->getLabel());
        $this->assertEquals('Joseph', $e->getValue());
        $this->assertEquals(null, $e->getAttribute('id'));
        $this->assertEquals(null, $e->getAttribute('class'));
    }
    
    
    public function testGetInput()
    {
        $d = new Definition('department', [
           'element' => 'Zend\Form\Element\Select',
        ]);
        $d->init();
        $input = $d->input();
        $this->assertInstanceOf('Zend\InputFilter\InputInterface', $input);
        
        $d = new Definition('department', [
           'element' => 'Zend\Form\Element\Select',
           'validators' => [
               'IsFloat' => [],
           ],
           'filters' => [
               'Alpha' => [],
           ],
        ]);
        $d->init();
        $input = $d->input();
        $this->assertInstanceOf('Zend\InputFilter\InputInterface', $input);
    }
    
    
    public function testHasElement()
    {
        $d = new Definition('department', [
           'element' => 'Zend\Form\Element\Select',
        ]);
        $d->init();
        
        $hasElement = $d->hasElement();
        
        $this->assertTrue($hasElement);
    
        $d = new Definition('department', []);
        $d->init();
        
        $hasElement = $d->hasElement();
        $this->assertFalse($hasElement);
    
    }
    
    
    public function testHasInput()
    {
        $d = new Definition('department', []);
        $d->init();
        
        $hasInput = $d->hasInput();
        $this->assertTrue($hasInput);
    
        $d = new Definition('department', [
            'noInput' => true,
        ]);
        $d->init();
        
        $hasInput = $d->hasInput();
        $this->assertFalse($hasInput);
    }
    */
}

