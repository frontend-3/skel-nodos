<?php
namespace Florence2Tests;

use PHPUnit_Framework_TestCase;
use BaseTest\ServiceManagerGrabber;
use Mockery as m;

use Florence2\Florence2;

class Florence2Test extends PHPUnit_Framework_TestCase
{
    use \BaseTest\Helpers;
    
    public function setupConfigForm()
    {
        $sm = $this->getServiceManager();
        $sm->setAllowOverride(true);
        
        $config = $sm->get('Config');
        $config['florence2']['forms'] = [
            'test' => __DIR__ . '/test.yml',
        ];
        $sm->setService('Config', $config);
        
        $sm->setAllowOverride(false);
    }
    
    
    public function testGetFormDefinitionNoFlorence2InConfig()
    {
        $this->setExpectedException('OutOfRangeException');
        $def = Florence2::getFormDefinition($this->getServiceManager(), 'nonexistent');
    }
    
    
    public function testGetFormDefinitionNoForm()
    {
        $this->setupConfigForm();
        
        $this->setExpectedException('OutOfRangeException');
        $def = Florence2::getFormDefinition($this->getServiceManager(), 'nonexistent');
    }
    
    
    public function testGetFormDefinition()
    {
        $this->setupConfigForm();
        $def = Florence2::getFormDefinition($this->getServiceManager(), 'test');
        
        $this->assertInternalType('array', $def);
    }
    
    
    /*
    public function getInstance()
    {
        return Florence2::parse(__DIR__ . '/test.yaml', $this->serviceManager);
    }
    
    
    public function testParse()
    {
        $flr = $this->getInstance();
        $this->assertInstanceOf('Florence2\Florence2', $flr);
    }
    
    
    public function testSetupOptions()
    {
        $flr = $this->getInstance();
        
        $options = $flr->setupOptions([]);
        $this->assertFalse($options['autocomplete']);
        
        $options = $flr->setupOptions([
            'autocomplete' => true,
            'vivalavidaloca' => ':thumbsdown:',
        ]);
        $this->assertTrue($options['autocomplete']);
        $this->assertEquals(':thumbsdown:', $options['vivalavidaloca']);
    }
    
    
    public function testGetDefinition()
    {
        $flr = $this->getInstance();
        
        $e = $flr->get('first_name');
        $this->assertInstanceOf('Florence2\Definition', $e);
        
        $e = $flr->get('nonexistent');
        $this->assertEquals(false, $e);
    }
    
    
    /*
    public function testGetForm()
    {
        $flr = $this->getInstance();
        
        $form = $flr->form();
        
        $this->assertInstanceOf('Zend\Form\FormInterface', $form);
    }
    
    
    public function testGetDefinitionsInputs()
    {
        $flr    = $this->getInstance();
        $inputs = $flr->getDefinitionsInputs();
        
        $this->assertInternalType('array', $inputs);
        $this->assertInstanceOf('Zend\InputFilter\InputInterface', $inputs[0]);
    }
    
    
    public function testGetInputFilter()
    {
        $flr = $this->getInstance();
        $if  = $flr->inputFilter();
        
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);
    }
    
    
    public function testFormWorks()
    {
        $flr = $this->getInstance();
        
        $data = [
            'first_name' => 'Michael',
            'age'        => 57,
            'accept'     => '1',
        ];
        
        $form = $flr->form();
        $form->setData($data);
        $this->assertTrue($form->isValid());
        
        $data = [
            'first_name' => 'Invalid 123',
            'age'        => 57,
            'accept'     => '1',
        ];
        
        $form = $flr->form();
        $form->setData($data);
        $this->assertFalse($form->isValid());
    }
    
    
    public function testGetConfigurator()
    {
        $flr = $this->getInstance();
        
        $c = $flr->getConfiguratorFor(\Zend\Form\Element\Checkbox::class);
        $this->assertEquals(\Florence2\Configurator\Element\Checkbox::class, $c);
    }
    
    
    public function testConfigureElement()
    {
        $flr = $this->getInstance();
        $definition = $flr->get('accept');
        $flr->configureElement($definition);
        
        $element = $definition->element();
        $this->assertTrue($element->useHiddenElement());
        $this->assertEquals('0', $element->getUncheckedValue());
        $this->assertEquals('1', $element->getCheckedValue());
    }
    
    
    public function testConfigureFilters()
    {
        $flr = $this->getInstance();
        $definition = $flr->get('first_name');
        $flr->configureFilters($definition);
        
        $input = $definition->input();
        $filters = $input->getFilterChain()->getFilters();
        
        foreach ($filters as $filter) {
            $this->assertTrue($filter->getAllowWhiteSpace());
        }
        
    }
    
    
    public function testConfigureValidators()
    {
        $flr = $this->getInstance();
        $definition = $flr->get('first_name');
        $flr->configureValidators($definition);
        
        $input = $definition->input();
        $validators = $input->getValidatorChain()->getValidators();
        
        foreach ($validators as $validatorData) {
            $validator = $validatorData['instance'];
            
            if ($validator instanceOf \Zend\Validator\StringLength) {
                $this->assertEquals(5, $validator->getMin());
                $this->assertEquals(10, $validator->getMax());
            }
        }
    }
    */
}

