<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ArrayUtils;
use Symfony\Component\Yaml\Yaml;
use Florence2\Fieldset as Florence2Fieldset;

/**
 * Florence
 * 
 * A facade to instantiate Form, InputFilter, Filter and Validator objects
 * using simple YAML files as definition and description.
 * 
 * @package Florence2
 * @version v2.0.0
 * @author Jaime G. Wong <j@jgwong.org>
 */
class Florence2
{
    /**
     * @var ServiceLocatorInterface Service Manager
     */
     private $serviceManager;
    
    /**
     * @var eventManagerInterface Event Manager
     */
    private $eventManager;
    
    
    /**
     * @var array Config
     */
    private $config;
    
    /**
     * @var array List of Florence2\Definition objects
     */
    private $definitions;
    
    /**
     * @var \Zend\Form\Form
     */
    private $form;
    
    /**
     * @var \Florence2\Fieldset
     */
    private $fieldset;
    
    /**
     * @var \Zend\InputFilter\InputFilter
     */
    private $inputFilter;
    
    /**
     * @var array Creators list
     */
    protected $creators = [];
    
    
    /**
     * Parses a YAML form definition file, then instantiates and configures a
     * Florence2 object.
     * 
     * @param string Full path to YAML file
     * @param ServiceLocatorInterface $serviceManager
     * @param array $config Form options and attributes
     * @return Florence2
     * @raise \RuntimeException
     * @since v1.0.0
     */
    static public function parse($form, ServiceLocatorInterface $serviceManager, $config = [])
    {
        $definitions = self::getFormDefinition($serviceManager, $form);
        
        $flr = new Florence2();
        
        $flr->serviceManager = $serviceManager;
        $flr->eventManager = $serviceManager->get('EventManager');
        $flr->eventManager->setIdentifiers('Florence2\Florence2');
        $flr->setupDefaultListeners();
        
        $flr->config = $flr->setupConfig($config);
        $flr->loadDefinitions($definitions);
        
        return $flr;
    }
    
    
    /**
     * Get Form definition
     *
     * @return Florence2
     * @since v2.0.0
     */
    static function getFormDefinition(ServiceLocatorInterface $serviceManager, $form)
    {
        $config = $serviceManager->get('Config');
        
        if (!array_key_exists('florence2', $config)) {
            throw new \OutOfRangeException('Config key "florence2" does not exist!');
        }
        
        if (!array_key_exists('forms', $config['florence2'])) {
            throw new \OutOfRangeException('Config key "florence2.forms" does not exist!');
        }
        
        if (!array_key_exists($form, $config['florence2']['forms'])) {
            throw new \OutOfRangeException('Form "' . $form . '" not found in config!');
        }
        
        $yamlFile = $config['florence2']['forms'][$form];
        
        if (!file_exists($yamlFile)) {
            throw new \OutOfRangeException('YAML file "' . $yamlFile . '" not found!');
        }
        
        return Yaml::parse(file_get_contents($yamlFile));
    }
    
    
    /**
     * Setup config
     * 
     * @param array $config
     * @return array
     * @since v1.0.0
     */
    public function setupConfig($config)
    {
        $defaults = [
            'options'    => [],
            'attributes' => [
                'autocomplete' => 'false',
                'method'       => 'POST',
            ],
        ];
        
        $config = array_merge($defaults, $config);
        
        return $config;
    }
    
    
    /**
     * Setup default event Listeners
     * 
     * @return void
     * @since v1.0.0
     */
    public function setupDefaultListeners()
    {
        $em = $this->eventManager;
        
        $em->attach('newElement', function ($e) {
            $flr        = $e->getTarget();
            $definition = $e->getParam('definition');
            echo 'I don\'t know what do here, but I guess it works.';
            var_dump($e);
        }, 100);
    }
    
    
    /**
     * Instantiates Definitions from an array of data
     * 
     * @param array $definitions
     * @return Florence2
     * @since v1.0.0
     */
    public function loadDefinitions($definitions)
    {
        $this->definitions = [];
        
        foreach ($definitions as $name => $definition) {
            $def = new Definition($name, $definition, $this->serviceManager);
            $def->init();
            $this->definitions[$name] = $def;
        }
        
        return $this;
    }
    
    
    /**
     * Get a Definition.
     * 
     * @param string $name
     * @return Definition|false
     * @since v1.0.0
     */
    public function get($name)
    {
        if (array_key_exists($name, $this->definitions)) {
            return $this->definitions[$name];
        } else {
            return false;
        }
    }
    
    
    /**
     * Instantiate or return existing Zend\Form\Form object.
     * 
     * @return Form
     * @since v1.0.0
     */
    public function getForm()
    {
        if (is_null($this->form)) {
            $form = new Form();
            
            $form->setOptions($this->config['options']);
            $form->setAttributes($this->config['attributes']);
            
            $this->loadElements($form);
            $this->loadInputFilter($form);
            
            $this->form = $form;
        }
        
        return $this->form;
    }
    
    
    /**
     * Instantiate or return existing Florence2\Fieldset object.
     * 
     * @return Fieldset
     * @since v1.1.0
     */
    public function getFieldset()
    {
        if (is_null($this->fieldset)) {
            $fieldset = new Florence2Fieldset();
            $this->loadElements($fieldset);
            $this->loadInputFilter($fieldset);
            
            $this->fieldset = $fieldset;
        }
        
        return $this->fieldset;
    }
    
    
    /**
     * Adds Form Elements from each Definition
     * 
     * @param Form|Fieldset $target Form or Fieldset instance
     * @return void
     * @since v1.0.0
     */
    public function loadElements($target)
    {
        foreach ($this->definitions as $definition) {
            $target->add($definition->getElement());
        }
    }
    
    
    /**
     * Instantiates an Input Filter from each Definition
     * 
     * @param Form|Fieldset $target Form or Fieldset
     * @return void
     * @throw \RuntimeException
     * @since v1.0.0
     */
    public function loadInputFilter($target)
    {
        if ($target instanceOf Form) {
            $inputFilter = new InputFilter();
            
            foreach ($this->definitions as $definition) {
                if ($definition->get('elementClassName') !== 'Zend\Form\Element\Collection') {
                    $inputFilter->add($definition->getInput());
                }
            }
            
            $target->setInputFilter($inputFilter);
            $this->inputFilter = $inputFilter;
            
        } elseif ($target instanceOf Fieldset) {
            $inputs = [];
            
            foreach ($this->definitions as $definition) {
                $inputs[] = $definition->getInput();
            }
            
            $target->setInputs($inputs);
            
        } else {
            throw new \RuntimeException("Form or Fieldset object expected, but received " . get_class($target));
        }
    }
    
    
    /**
     * Get instance of InputFilter.
     * 
     * @return InputFilterInterface
     * @since v1.0.0
     */
    public function getInputFilter()
    {
        if (!is_null($this->inputFilter)) {
            return $this->inputFilter;
        }
        else {
            $this->loadInputFilter();
            return $this->inputFilter;
        }
    }
    
    
    /**
     * Returns the Service Manager instance
     * 
     * @return ServiceLocatorInterface
     * @since v1.0.0
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}


