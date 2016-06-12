<?php
// Florence 1.0.2
// A fine piece of junk written by Jaime G. Wong <j@jgwong.org>
// for Zend Framework 2.4. See README.md

namespace Florence;

require_once(__DIR__ . '/spyc.php');

class Florence {
    private $name;
    private $options;
    private $controller;
    private $controller_name;
    public  $elements;
    private $form;
    private $inputFilter;
    
    function __construct($controller, $name, $options = Array()) {
    // Parameters:
    // - $controller - is the calling Controller object.
    // - $name - is the name of the form definition file. This file is
    //   expected to be found on module/$a/src/$b/Formdefs/$name.yaml
    // - $options - is an array with options for Florence itself or
    //   Elements, as they need them.
        $this->name       = $name;
        $this->controller = $controller;
        
        $options_defaults = array(
            'autocomplete' => 'off',
        );
        reverse_merge($options, $options_defaults);
        
        $this->options    = $options;
        
        // Get module's name
        preg_match('/\A(.+)?\\\\Controller\\\\(.+?)Controller\Z/', get_class($controller), $match);
        $this->module_name     = $match[1];
        $this->controller_name = $match[2];
        
        $forms_dir = 'module/' . $this->module_name . '/formdefs/';
        
        if(!file_exists($forms_dir)) {
            throw new \Exception('Florence: "Formdefs" directory for module "' . $this->module_name . '" not found!');
        }
        
        $yaml_file = $forms_dir . $name . '.yaml';
        
        if(!file_exists($yaml_file)) {
            throw new \Exception('Florence: "' . $name . '" form definition file for module "' . $this->module_name . '" not found!');
        }
        
        $this->elements = \Spyc::YAMLLoad($yaml_file);
        
        // Load defaults. See directory defaults/{$type}.php
        foreach($this->elements as $name => $element) {
            $file = __DIR__ . '/defaults/' . $element['type'] . '.php';
            if(file_exists($file)) {
                include($file);
            }
            else {
                include(__DIR__ . '/defaults/default.php');
            }
        }
    }
    
    
    public function generate() {
    // Instantiates a new Form object, every Element and every InputFilter.
    // Then the InputFilter is attached to the Form.
        
        // Instantiate Form
        $this->form = new \Zend\Form\Form();
        
        if(isset($this->options['autocomplete'])) {
            $this->form->setAttribute('autocomplete', $this->options['autocomplete']);
        }
        else {
            $this->form->setAttribute('autocomplete', 'off');
        }
        
        foreach($this->elements as $name => $element) {
            $file = __DIR__ . '/elements/' . $element['type'] . '.php';
            if(file_exists($file)) {
                include($file);
            }
            else {
                include(__DIR__ . '/elements/default.php');
            }
        }
        
        // Instantiate InputFilter
        $this->inputFilter = new \Zend\InputFilter\InputFilter();
        
        foreach($this->elements as $name => $element) {
            $if = array(
                'name' => $name,
            );
            
            if(isset($element['required'])) {
                $if['required'] = $element['required'];
            }
            
            // Filters
            $filters = array();
            if(isset($element['filters']) && is_array($element['filters'])) {
                foreach($element['filters'] as $filter) {
                    $filters[] = array('name' => $filter);
                }
            }
            $if['filters'] = $filters;
            
            // Validators
            $validators = array();
            
            if(isset($element['required']) && $element['required'] === true) {
                if(!isset($element['validators'])) {
                    $element['validators'] = array();
                }
                
                if(!in_array('NotEmpty', array_keys($element['validators']))) {
                    $element['validators']['NotEmpty'] = '';
                }
            }
            
            if(isset($element['validators'])) {
                foreach($element['validators'] as $validator_name => $validator) {
                    if(is_array($validator) && isset($validator['options'])) {
                       $options = $validator['options'];
                    }
                    else {
                       $options = array();
                    }
                    
                    $v = __DIR__ . '/validators/' . $validator_name . '.php';
                    
                    if(file_exists($v)) {
                        include($v);
                    }
                    else {
                        throw new \Exception('Florence: Validator "' . $validator_name . '" not found for element "' . $name . '" in form "' . $this->name . '" of controller "' . $this->module_name . '\\' . $this->controller_name . '" not found!');
                    }
                }
            }
            
            $if['validators'] = $validators;
            
            $this->inputFilter->add($if);
        }
        
        // Finally, attach the InputFilter to the Form
        $this->form->setInputFilter($this->getInputFilter());
        
        return $this->form;
    }
    
    
    public function getForm() {
        return $this->form;
    }
    
    
    public function getInputFilter() {
        return $this->inputFilter;
    }
    
    
    // possible gotchas
    // - Fatal error: Class '\Zend\Form\Element\elect' not found in /home/jgwong/nd/dogchow/repos/nestle-dogchow-website/backend/module/Florence/src/Florence/Florence.php on line 148
    
    
    public function load_options_for($element) {
    // Loads an array of options for a Select element by fetching the
    // data from the database. This is done automagically by defining first
    // the proper fields on the YAML.
        $e = $this->elements[$element];
        
        if($e['type'] == 'Select') {
            $table  = $e['model_obj'];
            $r      = $table->all(array($e['table_key'], $e['table_value']));
            $list   = array();
            
            foreach ($r as $k => $v) {
                $list[$v[$e['table_key']]] = $v[$e['table_value']];
            }
            
            $this->elements[$element]['valueOptions'] = $list;
        }
        else {
            throw new \Exception('Florence: Tried to load options for element "' . $element . '", but it\'s not a Select!');
        }
    }
    
}

?>
