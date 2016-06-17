<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

use Zend\Stdlib\ArrayUtils;

/**
 * Configure Select Element Listener
 * 
 * @package Florence2
 * @version v1.0.1
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureElementSelect
{
    /**
     * Configure
     * 
     * @return void
     * @since v1.0.0
     */
    static function configure($e)
    {
        $definition = $e->getTarget();
        $specification = $definition->getElementSpecification();
        
        $options = [];
        
        // emptyOption
        try {
            $emptyOption = $definition->get('emptyOption');
            $options['empty_option'] = $emptyOption;
        }
        catch (\OutOfRangeException $e) {
        }
        
        // unselectedValue
        try {
            $unselectedValue = $definition->get('unselectedValue');
            $options['unselected_value'] = $unselectedValue;
        }
        catch (\OutOfRangeException $e) {
        }
        
        $defaults = [
            'options' => $options
        ];
        $specification = ArrayUtils::merge($defaults, $specification);
        
        $definition->setElementSpecification($specification);
    }
}

