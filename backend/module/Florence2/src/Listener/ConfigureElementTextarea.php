<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

use Zend\Stdlib\ArrayUtils;

/**
 * Configure Textarea Element Listener
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureElementTextarea
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
        
        $defaults = [
            'filters'  => [
                'StripTags',
                'StringTrim',
            ],
        ];
        $definition->merge($defaults);
        
        $newAttributes = [];
        
        foreach (['cols', 'rows'] as $attribute) {
            try {
                $newAttributes[$attribute] = $definition->get($attribute);
            }
            catch (\OutOfRangeException $e) {
            }
        }
        
        $specification['attributes'] = ArrayUtils::merge($newAttributes, $specification['attributes']);
        $definition->setElementSpecification($specification);
        
        
    }
}

