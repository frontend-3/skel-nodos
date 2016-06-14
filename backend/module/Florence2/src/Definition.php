<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\ElementInterface;
use Zend\Form\Factory as FormFactory;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\Input;
use Zend\Stdlib\ArrayUtils;
use Configurator\Definition\ConfiguratorInterface;

/**
 * Definition
 * 
 * @package Florence2
 * @version v1.1.2
 * @author Jaime G. Wong <j@jgwong.org>
 */
class Definition
{
    /**
     * @var string Name
     */
    protected $name;
    
    /**
     * @var ServiceLocatorInterface Service Manager
     */
    protected $serviceManager;
    
    /**
     * @var eventManagerInterface Event Manager
     */
    private $eventManager;
    
    /**
     * @var FormElementManager Form Element Manager
     */
    private $formElementManager;
    
    /**
     * @var array Definition array
     */
    protected $definition;
    
    /**
     * @var array Element Specification
     */
    protected $elementSpecification = [];
    
    /**
     * @var array Filters Specification
     */
    protected $filtersSpecification = [];
    
    /**
     * @var array Validators Specification
     */
    protected $validatorsSpecification = [];
    
    /**
     * @var ElementInterface Element
     */
    protected $element;
    
    /**
     * @var InputInterface Input
     */
    protected $input;
    
    
    /**
     * Constructor
     * 
     * @param string $name Name
     * @param array $definition Definition data values.
     * @param ServiceLocatorInterface $serviceManager Service Manager
     * @return Definition
     * @throw \RuntimeException
     * @since v1.0.0
     */
    public function __construct($name, $definition, ServiceLocatorInterface $serviceManager)
    {
        $defaultValues = [
            'type'              => null,  // Element type
            'elementClassName'  => null,  // Element class name
            
            'label'             => null,  // Element label
            'labelOptions'      => [],    // Element label options
            'labelAttributes'   => [],    // Element label attributes
            'value'             => null,  // Element initial value
            'elementAttributes' => [],    // Element attributes
            'elementOptions'    => [],    // Element options
            'filters'           => [],    // Input Validators
            'validators'        => [],    // Input Validators
            
            // Non-standard definitions
            'actsAs'            => null,  // "Acts as" definition modifier
            'id'                => null,  // Element HTML ID attribute
            'class'             => null,  // Element HTML class attribute
            'required'          => true,  // Element required attribute
        ];
        
        $this->name = $name;
        
        $this->serviceManager = $serviceManager;
        $this->eventManager = $this->serviceManager->get('EventManager');
        $this->getEventManager()->setIdentifiers('Florence2\Definition');
        $this->setupDefaultListeners();
        $this->formElementManager = new FormElementManager();
        
        $definition = array_merge($defaultValues, $definition);
        $this->setAll($definition);
        
        return $this;
    }
    
    
    /**
     * Initializes and configures the final definition data
     * 
     * @return Definition
     * @throw \RuntimeException
     * @since v1.0.0
     */
    public function init()
    {
        $this->configureDefinition();
        $this->configureElement();
        $this->configureFilters();
        $this->configureValidators();
        return $this;
    }
    
    
    /**
     * Return the name
     * 
     * @return string
     * @since v1.0.0
     */
    public function name()
    {
        return $this->name;
    }
    
    
    /**
     * Get Service Manager
     * 
     * @return ServiceLocatorInterface
     * @since v1.0.0
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
    
    
    /**
     * Set Service Manager
     * 
     * @param type $
     * @return Definition
     * @since v1.1.0
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
    
    
    /**
     * Get Event Manager
     * 
     * @return string
     * @since v1.0.0
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    
    /**
     * Set Event Manager
     * 
     * @param type $
     * @return Definition
     * @since v1.1.0
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }
    
    
    /**
     * Get Form Element Manager
     * 
     * @return FormElementManager
     * @since v1.1.0
     */
    public function getFormElementManager()
    {
        return $this->formElementManager;
    }
    
    
    /**
     * Set Form Element Manager
     * 
     * @param type $
     * @return Definition
     * @since v1.1.0
     */
    public function setFormElementManager($formElementManager)
    {
        $this->formElementManager = $formElementManager;
        return $this;
    }
    
    
    /**
     * Get a Definition value
     * 
     * @param string $index
     * @return mixed|null
     * @throw \OutOfRangeException
     * @since v1.0.0
     */
    public function get($index)
    {
        if (!$this->has($index)) {
            throw new \OutOfRangeException('Index "' . $index . '" not found');
        }
        
        return $this->definition[$index];
    }
    
    
    /**
     * Returns true if Definition value exists
     * 
     * @param string $index
     * @return boolean
     * @throw \OutOfRangeException
     * @since v1.1.0
     */
    public function has($index)
    {
        return array_key_exists($index, $this->definition);
    }
    
    
    /**
     * Get the Definition array
     * 
     * @return string
     * @since v1.0.0
     */
    public function getAll()
    {
        return $this->definition;
    }
    
    
    /**
     * Set the Definition array
     * 
     * @param array $definition
     * @return string
     * @since v1.0.0
     */
    public function setAll($definition)
    {
        return $this->definition = $definition;
    }
    
    
    /**
     * Set a definition value
     * 
     * @param string $index
     * @param mixed $value
     * @return Definition
     * @since v1.0.0
     */
    public function set($index, $value)
    {
        $this->definition[$index] = $value;
        return $this;
    }
    
    
    /**
     * Merge a value with the Definition array
     * 
     * @param mixed $value
     * @return Definition
     * @since v1.0.0
     */
    public function merge($value)
    {
        $this->definition = ArrayUtils::merge($this->definition, $value);
        return $this;
    }
    
    
    /**
     * Configure Definition
     * 
     * The Definition is configured using Listeners. A Listener should write
     * or edit the definition values array.
     * 
     * @return void
     * @since v1.0.0
     */
    public function configureDefinition()
    {
        $this->getEventManager()->trigger('configureDefinition', $this);
    }
    
    
    /**
     * Configure Element
     * 
     * This Definition's Element is configured using Listeners. A Listener
     * should read and write to the elementSpecification property or any other
     * part of the definition array (e.g. adding extra validators).
     * 
     * @return void
     * @since v1.0.0
     */
    public function configureElement()
    {
        // Define Element class name with full namespace
        $elementClassName = $this->inferElementClass($this->get('type'));
        $this->set('elementClassName', $elementClassName);
        
        $this->getEventManager()->trigger('configureElement', $this);
        $this->getEventManager()->trigger('configureElement.' . $this->get('elementClassName'), $this);
    }
    
    
    /**
     * Get the Element specification array
     * 
     * @return array
     * @since v1.0.0
     */
    public function getElementSpecification()
    {
        return $this->elementSpecification;
    }
    
    
    /**
     * Set the Element specification array
     * 
     * @param array $spec
     * @return Definition
     * @since v1.0.0
     */
    public function setElementSpecification($spec)
    {
        $this->elementSpecification = $spec;
        return $this;
    }
    
    
    /**
     * Returns the Element instance
     * 
     * @return ElementInterface;
     * @since v1.0.0
     */
    public function getElement()
    {
        if (!is_null($this->element)) {
            return $this->element;
        }
        
        $elementClassName = $this->get('elementClassName');
        
        $this->getEventManager()->trigger('createElement.pre', $this);
        $this->getEventManager()->trigger('createElement.pre.' . $elementClassName, $this);
        
        $formFactory = new FormFactory();
        $this->element = $formFactory->create($this->getElementSpecification());
        
        $this->getEventManager()->trigger('createElement.post.' . $elementClassName, $this);
        $this->getEventManager()->trigger('createElement.post', $this);
        
        return $this->element;
    }
    
    
    /**
     * Set Element
     * 
     * @param ElementInterface $element
     * @return void
     * @since v1.0.0
     */
    public function setElement(ElementInterface $element)
    {
        $this->element = $element;
    }
    
    
    /**
     * Returns a direct access to the $element private property
     * 
     * This is needed because Listeners in "createElement.pre" need to read
     * the element *before* it's created. A that point, calling getElement()
     * would fall into an endless loop.
     * 
     * @return null|ElementInterface;
     * @since v1.0.0
     */
    public function getRawElement()
    {
        return $this->element;
    }
    
    
    /**
     * Configure Filters
     * 
     * Filters are configured by first merging all so there are no duplicates.
     * This merged array contains the filters definitions having the key as
     * the Filter name (e.g. 'striptags').
     * This merged array is saved into the filtersSpecification property.
     * Then Listeners for each Filter class are invoked. Each Listener should
     * lookup into the proper entry in the filtersSpecification array and
     * write its configuration there.
     * 
     * @return void
     * @since v1.0.0
     */
    public function configureFilters()
    {
        $this->getEventManager()->trigger('configureFilters.pre', $this);
        
        $filters = $this->mergeValidatorFilterList($this->get('filters'));
        $this->setFiltersSpecification($filters);
        
        foreach ($filters as $name => $definition) {
            $this->getEventManager()->trigger('configureFilter.' . $name, $this);
        }
        
        $this->getEventManager()->trigger('configureFilters.post', $this);
    }
    
    
    /**
     * Get the Filters specifications array
     * 
     * @return array
     * @since v1.0.0
     */
    public function getFiltersSpecification()
    {
        return $this->filtersSpecification;
    }
    
    
    /**
     * Set the Filters specifications
     * 
     * @param array $spec
     * @return Definition
     * @since v1.0.0
     */
    public function setFiltersSpecification($spec)
    {
        $this->filtersSpecification = $spec;
        return $this;
    }
    
    
    /**
     * Configure Validators
     * 
     * Validators are configured by first merging all so there are no duplicates.
     * This merged array contains the filters definitions having the key as
     * the Validator name (e.g. 'alnum').
     * This merged array is saved into the validatorsSpecification property.
     * Then Listeners for each Validator class are invoked. Each Listener should
     * lookup into the proper entry in the validatorsSpecification array and
     * write its configuration there.
     * 
     * @return void
     * @since v1.0.0
     */
    public function configureValidators()
    {
        $this->getEventManager()->trigger('configureValidators.pre', $this);
        
        $validators = $this->mergeValidatorFilterList($this->get('validators'));
        $this->setValidatorsSpecification($validators);
        
        foreach ($validators as $name => $definition) {
            $this->getEventManager()->trigger('configureValidator.' . $name, $this);
        }
        
        $this->getEventManager()->trigger('configureValidators.post', $this);
        
        return true;
    }
    
    
    /**
     * Get the Validators specifications array
     * 
     * @return array
     * @since v1.0.0
     */
    public function getValidatorsSpecification()
    {
        return $this->validatorsSpecification;
    }
    
    
    /**
     * Set the Validators specifications
     * 
     * @param array $spec
     * @return Definition
     * @since v1.0.0
     */
    public function setValidatorsSpecification($spec)
    {
        $this->validatorsSpecification = $spec;
        return $this;
    }
    
    
    public function getInput()
    {
        if (!is_null($this->input)) {
            return $this->input;
        }
        
        $factory = new InputFilterFactory();
        $this->input = $factory->createInput([
            'name'       => $this->name(),
            'filters'    => $this->getFiltersSpecification(),
            'validators' => $this->getValidatorsSpecification(),
            'required'   => $this->get('required'),
        ]);
        
        return $this->input;
    }
    
    
    /**
     * Merges a Validator or Filter list
     * 
     * The $list parameter can be either an array or a string:
     * 
     *     Array: ['name' => $options]
     *     String: 'name'
     * 
     * This function merges any repeated items, where old values trump new ones.
     * 
     * @param array|string $list
     * @return array
     * @since v1.0.0
     */
    public function mergeValidatorFilterList($list)
    {
        $merged = [];
        
        foreach ($list as $item) {
            if (is_array($item)) {
                $name    = key($item);
                $options = current($item);
                
            } else {
                $name    = $item;
                $options = [];
            }
            
            // If it doesn't look like a FQCN, then lowercase it, in order to
            // prevent erroneus behavior because of different cases
            if (strpos($name, '\\') === false) {
                $name = strtolower($name);
            }
            
            $entry = [
                'name'    => $name,
                'options' => $options,
            ];
            
            if (array_key_exists($name, $merged)) {
                // Items are merged in reverse, that is, old values trump
                // new values.
                $merged[$name] = ArrayUtils::merge($entry, $merged[$name]);
            } else {
                $merged[$name] = $entry;
            }
        }
        
        return $merged;
    }
    
    
    /**
     * Returns a FQCN class name based on the name parameter
     * 
     * If it looks looks like a FQCN, it's returned as is. Else, it's assumed
     * it's a \Zend\Form\Element class and gets that prefix appended.
     * 
     * @param string $name
     * @return string
     * @raise \RuntimeException
     * @since v1.0.0
     */
    public function inferElementClass($name)
    {
        if (empty($name)) {
            throw new \RuntimeException('Class name can\'t be empty for definition "' . $this->name() . '"');
        }
        
        /* Infer Element name. Check if exists on the FormElementManager,
           otherwise, conclude it's a FQCN. */
        $className = $this->getFormElementManager()->getInvokableClassName($name);
        
        if ($className == false) {
            return $name;
        } else {
            return $className;
        }
    }
    
    
    /**
     * Setup Florence's default bundled Listeners
     * 
     * @return void
     * @since v1.0.0
     */
    public function setupDefaultListeners()
    {
        // Configure Definition
        $this->getEventManager()->attach('configureDefinition', ['Florence2\Listener\ConfigureDefinition', 'configure'], 1000);
        
        // Configure Element
        $this->getEventManager()->attach('configureElement', ['Florence2\Listener\ConfigureElement', 'configure'], 1000);
        
        foreach(['Text', 'Checkbox', 'Csrf', 'Select', 'Textarea'] as $element) {
            $this->getEventManager()->attach('configureElement.Zend\\Form\\Element\\' . $element, ['Florence2\Listener\ConfigureElement' . $element, 'configure'], 1000);
        }
        
        // Configure Validator
        $this->getEventManager()->attach('configureValidator.notempty', ['Florence2\Listener\ConfigureValidatorNotEmpty', 'configure'], 1000);
        $this->getEventManager()->attach('configureValidator.stringlength', ['Florence2\Listener\ConfigureValidatorStringLength', 'configure'], 1000);
        $this->getEventManager()->attach('configureValidator.dbrecordexists', ['Florence2\Listener\ConfigureValidatorDBRecordExists', 'configure'], 1000);
        $this->getEventManager()->attach('configureValidator.dbnorecordexists', ['Florence2\Listener\ConfigureValidatorDBNoRecordExists', 'configure'], 1000);
        
        // Create Element
        $this->getEventManager()->attach('createElement.post.Zend\\Form\\Element\Checkbox', ['Florence2\Listener\CreateElementCheckbox', 'post'], 1000);
    }
}

