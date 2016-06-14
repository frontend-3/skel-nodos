<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2\Listener;

/**
 * Create Element Checkbox Listener
 * 
 * @package Florence2
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class CreateElementCheckbox
{
    /**
     * Post
     * 
     * @return void
     * @since v1.0.0
     */
    static function post($e)
    {
        $definition = $e->getTarget();
        $checkbox   = $definition->getElement();
        
        try {
            $checkbox->setChecked($definition->get('checked'));
        }
        catch (\OutOfRangeException $e) {
        }
    }
}

