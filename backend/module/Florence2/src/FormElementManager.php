<?php
/**
 * This file is part of Florence2 Zend Framework 2 module.
 */

namespace Florence2;

use Zend\Form\FormElementManager as ZendFormElementManager;

/**
 * FormElementManager
 * 
 * Extends Zend's FormElementManager just so we can get access to the
 * invokable names array.
 * 
 * @package Name
 * @version v1.0.1
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */
class FormElementManager extends ZendFormElementManager
{
    /**
     * Get Invokable Class Name
     *
     * @param string $name
     * @return string|false
     * @version v1.0.0
     */
    public function getInvokableClassName($name)
    {
        $name = strtolower($name);
        
        if (array_key_exists($name, $this->invokableClasses)) {
            return $this->invokableClasses[$name];
        } else {
            return false;
        }
    }
}
