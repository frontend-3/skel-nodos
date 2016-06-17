<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

use Zend\Stdlib\ArrayUtils;

/**
 * Configure NotEmpty Validator Listener
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureValidatorNotEmpty
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
        $specs      = $definition->getValidatorsSpecification();
        
        $defaults = [
            'options' => [
                'messages' => [
                    'isEmpty' => 'Este campo no puede estar vacío',
                ],
            ],
        ];
        $specs['notempty'] = ArrayUtils::merge($defaults, $specs['notempty']);
        
        $definition->setValidatorsSpecification($specs);
    }
}



