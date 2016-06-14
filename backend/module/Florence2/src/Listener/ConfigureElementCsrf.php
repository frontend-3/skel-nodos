<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

use Zend\Stdlib\ArrayUtils;

/**
 * Configure Csrf Element Listener
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class ConfigureElementCsrf
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
            'options' => [
                'timeout' => 3600,
            ],
        ];
        $specification = ArrayUtils::merge($defaults, $specification);
        
        $definition->set('elementSpecification', $specification);
    }
}




